<?php
namespace app\api\controller;
use app\common\controller\Home;
use app\api\model\Goods;
use app\api\model\GoodsComment as GC;
use app\api\model\GoodsComPhoto as GCP;
class GoodsComment extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    public function index(){

        empty(input('page')) ? $page = 1 : $page = input('page');
        empty(input('limit')) ?  $limit = 5 : $limit = input('limit');
        empty(input('id')) ? return_ajax('数据错误',400) : $id=input('id');
        $data = GC::with('goodsComPhoto,user')->where(['gid'=>$id,'status'=>1])->order(['rank_num desc','update_time desc'])->limit(($page-1)*$limit,$limit)->select();


        return_ajax('成功！',200,$data);

    }
	
	public function add(){
		$data = input();
		empty($data['goods_id']) ? return_ajax('路线iD不能为空',400) : $ndata['gid']=$data['goods_id'];
		empty($data['content']) ? return_ajax('评论内容不能为空',400) : $ndata['content']=$data['content'];
		empty($data['score']) ? $ndata['score']=0 : $ndata['score']=$data['score'];
		$userInfo = $this->userInfo();
		$ndata['uid'] = $userInfo['id'];
		$ndata['status'] = 1;
		$ndata['add_time'] = time();
		$ndata['update_time'] = time();
		
		$effect = db('goodsComment')->insertGetId($ndata);
		if( !empty($data['photo']) ){
			foreach( $data['photo'] as $key=>$item ){
				$pdata[$key]['gcid'] = $effect;
				$pdata[$key]['photo'] = $item;
			}
			
			$GCP = new GCP;
			$GCP->saveAll($pdata);
		}
        if( $effect ){
            return_ajax('提交成功',200);
        }else{
            return_ajax('提交失败',400);
        }
		
	}
	
	

}
