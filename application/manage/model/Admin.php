<?php
namespace app\manage\model;
use app\common\model\Base;

class Admin extends Base{
	
	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $validate = [
        'rule'=>[
            'username'=>'require',
        ],
        'msg'=>[
            'username.require' => '名称必须填写',
        ],
    ];
    protected function baseCondition($where = array()){
        return $this->alias('a')
                ->join('admin_group b','a.role_id = b.gid')
                ->field('a.*,b.name as level_name')
                ->where($where);
    }
    /**
     * 获取全部信息
    **/
    public function loadList($where = array(),$limit = 20,$order = 'a.id desc'){
        $list = $this->baseCondition($where)
                ->limit($limit)
                ->order($order)
                ->paginate();
        if(empty($list)){
            return [];
        }
        $result_list['list'] = $list;
        $result_list['page'] = $list->render();
        return $result_list;
    }
    public function loadSelect($where = array(),$limit = 20,$order = 'a.id desc'){
        $list = $this->baseCondition($where)
                ->limit($limit)
                ->order($order)
                ->select();
        if(empty($list)){
            return [];
        }
        return $list;
    }
    /**
     * 获取单条信息
    **/
    public function getOne($where){
        return $this->baseCondition($where)->find();
    }
    /**
     * 用户登录
     * @param array $data
     * @return bool
    **/
    public function setLogin($data){
        if (!$data) {
            $data = Request::instance()->param();
        }
        $userInfo = $this->baseCondition(['a.username'=>$data['username']])->find();
        if (empty($userInfo)) {
            $this->error = '该用户不存在!';
            return false;
        }
        if (!$userInfo['status']) {
            $this->error = '该用户已被禁止登录!';
            return false;
        }
        if(!sp_compare_password($data['password'],$userInfo['password'])){
            $this->error = '用户密码错误！!';
            return false;
        }
        $loginInfo = [
            'id'=>$userInfo['id'],
            'login_time'=>request()->time(),
        ];
        $editState = $this->where('id',$userInfo['id'])->update($loginInfo);
        if(!$editState){
            $this->error = '登陆失败,请稍后再试!';
            return false;
        }
        cookie('admin_cookie',$userInfo['id']);
        cookie('login_time',request()->time());
        return $userInfo['id'];
    }
    /**
     * 数据完成
    **/
    public function _saveAfter($data = [] , $type = 'add'){
        if($type == 'add'){
            $userInfo = $this->baseCondition(['a.username'=>$data['username']])->find();
            if(!empty($userInfo)){
                $this->error = '该账号已存在！';
                return false;
            }
            $data ['password'] = sp_password($data['password']);
            $data ['reg_time'] = request()->time();
            $data ['login_time'] = request()->time();
            $state = $this->allowField(true)->save($data);
            $id = $this->getLastInsID();
        }
        if($type == 'edit'){
            // 修改密码
            $userInfo = $this->baseCondition(['a.id'=>$data['id']])->find();
            if(!empty($data['password'])){
                $data['password'] = sp_password($data['password']);
            }else{
                $data['password'] = $userInfo['password'];
            }
            $Info = $this->baseCondition(['a.username'=>$data['username']])->find();
            if($userInfo['username'] != $data['username']){
                if(!empty($userInfo)){
                    $this->error = '该账号已存在！';
                    return false;
                }
            }
            $state = $this->allowField(true)->save($data,['id'=>$data['id']]);

            $id = $data['id'];
        }
        if($state){
            return $id;
        }else{
            $this->error = $this->getError() ? $this->getError() : '没有可修改的数据！';
            return false;
        }
        return false;
    }
}