<?php
// 应用公共文件
if (!function_exists('sp_password')) {
    /**
     * 密码加密方法
     * @param string $pw 要加密的字符串
     * @return string
     */
    function sp_password($pw,$authcode=''){
        if(empty($authcode)){
            $authcode=config("authcode");
        }
        $result="###".md5(md5($authcode.$pw));
        return $result;
    }
}
if (!function_exists('sp_password_old')) {
    /**
     * 密码加密方法 (X2.0.0以前的方法)
     * @param string $pw 要加密的字符串
     * @return string
     */
    function sp_password_old($pw){
        $decor=md5(config('prefix'));
        $mi=md5($pw);
        return substr($decor,0,12).$mi.substr($decor,-4,4);
    }
}
if (!function_exists('sp_compare_password')) {
    /**
     * 密码比较方法,所有涉及密码比较的地方都用这个方法
     * @param string $password 要比较的密码
     * @param string $password_in_db 数据库保存的已经加密过的密码
     * @return boolean 密码相同，返回true
     */
    function sp_compare_password($password,$password_in_db){
        if(strpos($password_in_db, "###")===0){
            return sp_password($password)==$password_in_db;
        }else{
            return sp_password_old($password)==$password_in_db;
        }
    }
}
if (!function_exists('sp_random_string')) {
    /**
     * 随机字符串生成
     * @param int $len 生成的字符串长度
     * @return string
     */
    function sp_random_string($len = 6) {
        $chars = array(
                "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
                "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
                "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
                "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
                "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}
if(!function_exists('load_config')){
    /**
     * 简化类配置加载
    **/
    function load_config($file,$enforce = true){
        return \think\Config::loadConfig($file, $enforce);
    }
}
if(!function_exists('save_config')){
    /**
     * 配置保存
     * @param $file
     * @param $config
     * @return array|bool
     */
    function save_config($file, $config){
        return \think\Config::saveConfig($file, $config);
    }
}
if(!function_exists('msubstr')){
    /**
     * 字符串截取，支持中文和其他编码
     *
     * @access public
     * @param string $str
     *          需要转换的字符串
     * @param string $start
     *          开始位置
     * @param string $length
     *          截取长度
     * @param string $charset
     *          编码格式
     * @param string $suffix
     *          截断显示字符
     * @return string
     */
    function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
        if (function_exists ( "mb_substr" ))
            $slice = mb_substr ( $str, $start, $length, $charset );
        elseif (function_exists ( 'iconv_substr' )) {
            $slice = iconv_substr ( $str, $start, $length, $charset );
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all ( $re [$charset], $str, $match );
            $slice = join ( "", array_slice ( $match [0], $start, $length ) );
        }
        
        return $suffix && $str != $slice ? $slice . '...' : $slice;
    }
}
if(!function_exists('phoneNum')){
    /**
    * 验证手机号是否正确
    * @param int $mobile
    */
    function phoneNum($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}

if(!function_exists('idCard')){
    /**
    * 身份证号码正确性检查
    * @param string $id
    */
    function idCard($id){
        $id = strtoupper($id); 
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
        $arr_split = array(); 
        if(!preg_match($regx, $id)) {
            return FALSE; 
        }
        if(15==strlen($id)) //检查15位 
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
            @preg_match($regx, $id, $arr_split); 
            //检查生日日期是否正确 
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
            if(!strtotime($dtm_birth)){
                return FALSE; 
            }else{
                return TRUE; 
            }
        }else{
            //检查18位 
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
            @preg_match($regx, $id, $arr_split); 
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
            if(!strtotime($dtm_birth)) //检查生日日期是否正确 
            {
                return FALSE; 
            }else{
                //检验18位身份证的校验码是否正确。 
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
                $sign = 0; 
                for ( $i = 0; $i < 17; $i++ ) {
                    $b = (int) $id{$i}; 
                    $w = $arr_int[$i]; 
                    $sign += $b * $w; 
                }
                $n = $sign % 11; 
                $val_num = $arr_ch[$n]; 
                if ($val_num != substr($id,17, 1)) {
                    return FALSE; 
                }else{
                    return TRUE; 
                }
            }
        }
    }
}
if(!function_exists('url_set_value')){
    /**
    * 替换url参数
    * @param string $url
    * @param string $key
    * @param string $value
    */
    function url_set_value($url,$key,$value) { 
        parse_str($url,$arr);
        $arr[$key]=$value;
        return '?'.http_build_query($arr); 
    }
}

if(!function_exists('date_tran')){
    /**
     * 时间格式化
     * @param $time
     * @return string
     */
    function date_tran($time) {
        $agoTime = (int)$time;

        // 计算出当前日期时间到之前的日期时间的毫秒数，以便进行下一步的计算
        $time = time() - $agoTime;

        if ($time >= 31104000) { // N年前
            $num = (int)($time / 31104000);

            return $num . '年前';
        }
        if ($time >= 2592000) { // N月前
            $num = (int)($time / 2592000);

            return $num . '月前';
        }
        if ($time >= 86400) { // N天前
            $num = (int)($time / 86400);

            return $num . '天前';
        }
        if ($time >= 3600) { // N小时前
            $num = (int)($time / 3600);

            return $num . '小时前';
        }
        if ($time > 60) { // N分钟前
            $num = (int)($time / 60);

            return $num . '分钟前';
        }

        return '刚刚';
    }
}

if(!function_exists('ToXml')){
    /**
     * 输出xml字符
     */
    function ToXml($arr = []) {
        if (! is_array ( $arr ) || count ( $arr ) <= 0) {
            exit ( "数组数据异常！" );
        }
        
        $xml = "<xml>";
        foreach ( $arr as $key => $val ) {
            if (is_numeric ( $val )) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
if(!function_exists('FromXml')){
    /**
     * 将xml转为array
     */
    function FromXml($xml) {
        if (! $xml) {
            exit ( "xml数据异常！" );
        }
        // 将XML转为array
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        return $arr;
    }
}

if(!function_exists('isAjax')){
    /**
     * 判断AJAX
     */
    function isAjax() {
        if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') && !isset($_GET['ajax'])) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('isGet')){
    /**
     * 判断GET
     */
    function isGet() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('isPost')){
    /**
     * 判断POST
     */
    function isPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        } else {
            return false;
        }
    }
}
if(!function_exists('isWechat')){
    /**
     * 判断微信访问
     * @return bool
     */
    function isWechat() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }

        return false;
    }
}
if (!function_exists('isWeixinBrowser')) {
    // 判断是否是在微信浏览器里
    function isWeixinBrowser($from = 0) {
        if ((! $from && defined ( 'IN_WEIXIN' ) && IN_WEIXIN) || isset ( $_GET ['is_stree'] ))
            return true;
        
        $agent = $_SERVER ['HTTP_USER_AGENT'];
        if (! strpos ( $agent, "icroMessenger" )) {
            return false;
        }
        return true;
    }
}
if (!function_exists('week_name')) {
	function week_name($number = null) {
	    if ($number === null)
	        $number = date ( 'w' );
	    
	    $arr = array (
	            "日",
	            "一",
	            "二",
	            "三",
	            "四",
	            "五",
	            "六" 
	    );
	    
	    return '周' . $arr [$number];
	}
}
if (!function_exists('daytoweek')) {
	// 日期转换成星期几
	function daytoweek($day = null) {
	    $day === null && $day = date ( 'Y-m-d' );
	    if (empty ( $day ))
	        return '';
	    
	    $number = date ( 'w', strtotime ( $day ) );
	    
	    return week_name ( $number );
	}
}
if (!function_exists('outText')) {
	// 回车处理
	function outText($content){
	    $patten = array("\r\n", "\n", "\r");
	    $str=str_replace($patten, "<br />", $content); 
	    return $str;
	}
}
if (!function_exists('lock_url')) {
    //加密函数  
    function lock_url($txt,$key=''){  
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";  
        $nh = rand(0,64);  
        $ch = $chars[$nh];  
        $mdKey = md5($key.$ch);  
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);  
        $txt = base64_encode($txt);  
        $tmp = '';  
        $i=0;$j=0;$k = 0;  
        for ($i=0; $i<strlen($txt); $i++) {  
            $k = $k == strlen($mdKey) ? 0 : $k;  
            $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;  
            $tmp .= $chars[$j];  
        }  
        return urlencode($ch.$tmp);  
    }  
}  
if (!function_exists('unlock_url')) {
    //解密函数  
    function unlock_url($txt,$key=''){  
        $txt = urldecode($txt);  
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";  
        $ch = $txt[0];  
        $nh = strpos($chars,$ch);  
        $mdKey = md5($key.$ch);  
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);  
        $txt = substr($txt,1);  
        $tmp = '';  
        $i=0;$j=0; $k = 0;  
        for ($i=0; $i<strlen($txt); $i++) {  
            $k = $k == strlen($mdKey) ? 0 : $k;  
            $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);  
            while ($j<0) $j+=64;  
            $tmp .= $chars[$j];  
        }  
        return base64_decode($tmp);  
    }  
}  

