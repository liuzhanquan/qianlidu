<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Api extends Admin{


    public function _initialize(){
        parent::_initialize();
        $this->view->engine->layout('api_layout');
    }


    public function getUser(){
        $key = isset($_GET['key']) ? $_GET['key'] : '';
        $brandId = isset($_GET['id']) ? $_GET['id'] : '';
        if($key == ''){
            // 查询全部
            $list = db('user a')->join('user_auth b','a.id = b.uid')->where('a.status','1')->paginate(15,false,['query'=>request()->param()]);
        }else{
            // 查询授权编号
            $list = array();
            $numWhere['auth_number'] = array('like','%'.$key.'%');
            $levelNum = db('user_auth')->where($numWhere)->select();
            $where = array();
            $where['a.status'] = 1;
            if(!empty($levelNum)){
                $levelUid = array();
                foreach($levelNum as $vo){
                    $levelUid[] = $vo['uid'];
                }
                $where['uid'] = array('in',implode(',', $levelUid));
            }else{
                $uWhere['auth_name'] = array('like','%'.$key.'%');
                $ulist = db('user_auth')->where($uWhere)->select();
                if(empty($ulist)){
                    $pWhere['phone']  = array('like','%'.$key.'%');
                    $plist = db('user_auth')->where($pWhere)->select();
                    if(!empty($plist)){
                        $where['a.phone'] = array('like','%'.$key.'%');
                    }
                }else{
                    $where['b.auth_name'] = array('like','%'.$key.'%');
                }
            }
            
            $list = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->paginate(15,false,['query'=>request()->param()]);
            
        }
        $this->assign('key',$key);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    /**
     * $type 1为省代 2为市代
    **/
    public function geitopUser($id = 0,$type = 0){
        $key = isset($_GET['key']) ? $_GET['key'] : '';
        if($key == ''){
            // 查询全部
            $where['a.level_id'] = array('in','6,11,12');
            $where['a.status'] = 1;
            $list = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->paginate(15,false,['query'=>request()->param()]);
        }else{
            // 查询授权编号
            $numWhere['auth_number'] = array('like','%'.$key.'%');
            $levelNum = db('user_auth')->where($numWhere)->select();
            $where = array();
            $where['a.status'] = 1;
            if(!empty($levelNum)){
                $levelUid = array();
                foreach($levelNum as $vo){
                    $levelUid[] = $vo['uid'];
                }
                $where['b.uid'] = array('in',implode(',', $levelUid));
            }else{
                $uWhere['auth_name'] = array('like','%'.$key.'%');
                $ulist = db('user_auth')->where($uWhere)->select();
                if(empty($ulist)){
                    $pWhere['phone']  = array('like','%'.$key.'%');
                    $plist = db('user_auth')->where($pWhere)->select();
                    if(!empty($plist)){
                        $where['a.phone'] = array('like','%'.$key.'%');
                    }
                }else{
                    $where['b.auth_name'] = array('like','%'.$key.'%');
                }
            }
            $where['a.level_id'] = array('in','6,11,12');
            $list = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->paginate(15,false,['query'=>request()->param()]);
        }
        // 获取已经添加的总裁
        $agent = db('agent_city')->where('area_id',$id)->select();
        $agentArr = array();
        if(!empty($agent)){
            foreach($agent as $vo){
                $agentArr[] = $vo['agent_id'];
            }
        }
        if(isPost()){
            $data = $this->data;
            $inData = [
                'agent_id'=>$data['agent_id'],
                'type'=>$data['type'],
                'area_id'=>$data['area_id'],
                'is_reward'=>'0',
            ];
            $state = db('agent_city')->insertGetId($inData);
            if($state){
                return $this->success('添加成功');
            }else{
                return $this->error('添加失败');
            }
        }
        $this->assign('agent',$agentArr);
        $this->assign('id',$id);
        $this->assign('type',$type);
        $this->assign('key',$key);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    public function setCity($id = 0){
        if(!isPost()){
            $where['area_id'] = $id;
            $list = db('agent_city')->where($where)->paginate(15,false,['query'=>request()->param()]);
            $this->assign('list',$list);
            $this->assign('id',$id);
            $this->assign('page',$list->render());
            return$this->fetch();
        }else{
            $data = $this->data;
            // db('agent_city')->where(['area_id'=>$data['area_id'],'is_reward'=>'1'])->update(['is_reward'=>'0']);
            $state = db('agent_city')->where('id',$data['id'])->update(['is_reward'=>'1']);
            if($state){
                return $this->success('设置成功');
            }else{
                return $this->error('设置失败');
            }
        }
    }

    /**
     * 生成推广链接
     * @param brandId 品牌ID，多个字符串提交
     * @param levelId 等级ID，多个字符串提交
     * @param upAgentId 会员ID，总部为0
     * @param IsEverTime 链接类型 0为临时 1为永久
    **/
    public function savelink(){
        if(!isPost()){
            return json(['code'=>0]);
        }else{
            $data = $_POST;
            if($data['IsEverTime'] == '1'){
                // 生成永久链接
                $info = db('user_link')->where(['level_id'=>$data['levelId'],'uid'=>$data['upAgentId'],'type'=>$data['IsEverTime']])->find();
                if(empty($info)){
                    $isSet = 0;
                }else{
                    $isSet = 1;
                    $return['url'] = $info['local_url'];
                }

            }else if($data['IsEverTime'] == '0'){
                // 生成临时链接，有限期为48小时
                $isSet = 0;
                $weObj = initWechat();
                $return['time'] = 48*60;
                $inData['end_time'] = date('Y-m-d H:i:s',strtotime("+ 48 hours"));
                // $return['qrCode'] = '';
            }
            if($isSet == 0){
                $inData['level_id'] = $data['levelId'];
                $inData['uid'] = $data['upAgentId'];
                $inData['type'] = $data['IsEverTime'];
                $inData['timestamp'] = date('Y-m-d H:i:s');
                $state = db('user_link')->insertGetId($inData);
                // 二维码包含信息
                $qrcodeStr = "uid=".$data['upAgentId'].'&link='.$state;
                $key = 'apply_'.lock_url($qrcodeStr);  //lock_url  unlock_url
                if($data['IsEverTime'] == 1){
                    $ticket = $weObj->getQRCode($key,'2'); // 换取微信生成Ticket
                }else{
                    $ticket = $weObj->getQRCode($key,'3',172800); // 换取微信生成Ticket
                    $return['qrCode'] = $weObj->getQRUrl($ticket['ticket']);
                }
                $formUrl = urlDiy('/user/auth/register',['uid'=>$data['upAgentId'],'type'=>$data['IsEverTime'],'id'=>$state]);
                db('user_link')->where('id',$state)->update(['local_url'=>$formUrl,'ticket'=>$ticket['ticket'],'url'=>$weObj->getQRUrl($ticket['ticket'])]);
                $return['url'] = $formUrl;
            }else{
                $state = 1;
            }

            if($state){
                return json(['code'=>1,'data'=>$return]);
            }else{
                return json(['code'=>0,'msg'=>'生成邀请链接失败']);
            }
        }
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
    /**
     * 修改会员密码
     * @param id 会员id
    **/
    public function setpasw($id = '0'){
        if(!isPost()){
            if($id <> 0){
                $info = db('user')->find($id);
                $this->assign('info',$info);
                return $this->fetch();
            }else{
                return json(['code'=>0]);
            }
        }else{
            $data = $_POST;
            $data['password'] = sp_password($data['password']);
            $state = db('user')->where('id',$data['id'])->update($data);
            if($state){
                return $this->success('操作成功！','','1');
            }else{
                return $this->error('操作失败','','1');
            }
        }
    }
    /**
     * 修改会员级别
     * @param id 会员id
    **/
    public function setlevel($id = '0'){
        if(!isPost()){
            if($id <> 0){
                $level = db('agent_level')->where('is_extends','0')->select();
                $info = db('user a')->join('user_auth b','a.id = b.uid')->where('a.id',$id)->find();
                $noelevel = db('agent_level')->where('id',$info['level_id'])->find();
                $this->assign('level',$level);
                $this->assign('noelevel',$noelevel);
                $this->assign('info',$info);
                return $this->fetch();
            }else{
                return json(['code'=>0]);
            }
        }else{
            set_time_limit(0);
            $data = $this->data;
            $selfLevel = db('agent_level')->find($data['level_id']);
            $selfUser = db('user')->find($data['uid']);
            $userLevel = db('agent_level')->find($selfUser['level_id']);
            // 更新所有的上级对应信息
            $parentInfo = db('user a')->join('agent_level b','a.level_id = b.id')->field('a.*,b.level_num')->where('a.id',$selfUser['parent_id'])->find();
            $userUpData = array();
            if(!empty($parentInfo)){
                if(empty($parentInfo['parent_path'])){
                    $userUpData['parent_path'] = $parentInfo['only_code'].'_'.$parentInfo['level_num'];
                }else{
                    $userUpData['parent_path'] = $parentInfo['only_code'].'_'.$parentInfo['level_num'].','.$parentInfo['parent_path'];
                }
                // 查询会员更高级别的上级
                if(!empty($userUpData['parent_path'])){
                    
                    // $parent_path = $this->user_data['id'].'_'.$this->userLevel['level_num'].','.$userUpData['parent_path'];
                    // $parentPath = explode(',', $parent_path);
                    if($parentInfo['level_num'] < $selfLevel['level_num']){ // 如果上级的级别比本身级别高，则最高上级为推荐上级
                        $userUpData['top_parent'] = $parentInfo['id'];
                    }else{
                        $parentPath = explode(',', $userUpData['parent_path']);
                         
                        if(!empty($parentPath)){
                            $gtArr = array();
                            foreach($parentPath as $k=>$vo){
                                $parentPathLevel = explode('_', $vo);
                                if($parentPathLevel[1] < $selfLevel['level_num']){
                                    $gtArr[] = $parentPathLevel[1].'_'.$parentPathLevel[0];
                                }
                            }
                       
                            if(!empty($gtArr)){
                                $parentCode = explode('_', $gtArr[0]);
                                $topParent = db('user')->where('only_code',$parentCode[1])->find();
                                $userUpData['top_parent'] = $topParent['id'];
                                if ($topParent['level_id']==$data['level_id']) {
                                    $userUpData['top_parent'] = $topParent['top_parent'];
                                }
                            }else{
                                $userUpData['top_parent'] = "0";
                            }
                        }
                    }
                }

            }else{
                $userUpData['parent_path'] = "0_99";
                $userUpData['top_parent'] = "0";
            }
            // 计算新的货款
            if($data['type'] == '2'){
                $userUpData['money'] = $selfUser['money'] / $userLevel['rebate'] * $selfLevel['rebate'];
            }
            $userUpData['level_id'] = $data['level_id'];
            $state = db('user')->where('id',$data['uid'])->update($userUpData);
            // 写入授权信息
            $authData = array();
           
            $authData = [
                'top_parent'=>$userUpData['top_parent'],
                'level_id'=>$selfLevel['id'],
                'level_name'=>$selfLevel['name']
            ];
            db('user_auth')->where('uid',$data['uid'])->update($authData);
            $allDowns = db('user')->where('parent_id',$data['uid'])->select();
            foreach ($allDowns as $key => $user) {
                $this->updateDown($user);
            }
            if($state){
                return $this->success('修改成功','','1');
            }else{
                return $this->error('修改失败','','1');
            }
        }
    }
    private function updateDown($selfUser=array()){
        // 更新所有的上级对应信息
        set_time_limit(0);
        if (empty($selfUser)) {
            return false;
        }
        $selfLevel = db('agent_level')->find($selfUser['level_id']);
        $parentInfo = db('user a')->join('agent_level b','a.level_id = b.id')->field('a.*,b.level_num')->where('a.id',$selfUser['parent_id'])->find();
        $userUpData = array();
        if(!empty($parentInfo)){
            if(empty($parentInfo['parent_path'])){
                $userUpData['parent_path'] = $parentInfo['only_code'].'_'.$parentInfo['level_num'];
            }else{
                $userUpData['parent_path'] = $parentInfo['only_code'].'_'.$parentInfo['level_num'].','.$parentInfo['parent_path'];
            }
            // 查询会员更高级别的上级
            if(!empty($userUpData['parent_path'])){
                
                // $parent_path = $this->user_data['id'].'_'.$this->userLevel['level_num'].','.$userUpData['parent_path'];
                // $parentPath = explode(',', $parent_path);
                if($parentInfo['level_num'] < $selfLevel['level_num']){ // 如果上级的级别比本身级别高，则最高上级为推荐上级
                    $userUpData['top_parent'] = $parentInfo['id'];
                }else{
                    $parentPath = explode(',', $userUpData['parent_path']);
                    if(!empty($parentPath)){
                        $gtArr = array();
                        foreach($parentPath as $k=>$vo){
                            $parentPathLevel = explode('_', $vo);
                            if($parentPathLevel[1] < $selfLevel['level_num']){
                                $gtArr[] = $parentPathLevel[1].'_'.$parentPathLevel[0];
                            }
                        }
                        if(!empty($gtArr)){
                            $parentCode = explode('_', $gtArr[0]);
                            $topParent = db('user')->where('only_code',$parentCode[1])->find();
                            $userUpData['top_parent'] = $topParent['id'];
                            if ($topParent['level_id']==$selfUser['level_id']) {
                                $userUpData['top_parent'] = $topParent['top_parent'];
                            }
                        }
                    }
                }
            }

        }
        $state = db('user')->where('id',$selfUser['id'])->update($userUpData);
        $authData = array();
        $authData = [
            'top_parent'=>$userUpData['top_parent'],
            'level_id'=>$selfLevel['id'],
            'level_name'=>$selfLevel['name']
        ];
        db('user_auth')->where('uid',$selfUser['id'])->update($authData);
        $allDowns = db('user')->where('parent_id',$selfUser['id'])->select();
        foreach ($allDowns as $key => $user) {
            $this->updateDown($user);
        }
    }
    public function getlevel(){
        if(!isAjax()){
            return json(['code'=>0]);
        }else{
            $data = $this->data;
            $info = db('agent_level')->find($data['id']);
            return json(['code'=>'1','data'=>$info]);
        }
    }
    public function ajax_user(){
        $uid = isset($_GET['uid']) ? $_GET['uid'] : "0";
        $type = isset($_GET['type']) ? $_GET['type'] : "";
        $key = isset($_GET['key']) ? $_GET['key'] : "";
        if(!empty($type)){
            switch ($type) {
                case '1': // 授权名
                    $where['b.auth_number'] = $key;
                break;
                case '2': // 手机号
                    $where['b.phone'] = array('like','%'.$key.'%');
                break;
                case '3': // 微信号
                    $where['b.wechat'] = array('like','%'.$key.'%');
                break;
            }
            $user = db('user a')->join('user_auth b','a.id = b.uid')->where($where)->select();
        }else{
            // 获取会员直推会员
            $user = db('user a')->join('user_auth b','a.id = b.uid')->where('a.parent_id',$uid)->select();
        }
        $userArr = array();
        if(!empty($user)){
            foreach($user as $k=>$vo){
                $userArr[$k] = $vo;
                $son = db('user')->where(['parent_id'=>$vo['uid'],'status'=>array('in','1,2')])->find();
                if(!empty($son)){
                    $userArr[$k]['son'] = 1;
                }else{
                    $userArr[$k]['son'] = 0;
                }
            }
        }
        $this->assign('uid',$uid);
        $this->assign('list',$userArr);
        return $this->fetch();
    }
    public function ajax_info(){
        $uid = isset($_GET['uid']) ? $_GET['uid'] : "0";
        if($uid == 0){
            return json(['code'=>0]);
            die;
        }
        $info = db('user a')->join('user_auth b','a.id = b.uid')->where('a.id',$uid)->find();
        $this->assign('info',$info);
        return $this->fetch();

    }

    public function detail($type ,$id ){
        if(!isPost()){
            switch ($type) {
                case '1': // 利润额
                    $money = db('agent_profit')->where('id',$id)->value('money');
                break;
                case '2': // 平级奖金
                    $money = db('agent_log')->where('id',$id)->value('money');
                break;
                case '3': // 销售奖金
                    $money = db('agent_sales')->where('id',$id)->value('money');
                break;
                case '4': // 销售业绩
                    $money = db('performance')->where('id',$id)->value('money');
                break;
            }
            $this->assign('type',$type);
            $this->assign('id',$id);
            $this->assign('money',$money);
            return $this->fetch();
        }else{
            $data = $_POST;
            switch ($data['type']) {
                case '1':
                    if($data['do'] == '1'){
                        // 增加
                        $state = db('agent_profit')->where('id',$data['id'])->setInc('money',$data['money']);
                    }else if($data['do'] == '2'){
                        // 减少
                        $state = db('agent_profit')->where('id',$data['id'])->setDec('money',$data['money']);
                    }
                break;
                case '2':
                    if($data['do'] == '1'){
                        // 增加
                        $state = db('agent_log')->where('id',$data['id'])->setInc('money',$data['money']);
                    }else if($data['do'] == '2'){
                        // 减少
                        $state = db('agent_log')->where('id',$data['id'])->setDec('money',$data['money']);
                    }
                break;
                case '3':
                    if($data['do'] == '1'){
                        // 增加
                        $state = db('agent_sales')->where('id',$data['id'])->setInc('money',$data['money']);
                    }else if($data['do'] == '2'){
                        // 减少
                        $state = db('agent_sales')->where('id',$data['id'])->setDec('money',$data['money']);
                    }
                break;
                case '4':
                    if($data['do'] == '1'){
                        // 增加
                        $state = db('performance')->where('id',$data['id'])->setInc('money',$data['money']);
                    }else if($data['do'] == '2'){
                        // 减少
                        $state = db('performance')->where('id',$data['id'])->setDec('money',$data['money']);
                    }
                break;
            }
            if($state){
                db('option_log')->insertGetId([
                    'uid'=>$data['id'],
                    'money'=>$data['money'],
                    'type'=>$data['do'],
                    'do_type'=>$data['type'],
                    'remark'=>$data['remark'],
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);
                return $this->success('操作成功',url('wages/detail'),'1');
            }else{
                return $this->error('操作失败','','1');
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
                case 'agent_city_canel': // 取消省市代
                    $state = db('agent_city')->where('id',$id)->update(['is_reward'=>0]);
                    $txt = "取消成功";
                break;
                case 'agent_city_del': // 取消省市代
                    $state = db('agent_city')->where('id',$id)->delete();
                    $txt = " 删除成功";
                break;
            }
            if($state){
                return $this->success($txt);
            }else{
                return $this->error('操作失败');
            }
        }
    }

}