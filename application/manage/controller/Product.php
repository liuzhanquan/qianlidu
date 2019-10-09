<?php
namespace app\manage\controller;
use app\common\controller\Admin;
use \think\Model;
use app\manage\model\Goods;
use app\manage\model\Agent;
use app\manage\model\GoodsPhoto;
use app\manage\model\GoodsT;

class Product extends Admin{

	/**
	 * 产品资料管理
	**/
    public function index($state = 0){
    	// 搜索
        $data['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $where = array();
        if($state == '0'){
            $where['status'] = array('in','0,1,2');
        }else if($state == '1'){
            $where['status'] = '1';
        }else if($state == '2'){
            $where['status'] = '0';
        }
        if(!empty($data['key'])){
            $where['title|title_list'] = array('like','%'.$data['key'].'%');
        }
        

        $list = db('goods')->where($where)->order('rank_num desc')->paginate(15,false,['query'=>request()->param()]);
        $this->assign('data',$data);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
    	$this->assign('state',$state);
        return $this->fetch();
    }
	/**
	 * 产品回收站
	**/
	public function recycle(){
        $data['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $where = array();
        $where['status'] = '-1';
        if(!empty($data['key'])){
            $where['title'] = array('like','%'.$data['key'].'%');
        }
       

        $list = db('goods')->where($where)->order('id desc')->paginate(15,false,['query'=>request()->param()]);
        $this->assign('data',$data);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
	}

	/**
	 * 添加、编辑产品
	**/
	public function ProductEdit($id = '0'){
    	if(!isPost()){
            $info = db('goods')->find($id);
            $agent = Agent::with('User')->where(['status'=>1])->select();
            $goodsRecommed = db('goodsRecommed')->select();
            $photo = db('goodsPhoto')->where(['gid'=>$id])->select();
            $goodsTag = db('goodsTag')->select();
            $goodsT = db('goodsT')->where(['gid'=>$id])->select();

            // 获取代理级别
            //$level = db('agent_level')->select();
            //$this->assign('level',$level);
            //$this->assign('agent',$agent);
            $this->assign('info',$info);
            $this->assign('agent',$agent);
            $this->assign('goodsRecommed',$goodsRecommed);
            $this->assign('photo',$photo);
            $this->assign('goodsTag',$goodsTag);
            $this->assign('goodsT',$goodsT);
	        return $this->fetch();
    	}else{
    		$data = $_POST;
            
            $ndata['title']         = $data['title'];
            $ndata['title_list']    = $data['title_list'];
            $ndata['photo']         = $data['photo'];
            $ndata['agent']         = $data['agent'];
            $ndata['show_banner']   = $data['show_banner'];
            $ndata['rid']           = $data['rid'];
            $ndata['start_time']    = strtotime($data['start_time']);
            $ndata['stop_time']     = strtotime($data['stop_time']);
            $ndata['show_time']     = strtotime($data['show_time']);
            $ndata['hide_time']     = strtotime($data['hide_time']);
            $ndata['total_time']    = $data['total_time'];
            $ndata['content']       = $data['content'];
            $ndata['rank_num']      = $data['rank_num'];
            $ndata['update_time']   = time();
            $ndata['status']        = $data['status'];

            if($data['id']){
                $id = $data['id'];
                // 编辑
                
                
                $state = db('goods')->where('id',$id)->update($ndata);



            }else{
                // 添加
                $state = $id = db('goods')->insertGetId($ndata);
            }
            //图片更新
            db('goodsPhoto')->where('gid',$id)->delete();

            if( !empty($data['photo_arr']) ){
                foreach( $data['photo_arr'] as $key=>$item ){
                    $GP_data[$key]['gid'] = $id;
                    $GP_data[$key]['photo'] = $item;
                    $GP_data[$key]['status'] = 1;
                }
                $gp = new GoodsPhoto;
                $gp->saveAll($GP_data);
            }
            
            //路线标签更新
            db('goodsT')->where('gid',$id)->delete();
            if( !empty($data['goodsT']) ){
                foreach( $data['goodsT'] as $key=>$item ){
                    $GT_data[$key]['gid'] = $id;
                    $GT_data[$key]['tid'] = $item;
                    $GT_data[$key]['add_time'] = time();
                }
                $gp = new GoodsT;
                $gp->saveAll($GT_data);
            }


            if($state){
                return $this->success('保存成功',url('index'));
            }else{
                return $this->error('添加失败');
            }
    	}
	}
    /**
     * 处理上下架
    **/
    public function status(){
        if(!isPost()){

            return json(['code'=>0]);
        }else{
            $data = $_POST;
            $state = db('goods')->where('id',$data['id'])->update(['status'=>$data['val']]);
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }
    /**
     * 删除操作
    **/
    public function del(){
        if(!isAjax()){
            return json(['code'=>'0']);
        }else{
            $id = (int) $_POST['id'];
            switch ($_POST['table']) {
                case 'recycle': // 删除至回收站
                    $state = db('goods')->where('id',$id)->update(['status'=>'-1']);
                    $txt = "删除成功！";
                break;
                case 'reback': // 还原商品
                    $state = db('goods')->where('id',$id)->update(['status'=>'0']);
                    $txt = "还原成功！";
                break;
                case 'goods': // 彻底删除
                    $state = db('goods')->where('id',$id)->delete();
                    $txt = "彻底删除成功！";
                break;
            }
            if($state){
                return $this->success($txt);
            }else{
                return $this->error('删除失败');
            }
        }
    }

    public function create($id = '0'){

        if(!isPost()){
            $info = db('goods')->find($id);
            $agent = array();
            if(!empty($info['agent'])){
                $agent = unserialize($info['agent']);
            }
            // 获取代理级别
            //$level = db('agent_level')->select();
            //$this->assign('level',$level);
            //$this->assign('agent',$agent);
            $this->assign('goods',$info);
            return $this->fetch();
        }else{
            $data = $_POST;

            if(isset($data['agent'])){
                $data['agent'] = serialize($data['agent']);
            }
            if($data['id']){
                // 编辑
                $state = db('goods')->where('id',$data['id'])->update($data);
            }else{
                $snExit = db('goods')->where('goods_sn',$data['goods_sn'])->find();
                if(!empty($snExit)){
                    return $this->error('该产品编号已存在，请更换');
                } 
                // 添加
                $state = db('goods')->insertGetId($data);
            }
            if($state){
                return $this->success('保存成功',url('index'));
            }else{
                return $this->error('添加失败');
            }
        }


        return $this->fetch();

    }


}
