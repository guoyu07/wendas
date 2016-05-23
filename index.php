<?php
header("Content-type: text/html; charset=utf-8");

if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value){
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value); 
		return $value; 
	}
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE); 
}
//nginx设置
/*if(!$_SERVER['PATH_INFO']) {
	$_SERVER['PATH_INFO'] = $_GET['pathinfo'];
	unset($_GET['pathinfo']);
}*/
//关闭缓存，开发模式
define('APP_DEBUG',true);
define('HTML_CACHE_ON',true);
define('DB_FIELD_CACHE',true);

define('APP_NAME', 'xiaohua');
define('CONF_PATH','./Index/Conf/');
define('RUNTIME_PATH','./Index/Runtime/');
define('TMPL_PATH','./Index/Tpl/');
define('UPLOAD_PATH','./uploads/');
define('APP_PATH','./Index/');

require('./ThinkPHP/ThinkPHP.php');
require('./Index/Conf/config.php');