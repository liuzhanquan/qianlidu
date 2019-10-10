<?php
namespace app\manage\model;
use app\common\model\Base;

class GoodsComment extends Base{
	
	public function goods(){
		return $this->hasOne('goods','id','gid')->field('id,title');
	}
	
	public function user(){
		return $this->hasOne('user','id','uid')->field('id,nickname,avatar');
	}

}