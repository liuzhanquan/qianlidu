<?php
namespace app\user\controller;
use app\common\controller\Home;

class Ontop extends Home{

	public function myagent($type = 0){

		$this->assign('type',$type);
		return $this->showtpl('user/Ontop/myagent');
	}


	public function money(){
		// 获取累计支出
		$out_money = db('user_log')->where(['uid'=>$this->user_data['id'],'is_in'=>0])->sum('money');
		$max_money = db('user_log')->where(['uid'=>$this->user_data['id']])->max('user_money');
		$this->assign('max_money',$max_money);
		$this->assign('out_money',$out_money);
		return $this->showtpl('user/Ontop/money');
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


	public function mygoods(){

		return $this->showtpl('user/Ontop/mygoods');
	}

}