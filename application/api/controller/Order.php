<?php
namespace app\api\controller;
use app\common\controller\Home;
use app\api\model\Goods;
use app\api\model\GoodsComment;
use app\api\model\Order as OD;
use \think\Validate;
class Order extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    public function index(){

        return $this->showtpl('user/index');

    }

    public function orderAdd(){
        if( empty(input('goods_id')) ) return_ajax('旅游项目错误', 200);
        if( empty(input('aduly')) ) return_ajax('请填写成人数量', 200);
        if( empty(input('addrList')) ) return_ajax('请填写详细地址', 200);
        $userInfo = $this->userInfo();
        $validate = Validate("Valifybase");

        $data['order_num']  = makeOrderNum();
        $data['gid']        = input('goods_id');
        $data['uid']        = $userInfo['id'];
        $data['aduly']      = input('aduly');
        $data['baby']       = input('baby');
        $data['addr_list']  = input('addrList');
        $data['start_time'] = strtotime(input('start_time'));
        $data['add_time']   = time();
        $data['update_time']= time();

        
        if( !$validate->check($data) ){
            return_ajax($validate->getError(),400);
        }

        $effect = db('order')->insert($data);
        if( $effect ){
            return_ajax('下单成功',200,$data['order_num']);
        }else{
            return_ajax('下单失败',400);
        }
        
    }   

    public function orderAll(){
        empty(input('page')) ? $page = 1 : $page = input('page');
        empty(input('limit')) ?  $limit = 5 : $limit = input('limit');

        $userInfo = $this->userInfo();

        $order = OD::with('goods')->where(['uid'=>$userInfo['id']])->limit(($page-1)*$limit,$limit)->field('aduly,baby,addr_list,start_time,add_time,gid,status')->select();

        foreach( $order as $key=> $item ){
            $order[$key]['add_time'] = time_date($item['add_time']);
            if( $item['status'] == 1 ){
                $order[$key]['start_time'] = date_go($item['start_time']);
            }else{
                $order[$key]['start_time'] = time_date($item['start_time']);
            }
        }

        $order = obj_arr($order);

        return_ajax('成功！',200,$order);

    }

    public function orderedit($status){
        
        $effect = db('order')->where(['id'=>input('orderId')])->update(['status'=>$status]);

        return $effect;
    }

    public function getPhone($id){
        return db('user')->where('id',$id)->column('phone')[0];
    }


    public function orderCustomer(){
        if( empty(input('orderId')) ) return_ajax('数据错误',400);
        $uid = $this->userInfo()['id'];
        $info = db('order')->where(['id'=>input('orderId'),'uid'=>$uid])->column('status');
        if($info[0] != 0 ) return_ajax('数据错误!',400,$info[0]);

        $effect = $this->orderEdit(1);

        if( $effect ){
            return_ajax('操作成功',200);
        }else{
            return_ajax('操作失败',400);
        }
    }




    public function orderGo(){
        
        if( empty(input('orderId')) ) return_ajax('数据错误',400);
        $uid = $this->userInfo()['id'];
        $info = db('order')->where(['id'=>input('orderId'),'uid'=>$uid])->column('status');
        if($info[0] != 1 ) return_ajax('数据错误!',400,$info[0]);
        $effect = $this->orderEdit(2);

        if( $effect ){
            return_ajax('操作成功',200);
        }else{
            return_ajax('操作失败',400);
        }

    }

    public function orderReset(){
        
        if( empty(input('orderId')) ) return_ajax('数据错误',400);
        $uid = $this->userInfo()['id'];
        $info = db('order')->where(['id'=>input('orderId'),'uid'=>$uid])->column('status');
        if($info[0] != 100 ) return_ajax('数据错误!',400,$info[0]);
        $effect = $this->orderEdit(0);

        if( $effect ){
            return_ajax('操作成功',200);
        }else{
            return_ajax('操作失败',400);
        }

    }

    public function orderComfirm(){
        
        if( empty(input('orderId')) ) return_ajax('数据错误',400);
        $uid = $this->userInfo()['id'];
        $info = db('order')->where(['id'=>input('orderId'),'uid'=>$uid])->column('status');
        if($info[0] != 2 ) return_ajax('数据错误!',400,$info[0]);
        $effect = $this->orderEdit(3);

        if( $effect ){
            return_ajax('操作成功',200);
        }else{
            return_ajax('操作失败',400);
        }

    }

    public function orderCancel(){
        
        if( empty(input('orderId')) ) return_ajax('数据错误',400);
        $uid = $this->userInfo()['id'];
        $info = db('order')->where(['id'=>input('orderId'),'uid'=>$uid])->column('status');
        if( $info[0] >= 1 && $info[0] < 100 ) return_ajax('数据错误!',400,$info[0]);
        $effect = $this->orderEdit(100);

        if( $effect ){
            return_ajax('操作成功',200);
        }else{
            return_ajax('操作失败',400);
        }

    }

    public function orderGet(){
        if( empty(input('orderId')) ) return_ajax('数据错误',400);
        $uid = $this->userInfo()['id'];
        $info['order'] = OD::with('goods')->where(['id'=>input('orderId'),'uid'=>$uid])->field('add_time,start_time,aduly,baby,addr_list,order_num,gid,status')->find();
        $info['config'] = $this->configInfo();

        $info['order']['start_time'] = time_date( $info['order']['start_time'] );
        $info['order']['add_time']   = time_date( $info['order']['add_time'] );
        $info['order']['phone']   = $this->getPhone($uid);
        return_ajax('成功',200,$info);
    }
    
    

}
