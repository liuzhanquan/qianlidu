<?php
namespace app\user\controller;
use app\common\controller\Home;

class Apply extends Home{

	/**
	 * 代理注册
	**/
	public function index(){
		$getData = isset($_GET['v']) ? $_GET['v'] : '';
		if($getData){
			if(!isPost()){
				$vData = unlock_url($getData);
				$vArr = parse_str($vData);
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
				// 如果提交过则提示
				$authorTrue = db('user_auth')->where(['uid'=>$this->user_data['id']])->find();
				if(!empty($authorTrue)){
					if($authorTrue['status'] == 0){
						return $this->redirect(url('show').'?title=啊哦，你已提交过申请');
		                exit();
					}else if($authorTrue['status'] == 1){
						return $this->redirect(url('/user/index'));
		                exit();
					}
				}
				// 查询上级
				$parent = db('user a')->join('user_auth b','a.id = b.uid')->where('a.id',$uid)->find();
				// 更新链接打开次数
				db('user_link')->where('id',$linkInfo['id'])->setInc('open_num');
				// 计算链接失效时间
				$endTime = strtotime($linkInfo['end_time']) - time();
				// 获取地址
				$region = db('region')->where('parent_id','0')->select();
				$this->assign('region',$region);
				// 正常邀请
				$this->assign('endTime',$endTime);
				$this->assign('info',$linkInfo);
				$this->assign('parent',$parent);
				return $this->showtpl('user/apply');
			}else{
				$data = $this->data;
				// 授权信息写入
				$authData = [
					'uid'=>$this->user_data['id'],
					'phone'=>$data['phone'],
					'wechat'=>$data['wechat'],
					'parent_id'=>$data['uid'],
					'auth_name'=>$data['auth_name'],
					'idcard'=>$data['idcard'],
					'province'=>$data['province'],
					'city'=>$data['city'],
					'area'=>$data['area'],
					'address'=>$data['address'],
					'level_id'=>$data['level_id'],
					'level_name'=>$data['level_name'],
					'status'=>'0',
					'timestamp'=>date('Y-m-d H:i:s')
				];
				$userU = [
					'phone'=>$data['phone'],
					'parent_id'=>$data['uid'],
					'level_id'=>$data['level_id'],
				];
				db('user')->where('id',$this->user_data['id'])->update($userU);
				$state = db('user_auth')->insertGetId($authData);
				// 如果上级跟审核上级非同一个代理时
				if($this->user_data['top_parent'] != $this->user_data['parent_id']){
					if($this->user_data['parent_id'] > 0){
						// 发送消息给直推上级
						$msgData = "亲，你有新的下级代理申请\n申请人：".$data['auth_name']."\n手机号：".$data['phone']."\n授权级别：".$data['level_name']."\n申请时间：".date('Y-m-d H:i:s')."\n\n等待审核中！";
						$parent = db('user')->find($this->user_data['parent_id']);
						SendWxMessage($msgData,$parent['openid']);
					}
				}
				// 发送微信消息给上级
				if($data['uid'] > 0){
					$parentInfo = db('user')->find($data['uid']);
	                $authLink = '<a href="'.urlDiy('/user/agent/auditing').'?id='.$state.'">去审核</a>';
					$msgData = "亲，你有下级代理授权申请\n申请人：".$data['auth_name']."\n手机号：".$data['phone']."\n授权级别：".$data['level_name']."\n申请时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
					SendWxMessage($msgData,$parentInfo['openid']);
				}
				// 提交信息后页面信息  恭喜你，提交成功！   <p>请耐心等待上级代理审核</p> 审核通过后即可  前往代理后台
				if($state){
					db('user_link')->where('id',$data['id'])->setInc('apply_num');
					return $this->success('提交成功',url('show'));
				}else{
					return $this->error('系统发生错，请联系管理员');
				}
			}
		}
	}
	public function show(){

		return $this->showtpl('user/success');
	}

}