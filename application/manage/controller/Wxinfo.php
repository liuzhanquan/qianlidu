<?php
namespace app\manage\controller;
use app\common\controller\Base;

class Wxinfo extends Base{


	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
		cookie('adminwx_cookie','3');
		return $this->fetch();	
	}
}