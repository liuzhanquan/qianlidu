<?php
namespace app\user\controller;
use app\common\controller\User;

class Data extends User{

	public function stock(){

		return $this->showtpl('user/Data/stock');
	}
	
	public function achievement(){

		return $this->showtpl('user/Data/achievement');
	}
	
	/**
	 * 结算记录
	**/
	public function settlement(){

		return $this->showtpl('user/Data/settlement');
	}
}