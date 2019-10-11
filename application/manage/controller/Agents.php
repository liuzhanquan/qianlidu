<?php
namespace app\manage\controller;
use app\common\controller\Admin;
use app\manage\model\Agent;

class Agents extends Admin{

    /**
	 * 代理商列表
    **/
    public function index($id = 1){

        $search = $_GET;
        $searchData['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';
        $searchData['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $searchData['level'] = isset($_GET['level']) ? $_GET['level'] : '-1';
        $searchData['province'] = isset($_GET['province']) ? $_GET['province'] : '-1';
        $searchData['city'] = isset($_GET['city']) ? $_GET['city'] : '-1';
        $searchData['area'] = isset($_GET['area']) ? $_GET['area'] : '-1';
        $where = array();
        $where['status'] = 1;
        if($searchData['province'] !== '-1' && $searchData['city'] !== '-1' && $searchData['area'] !== '-1'){
            $where['b.province'] = $searchData['province'];
            $where['b.city'] = $searchData['city'];
            $where['b.area'] = $searchData['area'];
        }
        if($searchData['level'] !== '-1'){
            $where['b.level_id'] = $searchData['level'];
        }
        // $where['b.second'] = 2;
        if($id == 2){
            $where['status'] = 0;
        }elseif($id == 3){
            $where['status'] = 3;
        }
        // $where['b.second'] = '2';
        switch ($searchData['selkey']) {
            case '1': // 手机号
                if(!empty($searchData['key'])){
                    $whereU['phone'] = array('like','%'.$searchData['key'].'%');
                    $where['uid'] = ['in',db('user')->where($whereU)->column('id')];
                }

            break;
            case '2': // 授权名称
                if(!empty($searchData['key'])){
                    $where['name'] = array('like','%'.$searchData['key'].'%');
                }
            break;
            case '3': // 身份证
                if(!empty($searchData['key'])){
                    $whereU['card_id'] = array('like','%'.$searchData['key'].'%');
                    $where['uid'] = ['in',db('user')->where($whereU)->column('id')];
                }
            break;
        }
        // 
        //$list = db('user a')->join('user_auth b','a.id = b.uid')->order('b.timestamp desc')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $list = Agent::with('UserAll')->where($where)->paginate(20,false,['query'=>request()->param()]);
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
            $state = db('agent')->where($where)->update(['status'=>$data['val']]);
            // $state = db('user')->where('id',$data['id'])->update(['status'=>$data['val']]);
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }

    
    public function douser($id = '0',$type = 'view'){
        if($type == 'view'){
            $info = Agent::with('UserAll')->where('id',$id)->find();

            $this->assign('info',$info);
            return $this->fetch('user_view');
        }else if($type == 'edit'){
            if(!isPost()){
                $info = Agent::with('UserAll')->where('id',$id)->find();

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
                
                if( !empty($data['card_num'])  ){
                    if(!idCard($data['card_num'])){
                     return_ajax("请输入正确的身份证号码",400);
                    }else{
                        $ndata['card_num'] = $data['card_num'];
                    }
                }
                
                if(!phoneNum($data['phone'])) return_ajax("请输入正确的手机号码",400);
                
                db('user')->where(['id'=>$data['uid']])->update(['phone'=>$data['phone']]);
                $ndata['name']      = $data['name'];
                $ndata['level']  = $data['level'];
                $ndata['business']  = $data['business'];
                $ndata['update_time']   = time();
                $ndata['start_time']    = strtotime($data['start_time']);
                $ndata['stop_time']     = strtotime($data['stop_time']);

                $state = db('agent')->where('id',$data['id'])->update($ndata);
                if($state){
                    return $this->success('操作成功',url('index'));
                }else{
                    return $this->error('操作失败！');
                }
            }
        }
    }


    /**
     * 代理商列表
    **/
    public function count($id = 1){
        $search = $_GET;
        $searchData['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';
        $searchData['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $searchData['level'] = isset($_GET['level']) ? $_GET['level'] : '-1';
        $searchData['province'] = isset($_GET['province']) ? $_GET['province'] : '-1';
        $searchData['city'] = isset($_GET['city']) ? $_GET['city'] : '-1';
        $searchData['area'] = isset($_GET['area']) ? $_GET['area'] : '-1';
        $where = array();
        if($searchData['province'] !== '-1' && $searchData['city'] !== '-1' && $searchData['area'] !== '-1'){
            $where['b.province'] = $searchData['province'];
            $where['b.city'] = $searchData['city'];
            $where['b.area'] = $searchData['area'];
        }
        if($searchData['level'] !== '-1'){
            $where['b.level_id'] = $searchData['level'];
        }
        // $where['b.second'] = 2;
        if($id == 2){
            $where['status'] = 0;
        }elseif($id == 3){
            $where['status'] = 3;
        }
        // $where['b.second'] = '2';
        switch ($searchData['selkey']) {
            case '1': // 手机号
                if(!empty($searchData['key'])){
                    $whereU['phone'] = array('like','%'.$searchData['key'].'%');
                    $where['uid'] = ['in',db('user')->where($whereU)->column('id')];
                }

            break;
            case '2': // 授权名称
                if(!empty($searchData['key'])){
                    $where['name'] = array('like','%'.$searchData['key'].'%');
                }
            break;
            case '3': // 身份证
                if(!empty($searchData['key'])){
                    $whereU['card_id'] = array('like','%'.$searchData['key'].'%');
                    $where['uid'] = ['in',db('user')->where($whereU)->column('id')];
                }
            break;
        }
        // 
        //$list = db('user a')->join('user_auth b','a.id = b.uid')->order('b.timestamp desc')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $list = Agent::with('User')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $cardType = db('cardType')->select();
        foreach( $list as $key=>$item ){
            foreach( $cardType as $k=>$i ){
                $list[$key][$i['name'].'1'] = db('agentCard')->where(['gid'=>$item['id'],'card_style'=>$i['id'],'card_type'=>1])->count();
                $list[$key][$i['name'].'2'] = db('agentCard')->where(['gid'=>$item['id'],'card_style'=>$i['id'],'card_type'=>2])->count();
            }
        }

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
            $ndata['status'] = -1; 
            $ndata['update_time'] = time(); 
            $state = db('agent')->where($where)->update($ndata);
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
