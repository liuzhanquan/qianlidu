<?php
namespace app\api\controller;
use app\common\controller\Home;
use app\api\model\User;
use app\api\model\Agrent as Ag;
use \think\Validate;
class Agent extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 我的代理信息接口
     * @param $userid      cookie('userInfo')
     * @return array
     */
    public function Index(){
        empty(input('limit')) ?  $limit = 3 : $limit = input('limit');
        $userInfo = $this->userInfo();
        $id = $userInfo['id'];
        $agent['team'] = db('User')->where('parent_id = '.$id.' or top_parent ='.$id)->field('id,avatar')->limit($limit)->select();
        $agentId = db('agent')->where('uid',$id)->column('id')[0];
        $agent['A1'] = $this->card_count($agentId,1,1);
        $agent['A2'] = $this->card_count($agentId,2,1);
        $agent['B1'] = $this->card_count($agentId,1,2);
        $agent['B2'] = $this->card_count($agentId,2,2);
        $agent['C1'] = $this->card_count($agentId,1,3);
        $agent['C2'] = $this->card_count($agentId,2,3);

        return_ajax('成功',200,$agent);
    }

    /**
     * 卡片种类、数量统计
     * @param $id
     * @param $type
     * @param $style
     * @return array
     */
    public function card_count($id,$type, $style){
        $card['card_type'] = $type;
        $card['card_style'] = $style;
        $card['count'] = db('agentCard')->where(['gid'=>$id,'card_type'=>$type,'card_style'=>$style])->count();
        return $card;
    }

    /**
     * 我的客户接口
     * @param $userid      cookie('userInfo')
     * @return array
     */
    public function teamList(){
        empty(input('page')) ? $page = 1 : $page = input('page');
        empty(input('limit')) ?  $limit = 5 : $limit = input('limit');
        $userInfo = $this->userInfo();
        $id = $userInfo['id'];
        $agent['team'] = db('User')->where('parent_id = '.$id.' or top_parent ='.$id)->field('id,avatar')->limit(($page-1)*$limit,$limit)->select();

        return_ajax('成功',200,$agent);
    }

    /**
     * 卡号卡密查询
     * @param $userid      cookie('userInfo')
     * @return array
     */
    public function cardList(){
        empty(input('page')) ? $page = 1 : $page = input('page');
        empty(input('limit')) ?  $limit = 5 : $limit = input('limit');
        if( !empty(input('type')) ){
            $where['card_type'] = input('type');
        }else{
            return_ajax('数据错误，卡片类型不存在',400);
        }
        if( !empty(input('style')) ){
            $where['card_style'] = input('style');
        }else{
            return_ajax('数据错误，卡片样式不存在',400);
        }
        
        if( !empty(input('state')) || input('state') === '0' ){
            $where['card_state'] = input('state');
        }

        $userInfo = $this->userInfo();
        $agentId = db('agent')->where('uid',$userInfo['id'])->column('id')[0];
        $card = db('agentCard')->where($where)->limit(($page-1)*$limit,$limit)->order('stop_time')->field('id,card_num,card_state,password,start_time,stop_time')->select();

            foreach( $card as $key=>$item ){
                if( $item['card_state'] === 0 && input('type') == 1 ){
                    $card[$key]['password'] = '********';
                }
                $card[$key]['start_time'] = time_date($item['start_time']);
                $card[$key]['stop_time'] = time_date($item['start_time']);
            }

        return_ajax('成功',200,$card);
    }



}
