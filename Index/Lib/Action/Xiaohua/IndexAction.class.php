<?php
class IndexAction extends BaseAction{

	//笑话详情
    public function index() {
       	$id = isset($_GET['id']) ? (int)$_GET['id'] : '';
       	if($id) {
       		$user_joke_mod = D('user_joke');
       		$user_joke = $user_joke_mod->where('id='.$id)->find();
       		if($user_joke && $user_joke['status'] == C('JOKE_STATUS_AUDIT')) {
                
                $user_joke['record'] = $this->get_record($user_joke['id']);
                $user = D('user')->where('id='.$user_joke['user_id'])->find();
                $user_joke['user_info'] = $user;

         			$review = $this->get_review($id);
              $count = D('user_review')->where('joke_id='.$id)->count();

              $rel_joke = $this->get_rel_joke($id);
              $this->assign('count',$count);
         			$this->assign('review',$review);
         			$this->assign('user_joke',$user_joke);
              $this->assign('rel_joke', $rel_joke);

              //文字笑话
              $text = $this->get_good_joke(C('JOKE_TYPE_TEXT'));
              $this->assign('text',$text);
              //搞笑图片
              $pic = $this->get_good_joke(C('JOKE_TYPE_PIC'));
              $this->assign('pic',$pic);
              
              $this->assign('title',$user_joke['title']);
         			$this->display();
              exit;
       		}
       	}
        $this->_empty();
    }

    //点赞
    public function record() {
      if($this->isAjax()) { 
        $id = (int)$_POST['id'];
        $type = $_POST['type'];
        if($this->user) {
          $user_id = $this->user['id'];  
        }else {
          $user_id = session_id();
        }
        
        $user_record_mod = D('user_record');
        $user_record = $user_record_mod->where('joke_id='.$id.' and user_id="'.$user_id.'"')->find();
        if(!$user_record) {
          $data = array();
          $data['type'] = $type;
          $data['joke_id'] = $id;
          $data['user_id'] = $user_id;
          $data['created_time'] = time();
          if($user_record_mod->add($data)) {
            if($type == 'good') {
              //给笑话加点赞数
              D('user_joke')->where('id='.$id)->setField(array('good_num' => array('exp', "(good_num + 1)")));
            }
            if($type == 'bad') {
              //给笑话加倒垂数
              D('user_joke')->where('id='.$id)->setField(array('bad_num' => array('exp', "(bad_num + 1)")));
            }

            $this->ajaxReturn(array('err' => 1,'msg' => '操作成功!'));
          }
        }else {
          if($user_record['type'] == '') {
             $user_record_mod->where('joke_id='.$id.' and user_id="'.$user_id.'"')->save(array('type' => $type));
             if($type == 'good') {
                //给笑话加点赞数
                D('user_joke')->where('id='.$id)->setField(array('good_num' => array('exp', "(good_num + 1)")));
              }
              if($type == 'bad') {
                //给笑话加倒垂数
                D('user_joke')->where('id='.$id)->setField(array('bad_num' => array('exp', "(bad_num + 1)")));
              }
              $this->ajaxReturn(array('err' => 1,'msg' => '操作成功!'));
          }
        }
        $this->ajaxReturn(array('err' => 0,'msg' => '操作失败!'));
      }
    }

     //评论点赞
    public function reviewrecord() {
        if($this->isAjax()) { 
            $id = (int)$_POST['id'];
            if($this->user) {
              $user_id = $this->user['id'];  
            }else {
              $user_id = session_id();
            }

            $user_review_record_mod = D('user_review_record');
            $user_review_record = $user_review_record_mod->where('review_id='.$id.' and user_id="'.$user_id.'"')->find();
            if(!$user_review_record) {
                $data = array();
                $data['review_id'] = $id;
                $data['user_id'] = $user_id;
                $data['created_time'] = time();
                if($user_review_record_mod->add($data)) {
                    //给笑话加评论数
                    D('user_review')->where('id='.$id)->setField(array('good_num' => array('exp', "(good_num + 1)")));
                    //给予神回复
                    $god_reply = D('user_review')->where('id='.$id.' and good_num > 10')->find();
                    if($god_reply) {
                        D('user_joke')->where('id='.$god_reply['joke_id'])->save(array('god_reply' => 1));
                    }

                    $this->ajaxReturn(array('err' => 1,'msg' => '操作成功!'));
                }
            }
            $this->ajaxReturn(array('err' => 0,'msg' => '操作失败!'));
        }
    }

    //ajax获取评论列表
    public function getreview() {
    	if($this->isAjax()) { 
    		$id = (int)$_POST['id'];
    		$p = (int)$_POST['p'];

    		$review = $this->get_review($id,$p);
    		$this->ajaxReturn(array('err' => 1,'msg' => $review));
    	}
    }

    private function get_rel_joke($joke_id) {
      $where = 'status='.C('JOKE_STATUS_AUDIT');
      $user_joke_mod = D('user_joke');
      //上一条
      $pre_joke = $user_joke_mod->where($where.' and id > ' .$joke_id)->order('id asc')->find();
      $next_joke = $user_joke_mod->where($where.' and id < ' .$joke_id)->order('id desc')->find();
      $pre_joke_id = $pre_joke ? $pre_joke['id'] : 0;
      $next_joke_id = $next_joke ? $next_joke['id'] : 0;

      return array('pre_joke_id' => $pre_joke_id, 'next_joke_id' => $next_joke_id);

    }
}