<?php
class AdvAction extends BaseAction {
	public function index() {
		$adv_mod = D('adv');
		import("ORG.Util.Page");

		$count = $adv_mod->count();
		$p = new Page($count, 20);
		$adv_list = $adv_mod->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=adv&a=add\', title:\'' . '添加广告' . '\', width:\'500\', height:\'250\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加广告');
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('adv_list', $adv_list);
		$this->display();
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {

			$adv_mod = D('adv');
			$data = array();
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('标识不能为空');
			$code = isset($_POST['code']) && trim($_POST['code']) ? trim($_POST['code']) : $this->error('代码不能为空');
			
			$data = $adv_mod->create();
			$adv_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {
			$this->assign('show_header', false);
			$this->display();
		}
	}
	
	public function edit() {
		if (isset($_POST['dosubmit'])) {
			$adv_mod = D('adv');
			$data = array();
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('标识不能为空');
			$code = isset($_POST['code']) && trim($_POST['code']) ? trim($_POST['code']) : $this->error('代码不能为空');	
			$data = $adv_mod->create();
			$adv_mod->where('id='.I('post.id'))->save($data);
			$this->success(L('operation_success'),U('index'));
		}

	}

	public function delete() {
		$adv_mod = D('adv');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的广告！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
			$adv_mod->where("id in({$id})")->delete();
		} else {
			$id = intval($_GET['id']);
			$adv_mod->where('id = ' . $id)->delete();
		}
		$this->success(L('operation_success'));
	}

}

?>