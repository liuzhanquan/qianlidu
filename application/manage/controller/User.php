<?php
namespace app\manage\controller;
use app\common\controller\Admin;
use app\manage\model\User as UM;

class User extends Admin{

    /**
	 * 用户列表
    **/
    public function index($id = 1){

        $search = $_GET;
        $searchData['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';
        $searchData['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $searchData['level'] = isset($_GET['level']) ? $_GET['level'] : '-1';
        $where = array();
        $where['status'] = 1;
        if($id == 2){
            $where['status'] = 0;
        }elseif($id == 3){
            $where['status'] = 3;
        }elseif($id == -1){
            $where['status'] = -1;
        }
        switch ($searchData['selkey']) {
            case '1': // 手机号
                if(!empty($searchData['key'])){
                    $where['phone'] = array('like','%'.$searchData['key'].'%');
                }

            break;
            case '2': // 用户名
                if(!empty($searchData['key'])){
                    $where['nickname'] = array('like','%'.$searchData['key'].'%');
                }
            break;
            case '3': // 身份证
                if(!empty($searchData['key'])){
                    $where['card_id'] = array('like','%'.$searchData['key'].'%');
                }
            break;
        }
        // 
        //$list = db('user a')->join('user_auth b','a.id = b.uid')->order('b.timestamp desc')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $list = UM::where($where)->paginate(20,false,['query'=>request()->param()]);
        // $region = db('region')->where('parent_id','0')->select();
        // $this->assign('region',$region);
        // $level = db('agent_level')->select();
        // $this->assign('level',$level);
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
            $state = db('user')->where($where)->update(['status'=>$data['val']]);
            // $state = db('user')->where('id',$data['id'])->update(['status'=>$data['val']]);
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }

    /*
	*会员信息修改
	*/
    public function douser($id = '0',$type = 'view'){
        if($type == 'view'){
            $info = UM::where('id',$id)->find();

            $this->assign('info',$info);
            return $this->fetch('user_view');
        }else if($type == 'edit'){
            if(!isPost()){
                $info = UM::where('id',$id)->find();

                $this->assign('info',$info);
                // $region = db('region')->where('parent_id','0')->select();
                // $this->assign('region',$region);
                $this->assign('region',[]);
                // 获取级别
                // $level = db('agent_level')->select();
                // $this->assign('level',$level);
                
                return $this->fetch('user_edit');
            }else{
                $data = $_POST;
                // 修改会员等级
                //$level = db('agent_level')->find($data['level_id']);
                
                if( !empty($data['card_id'])  ){
                    if(!idCard($data['card_id'])){
                     return_ajax("请输入正确的身份证号码",400);
                    }else{
                        $ndata['card_id'] = $data['card_id'];
                    }
                }
                
                if(!phoneNum($data['phone'])) return_ajax("请输入正确的手机号码",400);
                
                $ndata['nickname']      = $data['nickname'];
                $ndata['card_id']      = $data['card_id'];
                $ndata['phone']     	= $data['phone'];
                $ndata['level']  		= $data['level'];
                $ndata['update_time']   = time();
                $ndata['charge_time']    = strtotime($data['charge_time']);

                $state = db('user')->where('id',$data['id'])->update($ndata);
                if($state){
                    return $this->success('操作成功',url('index'));
                }else{
                    return $this->error('操作失败！');
                }
            }
        }
    }



    /**
     * 删除操作
    **/
    public function del(){
        if( isPost() ){
            $data = $_POST;
            if( empty($data['id']) ) return_ajax('非法操作',400);
            if( empty($data['type']) ){
                $where['id'] = $data['id'];
            }else{
                if( $data['type'] == 1 ){
                    $data['id'] = explode(',',$data['id']);
                    $where['id'] = ['in',$data['id']];
                }
            }
			$arr['id'] = ['in',db('user')->where(['status'=>'-1','id'=>$where['id']])->column('id')];
            $ndata['status'] = -1; 
            $ndata['update_time'] = time(); 
            $state = db('user')->where($where)->update($ndata);
			//当状态等于-1时彻底删除
			db('user')->where($arr)->delete();
            if($state){
                return $this->success('删除成功');
            }else{
                return $this->error('操作失败！');
            }

        }else{
            return_ajax('非法操作',400);
        }
    }
	

}
