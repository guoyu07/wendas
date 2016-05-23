<?php

class AdminAction extends BaseAction
{
	public function index()
	{
		$admin_mod = D('admin');
		import("ORG.Util.Page");
		$prex = C('DB_PREFIX');
		$count = $admin_mod->count();
		$p = new Page($count,30);

		$admin_list = $admin_mod->field($prex.'admin.*,'.$prex.'role.name as role_name')->join('LEFT JOIN '.$prex.'role ON '.$prex.'admin.role_id = '.$prex.'role.id ')->limit($p->firstRow.','.$p->listRows)->order($prex.'admin.add_time DESC')->select();

		$key = 1;
		foreach($admin_list as $k=>$val){
			$admin_list[$k]['key'] = ++$p->firstRow;
		}
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=admin&a=add\', title:\'添加管理员\', width:\'480\', height:\'250\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加管理员');
		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('big_menu',$big_menu);
		
		$this->assign('admin_list',$admin_list);
		$this->display();
	}

	public function add()
	{
	    if(isset($_POST['dosubmit'])){
	    	$admin_mod = D('admin');
			if(!isset($_POST['username'])||($_POST['username']=='')){
				$this->error('用户名不能为空');
			}
			if($_POST['password'] != $_POST['repassword']){
				$this->error('两次输入的密码不相同');
			}
			$result = $admin_mod->where("username='".$_POST['username']."'")->count();
			if($result){
			    $this->error('管理员'.$_POST['user_name'].'已经存在');
			}
			unset($_POST['repassword']);
			$_POST['password'] = md5($_POST['password']);
			$admin_mod->create();
			$admin_mod->add_time = time();
			$admin_mod->last_time = time();
			$result = $admin_mod->add();
			if($result){
				$this->success(L('operation_success'), '', '', 'add');
			}else{
				$this->error(L('operation_failure'));
			}

	    }else{
		    $role_mod = D('role');
		    $role_list = $role_mod->where('status=1')->select();
		    $this->assign('role_list',$role_list);

		    $this->assign('show_header', false);
			$this->display();
	    }
	}

	public function edit()
	{
		if(isset($_POST['dosubmit'])){
			$admin_mod = D('admin');
			$count=$admin_mod->where("id!=".$_POST['id']." and username='".$_POST['username']."'")->count();
			if($count>0){
				$this->error('用户名已经存在！');
			}
			//print_r($count);exit;
			if ($_POST['password']) {
			    if($_POST['password'] != $_POST['repassword']){
				    $this->error('两次输入的密码不相同');
			    }
			    $_POST['password'] = md5($_POST['password']);
			} else {
			    unset($_POST['password']);
			}
			unset($_POST['repassword']);
			if (false === $admin_mod->create()) {
				$this->error($admin_mod->getError());
			}

			$result = $admin_mod->save();
			if(false !== $result){
				$this->success(L('operation_success'), '', '', 'edit');
			}else{
				$this->error(L('operation_failure'));
			}
		}else{
			if( isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
			}
			$role_mod = D('role');
		    $role_list = $role_mod->where('status=1')->select();
		    $this->assign('role_list',$role_list);

		    $admin_mod = D('admin');
			$admin_info = $admin_mod->where('id='.$id)->find();
			$this->assign('admin_info', $admin_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function ajax_check_username()
	{
	    $user_name = $_GET['username'];
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';
        $admin_mod = D('admin');
        if($id) {
			$count =  $admin_mod->where("id!=".$id." and username='".$user_name."'")->count();
        }else {
        	$count =  $admin_mod->where("username='".$user_name."'")->count();	
        }
    	
        if ($count) {
        	//存在
            echo '0';
        } else {
        	//不存在
            echo '1';
        }
        exit;
	}
   
}
?>