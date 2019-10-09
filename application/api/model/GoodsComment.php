<?php  
namespace app\api\model;
use \app\common\model\Base;

class GoodsComment extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    public function goodsComPhoto(){
        return $this->hasMany('goodsComPhoto','gcid','id');
    }

    public function user(){
    	return $this->hasOne('user','id','uid')->field('id,nickname,avatar');
    }

}