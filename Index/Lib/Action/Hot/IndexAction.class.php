<?php
class IndexAction extends BaseAction{

    private $user_joke_mod;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->user_joke_mod = D('user_joke');

        //文字笑话
        $text = $this->get_good_joke(C('JOKE_TYPE_TEXT'));
        $this->assign('text',$text);
        //搞笑图片
        $pic = $this->get_good_joke(C('JOKE_TYPE_PIC'));
        $this->assign('pic',$pic);
        //最佳评审官
        $audit_day_user = $this->get_audit_day_user();
        $audit_week_user = $this->get_audit_week_user();
        $this->assign('audit_day_user',$audit_day_user);
        $this->assign('audit_week_user',$audit_week_user);
    }
    //8小时热门笑话
    public function index() {
        $time = time() - (8 * 3600);
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and created_time >='.$time;
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('good_num desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       // print_r($user_joke);
        $this->assign('user_joke',$user_joke);

        $page_str = str_replace('/Hot/Index/index/p/', '/hot/index_', $page->show());
        $this->assign('page',$page_str);

        $this->getseo('hot');
        $this->display();
    }

    //7天热门
    public function week() {
        $time = strtotime('-7 day');
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and created_time >='.$time;
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('good_num desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       // print_r($user_joke);
        $this->assign('user_joke',$user_joke);

        $page_str = str_replace('/Hot/Index/week/p/', '/hot/week_', $page->show());
        $this->assign('page',$page_str);

        $this->getseo('week');
        $this->display();
    }

    //30天热门
    public function month() {
        $time = strtotime('-1 month');
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and created_time >='.$time;
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('good_num desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       // print_r($user_joke);
        $this->assign('user_joke',$user_joke);

        $page_str = str_replace('/Hot/Index/month/p/', '/hot/month_', $page->show());
        $this->assign('page',$page_str);

        $this->getseo('month');
        $this->display();
    }

}