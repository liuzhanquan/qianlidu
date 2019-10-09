<?php
namespace app\user\controller;
use app\common\controller\User;

class Profile extends User{


	/**
	 * 个人管理
	**/
	public function index(){

        if(!isPost()){

			return $this->showtpl('user/Profile/index');
        }else{
            $data = $_POST;
        }
	}
    /**
     * 修改密码
    **/
    public function setpasw(){
        if(!isPost()){
            return $this->showtpl('user/Profile/setpasw');
        }else{
            $data = $_POST;
            $data['password'] = sp_password($data['password']);
            $state = db('user')->where('id',$this->user_data['id'])->update($data);
            if($state){
                return $this->success('操作成功！',url('profile'));
            }else{
                return $this->error('操作失败');
            }
        }
    }

	/**
	 * 我的授权
	**/
	public function accredit(){
		// 查询当前会员授权记录
        $authorization = db('user a')->join('user_auth b','a.id = b.uid')->where(['a.id'=>$this->user_data['id']])->select();
		$this->assign('list',$authorization);
		return $this->showtpl('user/Profile/accredit');
	}
	/**
	 * 查看授权证书
	**/
	public function certificate($id = '0'){
		$info = db('user_auth a')->join('user b','a.uid = b.id')->where(['a.id'=>$id])->find();
		$levelInfo = db('agent_level')->find($info['level_id']);
		if(!empty($levelInfo['position'])){
    		$position = unserialize($levelInfo['position']);
    		$textArr = array();
    		// 授权名称
    		if(!empty($position['name'])){
                if(!empty($position['name']['position'])){
                    $posArr = explode(',', $position['name']['position']);
                    $textArr['name']['left'] = $posArr[0];
                    $textArr['name']['top'] = $posArr[1] + 20;
                }
                $textArr['name']['text'] = $info['auth_name'];
                $textArr['name']['fontSize'] = $position['name']['size'];
                $textArr['name']['fontColor'] = hex2rgba($position['name']['color']);
                $textArr['name']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['name']['angle'] = 0;
                $textArr['name']['center'] = 0;
    		}
    		// 身份证
    		if(!empty($position['card'])){
                if(!empty($position['card']['position'])){
                    $posArr = explode(',', $position['card']['position']);
                    $textArr['card']['left'] = $posArr[0];
                    $textArr['card']['top'] = $posArr[1] + 20;
                }
                $textArr['card']['text'] = isset($info['idcard']) ? $info['idcard'] : '';
                $textArr['card']['fontSize'] = $position['card']['size'];
                $textArr['card']['fontColor'] = hex2rgba($position['card']['color']);
                $textArr['card']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['card']['angle'] = 0;
                $textArr['card']['center'] = 0;
    		}
    		// 微信号
    		if(!empty($position['weixin'])){
                if(!empty($position['weixin']['position'])){
                    $posArr = explode(',', $position['weixin']['position']);
                    $textArr['weixin']['left'] = $posArr[0];
                    $textArr['weixin']['top'] = $posArr[1] + 20;
                }
                $textArr['weixin']['text'] = isset($info['wechat']) ? $info['wechat'] : '';
                $textArr['weixin']['fontSize'] = $position['weixin']['size'];
                $textArr['weixin']['fontColor'] = hex2rgba($position['weixin']['color']);
                $textArr['weixin']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['weixin']['angle'] = 0;
                $textArr['weixin']['center'] = 0;
    		}
    		// 地址
    		if(!empty($position['address'])){
                if(!empty($position['address']['position'])){
                    $posArr = explode(',', $position['address']['position']);
                    $textArr['address']['left'] = $posArr[0];
                    $textArr['address']['top'] = $posArr[1] + 20;
                }
                $address = isset($info['address']) ? $info['address'] : '';
                $textArr['address']['text'] = getCity($info['province']).getCity($info['city']).getCity($info['area']).$address;
                $textArr['address']['fontSize'] = $position['address']['size'];
                $textArr['address']['fontColor'] = hex2rgba($position['address']['color']);
                $textArr['address']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['address']['angle'] = 0;
                $textArr['address']['center'] = 0;
    		}
    		// 淘宝ID
    		if(!empty($position['tao'])){
                if(!empty($position['tao']['position'])){
                    $posArr = explode(',', $position['tao']['position']);
                    $textArr['tao']['left'] = $posArr[0];
                    $textArr['tao']['top'] = $posArr[1] + 20;
                }
                $textArr['tao']['text'] = isset($info['taobao']) ? $info['taobao'] : '';
                $textArr['tao']['fontSize'] = $position['tao']['size'];
                $textArr['tao']['fontColor'] = hex2rgba($position['tao']['color']);
                $textArr['tao']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['tao']['angle'] = 0;
                $textArr['tao']['center'] = 0;
    		}
    		// 手机号码
    		if(!empty($position['phone'])){
                if(!empty($position['phone']['position'])){
                    $posArr = explode(',', $position['phone']['position']);
                    $textArr['phone']['left'] = $posArr[0];
                    $textArr['phone']['top'] = $posArr[1] + 20;
                }
                $textArr['phone']['text'] = $info['phone'];
                $textArr['phone']['fontSize'] = $position['phone']['size'];
                $textArr['phone']['fontColor'] = hex2rgba($position['phone']['color']);
                $textArr['phone']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['phone']['angle'] = 0;
                $textArr['phone']['center'] = 0;
    		}
    		// 代理级别
    		if(!empty($position['grape'])){
                if(!empty($position['grape']['position'])){
                    $posArr = explode(',', $position['grape']['position']);
                    $textArr['grape']['left'] = $posArr[0];
                    $textArr['grape']['top'] = $posArr[1] + 20;
                }
                $textArr['grape']['text'] = $levelInfo['name'];
                $textArr['grape']['fontSize'] = $position['grape']['size'];
                $textArr['grape']['fontColor'] = hex2rgba($position['grape']['color']);
                $textArr['grape']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['grape']['angle'] = 0;
                $textArr['grape']['center'] = 0;
    		}
    		// 授权编码
    		if(!empty($position['number'])){
                if(!empty($position['number']['position'])){
                    $posArr = explode(',', $position['number']['position']);
                    $textArr['number']['left'] = $posArr[0];
                    $textArr['number']['top'] = $posArr[1] + 20;
                }
                $textArr['number']['text'] = $this->config['brand_prefix'].$info['auth_number'];
                $textArr['number']['fontSize'] = $position['number']['size'];
                $textArr['number']['fontColor'] = hex2rgba($position['number']['color']);
                $textArr['number']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['number']['angle'] = 0;
                $textArr['number']['center'] = 0;
    		}
    		// 授权期限开始时间
    		if(!empty($position['start'])){
                if(!empty($position['start']['position'])){
                    $posArr = explode(',', $position['start']['position']);
                    $textArr['start']['left'] = $posArr[0];
                    $textArr['start']['top'] = $posArr[1] + 20;
                }
                $textArr['start']['text'] = date('Y年m月d日',strtotime($info['start_time']));
                $textArr['start']['fontSize'] = $position['start']['size'];
                $textArr['start']['fontColor'] = hex2rgba($position['start']['color']);
                $textArr['start']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['start']['angle'] = 0;
                $textArr['start']['center'] = 0;
    		}
    		// 授权期限结束时间
    		if(!empty($position['end'])){
                if(!empty($position['end']['position'])){
                    $posArr = explode(',', $position['end']['position']);
                    $textArr['end']['left'] = $posArr[0];
                    $textArr['end']['top'] = $posArr[1] + 20;
                }
                $textArr['end']['text'] = date('Y年m月d日',strtotime($info['end_time']));
                $textArr['end']['fontSize'] = $position['end']['size'];
                $textArr['end']['fontColor'] = hex2rgba($position['end']['color']);
                $textArr['end']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['end']['angle'] = 0;
                $textArr['end']['center'] = 0;
    		}
    		$isImg = ROOT_PATH.'public'.str_replace("\\", '/', $levelInfo['tplimg']);
    		// 头像
    		$config = array(
    			'text'=>$textArr,
    			'background'=>$isImg, // 背景图
    		);
    		$returnFile = ROOT_PATH.'public'.DS.'uploads/certificate/qrcode_'.$info['uid'].'.jpg'; // 生成文件
		    createPoster($config,$returnFile);
            $this->assign('imgUrl',"/uploads/certificate/qrcode_".$info['uid'].".jpg");
            return $this->showtpl('user/Profile/certificate');
    	}else{
    		return $this->GmError('授权证书配置信息不完整','该代理级别的授权证书配置尚未完善，请联系管理进行处理');
    	}
	}

