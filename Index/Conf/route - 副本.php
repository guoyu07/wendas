<?php
/**
 *网站路由配置
 **/
return array(
	/*路由设置*/
	'URL_MODEL' 			=>	2,				//URL访问模式
	'URL_ROUTER_ON'   		=> true, 			//开启路由
	'URL_HTML_SUFFIX'		=>'html',			//伪静态后缀
	'URL_ROUTE_RULES' 		=> array( 			//定义路由规则
		'rss'  => 'Newjokes/Index/rss',
		//最新笑话
		'/^text_(\d*)$/'  => 'Newjokes/Index/text?p=:1',
		'text'  => 'Newjokes/Index/text',
		
		'/^pic_(\d*)$/'  => 'Newjokes/Index/pic?p=:1',
		'pic'  => 'Newjokes/Index/pic',
			
		'/^gif_(\d*)$/'  => 'Newjokes/Index/gif?p=:1',
		'gif'  => 'Newjokes/Index/gif',
		
		'/^video_(\d*)$/'  => 'Newjokes/Index/video?p=:1',
		'video'  => 'Newjokes/Index/video',

		'/^godreply_(\d*)$/'  => 'Newjokes/Index/godreply?p=:1',
		'godreply'  => 'Newjokes/Index/godreply',

		'/^hotjoke_(\d*)$/'  => 'Newjokes/Index/hotjoke?p=:1',
		'hotjoke'  => 'Newjokes/Index/hotjoke',
		
 		'/^index_(\d*)$/'  => 'Newjokes/Index/index?p=:1',
 		//'newjokes'  => 'Newjokes/Index/index',

 		//热门
 		'/^hot\/month_(\d*)$/'  => 'Hot/Index/month?p=:1',
 		'hot/month'  => 'Hot/Index/month',
 		'/^hot\/week_(\d*)$/'  => 'Hot/Index/week?p=:1',
 		'hot/week'  => 'Hot/Index/week',
 		'/^hot\/index_(\d*)$/'  => 'Hot/Index/index?p=:1',
 		'hot'  => 'Hot/Index/index',
 		
 		//神回复
 		/*'godreply'  => 'Godreply/Index/index',
 		'/^godreply\/index_(\d*)$/'  => 'Godreply/Index/index?p=:1',*/
 		//标签
 		'/^tags\/(\d*)_(\d*)$/'  => 'Tags/Index/info?id=:1&p=:2',
 		'tags'  => 'Tags/Index/index',
 		//礼品商城
 		'/^shop\/detail_(\d*)$/'  => 'Shop/Index/detail?id=:1',
 		'/^shop\/exchange_(\d*)$/'  => 'Shop/Index/exchange?id=:1',
 		'shop/order'  => 'Shop/Index/order',
 		'/^shop\/index_(\d*)$/'  => 'Shop/Index/index?p=:1',
 		'shop'  => 'Shop/Index/index',
 		
 		//关于瞎囧
 		'about/jianjie'  => 'About/Index/jianjie',
 		'about/gonggao'  => 'About/Index/gonggao',
 		'about/shengming'  => 'About/Index/shengming',
 		'about/feedback'  => 'About/Index/feedback',
 		'about/tougao'  => 'About/Index/tougao',
 		'about/shengao'  => 'About/Index/shengao',
 		'about/shengji'  => 'About/Index/shengji',
 		'about/jiongbi'  => 'About/Index/jiongbi',
 		'about'  => 'About/Index/index',
 		//投稿 审稿
 		'joke/execute'  => 'Joke/Index/execute',
 		'joke/publish4'  => 'Joke/Index/publish4',
 		'joke/publish3'  => 'Joke/Index/publish3',
 		'joke/publish2'  => 'Joke/Index/publish2',
 		'joke/publish'  => 'Joke/Index/publish',
 		'joke/audit'  => 'Joke/Index/audit',
 		'/^joke\/search_(\d*)$/'  => 'Joke/Index/search?p=:1',
 		'joke/search'  => 'Joke/Index/search',
 		'joke'  => 'Joke/Index/index',
 		//ajax
 		'ajax/award'  => 'Ajax/Index/award',
 		'ajax/review'  => 'Ajax/Index/review',
 		'ajax/package'  => 'Ajax/Index/package',
 		
 		//笑话
 		'xiaohua/reviewrecord' => 'Xiaohua/Index/reviewrecord',
 		'xiaohua/record'  => 'Xiaohua/Index/record',
 		'xiaohua/getreview'  => 'Xiaohua/Index/getreview',
 		'xiaohua/:id\d'  => 'Xiaohua/Index/index',
 		//用户中心
 		'user/feed'  => 'User/Index/feed',
 		'user/joke'  => 'User/Index/joke',
 		'user/review'  => 'User/Index/review',
 		'user/gift'  => 'User/Index/gift',
 		'user/info'  => 'User/Index/info',
 		'user/setpassword'  => 'User/Index/setpassword',
 		'user/createpassword'  => 'User/Index/createpassword',
 		'user/setemail'  => 'User/Index/setemail',
 		'user/setqq'  => 'User/Index/setqq',
 		'user/setphone'  => 'User/Index/setphone',
 		'user/setavatar'  => 'User/Index/setavatar',
 		'user/:user_id\d'  => 'User/Main/index',
 		'user'  => 'User/Index/index',
 		//账号操作
 		'account/qqlogin'  => 'Account/Index/qqlogin',
 		'account/qqcallback'  => 'Account/Index/qqcallback',
 		'account/wblogin'  => 'Account/Index/wblogin',
 		'account/wbcallback'  => 'Account/Index/wbcallback',
 		'account/checkemail'  => 'Account/Index/checkemail',
 		'account/checkusername'  => 'Account/Index/checkusername',
 		'account/registersuccess'  => 'Account/Index/registersuccess',
 		'account/activate'  => 'Account/Index/activate',
 		'account/forgetpassword'  => 'Account/Index/forgetpassword',
 		'account/login'  => 'Account/Index/login',
 		'account/register'  => 'Account/Index/register',
 		'account/logout'  => 'Account/Index/logout',
 		'account'  => 'Account/Index/index',
 		//公共
 		'public/token' => 'Public/Index/token',
 		'public/uploadify'  => 'Public/Index/uploadify',
 		'public/uploadfile'  => 'Public/Index/uploadfile',
 		'public/verify' => 'Public/Index/verify',
 		'public'  => 'Public/Index/index',
	),
);