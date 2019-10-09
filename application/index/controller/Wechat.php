<?php
namespace app\index\controller;
use app\common\controller\Base;

class Wechat extends Base{

	public $open_id;
	public $wxuser;

	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
		$weObj = initWechat();
		$weObj->valid();
		$type = $weObj->getRev()->getRevType();
		switch($type) {
			case "text":
				$weObj->text("hello, I'm wechat")->reply();
			break;
			case "image":

			break;
			case "event":
				$key = $weObj->getRev()->getRevEvent();
				$formKey = $key['key'];
				switch ($key['event']) {
					case 'CLICK':
						$keyContent = db('wechat_navs')->where('name',$formKey)->value('content');
						$weObj->text($keyContent)->reply();
					break;
					case 'SCAN': // 扫码操作
					case 'subscribe': // 扫码关注事件
						if(!empty($formKey)){
							// 判断是扫码关注后发送消息还是直接扫码发送
							$formKeyArr = explode('_', $formKey);
							if(isset($formKeyArr[0])){
								switch ($formKeyArr[0]) {
									case 'apply':
										// 扫描二维码推送消息
										$msgObj = new \wechat\Msg;
										$msgData = $msgObj->getUserLink($this->config['sitename'],$formKeyArr[1]);
										if($msgData){
											$weObj->news($msgData)->reply();
										}
									break;
									case 'qrscene':
										// 扫码记录
										switch ($formKeyArr[1]) {
											case 'apply':
												// 扫描二维码推送消息
												$msgObj = new \wechat\Msg;
												$msgData = $msgObj->getUserLink($this->config['sitename'],$formKeyArr[2]);
												if($msgData){
													$weObj->news($msgData)->reply();
												}
											break;
										}
									break;							
								}
							}
						}else{
							// 正常关注
							
						}
					break;
					case 'unsubscribe': // 取消关注
					break;
				}
			break;
			default:
				$weObj->text("help info")->reply();
			break;
		}
	}

	public function wxoauth(){
		$backUrl = isset($_GET['url']) ? $_GET['url'] : '';
		$config = unserialize($this->config['wechat']);
        $option = [
            'token'             =>  isset($config['token']) ? $config['token'] : 'weixintoken',
            'appid'             =>  $config['appid'],
            'appsecret'         =>  $config['appsecret'],
            'encodingaeskey'    =>  $config['keycode'],
        ];
        
		$auth = new \wechat\Wxauth($option);
		$wxuser = $auth->wxuser;
		// 查询会员是否存在
		$userOpenId = db('user')->where('openid',$wxuser['open_id'])->find();
		if(empty($userOpenId)){
			// 新会员加入
			$maxId = db('user')->max('id'); 
			$inData = [
				'nickname'=>$wxuser['nickname'],
				'openid'=>$wxuser['open_id'],
				'avatar'=>$wxuser['avatar'],
				'reg_time'=>date('Y-m-d H:i:s'),
				'login_time'=>date('Y-m-d H:i:s'),
				'only_code'=>substr(strtoupper(sha1($maxId)),0,4),
				'status'=>'0',
			];
			$uid = db('user')->insertGetId($inData);
			cookie('user_cookie',$uid);
		}else{
			// 更新会员信息
			$inData = [
				'nickname'=>$wxuser['nickname'],
				'openid'=>$wxuser['open_id'],
				'avatar'=>$wxuser['avatar'],
				'reg_time'=>date('Y-m-d H:i:s'),
				'login_time'=>date('Y-m-d H:i:s')
			];
			db('user')->where('id',$userOpenId['id'])->update($inData);
			cookie('user_cookie',$userOpenId['id']);
		}
		if($backUrl){
			return $this->redirect ( $backUrl ); die;
		}
	}
}