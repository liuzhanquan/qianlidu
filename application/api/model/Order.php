<?php  
namespace app\api\model;
use \app\common\model\Base;

class Order extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    public function goods(){
    	return $this->hasOne('goods','id','gid')->field('id,title,title_list,tips,total_time,photo');
    }

    public function user(){
    	return $this->hasOne('user','id','uid')->field('id,phone');
    }

}