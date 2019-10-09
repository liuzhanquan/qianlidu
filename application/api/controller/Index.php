<?php
namespace app\api\controller;
use app\common\controller\Home;
use app\Index\model\Goods;
class Index extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    public function index(){

        return $this->showtpl('user/index');

    }


    public function errors(){
        // $msgLink = '<a href="'.urlDiy('/user/index').'">去审核</a>';
        // $msgData = "亲，你有下级代理授权申请\n申请人：王生\n手机号：13265961104\n申请时间：".date('Y-m-d H:i:s')."\n\n".$msgLink;
        // SendWxMessage($msgData,$this->user_data['openid']);
        $authorization = db('user_auth')->where('uid',$this->user_data['id'])->find();
        if($authorization['status'] > 0){
            return $this->redirect(url('/user/index'));die;
        }else{
            return $this->showtpl('user/error');
        }
    }
}
