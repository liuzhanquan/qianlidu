<?php  
namespace app\api\model;
use \app\common\model\Base;

class GoodsCollect extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    public function sAll(){
        $goods = $this->select();
        return $goods;
    }

}