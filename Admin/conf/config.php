<?php
/**
 *项目公共配置
 **/
return array(

	'LOAD_EXT_CONFIG' 		=> 'db,route',		
	'APP_AUTOLOAD_PATH'     =>'@.ORG',
	'OUTPUT_ENCODE'         =>  true, 			//页面压缩输出
	'PAGE_NUM'				=> 15,
	'URL_CASE_INSENSITIVE'  => true,

	/*Cookie配置*/

	'COOKIE_PATH'           => '/',     		// Cookie路径
    'COOKIE_PREFIX'         => '',      		// Cookie前缀 避免冲突

	/*定义模版标签*/

	'TMPL_L_DELIM'   		=>'{',			//模板引擎普通标签开始标记
	'TMPL_R_DELIM'			=>'}',				//模板引擎普通标签结束标记

	'APP_GROUP_LIST'        => '',      // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
	'DEFAULT_GROUP'         => '',  // 默认分组
    'DEFAULT_MODULE'        => 'public', // 默认模块名称
    'DEFAULT_ACTION'        => 'login', // 默认操作名称
    'DEFAULT_THEME'         => 'default',
    'LANG_SWITCH_ON' 		=> true, //多语言包功能
    'DEFAULT_LANG' 			=> 'zh-cn', // 默认语言
    'LANG_AUTO_DETECT' 		=> true, // 自动侦测语言
    'TMPL_ACTION_ERROR'     => 'Public:error',
    'TMPL_ACTION_SUCCESS'   => 'Public:success',
    'APP_AUTOLOAD_PATH'		=>'@.TagLib',//
    'IGNORE_PRIV_LIST'		=>array(
						    		array(
										'module_name'=>'index',
										'action_list'=>array()
										)
						    		)
	//'SHOW_PAGE_TRACE'		=>true,

);

?>