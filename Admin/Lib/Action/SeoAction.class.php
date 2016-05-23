<?php
class SeoAction extends BaseAction {
	public function index() {
		$seo_mod = D('seo');
		import("ORG.Util.Page");

		$count = $seo_mod->count();
		$p = new Page($count, 20);
		$seo_list = $seo_mod->limit($p->firstRow . ',' . $p->listRows)->order('id asc')->select();

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=seo&a=add\', title:\'' . '添加关键词' . '\', width:\'500\', height:\'400\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加关键词');
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('seo_list', $seo_list);
		$this->display();
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {

			$seo_mod = D('seo');
			$data = array();
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('名称不能为空');
			$alias = isset($_POST['alias']) && trim($_POST['alias']) ? trim($_POST['alias']) : $this->error('别名不能为空');
			$keywords = isset($_POST['keywords']) && trim($_POST['keywords']) ? trim($_POST['keywords']) : $this->error('关键词不能为空');
			$description = isset($_POST['description']) && trim($_POST['description']) ? trim($_POST['description']) : $this->error('描述不能为空');
			//$active = isset($_POST['active']) && trim($_POST['active']) ? trim($_POST['active']) : $this->error( '投稿数不能为空');
			//$sort = isset($_POST['sort']) && trim($_POST['sort']) ? trim($_POST['sort']) : $this->error('排序不能为空');
			
			$data = $seo_mod->create();
			$seo_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function edit() {
		if (isset($_POST['dosubmit'])) {
			$seo_mod = D('seo');
			$data = $seo_mod->create();
			
			$result = $seo_mod->where("id=" . $data['id'])->save($data);
			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'edit');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$seo_mod = D('seo');
			if (isset($_GET['id'])) {
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的关键词');
			}

			$seo_info = $seo_mod->where('id=' . $id)->find();
			$this->assign('seo_info', $seo_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function delete() {
		$seo_mod = D('seo');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的关键词！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
			$seo_mod->where("id in({$id})")->delete();
		} else {
			$id = intval($_GET['id']);
			$seo_mod->where('id = ' . $id)->delete();
		}
		$this->success(L('operation_success'));
	}

}

?>