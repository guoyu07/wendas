<?php
/**
 *项目公共配置
 **/
return array(
	 //'配置项'=>'配置值'
	'SHOW_PAGE_TRACE'=>true,


	'LOAD_EXT_CONFIG' 		=> 'db,route,constants',		
	'APP_AUTOLOAD_PATH'     =>'@.ORG',
	'OUTPUT_ENCODE'         =>  true, 			//页面压缩输出
	'PAGE_NUM'				=> 15,

	/*Cookie配置*/
	'COOKIE_PATH'           => '/',     		// Cookie路径
    'COOKIE_PREFIX'         => '',      		// Cookie前缀 避免冲突

	/*定义模版标签*/
	'TMPL_L_DELIM'   		=>'{',			//模板引擎普通标签开始标记
	'TMPL_R_DELIM'			=>'}',				//模板引擎普通标签结束标记
	'APP_GROUP_LIST'        => 'Newjokes,Hot,About,Shop,Ajax,Tags,User,Joke,Account,Public',      // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
	'DEFAULT_GROUP'         => 'Newjokes',  // 默认分组
    'DEFAULT_MODULE'        => 'Index', // 默认模块名称
    'DEFAULT_ACTION'        => 'index', // 默认操作名称
    'APP_AUTOLOAD_PATH'		=>'@.TagLib',//

    'SESSION_EXPIRE' 		=> 	600,
	
	'TMPL_PARSE_STRING' => array(				//更改系统的模版的常量
							'__PUBLIC__' => '',
							),
	//'SHOW_PAGE_TRACE'		=>true,

);

?>