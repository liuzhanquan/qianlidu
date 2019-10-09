<?php
namespace app\manage\controller;
use app\common\controller\Base;

class Wxoauth extends Base{

	public $open_id;
	public $wxuser;

	public function _initialize(){
		parent::_initialize();
	}


	public function index(){
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
		$userOpenId = db('admin_wechat')->where('openid',$wxuser['open_id'])->find();
		if(empty($userOpenId)){
			// 新会员加入
			return $this->gmError('该账号未授权','请在管理后台扫码进行授权后方可登录');die;
		}else{
			// 更新会员信息
			$inData = [
				'nickname'=>$wxuser['nickname'],
				'openid'=>$wxuser['open_id'],
				'avatar'=>$wxuser['avatar'],
				'login_time'=>date('Y-m-d H:i:s')
			];
			db('admin_wechat')->where('id',$userOpenId['id'])->update($inData);
			cookie('adminwx_cookie',$userOpenId['id']);
		}
		if($backUrl){
			return $this->redirect ( $backUrl ); die;
		}
	}
}