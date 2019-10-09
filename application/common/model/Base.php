<?php
namespace app\common\model;
use \think\Model;
use \think\Request;

class Base extends Model{
	
    /**
     * 获取列表
     * @return array
     */
    public function loadList() {
        $params = func_get_args();

        $where = !empty($params[0]) ? $params[0] : [];
        $limit = !empty($params[1]) ? $params[1] : 0;
        $order = !empty($params[2]) ? $params[2] : $this->getPk() . ' desc';
        return $this->order($order)->select();
    }

    /**
     * 获取数量
     * @param array $where
     * @return mixed
     */
    public function countList($where = []) {
        return $this->where($where)->count();
    }
	/**
     * 保存信息
     * @param string $type
     * @param array $data
     * @return bool
    **/
    public function saveData($data = [],$type = 'add'){
    	
    	if (!$data) {
            $data = Request::instance()->param();
        }
        if ($type == 'add') {
        	if (method_exists($this, '_addBefore')) {
                $data = $this->_addBefore($data);
            }
            if (!$data) {
	            return false;
	        }
            if (method_exists($this, '_saveAfter')) {
            	$id = $this->_saveAfter($data,$type);
                if (!$id) {
                    return false;
                }
            }
            return $id;
        }
        if ($type == 'edit') {
        	if (method_exists($this, '_editBefore')) {
                $data = $this->_editBefore($data);
            }
            if (!$data) {
	            return false;
	        }
            if (method_exists($this, '_saveAfter')) {
            	$id = $this->_saveAfter($data,$type);
                if (!$id) {
                    return false;
                }
            }
            return $id;
        }
        return false;
    }
}
