<?php

class BaseAction extends Action{



	protected $user = false;

	protected $setting;

	protected $flink;

	protected $level;

	protected $tags;

	protected $seo;

	protected $adv;



	protected function _initialize() {

		//网站配置

		$this->get_setting();

		//seo

		$this->get_seo();

		//广告

		$this->get_adv();

		//友情链接

		$this->get_flink();

		//标签数据

		$this->get_tags();

		//等级信息

		$this->get_level();

		//获取用户信息

		$this->get_user();



		//计算升级

		if($this->user && $this->user['level'] < 99) {

			if(isset($_SESSION['start_time'])) {

				$start_time = $_SESSION['start_time'];

				$time = time();

				$t = $time - $start_time;

				if($t > 60) {

					$t = floor($t / 60);

					//处理升级

					$next_level = $this->get_next_level();

					if($this->user['online_time'] + $t >= $next_level['minute']) {

						$t = abs($next_level['minute'] - ($this->user['online_time'] + $t));



						$level = $this->user['level'] + 1;

						$money = $this->user['money'] + $next_level['award'];

						$online_time = $t;



						//增加消费记录

						$params = array('user_id' => $this->user['id'],

										'value' => $next_level['award'],

										'type' => C('TRACE_STATUS_INCOME'),

										'content' => '升到 '.$level.' 级 ，获得 '.$next_level['award'].' 囧币',

										'created_time' => time());

						D('user_trace')->add($params);



						D('user')->where('id='.$this->user['id'])->save(array('level' => $level, 'money' => $money, 'online_time' => $online_time));



					}else {

						D('user')->where('id='.$this->user['id'])->setField(array('online_time' => array('exp', "(online_time + {$t})")));	

					}



					$t = $_SESSION['start_time'] + ($t * 60);

					$_SESSION['start_time'] = $t;

				}

			}

		}

	}



	/** 获取用户信息*/

    private function get_user(){

    	$id = 0;

    	if(isset($_SESSION['xj_id']) && isset($_SESSION['xj_expire'])) {

    		if($_SESSION['xj_expire'] <= time()) {

    			unset($_SESSION['xj_id']);

    			unset($_SESSION['xj_expire']);

    		}else {

    			$id = $_SESSION['xj_id'];

    			$_SESSION['xj_expire'] = time() + C('SESSION_EXPIRE');

    		}

    	}else {

    		if(isset($_COOKIE['user']['id'])){

    			$id = $_COOKIE['user']['id'];

    			if(!isset($_SESSION['start_time'])) { 

    				$_SESSION['start_time'] = time();

    			}

			}

    	}

    	$user_mod = D('user');

    	$this->user = $user_mod->where('id='.$id)->find();

		if($this->user) {

			unset($this->user['password']);

			$this->assign('user',$this->user);

		}

	}



	/** 获取设置信息*/

	private function get_setting() {

		if(S('setting')) {

			$set = S('setting');

		}else {

			$setting_mod = D('setting');

			$setting = $setting_mod->select();

			foreach ($setting as $val) {

				$set[$val['name']] = $val['data'];

			}

	        S('setting',$set,'3600');

	    }

        $this->setting = $set;

        $this->assign('setting',$this->setting);

	}



	/** 获取设置信息*/

	private function get_seo() {

		$seo_mod = D('seo');

		$seo = $seo_mod->select();

        $this->seo = $seo;

        $this->assign('seo',$this->seo);

	}



	/** 获取广告信息*/

	private function get_adv() {

		if(S('adv')) {

			$adv = S('adv');

		}else {

			$adv_mod = D('adv');

			$adv_list = $adv_mod->select();

			$adv = array();

			foreach ($adv_list as $val) {

				$adv[$val['name']] = $val['code'];

			}

	        S('adv',$adv,'3600');

	    }

        $this->adv = $adv;

        $this->assign('adv',$this->adv);

	}



	/** 获取友情链接信息*/

	private function get_flink() {

		if(S('flink')) {

			$flink = S('flink');

		}else {

			$flink_mod = D('flink');

			$flink = $flink_mod->order('ordid asc')->where('status=1')->select();

	        S('flink',$flink,'3600');

	    }

        $this->flink = $flink;

        $this->assign('flink',$this->flink);

	}



