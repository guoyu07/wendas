<?php
class IndexAction extends UserAction{

    //初始化
    protected function _initialize() {
        parent::_initialize();
    }
    
    //打赏
    public function award() {
    	if($this->isAjax()) { 
    		$id = (int)$_POST['id'];
    		$fee = (int)$_POST['fee'];
    		$user_joke_mod = D('user_joke');
       		$user_joke = $user_joke_mod->where('id='.$id)->find();
    		if($user_joke) {
                if($user_joke['user_id'] == $this->user['id']) {
                    $this->ajaxReturn(array('err' => 0,'msg' => '自己不能打赏自己!'));
                }

                $user_record_mod = D('user_record');
                $user_record = $user_record_mod->where('joke_id='.$id.' and user_id='.$this->user['id'])->find();
                //检测是否已被自己打赏
                if($user_record && $user_record['award'] == 1) {
                    $this->ajaxReturn(array('err' => 0,'msg' => '您已打赏过了!'));
                }

    			//扣除自己的囧币
    			if($this->reduce_money($fee)) {
    				//增加消费记录
					$params = array('user_id' => $this->user['id'],
									'value' => $fee,
									'type' => C('TRACE_STATUS_COST'),
									'content' => '打赏了 '.$user_joke['title'].' ，花了 '.$fee.' 囧币',
									'created_time' => time());
					D('user_trace')->add($params);
                    //保存操作记录
                    if(!$user_record) {
                        $data = array();
                        $data['type'] = '';
                        $data['joke_id'] = $id;
                        $data['user_id'] = $this->user['id'];
                        $data['award'] = 1;
                        $data['created_time'] = time();
                        $user_record_mod->add($data);
                    }else {
                        $user_record_mod->where('id='.$user_record['id'])->save(array('award' => 1));
                    }
    			}else {
    				$this->ajaxReturn(array('err' => 0,'msg' => '囧币不足!'));
    			}

    			$user_id = $user_joke['user_id'];
    			if($user_joke['is_package'] == 1 && $user_joke['package_user_id'] > 0) {
    				$user_id = $user_joke['package_user_id'];
    			}
    			//给笑话的发稿人增加囧币
				$joke_user = D('user')->where('id='.$user_id)->find();	
				$m = $joke_user['money'] + $fee;
				D('user')->where('id='.$user_id)->save(array('money' => $m));
				//增加消费记录
				$params = array('user_id' => $user_id,
								'value' => $fee,
								'type' => C('TRACE_STATUS_INCOME'),
								'content' => $user_joke['title'].' 被打赏了，获得 '.$fee.' 囧币',
								'created_time' => time());
				D('user_trace')->add($params);

                //给笑话增加打赏囧币
                D('user_joke')->where('id='.$id)->setField(array('award_num' => array('exp', "(award_num + {$fee})")));	

				$this->ajaxReturn(array('err' => 1,'msg' => '操作成功!'));
    		}
    		$this->ajaxReturn(array('err' => 0,'msg' => '操作失败!'));
    	}
    }
    //评论
    public function review() {
    	if($this->isAjax()) { 
    		$id = (int)$_POST['id'];
    		$content = $_POST['content'];
    		if(mb_strlen($content,'utf8') > 140) {
    			$this->ajaxReturn(array('err' => 0,'msg' => '评论内容请保持140个字符!'));
    		}

            if($this->check_word($content)) {
                $this->ajaxReturn(array('err' => 0,'msg' => '不能有非法字符!'));
            }

    		$user_review_mod = D('user_review');
			$data = array();
            $data['at_user_id'] = (int)$_POST['at_user_id'];
			$data['joke_id'] = $id;
			$data['user_id'] = $this->user['id'];
			$data['content'] = $content;
			$data['created_time'] = time();
            $data['status'] = C('REVIEW_STATUS_UNAUDIT');
			if($user_review_mod->add($data)) {
				//给笑话加评论数
				//D('user_joke')->where('id='.$id)->setField(array('review_num' => array('exp', "(review_num + 1)")));

                $review = $this->get_review($id);
				$this->ajaxReturn(array('err' => 1,'msg' => $review));
			}
    		$this->ajaxReturn(array('err' => 0,'msg' => '操作失败!'));
    	}
    }
   
    //包养
    public function package() {
    	if($this->isAjax()) { 
    		$id = (int)$_POST['id'];
    		$user_joke_mod = D('user_joke');
       		$user_joke = $user_joke_mod->where('id='.$id)->find();

            if($user_joke && $user_joke['is_package'] == 0) {
                $this->ajaxReturn(array('err' => 0,'msg' => '此笑话不可包养!'));
            }
            if($user_joke && $user_joke['package_user_id'] > 0) {
                $this->ajaxReturn(array('err' => 0,'msg' => '很遗憾，您下手晚了!'));
            }
            if($user_joke && $user_joke['user_id'] == $this->user['id']) {
                $this->ajaxReturn(array('err' => 0,'msg' => '自己不能包养自己!'));
            }

            //检测是否已被自己包养
            $user_record_mod = D('user_record');
            $user_record = $user_record_mod->where('joke_id='.$id.' and user_id='.$this->user['id'])->find();
            if($user_record && $user_record['package'] == 1) {
                $this->ajaxReturn(array('err' => 0,'msg' => '您已包养过了!'));
            }

    		if($user_joke && $user_joke['is_package'] == 1 && $user_joke['package_user_id'] == 0 && $user_joke['user_id'] != $this->user['id']) {
    			$fee = $user_joke['package_fee'];
    			//扣除自己的囧币
    			if($this->reduce_money($fee)) {
    				//增加消费记录
					$params = array('user_id' => $this->user['id'],
									'value' => $fee,
									'type' => C('TRACE_STATUS_COST'),
									'content' => '包养了 '.$user_joke['title'].' ，花了 '.$fee.' 囧币',
									'created_time' => time());
					D('user_trace')->add($params);
					//更新包养人的package_user_id
					$user_joke_mod->where('id='.$id)->save(array('package_user_id' => $this->user['id']));
                   //保存操作记录
                    if(!$user_record) {
                        $data = array();
                        $data['type'] = '';
                        $data['joke_id'] = $id;
                        $data['user_id'] = $this->user['id'];
                        $data['package'] = 1;
                        $data['created_time'] = time();
                        $user_record_mod->add($data);
                    }else {
                        $user_record_mod->where('id='.$user_record['id'])->save(array('package' => 1));
                    }

    				//给笑话的发稿人增加囧币
					$joke_user = D('user')->where('id='.$user_joke['user_id'])->find();	
					$m = $joke_user['money'] + $fee;
					D('user')->where('id='.$user_joke['user_id'])->save(array('money' => $m));
					//增加消费记录
					$params = array('user_id' => $user_joke['user_id'],
									'value' => $fee,
									'type' => C('TRACE_STATUS_INCOME'),
									'content' => $user_joke['title'].' 被包养了，获得 '.$fee.' 囧币',
									'created_time' => time());
					D('user_trace')->add($params);	
					
					$this->ajaxReturn(array('err' => 1,'msg' => array('id' => $this->user['id'], 'username' => $this->user['username'])));
    			}
    			$this->ajaxReturn(array('err' => 0,'msg' => '囧币不足!'));
    		}
    		$this->ajaxReturn(array('err' => 0,'msg' => '操作失败!'));
    	}
    }

}