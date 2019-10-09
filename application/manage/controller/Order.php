<?php
namespace app\manage\controller;
use app\common\controller\Admin;

class Order extends Admin{

    /**
	 * 销售统计
    **/
    public function index($type = '-1'){
        $data = $this->data;
        $data['selkey'] = isset($_GET['selkey']) ? $_GET['selkey'] : '1';
        $data['key'] = isset($_GET['key']) ? $_GET['key'] : '';
        if(isset($data['state'])){
            $type = $data['state'];
        }
        $where = array();
        if($type > '-1'){
            $where['status'] = $type - 1;
        }
        if(isset($data['start']) && isset($data['end'])){
            if(!empty($data['start']) && !empty($data['end'])){
                $where['timestamp'] = array('between',array($data['start'],$data['end']));
            }
        }
        switch ($data['selkey']) {
            case '1': // 快递单号
                $where['order_id'] = array('like','%'.$data['key'].'%');
            break;
            case '2': // 授权名称
                $user =  db('user_auth')->where(['auth_name'=>array('like','%'.$data['key'].'%')])->select();
                $userIds = array();
                if(!empty($user)){
                    foreach($user as $vo){
                        $userIds[] = $vo['uid'];
                    }
                }
                $where['uid'] = array('in',implode(',', $userIds));
            break;
        }
        $list = db('user_order')->order('timestamp desc')->where($where)->paginate(15,false,['query'=>request()->param()]);
        $listArr = array();
        if(!empty($list)){
            foreach($list as $k=>$vo){
                $listArr[$k] = $vo;
                $listArr[$k]['son'] = db('user_order_detail')->where('order_id',$vo['order_id'])->select();
            }
        }
        if(isset($data['exp'])){
            $filename = '商品订单'.date('YmdHis').'-'.rand(0000000000,999999999);  
            $header = array('订单编号','订单信息','收货地址','下单代理','订单金额','实付金额','订单状态','下单时间');  
            $exList = db('user_order')->where($where)->select();
            $expList = array();
            if(!empty($exList)){
                foreach($exList as $k=>$vo){
                    $expList[$k] = $vo;
                    // $link = "<a href='".urls('order/info',['id'=>$vo['order_id']])."'>".$vo['order_id']."</a>";
                    $expList[$k]['order_id'] = "'".$vo['order_id']."'";
                    $address = unserialize($vo['address']);
                    $expList[$k]['address'] = $address['name'].';'.$address['phone'].';'.getCity($address['province']).','.getCity($address['city']).','.getCity($address['area']).','.$address['address'];
                    $expList[$k]['agent'] = getUser($vo['uid']).';'.getUser($vo['uid'],'level_name');
                    $expList[$k]['price'] = $vo['original_price'];
                    $expList[$k]['money'] = $vo['price'];
                    if($vo['status'] == '0'){
                        $stateTxt = '待付款';
                    }elseif($vo['status'] == '1'){
                        $stateTxt = '待发货';
                    }elseif($vo['status'] == '2'){
                        $stateTxt = '已发货';
                    }elseif($vo['status'] == '3'){
                        $stateTxt = '已完成';
                    }elseif($vo['status'] == '4'){
                        $stateTxt = '已关闭';
                    }
                    $expList[$k]['status'] = $stateTxt;
                    $orderGodos = db('user_order_detail')->where('order_id',$vo['order_id'])->select();
                    $goodsTxt = array();
                    foreach($orderGodos as $vo){
                        $goods = db('goods')->find($vo['goods_id']);
                        $goodsTxt[] = $goods['title'].'* '.$vo['num'].'件/';
                    }
                    $expList[$k]['goods'] = implode('/', $goodsTxt);
                    $expList[$k]['time'] = $vo['timestamp'];
                }
            }
            $index = array('order_id','goods','address','agent','price','money','status','time');  
            $this->createtable($expList,$filename,$header,$index);
        }

        $this->assign('list',$listArr);
        $this->assign('page',$list->render());
        $this->assign('type',$type);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function info($id = 0){
        $info = db('user_order')->where('order_id',$id)->find();
        $order_detail = array();
        if(!empty($info)){
            $order_detail = db('user_order_detail')->where('order_id',$id)->select();
        }
        $express = unserialize($info['express']);
        if(!empty($express)){
            
        }
        // $link = "https://m.kuaidi100.com/query?type=".$express['com']."&postid=".$express['number'];
        // $expressInfo = wp_file_get_contents($link);
        // $expressArr = object_array(json_decode($expressInfo));
        // $this->assign('express',$expressArr);
        $elist = getExpress('');
        $this->assign('elist',$elist);
        $this->assign('einfo',$express);

        $orderUser = db('user')->find($info['uid']);
        $orderUserLevel = db('agent_level')->find($orderUser['level_id']);
        $this->assign('orderUserLevel',$orderUserLevel);
        $this->assign('info',$info);
        $this->assign('order_detail',$order_detail);
        return $this->fetch();
    }
    /**
	 * 批量发货
    **/
    public function batch(){
        if(!isPost()){
            $express = getExpress('');
            $this->assign('express',$express);
            return $this->fetch();
        }else{
            $data = $_POST;
            $filePath = ROOT_PATH.'public'.$data['file'];
            $file = fopen($filePath,'r'); 
            while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
                $goods_list[] = $data;
            }
            $list = array();
            foreach($goods_list as $k=>$vo){
                $list[$k]['order_id'] = is_znstr($vo[0]) ? '' : $vo[0]; 
                $list[$k]['com'] = is_znstr($vo[0]) ? '' : $vo[1]; 
                $list[$k]['number'] = is_znstr($vo[0]) ? '' : substr($vo[2], 0, -1); 
            }
            if(!empty($list)){
                foreach ($list as $key => $vo) {
                    $orderId = trim($vo['order_id']);
                    $order = db('user_order')->where('order_id',$orderId)->find();
                    if(!empty($order)){
                        $expre = [
                            'com'=>$vo['com'],
                            'number'=>$vo['number']
                        ];
                        $uData = [
                            'status'=>'2',
                            'express'=>serialize($expre)
                        ];
                        db('user_order')->where('id',$order['id'])->update($uData);
                    }
                }
            }
            return $this->success('批量发货成功');
        }
    }
    public function expcsv(){

        $limit = 500;//每次只从数据库取50000条以防变量缓存太大
        // buffer计数器
        $cnt = 0;
        $mpName = 'order';
        $start_time = $this->request->param('start_time', '');
        $end_time = $this->request->param('end_time', '');
        $args['start_time'] = $start_time;
        $args['end_time'] = $end_time;
        $xlsTitle = ['订单编号', '快递公司编码','快递单号'];

        set_time_limit(0);

        $sqlCount = db('user_order')->where('status','1')->count();
        // $sqlCount = $this->get_fans_list($mpid, 0, $limit, $args, true);
        $fileName = iconv('utf-8', 'gb2312', $mpName);//文件名称
        $fileName = $fileName . date('_YmdHis');// 文件名称可根据自己情况设定
        // 输出Excel文件头，可把user.csv换成你要的文件名
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        header('Cache-Control: max-age=0');
        $fileNameArr = array();
        // 逐行取出数据，不浪费内存
        for ($i = 0; $i < ceil($sqlCount / $limit); $i++) {
            $fp = fopen($fileName . '_' . $i . '.csv', 'w'); //生成临时文件
            // chmod('attack_ip_info_' . $i . '.csv',777);//修改可执行权限
            $fileNameArr[] = $fileName . '_' . $i . '.csv';
            // 将数据通过fputcsv写到文件句柄
            fputcsv($fp, $xlsTitle);
            $start = $i * $limit;
            $dataArr = db('user_order')->where('status','1')->limit($start,$limit)->select();
            // $dataArr = $this->get_fans_list($mpid, $start, $limit, $args);
            foreach ($dataArr as $vo) {
                $cnt++;
                if ($limit == $cnt) {
                    //刷新一下输出buffer，防止由于数据过多造成问题
                    ob_flush();
                    flush();
                    $cnt = 0;
                }
                // $indata['order_id'] = "'".$vo['order_id']."'";
                $indata['order_id'] = "\t".$vo['order_id'];
                $indata['com'] = '';
                $indata['number'] = '';

                fputcsv($fp, $indata);
            }
            fclose($fp);  //每生成一个文件关闭
        }
        //进行多个文件压缩
        $zip = new \ZipArchive();
        $filename = $fileName . ".zip";
        $zip->open($filename, $zip::CREATE);   //打开压缩包
        foreach ($fileNameArr as $file) {
            $zip->addFile($file, basename($file));   //向压缩包中添加文件
        }
        $zip->close();  //关闭压缩包
        foreach ($fileNameArr as $file) {
            unlink($file); //删除csv临时文件
        }
        // $this->insertLog($this->admin_id, '', '导出公众号粉丝');
        //输出压缩文件提供下载
        header("Cache-Control: max-age=0");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename)); // 文件名
        header("Content-Type: application/zip"); // zip格式的
        header("Content-Transfer-Encoding: binary"); //
        header('Content-Length: ' . filesize($filename)); //
        @readfile($filename);//输出文件;
        unlink($filename); //删除压缩包临时文件
    }
    /**
	 * 订单明细统计
    **/
    public function details(){
        return $this->fetch();
    }
    /**
     * 单独订单发货
    **/
    public function sendorder($id = '0'){
        // $this->view->engine->layout('api_layout');
        if(!isPost()){
            $express = getExpress('');
            $order = db('user_order')->find($id);
            $this->assign('express',$express);
            $this->assign('info',$order);
            return $this->fetch();
        }else{
            $data = $this->data;
            if(empty($data['express'])){
                return $this->error('请填写快递信息');die;
            }
            $uData = [
                'status'=>'2',
                'express'=>serialize($data['express'])
            ];
            $state = db('user_order')->where('id',$data['id'])->update($uData);
            if($state){
                return $this->success('发货成功','','1');
            }else{
                return $this->error('发货失败','','');
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
                case 'order': // 删除订单
                    $order = db('user_order')->where('id',$id)->find();
                    $orderId = $order['order_id'];
                    // 如果订单已付款则退款
                    if($order['status'] == '1'){
                        db('agent_log')->insertGetId([
                            'agent_id'=>$order['uid'],
                            'money'=>$order['price'],
                            'type'=>'4',//下单退款
                            'form_type'=>'0',
                            'is_end'=>'0',
                            'remark'=>'订单退款',
                            'timestamp'=>date('Y-m-d H:i:s')
                        ]);
                        db('user')->where('id',$order['uid'])->setInc('money',$order['price']);
                    }
                    db('user_order_detail')->where('order_id',$orderId)->delete();
                    $state = db('user_order')->where('id',$id)->delete();
                    $txt = "删除成功！";
                break;
                case 'down':
                    // 确认收货
                    $state = db('user_order')->where('id',$id)->update(['status'=>'3']);
                    $txt = "确认收货成功";
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