if (!function_exists('combineDika')) {
	/**
	 * 多个数组的笛卡尔积
	*
	* @param unknown_type $data
	*/
	function combineDika() {
	    $data = func_get_args();
	    $data = current($data);
	    $cnt = count($data);
	    $result = array();
	    $arr1 = array_shift($data);
	    foreach($arr1 as $key=>$item) 
	    {
	        $result[] = array($item);
	    }       

	    foreach($data as $key=>$item) 
	    {                                
	        $result = combineArray($result,$item);
	    }
	    return $result;
	}
}


if (!function_exists('combineArray')) {
	/**
	 * 两个数组的笛卡尔积
	 * @param unknown_type $arr1
	 * @param unknown_type $arr2
	*/
	function combineArray($arr1,$arr2) {         
	    $result = array();
	    foreach ($arr1 as $item1) 
	    {
	        foreach ($arr2 as $item2) 
	        {
	            $temp = $item1;
	            $temp[] = $item2;
	            $result[] = $temp;
	        }
	    }
	    return $result;
	}
}
if (!function_exists('group_same_key')) {
	/**
	 * 将二维数组以元素的某个值作为键 并归类数组
	 * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
	 * array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
	 * @param $arr 数组
	 * @param $key 分组值的key
	 * @return array
	 */
	function group_same_key($arr,$key){
	    $new_arr = array();
	    foreach($arr as $k=>$v ){
	        $new_arr[$v[$key]][] = $v;
	    }
	    return $new_arr;
	}
}
if (!function_exists('get_client_ip')) {
	// 获取客户端ip
	function get_client_ip(){
	    $cip = 'unknown';
	    if($_SERVER['REMOTE_ADDR']){
	        $cip = $_SERVER['REMOTE_ADDR'];
	    }elseif(getenv('REMOTE_ADDR')){
	        $cip = getenv('REMOTE_ADDR');
	    }
	    return $cip;
	}
}
if (!function_exists('urls')) {
    function urls($url,$array = array()){
        return url($url,$array,'html',true);
    }
}
// 生成拼团推广图片
function pinThumb($id,$uid){
    // 获取拼团商品信息
    $info = db('goods_tuan')->where('id',$id)->find();
    // 获取会员信息
    $user = db('user')->where('uid',$uid)->field('username,avatar')->find();
    // 开始拼接图片
    $goodsImg = local_img($info['image'],$id);
    $userImg = local_img($user['avatar']);
    // 生成二维码
    $goodsUrl = 'pin_'.$id.'_'.$uid;
    $tUrl = urls('auth/weekapp').'?v='.lock_url($goodsUrl);
    $qr_name = 'qrcode'.$uid.'.jpg';
    $qr_url = createQrcode($tUrl,$qr_name);
    $userQr = ROOT_PATH.'router/uploads/qrcode/'.$qr_name;
    $config = array(
        'image'=>array(
            array(
                'url'=>$goodsImg,     //商品图片
                'stream'=>0,
                'left'=>0,
                'top'=>0,
                'right'=>0,
                'bottom'=>0,
                'width'=>750,
                'height'=>750,
                'opacity'=>100,
                'center'=>0,
            ),
            array(
                'url'=>$userImg,     //用户头像
                'stream'=>0,
                'left'=>320,
                'top'=>780,
                'right'=>0,
                'bottom'=>0,
                'width'=>100,
                'height'=>100,
                'opacity'=>100,
                'center'=>1,
            ),
            array(
                'url'=>$userQr,     //用户头像
                'stream'=>0,
                'left'=>246,
                'top'=>1189,
                'right'=>0,
                'bottom'=>0,
                'width'=>110,
                'height'=>110,
                'opacity'=>100,
                'center'=>0,
            ),
        ),
        'text'=>array(
            array(
                'text'=>"".$info['price']."元 已拼".$info['virtual_num']."件", // 商品价格
                'left'=>0,
                'top'=>960,
                'fontPath'=>ROOT_PATH.'font/font.ttf',//字体文件
                'fontSize'=>18,
                'fontColor'=>'171,171,171',
                'angle'=>0,
                'center'=>1,
            ),
            array(
                'text'=>$user['username'],
                'left'=>320,
                'top'=>910,
                'fontPath'=>ROOT_PATH.'font/font.ttf',//字体文件
                'fontSize'=>18,
                'fontColor'=>'113,113,113',
                'angle'=>0,
                'center'=>1,
            ),
            array(
                'text'=>$info['goods_name'],
                'left'=>20,
                'top'=>1000,
                'right'=>20,
                'fontPath'=>ROOT_PATH.'font/font.ttf',//字体文件
                'fontSize'=>18,
                'fontColor'=>'0,0,0',
                'angle'=>0,
                'center'=>0,
            )
        ),
        'background'=>ROOT_PATH.'router'.DS.'uploads/qrcode.png', // 背景图
    );
    $returnFile = ROOT_PATH.'router'.DS.'uploads/goods/pintuan_qrcode_'.$id.'_'.$uid.'.jpg'; // 生成文件
    createPoster($config,$returnFile);
    return 'http://file.taoyuantoday.com/goods/pintuan_qrcode_'.$id.'_'.$uid.'.jpg';
}
function hex2rgba($color, $opacity = false, $raw = false) {
    $default = 'rgb(0,0,0)';
    //Return default if no color provided
    if(empty($color))
          return $default; 
    //Sanitize $color if "#" is provided 
    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }
    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
            return $default;
    }

    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);

    if($raw){
        if($opacity){
            if(abs($opacity) > 1) $opacity = 1.0;
            array_push($rgb, $opacity);
        }
        $output = $rgb;
    }else{
        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            // $output = 'rgb('.implode(",",$rgb).')';
            $output = implode(",",$rgb);
        }
    }

    //Return rgb(a) color string
    return $output;
}
if (!function_exists('createPoster')) {
    /**
     * 生成宣传海报
     * @param array  参数,包括图片和文字
     * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
     * @return [type] [description]
     */
    function createPoster($config = array(), $filename = ""){
        if(empty($filename)){
            header("content-type: image/png");
        }
        $imageDefault = array(
            "left"=>0,
            "top"=>0,
            "right"=>0,
            "bottom"=>0,
            "width"=>100,
            "height"=>100,
            "opacity"=>100
        );
        $textDefault = array(
            "text"=>'',
            "left"=>0,
            "top"=>0,
            "fontSize"=>32,
            "fontColor"=>'255,255,255',
            "angle"=>0,
        );
        // $background = @imagecreatefrompng($config['background']);
        $backgroundInfo = getimagesize($config['background']);
        if($backgroundInfo['mime'] == 'image/jpeg'){
            $background = @imagecreatefromjpeg($config['background']);
        }else if($backgroundInfo['mime'] == 'image/gif'){
            $background = @imagecreatefromgif($config['background']);
        }else if($backgroundInfo['mime'] == 'image/png'){
            $background = @imagecreatefrompng($config['background']);
        }
        $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2],false);
        $backgroundFun = $backgroundFun($config['background']);
        // $backgroundWidth = imagesx($background); // 图片宽度
        // $backgroundHeight = imagesy($background); // 图片高度
        $backgroundWidth = $backgroundInfo[0]; // 图片宽度
        $backgroundHeight = $backgroundInfo[1]; // 图片高度
        $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
        // imagecopyresampled($imageRes,$background,0,0,0,0,$backgroundWidth,$backgroundHeight,$backgroundWidth,$backgroundHeight);
        if(!empty($config['image'])){
            foreach ($config['image'] as $key => $val) {
                $val = array_merge($imageDefault,$val);
                $info = getimagesize($val['url']);
                $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
                if($val['stream']){
                    //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                //建立画板 ，缩放图片至指定尺寸
                $canvas=imagecreatetruecolor($val['width'], $val['height']);
                imagefill($canvas, 0, 0, $color);
                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
                //放置图像
                if($val['center'] == '1'){

                    $x         = ceil((750 - $val['width']) / 2); //计算文字的水平位置

                    imagecopymerge($imageRes,$canvas, $x,$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);
                }else{
                    imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);
                }
            }
        }
        //处理文字
        if(!empty($config['text'])){
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault,$val);
                list($R,$G,$B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
                $text = autowrap($val['fontSize'],0,$val['fontPath'],$val['text'],710); // 自动转行
                if($val['center'] == '1'){
                    $fontWidth = imagefontwidth($val['fontSize']);
                    $textWidth = $fontWidth * mb_strlen($val['text']);
                    $x         = ceil((750 - $textWidth) / 2); //计算文字的水平位置
                    imagettftext($imageRes,$val['fontSize'],$val['angle'],$x,$val['top'],$fontColor,$val['fontPath'],$text);
                }else{
                    imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$text);
                }
            }
        }
        //生成图片
        if(!empty($filename)){
            $res = imagejpeg ($imageRes,$filename,90); //保存到本地
            imagedestroy($imageRes);
            if(!$res) return false;
            return $filename;
        }else{
            imagejpeg ($imageRes);//在浏览器上显示
            imagedestroy($imageRes);
        }
    }
}
if (!function_exists('autowrap')) {
    function autowrap($fontsize, $angle, $fontface, $string, $width) {
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        $letter = array();
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        for ($i=0;$i<mb_strlen($string);$i++) {
            $letter[] = mb_substr($string, $i, 1);
        }
        foreach ($letter as $l) {
            $teststr = $content." ".$l;
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $content .= "\n";
            }
            $content .= $l;
        }
        return $content;
    }
}
if (!function_exists('thumb')) {
    //生成缩略图
    function thumb($img,$w,$h){
        $imgFile = ROOT_PATH.'public'.str_replace("\\", '/', $img);
        if(file_exists($imgFile)){
            $isImg = ROOT_PATH.'public'.str_replace("\\", '/', $img);
            $image = \think\Image::open($isImg);
            $image->thumb(640, 1008,\think\Image::THUMB_FIXED)->save($isImg);
            $file=$img;
        }else{
            $file='uploadfile/nopic.gif';
        }
        return $file;
    }
}

