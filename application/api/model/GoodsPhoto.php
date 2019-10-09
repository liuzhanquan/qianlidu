<?php  
namespace app\api\model;
use \app\common\model\Base;

class GoodsPhoto extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    public function sAll(){
        $goods = $this->select();
        return $goods;
    }

}