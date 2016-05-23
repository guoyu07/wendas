<?php
class ReviewAction extends BaseAction {

	//待审核
	public function unaudit() {

		//搜索
		//$where = 'status=1 and audit_num >= 10';
		$where = 'status=1';
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
		
		$review_mod = D('user_review');
		import("ORG.Util.Page");

		$count = $review_mod->where($where)->count();
		$p = new Page($count, 20);
		$review_list = $review_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
		foreach ($review_list as $key => $value) {
			$user = D('user')->where('id='.$value['user_id'])->find();
			$review_list[$key]['user_info'] = $user;
		}
	
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('review_list', $review_list);
		$this->display();
	}

	//已通过
	public function audit() {

		//搜索
		$where = 'status=2';
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
		
		$review_mod = D('user_review');
		import("ORG.Util.Page");

		$count = $review_mod->where($where)->count();
		$p = new Page($count, 20);
		$review_list = $review_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
		foreach ($review_list as $key => $value) {
			$user = D('user')->where('id='.$value['user_id'])->find();
			$review_list[$key]['user_info'] = $user;
		}
	
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('review_list', $review_list);
		$this->display();
	}


	//未通过
	public function failed() {

		//搜索
		$where = 'status=3';
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
		
		$review_mod = D('user_review');
		import("ORG.Util.Page");

		$count = $review_mod->where($where)->count();
		$p = new Page($count, 20);
		$review_list = $review_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
		foreach ($review_list as $key => $value) {
			$user = D('user')->where('id='.$value['user_id'])->find();
			$review_list[$key]['user_info'] = $user;
		}
	
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('review_list', $review_list);
		$this->display();
	}
	
	//审核评论
	public function audit_review() {
		$review_mod = D('user_review');
		if($this->isAjax()) {
			$id = (int)$_GET['id'];
			$type = (int)$_GET['type'];

			//设置状态
			D('user_review')->where('id='.$id)->save(array('status' => $type));
			
			if($type == 2) {
				$review = D('user_review')->where('id='.$id)->find();
				//给笑话加评论数
				D('user_joke')->where('id='.$review['joke_id'])->setField(array('review_num' => array('exp', "(review_num + 1)")));
			}
			$this->ajaxReturn(array('err' => 1,'msg' => '操作成功!'));
		}
	}

	public function delete() {
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的评论！');
		}

		$review_mod = D('user_review');

		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
			$review_mod->where("id in({$id})")->delete();
		} else {
			$id = intval($_GET['id']);
			$review_mod->where('id = ' . $id)->delete();
		}
		$this->success(L('operation_success'));
	}

	public function process() {
		if(!isset($_POST['id']) || empty($_POST['id'])) {
			$this->error('请选择要操作的评论！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$id = implode(',', $_POST['id']);
		} 

		$review_mod = D('user_review');
		//删除
		if(isset($_POST['delete'])) {
			$review_mod->where("id in({$id})")->delete();
		}
		//批量通过
		if(isset($_POST['all_audit'])) {
			//设置状态
			$review_mod->where("id in({$id})")->save(array('status' => 2));

			$review = $review_mod->where("id in({$id})")->select();
			foreach ($review as $key => $value) {
				//给笑话加评论数
				D('user_joke')->where('id='.$value['joke_id'])->setField(array('review_num' => array('exp', "(review_num + 1)")));	
			}
		}
		//批量不通过
		if(isset($_POST['all_failed'])) {
			//设置状态
			$review_mod->where("id in({$id})")->save(array('status' => 3));
		}
		$this->success(L('operation_success'));
	}

}

?>