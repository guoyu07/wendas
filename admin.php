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

define('APP_DEBUG',0);
define('APP_NAME', 'xiaohua');
define('CONF_PATH','./Admin/conf/');
define('RUNTIME_PATH','./Admin/runtime/');
define('TMPL_PATH','./Admin/tpl/');
define('UPLOAD_PATH','./uploads/');
//define('HTML_PATH','./data/html/');
define('APP_PATH','./Admin/');
define('CORE','./ThinkPHP');
require(CORE.'/ThinkPHP.php');
require("./Admin/conf/config.php");
?>
