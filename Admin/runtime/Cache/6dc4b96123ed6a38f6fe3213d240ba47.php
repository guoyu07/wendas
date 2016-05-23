<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=7" /><link href="__ROOT__/Assets/admin/css/style.css" rel="stylesheet" type="text/css"/><link href="__ROOT__/Assets/admin/css/dialog.css" rel="stylesheet" type="text/css" /><link href="__ROOT__/Assets/js/uploadify/uploadify.css" rel="stylesheet" type="text/css"><script language="javascript" type="text/javascript" src="__ROOT__/Assets/js/jquery.min.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/Assets/js/formvalidator.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/Assets/js/formvalidatorregex.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/Assets/js/jquery.uploadify.min.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/Assets/admin/js/admin_common.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/Assets/js/dialog.js"></script><!-- <script language="javascript" type="text/javascript" src="__ROOT__/Assets/js/iColorPicker.js"></script> --><!-- <script type="text/javascript" src="__ROOT__/Assets/js/WdatePicker.js"></script> --><script language="javascript">
/*var URL = '__URL__';
var ROOT_PATH = '__ROOT__';
var APP	 =	 '__APP__';
var lang_please_select = "<?php echo (L("please_select")); ?>";*/
//var def=<?php echo ($def); ?>;
$(function($){
	$("#ajax_loading").ajaxStart(function(){
		$(this).show();
	}).ajaxSuccess(function(){
		$(this).hide();
	});
});

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><link href="__ROOT__/Assets/js/jqueryui/development-bundle/themes/base/jquery.ui.all.css" type="text/css" rel="stylesheet"><link href="__ROOT__/Assets/js/jqueryui/development-bundle/themes/base/jquery.ui.datepicker.css" rel="stylesheet" type="text/css"/><script src="__ROOT__/Assets/js/jqueryui/development-bundle/ui/jquery.ui.core.js"></script><script src="__ROOT__/Assets/js/jqueryui/development-bundle/ui/jquery.ui.datepicker.js"></script><div class="pad-lr-10"><form name="searchform" action="" method="get" ><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col">
                关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" placeholder="昵称/邮箱/手机号"/>
                &nbsp;&nbsp;状态：
                <select name="status"><option value="">--请选择--</option><option value="1">未激活</option><option value="2">正常</option><option value="0">禁用</option></select>
                &nbsp;
                注册时间：         
                <!-- <wego:calendar name="time_start"><?php echo ($time_start); ?></wego:calendar> --><input type="text" size="12" name="time_start" id="time_start" value="<?php if($time_start != ""): echo ($time_start); endif; ?>"/>
                -      
                <!-- <wego:calendar name="time_end" more="true"><?php echo ($time_end); ?></wego:calendar> --><input type="text" size="12" name="time_end" id="time_end" value="<?php if($time_end != ""): echo ($time_end); endif; ?>"/><input type="hidden" name="m" value="user" /><input type="hidden" name="a" value="index" /><input type="submit" name="search" class="button" value="搜索" /></div></td></tr></tbody></table></form><form id="myform" name="myform" action="<?php echo u('user/delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th>ID</th><th><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th>昵称</th><th>等级 / 囧币</th><th>邮箱</th><th>在线时间</th><th>注册时间</th><th>注册IP</th><th>最后登陆时间</th><th>最后登陆IP</th><th>状态</th><th>操作</th></tr></thead><tbody><?php if(is_array($user_list)): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($val["id"]); ?></td><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td align="center"><img src="<?php echo ($val["avatar"]); ?>" width="80"/><br/><?php echo ($val["username"]); ?></td><td align="center"><?php echo ($val["level"]); ?> / <?php echo ($val["money"]); ?><br/><br/><a class="blue" href="javascript:change(<?php echo ($val["id"]); ?>,'<?php echo ($val["username"]); ?>')">调节</a></td><td align="center"><?php echo ($val["email"]); ?></td><td align="center"><?php echo ($val["online_time"]); ?> 分钟</td><td align="center"><?php echo (date('Y-m-d H:i:s',$val["created_time"])); ?></td><td align="center"><?php echo ($val["register_ip"]); ?></td><td align="center"><?php echo (date('Y-m-d H:i:s',$val["last_login_time"])); ?></td><td align="center"><?php echo ($val["last_login_ip"]); ?></td><td align="center"><?php if($val["status"] == 1): ?>未激活<?php endif; if($val["status"] == 2): ?>正常<?php endif; if($val["status"] == 0): ?>禁用<?php endif; ?></td><td align="center"><a href="javascript:show(<?php echo ($val["id"]); ?>,'<?php echo ($val["username"]); ?>')" class="blue">详情</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box" style="float:left;"><?php echo (L("select_all")); ?>/<?php echo (L("cancel")); ?></label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin-left:10px;"/><div id="pages"><?php echo ($page); ?></div></div></div></form></div><script language="javascript">
$(function(){
    $("#time_start").datepicker({
      changeMonth: true,
      changeYear: true
    });
    $("#time_end").datepicker({
      changeMonth: true,
      changeYear: true
    });
});

function show(id, name) {
	var lang_show = "详情";
	window.top.art.dialog({id:'show'}).close();
	window.top.art.dialog({title:lang_show+'--'+name,id:'show',iframe:'?m=user&a=show&id='+id,width:'500',height:'500'}, function(){var d = window.top.art.dialog({id:'show'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'show'}).close()});
}

function change(id, name) {
    var lang_show = "调节";
    window.top.art.dialog({id:'change'}).close();
    window.top.art.dialog({title:lang_show+'--'+name,id:'change',iframe:'?m=user&a=change&id='+id,width:'500',height:'400'}, function(){var d = window.top.art.dialog({id:'change'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'change'}).close()});
}
</script>