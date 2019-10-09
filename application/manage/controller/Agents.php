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
            $state = db('agent')->where('id',$data['id'])->update(['status'=>$data['val']]);
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
	 * 代理结构树
    **/
    public function tree(){
        return $this->fetch();
    }
    /**
     * 级别扩展
    **/
    public function extend(){
        $list = db('agent_level')->where('is_extends','1')->paginate();
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    /**
     * 代理级别设定
    **/
    public function doextend($id = '0'){
        // 获取最大的级别数
        $max = db('agent_level')->max('level_num');
        $max = $max + 1;
        if(!isPost()){
            $info = db('agent_level')->find($id);
            $list = db('agent_level')->where('is_extends','0')->select();
            $this->assign('info',$info);
            $this->assign('list',$list);
            $this->assign('id',$id);
            return $this->fetch();
        }else{
            $data = $_POST;
            $data['is_extends'] = 1;
            $parent = db('agent_level')->find($data['parent_id']);
            $data['order_rebate'] = $parent['order_rebate'];
            $data['order_money'] = $parent['order_money'];
            $data['rebate'] = $parent['rebate'];
            $data['ddlDelivery'] = $parent['ddlDelivery'];
            $data['recharge'] = $parent['recharge'];
            $data['auditing'] = $parent['auditing'];
            $data['IsCheckOrder'] = $parent['IsCheckOrder'];
            $data['IsCancelCheckedOrder'] = $parent['IsCancelCheckedOrder'];
            if($data['id']){
                $state = db('agent_level')->where('id',$data['id'])->update($data);
            }else{
                $data['level_num'] = $max;
                $state = db('agent_level')->insertGetId($data);
            }
            if($state){
                return $this->success('操作成功',url('extend'));
            }else{
                return $this->error('操作失败！');
            }
        }
    }

    /**
	 * 代理级别设定
    **/
    public function groups(){
        $list = db('agent_level')->where('is_extends','0')->paginate();
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    /**
     * 代理级别设定
    **/
    public function dolevel($id = '0'){
        if(!isPost()){
            // 获取最大的级别数
            $max = db('agent_level')->max('level_num');
            $max = $max + 1;
            $info = db('agent_level')->find($id);
            $this->assign('info',$info);
            $this->assign('max',$max);
            $this->assign('id',$id);
            return $this->fetch();
        }else{
            $data = $_POST;
            if($data['id']){
                $state = db('agent_level')->where('id',$data['id'])->update($data);
            }else{
                $state = db('agent_level')->insertGetId($data);
            }
            if($state){
                return $this->success('操作成功',url('groups'));
            }else{
                return $this->error('操作失败！');
            }
        }
    }
    /**
     * 设置代理授权证书
    **/
    public function settpl($id = ''){
        if(empty($id)){
            $this->redirect(url('groups'));
            exit;
        }
        if(!isPost()){
            $info = db('agent_level')->find($id);
            $position = unserialize($info['position']);
            $data = array();
            if(!empty($position)){
                foreach($position as $k=>$vo){
                    $style = 'position:absolute;';
                    if(!empty($vo['position'])){
                        $posArr = explode(',', $vo['position']);
                        $style .= "left:{$posArr[0]}px;top:{$posArr[1]}px;";
                    }
                    $style .= "font-size:".$vo['size']."px;";
                    $style .= "font-weight:".$vo['bold'].";";
                    $style .= "font-style:".$vo['italic'].";";
                    $style .= "color:".$vo['color'].";";
                    $data[$k]['style'] = $style;
                }
            }
            $this->assign('data',$data);
            $this->assign('position',$position);
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            $udata['position'] = serialize($data);
            $state = db('agent_level')->where('id',$id)->update($udata);
            if($state){
                return $this->success('操作成功',url('groups'));
            }else{
                return $this->error('操作失败！');
            }
        }
    }

    /**
     * 代理授权管理
    **/
    public function auth(){

        $list = db('user_auth a')->join('user b','a.uid = b.id')->field('a.*,b.phone')->where('a.status','1')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());

        $level = db('agent_level')->select();
        $this->assign('level',$level);
        return $this->fetch();
    }

    /**
     * 代理邀请明细
    **/
    public function details($id = '0'){
        $search = $this->data;
        $where['type'] = $id;
        if($id == 0){
            if(isset($search['level']) && $search['level'] > 0){
                $where['level_id'] = $search['level'];
            }
            if(isset($search['state'])){
                if($search['state'] == 0){
                    $where['end_time'] = array('>',date('Y-m-d H:i:s'));
                }else if($search['state'] == 1){
                    $where['end_time'] = array('<',date('Y-m-d H:i:s'));
                }
            }
        }else{
            if(isset($search['level']) && $search['level'] > 0){
                $where['level_id'] = $search['level'];
            }
        }
        $list = db('user_link')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('data',$search);
        $this->assign('page',$list->render());

        $level = db('agent_level')->select();
        $this->assign('level',$level);
        $this->assign('id',$id);
        return $this->fetch();
    }
    public function getLinks(){
        if(!isPost()){
            return json(['code'=>0]);
        }else{
            $data = $_POST;
            $info = db('user_link')->find($data['id']);
            if($data['type'] <> 0){
                $return = [
                    'url'=>$info['local_url']
                ];
            }else{
                $weObj = initWechat();
                $return = [
                    'url'=>$info['local_url'],
                    'qrCode'=>$weObj->getQRUrl($info['ticket']),
                    'time'=>strtotime($info['end_time']) - time()
                ];
            } 
            return json(['code'=>1,'data'=>$return]);       
        }
    }
    /**
     * 添加代理申请链接
    **/
    public function addagent(){
        if(!isPost()){
            // 查询品牌
            $level = db('agent_level')->select();
            $this->assign('brandLevel',$level);
            return $this->fetch();
        }else{
            $data = $_POST;

        }
    }

    public function province(){
        // 获取所有的省份
        $province = db('region')->where('parent_id','0')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$province);
        $this->assign('page',$province->render());        
        return $this->fetch();
    }

    public function city($id = 0){
        // 获取所有的省份
        $city = db('region')->where('parent_id',$id)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$city);
        $this->assign('page',$city->render());        
        return $this->fetch();
    }
    public function agent_list($type = '1',$id = '0'){
        $city = db('agent_city')->where(['area_id'=>$id,'type'=>$type])->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$city);
        $this->assign('page',$city->render());   
        $info = db('region')->find($id);
        $this->assign('info',$info);
        $this->assign('type',$type);
        return $this->fetch();
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
                case 'link':
                    $info = db('user_link')->where('id',$id)->find();
                    if($info['type'] == 0){
                        return $this->error('临时链接不支持删除');
                    }
                    $state = db('user_link')->where('id',$id)->delete();
                break;
                case 'level':
                    $info = db('user')->where('level_id',$id)->find();
                    if(!empty($info)){
                        return $this->error('该级别下已有授权代理不能删除');
                    }
                    $state = db('agent_level')->where('id',$id)->delete();
                break;
                case 'user': // 删除代理
                    db('user')->where('id',$id)->delete();
                    db('user_link')->where('uid',$id)->delete();
                    db('user_order')->where('uid',$id)->delete();
                    db('user_address')->where('uid',$id)->delete();
                    db('user_cart')->where('uid',$id)->delete();
                    db('user_recharge')->where('uid',$id)->delete();
                    db('user_wallet')->where('uid',$id)->delete();
                    $state = db('user_auth')->where('uid',$id)->delete();
                break;
                case 'agent_city':
                    $state = db('agent_city')->where('id',$id)->delete();
                break;
                case 'home':
                    $state = db('home')->where('id',$id)->delete();
                break;
            }
            if($state){
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
        }
    }

}
