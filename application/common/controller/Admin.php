<?php
namespace app\common\controller;
use app\common\controller\Base;

class Admin extends Base{

	protected $admin_data = [];
	protected $infoModule = [];

	public function _initialize(){
		parent::_initialize();
		$this->initSystem();
        $this->doAction();
		$this->setLink();
        $this->view->engine->layout(true);
	}
    /**
     * 链接合并
     * @access protected
     * @return void
     */
    protected function setLink(){
        $link = load_config('application/link');
        // 获取文章分类
        // $where['type'] = array('in','2,3');
        // $list = model('common/Category')->allList($where);
        // if(!empty($list)){
        //     $nList = array();
        //     foreach($list as $vo){
        //         $nList[$vo['name']] = $vo['url'];
        //     }
        //     $link = array_merge($link,$nList);
        // }
        $this->assign('sysLink',$link);
    }
	
    /**
     * 后台登录检查
     * @access protected
     * @return void
     */
    protected function initSystem(){
    	$cookie_admin_id = cookie('admin_cookie');
		if(empty($cookie_admin_id)){
			if($this->request->isAjax()){
				return $this->error('您还没有登录！',url('/login'));
			}else{
                return $this->redirect(url('/login'));
				exit();
			}
		}
        $this->admin_data = model('Admin')->getOne(['a.id'=>$cookie_admin_id]);
        if (method_exists($this, '_infoModule')) {
            $this->infoModule = $this->_infoModule();
        }
        $this->assign('admin',$this->admin_data);
        $this->checkPurview();
        
        $this->getPath();
    }
    private function getPath(){
        $topKey = $this->request->controller(); // 控制器
        $action = $this->request->action(); // 操作方法
        $data = db('sys_menu')->select();
        $where = array(
            'model'=>$topKey,
            'url'=>$action,
        );
        $info = db('sys_menu')->where($where)->find();
        if(empty($info)){
            $s_where['model'] = $topKey;
            $s_where['extend'] = array('like','%'.$action.'%');
            $info = db('sys_menu')->where($s_where)->find();
        }
        $path = array();
        if(!empty($info)){
            $categroy = new \lib\Category(['id','parent','name','cname']);
            $path = $categroy->getPath($data,$info['id']);
        }
        $this->assign('url_path',$path);
    }
    /**
     * 权限检测
     * @return bool
     */
    private function checkPurview() {
        $topKey = $this->request->controller(); // 控制器
        $action = $this->request->action(); // 操作方法
        $userRoleId = $this->admin_data['role_id'];
        $roleGroup = db('admin_group')->where('gid',$userRoleId)->find();
        if($roleGroup['is_sys'] <> 1){
            // 栏目权限
            $menu = db('sys_menu')->where('id','in',$roleGroup['menu_power'])->order('sort asc')->select();
            $powerArr = explode(',', $roleGroup['power']);
            $powerArr[] = 'Index/index';
            $delArr = array();
            foreach($powerArr as $vo){
                $powarr = explode('/', $vo);
                if(count($powarr) >= 3){
                    $delArr[] = $powarr[2];
                }
            }
            $menuPower = $topKey.'/'.$action;
            if($action == 'del'){
                // 判断删除权限
                $table = $this->data['table'];
                if(!in_array($table,$delArr)){
                    return $this->error('您没有删除权限！');
                }
            }else{

                if(!in_array($menuPower,$powerArr)){
                    return $this->error('您没有权限！');
                }
            }

        }else{
            // 超级管理员
            $menu = db('sys_menu')->order('sort asc')->select();
        }
        foreach($menu as $k=>$vo){
            $menu[$k] = $vo;
            $menu[$k]['action'] = $vo['url'];
            if(empty($vo['extend'])){
                $extStr = $vo['url'];
            }else{
                $extStr = $vo['url'].','.$vo['extend'];
            }
            $menu[$k]['extend'] = explode(',', $extStr);
            $menu[$k]['url'] = url($vo['model'].'/'.$vo['url']);
        }

        $li = new \lib\PHPTree(['id', 'parent']);
        $list = $li->makeTree($menu,array('parent_key'=>'parent','expanded'=>true));
        //dump($list);exit();
        $this->assign('topNav', $list);
    }
    /**
     * 超时自动退出登录
     * @access protected
     * @return void
     */
	protected function doAction(){
		$logintime = cookie('login_time');
		$time = request()->time();
		if($time > $logintime || ($time - $logintime) > 60){
			$newLogintime = $logintime + 3600;
			cookie('login_time',$newLogintime);
		}
	}
    /**
     * 通用删除
     * @access public
     * @return void
     */
    public function del(){
        if(!isAjax()){
            return json_encode(['code'=>'0']);
        }else{

        }
    }
    /**
     * 选择链接
     * @access public
     * @param $type 1 店铺 2商品
     * @return void
     */
    public function selecturl($type = '1'){
        if($type == '1'){
            $list = model('shop/Seller')->getList(['a.status'=>'1']);
        }else{
            $list = model('shop/Goods')->loadList(['status'=>'1']);
        }
        $back = isset($_GET['back']) ? $_GET['back'] : '0';
        $this->assign('list',$list['list']);
        $this->assign('page',$list['page']);
        $this->assign('type',$type);
        $this->assign('back',$back);
        return $this->fetch('index/selecturl');
    }
    /**
     * 通用异步更新排序
     * @access public
     * @return void
     */
    public function sorts(){
        if(!isAjax()){
            return json_encode(['code'=>'0']);
        }else{
            $data = $_POST;
            if(!is_numeric($data['value'])){
                return $this->error('请输入有效的数字！');
            }
            // 無操作
            $info = db($data['table'])->where($data['field'],$data['id'])->find();
            if($info[$data['name']] != $data['value']){
                $state = db($data['table'])->where($data['field'],$data['id'])->update([$data['name']=>$data['value']]);
                if($state){
                    return $this->success('更新成功！');
                }else{
                    return $this->error('更新失败！');
                }
            }
        }
    }
    /**
     * 根据品牌获取代理级别
    **/
    public function getlevel(){
        if(!isAjax()){
            return json(['code'=>0]);
        }else{
            $data = $_POST;
            $list = db('level')->field('id,name')->where('brand_id',$data['brandId'])->select();
            return json($list);
        }
    }
}
