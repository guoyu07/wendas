<?php

class NavAction extends BaseAction{

	public function _initialize()
	{
		$this->nav_mod = M('nav');
		parent::_initialize();	
	}

	public function index()
	{
		$nav_list = $this->nav_mod->order('sort ASC')->select();
		
		$this->assign('nav_list',$nav_list);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=nav&a=add\', title:\'添加导航\', width:\'540\', height:\'330\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);','添加导航');
		$this->assign('big_menu',$big_menu);
		$this->display();
	}

	function add()
	{
		if(isset($_POST['dosubmit'])){
			if( false === $vo = $this->nav_mod->create() ){
				$this->error( $this->nav_mod->error() );
			}
			if($vo['name']==''){
				$this->error('导航名称不能为空');
			}
			$result = $this->nav_mod->where("name='".$vo['name']."'")->count();
			if($result != 0){
				$this->error('该导航已经存在');
			}
			//保存当前数据
			$this->nav_mod->add();
			$this->success('添加成功', '', '', 'add');
		}else{
			$this->display();
		}
	}

	function edit()
	{
		$id = $_REQUEST['id'];

		if(isset($_POST['dosubmit'])){
			if( false === $vo = $this->nav_mod->create() ){
				$this->error( $this->nav_mod->error() );
			}
			if($vo['name']==''){
				$this->error('导航名称不能为空');
			}
			$result = $this->nav_mod->where("name='".$vo['name']."' and id<>".$id)->count();
			if($result != 0){
				$this->error('该导航已经存在');
			}

			$this->nav_mod->save($vo);
			$this->success('修改成功', '', '', 'edit');
		}else{
			$nav = $this->nav_mod->where('id='.$id)->find();
			//print_r($nav);
			$this->assign('nav',$nav);
			$this->assign('show_header', false);
			$this->display();
		}
	}

}
?>