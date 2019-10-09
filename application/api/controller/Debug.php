<?php
namespace app\api\controller;
use app\common\controller\Base;

class Debug extends Base{

	public function index(){
		$list = db('user a')->join('user_auth b','a.id = b.uid')->field('a.*,b.*')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
		return $this->showtpl('debug');
	}
	public function login($id = 0){
		cookie('user_cookie',$id);
	}
}