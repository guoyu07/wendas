<?php

class UserAction extends BaseAction
{
	public function index()
	{
		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
		    $where .= " AND (username LIKE '%".$_GET['keyword']."%' or email LIKE '%".$_GET['keyword']."%' or phone LIKE '%".$_GET['keyword']."%')";
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
		if (isset($_GET['status']) && intval($_GET['status'])) {
		    $where .= " AND status=".$_GET['status'];
		    $this->assign('status', $_GET['status']);
		}

		$user_mod = D('user');
		import("ORG.Util.Page");
		$count = $user_mod->where($where)->count();
		$p = new Page($count,30);

		$user_list = $user_mod->where($where)->limit($p->firstRow.','.$p->listRows)->order('id desc')->select();

		$page = $p->show();
		$this->assign('page',$page);
		
		$this->assign('user_list',$user_list);
		$this->display();
	}

	public function show() {
		if( isset($_GET['id']) ){
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
		}

	    $user_mod = D('user');
		$user_info = $user_mod->where('id='.$id)->find();
		$this->assign('user_info', $user_info);
		$this->assign('show_header', false);
		$this->display();
	}

	public function change() {
		if (isset($_POST['dosubmit'])) {
			$id = (int)$_POST['id'];

			$user_mod = D('user');
			
			$level = (int)$_POST['level'];
			$ptype = $_POST['ptype'];
			$money = (int)$_POST['money'];
			$content = $_POST['content'];

			$user_info = $user_mod->where('id=' . $id)->find();
			$data = array();
			if($user_info['level'] != $level) {
				$data['level'] = $level;
			}

			if($ptype == 'add') {
				$data['money'] = $user_info['money'] + $money;

				//增加消费记录
				$params = array('user_id' => $id,
								'value' => $money,
								'type' => 1,
								'content' => $content,
								'created_time' => time());
				D('user_trace')->add($params);

			}else if($ptype == 'des') {
				
				if($user_info['money'] >= $money) {
					$data['money'] = $user_info['money'] - $money;

					//增加消费记录
					$params = array('user_id' => $id,
									'value' => $money,
									'type' => 2,
									'content' => $content,
									'created_time' => time());
					D('user_trace')->add($params);
				}

			}else {

			}
			$result = D('user')->where('id='.$id)->save($data);

			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'change');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$user_mod = D('user');
			if (isset($_GET['id'])) {
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的用户');
			}

			$user_info = $user_mod->where('id=' . $id)->find();
			$this->assign('user_info', $user_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function delete() {
		$user_mod = D('user');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的用户！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$user_ids = implode(',', $_POST['id']);
			$user_mod->delete($user_ids);
		} else {
			$user_id = intval($_GET['id']);
			$user_mod->where('id=' . $user_id)->delete();
		}
		$this->success(L('operation_success'));
	}
   
}
?>