<?php
namespace app\user\controller;
use app\common\controller\User;

class Province extends User{

	public function index(){

		return $this->showtpl('user/Province/index');
	}

	/**
	 * 查看地区代理
	**/
	public function agent(){
		// 判断代理是否为省代
		$userProvice = db('agent_city')->where(['agent_id'=>$this->user_data['id'],'type'=>'1'])->find();
		$cityList = array();
		if(!empty($userProvice)){
			$cityList = db('region')->where('parent_id',$userProvice['area_id'])->select();
		}
		$userCity = db('agent_city')->where(['agent_id'=>$this->user_data['id'],'type'=>'2'])->find();
		$userList = array();
		if(!empty($userCity)){
			$userList = db('agent_city')->where(['area_id'=>$userCity['area_id']])->paginate(20,false,['query'=>request()->param()]);
			$this->assign('list',$userList);
			$this->assign('page',$userList->render());
		}
		$this->assign('cityList',$cityList);
		$this->assign('userProvice',$userProvice);
		$this->assign('userCity',$userCity);
		return $this->showtpl('user/Province/agent');
	}
	
	public function invite(){
		if(!isPost()){
			$where['is_extends'] = 1;
			$level = db('agent_level')->where($where)->select();
			$this->assign('level',$level);
			return $this->showtpl('user/Province/invite');
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
            	return $this->success('生成成功',url('agent/showLink').'?id='.$state);
            }else{
            	return $this->error('生成失败');
            }
		}
		
	}

	/**
	 * 省代查看市级下代理
	**/
	public function level_user($id = 0){
		$info = db('region')->where('id',$id)->find();
		$this->assign('info',$info);
		$userList = db('agent_city')->where(['area_id'=>$id])->paginate(20,false,['query'=>request()->param()]);
		$this->assign('list',$userList);
		$this->assign('page',$userList->render());
		return $this->showtpl('user/Province/level_user');
	}
	
	public function money(){

		return $this->showtpl('user/Province/money');
	}
}