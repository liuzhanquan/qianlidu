<?php
namespace app\user\controller;
use app\common\controller\User;

class Finance extends User{

    /**
     * 我要充值
    **/
    public function recharge(){
        if(!isPost()){
            return $this->showtpl('user/Finance/recharge');
        }else{
            $data = $_POST;
            // 检测该代理是否为首笔充值
            $firstCharge = db('user_recharge')->where('uid',$this->user_data['id'])->find();
            if(empty($firstCharge)){
                // 查询首笔下单注册订单金额
                // $firstMoney = db('user_order')->where(['uid'=>$this->user_data['id'],'is_reg'=>'1'])->find();
                // if($data['money'] < $firstMoney['price']){
                //     return $this->error('首笔充值不能低于'.$firstMoney['price'].'元');die;
                // }
                $data['is_reg'] = 1;
            }
            $rebateMoney = $this->userLevel['rebate'] * 10;
            if($data['money'] % $rebateMoney != 0){
                return $this->error('充值金额须为（'.$this->userLevel['rebate'].'）的倍数');die;
            }
            if($this->userLevel['max_recharge'] <> 0){
                if($data['money'] >= $this->userLevel['max_recharge']){
                    return $this->error('单笔充值金额不能超过'.$this->userLevel['max_recharge'].'');die;
                }
            }
            $selfLevel = db('agent_level')->find($this->user_data['level_id']);
            $data['uid'] = $this->user_data['id'];
            $data['auth_name'] = $this->auth_user['auth_name'];
            $data['phone'] = $this->user_data['phone'];
            // 计算上级需要支付金额
            $topParent = db('user')->find($this->user_data['top_parent']);
            $topParentLevel = db('agent_level')->where('id',$topParent['level_id'])->value('rebate');
            $firstMoney = $data['money'] / ($this->userLevel['rebate'] / 10);
            $reMoney = $firstMoney * $topParentLevel / 10;
            $data['end_money'] = $reMoney;
            // 
            $data['user_money'] = $this->user_data['money'];
            $data['status'] = 0;
            $data['timestamp'] = date('Y-m-d H:i:s');
            $state = db('user_recharge')->insertGetId($data);
            if($state){
                if($data['parent_id'] <> 0){
                    $authLink = '<a href="'.urlDiy('/user/finance/recharge_down').'">查看</a>';
                    $msgData = "亲，".$this->auth_user['nickname']."有一笔充值申请哦！\n\n申请人：".$this->auth_user['auth_name']."\n申请额度：￥".number_format($data['money'],2)."\n\n申请时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
                    $openId = getUser($data['parent_id'],'openid');
                    SendWxMessage($msgData,$openId);
                }
                return $this->success('提交已申请，请等待审核',url('rechargelog'));
            }else{
                return $this->error('申请失败');
            }
        }
    }
    /**
     * 充值记录
    **/
    public function rechargelog($type = 0){
        $where = array();
        $start = isset($_GET['start']) ? $_GET['start'] : '';
        $end = isset($_GET['end']) ? $_GET['end'] : '';
        if(!empty($start) && !empty($end)){
            $where['timestamp'] = array('between',array($start.' 00:00:00',$end.' 23:59:59'));
        }
        if($type <> 0){
            $where['status'] = ($type - 1); 
        }
        $where['uid'] = $this->user_data['id'];
        $money = db('user_recharge')->where($where)->sum('money');
        $list = db('user_recharge')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('type',$type);
        $this->assign('money',$money);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->showtpl('user/Finance/rechargelog');
    }
    /**
     * 下级充值
    **/
    public function recharge_down($type = 0){
        if($type <> 0){
            $where['status'] = (int)($type - 1);
        }
        $where['parent_id'] = $this->user_data['id'];
        $list = db('user_recharge')->order('timestamp desc')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('page',$list->render());
        $start = isset($_GET['start']) ? $_GET['start'] : '';
        $end = isset($_GET['end']) ? $_GET['end'] : '';   
        $this->assign('list',$list);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('type',$type);
        return $this->showtpl('user/Finance/recharge_down');
    }
    /**
     * 下级充值处理
    **/
    public function do_down($id = 0,$type = 0){
        if(!isPost()){
            $info = db('user_recharge')->find($id);
            $this->assign('type',$type);
            $this->assign('info',$info);
            return $this->showtpl('user/Finance/do_down');
        }else{
            $data = $this->data;
            $info = db('user_recharge')->find($data['id']);
            if($data['type'] == 0){
                // 拒绝
                $state = db('user_recharge')->where('id',$data['id'])->update(['status'=>2,'remark'=>$data['remark'],'do_time'=>date('Y-m-d H:i:s')]);
                if($state){
                    if($info['parent_id'] <> 0){
                        $authLink = '<a href="'.urlDiy('/user/finance/rechargelog').'">查看</a>';
                        $msgData = "亲，".getUser($info['parent_id'],'nickname')."拒绝给你转账~\n\n申请额度：￥".number_format($info['money'],2)."\n处理人：".getUser($info['parent_id'])."\n拒绝原因：".$data['remark']."\n处理时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
                        $openId = getUser($info['uid'],'openid');
                        SendWxMessage($msgData,$openId);
                    }
                    return $this->success('处理成功',url('recharge_down'));
                }else{
                    return $this->error('处理失败');
                }

            }else if($data['type'] == 1){
                // 给予充值
                if($info['end_money'] > $this->user_data['money']){
                    return $this->error('账户余额不足');
                }
                // if($info['is_reg'] == '1'){
                //     // 查询是否为首单充值，首单充值记录到上级业绩
                //     $rechargeUser = db('user a')->join('agent_level b','a.level_id = b.id')->fiels('a.*,b.first_money')->where('a.id',$info['uid'])->find();
                //     $parentUser = db('user a')->join('agent_level b','a.level_id = b.id')->fiels('a.*,b.first_money')->where('a.id',$rechargeUser['parent_id'])->find();

                //     if($parentUser['first_money'] == '1'){
                //         if($parentUser['level_id'] == $rechargeUser['level_id']){
                //             db('performance')->insertGetId([
                //                 'agent_id'=>$parentUser['id'],
                //                 'form_id'=>$info['uid'],
                //                 'type'=>'2',
                //                 'money'=>$info['money'],
                //                 'timestamp'=>date('Y-m-d H:i:s')
                //             ]);
                //         }
                //     }
                // }
                $userData['money'] = $this->user_data['money'] - $info['end_money'];
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
                    'agent_id'=>$this->user_data['id'],
                    'form_id'=>$info['uid'],
                    'money'=>($info['money'] - $info['end_money']),
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);

                db('user')->where('id',$this->user_data['id'])->update($userData);
                // 写入收支明细
                $logData = [
                    'agent_id'=>$this->user_data['id'],
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
                    $reward->same($info['uid'],$info['money']);
                    $selfData = [
                        'agent_id'=>$info['uid'],
                        'remark'=>'充值：'.$data['remark'],
                        'type'=>'4',
                        'money'=>$info['money'],
                        'timestamp'=>date('Y-m-d H:i:s')
                    ];
                    db('agent_log')->insertGetId($selfData);
                    if($info['parent_id'] <> 0){
                        $authLink = '<a href="'.urlDiy('/user/ontop/money_log').'">查看</a>';
                        $msgData = "亲，".getUser($info['parent_id'],'nickname')."给你转账啦！\n\n转账额度：￥".number_format($info['money'],2)."\n转账人：".getUser($info['parent_id'])."\n留言：".$data['remark']."\n转账时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
                        $openId = getUser($info['uid'],'openid');
                        SendWxMessage($msgData,$openId);
                    }
                    return $this->success('处理成功',url('recharge_down'));
                }else{
                    return $this->error('处理失败');
                }

            }
        }
    }
    /**
     * 我的业绩
    **/
    public function teammoney($mon = 0,$type = '1'){
        if($mon == 0){
            $mon = date('m');
            //获取本月开始的时间戳
            $beginThismonth = date('Y-m-d H:i:s',mktime(0,0,0,date('m'),1,date('Y')));
            //获取本月结束的时间戳
            $endThismonth = date('Y-m-d H:i:s',mktime(23,59,59,date('m'),date('t'),date('Y')));
        }else{
            //获取本月开始的时间戳
            $beginThismonth = date('Y-m-d H:i:s',mktime(0,0,0,$mon,1,date('Y')));
            //获取本月结束的时间戳
            $endThismonth = date('Y-m-d H:i:s',mktime(23,59,59,$mon+1,0,date('Y')));
        }
        // 统计利润额
        $data['lirun'] = db('agent_profit')->where(['agent_id'=>$this->user_data['id'],'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->sum('money');
        // 统计销售业绩
        $data['yeji'] = db('performance')->where(['agent_id'=>$this->user_data['id'],'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->sum('money');
        // 统计销售业绩奖金
        $data['yeji_money'] = db('agent_sales')->where(['agent_id'=>$this->user_data['id'],'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->sum('money');
        // 平级奖金
        $data['pinji'] = db('agent_log')->where(['agent_id'=>$this->user_data['id'],'type'=>array('in','1,2'),'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->sum('money');
        // 统计本月总收入奖金
        // $data['reward'] = db('agent_log')->where(['agent_id'=>$this->user_data['id'],'type'=>array('<','4'),'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->sum('money');
        $data['all'] = $data['lirun']+$data['yeji_money']+$data['pinji'];

        // 记录查询
        if($type == '1'){
            // 利润额记录
            $list = db('agent_profit')->where(['agent_id'=>$this->user_data['id'],'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->paginate(20,false,['query'=>request()->param()]);
            $this->assign('list',$list);
            $this->assign('page',$list->render());
        }else if($type == '2'){
            // 平级奖金
            $list = db('agent_log')->where(['agent_id'=>$this->user_data['id'],'type'=>array('in','1,2'),'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->paginate(20,false,['query'=>request()->param()]);
            $this->assign('list',$list);
            $this->assign('page',$list->render());
        }else if($type == '3'){
            // 销售奖金
            $list = db('agent_sales')->where(['agent_id'=>$this->user_data['id'],'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->paginate(20,false,['query'=>request()->param()]);
            $this->assign('list',$list);
            $this->assign('page',$list->render());
        }else if($type == '4'){
            // 销售业绩
            $list = db('performance')->where(['agent_id'=>$this->user_data['id'],'timestamp'=>array('between',array($beginThismonth,$endThismonth))])->paginate(20,false,['query'=>request()->param()]);
            $this->assign('list',$list);
            $this->assign('page',$list->render());
        }

        $this->assign('data',$data);
        $this->assign('type',$type);
        $this->assign('mon',$mon);
        return $this->showtpl('user/Finance/teammoney');
    }
    /**
     * 我的余额
    **/
    public function package(){

        return $this->showtpl('user/Finance/package');
    }
    /**
     * 累计收益
    **/
    public function profit(){
        // 计算当前佣金
        $where['type'] = array('<','5');
        $where['agent_id'] = $this->user_data['id'];
        $data['allMoney'] = db('agent_settlement')->where($where)->sum('money');
        $list = db('agent_log')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        $this->assign('data',$data);

        return $this->showtpl('user/Finance/profit');
    }
    /**
     * 奖金结算
    **/
    public function settlement($type = 1){
        // 计算当前佣金
        $where['agent_id'] = $this->user_data['id'];
        $data['allMoney'] = db('agent_settlement')->where(['agent_id'=>$this->user_data['id']])->sum('money');
        $data['trueMoney'] = db('agent_settlement')->where(['agent_id'=>$this->user_data['id'],'status'=>'0'])->sum('money');

        $this->assign('data',$data);
        return $this->showtpl('user/Finance/settlement');
    }

    public function money_log($type = 0){
        $nowTime = date('Y-m-d H:i:s');
        if($type == 0){
            // 近一周
            $where['timestamp'] = array('between',array(date('Y-m-d H:i:s',strtotime("-7 days")),$nowTime));
        }else if($type == 1){
            // 近一月
            $where['timestamp'] = array('between',array(date('Y-m-d H:i:s',strtotime("-1 month")),$nowTime));
        }else if($type == 2){
            // 近半年
            $where['timestamp'] = array('between',array(date('Y-m-d H:i:s',strtotime("-6 month")),$nowTime));
        }else if($type == 3){
            // 近一年
            $where['timestamp'] = array('between',array(date('Y-m-d H:i:s',strtotime("-1 year")),$nowTime));
        }
        $where['agent_id'] = $this->user_data['id'];
        $list = db('agent_log')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->showtpl('user/Ontop/money_log');
    }
    public function apply_my($mon = 0){
        if($mon == 0){
            $mon = date('m') - 1;
            //获取本月开始的时间戳
            $beginThismonth = date('Ym',mktime(0,0,0,date('m')-1,1,date('Y')));
        }else{
            //获取本月开始的时间戳
            $beginThismonth = date('Ym',mktime(0,0,0,$mon,1,date('Y')));
        }
        $totalMoney = db('agent_settlement')->where(['timecode'=>$beginThismonth,'agent_id'=>$this->user_data['id']])->sum('money');
        $list = db('agent_settlement')->where(['timecode'=>$beginThismonth,'agent_id'=>$this->user_data['id']])->select();
        $this->assign('list',$list);
        $this->assign('totalMoney',$totalMoney);
        $this->assign('mon',$mon);
        return $this->showtpl('user/Finance/apply_my');
    }
    public function apply_log(){
        return $this->showtpl('user/Finance/apply_log');
    }
    public function apply_down(){
        if(!isPost()){

            return $this->showtpl('user/Finance/apply_down');
        }else{
            $data = $this->data;
        }
    }

}