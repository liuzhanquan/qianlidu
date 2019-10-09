<?php
namespace app\user\controller;
use app\common\controller\User;

class Agent extends User{

	/**
	 * 新代理加盟
	**/
	public function addagent(){
		if(!isPost()){
			$where['id'] = array('>=',$this->user_data['level_id']);
			$where['is_extends'] = 0;
			$level = db('agent_level')->where($where)->select();
			$this->assign('level',$level);
			return $this->showtpl('user/Agent/addagent');
		}else{
			$data = $this->data;
			$levelId = unlock_url($this->data['level']);
			$levelIds = trim($levelId);
			$weObj = initWechat();
            $return['time'] = 48*60;
            $inData['end_time'] = date('Y-m-d H:i:s',strtotime("+ 48 hours"));
            $inData['level_id'] = $levelIds;
            $inData['uid'] = $this->user_data['id'];
            $inData['type'] = 0;
            $inData['timestamp'] = date('Y-m-d H:i:s');
            $state = db('user_link')->insertGetId($inData);
            // 二维码包含信息
            $qrcodeStr = "uid=".$this->user_data['id'].'&link='.$state;
            $key = 'apply_'.lock_url($qrcodeStr);  //lock_url  unlock_url
            $ticket = $weObj->getQRCode($key,'3',172800); // 换取微信生成Ticket
            $return['qrCode'] = $weObj->getQRUrl($ticket['ticket']);
            // 
            $formUrl = urlDiy('/user/auth/register',['uid'=>$this->user_data['id'],'type'=>0,'id'=>$state]);
            db('user_link')->where('id',$state)->update(['local_url'=>$formUrl,'ticket'=>$ticket['ticket'],'url'=>$weObj->getQRUrl($ticket['ticket'])]);
            $return['url'] = $formUrl;
            $return['id'] = $state;
            if($state){
            	return $this->success('生成成功',url('showLink').'?id='.$state);
            }else{
            	return $this->error('生成失败');
            }
		}
	}
	/**
	 * 生成链接
	**/
	public function showLink(){
		$id = isset($_GET['id']) ? $_GET['id'] : '0';
		if($id <> 0){
			$info = db('user_link')->find($id);
			// 获取品牌信息
			$level = db('agent_level')->where('id',$info['level_id'])->value('name');
            $return = [
                'url'=>$info['local_url'],
                'qrCode'=>$info['url'],
                'time'=>strtotime($info['end_time']) - time(),
                'logo'=>$this->config['brand_logo'],
                'brand'=>$this->config['brand_name'].','.$level
            ];
			$this->assign('info',$return);
			return $this->showtpl('user/Agent/showLink');
		}else{
			return $this->redirect(url('/user/index'));
	        exit();
		}
	}
	/**
	 * 查看邀请记录
	**/
	public function linkLog($id = '0'){
		// 统计总生成链接数量
		$returnData = array();
		$returnData['userNum'] = db('user_link')->where('uid',$this->user_data['id'])->count();
		$returnData['open_num'] = db('user_link')->where('uid',$this->user_data['id'])->sum('open_num');
		$returnData['apply_num'] = db('user_link')->where('uid',$this->user_data['id'])->sum('apply_num');
		$where = array();
		if($id <> 0){
			// 已经过期
			$where['end_time'] = array('<',date("Y-m-d H:i:s"));
		}else{
			// 未过期
			$where['end_time'] = array('>',date("Y-m-d H:i:s"));
		}
		$where['uid'] = $this->user_data['id'];
		$list = db('user_link')->where($where)->paginate(20,false,['query'=>request()->param()]);

        $this->assign('list',$list);
        $this->assign('page',$list->render());
		$this->assign('returnData',$returnData);
		$this->assign('id',$id);
		return $this->showtpl('user/Agent/linkLog');
	}

	/**
	 * 跨级推荐
	**/
	public function cross(){
		if(!isPost()){
			$level = db('agent_level')->where('id','<',$this->user_data['level_id'])->select();
			$this->assign('level',$level);
			return $this->showtpl('user/Agent/addagent');
		}else{
			$data = $this->data;
			if(!isset($data['level'])){
				return $this->error('请选择推荐代理级别');
			}
			$levelId = unlock_url($this->data['level']);
			$levelIds = trim($levelId);
			$weObj = initWechat();
            $return['time'] = 48*60;
            $inData['end_time'] = date('Y-m-d H:i:s',strtotime("+ 48 hours"));
            $inData['level_id'] = $levelIds;
            $inData['uid'] = $this->user_data['id'];
            $inData['type'] = 0;
            $inData['timestamp'] = date('Y-m-d H:i:s');
            $state = db('user_link')->insertGetId($inData);
            // 二维码包含信息
            $qrcodeStr = "uid=".$this->user_data['id'].'&link='.$state;
            $key = 'apply_'.lock_url($qrcodeStr);  //lock_url  unlock_url
            $ticket = $weObj->getQRCode($key,'3',172800); // 换取微信生成Ticket
            $return['qrCode'] = $weObj->getQRUrl($ticket['ticket']);
            // 
            $formUrl = urlDiy('/user/auth/register',['uid'=>$this->user_data['id'],'type'=>0,'id'=>$state]);
            db('user_link')->where('id',$state)->update(['local_url'=>$formUrl,'ticket'=>$ticket['ticket'],'url'=>$weObj->getQRUrl($ticket['ticket'])]);
            $return['url'] = $formUrl;
            $return['id'] = $state;
            if($state){
            	return $this->success('生成成功',url('showLink').'?id='.$state);
            }else{
            	return $this->error('生成失败');
            }
		}
	}

