<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="applicable-device" content="pc"/>
<meta name="renderer" content="webkit" />
<meta property="qc:admins" content="2453043260647677541556375" />
<!--meta property="qc:admins" content="54440037776011217676375" /><!--QQ第三方登录-->
<meta property="wb:webmaster" content="4fabd59cb09eec0a" />
<!--meta property="wb:webmaster" content="2f873b1150e120cd" /><!--微博第三方登录-->
<!--title><?php echo ($title); ?> - <?php echo ($setting["site_name"]); ?></title-->
<title><?php echo ($setting["site_name"]); ?></title>
<script type="text/javascript">
//if((navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i))){
//	location.replace("http://m.dongwutime.com/");
//};
</script>
<link rel="icon" href="__PUBLIC__/Assets/index/images/favicon.ico" type="image/x-icon" >
<meta name="keywords" content="<?php if($keywords != NULL): echo ($keywords); else: echo ($title); endif; ?>" />
<meta name="description" content="<?php if($description != NULL): echo ($description); else: echo ($title); endif; ?>" />
<link rel="alternate" type="application/rss+xml" title="东吴在线网" href="http://www.dongwutime.com/rss"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Assets/index/css/main.css">
<script type="text/javascript" src="__PUBLIC__/Assets/js/sea.js"></script>
<script type="text/javascript" src="__PUBLIC__/Assets/js/sea_config.js"></script>
<script type="text/javascript" src="__PUBLIC__/Assets/js/jquery-1.11.1.min.js"></script>
<!--<script type="text/javascript" src="__PUBLIC__/Assets/js/jquery.lazyload.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("img[original]").lazyload({
            placeholder: "/Assets/index/images/loading.gif",
            effect: "show"
        });
	});

</script>-->
</head>
<body>


<!-- 顶部-开始 -->
<div class="header">
	<div class="header-nav clearfix">
		<div class="logo"><a href="/"><img src="__PUBLIC__/Assets/index/images/logo.jpg" width="60"></a></div><!--改了图片属性，作者常委-->
		<ul class="clearfix menu">
			<li><a href="/">最新提问</a></li>
			<li><a href="/hotjoke">热门提问</a></li>
			<li><a href="/tags">话题广场</a></li>
			<li><a href="/godreply">神回复</a></li>
			<li><a href="/shop">积分商城</a></li>
		</ul>
		<div class="header-user clearfix">
			<?php if($user == NULL): ?><a href="/account/login">登录</a>　
			<a href="/account/register">注册</a>
			<script type="text/javascript">var test = 0;</script>
			<?php else: ?>
			<!--登录状态-->
			<div class="username clearfix">
				<span><a href="/user"><?php echo ($user["username"]); ?></a>，欢迎回来！</span>
				<ul class="clearfix username-nav">
					<li><a href="/user/joke">我的投稿</a></li>
					<li><a href="/user/review">@评论</a></li>
					<li><a href="/user/gift">我的礼品</a></li>
					<li><a href="/user/info">个人资料</a></li>
					<li><a href="/account/logout">退出</a></li>
				</ul>
				<script type="text/javascript">var test = 1;</script>
			</div><?php endif; ?>
		</div>
	</div>
</div>
<!-- 顶部-结束 -->


