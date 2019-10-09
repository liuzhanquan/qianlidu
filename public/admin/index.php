<?php

// [ 应用入口文件 ]
define('APP_DIR',dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
// 定义应用目录
define('APP_PATH', APP_DIR . 'application/');

define('BIND_MODULE','manage');

// 加载框架引导文件
require APP_DIR . 'framework/start.php';
