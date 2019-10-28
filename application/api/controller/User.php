<?php
namespace app\api\controller;
use app\common\controller\Home;
use app\api\model\Order;
use app\api\model\User as UDb;
use \think\Validate;
class User extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 个人中心接口
     * author Jason
     * time    2019-09-30 
     * @return array
     */


    public function Index(){

        $userInfo = userdecode(cookie('userInfo'));
        $data['user'] =  db('user')->where(['id'=>$userInfo['id']])->field('avatar,id,level,phone,charge_time')->find();
        //确定会员到期时间，  到期提醒 未到期显示时间
        if( $data['user']['charge_time'] > time() ){
            $data['user']['charge_time'] = date('Y-m-d H:i:s',$data['user']['charge_time']);
        }else{
            $data['user']['charge_time'] = "会员已到期";
        }
		
        if( $data['user']['level'] > 0 ){
            $agent = db('agent')->where('uid',$userInfo['id'])->field('id,start_time,stop_time,business')->find();
            if( !empty($agent) ){
                $data['agent_id'] = $agent;
                $data['agent_id']['start_time'] = time_date($data['agent_id']['start_time']);;
                $data['agent_id']['stop_time']  = time_date($data['agent_id']['stop_time']);;
            }

            $data['order'] = Order::with('goods')
                ->where('start_time','>',time())
                ->where('status','<',2)
                ->field('aduly,baby,addr_list,start_time,add_time,gid,status')
                ->order('start_time')
                ->find();

            if( !empty($data['order']) ){
                $data['order']['add_time'] = time_date($data['order']['add_time']); 
                $data['order']['start_time'] = time_date($data['order']['start_time']); 
            }

        }
        $data['config'] = $this->configInfo();
        return_ajax('成功',200,$data);
    }

    /**
     * 用户激活接口
     * author Jason
     * time    2019-09-30 
     * @param  phone      手机号
     * @param  code       手机验证码
     * @param  card_num   激活卡号
     * @param  card_pass  卡密码
     * @return array
     */
    public function userActivate(){

        if( empty(input('phone')) ) return_ajax('手机号不能为空',400);
        if( !phoneNum(input('phone')) ) return_ajax('手机号格式不正确',400);
        if( empty(input('code')) ) return_ajax('验证码不能为空',400);
        if( empty(input('card_num')) ) return_ajax('卡号不能为空',400);
        if( empty(input('card_pass')) ) return_ajax('卡密不能为空',400);

        $userInfo = userdecode(cookie('userInfo'));
		
		//查看手机号是否已经绑定别的用户
		$res1 = db('user')
				->where('id','neq',$userInfo['id'])
				->where('phone','eq',input('phone'))
				->count();
		if( $res1 ) return_ajax('该手机号已经绑定别的用户，无法重复绑定',400);
		
        $res = db('user')
               ->where(['id'=>$userInfo['id']])
               // ->where('level','neq',0)
               ->find();
			   
		//判断用户是否绑定手机号
		$phone = 0;
		if( empty($res['phone']) ){
			$phone = input('phone');
		}else{
			if( $res['phone'] <> input('phone') ) return_ajax('您的手机号码与绑定手机号码不一致',400);
		}
		
		
        // if(!empty($res)) return_ajax('用户已激活，无需再次激活',400);

        $this->checkPhoneCms(input('phone'),input('code'));
        $chagetime = $this->cardActivate(input('card_num'),input('card_pass'),$userInfo['id']);
        $chagetime = 100;
        $this->chargeUserTime($userInfo['id'],$res['charge_time'],$chagetime,$phone);
        
        
        return_ajax('激活成功',200);
    }

    /**
     * 会员等级修改  会员时间设置
     * author Jason
     * time    2019-09-30 
     * @param  id           用户ID
     * @param  user_charge  用户会员到期时间
     * @param  card_charge  续期时间
     * @return array
     */
    public function chargeUserTime($id, $user_charge, $card_charge,$phone = 0){
        $date['level'] = 1;
		
        if( $user_charge < time() ){
            $date['charge_time'] = time() + $card_charge;
            $str = '激活成功';
        }else{
            $date['charge_time'] = $user_charge + $card_charge;
            $str = '会员续期成功';
        }
		
		if( !empty( $phone ) ){
			$date['phone'] = $phone;
		}
		
        $info = db('user')->where('id',$id)->update($date);

        if( $info ){
            return_ajax($str,200);
        }else{
            return_ajax('激活失败',200);
        }
    }


    /**
     * 用户激活接口
     * author Jason
     * time    2019-09-30
     * @param  card_num      卡号
     * @param  card_pass     卡密
     * @param  userid        用户ID
     * @return array
     */
    public function cardActivate($card_num,$card_pass,$userid){
        
        $card = db('agentCard')
                ->where('card_num',$card_num)
                ->field('id,password,card_state,charge_time,start_time,stop_time,up_num,update_time')
                ->find();
        //判断卡片是否存在
        if( empty($card) ) return_ajax('请填写正确的卡号',400);
        
        //判断卡片是否已激活
        if( $card['card_state'] == 1 ) return_ajax('该激活码已被使用！！',400);

        if( $card['start_time'] > time() ){
            return_ajax('卡片未到使用期',400);
        }else if( $card['stop_time'] < time() ){
            return_ajax('卡片已过期',400);
        }
        //获取时间差
        $is_day = strtotime(date("Y-m-d"),time())-strtotime(date("Y-m-d",$card['update_time']));

        //判断时间是否是今天  并实验次数是否超过10次
        if( $card['up_num'] > 10 && $is_day < 86400 ){
            return_ajax('卡片错误次数超过，请明天再试',400);
        }
        //判断时间是否是今天 不是今天清零重新算
        if($is_day >= 86400){
            $card['up_num'] = 0;
        }

        $udata['update_time'] = time();
        
        if( md5($card['password']) !== md5($card_pass) ){
            $udata['up_num'] = $card['up_num']+1;
            db('agentCard')->where('card_num',$card_num)->update($udata);
            return_ajax('卡号密码错误',400);

        }else{

            $udata['card_state'] = 1;
            db('agentCard')->where('card_num',$card_num)->update($udata);
            $ndata['cid'] = $card['id'];
            $ndata['uid'] = $userid;
            $ndata['add_time'] = time();
            db('cardUser')->insert($ndata);

        }

        return $card['charge_time'];
            
    }

    /**
     * 短信验证
     * author  Jason
     * time    2019-09-30 
     * @param  phone   手机号
     * @param  code    验证码    
     * @return array
     */
    public function checkPhoneCms($phone,$code){

        $info = db('sms')->where('phone',$phone)->find();


        //判断时间是否大于60秒
        if( (time() - (int)$info['add_time']) > 60 ){
            return_ajax('验证码已过期，请获取新的验证码',400);
        }

        if( md5($info['code']) !== md5($code) ){
            return_ajax('验证码错误',400);
        }
       

    }



    /**
     * 发送短信接口
     * author  Jason        
     * time    2019-09-30 
     * @param  phone      手机号
     * @return array
     */
    public function usersms(){

        if( !phoneNum(input('phone')) ) return_ajax('手机号格式不正确',400);

        $code = mt_rand(100000,999999);

        $data['phone'] = input('phone');
        $data['code']  = $code;
        $data['add_time'] = time();
        $info = db('sms')->where('phone',input('phone'))->column('add_time');
        

        if( empty($info[0]) ){
            $res = db('sms')->insert($data);
        }else{
			//判断时间是否大于60秒
			if( (time() - (int)$info[0]) < 60 ){
				return_ajax('一分钟内只能发送一次哦',400);
			}
            $res = db('sms')->where('phone',input('phone'))->update($data);
        }
        if( $res ){
            return_ajax('短信发送成功',200);
        }else{
            return_ajax('短信发送失败',400);
        }

    }














    /**
     * 查看短信验证码 测试用
     * author  Jason
     * time    2019-09-30 
     * @param  phone      手机号
     * @return array
     */
    public function PhoneCmsFind(){
        $phone = input('phone');
        $info = db('sms')->where('phone',$phone)->find();


        //判断时间是否大于60秒
        // if( (time() - (int)$info['add_time']) > 60 ){
        //     return_ajax('请获取新的验证码',400);
        // }

        return_ajax('验证码：'.$info['code'],400);
       

    }

    /**
     * 查看激活卡  测试用
     * author  Jason
     * time    2019-09-30 
     * @return array
     */

    public function getCard(){
        $info = db('agent_card')->select();


        return_ajax($info,400);
       

    }














}