/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, $allowFiles, &$files = array())
{
    $config = load_config('application/upload');
    if (!is_dir($path)) return null;
    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $path2 = $path . $file;
            if (is_dir($path2)) {
                getfiles($path2, $allowFiles, $files);
            } else {
                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                    $Rootpath = ROOT_PATH . 'public' . DS.'uploads/';
                    $files[] = array(
                        // 'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                        'url'=> '/uploads/'.substr($path2, strlen($Rootpath)),
                        'mtime'=> filemtime($path2)
                    );
                }
            }
        }
    }
    return $files;
}
if(!function_exists('initWechat')){
    /**
     * 初始化微信接口
     * @param $siteId
     * @return bool
     */
    function initWechat(){
        $config = model('common/Config')->getConfig();
        $config = unserialize($config['wechat']);
        $option = [
            'token'             =>  isset($config['token']) ? $config['token'] : 'weixintoken',
            'appid'             =>  $config['appid'],
            'appsecret'         =>  $config['appsecret'],
            'encodingaeskey'    =>  $config['keycode'],
        ];
        $wObj = new \wechat\Wxextends($option);
        return $wObj;
    }
}
if(!function_exists('SendWxMessage')){
    /**
     * 发送微信信息
     * @param $siteId
     * @param $content
     * @param $openid
     * @return bool
     */
    function SendWxMessage($content,$openid){
        $wObj = initWechat();
        $sendData = array(
            "touser"=>$openid,
            "msgtype"=>"text",
            "text"=>array(
                "content"=>$content
            ),
        );
        $state = $wObj->sendCustomMessage($sendData);
    }
}


