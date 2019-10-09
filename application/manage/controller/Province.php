<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Province extends Admin{


    /**
	 * 省市代区域列表
    **/
    public function index(){
        return $this->fetch();
    }
}