<!-- 主体内容-开始 -->
<div class="main clearfix">
	<div class="main-left fl" id="j-main-list">

		<div class="main-nav clearfix" id="j-main-nav">
			<ul class="clearfix fl">
				<li>全 部</li>
				<!-- <li><a class="action" href="">最新</a></li>
				<li><a href="">8小时热门</a></li>
				<li><a href="">7天热门</a></li>
				<li><a href="">30天热门</a></li> -->
			</ul>
			<p class="notice"><a href="/about/jiongbi.html"><b>公告：</b>囧币规则</a></p>
		</div>

		<div class="side-nav" id="j-side-nav">
			<ul class="clearfix">
				<li><a href="/">首页</a></li>
				<li><a href="/text">段子</a></li>
				<li><a href="/pic">趣图</a></li>
				<li><a href="/gif">动图</a></li>
				<li class="cur"><a href="/video">视频</a></li>
				<!--<li><a href="/duanzi">段子</a></li>
				 <li><a href="">动画</a></li>
				<li data-left="-30" data-string="搞笑漫画"><a href="">漫画</a></li>
				<li data-left="-30" data-string="毛线话题"><a href="">毛线</a></li> -->
			</ul>
		</div>

		<!-- 笑话列表-开始 -->
		
		<?php if($user_joke == NULL): ?><dl class="main-list" style="height:100%;text-align:center;">
				<p style="font-size:20px;text-align:center;margin-top:16px;padding-bottom:20px;">小伙伴新来的，啥也木有。</p>
			</dl><?php endif; ?>

		<?php if(is_array($user_joke)): $i = 0; $__LIST__ = $user_joke;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><dl class="main-list">
			<dt>
				<a href="/user/<?php echo ($val["user_info"]["id"]); ?>" target="_blank">
					<img src="<?php echo ($val["user_info"]["avatar"]); ?>">
				</a>
				<!--p class="user">
					<a href="/user/<?php echo ($val["user_info"]["id"]); ?>"><?php echo ($val["user_info"]["username"]); ?></a>
				</p--><!--名字省略了一下，作者常委-->
				<span class="title"><a target="_blank" href="/xiaohua/<?php echo ($val["id"]); ?>.html"><?php echo ($val["title"]); ?></a></span>
			</dt>

			<dd class="content <?php if($val['type'] == 2){echo 'j-funny'; } ?>">
				<?php if($val['type'] == 3) { $content = $val['content']; $image = str_replace('m_','',$val['image']); $content = str_replace('src="'.$image.'"','class="gifimg" gifimg="'.$image.'" src="'.$val['image'].'"',$content); echo $content; echo '<span class="gif-play-btn" style="display:none">播放GIF</span>'; }else if($val['type'] == 2){ $image = $val['image']; $content = $val['content']; $content = str_replace('src="'.$image.'"','alt="'.$val['title'].'" src="'.$image.'"',$content); echo $content; }else if($val['type'] == 4){ echo '<embed src="'.$val['content'].'" quality="high" width="480" height="400" align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed>'; }else{ if(strlen($val['content']) > 400) { $content = str_replace('<p>','',$val['content']); $content = str_replace('</p>','',$content); $content = str_replace('<br style="color: #444444;" />','',$content); echo mb_substr($content,0,400,'utf-8').'……'; echo '<br/>'; echo '<div><a href="/xiaohua/'.$val['id'].'.html" style="text-decoration:underline;"> >>查看更多</a></div>'; }else { echo $val['content']; } } ?>
			</dd>

			<dd class="operation clearfix">
				<div class="operation-btn">
					<a href="javascript:void(0);" data-id="<?php echo ($val["id"]); ?>" class="ding <?php if(($val["record"] != NULL) AND ($val['record']['type'] == 'good')): ?>ding-hover<?php endif; ?>" title="顶">
						<div class="dingcai">
							<span></span>
							<i><?php echo ($val["good_num"]); ?></i>
						</div>
					</a>
					<div class="operation-line"></div>
				</div>
				<div class="operation-btn">
					<a href="javascript:void(0);" data-id="<?php echo ($val["id"]); ?>" class="cai <?php if(($val["record"] != NULL) AND ($val['record']['type'] == 'bad')): ?>cai-hover<?php endif; ?>" title="踩">
						<div class="dingcai">
							<span></span>
							<i><?php echo ($val["bad_num"]); ?></i>
						</div>
					</a>
					<div class="operation-line"></div>
				</div>
				<div class="operation-btn">
					<a href="javascript:void(0);" data-id="<?php echo ($val["id"]); ?>" class="index-comment comment" title="评论">
						<div class="dingcai"><span></span><i><?php echo ($val["review_num"]); ?></i></div>
					</a>
					<div class="operation-line"></div>
				</div>
				<div class="share-box clearfix">
					<a href="javascript:void(0);" class="share" title="分享"></a>
					<ul class="share-menu clearfix">
						<li><a href="javascript:;" class="tr share-qzone"  title="分享到QQ空间" data-title="<?php echo ($val["title"]); ?>" data-pic="<?php if($val['image'] != ''): if($val['id'] < 1822): echo ($setting["site_domain"]); endif; echo ($val["image"]); endif; ?>" data-url="<?php echo ($setting["site_domain"]); ?>/xiaohua/<?php echo ($val["id"]); ?>.html"></a></li>
						<li><a href="javascript:;" class="tr share-tqq" title="分享到腾讯微博" data-title="<?php echo ($val["title"]); ?>" data-pic="<?php if($val['image'] != ''): if($val['id'] < 1822): echo ($setting["site_domain"]); endif; echo ($val["image"]); endif; ?>" data-url="<?php echo ($setting["site_domain"]); ?>/xiaohua/<?php echo ($val["id"]); ?>.html"></a></li>
						<li><a href="javascript:;" class="tr share-tsina" title="分享到新浪微博" data-title="<?php echo ($val["title"]); ?>" data-pic="<?php if($val['image'] != ''): if($val['id'] < 1822): echo ($setting["site_domain"]); endif; echo ($val["image"]); endif; ?>" data-url="<?php echo ($setting["site_domain"]); ?>/xiaohua/<?php echo ($val["id"]); ?>.html"></a></li>
						<li><a href="javascript:;" class="tr share-sqq" title="分享到QQ好友" data-title="<?php echo ($val["title"]); ?>" data-pic="<?php if($val['image'] != ''): if($val['id'] < 1822): echo ($setting["site_domain"]); endif; echo ($val["image"]); endif; ?>" data-url="<?php echo ($setting["site_domain"]); ?>/xiaohua/<?php echo ($val["id"]); ?>.html"></a></li>
						<li><a href="javascript:;" class="tr share-copy" title="复制网址" data-url="<?php echo ($setting["site_domain"]); ?>/xiaohua/<?php echo ($val["id"]); ?>.html"></a></li>
					</ul>
				</div>
				
				<?php if(($val["record"] == NULL) OR ($val['record']['award'] == 0)): ?><a class="reward" href="javascript:void(0)" data-award="<?php echo ($val["award_num"]); ?>" data-id="<?php echo ($val["id"]); ?>">打赏</a><?php endif; ?>
				<?php if(($val["record"] != NULL) AND ($val['record']['award'] > 0)): ?><a class="rewarded" href="javascript:void(0)">已打赏</a><?php endif; ?>

				<?php if(($val["is_package"] == 1) AND ($val["package_user_id"] == 0)): ?><a class="buy" href="javascript:void(0)" data-id="<?php echo ($val["id"]); ?>" data-fee="<?php echo ($val["package_fee"]); ?>">包养</a><?php endif; ?>
				<?php if(($val["is_package"] == 1) AND ($val["package_user_id"] > 0)): ?><div class="kepted">
						<a href="/user/<?php echo ($val['record']['package_info']['id']); ?>"><?php echo ($val['record']['package_info']['username']); ?></a>
						<span>包养了Ta</span>
					</div><?php endif; ?>

			</dd>
			</dd>
		</dl><?php endforeach; endif; else: echo "" ;endif; ?>

		<div id="reward" class="joke-buy-box" style="display: none; top: 0; left: 0;">

		</div>

		<img src="__PUBLIC__/Assets/index/images/loading.gif" style="width:0;height:0;display:none;">

		<!-- 笑话列表-结束 -->
		<script type="text/javascript">
			function copy(c){
				var clipBoardContent = c;
				if(copy2Clipboard(clipBoardContent)!=false){
					alert("复制成功，请粘贴到你的QQ空间、新浪微博、腾讯微博，分享给你的好友!");
				}
			}

			function copy2Clipboard(txt){
				if(window.clipboardData){
					window.clipboardData.clearData();
					window.clipboardData.setData("Text",txt);
				}else{
					prompt("您用的浏览器非IE核心请用Ctrl+C复制内容",txt);
					return false;
				}
			}
			
			seajs.use(['user','share'], function(user,share) {
				$(window).load(function(){
					user.jokeList(test);
				});

				$('.share-qzone').click(function() {
					var data = $(this).data();
					share.shareToQzone(data.title,data.url,data.pic);
				});
				$('.share-tqq').click(function() {
					var data = $(this).data();
					share.shareToTencent(data.title,data.url,data.pic);
				});
				$('.share-tsina').click(function() {
					var data = $(this).data();
					share.shareToSina(data.title,data.url,data.pic);
				});
				$('.share-sqq').click(function() {
					var data = $(this).data();
					share.shareToFriend(data.title,data.url,data.pic);
				});

				//复制笑话网址
				$('.share-copy').click(function(){
					copy($(this).attr('data-url'));
					return false;
				});
			
			});
		</script>

		<div class="comment">
			<div class="page"> 
				<?php echo ($page); ?>
			</div>
		</div>

	</div>

	<div class="main-right fr">

		<ul class="user-operation">
	<li class="user-show">
		<?php if($user == NULL): ?><!--未登录-->
		<a href="javascript:void(0)" class="right-login-web">点击登录</a>
		<span class="user-link"><em>或</em></span>
		<a href="/account/qqlogin" target="_blank" class="right-login-qq"><i class="tr"></i><span>QQ登录</span></a>
		<a href="/account/wblogin" target="_blank" class="right-login-weibo"><i class="tr"></i><span>微博登录</span></a>
		<?php else: ?>
		 <!--已登录-->
		<div class="right-top-avatar-a">
			<a href="/user/<?php echo ($user["id"]); ?>" target="_blank">
				<img alt="<?php echo ($user["username"]); ?>" src="<?php echo ($user["avatar"]); ?>">
			</a>
		</div>
		<dl class="right-top-avatar">
			<dt><a href="/user" target="_blank"><?php echo ($user["username"]); ?></a><p></p>
			</dt><dd class="noborder">囧币：<em><?php echo ($user["money"]); ?></em></dd>
			<dd>等级：<?php echo ($user["level"]); ?>级</dd>
			<dd class="noborder">投稿数：<em><?php echo ($user["send_num"]); ?></em></dd>
			<dd>审稿数：<?php echo ($user["audit_num"]); ?></dd>
		</dl><?php endif; ?>
	</li>

	<li class="user-btn">
		<a href="/joke/publish" class="publish" target="_blank"><i class="tr"></i><span>投稿</span></a>
		<a href="/joke/audit" class="audit" target="_blank"><i class="tr"></i><span>审稿</span></a>
	</li>