	/**
	 * 代理审核
	**/
	public function auditor($type = 0){
		$id = isset($_GET['id']) ? $_GET['id'] : '1';
		if($type == 0){
			$where['parent_id'] = $this->user_data['id'];
			$where['status'] = 0;
		}else if($type == 1){
			$where['top_parent'] = $this->user_data['id'];
			$where['status'] = 0;
		}else if($type == 2){
			$where['parent_id'] = $this->user_data['id'];
			$where['status'] = 2;
		}
		$where['type'] = $id;
		$data['shouquan'] = db('user_auth')->where(['parent_id'=>$this->user_data['id'],'type'=>'1','status'=>'0'])->count();
		$data['update'] = db('user_auth')->where(['parent_id'=>$this->user_data['id'],'type'=>'2','status'=>'0'])->count();
		$list = db('user_auth')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
		$this->assign('data',$data);
		$this->assign('id',$id);
		$this->assign('type',$type);
		return $this->showtpl('user/Agent/auditor');
	}
	public function auditing(){
		$id = isset($_GET['id']) ? $_GET['id'] : '0';
		if($id <> 0){
			if(!isPost()){
				$info = db('user_auth')->where('id',$id)->find();
	            if($info['status'] == '1'){
	                return $this->redirect(url('auditor'));
	                exit();
	            }
	            $this->assign('info',$info);
				return $this->showtpl('user/Agent/auditing');
			}else{
				$data = $_POST;
	            $info = db('user_auth')->where('id',$data['id'])->find();
	            // 判断是否审核通过
	            if($data['status'] == '2'){
	                if(empty($data['remark'])){
	                    return $this->error('请输入审核不通过原因/备注');die;
	                }
	                db('user')->where('id',$info['uid'])->update(['phone'=>'']);
	                // 审核不通过
	                db('user_auth')->where('id',$data['id'])->delete();
	                $msgData = "亲，你的代理授权申请审核不通过！\n审核人：".getUser($info['parent_id'],'auth_name')."\n审核时间：".date('Y-m-d H:i:s')."\n原因：".$data['remark']."\n\n申请人：".getUser($info['uid'],'auth_name')."\n微信号：".getUser($info['uid'],'wechat')."\n手机号：".getUser($info['uid'],'phone')."\n\n申请级别：\n".$info['brand_name'].'-'.$info['level_name'];
	                $openId = getUser($info['uid'],'openid');
	                SendWxMessage($msgData,$openId);

	                return $this->success('处理成功',url('apply'));
	            }else if($data['status'] == '1'){
	                // 写入到授权信息
	                $level = db('agent_level')->find($info['level_id']);
	                if($level['auditing'] != '1'){
	                	return $this->error('您没有审核权限哦~');die;
	                }
                	// 查询申请入驻订单总金额
                	$levelRatio = db('agent_level')->where('id',$this->user_data['level_id'])->value('rebate');
                	$orderMoney = db('user_order')->where(['uid'=>$info['uid'],'is_reg'=>'1'])->find();
                	$levelOrderMoney = $orderMoney['original_price'] * $levelRatio / 10;
                	// 由上级审核，验证款项
                	if($levelOrderMoney > $this->user_data['money']){
                		return $this->error('账户款项不足'.$levelOrderMoney.'元，请先充值');die;
                	}
	                // 计算发放佣金推荐
	                $reward = new \lib\Reward;
	                $reward->push($info['uid']);
	                $reward->same($info['uid']);
	                // 扣除更高级代理账户货款
	                $endMoney = $this->user_data['money'] - $levelOrderMoney;
	                db('user')->where('id',$this->user_data['id'])->update(['money'=>$endMoney]);
	                // 写入业绩记录
	                db('performance')->insertGetId([
	                	'agent_id'=>$this->user_data['id'],
	                	'form_id'=>$info['uid'],
	                	'money'=>$levelOrderMoney,
	                	'timestamp'=>date('Y-m-d H:i:s'),
	                ]);
	                $auth_number = '';
	                $numberLen = strlen($this->config['brand_number']);
	                $maxNumber = db('user_auth')->max('auth_number');
	                $auth_number = str_pad(($maxNumber+1),$numberLen,'0',STR_PAD_LEFT);
	                $authData = [
	                    'status'=>1,
	                    'auth_number'=>$auth_number,
	                    'start_time'=> date('Y-m-d H:i:s'),
	                    'end_time'=> date('Y-m-d H:i:s',strtotime("+".$level['txtMounth']." month")),
	                ];
	                db('user_auth')->where('id',$data['id'])->update($authData);
	                $state = db('user')->where('id',$info['uid'])->update(['status'=>'1']);

	                if($state){
	                    $authLink = '<a href="'.urlDiy('/user/index').'">前往代理后台</a>';
	                    $msgData = "亲，你的代理授权申请审核通过啦！\n审核人：".getUser($info['parent_id'],'auth_name')."\n审核时间：".date('Y-m-d H:i:s')."\n\n申请人：".getUser($info['uid'],'auth_name')."\n微信号：".getUser($info['uid'],'wechat')."\n手机号：".getUser($info['uid'],'phone')."\n\n申请级别：".$info['level_name']."\n\n".$authLink;
	                    
	                    $openId = getUser($info['uid'],'openid');
	                    SendWxMessage($msgData,$openId);
	                    return $this->success('审核成功',url('auditor'));
	                }else{
	                    return $this->error('操作失败');
	                }
	            }
			}
		}else{
			return $this->redirect(url('auditor'));
	        exit();
		}
	}

