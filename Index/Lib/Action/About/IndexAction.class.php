<?php

class IndexAction extends BaseAction{



    public function index() {

        $this->_empty();

    }



    //简介

    public function jianjie() {

    	$this->assign('title','虾囧简介');

    	$this->display();

    }

    //公告

    public function gonggao() {

    	$this->assign('title','虾囧公告');

    	$this->display();

    }

    //声明

    public function shengming() {

    	$this->assign('title','免责声明');

    	$this->display();

    }

    //反馈

    public function feedback() {

    	if($this->isAjax()) { 

    		$feedback_mod = D('feedback');

    		$feedback = $feedback_mod->create();



            if($this->check_word($feedback['content'])) {

                $this->ajaxReturn(array('err' => 0,'msg' => '内容不能有非法字符!'));

            }



            if($this->check_word($feedback['contact'])) {

                $this->ajaxReturn(array('err' => 0,'msg' => '联系方式不能有非法字符!'));

            }



            $code = trim($_POST['code']);

            //验证码

            if ($_SESSION['verify'] != md5($code)) {

                $this->ajaxReturn(array('err' => 0,'msg' => '验证码错误!'));

            }



    		if(trim($feedback['content']) == '' || trim($feedback['contact']) == '') {

    			$this->ajaxReturn(array('err' => 0,'msg' => '请完善信息!'));

    		}
                            $feedback['created_time']=time();
    		if($feedback_mod->add($feedback)) {

    			$this->ajaxReturn(array('err' => 1,'msg' => '感谢您的参与!'));

    		}

    	}

    	$this->assign('title','反馈意见');

    	$this->display();

    }

    //投稿

    public function tougao() {

    	$this->assign('title','投稿规则');

    	$this->display();

    }

    //审稿

    public function shengao() {

    	$this->assign('title','审稿规则');

    	$this->display();

    }

    //升级

    public function shengji() {

    	$this->assign('title','升级规则');

    	$this->display();

    }

    //囧币

    public function jiongbi() {

    	$this->assign('title','虾囧规则');

    	$this->display();

    }



}