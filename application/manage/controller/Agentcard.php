<?php
namespace app\manage\controller;
use app\common\controller\Admin;
use app\manage\model\AgentCard as AG;
use app\manage\model\Agent;

class Agentcard extends Admin{

    /**
	 * 出货管理
    **/
    public function index($id = 1){

        $search = $_GET;
        $searchData['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';
        $searchData['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $searchData['type'] = isset($_GET['type']) ? $_GET['type'] : '';
        $searchData['style'] = isset($_GET['style']) ? $_GET['style'] : '';
        $where = array();
        // $where['b.second'] = 2;
        if($id == 2){
            $where['card_state'] = 1;
        }elseif($id == 3){
            $where['card_state'] = 0;
        }elseif($id == 4){
            $where['card_type'] = 2;
        }elseif($id == 5){
            $where['card_type'] = 1;
        }elseif($id == 6){
            $where['state'] = 3;
        }elseif($id == 7){
            $where['state'] = 1;
        }
        $order = [];
        $order[] = 'id asc'; 
        switch ($searchData['selkey']) {
            case '1': // 手机号
                if(!empty($searchData['key'])){
                    $whereU['phone'] = array('like','%'.$searchData['key'].'%');
                    $where['gid'] = ['in',db('user')->alias('u')->join('agent a','a.uid=u.id')->where($whereU)->field('a.id')->column('a.id')];
                    
                }

            break;
            case '2': // 会员卡号
                if(!empty($searchData['key'])){
                    $where['card_num'] = array('like','%'.$searchData['key'].'%');
                }
            break;
            case '3': // 身份证
                if(!empty($searchData['key'])){
                    $whereU['card_id'] = array('like','%'.$searchData['key'].'%');
                    $where['gid'] = ['in',db('user')->alias('u')->join('agent a','a.uid=u.id')->where($whereU)->field('a.id')->column('a.id')];
                }
            break;
        }
        if(!empty($searchData['type'])){
            $where['card_type'] = $searchData['type'];
        }
        if(!empty($searchData['style'])){
            $where['card_style'] = $searchData['style'];
        }



        $list = db('agentCard')->where($where)->order($order)->paginate(20,false,['query'=>request()->param()]);
        $cardType = db('cardType')->select();
        //dump($list);exit();
        //dump($list);exit();
        $this->assign('id',$id);
        $this->assign('data',$searchData);
        $this->assign('list',$list);
        $this->assign('cardType',$cardType);
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
            $state = db('agentCard')->where($where)->update(['state'=>$data['val']]);
            // $state = db('user')->where('id',$data['id'])->update(['status'=>$data['val']]);
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }


    public function list($id = '0',$type = 'view'){
        if($type == 'view'){
            $info = AG::where('id',$id)->find();

            $agent = Agent::with('userAll')->where('id',$info['gid'])->field('name,uid,start_time,stop_time,level')->find();
            $this->assign('agent',$agent);

            $cardType = db('cardType')->select();
            $this->assign('info',$info);
            $this->assign('cardType',$cardType);
            return $this->fetch('card_view');
        }else if($type == 'edit'){
            if(!isPost()){
                $info = AG::where('id',$id)->find();
                $agent = Agent::with('userAll')->where('status',1)->field('id,name,uid')->select();
                $cardType = db('cardType')->select();

                //将会员卡充值时间变数字
                $info['charge_time_num'] = mb_substr(charge_time(time() + $info['charge_time']),0,-2);

                $this->assign('agent',$agent);
                $this->assign('info',$info);
                $this->assign('cardType',$cardType);

                return $this->fetch('card_edit');
            }else{
                $data = $_POST;

                $ndata['update_time']   = time();
                $ndata['start_time']    = strtotime($data['start_time']);
                $ndata['stop_time']     = strtotime($data['stop_time']);

                if( !empty($data['id']) ){

                    $ndata['card_num']      = $data['card_num'];
                    $ndata['password']      = $data['password'];
                    $ndata['card_type']     = $data['card_type'];
                    $ndata['print_status']  = $data['print_status'];
                    $ndata['card_style']    = $data['card_style'];
                    $ndata['up_num']        = $data['up_num'];
                    $ndata['gid']           = $data['gid'];
                    $ndata['state']         = $data['state'];
                    $ndata['charge_time']   = $data['charge_time']*$data['charge_type'];

                    $state = db('agentCard')->where('id',$data['id'])->update($ndata);
                }
                
                
                if($state){
                    return $this->success('操作成功',url('index'));
                }else{
                    return $this->error('操作失败！');
                }
            }
        }
    }


    public function add(){
        if(isPost()){
            $data = $_POST;

            if( empty($data['num']) )          $this->error('请填写创建数量');
            if( empty($data['card_type']) )    $this->error('请选择卡片类型');
            if( empty($data['card_style']) )   $this->error('请选择卡片样式');
            if( empty($data['start_time']) )   $this->error('请填写使用时间');
            if( empty($data['stop_time']) )    $this->error('请填写使用时间');
            if( empty($data['charge_time']) )  $this->error('请填写激活使用后增加会员时间');
            $ndata = makeAgentNum($data['card_type'],$data['card_style'],$data['num'],strtotime($data['start_time']),strtotime($data['stop_time']),$data['charge_time']*$data['charge_type'],$data['gid']);
            $ag = new AG;
            $state = $ag->saveAll($ndata);
            
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败！');
            }
        }else{

            $agent = Agent::with('userAll')->where('status',1)->field('id,name,uid')->select();
            $this->assign('agent',$agent);
            
            $cardType = db('cardType')->select();
            $this->assign('cardType',$cardType);

            return $this->fetch();
        }
        
    }

    public function cardtype(){


        $list = db('cardType')->select();
        
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function typeedit( $id=0 ){
        if( !isPost() ){
            $info = db('cardType')->where('id',$id)->find();

            $this->assign('info',$info);

            return $this->fetch();
        }else{
            $data = $_POST;
            
            $ndata['update_time'] = time();
            $ndata['name'] = $data['name'];
            $ndata['title'] = $data['title'];
            if( !empty($data['id']) ){
                $state = db('cardType')->where('id',$data['id'])->update($ndata);
            }else{
                $ndata['add_time'] = time();
                $state = db('cardType')->insertGetId($ndata);
            }

            if($state){
                return $this->success('操作成功',url('cardtype'));
            }else{
                return $this->error('操作失败！');
            }
        }

    }

    public function del(){
        if( isPost() ){
            $id = $_POST['id'];
            if( empty($id) ) return_ajax('非法操作',400);
            $state = db('cardType')->delete($id);
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
