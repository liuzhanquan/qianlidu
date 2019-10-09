<?php  
namespace app\common\model;
use \app\common\model\Base;

class User extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $branchList = [];
    
    protected function baseJoin($where = array()){
        return $this->where($where);
    }
    public function getOne($where = array()){
        return $this->baseJoin($where)->find();
    }
    public function loadList($where = array(),$order = 'id asc'){
        $list = $this->baseJoin($where)->order($order)->paginate();
        if(empty($list)){
            return [];
        }
        $result_list['list'] = $list;
        $result_list['page'] = $list->render();
        return $result_list;
    }

    public function selectList($where = array(),$limit = '20',$order = 'id asc'){
        if($limit){
            $list = $this->baseJoin($where)->limit($limit)->order($order)->select();
        }else{ 
            $list = $this->baseJoin($where)->order($order)->select();
        }
        return $list;
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
        if(!captcha_check($data['captcha'])){
            $this->error = '验证码错误！!';
            return false;
        }
        if(isPhoneNum($data['name'])){
            $userInfo = $this->where(['phone'=>$data['name']])->find();
        }else{
            $userInfo = $this->where(['username'=>$data['name']])->find();
        }
        if (empty($userInfo)) {
            $this->error = '该用户不存在!';
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
        // 替换浏览记录中的sessionID
        $sessionId = session_id();
        db('user_browse')->where('id',$sessionId)->update(['id'=>$userInfo['id']]);
        $editState = $this->where('id',$userInfo['id'])->update($loginInfo);
        if(!$editState){
            $this->error = '登陆失败,请稍后再试!';
            return false;
        }
        cookie('user_id',$userInfo['id']);
        cookie('user_login_time',request()->time());
        return $userInfo['id'];
    }
    /**
     * 数据完成
    **/
    public function _saveAfter($data = [],$type = 'add'){
        if($type == 'add'){
            $data['username'] = sp_random_string(5).'_'.substr($data['phone'],-4);
            $data['password'] = sp_password($data['password']);
            $data['reg_time'] = time();
            $data['vistcode'] = sp_random_string();
            $data['user_bg'] = 'http://cdn.taoyuantoday.com/resource/default/images/user-bg.jpg';
            // 查询默认级别
            $group = model('common/UserGroup')->getOne(['is_defa'=>1]);
            $data['gid'] = $group['gid'];
            $data['point'] = $group['e_point'];
            $data['avatar'] = 'http://cdn.taoyuantoday.com/resource/images/avatar.png';
            $state = $this->allowField(true)->save($data);
            $id = $this->getLastInsID();
            $log_data = array(
                'id' => $id,
                'action' => '账户注册',
                'timestamp' => time()
            );
            db('user_log')->insert($log_data); // 写入记录
        }
        if($type == 'edit'){
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