	/** 获取标签信息*/

	private function get_tags() {

		if(S('tags')) {

			$tags = S('tags');

		}else {

			$tags_mod = D('tags');

			$tags = $tags_mod->order('sort asc')->select();

	        S('tags',$tags,'3600');

	    }

        $this->tags = $tags;

        $this->assign('tags',$this->tags);

	}



	/** 获取等级固话*/

	private function get_level() {

		if(S('level')) {

			$level = S('level');

		}else {

			$level_mod = D('level');

			$level = $level_mod->order('level asc')->select();	

			S('level',$level,'3600');

		}

		$this->level = $level;

		$this->assign('level',$this->level);

	}



	/** 获取seo*/

	protected function getseo($alias,$page = '') {

		foreach ($this->seo as $key => $value) {

			if($value['alias'] == $alias) {

				$this->assign('title',$value['title'].$page);

				$this->assign('keywords',$value['keywords']);

				$this->assign('description',$value['description']);

			}

		}

	}



	/** 获取当前等级信息*/

	protected function get_curr_level() {

		$level = array();

		foreach ($this->level as $key => $value) {

			if($value['level'] == $this->user['level']) {

				$level = $value;

			}

		}

		return $level;

	}



	/** 获取下个等级信息*/

	protected function get_next_level() {

		$level = array();

		foreach ($this->level as $key => $value) {

			if($value['level'] == $this->user['level'] + 1) {

				$level = $value;

			}

		}

		return $level;

	}



	 //获取最好笑话

    protected function get_good_joke($type) {

        $user_joke_mod = D('user_joke');

        $where = 'type = '.$type.' and status = '.C('JOKE_STATUS_AUDIT').' and commend=1';



        $user_joke = $user_joke_mod->where($where)->order('rand()')->limit('0,10')->select();

        //$user_joke = $user_joke_mod->where($where)->order('good_num desc')->limit('0,10')->select();

        return $user_joke;

    }

    /** 最佳当天评审官*/

    protected function get_audit_day_user() {

    	//今天

    	$time = strtotime(date('Y-m-d',time()));

    	return $this->get_audit_user($time);

    }

    /** 最佳本周评审官*/

    protected function get_audit_week_user() {

    	$day = date('w');

    	if($day == 0) $day = 7;



    	$time = strtotime(date('Y-m-d',strtotime("-{$day} day")).' 23:59:59');

    	return $this->get_audit_user($time);

    }

    

    /** 最佳评审官*/

    private function get_audit_user($time) {

    	$where = "j.status=".C('JOKE_STATUS_AUDIT').' and a.type < 4 and a.created_time >='.$time;

    	//$where = "j.status=".C('JOKE_STATUS_AUDIT').' and a.type < 4';

    	$field = "j.id,j.status,a.joke_id,a.user_id";

    	$join = "a left join xh_user_joke j on a.joke_id=j.id";



    	$audit = D('user_audit')->field($field)->join($join)->where($where)->select();

    	$user_list = array();

    	foreach ($audit as $key => $value) {

    		if(isset($user_list[$value['user_id']])) {

    			$user_list[$value['user_id']] += 1;

    		}else {

    			$user_list[$value['user_id']] = 1;

    		}

    	}

    	arsort($user_list);



    	foreach ($user_list as $key => $value) {

    		$user = D('user')->find($key);

    		$user_list[$key] = $user;

    		$user_list[$key]['day_audit_num'] = $value;

    	}

        

        return $user_list;

    }



	/** 获取随机9个用户信息*/

	protected function get_rand_user() {

		$user_mod = D('user');

		$user = $user_mod->order('rand()')->limit('0,15')->select();

		return $user;

	}



	/** 减少囧币*/

	protected function reduce_money($money) {

		if($money && $this->user['money'] >= $money) {

			$user_mod = D('user');	

			$m = $this->user['money'] - $money;

			if($user_mod->where('id='.$this->user['id'])->save(array('money' => $m))) {

				return true;	

			}

		}

		return false;

	}



	/** 增加囧币*/

