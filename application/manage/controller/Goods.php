<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Goods extends Admin{

    /**
	 * 出货管理
    **/
    public function index(){
        return $this->fetch();
    }

    /**
	 * 退货管理
    **/
    public function reback(){
        return $this->fetch();
    }

    /**
	 * 代理商设定
    **/
    public function groups(){
        return $this->fetch();
    }

    /**
     * 物流管理
    **/
    public function express(){
        return $this->fetch();
    }

    /**
     * 便签物流码替换
    **/
    public function qrcode(){
        return $this->fetch();
    }

    /**
     * 下级出退货
    **/
    public function clearing(){
        return $this->fetch();
    }

    /**
     * 代理零售
    **/
    public function retail(){
        return $this->fetch();
    }

}