</ul>
<script type="text/javascript">
	seajs.use(['user'], function(user){
		var login = $('.right-login-web');
		login.click(function(){
			user.loginDialog();
		});
	});
</script>



		<!--已签到-->

		<!-- <div class="calendar calendar-out clearfix">

			<a href="javascript:void(0);" class="calendar-left">

				<i class="icon icon24 icon-diary"></i>签到

			</a>

			<div class="calendar-info">

				<p class="calendar-info-itme flca5">已连续签到2天，本次获得25分</p>

				<p class="flca5 weather">合肥 多云 19℃ 东风微风</p>

			</div>

		</div> -->



		<!--未签到-->

		<!-- <div class="calendar calendar-in">

			<a href="javascript:void(0);" class="calendar-left">

				<i class="icon icon24 icon-diary-s"></i>签到

			</a>

			<div class="calendar-info">

				<p class="calendar-info-itme">

					<span class="flcf66"><i class="icon icon10 icon-arr"></i>点击签到赚积分</span>

				</p>

				<p class="flca5 weather">合肥 多云 19℃ 东风微风</p>

			</div>

		</div> -->





		<div class="trial" id="j-trial">

			<dl class="trial-head">

				<dt>最佳评审官</dt>

				<dd>

					<a href="javascript:void(0)" class="date-type weeks hover" data-body="weeks">周</a>

					<!-- <a href="javascript:void(0)" class="date-type day hover" data-body="day">日</a> -->

				</dd>

			</dl>

			<!-- <ul class="trial-body day-body clearfix" style="display:block;">
				<?php if($audit_day_user == NULL): ?><li style="text-align:center;">今天还没有人审稿。</li><?php endif; ?>

				<?php if(is_array($audit_day_user)): $k = 0; $__LIST__ = array_slice($audit_day_user,0,5,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><li>

					<span class="num top<?php echo ($k); ?>"><?php echo ($k); ?></span>

					<a href="/user/<?php echo ($val["id"]); ?>" class="user" target="_blank">

						<img src="<?php echo ($val["avatar"]); ?>" alt="<?php echo ($val["username"]); ?>">

					</a>

					<div class="user-title">

						<a href="/user/<?php echo ($val["id"]); ?>" target="_blank"><?php echo ($val["username"]); ?></a>

						<p>审准了<?php echo ($val["day_audit_num"]); ?>条</p>

					</div>

				</li><?php endforeach; endif; else: echo "" ;endif; ?>

			</ul> -->

			<ul class="trial-body weeks-body clearfix" style="display:block;">
				<?php if($audit_week_user == NULL): ?><li style="text-align:center;">该周还没有人审稿。</li><?php endif; ?>
				<?php if(is_array($audit_week_user)): $k = 0; $__LIST__ = array_slice($audit_week_user,0,5,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><li>

					<span class="num top<?php echo ($k); ?>"><?php echo ($k); ?></span>

					<a href="/user/<?php echo ($val["id"]); ?>" class="user" target="_blank">

						<img src="<?php echo ($val["avatar"]); ?>" alt="<?php echo ($val["username"]); ?>">

					</a>

					<div class="user-title">

						<a href="/user/<?php echo ($val["id"]); ?>" target="_blank"><?php echo ($val["username"]); ?></a>

						<p>审准了<?php echo ($val["day_audit_num"]); ?>条</p>

					</div>

				</li><?php endforeach; endif; else: echo "" ;endif; ?>

			</ul>

		</div>

		<script type="text/javascript">
	function checkSearch() {
		if($('#key').val() == '') {
			return false;
		}
		return true;
	}
</script>
<dl class="tags-list">
	<dt>
		<a href="/tags" target="_blank"><h2>话题</h2></a>
		<a href="/tags" class="more" target="_blank">更多</a>
	</dt>
	<dd>
		<?php if(is_array($tags)): $i = 0; $__LIST__ = array_slice($tags,0,20,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><a href="/tags/<?php echo ($val["id"]); ?>_1.html" target="_blank"><?php echo ($val["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
	</dd>
	<dd>
		<div class="search">
			<form action="/joke/search" method="GET" onsubmit="return checkSearch();">
				<input type="text" value="" id="key" class="search-text" placeholder="你想找点啥 ?" title="你想找点啥 ?" name="key" style="color: rgb(204, 204, 204);">
				<input type="hidden" name="p" value="1" />
				<input type="submit" value="" class="search-submit">
			</form>
		</div>
	</dd>
</dl>
        <dl class="img-list">
	<dt>
		<a href="javascript:;" target="_blank"><h2>精彩图片</h2></a>
		<!-- <a href="" class="more" target="_blank">更多</a> -->
	</dt>
	<dd class="clearfix">
		<?php if(is_array($pic)): $i = 0; $__LIST__ = $pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><a href="/xiaohua/<?php echo ($val["id"]); ?>.html" target="_blank">
			<div><img alt="" src="<?php echo ($val["image"]); ?>"></div>
			<span><?php echo ($val["title"]); ?></span>
		</a><?php endforeach; endif; else: echo "" ;endif; ?>
	</dd>
</dl>
		<dl class="text-list">
	<dt>
		<a href="javascript:;" target="_blank"><h2>编辑推荐</h2></a>
		<!-- <a href="" class="more" target="_blank">更多</a> -->
	</dt>
	<?php if(is_array($text)): $i = 0; $__LIST__ = $text;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><dd><a target="_blank" class="title" href="/xiaohua/<?php echo ($val["id"]); ?>.html"><?php echo ($val["title"]); ?></a></dd><?php endforeach; endif; else: echo "" ;endif; ?>
</dl>
		<dl class="img-list">
	<dt>
		<a href="javascript:;" target="_blank"><h2>广告位招租</h2></a>
	</dt>
	<dd class="clearfix">
	
    </dd>
	
</dl>

<a href="#" target="_blank"><img width="300" border="0" src="http://ubmcmm.baidustatic.com/media/v1/0f00026tg5KO1A3ocVbdY0.jpg"></a><!--这是自己加的，作者常委-->
		<!-- <div class="advertising" id="j-advertising">
			<div class="advertising-box">
				<a href="" target="_blank"><img src="/Assets/index/images/ab_right_4.jpg?v=20150602" width="300" height="250"></a>
			</div>

		</div> -->

	</div>
	<script type="text/javascript">
		//tab
		var trial = $('#j-trial');
			type = trial.find('.date-type');
		type.mouseover(function(){
			var self = $(this),
				type = self.data('body');
			self.addClass('hover').siblings('a').removeClass('hover');
			trial.find('.' + type + '-body').show().siblings('ul').hide();
		});
	</script>

</div>
<!-- 主体内容-结束 -->


<!-- 底部-开始 -->
<div class="footer">

	<div class="footer-content">
		<div class="about">
			<dl>
				<dt>关于东吴</dt>
				<dd><a href="/about/jianjie.html" target="_blank">东吴简介</a><a href="/about/gonggao.html" target="_blank">东吴公告</a></dd>
				<dd><a href="/about/shengming.html" target="_blank">免责声明</a><a href="/about/feedback.html" target="_blank" class="a-color-red">反馈意见</a></dd>
			</dl>
			<dl>
				<dt>互动规则</dt>
				<dd><a href="/about/tougao.html" target="_blank">投稿规则</a><a href="/about/shengao.html" target="_blank" class="a-color-red">审稿规则</a></dd>
				<dd><a href="/about/shengji.html" target="_blank">升级规则</a><a href="/about/jiongbi.html" target="_blank">囧币规则</a></dd>
			</dl>
			<dl>
				<dt>关注东吴</dt>
				<dd><a href="#" target="_blank" class="footer-weibo" rel="nofollow">新浪微博</a><a href="#" target="_blank" class="footer-qzone">QQ空间</a></dd>
				<dd><a href="#" target="_blank" class="footer-QQweibo" rel="nofollow">腾讯微博</a><a href="#" target="_blank" class="footer-weixin">微信订阅</a></dd>
			</dl>
			<dl>
				<dt>东吴合作</dt>
				<dd>东吴粉丝QQ群:156322789</dd>
				<dd>合作：2893234597@qq.com</dd>
			</dl>
			<dl>
				<dt>东吴在线微信公众平台</dt>
				<dd><img src="/Assets/index/images/qrcode.jpg" width="110" height="110"></dd><!--图片属性改了一下，作者常委-->
			</dl>
		</div>
		<div class="coypright">
		    <p>东吴在线所有的文字，图片均以知识共享 署名-相同方式共享 3.0协议（CC-by-sa-3.0）方式授权。</p>
			<p>Copyright 2015-2016 dongwutime.com 版权所有 <script src="http://s4.cnzz.com/z_stat.php?id=1259203939&web_id=1259203939" language="JavaScript"></script></p>
					
			
		</div>
	</div>
</div>
<!-- 底部-结束 -->

<!--回到顶部-开始-->
<div class="return-top" id="j-return-top">
	<div class="return-code">
		<img class="qrcode" src="/Assets/index/images/qrcode.jpg"><!--图片属性改了一下，作者常委-->
		<img class="arrow" src="/Assets/index/images/reutrn-code-bg.png">
	</div>
	<div class="return-btn"></div>
</div>
<!--回到顶部-结束-->

</body>
</html>