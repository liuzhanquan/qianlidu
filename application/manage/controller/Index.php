<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Index extends Admin{

    public function index(){

        // 统计最近7天代理加盟数量
        $oldDay = array();
        for($i = 0;$i < 7;$i++){
            $oldDay[] = date('Y-m-d',strtotime('-'.$i.' days'));
        }

        $money['agent'] = db('agent')->where('business',0)->count();
        $money['business'] = db('agent')->where('business',1)->count();
        $money['user'] = db('user')->count();
        $money['order'] = db('order')->count();
        $this->assign('money',$money);
        return $this->fetch();
    }

    // public function t($mon){
    //     // agent_sales  
    //     // agent_log
    //     $beginThismonth = date('Y-m-d H:i:s',mktime(0,0,0,$mon,1,date('Y')));
    //     //获取本月结束的时间戳
    //     $endThismonth = date('Y-m-d H:i:s',mktime(23,59,59,$mon+1,0,date('Y')));
    //     $where['timestamp'] = array('between',array($beginThismonth,$endThismonth));
    //     $where['type'] = array('in','1,2');
    //     $list = db('agent_log')->where($where)->select();
    //     $timeCode = date('Y').$mon;
    //     foreach($list as $vo){
    //         $user  = db('user')->where('id',$vo['agent_id'])->find();
    //         $exitCode = db('agent_settlement')->where(['top_parent'=>$user['top_parent'],'agent_id'=>$user['id'],'timecode'=>$timeCode])->find();
    //         if(empty($exitCode)){
    //             db('agent_settlement')->insertGetId([
    //                 'top_parent'=>$user['top_parent'],
    //                 'agent_id'=>$user['id'],
    //                 'money'=>$vo['money'],
    //                 'status'=>'0',
    //                 'timecode'=>$timeCode,
    //                 'last_time'=>$vo['timestamp']
    //             ]);
    //         }else{
    //             $upData = [
    //                 'money' => $exitCode['money'] + $vo['money'],
    //                 'last_time' => $vo['timestamp'],
    //             ];
    //             db('agent_settlement')->where('id',$exitCode['id'])->update($upData);
    //         }
    //         // addSettlement($user['id'],$user['top_parent'],$vo['money']);
    //     }
    // }
    public function r(){
        //$arr=array(30,40,10,50,20,60);
        //$a = bubble_sort($arr);
        //print_r($a);
        // $reward = new \lib\Reward;
        // $reward->teamreward('8','20000');
        if(1280.00 > 1280.00){
            echo '<pre>';
            $nowNum = '3';
            $str = "667B_3,A933_2,2E01_2,FB64_1,0_99";
            $strArr = explode(',', $str);
            $gtArr = array();
            foreach($strArr as $vo){
                $vStr = explode('_', $vo);
                if($vStr[1] < $nowNum){
                    $gtArr[] = $vStr[1].'_'.$vStr[0];
                }
            }
            print_r($gtArr);
        }
    }
    public function menu(){
        $list = model('common/SysMenu')->loadTreeList();
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function domenu($id = '0'){
        if(!isPost()){
            $info = model('common/SysMenu')->getOne(['id'=>$id]);
            $list = model('common/SysMenu')->loadTreeList();
            $this->assign('list',$list);
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            if($data['id']){
                // edit
                $state = model('common/SysMenu')->saveData($data,'edit');
            }else{
                $state = model('common/SysMenu')->saveData($data);
            }
            if($state){
                return $this->success('添加成功');
            }else{
                return $this->error('添加失败');
            }
        }
    }
    public function city($id = '0'){
        $list = db('region')->where('parent_id',$id)->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function docity($id = '0'){
        if(!isPost()){
            $parent = db('region')->find($id);
            $this->assign('info',$parent);
            return $this->fetch();
        }else{
            $data = $this->data;
            if(!empty($data['content'])){
                $temp_data = nl2br($data['content']);
                $temp_data = str_replace("<br />","/n",$temp_data);
                $list = explode('/n', $temp_data);
                if(!empty($list)){
                    foreach($list as $vo){
                        $key = trim($vo);
                        // $idKey = explode(',', $key);
                        $inArr = [
                            // 'id'=>trim($idKey[0]),
                            'parent_id'=>$data['parent_id'],
                            'name'=>$key,
                            // 'status'=>'1'
                        ];
                        db('region')->insertGetId($inArr);
                        // print_r($inArr);
                    }
                }
            }
        }
    }
}