	/**
	 * 代理审核
	**/
	public function team(){
		$list = db('agent_level')->where('is_extends','0')->select();
		$this->assign('list',$list);
		return $this->showtpl('user/Agent/team');
	}
	/**
	 * 代理审核
	**/
	public function level_user($id = 0){
		$info = db('agent_level')->find($id);
		$this->assign('info',$info);
		$where['a.parent_id'] = $this->user_data['id'];
		$where['a.status'] = '1';
		$list = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
		return $this->showtpl('user/Agent/level_user');
	}
	/**
	 * 代理审核
	**/
	public function user_down($id = 0){
		$info = db('user_auth')->where('uid',$id)->find();
		$this->assign('info',$info);
		$where['a.parent_id'] = $id;
		$where['a.status'] = '1';
		$list = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
		return $this->showtpl('user/Agent/user_down');
	}
	/**
	 * 代理审核
	**/
	public function view_user(){
		$id = isset($_GET['id']) ? $_GET['id'] : "0";
		$where['a.id'] = $id;
		$info = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->find();
		$this->assign('info',$info);
		$list = db('agent_level')->select();
		$this->assign('list',$list);
		return $this->showtpl('user/Agent/view_user');
	}

	/**
	 * 我的推荐
	**/
	public function recommend($type = 0){
		$where['a.parent_id'] = $this->user_data['id'];
		$where['a.status'] = $type;
		$list = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        $num = [
        	'1'=>db('user a')->join('user_auth b','a.id = b.uid')->where(['a.parent_id'=>$this->user_data['id'],'a.status'=>0])->count(),
        	'2'=>db('user a')->join('user_auth b','a.id = b.uid')->where(['a.parent_id'=>$this->user_data['id'],'a.status'=>1])->count(),
        	'3'=>db('user a')->join('user_auth b','a.id = b.uid')->where(['a.parent_id'=>$this->user_data['id'],'a.status'=>2])->count(),
        ];
		$this->assign('num',$num);
		$this->assign('type',$type);
		return $this->showtpl('user/Agent/recommend');
	}
	/**
	 * 查看推荐人信息
	**/
	public function view($id = 0){
		$info = db('user a')->join('user_auth b','a.id = b.uid')->where(['a.id'=>$id])->find();
		$this->assign('info',$info);
		return $this->showtpl('user/Agent/recommend_view');
	}

	/**
	 * 代理政策
	**/
	public function policy($type = '0'){
		$selfLevel = $this->user_data['level_id'];
		// 直推奖励
		$recommend = unserialize($this->config['recommend']);
		$recommendList = array();
		if(isset($recommend[$selfLevel])){
			$recommendList = $recommend[$selfLevel];
		}
		$this->assign('recommendList',$recommendList);
		// 升级设置
		$upgrade = unserialize($this->config['upgrade']);
		$upgradeList = array();
		if(isset($upgrade[$selfLevel])){
			$upgradeList = $upgrade[$selfLevel];
		}
		$this->assign('upgradeList',$upgradeList);
		// 平级奖励
		$same = unserialize($this->config['same']);
		$sameList = array();
		if(isset($same[$selfLevel])){
			$sameList = $same[$selfLevel];
		}
		$this->assign('sameList',$sameList);
		// 销售奖金【业绩奖励】
		$team = unserialize($this->config['team']);
		$teamList = array();
		if(isset($team['reward'][$selfLevel])){
			$teamList = $team['reward'][$selfLevel];
		}
		$this->assign('teamList',$teamList);
		$this->assign('type',$type);
		return $this->showtpl('user/Agent/policy');
	}

}