function urlDiy($url,$array = array()){
    $domain = request()->domain().$url;
    if(empty($array)){
        return $domain.'.html';
    }else{
        return $domain.'?v='.lock_url(http_build_query($array));
    }
}

function urlDiys($url,$array = array()){
    $domain = request()->domain().$url;
    if(empty($array)){
        return $domain.'.html';
    }else{
        return $domain.'?'.http_build_query($array);
    }
}

function getCity($id,$field = 'name'){
    //$item = db('region')->where('id',$id)->value($field);
    //return $item ? $item : '';
    return '';
}

function getUser($id,$field = 'auth_name'){
    if($id > 0){
        $info = db('user a')->join('user_auth b','a.id = b.uid')->where('a.id',$id)->find();
        $item = $info[$field];
    }else{
        if($field == 'avatar'){
            $config = db('config')->where('name','brand_logo')->find();
            $item = $config['value'];
        }else{
            $item = '总部';
        }
    }
    return $item ? $item : '';
}

function getLevels($ids){
    $list = db('level')->where('id','in',$ids)->select();
    $listArr = array();
    if(!empty($list)){
        foreach($list as $k=>$vo){
            $listArr[$vo['brand_id']]['name'] = $vo['name'];
            $listArr[$vo['brand_id']]['id'] = $vo['id'];
        }
    }
    return $listArr;
}
// 查询会员更高级的上级
function getTopParent($uid){
    $user = db('user')->find($uid);
    $level = db('agent_level')->find($user['level_id']);
    
}
// 获取统计信息
function getData($url,$uid){
    $data = explode('/', $url);
    if(isset($data[1])){
        switch ($data[1]) {
            case 'myagent':
                // 直属代理
                $num = db('user')->where('parent_id',$uid)->count();
            break;
            case 'money':
                // 账户余额
                $num = db('user')->where('id',$uid)->value('money');
            break;
            case 'mygoods':
                // 我的货款
                $num = '30';
            break;
            case 'rebate':
                // 我的返利
                $num = '40';
            break;
        }
        return $num;
    }
    return 0;
}
function bqwhits($hits) {
    $b=1000;
    $c=10000;
    $d=100000000;
    // if ($hits>=$b && $hits<$c){
    //     return number_format($hits/$b,'2').'';
    // }else 
    if ($hits>=$c && $hits<$d){
        return number_format($hits/$c,'2').'万';
    }else if ($hits>=$d){
        return number_format($hits/$d,'2').'亿';
    }else{
        return number_format($hits,'2');
    }
}
// 记录每个月总奖金待结算记录
function addSettlement($uid,$topPraent,$money){
    $timeCode = date('Ym');
    $exitCode = db('agent_settlement')->where(['top_parent'=>$topPraent,'agent_id'=>$uid,'timecode'=>$timeCode])->find();
    // agent_settlement
    if(empty($exitCode)){
        db('agent_settlement')->insertGetId([
            'top_parent'=>$topPraent,
            'agent_id'=>$uid,
            'money'=>$money,
            'status'=>'0',
            'timecode'=>$timeCode,
            'last_time'=>date('Y-m-d H:i:s')
        ]);
    }else{
        $upData = [
            'money' => $exitCode['money'] + $money,
            'last_time' => date('Y-m-d H:i:s'),
        ];
        db('agent_settlement')->where('id',$exitCode['id'])->update();
    }


}


