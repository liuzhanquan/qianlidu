<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Wages extends Admin{

    /**
	 * 发放设置
    **/
    public function index($id = 1){
        if(!isPost()){
            $teamReward = unserialize($this->config['team']);
            $this->assign('teamReward',$teamReward);
            $teamRecommend = unserialize($this->config['recommend_team']);
            $this->assign('teamRecommend',$teamRecommend);
            $recommend = unserialize($this->config['recommend']);
            $this->assign('recommend',$recommend);
            
            $upgrade = unserialize($this->config['upgrade']);
            $this->assign('upgrade',$upgrade);
            
            $same = unserialize($this->config['same']);
            $this->assign('same',$same);
            // 获取级别
            $levle = db('agent_level')->where('is_extends','0')->select();
            $this->assign('levle',$levle);
            $this->assign('id',$id);
            return $this->fetch();
        }else{
            $data = $this->data;
            if(isset($data['upgrade'])){
                $data['upgrade'] = serialize($data['upgrade']);
            }
            if(isset($data['recommend'])){
                $data['recommend'] = serialize($data['recommend']);
            }
            if(isset($data['same'])){
                $data['same'] = serialize($data['same']);
            }
            if(isset($data['recommend_team'])){
                $data['recommend_team'] = serialize($data['recommend_team']);
            }
            if(isset($data['team'])){
                $data['team'] = serialize($data['team']);                
            }
            $state = model('common/Config')->saveInfo($data);
            if($state){
                return $this->success('操作成功！','');
            }else{
                return $this->error('操作失败！');
            }
        }
    }
    /**
	 * 平级推荐发放
    **/
    public function referee(){
        return $this->fetch();
    }
    /**
     * 月度销售业绩发放
    **/
    public function sale(){
        return $this->fetch();
    }
    /**
     * 业绩
    **/
    public function achievement($id = 0){
        $search = $_GET;
        $searchData['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';   
        $searchData['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        $searchData['start'] = isset($_GET['start']) ? $_GET['start'] : date('Y-m');
        $where = array();
        if(isset($data['start']) && isset($data['end'])){
            if(!empty($data['start']) && !empty($data['end'])){
                $where['timestamp'] = array('between',array($data['start'],$data['end']));
            }
        } 
        switch ($searchData['selkey']) {
            case '1': // 手机号
                if(!empty($searchData['key'])){
                    $where['a.phone'] = array('like','%'.$searchData['key'].'%');
                }
            break;
            case '2': // 授权名称
                if(!empty($searchData['key'])){
                    $where['b.auth_name'] = array('like','%'.$searchData['key'].'%');
                }
            break;
            case '3': // 身份证
                if(!empty($searchData['key'])){
                    $where['b.idcard'] = array('like','%'.$searchData['key'].'%');
                }
            break;
        }
        $where['a.top_parent'] = $id;
        $list = db('user a')->join('user_auth b','a.id = b.uid')->order('b.timestamp desc')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('data',$searchData);
        $this->assign('list',$list);
        $this->assign('page',$list->render());        
        return $this->fetch();
    }
    /**
     * 发放明细
    **/
    public function logs($id = '1'){
        $data = $this->data;
        $where = array();
        $data['start'] = isset($data['start']) ? $data['start'] : date('Y-m',mktime(0,0,0,date('m'),1,date('Y')));
        $data['end'] = isset($data['end']) ? $data['end'] : date('Y-m',mktime(0,0,0,date('m'),1,date('Y')));
        // if(isset($data['start']) && isset($data['end'])){
        //     if(!empty($data['start']) && !empty($data['end'])){
        //         $startStr = date('Ym',strtotime($data['start']));
        //         $endStr = date('Ym',strtotime($data['end']));
        //         $where['a.timecode'] = array('between',array($startStr,$endStr));
        //     }
        // }
        if(isset($data['start'])){
            if(!empty($data['start'])){
                $startStr = date('Ym',strtotime($data['start']));
                // $endStr = date('Ym',strtotime($data['end']));
                $where['a.timecode'] = $startStr;
            }
        }
        $where['a.status'] = 0;
        $where['a.top_parent'] = 0;
        if(isset($data['exp'])){
            $filename = '结算管理记录'.date('YmdHis').'-'.rand(0000000000,999999999);  
            $header = array('代理信息','结算月份','直推奖励','平级奖励','销售奖励','结算总额','已结算金额');  
            $exList = db('agent_settlement a')->join('user b','a.agent_id = b.id')->join('user_auth c','a.agent_id = c.uid')->field('a.*,b.avatar,c.auth_name,c.wechat,c.phone,c.auth_number,c.level_name')->where($where)->select();
            $expList = array();
            if(!empty($exList)){
                foreach($exList as $k=>$vo){
                    $expList[$k] = $vo;
                    $expList[$k]['agent'] = $vo['auth_name'].";".$vo['level_name'];
                    $expList[$k]['timecode'] = $vo['timecode'];
                    $expList[$k]['zhitui'] = getReward($vo['agent_id'],'1',$data['start'],$data['end']);
                    $expList[$k]['pinji'] = getReward($vo['agent_id'],'2',$data['start'],$data['end']);
                    $expList[$k]['xiaoshou'] = getReward($vo['agent_id'],'3',$data['start'],$data['end']);
                    $expList[$k]['all'] = $vo['money'];
                    $expList[$k]['end'] = $vo['end_money'];
                }
            }
            $index = array('agent','timecode','zhitui','pinji','xiaoshou','all','end');  
            $this->createtable($expList,$filename,$header,$index);
        }
        $list = db('agent_settlement a')->join('user b','a.agent_id = b.id')->join('user_auth c','a.agent_id = c.uid')->field('a.*,b.avatar,c.auth_name,c.wechat,c.phone,c.auth_number,c.level_name')->where($where)->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        $this->assign('data',$data);
        $this->assign('id',$id);
        return $this->fetch();
    }
    public function endmoney($id = 0){
        $this->view->engine->layout('api_layout');
        if($id <> 0){
             if(!isPost()){
                $info = db('agent_settlement a')->join('user b','a.agent_id = b.id')->join('user_auth c','a.agent_id = c.uid')->field('a.*,b.avatar,c.auth_name,c.wechat,c.phone,c.auth_number,c.level_name')->where('a.id',$id)->find();
                $this->assign('info',$info);
                // 获取收款账号
                $bank = db('user_wallet')->where('uid',$info['agent_id'])->find();
                $this->assign('account',$bank);
                return $this->fetch();
            }else{
                $data = $_POST;
                $info = db('agent_settlement')->where('id',$data['id'])->find();
                $user = db('user')->where('id',$info['agent_id'])->find();
                if($info['end_money'] >= $info['money']){
                    return $this->error('该奖金已结算','','1');die;
                }
                if($data['money'] > $info['money']){
                    return $this->error('结算金额不能大于可结算金额','','1');die;
                }
                $uData['end_money'] = $data['money'];
                db('user')->where('id',$info['agent_id'])->update(['reward_money'=>($user['reward_money'] - $data['money'])]);
                db('agent_settlement_log')->insertGetId([
                    'agent_id'=>$info['agent_id'],
                    'parent_id'=>0,
                    'parent_name'=>'总部',
                    'money'=>$data['money'],
                    'status'=>1,
                    'timestamp'=>date('Y-m-d H:i:s'),
                ]);
                $state = db('agent_settlement')->where('id',$data['id'])->update($uData);
                if($state){
                    return $this->success('结算成功','','1');
                }else{
                    return $this->error('结算失败','','1');
                }
            }
        }else{
            return json(['code'=>0]);
        }
    }
    /**
     * 充值申请
    **/
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
     * 发放明细
    **/
    public function detail($id = '1'){
        switch ($id) {
            case '1': // 利润额
                $list = db('agent_profit')->order('timestamp desc')->paginate(20,false,['query'=>request()->param()]);
            break;
            case '2': // 平级奖金
                $list = db('agent_log')->order('timestamp desc')->where(['type'=>array('in','1,2')])->paginate(20,false,['query'=>request()->param()]);
            break;
            case '3': // 销售奖金
                $list = db('agent_sales')->order('timestamp desc')->paginate(20,false,['query'=>request()->param()]);
            break;
            case '4': // 销售业绩
                $list = db('performance')->order('timestamp desc')->paginate(20,false,['query'=>request()->param()]);
            break;
            case '5': // 操作记录
                $list = db('option_log')->order('timestamp desc')->where('do_type','>','0')->paginate(20,false,['query'=>request()->param()]);
            break;
        }
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        $this->assign('id',$id);
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
                case 'agent_profit': // 删除
                    $state = db('agent_profit')->where('id',$id)->delete();
                break;
                case 'agent_log': // 删除
                    $state = db('agent_log')->where('id',$id)->delete();
                break;
                case 'agent_sales': // 删除
                    $state = db('agent_sales')->where('id',$id)->delete();
                break;
                case 'performance': // 删除
                    $state = db('performance')->where('id',$id)->delete();
                break;
            }
            if($state){
                return $this->success('删除成功！');
            }else{
                return $this->error('删除失败');
            }
        }
    }

}