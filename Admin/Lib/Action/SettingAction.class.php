<?php

class SettingAction extends BaseAction
{
	public function index()
	{   
        $images = '';
        if(!empty($this->setting['index_banner']))
            $images= explode(';',$this->setting['index_banner']);

		$this->assign('set',$this->setting);
        $this->assign('images', $images);
		$this->display($_REQUEST['data']);
	}
	public function edit()
	{
		$setting_mod = M('setting');
		foreach($this->_stripcslashes($_POST['site']) as $key=>$val ){
			$setting_mod->where("name='".$key."'")->save(array('data'=>$val));
		}

		$this->success('修改成功',U('setting/index',array('data' => $_POST['data'])));
	}

	public function banner() {

		$banner_mod = M('banner');
		import("ORG.Util.Page");
		$prex = C('DB_PREFIX');

		//搜索
		$where = '1=1';

		$count = $banner_mod->where($where)->count();
		$p = new Page($count, 20);
		$list = $banner_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('ordid asc')->select();

		$key = 1;
		foreach ($list as $k => $val) {
			$list[$k]['key'] = ++$p->firstRow;
		}

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=setting&a=add_banner\', title:\'' . '添加Banner' . '\', width:\'450\', height:\'300\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);','添加Banner');
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('list', $list);
		$this->display();

	}

	public function add_banner() {
		if (isset($_POST['dosubmit'])) {

			$banner_mod = M('banner');
			$data = array();
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('名称不能为空!');
			$url = isset($_POST['url']) && trim($_POST['url']) ? trim($_POST['url']) : $this->error('url不能为空!');
			
			$data = $banner_mod->create();
			
			/*if ($_FILES['img']['name'] != '') {
				$upload_list=$this->_upload($_FILES['img']);
				$data['img'] = '/data/banner/'.$upload_list['0']['savename'];
			}*/
			
			$banner_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {
		
			$this->assign('show_header', false);
			$this->display();
		}

	}

	public function edit_banner() {
		if (isset($_POST['dosubmit'])) {
			$banner_mod = M('banner');
			$data = $banner_mod->create();
			
			/*if ($_FILES['img']['name'] != '') {
				$upload_list=$this->_upload($_FILES['img']);
				$data['img'] = '/data/banner/'.$upload_list['0']['savename'];
			}*/
			
			$result = $banner_mod->where("id=" . $data['id'])->save($data);
			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'edit');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$banner_mod = M('banner');
			if (isset($_GET['id'])) {
				$banner_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
			}

			$banner_info = $banner_mod->where('id=' . $banner_id)->find();
			$this->assign('banner_info', $banner_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	public function del_banner() {
		$banner_mod = M('banner');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的链接！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$banner_ids = implode(',', $_POST['id']);
			$banner_mod->delete($banner_ids);
		} else {
			$banner_id = intval($_GET['id']);
			$banner_mod->where('id=' . $banner_id)->delete();
		}
		$this->success(L('operation_success'));
	}

	/*public function ordid_banner() {
		$banner_mod = D('banner');
		if (isset($_POST['listorders'])) {
			foreach ($_POST['listorders'] as $id => $sort_order) {
				$data['ordid'] = $sort_order;
				$banner_mod->where('id=' . $id)->save($data);
			}
			$this->success(L('operation_success'));
		}
		$this->error(L('operation_failure'));
	}*/
	public function sort_banner() {
		$mod = D('banner');
		$id     = intval($_REQUEST['id']);
		$type   = trim($_REQUEST['type']);
		$num=trim($_REQUEST['num']);
		if(!is_numeric($num)){
			$values = $mod->where('id='.$id)->find();			
			$this->ajaxReturn($values[$type]);
			exit;
		}
		$sql    = "update ".C('DB_PREFIX').'banner'." set $type=$num where id='$id'";
        
		$res    = $mod->execute($sql);
		$values = $mod->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}

	//修改状态
	public function status_banner() {
		$banner_mod = D('banner');
		$id = intval($_REQUEST['id']);
		$type = trim($_REQUEST['type']);
		$sql = "update " . C('DB_PREFIX') . "banner set $type=($type+1)%2 where id='$id'";
		$res = $banner_mod->execute($sql);
		$values = $banner_mod->where('id=' . $id)->find();
		$this->ajaxReturn($values[$type]);
	}
}
?>