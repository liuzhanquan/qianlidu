<?php
namespace app\common\controller;
use app\common\controller\Base;

class Test extends Base{

    public function _initialize(){
        dump(1);exit();
        parent::_initialize();
        $this->isAuth();
    }

    protected function index(){
        dump(1312);exit();
    }
    
}