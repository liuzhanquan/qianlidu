<?php  
namespace app\common\model;
use \app\common\model\Base;

class Config extends Base{


    public function getConfig() {
        $list = $this->loadList();
        
        $data = array();
        foreach($list as $vo) {
            $data[$vo['name']] = $vo['value'];
        }
        
        return $data;
    }
    public function saveInfo($data = array()) {
    	if(empty($data)){
    		$data = request()->param();
    	}
    	foreach($data as $k=>$vo){
			$upDa['value'] = $vo;
			$state = $this->where('name',$k)->update($upDa);
    	}
        return true;
    }
}