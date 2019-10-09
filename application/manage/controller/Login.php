<?php
namespace app\manage\controller;
use app\common\controller\Base;

class Login extends Base{

	public function _initialize(){
		parent::_initialize();
		$this->view->engine->layout(false);
	}
	
	public function index(){
		if(isPost()){
            $userName = $this->data['username'];
            $passWord = $this->data['password'];
            if (empty($userName) || empty($passWord)) {
                return $this->error('用户名或密码未填写！');
            }
            if(model('Admin')->setLogin($this->data)){
                return $this->success('登录系统成功！', url('/'));
            }else{
                return $this->error(model('Admin')->getError());
            }
		}else{
			$cookie_admin = cookie('admin_cookie');
			if ($cookie_admin){
				$this->redirect(url("/"));
				exit;
			} else {
				return $this->fetch();
			}
		}
	}

	public function out(){
    	$cookie_admin = cookie('admin_cookie');
        if(!empty($cookie_admin)){
			cookie('admin_cookie',null);
			if(!isAjax()){
	            $this->redirect(url('/login'));
	            exit;
			}else{
	            return $this->success('退出成功',url('/login'));
			}
        } else {
            $this->redirect(url('/login'));
            exit;
        }
	}
	
	// 检测是否登录超时
	public function loginLosetime(){
		$logintime = cookie('login_time');
		$time = request()->time();
		if($time > $logintime){
			return json(['code'=>1,'msg'=>'登录超时！','url'=>url('/login')]);
		}else{
			return json(['code'=>0]);
		}
	}
}