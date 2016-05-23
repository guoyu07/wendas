<?php
class MainAction extends BaseAction{
	
	//初始化
    protected function _initialize()
    {
        parent::_initialize();

        //下个等级信息
        $next_level = $this->get_next_level();
        $this->assign('next_level',$next_level);
        //右侧用户列表
        $user_list = $this->get_rand_user();
        $this->assign('user_list',$user_list);

    }

    //主页
    public function index() {
        $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
        if($user_id) {
            $user_info = D('user')->where('id='.$user_id)->find();

             $where = 'user_id='.$user_id.' and status='.C('JOKE_STATUS_AUDIT');
      
            $user_joke_mod = D('user_joke');
            $count = $user_joke_mod->where($where)->count();
            import("ORG.Util.Page");
            $page = new Page($count,10);

            $user_joke = $user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
            foreach ($user_joke as $key => $value) {
                $user_joke[$key]['record'] = $this->get_record($value['id']);
                $user = D('user')->find($value['user_id']);
                $user_joke[$key]['user_info'] = $user;
            }

            //下一级信息
            $level = array();
            foreach ($this->level as $key => $value) {
                if($value['level'] == $user_info['level'] + 1) {
                    $level = $value;
                }
            }
            
            $page_str = str_replace('/User/Main/index/user_id/', '/user/', $page->show());
            $this->assign('count',$count);
            $this->assign('user_joke',$user_joke);
            $this->assign('user_info',$user_info);
            $this->assign('user_level',$level);
            $this->assign('page',$page_str);

            $this->assign('title',$user_info['username'].'用户主页');
            $this->assign('keywords',$user_info['username'].'用户主页');
            $this->assign('description',$user_info['username'].'用户主页');
            $this->display();

        }else {
            $this->msg('错误提示','非法访问！');
        }

    }
}