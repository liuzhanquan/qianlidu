<?php
namespace app\common\controller;
use app\common\controller\Base;

class Home extends Base{

    protected $userLevel;
    protected $auth_user;

    public function _initialize(){
        parent::_initialize();
        $this->wechatAauth();
        //$this->jssdk();
    }

    protected function wechatAauth(){
        cookie('user_cookie',85);

        $cookie_user = cookie('user_cookie');
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $this->user_data = db('user')->where('id',$cookie_user)->find();
        if(empty($cookie_user) || empty($this->user_data)){
            if($this->request->isAjax()){
                return $this->error('您还没有登录！',url('/index/wechat/wxoauth'));
            }else{
                
                return $this->redirect(url('/index/wechat/wxoauth').'?url='.$url);
                exit();
            }
        }
        
        //$this->auth_user = db('user a')->join('user_auth b','a.id = b.uid')->where(['a.id'=>$this->user_data['id']])->find();
        //$this->userLevel = db('agent_level')->where('id',$this->user_data['level_id'])->find();
        //$this->assign('userLevel',$this->userLevel);
        //$this->assign('user',$this->auth_user);
        //$this->assign('authUser',$this->user_data);
    }

    protected function jssdk(){
        $weObj = initWechat();
        $auth = $weObj->checkAuth();
        $js_ticket = $weObj->getJsTicket();
        // if (!$js_ticket) {
        //     echo "获取js_ticket失败！<br>";
        //     echo '错误码：'.$weObj->errCode;
        //     echo ' 错误原因：'.ErrCode::getErrText($weObj->errCode);
        //     exit;
        // }
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $js_sign = $weObj->getJsSign($url);
        $this->assign('jssdk',$js_sign);
    }
}
