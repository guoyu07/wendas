<?php
class IndexAction extends UserAction{
	
	private $user_trace_mod;
	//初始化
    protected function _initialize()
    {
        parent::_initialize();

        $this->user_trace_mod = D('user_trace');
        
        //本月囧币
        $mtrace = $this->get_trace();
        $this->assign('mtrace',$mtrace);

        //下个等级信息
        $next_level = $this->get_next_level();
        $this->assign('next_level',$next_level);
        //右侧用户列表
        $user_list = $this->get_rand_user();
        $this->assign('user_list',$user_list);

    }

	//我的信息
    public function index() {
        $type = isset($_GET['type']) ? trim($_GET['type']) : 'all';
        $where = 'user_id='.$this->user['id'];
        //7天内
        if($type == 'week') {
        	$start = strtotime('-7 day');
        	$where .= ' and created_time >='.$start;
        }
        //一月内
        if($type == 'month') {
        	$start = strtotime('-1 month');
        	$where .= ' and created_time >='.$start;
        }
        //收入
        if($type == 'income') {
        	$where .= ' and type='.C('TRACE_STATUS_INCOME');

        }
        //消费
        if($type == 'cost') {
        	$where .= ' and type='.C('TRACE_STATUS_COST');
        }
        
        $count = $this->user_trace_mod->where($where)->count();
		import("ORG.Util.Page");
		$page = new Page($count,10);

        $trace = $this->user_trace_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('trace',$trace);
       
        $page_str = str_replace('/User/Index/index', '/user', $page->show());
        $this->assign('page',$page_str);
        $this->assign('type',$type);

        $this->assign('title','我的信息');
        $this->display();
    }

    //我的信息
    public function feed() {
        $type = isset($_GET['type']) ? trim($_GET['type']) : 'all';
        $where = 'user_id='.$this->user['id'];
        //7天内
        if($type == 'week') {
            $start = strtotime('-7 day');
            $where .= ' and created_time >='.$start;
        }
        //一月内
        if($type == 'month') {
            $start = strtotime('-1 month');
            $where .= ' and created_time >='.$start;
        }
        //收入
        if($type == 'income') {
            $where .= ' and type='.C('TRACE_STATUS_INCOME');

        }
        //消费
        if($type == 'cost') {
            $where .= ' and type='.C('TRACE_STATUS_COST');
        }
        
        $count = $this->user_trace_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);

        $trace = $this->user_trace_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('trace',$trace);

        $page_str = str_replace('/User/Index/index', '/user', $page->show());
        $this->assign('page',$page_str);
        $this->assign('type',$type);

