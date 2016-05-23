<?php
class GroupAction extends BaseAction
{
	public function index()
	{
		$group_mod = D('group');
		$group_list = $group_mod->order('sort ASC')->select();
		$this->assign('group_list',$group_list);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=group&a=add\', title:\'添加菜单分类\', width:\'500\', height:\'170\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);','添加菜单分类');
		$this->assign('big_menu',$big_menu);		
		$this->display();
	}
 	//增加
	public function add()
	{
	    if(isset($_POST['dosubmit'])){		    	
 			$group_mod = D('group');
 			if(!isset($_POST['title'])||($_POST['title']=='')){
				$this->error(L('group_title_require'));
			}		
			$result = $group_mod->where("title='".$_POST['title']."'")->count();
			if($result){
			    $this->error(L('group_title_exist'));
			}
 			if($group_mod->create()){
 				$rel = $group_mod->add();
 				if(false !== $rel){ 					
 					$this->success(L('operation_success'), '', '', 'add');
 				} 
 				else{
 				  $this->error(L('operation_failure'));	
 				}
 			}
 			else{
 				$this->error($group_mod->getError());
 			}
	    	

	    }else{		   
			$this->display();
	    }
	}
	//修改
	public function edit()
	{
		if(isset($_POST['dosubmit'])){
			$group_mod = D('group');
			$count=$group_mod->where("id!=".$_POST['id']." and title='".$_POST['title']."'")->count();
			if($count>0){
				$this->error(L('group_title_exist'));
			}		
			if (false === $group_mod->create()) {
				$this->error($group_mod->getError());
			}
			$result = $group_mod->save();
			if(false !== $result){
				$this->success(L('operation_success'), '', '', 'edit');
			}else{
				$this->error(L('operation_failure'));
			}
		}else{
			if( isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
			}
		    $group_mod = D('group');
			$group_info = $group_mod->where('id='.$id)->find();			
			$this->assign('group_info', $group_info);			
			$this->display();
		}
	}

	public function ajax_check_title()
	{
		$group_mod = D('group');
	    $title = $_GET['title']; 
	    $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';
	    if($id) {
			$count =  $group_mod->where("id!=".$id." and title='".$title."'")->count();
	    }else {
	    	$count =  $group_mod->where("title='".$title."'")->count();	
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