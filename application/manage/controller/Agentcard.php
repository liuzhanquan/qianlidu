<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Agentcard extends Admin{

    /**
	 * 出货管理
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




        $list = db('agentCard')->paginate(20,false,['query'=>request()->param()]);

        //dump($list);exit();
        $this->assign('id',$id);
        $this->assign('data',$searchData);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    

}
