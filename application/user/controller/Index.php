<?php
namespace app\user\controller;
use app\common\controller\User;

class Index extends User{

    public function index(){        
        // 获取后台设置代理后台功能
        
        $agentMenu = json_decode($this->config['jsondata'],true);
        
        $this->assign('menu',$agentMenu);
        
        return $this->showtpl('user/index');
    }
}
