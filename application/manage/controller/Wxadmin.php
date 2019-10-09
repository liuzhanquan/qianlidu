<?php
namespace app\manage\controller;
use app\common\controller\Wxuser;

class Wxadmin extends Wxuser{


	public function _initialize(){
		parent::_initialize();
	}

	public function index(){

        $money['num'] = db('user_auth')->count();
        $money['recharge'] = db('user_recharge')->where('parent_id','0')->sum('money');
        $money['user_money'] = db('user')->sum('money');
        $this->assign('money',$money);
		return $this->fetch();	
	}
	public function agent(){
		// 
        $list = db('user a')->join('user_auth b','a.id = b.uid')->order('b.timestamp desc')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
		return $this->fetch();	
	}
    public function douser($id = '0',$type = 'view'){
        if($type == 'view'){
            $info = db('user a')->join('user_auth b','a.id = b.uid')->where('a.id',$id)->find();
            $this->assign('info',$info);
            return $this->fetch('user_view');
        }else if($type == 'edit'){
            if(!isPost()){
                $info = db('user a')->join('user_auth b','a.id = b.uid')->where('a.id',$id)->find();
                $this->assign('info',$info);
                $region = db('region')->where('parent_id','0')->select();
                $this->assign('region',$region);
                // 获取级别
                $level = db('agent_level')->select();
                $this->assign('level',$level);
                return $this->fetch('user_edit');
            }else{
                $data = $_POST;
                // 修改会员等级
                $level = db('agent_level')->find($data['level_id']);
                db('user')->where('id',$data['uid'])->update(['level_id'=>$data['level_id']]);
                $data['level_name'] = $level['name'];
                $state = db('user_auth')->where('id',$data['id'])->update($data);
                if($state){
                    return $this->success('操作成功',url('index'));
                }else{
                    return $this->error('操作失败！');
                }
            }
        }
    }
    /**
     * 充值处理
    **/
    public function option($id = 0){
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
    /**
     * 审核代理商
    **/
    public function auditing($id = 0){
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
                    db('user_auth')->where('uid',$data['id'])->update($authData);
                    $state = db('user')->where('id',$data['id'])->update(['status'=>'1']);
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
                    db('user_auth')->where('uid',$data['id'])->update($authData);
                    $state = db('user')->where('id',$data['id'])->update(['status'=>'1']);
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
    public function status(){
        if(!isPost()){

            return json(['code'=>0]);
        }else{
            $data = $_POST;
            db('user_auth')->where('uid',$data['id'])->update(['status'=>$data['val']]);
            $state = db('user')->where('id',$data['id'])->update(['status'=>$data['val']]);
            if($state){
                return $this->success('操作成功',url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }

	public function recharge($id = '0'){
        $search = $this->data;
        $where = array();
        // 只显示由总部处理的打款
        $where['second'] = 2;
        if($id == '1'){
            $where['status'] = array('in','1,2');
        }else{
            $where['status'] = array('in','0,4');
        }
        if(isset($_GET['start']) && isset($_GET['end'])){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $where['timestamp'] = array('between',array($_GET['start'],$_GET['end']));
            }
        }
        if(isset($_GET['key'])){
            if(!empty($_GET['key'])){
                $key = $_GET['key'];
                // 查询授权名
                $uWhere['auth_name'] = array('like','%'.$key.'%');
                $ulist = db('user_recharge')->where($uWhere)->select();
                if(empty($ulist)){
                    $pWhere['phone']  = array('like','%'.$key.'%');
                    $plist = db('user_recharge')->where($pWhere)->select();
                    if(!empty($plist)){
                        $where['phone'] = array('like','%'.$key.'%');
                    }else{
                        $where['auth_name'] = array('like','%'.$key.'%');
                    }
                }else{
                    $where['auth_name'] = array('like','%'.$key.'%');
                }
            }
        }
        // 
        $list = db('user_recharge')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $totalMoney = db('user_recharge')->where($where)->sum('money');
        $this->assign('totalMoney',$totalMoney);
        $this->assign('list',$list);
        $this->assign('page',$list->render());

        $this->assign('data',$search);
        $this->assign('id',$id);
        return $this->fetch();
    }

    
    /**
     * 通过充值申请
     * @param id 充值记录id
    **/
    public function resuccess($id = '0'){
        if(!isPost()){
            $info = db('user_recharge')->where('id',$id)->find();
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            $info = db('user_recharge')->where('id',$data['id'])->find();
            if($info['parent_id'] > 0){
                $parent = db('user')->find($info['parent_id']);
                if($info['end_money'] > $parent['money']){
                    return $this->error('该代理上级账户余额不足');
                }
                $userData['money'] = $parent['money'] - $info['end_money'];
                // 记录充值人业绩
                db('performance')->insertGetId([
                    'agent_id'=>$info['uid'],
                    'form_id'=>$info['uid'],
                    'type'=>'1',
                    'money'=>$info['money'],
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);
                // 记录利润额
                db('agent_profit')->insertGetId([
                    'agent_id'=>$parent['id'],
                    'form_id'=>$info['uid'],
                    'money'=>($info['money'] - $info['end_money']),
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);

                db('user')->where('id',$parent['id'])->update($userData);
                // 写入收支明细
                $logData = [
                    'agent_id'=>$parent['id'],
                    'form_id'=>$info['uid'],
                    'remark'=>'转账：充值给'.getUser($info['uid']),
                    'type'=>'5',
                    'money'=>$info['end_money'],
                    'timestamp'=>date('Y-m-d H:i:s')
                ];
                db('agent_log')->insertGetId($logData);
                $state = db('user_recharge')->where('id',$data['id'])->update(['status'=>1,'remark'=>$data['remark'],'do_time'=>date('Y-m-d H:i:s')]);
                if($state){
                    // 收款人收支明细记录
                    $selfUser = db('user')->find($info['uid']);
                    $selfUserData['money'] = $selfUser['money'] + $info['money'];
                    db('user')->where('id',$info['uid'])->update($selfUserData);
                    $reward = new \lib\Reward;
                    $reward->teamreward($info['uid'],$info['money']);
                    $reward->same($info['uid'],$info['money'],'2');
                    $selfData = [
                        'agent_id'=>$info['uid'],
                        'remark'=>'充值：'.$data['remark'],
                        'type'=>'4',
                        'money'=>$info['money'],
                        'timestamp'=>date('Y-m-d H:i:s')
                    ];
                    db('agent_log')->insertGetId($selfData);

                    $authLink = '<a href="'.urlDiy('/user/ontop/money_log').'">查看</a>';
                    $msgData = "亲，您的充值申请总部审核通过啦！\n\n充值额度：￥".number_format($info['money'],2)."\n转账人：".getUser($info['parent_id'])."\n留言：".$data['remark']."\n处理时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
                    $openId = getUser($info['uid'],'openid');
                    SendWxMessage($msgData,$openId);

                    return $this->success('处理成功！','','1');
                }else{
                    return $this->error('操作失败','','1');
                }

            }else{
                // 总部直接给款
                $user = db('user')->where('id',$data['uid'])->find();
                // 发送微信信息至相对应微信会员
                $msgData = "亲，您的一笔充值申请已充值成功！\n\n申请额度：￥".number_format($info['money'],2)."\n商家留言：".$data['remark']."\n处理时间：".date('Y-m-d H:i:s');
                $openId = $user['openid'];
                SendWxMessage($msgData,$openId);
                // 处理充值
                $userData['money'] = $user['money'] + $info['money'];
                db('user')->where('id',$data['uid'])->update($userData);
                $state = db('user_recharge')->where('id',$data['id'])->update(['status'=>'1','remark'=>$data['remark'],'user_money'=>$userData['money'],'do_time'=>date('Y-m-d H:i:s')]);
                if($state){
                    // 写入业绩
                    db('performance')->insertGetId([
                        'agent_id'=>$user['id'],
                        'form_id'=>$user['id'],
                        'type'=>'1',
                        'money'=>$info['money'],
                        'timestamp'=>date('Y-m-d H:i:s')
                    ]);
                    $reward = new \lib\Reward;
                    // db('test')->insertGetId(['content'=>$info['money']]);
                    $reward->teamreward($user['id'],$info['money']);
                    $reward->same($user['id'],$info['money'],'2');

                    return $this->success('处理成功！','','1');
                }else{
                    return $this->error('操作失败','','1');
                }
            }
        }
    }

    /**
     * 拒绝充值申请
     * @param id 充值记录id
    **/
    public function reerr($id = '0'){
        if(!isPost()){
            $info = db('user_recharge')->where('id',$id)->find();
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            // 发送微信信息至相对应微信会员
            $info = db('user_recharge')->where('id',$data['id'])->find();
            $user = db('user')->where('id',$data['uid'])->find();
            $msgData = "亲，您的一笔充值申请已被管理员拒绝了哦！\n\n申请额度：￥".number_format($info['money'],2)."\n拒绝原因：".$data['remark']."\n处理时间：".date('Y-m-d H:i:s');
            $openId = $user['openid'];
            SendWxMessage($msgData,$openId);
            $state = db('user_recharge')->where('id',$data['id'])->update(['status'=>'2','remark'=>$data['remark']]);
            if($state){
                return $this->success('处理成功！','','1');
            }else{
                return $this->error('操作失败','','1');
            }
        }
    }
}