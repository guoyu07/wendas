<?php
class PublicAction extends Action
{
	// 菜单页面
	public function menu(){
		//显示菜单项
		$id	=	intval($_REQUEST['tag'])==0?6:intval($_REQUEST['tag']);
		$menu  = array();

		$role_id = D('admin')->where('id='.$_SESSION['admin_info']['id'])->getField('role_id');
		
		$node_ids_res = D("access")->where("role_id=".$role_id)->field("node_id")->select();
		
		$node_ids = array();
		foreach ($node_ids_res as $row) {
			array_push($node_ids,$row['node_id']);
		}
		$node_ids = implode(',', $node_ids);
		//读取数据库模块列表生成菜单项
		$node = M("node");
		$where = "auth_type<>2 AND status=1 AND is_show=0 AND group_id=".$id;
		/*if($_SESSION['admin_info']['username'] == 'admin') {
			$where = "auth_type<>2 AND status=1 AND is_show=0 AND group_id=".$id;
		}else {
			$where = "auth_type<>2 AND status=1 AND is_show=0 AND group_id=".$id." AND id in($node_ids)";
		}*/
		$list	=$node->where($where)->field('id,action,action_name,module,module_name,data')->order('sort DESC')->select();
		foreach($list as $key=>$action) {
			$data_arg = array();
			if ($action['data']){
				/*$data_arr = explode('&', $action['data']);
				foreach ($data_arr as $data_one) {
					$data_one_arr = explode('=', $data_one);
					$data_arg[$data_one_arr[0]] = $data_one_arr[1];
				}*/
				$data_arg = array('data' => $action['data']);
			}
			$action['url'] = U($action['module'].'/'.$action['action'], $data_arg);//为每个模块赋值
			if ($action['action']) 
			{
				$menu[$action['module']]['navs'][] = $action;
			}
			$menu[$action['module']]['name']	= $action['module_name'];
			$menu[$action['module']]['id']	= $action['id'];
		}
		$this->assign('menu',$menu);
		$this->display('left');
	}

	/**	 
	 * 主界面
	 */
	public function main()
	{
        $security_info=array();
		
		if(count($security_info)<=0){
			$this->assign('no_security_info',0);
		}
		else{
			$this->assign('no_security_info',1);
		}	
		$this->assign('security_info',$security_info);
        $disk_space = @disk_free_space(".")/pow(1024,2);
		$server_info = array(
		    '程序版本'=>'1.0',		
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],	
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',		
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round($disk_space < 1024 ? $disk_space:$disk_space/1024 ,2).($disk_space<1024?'M':'G'),
		);
        $this->assign('set',$this->setting);
		$this->assign('server_info',$server_info);	
		$this->display();
	}

	public function login()
	{
		
		if ($_POST) {
			$username = $_POST['username'] && trim($_POST['username']) ? trim($_POST['username']) : '';
			$password = $_POST['password'] && trim($_POST['password']) ? trim($_POST['password']) : '';
			if (!$username || !$password) {
				redirect(u('public/login'));
			}
			
			if ($_SESSION['verify'] != md5($_POST['verify']))
			{
				$this->error(L('verify_error'));
			}
			
			//生成认证条件	
			$admin_mod = M('admin');		
			$admin_info = $admin_mod->where("username='$username' and status=1")->find();

			//使用用户名、密码和状态的方式进行认证
			if(false === $admin_info) {
				$this->error('帐号不存在或已禁用！');
			}else {
				if($admin_info['password'] != md5($password)) {
					$this->error('密码错误！');
				}

				$_SESSION['admin_info'] =$admin_info;

				$this->success('登录成功！',u('index/index'));
				exit;
			}
			
		}
		$this->assign('set',$this->setting);
		$this->display();
	}

	public function logout()
	{
		if(isset($_SESSION['admin_info'])) {
			unset($_SESSION['admin_info']);			
			$this->success('退出登录成功！',u('public/login'));
		}else {
			$this->error('已经退出登录！');
		}
	}

		
	public function getCate($pid=0,$arr=array(),$i=0)//获取商品分类表
	{
		$m=M('shop_cate');
		$data=$m->where("pid=$pid")->select();
		if(!$data)
		{
			return $arr;
		}
		foreach($data as $key=>$val)
		{
			$val['level']=$i;
			$i++;
			$arr[]=$val;
			$arr=$this->getCate($val['id'],$arr,$i);
			$i--;
		}
		return $arr;
	}



	public function catid($pid,$arr=array(),$status='')//根据传过来的catid取得他所有的子类
	{
		$m=M('shop_cate');

		if($status == 1)
		{
			$data = $m->where("pid=$pid and status=1")->select();
		}
		else
		{
			$data=$m->where('pid='.$pid)->select();
		}
		if(!$data)
		{
			return $arr;
		}
		foreach($data as $key=>$val)
		{
			$arr[]=$val['id'];
			$arr=$this->catid($val['id'],$arr);
		}
		$arr[]=$pid;
		return $arr;
	}




	public function json($array) { //处理json数据中文状态下乱码的问题
	   $this->arrayRecursive($array, 'urlencode', true); 
	    $json = json_encode($array); 
	    return urldecode($json); 
	} 

	public function arrayRecursive(&$array, $function, $apply_to_keys_also = false){ 
	    static $recursive_counter = 0; 
	    if (++$recursive_counter > 1000) { 
	        die('possible deep recursion attack'); 
	    } 
	    foreach ($array as $key => $value) { 
	        if (is_array($value)) { 
	            arrayRecursive($array[$key], $function, $apply_to_keys_also); 
	        } else { 
	            $array[$key] = $function($value); 
	        }                                        
	        if ($apply_to_keys_also && is_string($key)) { 
	            $new_key = $function($key); 
	            if ($new_key != $key) { 
	                $array[$new_key] = $array[$key]; 
	                unset($array[$key]); 
	            } 
	        } 
	    } 
	    $recursive_counter--; 
	}       
}
?>