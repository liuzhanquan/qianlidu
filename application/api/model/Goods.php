<?php  
namespace app\api\model;
use \app\common\model\Base;

class Goods extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    public function sAll(){
        $goods = $this->select();
        return $goods;
    }


    public function goodsRecommed(){
        return $this->hasOne('goodsRecommed','id','rid')->field('id,name');
    }

    public function goodsT(){
        return $this->hasMany('goodsT','gid','id');
    }

    public function goodsCollect(){
        return $this->hasMany('goodsCollect','gid','id')->field('gid,add_time,uid')->order('add_time desc')->limit(3);
    }

    public function goodsPhoto(){
        return $this->hasMany('goodsPhoto','gid','id')->where('status',1);
    }

}