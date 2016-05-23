<?php
class GiftcateAction extends BaseAction {
	public function index() {
		$giftcate_mod = D('shop_cate');
		import("ORG.Util.Page");

		$count = $giftcate_mod->count();
		$p = new Page($count, 20);
		$giftcate_list = $giftcate_mod->limit($p->firstRow . ',' . $p->listRows)->order('sort asc')->select();

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=giftcate&a=add\', title:\'' . '添加分类' . '\', width:\'500\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加分类');
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('giftcate_list', $giftcate_list);
		$this->display();
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {

			$giftcate_mod = D('shop_cate');
			$data = array();
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('名称不能为空');
			//$description = isset($_POST['description']) && trim($_POST['description']) ? trim($_POST['description']) : $this->error('描述不能为空');
			//$active = isset($_POST['active']) && trim($_POST['active']) ? trim($_POST['active']) : $this->error( '投稿数不能为空');
			$sort = isset($_POST['sort']) && trim($_POST['sort']) ? trim($_POST['sort']) : $this->error('排序不能为空');
			
			$data = $giftcate_mod->create();
			$giftcate_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function edit() {
		if (isset($_POST['dosubmit'])) {
			$giftcate_mod = D('shop_cate');
			$data = $giftcate_mod->create();
			
			$result = $giftcate_mod->where("id=" . $data['id'])->save($data);
			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'edit');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$giftcate_mod = D('shop_cate');
			if (isset($_GET['id'])) {
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的分类');
			}

			$giftcate_info = $giftcate_mod->where('id=' . $id)->find();
			$this->assign('giftcate_info', $giftcate_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function delete() {
		$giftcate_mod = D('shop_cate');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的分类！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
			$giftcate_mod->where("id in({$id})")->delete();
		} else {
			$id = intval($_GET['id']);
			$giftcate_mod->where('id = ' . $id)->delete();
		}
		$this->success(L('operation_success'));
	}

}

?>