if(!function_exists('return_ajax')){
    /**
     * json返回方法
     * @param $message
     * @param $status
     * @param $data
     * @return array
     */
    function return_ajax( $message, $status = 1, $data=[] ){
        exit(json_encode(['message'=>$message,'status'=>$status,'data'=>$data]));
    }
}

if(!function_exists('obj_arr')){
    /**
     * 对象转数组
     * @param $obj
     * @return array
     */
    function obj_arr($obj){
        return json_decode(json_encode($obj),true);
    }
}

if(!function_exists('userencode')){
    /**
     * 登录用户信息加密
     * @param $obj
     * @return array
     */
    function userencode($obj){
        return base64_encode(json_encode($obj));
    }
}

if(!function_exists('userdecode')){
    /**
     * 登录用户信息解密
     * @param $obj
     * @return array
     */
    function userdecode($obj){
        return json_decode(base64_decode($obj),true);
    }
}

if(!function_exists('makeOrderNum')){
    /**
     * 订单单号生成
     * @param $obj
     * @return array
     */
    function makeOrderNum(){
        return strval(substr(date('YmdHis'),2) . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 4));
    }
}

if(!function_exists('makeAgentNum')){
    /**
     * 订单单号生成
     * @param $type         卡片类型  1虚拟卡  2实体卡
     * @param $class        卡片样式  1A类卡  2 B 类卡  3 C 类卡
     * @param $num          生成数量
     * @param $start_time   卡片有效时间  开始时间
     * @param $stop_time    卡片有效时间  结束时间
     * @param $charge_time    卡片有效时间  结束时间
     * @param $gid          绑定代理商  默认不绑定
     * @return array
     */
    function makeAgentNum($type,$class,$num,$start_time,$stop_time,$charge_time,$gid=0){
                
        for( $i = 0; $i < $num; $i++ ){
            $card[$i]['card_num'] = strval( substr(date('YmdHi'),2) . mt_rand(10001,99999));
            $card[$i]['password'] = mt_rand(10000001,99999999);
            $card[$i]['card_type'] = $type;
            $card[$i]['card_style'] = $class;
            $card[$i]['gid'] = $gid;
            $card[$i]['start_time'] = $start_time;
            $card[$i]['stop_time'] = $stop_time;
            $card[$i]['charge_time'] = $charge_time;
            $card[$i]['add_time'] = time();
            $card[$i]['update_time'] = time();
        }

        return $card;
    }
}

