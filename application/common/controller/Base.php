<?php
namespace app\common\controller;
use \think\Controller;
use Symfony\Component\HttpFoundation\Response;

class Base extends Controller{

	protected $request;
	protected $config;
	protected $data = [];


	/**
  	 * 初始化系统数据
	**/
	public function _initialize(){
		$this->request = \think\Request::instance();
		$this->data = $this->request->param();
		$this->setConfig();
	}

	/**
  	 * 获取系统配置信息设置为全局信息
	**/
	protected function setConfig(){
		$config = model('common/Config')->getConfig();
		$config['action'] = request()->action();
		$config['controller'] = request()->controller();
		$this->config = $config;
		config($config);
		$this->assign('config',$config);
	}
	/**
  	 * 定义模版渲染
	**/
	protected function showtpl($name = '',$type = '',$tpl= false,$data = array()){
		$name = $name ? $name : request()->action();
		// if(request()->isMobile()){
		// 	$path = $tpl ? $type.'mobile'.DS : config('tpl').DS.'m'.DS.$type;
		// }else{
		// 	$path = $tpl ? $type : config('tpl').DS.$type;
		// }
		$path = $tpl ? $type : config('tpl').DS.$type;
		$reData = $this->view->fetch($path.$name,$data);
        //模板包含
        if(preg_match_all('/<!--{#include\s* [\"|\'](.*)[\"|\']}-->/', $reData, $matches)){
            foreach ($matches[1] as $k => $v) {
                $ext=explode('.', $v);
                $ext=end($ext);
                $file=substr($v, 0, -(strlen($ext)+1));
                $phpText = $this->view->fetch($path.$file);
                $reData = str_replace($matches[0][$k], $phpText, $reData);
            }
        }
		echo $reData;
	}
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->showtpl('404');
	}
	public function show404($tpl = ''){
		header("HTTP/1.0 404 Not Found");
		$this->showtpl('404',$tpl);
	}
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed  $msg    提示信息
     * @param string $submsg    二次解释
     * @param mixed  $data   返回的数据
     * @param int    $wait   跳转等待时间
     * @param array  $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function GmError($msg = '', $submsg = null, $data = '', $wait = 3, array $header = [])
    {
        $type = $this->getResponseType();
        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
            'submsg'  => $submsg,
            'wait' => $wait,
        ];

        if ('html' == strtolower($type)) {
            $template = \think\Config::get('template');
            $view = \think\Config::get('view_replace_str');

            $result = \think\View::instance($template, $view)
                ->fetch(\think\Config::get('dispatch_gmerror'), $result);
        }

        $response = \think\Response::create($result, $type)->header($header);

        throw new \think\exception\HttpResponseException($response);
    }

    protected function GmSuccess($msg = '', $submsg = null, $data = '', $wait = 3, array $header = [])
    {
        $type = $this->getResponseType();
        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
            'submsg'  => $submsg,
            'wait' => $wait,
        ];

        if ('html' == strtolower($type)) {
            $template = \think\Config::get('template');
            $view = \think\Config::get('view_replace_str');

            $result = \think\View::instance($template, $view)
                ->fetch(\think\Config::get('dispatch_gmerror'), $result);
        }

        $response = \think\Response::create($result, $type)->header($header);

        throw new \think\exception\HttpResponseException($response);
    }

	public function captcha(){
		$captcha = new \lib\Vcode;
		$captcha->showImage();
	}
	/**
	 * 导出excel表格
	**/
    protected function createtable($list,$filename,$header=array(),$index = array()){    
        header("Content-type:application/vnd.ms-excel");    
        header("Content-Disposition:filename=".$filename.".xls");    
        $teble_header = implode("\t",$header);  
        $strexport = $teble_header."\r";  
        foreach ($list as $row){
            foreach($index as $val){  
                $strexport.=$row[$val]."\t";     
            }  
            $strexport.="\r";   
      
        }    
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);    
        exit($strexport);       
    }   
	// 发送短信
	public function sendsms(){
		if(!isAjax()){
    		return json(['code'=>0]);
		}else{
			$data = $_POST['data'];
    		if(empty($data['phone'])){
    			return $this->error('请输入手机号码!');
    		}
    		if(!isPhoneNum($data['phone'])){
    			return $this->error('手机号码格式不正确!');
    		}
    		if(empty($data['code'])){
    			return $this->error('请输入图片验证码!');
    		}
	        if(!captcha_check($data['code'])){
    			return $this->error('图片验证码不正确!');
	        }
	        $smsCon = unserialize($this->config['sms']);
    		// 查询最新手机号码发送短信时间
    		$new_msgId = db('sms')->where('phone',$data['phone'])->max('id');
    		$new_msg = db('sms')->where('id',$new_msgId)->find();
    		$endtime = $new_msg['timestamp'] + $smsCon['second'];//120秒内只能发送一次
    		if(time() < $endtime){
    			return $this->error('请等待'.($endtime - time()).'秒再试');
    		}
	        // 获取发送短信信息
	        $msg = db('sms_tpl')->where('tag',$data['type'])->find();
	        if(empty($msg)){
	        	return $this->error('请求参数错误');
	        }
	        switch ($data['type']) {
	        	case 'user_passw':
	        		// 会员密码找回
	        		$exist_phone = db('user')->where(['name'=>$data['phone']])->find();
	        		if(empty($exist_phone)){
	        			return $this->error('该手机号尚未注册');
	        			die;
	        		}
	        		$code = mt_rand('100000','999999');
	        		$sendMsg = str_replace('#验证码#', $code, $msg['content']);
        		break;
	        }
	        // 发送短信
	        $status = sendSms($data['phone'],$sendMsg,$code);
	        if($status){
				return $this->success('发送成功');
			}else{
				return $this->error('发送失败');
			}
		}
	}
    /*
     * 改变状态
     */
    public function setStatus(){
		if(!isAjax()){
    		return json(['code'=>0]);
		}else{
			$data = $_POST;
			$state = db($data['table'])->where($data['field'],$data['id'])->update([$data['name']=>$data['status']]);
			if($state){
				return $this->success('更新成功！');
			}else{
				return $this->error('操作失败！');
			}
		}
    }
    /**
     * 获取城市信息
    **/
    public function getregion(){
        if(!isAjax()){
            return json(['code'=>0]);
        }else{
            $data = $_POST;
            $list = db('region')->field('id,name')->where('parent_id',$data['id'])->select();
            return json($list);
        }
    }
    /*
     * 获取地区
     */
    public function getqrcode(){
    	$url = isset($_GET['url']) ? $_GET['url'] : 'index';
    	$trueUrl = unlock_url($url);
    	$qrCode = new \Endroid\QrCode\QrCode($trueUrl);
    	$qrCode->setSize(300);
    	$qrCode
	    ->setWriterByName('png')
	    ->setMargin(10)
	    ->setEncoding('UTF-8')
	    ->setErrorCorrectionLevel('high')
	    ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
	    ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
	    // ->setLabel('Scan the code', 16, __DIR__.'/../assets/noto_sans.otf', LabelAlignment::CENTER)
	    // ->setLogoPath(__DIR__.'/../assets/symfony.png')
	    ->setLogoWidth(150)
	    ->setValidateResult(false);
	    header('Content-Type: '.$qrCode->getContentType());
		echo $qrCode->writeString();
    }
    

    public function getTwon(){
    	$parent_id = input('get.parent_id/d');
    	$data = db('region')->where("parent_id",$parent_id)->select();
    	$html = '';
    	if($data){
    		foreach($data as $h){
    			$html .= "<option value='{$h['id']}'>{$h['name']}</option>";
    		}
    	}
    	if(empty($html)){
    		echo '0';
    	}else{
    		echo $html;
    	}
    }

}