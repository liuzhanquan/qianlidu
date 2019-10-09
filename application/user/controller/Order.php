<?php
namespace app\user\controller;
use app\common\controller\User;

class Order extends User{

	/**
	 * 我的订货价
	**/
    public function price(){        
    	$where = array();
    	$where['status'] = 1;
    	$list = db('goods')->where($where)->paginate(20,false,['query'=>request()->param()]);
    	$nList = array();
    	if(!empty($list)){
    		foreach($list as $k=>$vo){
    			$nList[$k] = $vo;
    			$agentPrice = unserialize($vo['agent']);
    			$nList[$k]['agent'] = $agentPrice[$this->user_data['level_id']];
    		}
    	}
        $this->assign('list',$nList);
        $this->assign('page',$list->render());
        return $this->showtpl('user/Order/price');
    }
    /**
     * 商品详情
    **/
    public function detail($id = 0){
    	$info = db('goods')->find($id);
    	$agentPrice = unserialize($info['agent']);
    	$info['agent'] = $agentPrice[$this->user_data['level_id']];
        $this->assign('info',$info);
    	return $this->showtpl('user/Order/detail');
    }
	/**
	 * 我要下单
	**/
    public function orderin(){  
    	$where = array();
    	$where['status'] = 1;
    	$list = db('goods')->where($where)->select();
    	$nList = array();
    	if(!empty($list)){
    		foreach($list as $k=>$vo){
    			$nList[$k] = $vo;
    			$agentPrice = unserialize($vo['agent']);
                $nList[$k]['agent'] = $agentPrice[$this->user_data['level_id']];
    		}
    	}
        $this->assign('list',$nList);
        return $this->showtpl('user/Order/orderin');
    }
	/**
	 * 下单
	**/
	public function to_order(){
		if(!isPost()){

			return json(['code'=>0]);
		}else{
			$data = $_POST;
			if(!empty($data)){
				$orderId = $this->user_data['id'].time().rand(00,99);
				foreach($data as $goodsId=>$vo){
                    $dataInfo = explode(',', $vo);
					// 查询商品
					$info = db('goods')->find($goodsId);
			    	$agentPrice = unserialize($info['agent']);
			    	$info['agent'] = $agentPrice[$this->user_data['level_id']];
			    	$cartData = [
			    		'goods_id'=>$goodsId,
			    		'price'=>$info['agent'],
			    		'num'=>$dataInfo[1],
			    		'plan_money'=>$info['agent'] * $dataInfo[1],
			    		'order_id'=>$orderId,
			    		'uid'=>$this->user_data['id'],
			    		'timestamp'=>date('Y-m-d H:i:s')
			    	];
			    	db('user_cart')->insertGetId($cartData);
				}

				$returnUrl = urls('buy').'?id='.$orderId;
				return json(['code'=>1,'url'=>$returnUrl]);
			}else{
				return json(['code'=>0]);
			}
		}
	}
	public function buy(){
        $orderId = isset($_GET['id']) ? $_GET['id'] : '0';
		$addressId = isset($_GET['aid']) ? $_GET['aid'] : '0';
        cookie('addressId',$addressId);
        if($addressId <> 0){
            $address = db('user_address')->where(['id'=>$addressId])->find();
        }else{
            // 查询默认地址
            $address = db('user_address')->where(['uid'=>$this->user_data['id'],'is_true'=>'1'])->find();
        }
		if($orderId <> 0){
            if(!isPost()){
                $order = db('user_cart')->where('order_id',$orderId)->select();
                $list = array();
                $totalMoney = 0;
                if(!empty($order)){
                    foreach($order as $k=>$vo){
                        $list[$k] = $vo;
                        $totalMoney += $vo['plan_money'];
                        $list[$k]['goods'] = db('goods')->find($vo['goods_id']);
                    }
                }
                $this->assign('id',$orderId);
                $this->assign('totalMoney',$totalMoney);
                $this->assign('address',$address);
                $this->assign('order',$list);
                return $this->showtpl('user/Order/buy');
            }else{
                $data = $_POST;
                if(empty($data['address_id'])){
                    return $this->error('请选择收货地址');
                }
                $order = db('user_cart')->where('order_id',$data['order'])->select();
                $list = array();
                $totalMoney = $totalNum = 0;
                if(!empty($order)){
                    foreach($order as $k=>$vo){
                        $list[$k] = $vo;
                        $totalMoney += $vo['plan_money'];
                        $totalNum += $vo['num'];
                        $goods = db('goods')->find($vo['goods_id']);
                        $detailData = [
                            'order_id'=>$data['order'],
                            'goods_id'=>$vo['goods_id'],
                            'goods'=>serialize($goods),
                            'price'=>$vo['price'],
                            'plan_money'=>$vo['plan_money'],
                            'num'=>$vo['num'],
                            'status'=>'1',
                            'timestamp'=>date('Y-m-d H:i:s')
                        ];
                        db('user_order_detail')->insertGetId($detailData);
                    }
                }
                // 写入订单
                $orderData = [
                    'uid'=>$this->user_data['id'],
                    'order_id'=>$data['order'],
                    'price'=>$totalMoney,
                    'num'=>$totalNum,
                    'address'=>serialize($address),
                    'status'=>'0',
                    'timestamp'=>date('Y-m-d H:i:s')
                ];
                $state = db('user_order')->insertGetId($orderData);
                // 推送
                $orderLink = "";
                $authLink = '<a href="'.urlDiy('/user/order/pay').'?id='.$data['order'].'">去付款</a>';
                $msgData = "你有新的订单下单成功啦~\n收货人：".$address['name']."\n手机号：".$address['phone']."\n订单金额：".$totalMoney."\n下单时间：".date('Y-m-d H:i:s')."\n\n".$authLink;
                SendWxMessage($msgData,$this->user_data['openid']);
                return $this->success('下单成功',url('pay').'?id='.$data['order']);
            }
		}else{
			return $this->redirect(url('orderin'));
	        exit();
		}
	}
    public function choose(){
        $orderId = isset($_GET['id']) ? $_GET['id'] : '0';
        if($orderId <> 0){
            $addressId = cookie('addressId');
            $address = db('user_address')->where(['uid'=>$this->user_data['id']])->select();
            $this->assign('addressId',$addressId);
            $this->assign('list',$address);
            $this->assign('orderId',$orderId);
            $region = db('region')->where('parent_id','0')->select();
            $this->assign('region',$region);
            return $this->showtpl('user/Order/choose');
        }else{
            return $this->redirect(url('orderin'));
            exit();
        }
    }
    public function pay(){
        $orderId = isset($_GET['id']) ? $_GET['id'] : '0';
        if($orderId <> 0){
            if(!isPost()){
                $order = db('user_order')->where('order_id',$orderId)->find();
                if($order['status'] != 0){
                    return $this->showtpl('user/paySuccess');
                    die;
                    // return $this->GmError('订单已支付或取消','返回订单中心进行查看吧~');die;
                }
                $this->assign('order',$order);
                $this->assign('orderId',$orderId);
                return $this->showtpl('user/Order/pay');
            }else{
                $data = $this->data;
                // 查询订单价格
                $order = db('user_order')->where('id',$data['id'])->find();
                if($order['price'] > $this->user_data['money']){
                    return $this->error('账户货款余额不足');
                }
                $userData['money'] = $this->user_data['money'] - $order['price'];
                // 写入收支明细
                db('agent_log')->insertGetId([
                    'agent_id'=>$this->user_data['id'],
                    'money'=>$order['price'],
                    'type'=>'5',
                    'remark'=>'下单支出',
                    'timestamp'=>date('Y-m-d H:i:s')
                ]);
                db('user')->where('id',$this->user_data['id'])->update($userData);
                db('user_order_detail')->where('order_id',$order['order_id'])->update(['status'=>'1']);
                $state = db('user_order')->where('id',$order['id'])->update(['status'=>'1']);
                if($state){
                    return $this->success('支付成功',url('orderlist'));
                }else{
                    return $this->error('订单支付失败');
                }
            }
        }else{
            return $this->redirect(url('orderin'));
            exit();
        }
    }


    /**
     * 我的订单
    **/
    public function orderlist($type = 0){    
        $where['uid'] = $this->user_data['id'];
        if($type <> 0){
            $where['status'] = $type;
        }
        $list = db('user_order')->where($where)->order('timestamp desc')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->showtpl('user/Order/orderlist');
    }
    /**
     * 下级订单详情
    **/
    public function order_down_info(){
        $orderSn = isset($_GET['id']) ? $_GET['id'] : '';
        if(!$orderSn){
            return $this->redirect(url('order_down'));
            exit();
        }
        $info = db('user_order')->where('order_id',$orderSn)->find();
        $list = db('user_order_detail')->where('order_id',$orderSn)->select();
        $this->assign('list',$list);
        $this->assign('info',$info);
        return $this->showtpl('user/Order/order_down_info');
    }
    /**
     * 累计收益
    **/
    public function profit(){        

        return $this->showtpl('user/Order/profit');
    }
    /**
     * 下级订单
    **/
    public function order_down(){        

        return $this->showtpl('user/Order/order_down');
    }
}
