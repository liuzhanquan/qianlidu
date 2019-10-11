<?php
namespace app\manage\model;
use app\common\model\Base;

class User extends Base{
	
	public function agent(){
		return $this->hasOne('agent','uid','id');
	}
	
}