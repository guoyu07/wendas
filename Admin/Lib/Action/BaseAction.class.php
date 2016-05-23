<?php
/**
 * 基础Action
 */
class BaseAction extends Action {
	protected $admin_mod = '';  //管理员模型
	protected $role_mod = '';//权限表
	protected $user = '';
	protected $setting = '';
	protected function mod_init(){
		$this->admin_mod = D('admin');
		$this->role_mod = D('role');
	}
	protected function _initialize() {
		$this->mod_init();		
		$this->site_root="http://".$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==80?'':':'.$_SERVER['SERVER_PORT']).__ROOT__."/";
		$this->assign('site_root',$this->site_root);
		// 用户权限检查
		$this->check_priv();
		//需要登陆
		$admin_info =$_SESSION['admin_info'];
		$this->user = $admin_info;

		$this->assign('my_info', $admin_info);
		//权限表
		$admin_level = $this->role_mod->where('id='.$_SESSION['admin_info']['role_id'])->find();
		$this->assign('admin_level',$admin_level);
		// 顶部菜单
		$model = M("group");
		$top_menu = $model->field('id,title')->where('status=1')->order('sort ASC')->select();
		$this->assign('top_menu',$top_menu);

		//设置
		$setting_mod = D('setting');
		$setting = $setting_mod->select();
		foreach ($setting as $key => $value) {
			$this->setting[$value['name']] = $value['data'];
		}
		
		$this->assign('show_header', true);
		$this->assign('const',get_defined_constants());

		$this->assign('iframe',$_REQUEST['iframe']);
		$def = array(
			'request'=>$_REQUEST
		);	
		$this->assign('def',json_encode($def));
        
	}
	//检查权限
	protected function check_priv()
	{
		if ((!isset($_SESSION['admin_info']) || !$_SESSION['admin_info']) && !in_array(ACTION_NAME, array('login','verify_code'))) {
			$this->redirect('public/login');
		}
		//如果是超级管理员，则可以执行所有操作
		//if($_SESSION['admin_info']['id'] == 1) {
		if($_SESSION['admin_info']['role_id'] == 1) {
			return true;
		}
		/*if(in_array(ACTION_NAME,array('status','sort_order','ordid'))){
			return true;
		}*/
		$module_name = strtolower(MODULE_NAME);
		//排除一些不必要的权限检查
		foreach (C('IGNORE_PRIV_LIST') as $key=>$val){
			if($module_name==$val['module_name']){
				if(count($val['action_list'])==0)return true;

				foreach($val['action_list'] as $action_item){
					if(ACTION_NAME==$action_item)return true;
				}
			}
		}

		$node_mod = D('node');
		$node_id = $node_mod->where(array('module'=>$module_name, 'action'=>ACTION_NAME))->getField('id');//查找对应的权限
		$access_mod = D('access');
		$rel = $access_mod->where(array('node_id'=>$node_id, 'role_id'=>$_SESSION['admin_info']['role_id']))->count();
		if ($rel==0) {
			$this->error(L('_VALID_ACCESS_'));
		}
	}
	
	//截取中文字符串
	protected function mubstr($str,$start,$length)
	{
		import('ORG.Util.String');
		$a = new String();
		$b = $a->msubstr($str,$start,$length);
		return($b);
	}
	//失败页面重写
	public function error($message, $url_forward='',$ms = 3, $dialog=false, $ajax=false, $returnjs = '')
	{
		$this->jumpUrl = $url_forward;
		$this->waitSecond = $ms;
		$this->assign('dialog',$dialog);
		$this->assign('returnjs',$returnjs);
		parent::error($message, $ajax);
	}
	//成功页面重写
	public function success($message, $url_forward='',$ms = 3, $dialog=false, $ajax=false, $returnjs = '')
	{
		$this->jumpUrl = $url_forward;
		$this->waitSecond = $ms;
		$this->assign('dialog',$dialog);
		$this->assign('returnjs',$returnjs);
		parent::success($message, $ajax);
	}

	protected function saddslashes($value)
	{
		if (empty($value)) {
			return $value;
		} else {
			return is_array($value) ? array_map(array('BaseAction','saddslashes'), $value) : addslashes($value);
		}
	}
	/*
	 * 通用删除操作
	 * */
	public function delete(){
		$module_name = strtolower(MODULE_NAME);
		$mod = D($module_name);

		if (isset($_REQUEST['id']) && is_array($_REQUEST['id'])) {
			$ids = implode(',', $_REQUEST['id']);
			$result = $mod->delete($ids);
		} else {
			$id = intval($_REQUEST['id']);
			$result = $mod->delete($id);
		}

		if($result){
			$this->success(L('operation_success'));
		}else{
			$this->error(L('operation_failure'));
		}
	}

	/*
	 * 通用改变状态
	 * */
	public function status(){

		$table=isset($_REQUEST['table'])?trim($_REQUEST['table']):'';
		if($table)	
		{
			$mod = D($table);
			$module_name = strtolower($table);
		}
		else
		{
			$module_name = strtolower(MODULE_NAME);
			$mod = D($module_name);
		}

		$id     = intval($_REQUEST['id']);
		$type   = trim($_REQUEST['type']);
		$sql    = "update ".C('DB_PREFIX').$module_name." set $type=($type+1)%2 where id='$id'";
		$res    = $mod->execute($sql);
		$values = $mod->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}
	/*
	 * 通用排序方法单个排序
	 * */
	public function sort(){
		$module_name = strtolower(MODULE_NAME);
		$mod = D($module_name);
		$id     = intval($_REQUEST['id']);
		$type   = trim($_REQUEST['type']);
		$num=trim($_REQUEST['num']);
		if(!is_numeric($num)){
			$values = $mod->where('id='.$id)->find();			
			$this->ajaxReturn($values[$type]);
			exit;
		}
		$sql    = "update ".C('DB_PREFIX').$module_name." set $type=$num where id='$id'";
        
		$res    = $mod->execute($sql);
		$values = $mod->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}
	
	protected function _stripcslashes($arr){
		if(ini_get('magic_quotes_gpc')!='1')return $arr;
		foreach ($arr as $key=>$val){
			$arr[$key]=stripcslashes($val);
		}
		return $arr;
	}

}
?>
