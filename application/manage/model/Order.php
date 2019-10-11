<?php
namespace app\manage\model;
use app\common\model\Base;

class Order extends Base{
	
	public function goods(){
		return $this->hasOne('goods','id','gid');
	}
	
	public function user(){
		return $this->hasOne('user','id','uid');
	}
	
}