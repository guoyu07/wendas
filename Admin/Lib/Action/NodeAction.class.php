<?php
class NodeAction extends BaseAction
{
	public function index()
	{	

		$group_id = isset($_GET['group_id']) && intval($_GET['group_id']) ? intval($_GET['group_id']) : '';
		$keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$where = '1=1';
		if($group_id!=''){
			$where.=" AND group_id=$group_id";			
		}
		if($keyword!=''){
			$where.=" AND module like '%$keyword%' or module_name like '%$keyword%' or action_name like '%$keyword%'";
		}		
		$node_mod = D('node');
		import("ORG.Util.Page");
		$count = $node_mod->where($where)->count();
		$p = new Page($count,15);
		$node_list = $node_mod->where($where)->limit($p->firstRow.','.$p->listRows)->order('module asc,sort ASC')->select();		
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=node&a=add\', title:\'添加菜单\', width:\'500\', height:\'490\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加菜单');
		$page = $p->show();		
		$group_mod = D('group');
		$group_list = $group_mod->select();
		$this->assign('group_list',$group_list);
		$this->assign('group_id',$group_id);
		$this->assign('keyword',$keyword);		
		$this->assign('page',$page);
		$this->assign('big_menu',$big_menu);
		$this->assign('node_list',$node_list);
		$this->display();
	}

	public function add()
	{
		//分组
		if(isset($_POST['dosubmit'])){
			if(!isset($_POST['module'])||($_POST['module']=='')){
				$this->error('请填写模型');
			}
			if(!isset($_POST['module_name'])||($_POST['module_name']=='')){
				$this->error('请填写模型名称');
			}

			$node_mod = D('node');
			$node_mod->create();
			$result = $node_mod->add();
			if($result){
				$this->success(L('operation_success'), '', '', 'add');
			}else{
				$this->error(L('operation_failure'));
			}
		}else{
			$group_mod = D('group');
			$group_list = $group_mod->select();
			$this->assign('group_list',$group_list);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function edit()
	{
		if(isset($_POST['dosubmit'])){
			if(!isset($_POST['module'])||($_POST['module']=='')){
				$this->error('请填写模型');
			}
			if(!isset($_POST['module_name'])||($_POST['module_name']=='')){
				$this->error('请填写模型名称');
			}
			$node_mod = D('node');
			if (false === $node_mod->create()) {
				$this->error($node_mod->getError());
			}
			$result = $node_mod->save();
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
			$group_list = $group_mod->select();
			$this->assign('group_list',$group_list);

			$node_mod = D('node');
			$node_info = $node_mod->where('id='.$id)->find();
			$this->assign('node_info', $node_info);
			$this->assign('show_header', false);
			$this->display();

		}
	}

}
?>