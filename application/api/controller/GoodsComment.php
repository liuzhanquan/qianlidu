<?php
namespace app\api\controller;
use app\common\controller\Home;
use app\api\model\Goods;
use app\api\model\GoodsComment as GC;
class GoodsComment extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    public function index(){

        empty(input('page')) ? $page = 1 : $page = input('page');
        empty(input('limit')) ?  $limit = 5 : $limit = input('limit');
        empty(input('id')) ? return_ajax('数据错误',400) : $id=input('id');
        $data = GC::with('goodsComPhoto,user')->where(['gid'=>$id,'status'=>1])->order('update_time desc')->limit(($page-1)*$limit,$limit)->select();


        return_ajax('成功！',200,$data);

    }

}
