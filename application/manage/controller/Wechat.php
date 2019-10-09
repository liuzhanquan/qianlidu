<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Wechat extends Admin{

	
    /**
     * 基础设置
    **/
    public function index(){
        if(!isPost()){

            /*微信设置 start*/
            $wechat = unserialize($this->config['wechat']);
            $this->assign('wechat',$wechat);
            return $this->fetch();
        }else{
            $data = $_POST;
            if(isset($data['wechat'])){
                $data['wechat'] = serialize($data['wechat']);
            }
            $state = model('common/Config')->saveInfo($data);
            if($state){
                return $this->success('操作成功！','');
            }else{
                return $this->error('操作失败！');
            }
        }
    }
    /**
     * 菜单导航
    **/
    public function navs(){
        $list = db('wechat_navs')->order('id asc')->select();
        $cate = new \lib\Category(['id','parent','name','cname']);
        $clist = $cate->getTree($list);
        $this->assign('list', $clist);
        return $this->fetch();
    }
    /**
     * 菜单操作
    **/
    public function donavs($id = '0'){
        $this->view->engine->layout('api_layout');
        if(!isPost()){
            $list = db('wechat_navs')->order('id asc')->select();
            $cate = new \lib\Category(['id','parent','name','cname']);
            $clist = $cate->getTree($list);
            $this->assign('list', $clist);
            $info = db('wechat_navs')->find($id);
            $this->assign('info', $info);
            return $this->fetch();
        }else{
            $data = $_POST;
            if($data['id']){
                // 编辑
                $state = db('wechat_navs')->where('id',$data['id'])->update($data);
            }else{
                // 新增
                if($data['parent'] == '0'){
                    $num = db('wechat_navs')->where('parent','0')->count();
                    if($num >= 3){
                        return $this->error('一级栏目只能添加三个');
                    }
                }else{
                    $num = db('wechat_navs')->where('parent',$data['parent'])->count();
                    if($num >= 5){
                        return $this->error('二级栏目只能添加五个');
                    }
                }
                $state = db('wechat_navs')->insertGetId($data);
            }
            if($state){
                return $this->success('操作成功！',url('navs'),'1');
            }else{
                return $this->error('操作失败','','1');
            }
        }
    }
    /**
     * 同步菜单到微信
    **/
    public function creatmenu(){
        if(!isPost()){
            return json(['code'=>0]);
        }else{
            $list = db('wechat_navs')->where('parent','0')->select();
            $button = array();
            foreach($list as $k=>$vo){
                $son = db('wechat_navs')->where('parent',$vo['id'])->select();
                if(!empty($son)){
                    $sonArr = array();
                    foreach($son as $k2=>$v2){
                        if($v2['type'] == 'view'){
                            $sonArr[$k2]['type'] = $v2['type'];
                            $sonArr[$k2]['name'] = $v2['name'];
                            $sonArr[$k2]['url'] = $v2['content'];
                        }else if($v2['type'] == 'click'){
                            $sonArr[$k2]['type'] = $v2['type'];
                            $sonArr[$k2]['name'] = $v2['name'];
                            $sonArr[$k2]['key'] = $v2['name'];
                        }
                    }
                    $button[$k]['name'] = $vo['name'];
                    $button[$k]['sub_button'] = $sonArr;
                }else{
                    if($vo['type'] == 'view'){
                        $button[$k]['type'] = $vo['type'];
                        $button[$k]['name'] = $vo['name'];
                        $button[$k]['url'] = $vo['content'];
                    }else if($vo['type'] == 'click'){
                        $button[$k]['type'] = $vo['type'];
                        $button[$k]['name'] = $vo['name'];
                        $button[$k]['key'] = $vo['content'];
                    }
                }
            }
            $menu['button'] = $button;
            // $newmenu = json_encode($menu,true);
            $weObj = initWechat();
            $result = $weObj->createMenu($menu);
            if($result){
                return $this->success('生成成功');
            }else{
                return $this->error('生成失败');
            }
        }
    }

}