	/**
	 * 收款账户
	**/
	public function bank(){
        $account = db('user_wallet')->where('uid',$this->user_data['id'])->find();
        $this->assign('account',$account);
		return $this->showtpl('user/Profile/bank');
	}
    /**
     * 添加或编辑收款账号
    **/
    public function dobank($id = 0){
        if(!isPost()){
            $account = db('user_wallet')->where('id',$id)->find();
            $this->assign('account',$account);
            return $this->showtpl('user/Profile/dobank');
        }else{
            $data = $_POST;
            $data['uid'] = $this->user_data['id'];
            $data['timestamp'] = date('Y-m-d H:i:s');
            if($data['id']){
                $state = db('user_wallet')->where('id',$data['id'])->update($data);
            }else{
                $state = db('user_wallet')->insertGetId($data);
            }
            if($state){
                return $this->success('保存成功',url('bank'));
            }else{
                return $this->error('保存失败');
            }
        }
    }

    /**
     * 收货地址
    **/
    public function address(){
        $list = db('user_address')->where(['uid'=>$this->user_data['id']])->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->showtpl('user/Profile/address');
    }
    public function doaddress($id = 0){
        $orderId = isset($_GET['oid']) ? $_GET['oid'] : '0';
        if(!isPost()){
            $region = db('region')->where('parent_id','0')->select();
            $this->assign('region',$region);
            $info = db('user_address')->find($id);
            $this->assign('info',$info);
            return $this->showtpl('user/Profile/doaddress');
        }else{
            $data = $_POST;
            $data['uid'] = $this->user_data['id'];
            $data['is_true'] = isset($data['is_true']) ? $data['is_true'] : '0';
            if($data['is_true'] == 1){
                db('user_address')->where('is_true','1')->update(['is_true'=>0]);
            }
            if($data['id']){
                $state = db('user_address')->where('id',$data['id'])->update($data);
            }else{
                $data['timestamp'] = date('Y-m-d H:i:s');
                $state = db('user_address')->insertGetId($data);
            }
            if($state){
                if($orderId <> 0){
                    $url = urls('order/choose').'?id='.$orderId;
                }else{
                    $url = urls('address');
                }
                return $this->success('保存成功',$url);
            }else{
                return $this->error('保存失败');
            }
        }
    }

