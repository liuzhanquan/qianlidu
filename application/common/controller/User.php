<?php
namespace app\common\controller;
use app\common\controller\Home;

class User extends Home{

    public function _initialize(){
        
        parent::_initialize();
        $this->isAuth();
    }

    protected function isAuth(){
        // 检测会员是否为代理
        
        $authorization = db('user_auth')->where('uid',$this->user_data['id'])->find();
        if(empty($authorization)){
            return $this->redirect(url('/index/index/errors'));die;
        }
        if($authorization['status'] != '1'){
            return $this->redirect(url('/index/index/errors'));die;
        }
    }

}