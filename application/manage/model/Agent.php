<?php
namespace app\manage\model;
use app\common\model\Base;

class Agent extends Base{
	
	public function User(){
		return $this->hasOne('User','id','uid')->field('id,nickname');
	}

	public function UserAll(){
		return $this->hasOne('User','id','uid');
	}

}