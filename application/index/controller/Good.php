<?php
namespace app\index\controller;
use app\common\controller\Home;
use app\Index\model\Goods;
class Good extends Home{

    public function _initialize(){
        parent::_initialize();
    }

    public function index(){

        return $this->showtpl('user/index');

    }

    public function good(){
        empty(input('page')) ? $page = 1 : $page = input('page');
        empty(input('limit')) ?  $limit = 5 : $limit = input('limit');

        if( $page == 1 ){
            //获取首页banner
            $goods['banner'] = Goods::with('goodsRecommed,goodsCollect')->where(['show_banner'=>1])->select();
            $goods = obj_arr($goods);
            foreach( $goods['banner'] as $key=>$item ){
                if( $item['goods_collect'] ){
                    foreach( $item['goods_collect'] as $k=>$ite ){
                        $goods['banner'][$key]['goods_collect'][$k]['avatar'] = db('user')
                                                                                ->where('id',$ite['userid'])
                                                                                ->column('avatar')[0];
                    }
                }
            }

        }
        //获取旅游路线列表
        $goods['list'] = Goods::with('goodsRecommed,goodsT')->where(['show_banner'=>0])->limit(($page-1)*$limit,$limit)->order('rid desc')->select();
        //获取所有标签
        $goodsTag = db('goods_tag')->select();
        //对象转数组
        $goods = obj_arr($goods);

        //转换对应标签
        foreach( $goods['list'] as $key=>$item ){
            
            if( $item['goods_t'] ){
                foreach( $item['goods_t'] as $k=>$ite ){
                    
                    foreach( $goodsTag as $i ){
                        if( $i['id'] == $ite['tid'] ){
                            $goods['list'][$key]['goods_t'][$k]['tid'] = $i['name'];
                        }
                    }
                }
            }
        }
        //dump($goods);exit();



        return_ajax('成功！',200,$goods);
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
