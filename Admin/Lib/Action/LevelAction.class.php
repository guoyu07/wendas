<?php
class LevelAction extends BaseAction {
	public function index() {
		$level_mod = D('level');
		import("ORG.Util.Page");

		$count = $level_mod->count();
		$p = new Page($count, 20);
		$level_list = $level_mod->limit($p->firstRow . ',' . $p->listRows)->order('level desc')->select();

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=level&a=add\', title:\'' . '添加等级'. '\', width:\'480\', height:\'250\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加等级');
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('level_list', $level_list);
		$this->display();
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {

			$level_mod = D('level');
			$data = array();
			$level = isset($_POST['level']) && trim($_POST['level']) ? trim($_POST['level']) : $this->error('等级不能为空');
			$minute = isset($_POST['minute']) && trim($_POST['minute']) ? trim($_POST['minute']) : $this->error('分钟不能为空');
			$send_num = isset($_POST['send_num']) && trim($_POST['send_num']) ? trim($_POST['send_num']) : $this->error('投稿数不能为空');
			$award = isset($_POST['award']) && trim($_POST['award']) ? trim($_POST['award']) : $this->error('奖励囧币不能为空');
			$exist = $level_mod->where("level='" . $level . "'")->count();
			if ($exist != 0) {
				$this->error('该等级已经存在');
			}
			$data = $level_mod->create();
			$level_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function edit() {
		if (isset($_POST['dosubmit'])) {
			$level_mod = D('level');
			$data = $level_mod->create();
			
			$result = $level_mod->where("level=" . $data['level'])->save($data);
			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'edit');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$level_mod = D('level');
			if (isset($_GET['level'])) {
				$level = isset($_GET['level']) && intval($_GET['level']) ? intval($_GET['level']) : $this->error('请选择要编辑的链接');
			}

			$level_info = $level_mod->where('level=' . $level)->find();
			$this->assign('level_info', $level_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function delete() {
		$level_mod = D('level');
		if ((!isset($_GET['level']) || empty($_GET['level'])) && (!isset($_POST['level']) || empty($_POST['level']))) {
			$this->error('请选择要删除的等级！');
		}
		if (isset($_POST['level']) && is_array($_POST['level'])) {
			$level = implode(',', $_POST['level']);
			$level_mod->where("level in({$level})")->delete();
		} else {
			$level = intval($_GET['level']);
			$level_mod->where('level = ' . $level)->delete();
		}
		$this->success(L('operation_success'));
	}

}

?>