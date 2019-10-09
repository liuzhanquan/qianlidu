<?php  
namespace app\common\model;
use \app\common\model\Base;

class SysMenu extends Base{

	protected $auto = [];
	protected $insert = [];
	protected $update = [];
    protected $validate = [
        'rule'=>[
            'name'=>'require',
        ],
        'msg'=>[
            'name.require' => '名称必须填写',
        ],
    ];

    public function loadList($where = array(),$limit=20,$order = 'id desc'){
        if($limit){
            $list =  $this->where($where)->limit($limit)->order($order)->select();
        }else{
            $list =  $this->where($where)->limit($limit)->select();
        }
        $nList = array();
        if(!empty($list)){
            foreach($list as $k=>$vo){
                $build_url = [
                    'spm'=>time(),
                    'sid'=>$vo['id'],
                ];
                $nList[$k] = $vo;
                $nList[$k]['url'] = url($vo['model'].$vo['url']).'?'.http_build_query($build_url);
            }
        }
        return $list;
    }
    
    public function loadSearchList($where = array(),$order = 'id asc'){
        $list = $this->where($where)->order($order)->paginate(20,false,['query'=>request()->param()]);
        if(empty($list)){
            return [];
        }
        $result_list['list'] = $list;
        $result_list['page'] = $list->render();
        return $result_list;
    }

    public function selectList($where = array(),$limit = '20',$order = 'id asc'){
        if($limit){
            $list = $this->where($where)->limit($limit)->order($order)->select();
        }else{
            $list = $this->where($where)->order($order)->select();
        }
        return $list;
    }
    
    public function getOne($where){
        $result = $this->where($where)->find();
        return $result;
    }
    /**
     * 获取指定ID的所有上级
     * @param int $catId
     * @return array
     */
    public function getParents($catId){  
        $categorys = $this->selectList(array(),'0');
        $tree=array();  
        foreach($categorys as $item){  
            if($item['id']==$catId){  
                if($item['parent']>0)  
                    $tree=array_merge($tree,$this->getParents($item['parent']));  
                $tree[]=$item;    
                break;    
            }  
        }  
        return $tree;  
    }  
    //方法二,迭代  
    public function getParents2($categorys,$catId){  
        $tree=array();  
        while($catId != 0){  
            foreach($categorys as $item){  
                if($item['id']==$catId){  
                    $tree[]=$item;  
                    $catId=$item['parent'];  
                    break;    
                }  
            }  
        }  
        return $tree;  
    }  

    /**
     * 获取分类树
     * @param array $where
     * @param int $limit
     * @param string $order
     * @param int $patrntId
     * @return array
     */
    public function loadTreeList(array $where = [], $order = 'id desc', $patrntId = 0) {
        $class = new \lib\Category(['id', 'parent', 'name', 'cname']);
        $list = $this->selectList($where,'0',$order);
        if(empty($list)){
            return [];
        }
        return $class->getTree($list, $patrntId);
    }
    public function loadChild($where = array(),$order = 'id desc',$id = 0){
        $db_list = $this->where($where)->where('status','1')->order($order)->select();
        \think\Db::close();
        if(!empty($db_list)){
            $data = new \lib\Category(['id','parent','name','cname']);
            $list = $data->getChild($id,$db_list);
        }
        $nList = array();
        if(!empty($list)){
            foreach($list as $k=>$vo){
                $build_url = [
                    'spm'=>time(),
                    'sid'=>$vo['id'],
                ];
                $nList[$k] = $vo;
                if($vo['type'] == '1'){ // 线下
                    $nList[$k]['url'] = url('column/cate',array('id'=>$vo['id'])).'?'.http_build_query($build_url);
                }elseif($vo['type'] == '2'){
                    $nList[$k]['url'] = url('column/cate',array('id'=>$vo['id'])).'?'.http_build_query($build_url);
                }
            }
        }
        return $nList;
    }

    public function loadCategoryPath($patrntId = 0){
        $class = new \lib\Category(['id', 'parent', 'name', 'cname']);
        $list = $this->loadList([],'0',[]);
        $list = $class->getPath($list, $patrntId);
        $nList = [];
        foreach($list as $k=>$vo){
            $nList[$k] = $vo;
            $nList[$k]['url'] = url('/category/'.$vo['id']);
        }
        return $nList;
    }

    public function _saveAfter($data = [],$type){
        if($type == 'add'){
            $state = $this->allowField(true)->save($data);
            $id = $this->getLastInsID();
        }
        if ($type === 'edit') {
            $state = $this->allowField(true)->save($data,['id'=>$data['id']]);
            $id = $data['id'];
        }
        if($state){
            return $id;
        }else{
            $this->error = $this->getError() ? $this->getError() : '没有可修改的数据！';
            return false;
        }
        return false;
    }
}