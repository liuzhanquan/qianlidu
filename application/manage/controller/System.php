<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class System extends Admin{

    /**
     * 基础设置
    **/
    public function index($id = 1){
        if(!isPost()){
            $bank = unserialize($this->config['bank']);
            $this->assign('bank',$bank);
            $this->assign('id',$id);
            return $this->fetch();
        }else{
            $data = $_POST;
            if(isset($data['bank'])){
                $data['bank'] = serialize($data['bank']);
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
     * 修改密码
    **/
    public function passwd(){
        if(!isPost()){
            $info = model('Admin')->getOne(['a.id'=>$this->admin_data['id']]);
            $list = db('admin_group')->select();
            $this->assign('list',$list);
            $this->assign('info',$info);
            // 生成绑定微信账号二维码
            $weObj = initWechat();
            if(empty($this->admin_data['ticket'])){
                // 重新生成Ticket
                $key = "bind_".$this->admin_data['id'];
                $ticket = $weObj->getQRCode($key,'2');
                $qrcode = $weObj->getQRUrl($ticket['ticket']);
                db('admin')->where('id',$this->admin_data['id'])->update(['ticket'=>$qrcode]);
            }else{
                $qrcode = $this->admin_data['ticket'];
            }
            $this->assign('qrcode',$qrcode);
            return $this->fetch();
        }else{
            $data = $_POST;
            if($data['id']){
                // 编辑
                $state = model('Admin')->saveData($data,'edit');
            }else{
                // 添加
                $state = model('Admin')->saveData($data);
            }
            if($state){
                return $this->success('保存成功！',url('account'));
            }else{
                return $this->error(model('Admin')->getError());
            }
        }
    }
    /**
     * 子账号管理
    **/
    public function account(){
        $where['a.is_sys'] = '0';
        if(isset($_GET['key'])){
            // 关键词检索
        }
        $search = db('admin_group')->select();
        $list = model('Admin')->loadList(['a.is_sys'=>'0']);
        $this->assign('list',$list['list']);
        $this->assign('page',$list['page']);
        $this->assign('search',$search);
        return $this->fetch();
    }
    public function doaccount($id = '0'){
        if(!isPost()){
            $info = model('Admin')->getOne(['a.id'=>$id]);
            $list = db('admin_group')->select();
            $this->assign('list',$list);
            $this->assign('info',$info);
            $this->assign('id',$id);
            return $this->fetch();
        }else{
            $data = $_POST;
            if($data['id']){
                // 编辑
                $state = model('Admin')->saveData($data,'edit');
            }else{
                // 添加
                $state = model('Admin')->saveData($data);
            }
            if($state){
                return $this->success('保存成功！',url('account'));
            }else{
                return $this->error(model('Admin')->getError());
            }
        }
    }
    /**
     * 短信管理
    **/
    public function sms($type = 0){
        if(!isPost()){
            $sms = unserialize($this->config['sms']);
            $this->assign('sms',$sms);
            $this->assign('type',$type);
            return $this->fetch();
        }else{
            $data = $_POST;
            if(isset($data['sms'])){
                $data['sms'] = serialize($data['sms']);
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
     * 短信模板
    **/
    public function sms_tpl(){
        $list = db('sms_tpl')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    public function dotpl($id = 0){
        if(!isPost()){
            $info = db('sms_tpl')->find($id);
            $list = array();
            if(!empty($info['param'])){
                $list = unserialize($info['param']);
                $list = array_values($list);
            }
            $this->assign('num',count($list));
            $this->assign('list', $list);
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            $data['param'] = serialize($data['param']);
            if($data['id']){
                $state = db('sms_tpl')->where('id',$data['id'])->update($data);
            }else{
                $state = db('sms_tpl')->insertGetId($data);
            }
            if($state){
                return $this->success('操作成功',url('sms_tpl'));
            }else{
                return $this->error('操作失败');
            }
        }
    }
    /**
     * 短信发送记录
    **/
    public function sms_log(){
        $list = db('sms_log')->paginate(20,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    /**
     * 权限管理
    **/
    public function role(){
        $list = db('admin_group')->order('gid desc')->paginate();
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }
    /**
     * [wecharAdmin 微信管理员]
     * @Author   seanyang                 QQ：305492760
     * @DateTime 2019-01-03T11:32:10+0800
     * @return   [type]                   [description]
     */
    public function wechat_admin(){
        if (!isPost()) {
            $list = db('admin_wechat')->paginate();
             // 生成绑定微信账号二维码
            $weObj = initWechat();
            if(empty($this->admin_data['ticket'])){
                // 重新生成Ticket
                $key = "bind_".$this->admin_data['id'];
                $ticket = $weObj->getQRCode($key,'2');
                $qrcode = $weObj->getQRUrl($ticket['ticket']);
                db('admin')->where('id',$this->admin_data['id'])->update(['ticket'=>$qrcode]);
            }else{
                $qrcode = $this->admin_data['ticket'];
            }
            $this->assign('qrcode',$qrcode);
            $this->assign('list', $list);
            $this->assign('page', $list->render());
            return $this->fetch();
        }else{
            $data = $_POST;
            $state = db('admin_wechat')->where('id',$data['id'])->delete();
            if($state){
                return $this->success('操作成功',url('wechat_admin'));
            }else{
                return $this->error('操作失败');
            }
        }
    }
    public function dorole($id = '0'){
        if(!isPost()){
            $info = db('admin_group')->where('gid',$id)->find();
            $menu = array();
            if(!empty($info)){
                $menu = explode(',', $info['menu_power']);
            }
			//dump($info);exit();
            $this->assign('menu', $menu);
            $this->assign('info', $info);
            return $this->fetch();
        }else{
            $data = $_POST;
            
            if(isset($data['menu_power'])){
                $data['menu_power'] = implode(',', $data['menu_power']);
            }
            if(isset($data['power'])){
                $data['power'] = implode(',', $data['power']);
            }
            if($data['gid']){
                $state = db('admin_group')->where('gid',$data['gid'])->update($data);
            }else{
                $state = db('admin_group')->insertGetId($data);
            }
            if($state){
                return $this->success('操作成功',url('role'));
            }else{
                return $this->error('操作失败');
            }
        }
    }
    /**
     * 代理菜单权限
    **/
    public function menu(){
        $dList = db('diy_nav')->order('sort asc')->select();
        $cate = new \lib\Category(['id','parent_id','name','cname']);
        $list = $cate->getTree($dList,'0');
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function domenu($id = '0'){
        if(!isPost()){
            $agentMenu = db('diy_nav')->where('parent_id','0')->select();
            $info = db('diy_nav')->find($id);
            // 获取代理级别
            $level = db('agent_level')->field('id,name')->select();
            $this->assign('level',$level);
            $agent = array();
            if(!empty($info['agent'])){
                $agent = unserialize($info['agent']);
            }
            $this->assign('agentMenu',$agentMenu);
            $this->assign('agent',$agent);
            $this->assign('info',$info);
            return $this->fetch();
        }else{
            $data = $_POST;
            if(!empty($data['agent'])){
                $data['agent'] = serialize($data['agent']);
            }
            if($data['id']){
                // 编辑
                $state = db('diy_nav')->where('id',$data['id'])->update($data);
            }else{
                // 添加
                $state = db('diy_nav')->insertGetId($data);
            }
            if($state){
                return $this->success('保存成功！',url('menu'));
            }else{
                return $this->error('保存失败！');
            }

        }
    }
    /**
     * 设置代理后台菜单
    **/
    public function sethome(){
        if(!isPost()){
            /*代理首页栏目 start*/
            $menu = json_decode($this->config['jsondata'],true);
            $menuArr = array();
            if(isset($menu['range'])){
                foreach($menu['range'] as $vo){
                    $menuArr[$vo['pid']] = $vo['cid'];
                }
            }
            $this->assign('menu',$menu);
            // 
            $parentNav = db('diy_nav')->where('parent_id','0')->select();
            $this->assign('parentNav',$parentNav);
            $ontopNav = db('diy_nav')->where('ontop','1')->select();
            $topSon = array();
            foreach($ontopNav as $k=>$vo){
                $topSon[$k]['id'] = $vo['id'];
                $topSon[$k]['name'] = $vo['name'];
                $topSon[$k]['son'] = db('diy_nav')->where('parent_id',$vo['id'])->select();
                if(!empty($menuArr)){
                    if(isset($menuArr[$vo['id']])){
                        $topSon[$k]['sonid'] = $menuArr[$vo['id']];
                    }else{
                        $topSon[$k]['sonid'] = array();
                    }
                }else{
                   $topSon[$k]['sonid'] = array();
                }

            }
            $this->assign('ontopNav',$topSon);
            $nomalNav = db('diy_nav')->where('ontop','0')->select();
            $nomalSon = array();
            foreach($nomalNav as $k=>$vo){
                $nomalSon[$k]['id'] = $vo['id'];
                $nomalSon[$k]['name'] = $vo['name'];
                $nomalSon[$k]['son'] = db('diy_nav')->where('parent_id',$vo['id'])->select();
                if(!empty($menuArr)){
                    if(isset($menuArr[$vo['id']])){
                        $nomalSon[$k]['sonid'] = $menuArr[$vo['id']];
                    }else{
                        $nomalSon[$k]['sonid'] = array();
                    }
                }else{
                   $nomalSon[$k]['sonid'] = array();
                }
            }
            $this->assign('nomalNav',$nomalSon);
            return $this->fetch();
        }else{
            $data = $_POST;
            $state = model('common/Config')->saveInfo($data);
            if($state){
                return $this->success('操作成功！','');
            }else{
                return $this->error('操作失败！');
            }
        }
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
                case 'admin': // 删除至回收站
                    $state = db('admin')->where('id',$id)->delete();
                    $txt = "删除成功！";
                break;
                case 'group': // 还原商品
                    $admin = db('admin')->where('role_id',$id)->find();
                    if(!empty($admin)){
                        return $this->error('请先删除该角色下的管理员');die;
                    }
                    $state = db('admin_group')->where('id',$id)->delete();
                    $txt = "删除成功！";
                break;
                case 'goods': // 彻底删除
                    $state = db('goods')->where('id',$id)->delete();
                    $txt = "彻底删除成功！";
                break;
                case 'adver': // 彻底删除
                    $state = db('adver')->where('id',$id)->delete();
                    $txt = "删除成功！";
                break;
                case 'navs': // 彻底删除
                    $state = db('home_nav')->where('id',$id)->delete();
                    $txt = "删除成功！";
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