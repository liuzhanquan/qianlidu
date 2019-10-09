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
                    $where['idcard'] = array('like','%'.$searchData['key'].'%');
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
    /**
     * 代理续期
    **/
    public function aloneauth($id = 0){
        $this->view->engine->layout('api_layout');
        if($id <> 0){
            if(!isPost()){
                $authInfo = db('user_auth')->find($id);
                $this->assign('info',$authInfo);
                return $this->fetch();
            }else{
                $data = $_POST;
                if($data['long'] < 1 || $data['long'] > 36){
                    return $this->error('续期时间范围1-36');die;
                }
                $authInfo = db('user_auth')->find($data['id']);
                $uData['end_time'] = date("Y-m-d H:i:s", strtotime("+".$data['long']." month", strtotime($authInfo['end_time'])));
                $state = db('user_auth')->where('id',$data['id'])->update($uData);
                if($state){
                    return $this->success('操作成功','','1');
                }else{
                    return $this->error('操作失败','','1');
                }
            }
        }else{
            return json(['code'=>0]);
        }
    }
    /**
     * 审核代理商
    **/
    public function auditing($id = 0){
        $this->view->engine->layout('api_layout');
        if(!isPost()){
            $info = db('user a')->join('user_auth b','a.id = b.uid')->where(['a.id'=>$id])->find();
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            $info = db('user a')->join('user_auth b','a.id = b.uid')->where(['a.id'=>$data['id']])->find();
            if($info['status'] == '2'){
                // 由上级审核后总部二次审核
                if($data['status'] == '1'){
                    // 审核通过
                    $authData = [
                        'status'=>1,
                        'second'=>1
                    ];
                    $state = db('user_auth')->where('uid',$data['id'])->update($authData);
                    db('user')->where('id',$data['id'])->update(['status'=>'1']);
                    if($state){
                        $authLink = '<a href="'.urlDiy('/user/index').'">前往代理后台</a>';
                        $msgData = "亲，你的代理授权申请总部审核通过啦！\n审核时间：".date('Y-m-d H:i:s')."\n\n申请人：".getUser($info['uid'],'auth_name')."\n微信号：".getUser($info['uid'],'wechat')."\n手机号：".getUser($info['uid'],'phone')."\n\n申请级别：".$info['level_name']."\n\n".$authLink;
                        
                        $openId = getUser($info['uid'],'openid');
                        SendWxMessage($msgData,$openId);
                        return $this->success('操作成功','','1');
                    }else{
                        return $this->error('操作失败','','1');  
                    }
                }else if($data['status'] == '2'){
                    // 拒绝审核
                    if(empty($data['remark'])){
                        return $this->error('请输入审核不通过原因/备注');die;
                    }
                    // 拒绝审核，并删除所有授权数据
                    // 发送相关短信
                    $msgData_1 = "亲，你的代理授权申请审核不通过！\n审核人：总部\n审核时间：".date('Y-m-d H:i:s')."\n原因：".$data['remark']."\n\n申请人：".$info['auth_name']."\n微信号：".$info['wechat']."\n手机号：".$info['phone']."\n申请级别：".$info['level_name'];
                    SendWxMessage($msgData_1,$info['openid']);
                    // 退还代理金额给上级
                     return $this->success('操作成功','','1');
                    $agent_profit = db('agent_profit')->where('form_id',$data['id'])->find();
                    $agentLevel = db('agent_level')->find($info['level_id']);
                    $endMoney = $agentLevel['money'] - $agent_profit['money'];
                    $parentTop = db('user')->find($info['top_parent']);
                    db('user')->where('id',$parentTop['id'])->setInc('money',$endMoney);


                    $state = db('user_auth')->where('uid',$data['id'])->delete();
                    db('user')->where('id',$data['id'])->delete();
                    db('agent_profit')->where('id',$agent_profit['id'])->delete();
                    db('user_link')->where('uid',$data['id'])->delete();
                    db('user_order')->where('uid',$data['id'])->delete();
                    db('user_address')->where('uid',$data['id'])->delete();
                    db('user_cart')->where('uid',$data['id'])->delete();
                    db('user_recharge')->where('uid',$data['id'])->delete();
                    db('user_wallet')->where('uid',$data['id'])->delete();
                    db('agent_log')->where('agent_id',$data['id'])->delete();
                    db('performance')->where('form_id',$data['id'])->delete(); // 删除上级业绩
                    db('agent_log')->where('form_id',$data['id'])->delete(); // 删除奖金记录

                    // db('user')->where('id',$data['id'])->update(['status'=>'0','phone'=>'','password'=>'','level_id'=>'0','money'=>'0']);
                    if($state){
                        return $this->success('操作成功','','1');
                    }else{
                        return $this->error('操作失败','','1');
                    }
                }
            }else{
                if($data['status'] == '1'){
                    // 审核通过
                    // 获取级别信息
                    $level = db('agent_level')->find($info['level_id']);
                    $orderMoney = db('user_order')->where(['uid'=>$info['uid'],'is_reg'=>'1'])->find();
                    $orderFirstMoney = $level['order_rebate'] * $orderMoney['original_price'] / 10;
                    $auth_number = '';
                    $numberLen = strlen($this->config['brand_number']);
                    $maxNumber = db('user_auth')->max('auth_number');
                    $auth_number = str_pad(($maxNumber+1),$numberLen,'0',STR_PAD_LEFT);
                    // 发放奖励
                    $authData = [
                        'status'=>1,
                        'auth_number'=>$auth_number,
                        'start_time'=> date('Y-m-d H:i:s'),
                        'end_time'=> date('Y-m-d H:i:s',strtotime("+".$level['txtMounth']." month")),
                    ];
                    $state = db('user_auth')->where('uid',$data['id'])->update($authData);
                    db('user')->where('id',$data['id'])->update(['status'=>'1']);
                    // 更新被审核人账户余额
                    $infoUser = db('user')->where('id',$info['uid'])->find();
                    // 计算发放佣金推荐
                    $reward = new \lib\Reward;
                    $reward->push($info['uid']);
                    $userLevel = db('agent_level')->find($info['level_id']);
                    $reward->same($info['uid'],$userLevel['money'],'1');
                    // 业绩计算
                    if($info['parent_id'] > 0){
                        $parent = db('user')->find($info['parent_id']);
                        $parentLevel = db('agent_level')->find($parent['level_id']);
                        if($info['level_id'] == $parent['level_id']){
                            // 首单推荐业绩
                            db('performance')->insertGetId([
                                'agent_id'=>$parent['id'],
                                'form_id'=>$info['uid'],
                                'type'=>'1',
                                'money'=>$level['money'],
                                'timestamp'=>date('Y-m-d H:i:s')
                            ]);
                             db("test")->insertGetId(['content'=>$level['money']]);
                            $reward->teamreward($parent['id'],$level['money']);
                        }
                    }

                    $infoUserMoney = $infoUser['money'] + ($level['money'] - $orderMoney['price']);
                    db('user')->where('id',$infoUser['id'])->update(['money'=>$infoUserMoney]);

                    db('agent_log')->insertGetId([
                        'agent_id'=>$infoUser['id'],
                        'money'=>$orderMoney['price'],
                        'type'=>'5',
                        'remark'=>'下单支出',
                        'timestamp'=>date('Y-m-d H:i:s')
                    ]);

                    if($state){
                        if($info['top_parent'] != $info['parent_id']){
                            $parentId = $info['parent_id'];
                        }else{
                            $parentId = $info['top_parent'];
                        }
                        if($parentId > 0){
                            $parentLink = '<a href="'.urlDiy('/user/agent/team').'">查看我的团队</a>';
                            $parentMsg = "亲，你的下级代理授权申请审核通过啦！\n审核人：".getUser($info['top_parent'],'auth_name')."\n审核时间：".date('Y-m-d H:i:s')."\n\n申请人：".getUser($info['uid'],'auth_name')."\n微信号：".getUser($info['uid'],'wechat')."\n手机号：".getUser($info['uid'],'phone')."\n\n申请级别：".$info['level_name']."\n\n".$parentLink;
                            $parentOpenId = getUser($parentId,'openid');
                            SendWxMessage($parentMsg,$parentOpenId);
                        }

                        $authLink = '<a href="'.urlDiy('/user/index').'">前往代理后台</a>';
                        $msgData = "亲，你的代理授权申请审核通过啦！\n审核人：".getUser($info['top_parent'],'auth_name')."\n审核时间：".date('Y-m-d H:i:s')."\n\n申请人：".getUser($info['uid'],'auth_name')."\n微信号：".getUser($info['uid'],'wechat')."\n手机号：".getUser($info['uid'],'phone')."\n\n申请级别：".$info['level_name']."\n\n".$authLink;
                        
                        $openId = getUser($info['uid'],'openid');
                        SendWxMessage($msgData,$openId);
                        return $this->success('操作成功','','1');
                    }else{
                        return $this->error('操作失败','','1');
                    }

                }else if($data['status'] == '2'){
                    if(empty($data['remark'])){
                        return $this->error('请输入审核不通过原因/备注');die;
                    }
                    $msgData = "亲，你的代理授权申请审核不通过！\n审核人：".getUser($info['top_parent'],'auth_name')."\n审核时间：".date('Y-m-d H:i:s')."\n原因：".$data['remark']."\n\n申请人：".getUser($info['uid'],'auth_name')."\n微信号：".getUser($info['uid'],'wechat')."\n手机号：".getUser($info['uid'],'phone')."\n申请级别：".$info['level_name'];
                    // echo $msgData.$info['openid'];
                    SendWxMessage($msgData,$info['openid']);
                    // 拒绝审核，并删除所有授权数据
                    // 发送相关短信
                    $state = db('user_auth')->where('uid',$data['id'])->delete();
                    db('user')->where('id',$data['id'])->delete();
                    db('user_link')->where('uid',$data['id'])->delete();
                    db('user_order')->where('uid',$data['id'])->delete();
                    db('user_address')->where('uid',$data['id'])->delete();
                    db('user_cart')->where('uid',$data['id'])->delete();
                    db('user_recharge')->where('uid',$data['id'])->delete();
                    db('user_wallet')->where('uid',$data['id'])->delete();
                    // db('user')->where('id',$data['id'])->update(['status'=>'0','phone'=>'','password'=>'','level_id'=>'0','money'=>'0']);
                    if($state){
                        return $this->success('操作成功','','1');
                    }else{
                        return $this->error('操作失败','','1');
                    }
                }
            }
        }
    }
    /**
     * 充值处理
    **/
    public function option($id = 0){
        $this->view->engine->layout('api_layout');
        if(!isPost()){
            $info = db('user a')->join('user_auth b','a.id = b.uid')->field('a.*,b.auth_name')->where(['a.id'=>$id])->find();
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $this->data;
            $info = db('user')->find($data['id']);
            if($data['type'] == '1'){
                // 增加货款
                $userMoney = $info['money'] + $data['money'];
                $typeTxt = "货款余额";
                $state = db('user')->where('id',$data['id'])->update(['money'=>$userMoney]);
            }else if($data['type'] == '2'){
                // 增加奖金池金额
                $userMoney = $info['reward_money'] + $data['money'];
                $typeTxt = "奖余额";
                $state = db('user')->where('id',$data['id'])->update(['reward_money'=>$userMoney]);
            }
            if($state){
                db('option_log')->insertGetId([
                    'uid'=>$info['id'],
                    'money'=>$data['money'],
                    'user_money'=>$userMoney,
                    'type'=>$data['type'],
                    'remark'=>$data['remark'],
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);

                $msgData = "亲，你的账户".$typeTxt."发生变化\n处理人：总部\n备注：".$data['remark']."\n时间：".date('Y-m-d H:i:s');
                SendWxMessage($msgData,$info['openid']);
                return $this->success('处理成功','','1');
            }else{
                return $this->error('处理失败','','1');
            }
            
        }
    }
    /**
     * 扣款处理
    **/
    public function option_s($id = 0){
        $this->view->engine->layout('api_layout');
        if(!isPost()){
            $info = db('user a')->join('user_auth b','a.id = b.uid')->field('a.*,b.auth_name')->where(['a.id'=>$id])->find();
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            $info = db('user')->find($data['id']);
            if($data['type'] == '1'){
                // 增加货款
                $userMoney = $info['money'] - $data['money'];
                $typeTxt = "货款余额";
                $state = db('user')->where('id',$data['id'])->update(['money'=>$userMoney]);
            }else if($data['type'] == '2'){
                // 增加奖金池金额
                $userMoney = $info['reward_money'] - $data['money'];
                $typeTxt = "奖余额";
                $state = db('user')->where('id',$data['id'])->update(['reward_money'=>$userMoney]);
            }
            if($state){
                db('option_log')->insertGetId([
                    'uid'=>$info['id'],
                    'money'=>$data['money'],
                    'user_money'=>$userMoney,
                    'type'=>$data['type'],
                    'remark'=>$data['remark'],
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);

                $msgData = "亲，你的账户".$typeTxt."发生变化\n处理人：总部\n备注：".$data['remark']."\n时间：".date('Y-m-d H:i:s');
                SendWxMessage($msgData,$info['openid']);
                return $this->success('处理成功','','1');
            }else{
                return $this->error('处理失败','','1');
            }
        }
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
