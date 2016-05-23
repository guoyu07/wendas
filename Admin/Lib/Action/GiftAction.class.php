<?php
class GiftAction extends BaseAction {
	public function index() {
		$gift_mod = D('shop_gift');
		import("ORG.Util.Page");

		$count = $gift_mod->count();
		$p = new Page($count, 20);
		$gift_list = $gift_mod->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();

		$gift_cate_mod = D('shop_cate');
		foreach ($gift_list as $key => $value) {
			$gift_cate = $gift_cate_mod->where('id='.$value['cate_id'])->find();
			$gift_list[$key]['cate_name'] = $gift_cate['name'];
		}

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=gift&a=add\', title:\'' . '添加礼品' . '\', width:\'700\', height:\'700\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加礼品');
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('gift_list', $gift_list);
		$this->display();
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {

			$gift_mod = D('shop_gift');
			$data = array();
			$title = isset($_POST['title']) && trim($_POST['title']) ? trim($_POST['title']) : $this->error('名称不能为空');
			$description = isset($_POST['description']) && trim($_POST['description']) ? trim($_POST['description']) : $this->error('描述不能为空');
		
			$data = $gift_mod->create();
			$gift_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {

			$this->assign('cate',$this->get_cate());
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function edit() {
		if (isset($_POST['dosubmit'])) {
			$gift_mod = D('shop_gift');
			$data = $gift_mod->create();
			
			$result = $gift_mod->where("id=" . $data['id'])->save($data);
			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'edit');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$gift_mod = D('shop_gift');
			if (isset($_GET['id'])) {
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的礼品');
			}
			$this->assign('cate',$this->get_cate());
			$gift_info = $gift_mod->where('id=' . $id)->find();
			$this->assign('gift_info', $gift_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function delete() {
		$gift_mod = D('shop_gift');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的礼品！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
			$gift_mod->where("id in({$id})")->delete();
		} else {
			$id = intval($_GET['id']);
			$gift_mod->where('id = ' . $id)->delete();
		}
		$this->success(L('operation_success'));
	}


	public function order() {
		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
		    $where .= " AND (username LIKE '%".$_GET['keyword']."%' or order_id LIKE '%".$_GET['keyword']."%' or phone LIKE '%".$_GET['keyword']."%')";
		    $this->assign('keyword', $_GET['keyword']);
		}
		if (isset($_GET['time_start']) && trim($_GET['time_start'])) {
		    $time_start = strtotime($_GET['time_start']);
		    $where .= " AND created_time>='".$time_start."'";
		    $this->assign('time_start', $_GET['time_start']);
		}
		if (isset($_GET['time_end']) && trim($_GET['time_end'])) {
		    $time_end = strtotime($_GET['time_end']);
		    $where .= " AND created_time<='".$time_end."'";
		    $this->assign('time_end', $_GET['time_end']);
		}
		if (isset($_GET['status']) && $_GET['status'] != 'all') {
		    $where .= " AND status=".$_GET['status'];
		    $this->assign('status', $_GET['status']);
		}

		$user_gift_mod = D('user_gift');
		import("ORG.Util.Page");

		$count = $user_gift_mod->where($where)->count();
		$p = new Page($count, 20);
		$order_list = $user_gift_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
		foreach ($order_list as $key => $value) {
			$order_list[$key]['gift_info'] = json_decode($value['gift_info'],true);

			$user = D('user')->where('id='.$value['user_id'])->find();
			$order_list[$key]['user_info'] = $user;
		}

		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('order_list', $order_list);
		$this->display();
	}

	public function deleteorder() {
		$user_gift_mod = D('user_gift');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的订单！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
			$user_gift_mod->where("id in({$id})")->delete();
		} else {
			$id = intval($_GET['id']);
			$user_gift_mod->where('id = ' . $id)->delete();
		}
		$this->success(L('operation_success'));

	}

	public function processorder() {
		if(D('user_gift')->where('id='.$_GET['id'].' and status=0')->save(array('status' => 1))) {
			$this->ajaxReturn(array('err' => 1, 'msg' => '操作成功'));
		}
	}


	private function get_cate() {
		$gift_cate = D('shop_cate')->order('sort asc')->select();
		return $gift_cate;
	}
}

?>