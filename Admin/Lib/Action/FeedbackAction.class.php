<?php
class FeedbackAction extends BaseAction
{
	public function index()
	{
		$feedback_mod = D('feedback');

		//处理查询条件
		if(IS_POST){
			$key = $_POST['keyword'];

			//判断是否有关键字查询
			if(trim($key) != '') {
				$where = array('contact' => $key);
				$this->assign('keyword',$key);
			} 

			//判断是否有时间查询
			if($_POST['time_start'] != '' && $_POST['time_end'] != '') {
				$where['created_time'] = array('between',array(strtotime($_POST['time_start']),strtotime($_POST['time_end'])));
				$this->assign('time_start', $_POST['time_start']);
				$this->assign('time_end', $_POST['time_end']);
			}

			//确定逻辑关系
			$where['_logic'] = 'and';

		}else{
			$where = array();
		}

		import("ORG.Util.Page");
		$count = $feedback_mod->where($where)->count();
		$p = new Page($count,20);

		$feedback_list = $feedback_mod->where($where)->limit($p->firstRow.','.$p->listRows)->order('created_time desc')->select();
		
		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('feedback_list',$feedback_list);
		$this->display();
	}

	
}
?>