<?php  
namespace app\Index\model;
use \app\common\model\Base;

class GoodsRecommed extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    public function sAll(){
        $goods = $this->select();
        return $goods;
    }

}