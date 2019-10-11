<?php
namespace app\manage\controller;
use app\common\controller\Admin;
use app\manage\model\Order as OM;


class Order extends Admin{

    /**
	 * 订单管理
	**/
    public function index($id = 0){

        $search = $_GET;
        $searchData['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';
        $searchData['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $searchData['start'] = isset($_GET['start']) ? $_GET['start'] : '';
        $searchData['end']   = isset($_GET['end']) ? $_GET['end'] : '';
        $id = isset($_GET['type']) ? $_GET['type'] : $id;
        $where = array();
		
        $where['show'] = 1;
		if($id == 1){
            $where['status'] = 0;
        }elseif($id == 2){
            $where['status'] = 1;
        }elseif($id == 3){
            $where['status'] = 2;
        }elseif($id == 4){
            $where['status'] = 3;
        }elseif($id == 5){
            $where['status'] = 4;
        }elseif($id == 6){
            $where['status'] = 5;
        }elseif($id == 100){
            $where['status'] = 100;
        }
		
        $searchData['status'] = $id;
		
        switch ($searchData['selkey']) {
            case '1': // 订单号
                if(!empty($searchData['key'])){
                    $where['order_num'] = array('like','%'.$searchData['key'].'%');
                    
                }

            break;
            case '2': // 用户名称
                if(!empty($searchData['key'])){
                    $whereU['nickname'] = array('like','%'.$searchData['key'].'%');
					$where['uid'] = ['in',db('user')->where($whereU)->column('id')];
                }
            break;
            case '3': // 用户手机
                if(!empty($searchData['key'])){
                    $whereU['phone'] = array('like','%'.$searchData['key'].'%');
                    $where['uid'] = ['in',db('user')->where($whereU)->column('id')];
                }
            break;
			case '4': // 旅游路线名
                if(!empty($searchData['key'])){
                    $whereU['title|title_list'] = array('like','%'.$searchData['key'].'%');
                    $where['gid'] = ['in',db('goods')->where($whereU)->column('id')];
                }
            break;
        }
		
		if(!empty($searchData['start']) && !empty($searchData['end']) ){
			$where['start_time'][]= array('gt',strtotime($searchData['start']));
			$where['start_time'][]= array('lt',strtotime($searchData['end']));
		}elseif(!empty($searchData['start'])){
			$where['start_time'] = array('lt',strtotime($searchData['end']));
		}elseif(!empty($searchData['end'])){
			$where['start_time'] = array('lt',strtotime($searchData['end']));
		}
		
		
		
        
        $list = OM::where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('data',$searchData);
        $this->assign('id',$id);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    
    public function status(){
        if(!isPost()){

            return json(['code'=>0]);
        }else{
            $data = $_POST;
            if( empty($data['type']) ){
                $where['id'] = $data['id'];
            }else{
                if( $data['type'] == 1 ){
                    $data['id'] = explode(',',$data['id']);
                    $where['id'] = ['in',$data['id']];
                }
            }
            $state = db('order')->where($where)->update(['status'=>$data['val']]);
            // $state = db('user')->where('id',$data['id'])->update(['status'=>$data['val']]);
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }
	
	/*
	*	订单修改
	*/
    public function douser($id = '0',$type = 'view'){
        if($type == 'view'){
            $info = OM::where('id',$id)->find();

            $this->assign('info',$info);
            return $this->fetch('order_view');
        }else if($type == 'edit'){
            if(!isPost()){
                $info = OM::where('id',$id)->find();

                $this->assign('info',$info);
                // $region = db('region')->where('parent_id','0')->select();
                // $this->assign('region',$region);
                $this->assign('region',[]);
                // 获取级别
                // $level = db('agent_level')->select();
                // $this->assign('level',$level);
                
                return $this->fetch('order_edit');
            }else{
                $data = $_POST;
				
				if(empty($data['addr_list'])) return_ajax("请输入详细地址",400);
				if(empty($data['start_time'])) return_ajax("出行时间不能为空",400);
				if(empty($data['add_time'])) return_ajax("下单时间不能为空",400);
				if(empty($data['aduly'])) return_ajax("成人数量不能为空",400);
				//db('user')->where(['id'=>$data['uid']])->update(['phone'=>$data['phone']]);
				
                $ndata['addr_list']     = $data['addr_list'];
                $ndata['aduly']      	= $data['aduly'];
                $ndata['baby']     		= $data['baby'];
                $ndata['plane']     	= $data['plane'];
                $ndata['car']     		= $data['car'];
                $ndata['butlers']     = $data['butlers'];
                $ndata['update_time']   = time();
                $ndata['start_time']    = strtotime($data['start_time']);
                $ndata['add_time']    	= strtotime($data['add_time']);

                $state = db('order')->where('id',$data['id'])->update($ndata);
                if($state){
                    return $this->success('操作成功',url('index'));
                }else{
                    return $this->error('操作失败！');
                }
            }
        }
    }
	
	

}