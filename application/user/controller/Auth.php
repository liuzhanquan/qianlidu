<?php
namespace app\user\controller;
use app\common\controller\Home;

class Auth extends Home{

	/**
	 * 代理登陆
	**/
	public function login(){
		if(!isPost()){

			return $this->showtpl('user/login');
		}else{
			$data = $_POST;
			if(!captcha_check($data['captcha'])){
				return $this->error('验证码错误！');
	        }
	        $userInfo = db('user')->where(['phone'=>$data['phone']])->find();
	        if (empty($userInfo)) {
				return $this->error('该代理不存在');
	        }
	        if ($userInfo['status'] == 0) {
				return $this->error('请耐心等待审核');
	        }
	        if ($userInfo['status'] == 2) {
				return $this->error('该代理已被冻结');
	        }
	        if(!sp_compare_password($data['password'],$userInfo['password'])){
	            return $this->error('账号密码错误');
	        }
	        $loginInfo = [
	            'id'=>$userInfo['id'],
	            'login_time'=>date('Y-m-d H:i:s'),
	        ];
	        $editState = db('user')->where('id',$userInfo['id'])->update($loginInfo);
	        if(!$editState){
	            return $this->error('登录失败！');
	        }else{
	        	return $this->success('登录成功',url('/user/index'));
	        }
		}
	}
	/**
	 * 代理注册
	**/
	public function register($type = 'level'){
		// 是否有推荐
		$parent = isset($_GET['v']) ? $_GET['v'] : '';
		if(!empty($parent)){
			$urlInfo = unlock_url($parent);
			parse_str($urlInfo);
			$linkInfo = db('user_link')->find($id);
			if(!empty($linkInfo)){
				$endTime = strtotime($linkInfo['end_time']);
				if(time() >= $endTime){
					// 链接失效
					return $this->GmError('哦，链接失效了！','请让上级代理重新生成邀请链接');die;
				}
			}else{
				return $this->GmError('邀请链接参数错误！','请让上级代理重新生成邀请链接');die;
			}
			// 查询当前会员是否已授权
			if(empty($this->auth_user)){
				// 更新上级
				$selfLevel = db('agent_level')->find($linkInfo['level_id']);
				db('user_link')->where('id',$linkInfo['id'])->setInc('open_num');
				$parentInfo = db('user a')->join('agent_level b','a.level_id = b.id')->field('a.*,b.level_num')->where('a.id',$uid)->find();
				$userUpData = array();
				if(!empty($parentInfo)){
					if(empty($parentInfo['parent_path'])){
						$userUpData['parent_path'] = $parentInfo['only_code'].'_'.$parentInfo['level_num'];
					}else{
						$userUpData['parent_path'] = $parentInfo['only_code'].'_'.$parentInfo['level_num'].','.$parentInfo['parent_path'];
					}
					// 查询会员更高级别的上级
					if(!empty($userUpData['parent_path'])){
						
						// $parent_path = $this->user_data['id'].'_'.$this->userLevel['level_num'].','.$userUpData['parent_path'];

						// $parentPath = explode(',', $parent_path);
						$parentPath = explode(',', $userUpData['parent_path']);
						if(!empty($parentPath)){
							foreach($parentPath as $k=>$vo){
								$parentPathLevel = explode('_', $vo);
								if($parentPathLevel[1] < $selfLevel['level_num']){
									$topParentId = db('user')->where('only_code',$parentPathLevel[0])->value('id');
									$userUpData['top_parent'] = $topParentId;
								}
							}
						}
					}

				}else{
					$userUpData['parent_path'] = "0_99";
					$userUpData['top_parent'] = "0";
				}
				$userUpData['parent_id'] = $uid;
				$userUpData['level_id'] = $linkInfo['level_id'];
				db('user')->where('id',$this->user_data['id'])->update($userUpData);
				return $this->redirect(url('/user/auth/register',['type'=>'goods']).'?level='.lock_url($linkInfo['level_id']));
                exit();
			}else{
				if($this->auth_user['status'] == 0){
					return $this->redirect(url('showmsg').'?title=啊哦，你已提交过申请');
	                exit();
				}else if($this->auth_user['status'] == 1){
					return $this->redirect(url('/user/index'));
	                exit();
				}
			}
		}
		switch ($type) {
			case 'level': // 选择代理级别
				$level = db('agent_level')->select();
				$this->assign('level',$level);
			break;
			case 'goods': // 选择代理代理产品
				if(!isset($_GET['level'])){
					return $this->redirect(url('register'));
	                exit();
				}
				$levelId = unlock_url($_GET['level']);
				cookie('levelId',trim($levelId));
				$level = db('agent_level')->find(trim($levelId));
				// 获取所有代理产品
				$goods = db('goods')->where('status','1')->select();
				$goodsArr = array();
				if(!empty($goods)){
					foreach($goods as $k2=>$vo){
						$agentMoney = unserialize($vo['agent']);
						$levelIds = trim($levelId);
						$goodsArr[$k2] = $vo;
						$goodsArr[$k2]['agent_price'] =  isset($agentMoney[$levelIds]) ? $agentMoney[$levelIds] : '0';
					}
				}
				$this->assign('use_level',$level);
				$this->assign('goods',$goodsArr);
			break;
			case 'savegoods': // 选择代理代理产品
				if(!isPost()){
					return json(['code'=>0]);
				}else{
					$data = $_POST;
					// 取得产品的ID已经下单数量
					if(empty($data)){
						return json(['code'=>0]);
					}
					$levelId = cookie('levelId');
					$orderData = array();
					$orderPrice = 0;
					foreach($data as $k=>$vo){
						$dataInfo = explode(',', $vo);
						$orderData[$k]['id'] = $dataInfo[0];
						$orderData[$k]['num'] = $dataInfo[1];
						$orderData[$k]['price'] = $dataInfo[2];
						$orderPrice += $dataInfo[2] * $dataInfo[1];
					}
					$levelPrice = db('agent_level')->where('id',$levelId)->find();
					if($levelPrice['order_money'] > ($orderPrice * $levelPrice['rebate'] / 10)){
						return json(['code'=>0,'msg'=>'折后总金额必须大于'.number_format($levelPrice['order_money'],0).'元']);
					}
					// 临时存储产品信息
					cookie('order',$orderData);
					cookie('orderMoney',($orderPrice * $levelPrice['rebate'] / 10));
					return json(['code'=>1,'url'=>url('register',['type'=>'profile'])]);
				}
			break;
			case 'profile': // 填写代理详细信息
				if(!isPost()){
					$levelId = cookie('levelId');
					$orderData = cookie('order');
					if(empty($orderData)){
						return $this->redirect(url('register'));
		                exit();
					}
					$levelInfo = db('agent_level')->find($levelId);
					$orderMoney = cookie('orderMoney');
					// 获取地址
					$region = db('region')->where('parent_id','0')->select();
					$this->assign('levelInfo',$levelInfo);
					$this->assign('orderMoney',$orderMoney);
					$this->assign('region',$region);
				}else{
					$data = $_POST;
					// 验证手机号码是否注册
					$phoneExit = db('user')->where('phone',$data['phone'])->find();
					if(!empty($phoneExit)){
						return $this->error('该手机号码已被注册');
					}
					// if(!idCard($data['idcard'])){
					// 	return $this->error('身份证号不正确');
					// }
					$idExit = db('user_auth')->where('idcard',$data['idcard'])->find();
					if(!empty($idExit)){
						return $this->error('该身份证号已被注册');
					}
					$levelId = cookie('levelId');
					$level = db('agent_level')->find($levelId);
					// 更新会员信息
					$userData = [
						'phone'=>$data['phone'],
						'code'=>substr(strtoupper(sha1($data['phone'])),0,8),
						'level_id'=>$level['id'],
					];
					// 检测是否提交推荐码
					if(isset($data['code']) && !empty($data['code'])){
						$codeUser = db('user')->where('code',$data['code'])->find();
						if(empty($codeUser)){
							return $this->error('该邀请码不存在');
						}
						$userData['parent_id'] = $codeUser['id'];
					}
					db('user')->where('id',$this->user_data['id'])->update($userData);
					$addressArr = [
						'uid'=>$this->user_data['id'],
						'name'=>$data['auth_name'],
						'phone'=>$data['phone'],
						'province'=>$data['province'],
						'city'=>$data['city'],
						'area'=>$data['area'],
						'address'=>$data['address'],
						'is_true'=>'1',
						'timestamp'=>date('Y-m-d H:i:s')
					];
					db('user_address')->insertGetId($addressArr);
					// 写入特殊奖励前提记录，以市为单位判断是否为第一人代理
					$goodsList = cookie('order');
					if(!empty($goodsList)){
						$orderId = $this->user_data['id'].time().rand(00,99);
						$orderNum = $orderMoney = 0;
						foreach($goodsList as $vo){
							$specilData = array();
							$specilData = [
								'order_id'=>$orderId,
								'goods_id'=>$vo['id'],
								'num'=>$vo['num'],
								'price'=>$vo['price'],
								'status'=>'0',
								'timestamp'=>date('Y-m-d H:i:s')
							];
							db('user_order_detail')->insertGetId($specilData); // 入驻申请代理产品
							$orderNum += $vo['num'];
							$orderMoney += $vo['num'] * $vo['price'];
						}
						// 写入订单
						$orderData = [
							'uid'=>$this->user_data['id'],
							'order_id'=>$orderId,
							'original_price'=>$orderMoney,
							'price'=>($orderMoney * $level['rebate'] / 10),
							'num'=>$orderNum,
							'is_reg'=>1,
							'goods'=>serialize($goodsList),
							'address'=>serialize($addressArr),
							'status'=>1,
							'timestamp'=>date('Y-m-d H:i:s')
						];
						db('user_order')->insertGetId($orderData);
					}
					// 写入授权信息
					$authData = array();
					$authData = [
						'uid'=>$this->user_data['id'],
						'parent_id'=>$this->user_data['parent_id'],
						'top_parent'=>$this->user_data['top_parent'],
						'auth_name'=>$data['auth_name'],
						'phone'=>$data['phone'],
						'wechat'=>$data['wechat'],
						'idcard'=>$data['idcard'],
						'province'=>$data['province'],
						'city'=>$data['city'],
						'area'=>$data['area'],
						'address'=>$data['address'],
						'level_id'=>$level['id'],
						'level_name'=>$level['name'],
						'money_img'=>$data['money_img'],
						'card_img'=>isset($data['card_img']) ? $data['card_img'] : '',
						'status'=>'0',
						'timestamp'=>date('Y-m-d H:i:s')
					];
					// 查询等级由谁审核
					if($this->user_data['parent_id'] == 0){
						// 公司审核
						$authData['second'] = 2;
					}
					if($level['auditing'] == '3'){
						// 公司审核
						$authData['second'] = 2;
					}
					$state = db('user_auth')->insertGetId($authData);
					if($state){
						if($level['auditing'] != '3'){
							if($this->user_data['top_parent'] > 0){
								$parentInfo = db('user')->find($this->user_data['top_parent']);
				                $authLink = '<a href="'.urlDiy('/user/agent/auditing').'?id='.$state.'">去审核</a>';
								$msgData = "亲，你有下级代理授权申请\n申请人：".$data['auth_name']."\n手机号：".$data['phone']."\n授权级别：".$level['name']."\n申请时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
								SendWxMessage($msgData,$parentInfo['openid']);
							}
						}
						// db('user_link')->where('id',$data['id'])->setInc('apply_num');
						return $this->success('提交成功',url('showmsg'));
						// return $this->success('提交成功',url('register',['type'=>'money']).'?id='.lock_url($state));
					}else{
						return $this->error('提交失败');
					}
				}
			break;
			case 'money': // 上传打款凭证
				if(!isPost()){
					$authId = isset($_GET['id']) ? unlock_url($_GET['id']) : '0';
					if($authId == 0){
						return $this->redirect(url('register'));
		                exit();
					}
					$authInfo = db('user_auth')->find($authId);
					if(empty($authInfo)){
						return $this->redirect(url('register'));
		                exit();
					}
					$this->assign('authInfo',$authInfo);
					$this->assign('bank',unserialize($this->config['bank']));
				}else{
					$data = $_POST;
					$state = db('user_auth')->where('id',$data['id'])->update($data);
					if($state){
						return $this->success('提交成功，请耐心等待审核',url('showmsg'));
					}else{
						return $this->error('提交失败');
					}
				}
			break;
		}
		$this->assign('type',$type);
		return $this->showtpl('user/register');
	}
	/**
	 * 代理找回密码
	**/
	public function forget(){
		if(!isPost()){

			return $this->showtpl('user/forget');
		}else{
			$data = $_POST;
		}
	}
	/**
	 * 等待审核或审核未通过显示
	**/
	public function showmsg(){
		return $this->showtpl('user/success');
	}

}