if(!function_exists('time_date')){
    /**
     * 时间戳转日期
     * @param $obj
     * @return array
     */
    function time_date($time){
        return date('Y-m-d H:i:s',$time);
    }
}

if(!function_exists('date_go')){
    /**
     * 计算出发时间距离现在天数
     * @param $time
     * @return string
     */
    function date_go($time) {
        $agoTime = (int)$time;
        $hours   = date('H:i:s',$agoTime);
        
        // 计算出当前日期时间到之前的日期时间的毫秒数，以便进行下一步的计算
        $time = $agoTime - time();

        if ($time >= 31104000) { // N年
            $num = (int)($time / 31104000);

            $t_total =  $num . '年 '.$hours;
        }
        else if ($time >= 2592000) { // N月
            $num = (int)($time / 2592000);

            $t_total = $num . '月 '.$hours;
        }
        else if ($time >= 86400) { // N天
            $num = (int)($time / 86400);

            $t_total = $num . '天 '.$hours;
        }
        else if ($time >= 3600) { // N小时
            $num = (int)($time / 3600);

            //return $num . '小时';
            $t_total = '今天'.$hours;
        }
        else if ($time > 60) { // N分钟
            $num = (int)($time / 60);

            // $t_total = $num . '分钟前';
            $t_total = '今天1'.$hours;
        }else{
            $t_total = date('Y-m-d H:i:s',$agoTime);
        }
        return $t_total;
    }
}


if(!function_exists('charge_time')){
    /**
     * 计算出发时间距离现在天数
     * @param $time
     * @return string
     */
    function charge_time($time) {
        $agoTime = (int)$time;
        $hours   = date('H:i:s',$agoTime);
        
        // 计算出当前日期时间到之前的日期时间的毫秒数，以便进行下一步的计算
        $time = $agoTime - time();

        if ($time >= 31104000) { // N年
            $num = (int)($time / 31104000);

            $t_total =  $num . '年 ';
        }
        else if ($time >= 2592000) { // N月
            $num = (int)($time / 2592000);

            $t_total = $num . '月 ';
        }
        else if ($time >= 86400) { // N天
            $num = (int)($time / 86400);

            $t_total = $num . '天 ';
        }
        else if ($time >= 3600) { // N小时
            $num = (int)($time / 3600);

            //return $num . '小时';
        }
        else if ($time > 60) { // N分钟
            $num = (int)($time / 60);

            $t_total = $num . '分钟前';
        }else{
            $t_total = date('Y-m-d H:i:s',$agoTime);
        }
        return $t_total;
    }
}