	protected function add_money($money) {

		if($money) {

			$user_mod = D('user');	

			$m = $this->user['money'] + $money;

			if($user_mod->where('id='.$this->user['id'])->save(array('money' => $m))) {

				return true;	

			}

		}

		return false;

	}



	/** 获取用户操作笑话的记录*/

	protected function get_record($joke_id) {

		if($this->user) {

          $user_id = $this->user['id'];  

        }else {

          $user_id = session_id();

        }



		$user_record_mod = D('user_record');

		$user_record = $user_record_mod->where('user_id="'.$user_id.'" and joke_id='.$joke_id)->find();

		//包养信息

		$user_joke = D('user_joke')->where('id='.$joke_id)->find();

		if($user_joke && $user_joke['package_user_id'] > 0) {

			$p_user = D('user')->where('id='.$user_joke['package_user_id'])->find();

			$user_record['package_info'] = $p_user;

		}

		return $user_record;

	}



	/** 获取评论记录*/

	protected function get_review($joke_id,$page = 1) {

    	$user_review_mod = D('user_review');



    	$where = 'joke_id='.$joke_id.' and status='.C('REVIEW_STATUS_AUDIT');



    	$count = $user_review_mod->where($where)->count();

    	$pagesize = 5;

    	$start = ($page - 1 ) * $pagesize;

    	//$end = $start + $pagesize > $count : $count : $start + $pagesize;

    	$user_review = $user_review_mod->where($where)->limit($start .','.$pagesize)->order('id desc')->select();

        foreach ($user_review as $key => $value) {

            $user_review[$key]['user_info'] = D('user')->where('id='.$value['user_id'])->find();    

        }

		 return $user_review;

    }



	//404页面

   	protected function _empty(){ 

        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 

        $this->display('./Index/Tpl/Public/default/Index_404.html');

        exit;

    }

    //错误页面

    protected function msg($info,$msg){ 

    	$this->assign('info',$info);

    	$this->assign('msg',$msg);

        $this->display('./Index/Tpl/Public/default/Index_msg.html');

        exit;

    }  



    //发送邮件

    protected function send_email($address,$title,$message)

	{

		import('ORG.PHPMailer.PHPMailer');

		$mail = new PHPMailer();

		//以html格式发送

		$mail->IsHTML(true);

		// 设置PHPMailer使用SMTP服务器发送Email

		$mail->IsSMTP();

		// 设置邮件的字符编码，若不指定，则为'UTF-8'

		$mail->CharSet='UTF-8';

		// 添加收件人地址，可以多次使用来添加多个收件人

		$mail->AddAddress($address);

		// 设置邮件正文

		$mail->Body=$message;

		// 设置邮件头的From字段。

		$mail->From=$this->setting['mail_address'];

		// 设置发件人名字

		$mail->FromName=$this->setting['mail_address'];

		// 设置邮件标题

		$mail->Subject=$title;

		// 设置SMTP服务器。

		$mail->Host=$this->setting['mail_smtp'];

		$mail->Port = '465';
		
		$mail->SMTPSecure = 'ssl';

		// 设置为“需要验证”

		$mail->SMTPAuth=true;

		// 设置用户名和密码。

		$mail->Username=$this->setting['mail_loginname'];

		$mail->Password=$this->setting['mail_password'];

		// 发送邮件。

		return($mail->Send());

	}



	/** 密码保存16位*/

	protected function substr_pwd($pwd) {

		$pwd = md5($pwd);

		if(strlen($pwd) == 32) {

			return substr($pwd, 8, 16);

		}

		return $pwd;

	}



	/** 生成随机6位数*/

	protected function rand_six_num() {

		return mt_rand(100000,999999);

	}



	/** 检测是否登录*/

	protected function check_login() {

		if(!$this->user) {



			if($this->isAjax()) { 

				$this->ajaxReturn(array('err' => 0,'msg' => '请先登录!'));

			}



			header('Location:/account/login');

		}

	}



	/** 检测是否有敏感词*/

	protected function check_word($content) {

		$word = $this->setting['sensitive'];

		$word = explode(',', $word);

		

		foreach ($word as $key => $value) {

			if(strpos($content, $value) > -1) {

				return true;

			}

		}

		return false;

	}



}