<?php

// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');
// // 响应类型  
header('Access-Control-Allow-Methods:*');
// // 响应头设置  
header('Access-Control-Allow-Headers:*');

// [ 应用入口文件 ]
define('APP_DIR',dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
// 定义应用目录
define('APP_PATH', APP_DIR . 'application/');


// 加载框架引导文件
require APP_DIR . 'framework/start.php';