        $this->assign('title','我的信息');
        $this->display();
    }
    
    //我的投稿
    public function joke() {
    	$type = isset($_GET['type']) ? (int)($_GET['type']) : 'all';
        $where = 'user_id='.$this->user['id'];
       	if($type != 'all' && $type > 0) {
       		$where .= ' and status='.$type;
       	}

       	$user_joke_mod = D('user_joke');
        $count = $user_joke_mod->where($where)->count();
		import("ORG.Util.Page");
		$page = new Page($count,10);

        $joke = $user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('joke',$joke);
        
        $page_str = str_replace('/User/Index/joke', '/user/joke', $page->show());
        $this->assign('page',$page_str);
        $this->assign('type',$type);

        $this->assign('title','我的投稿');
        $this->display();

    }
    //我的评论
    public function review() {
        $where = 'user_id='.$this->user['id'].' and at_user_id > 0';
       	
       	$user_review_mod = D('user_review');
        $count = $user_review_mod->where($where)->count();
		import("ORG.Util.Page");
		$page = new Page($count,10);

        $review = $user_review_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        foreach ($review as $key => $value) {
            $at_user = D('user')->where('id='.$value['at_user_id'])->find();
            $review[$key]['user_info'] = $at_user;

            $user_joke = D('user_joke')->where('id='.$value['joke_id'])->find();
            $review[$key]['joke_info'] = $user_joke; 
        }
        $this->assign('review',$review);
        $page_str = str_replace('/User/Index/review', '/user/review', $page->show());
        $this->assign('page',$page_str);
       
        $this->assign('title','我的评论');
        $this->display();
    }
    //我的礼品
    public function gift() {
    	$where = 'user_id='.$this->user['id'];
       	
       	$user_gift_mod = D('user_gift');
        $count = $user_gift_mod->where($where)->count();
		import("ORG.Util.Page");
		$page = new Page($count,10);

        $gift = $user_gift_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        foreach ($gift as $key => $value) {
            $gift[$key]['gift_info'] = json_decode($value['gift_info'],true);
        }
        $this->assign('gift',$gift);
        $page_str = str_replace('/User/Index/gift', '/user/gift', $page->show());
        $this->assign('page',$page_str);
       

        $this->assign('title','我的礼品');
        $this->display();
    }
    //我的资料
    public function info() {
        $this->assign('title','个人资料');
    	$this->display();
    }
    //修改密码
    public function setpassword() {
    	if($this->isAjax()) {
    		if($_POST['password'] != $_POST['confirm_password']) {
				$this->ajaxReturn(array('err'=>0,'msg'=>'两次密码不一样!'));
			}

    		$user_mod = D('user');
			$user = $user_mod->where('id='.$this->user['id'])->find();
			//新密码
			$password = $this->substr_pwd($_POST['password']);
			if($user && $this->substr_pwd($_POST['old_password']) == $user['password']) {
				if($user_mod->where('id='.$this->user['id'])->save(array('password' => $password))) {
					$this->ajaxReturn(array('err'=>1,'msg'=>'修改成功!'));
				}else {
					$this->ajaxReturn(array('err'=>0,'msg'=>'修改失败!'));
				}
			}else {
				$this->ajaxReturn(array('err'=>0,'msg'=>'原密码不正确!'));
			}
		}
    }
    //创建密码
    public function createpassword() {
        if($this->isAjax()) {
            if($_POST['password'] != $_POST['confirm_password']) {
                $this->ajaxReturn(array('err'=>0,'msg'=>'两次密码不一样!'));
            }

            $user_mod = D('user');
            $user = $user_mod->where('id='.$this->user['id'])->find();
            //新密码
            $password = $this->substr_pwd($_POST['password']);
            if($user) {
                if($user_mod->where('id='.$this->user['id'])->save(array('password' => $password, 'status' => C('USER_STATUS_NORMAL')))) {
                    $this->ajaxReturn(array('err'=>1,'msg'=>'修改成功!'));
                }else {
                    $this->ajaxReturn(array('err'=>0,'msg'=>'修改失败!'));
                }
            }
        }
    }
    //设置Email
    public function setemail() {
        if($this->isAjax()) {
            if(trim($_POST['email']) == '') {
                $this->ajaxReturn(array('err'=>0,'msg'=>'Email不能为空!'));
            }
            $user_mod = D('user');
            $user = $user_mod->where('id <> '.$this->user['id'].' and email="'.$_POST['email'].'"')->find();
            if($user) {
                 $this->ajaxReturn(array('err'=>0,'msg'=>'Email已存在!'));
            }
            if($user_mod->where('id='.$this->user['id'])->save(array('email' => $_POST['email']))) {
                $this->ajaxReturn(array('err'=>1,'msg'=>'修改成功!'));
            }else {
                $this->ajaxReturn(array('err'=>0,'msg'=>'修改失败!'));
            }
        }
    }
    //设置QQ
    public function setqq() {
    	if($this->isAjax()) {
    		if(trim($_POST['qq']) == '') {
				$this->ajaxReturn(array('err'=>0,'msg'=>'QQ不能为空!'));
			}
    		$user_mod = D('user');
			if($user_mod->where('id='.$this->user['id'])->save(array('qq' => $_POST['qq']))) {
				$this->ajaxReturn(array('err'=>1,'msg'=>'修改成功!'));
			}else {
				$this->ajaxReturn(array('err'=>0,'msg'=>'修改失败!'));
			}
		}
    }
    //设置手机号
    public function setphone() {
    	if($this->isAjax()) {
    		if(trim($_POST['phone']) == '') {
				$this->ajaxReturn(array('err'=>0,'msg'=>'手机号不能为空!'));
			}
    		$user_mod = D('user');
			if($user_mod->where('id='.$this->user['id'])->save(array('phone' => $_POST['phone']))) {
				$this->ajaxReturn(array('err'=>1,'msg'=>'修改成功!'));
			}else {
				$this->ajaxReturn(array('err'=>0,'msg'=>'修改失败!'));
			}
		}
    }

    //设置头像
    public function setavatar() {
    	if($this->isAjax()) {
            $x = $_POST['x'];
            $y = $_POST['y'];
            $w = $_POST['width'];
            $h = $_POST['height'];
            $url = $_POST['url'];

            if(trim($x) == '' || trim($y) == '' || trim($w) == '' || trim($h) == '' || trim($url) == '') {
                $this->ajaxReturn(array('err'=>0,'msg'=>'参数不能为空!'));
            }
           
            $img = $this->cut_img('.'.$url,$x,$y,$w,$h);
    		
    		$user_mod = D('user');
			if($user_mod->where('id='.$this->user['id'])->save(array('avatar' => substr($img, 1)))) {
				$this->ajaxReturn(array('err'=>1,'msg'=> $img ));
			}else {
				$this->ajaxReturn(array('err'=>0,'msg'=>'修改失败!'));
			}
		}
    }

    private function get_trace() {
    	//本月
    	$start = strtotime(date('Y-m',time()).'-01');
    	$where = 'user_id = '.$this->user['id'].' and created_time >='.$start.' and type > 0';
        $trace = $this->user_trace_mod->where($where)->select();
        $income = $cost = 0;
        foreach ($trace as $key => $value) {
        	//计算收入
        	if($value['type'] == C('TRACE_STATUS_INCOME')) {
        		$income += $value['value'];
        	}
        	if($value['type'] == C('TRACE_STATUS_COST')) {
        		$cost += $value['value'];
        	}
        }
     	return array('income' => $income, 'cost' => $cost);
    }

    private function cut_img($image,$x,$y,$w,$h) {
        $path = pathinfo($image);
        
        $type = $path['extension'];
        switch($type) {
            case 'jpg' :
                $image = imagecreatefromjpeg($image);
                break;
            case 'jpeg' :
                $image = imagecreatefromjpeg($image);
                break;
            case 'png' :
                $image = imagecreatefrompng($image);
                break;
            case 'gif' :
                $image = imagecreatefromgif($image);
                break;
            default:
                $this->ajaxReturn(array('err'=>0,'msg'=>'不支持的格式!'));
                exit;
        }
        $copy = $this->image_crop($image, $x, $y, $w, $h);

        $filename = $path['basename'];
        $cutPicfolder = $path['dirname'].'/';

        $newName = 'cut_'.$filename;
        $targetPic = $cutPicfolder.$newName;

        //TODO 目录与写文件检测
        if(false === imagejpeg($copy, $targetPic) ){
            $this->ajaxReturn(array('err'=>0,'msg'=>'生成裁剪图片失败！请确认保存路径存在且可写！'));
        } 
        @unlink($image);
        return $targetPic;
       // $this->ajaxReturn(array('err'=>1,'msg'=>$targetPic));
    }

    private function image_crop($image, $x, $y, $w, $h) {
       // Plug-in 15: Image Crop
       //
       // This plug-in takes a GD image and returns a cropped
       // version of it. If any arguments are out of the
       // image bounds then FALSE is returned. The arguments
       // required are:
       //
       //    $image:   The image source
       //    $x & $y:  The top-left corner
       //    $w & $h : The width and height

       $tw = imagesx($image);
       $th = imagesy($image);

       if ($x > $tw || $y > $th || $w > $tw || $h > $th)
          return FALSE;

       $temp = imagecreatetruecolor($w, $h);
       imagecopyresampled($temp, $image, 0, 0, $x, $y, $w, $h, $w, $h);
       return $temp;

    }
}