    /**
     * 专属资料
    **/
    public function information(){

        return $this->showtpl('user/Profile/information');
    }

	/**
	 * 升级
	**/
	public function upgrade(){
        // 获取升级设置参数
        $upgradeReward = unserialize($this->config['upgrade']);
        $userupgrade = $upgradeReward[$this->user_data['level_id']];
        $where['uid'] = $this->user_data['id'];
        $where['level_id'] = array('<',$this->user_data['level_id']);
        $upgrade = db('user_upgrade')->where($where)->select();
        $this->assign('list',$upgrade);
        $yeji = db('performance')->where(['agent_id'=>$this->user_data['id']])->sum('money');

        $this->assign('yeji',$yeji);
        $this->assign('upgradeReward',$userupgrade);
		return $this->showtpl('user/Profile/upgrade');
	}
    public function upgrade_info(){
        $upgradeReward = unserialize($this->config['upgrade']);
        $userupgrade = $upgradeReward[$this->user_data['level_id']];
        $yeji = db('performance')->where(['agent_id'=>$this->user_data['id']])->sum('money');
        if($userupgrade['num'] > 0){
            if($userupgrade['num'] > $yeji){
                return $this->GmError('升级条件未达标','你的销售业绩尚未达标，不能升级哦');
                die;
            }
        }else{
            return $this->GmError('升级条件未达标','你的销售业绩尚未达标，不能升级哦');
            die;
        }
        $goods = db('goods')->where('status','1')->select();
        $goodsArr = array();
        if(!empty($goods)){
            foreach($goods as $k2=>$vo){
                $agentMoney = unserialize($vo['agent']);
                $levelIds = trim($userupgrade['level']);
                $goodsArr[$k2] = $vo;
                $goodsArr[$k2]['agent_price'] =  isset($agentMoney[$levelIds]) ? $agentMoney[$levelIds] : '0';
            }
        }
        cookie('UpgradelevelId',$userupgrade['level']);
        $this->assign('goods',$goodsArr);
        $user_level = db('agent_level')->find($userupgrade['level']);
        $this->assign('use_level',$user_level);
        $this->assign('upgradeReward',$userupgrade);
        $region = db('region')->where('parent_id','0')->select();
        $this->assign('region',$region);

        return $this->showtpl('user/Profile/upgrade_info');    
    }
    public function upgrade_order($type = 'index'){

        return $this->showtpl('user/Profile/upgrade_order');  
    }
    /**
     * 删除操作
    **/
    public function del(){
        if(!isAjax()){
            return json(['code'=>'0']);
        }else{
            $id = (int) $_POST['id'];
            switch ($_POST['table']) {
                case 'wallet': // 删除收款账号
                    $state = db('user_wallet')->where('id',$id)->delete();
                    $txt = "删除成功！";
                break;
                case 'address': // 删除地址
                    $state = db('user_address')->where('id',$id)->update(['status'=>'0']);
                    $txt = "还原成功！";
                break;
                case 'goods': // 彻底删除
                    $state = db('goods')->where('id',$id)->delete();
                    $txt = "彻底删除成功！";
                break;
            }
            if($state){
                return $this->success($txt);
            }else{
                return $this->error('删除失败');
            }
        }
    }

}