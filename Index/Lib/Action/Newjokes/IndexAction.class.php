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
    //全部
    public function index() {
        $where = 'status='.C('JOKE_STATUS_AUDIT');
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('audit_time desc,id desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       // print_r($user_joke);
        $this->assign('user_joke',$user_joke);

        $page_str = str_replace('/Index/index/p/', '/index_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }

        $this->getseo('newjokes',$p);
        $this->display();
    }
    //文字笑话
    public function text() {
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and type='.C('JOKE_TYPE_TEXT');
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('audit_time desc,id desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       
        $this->assign('user_joke',$user_joke);
        
        $page_str = str_replace('/Index/text/p/', '/text_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }
        $this->getseo('text',$p);
        $this->display();
    }
    //图片笑话
    public function pic() {
       $where = 'status='.C('JOKE_STATUS_AUDIT').' and type='.C('JOKE_TYPE_PIC');
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('audit_time desc,id desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       
        $this->assign('user_joke',$user_joke);
        
        $page_str = str_replace('/Index/pic/p/', '/pic_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }
        $this->getseo('pic',$p);
        $this->display();
    }
    //动态图
     public function gif() {
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and type='.C('JOKE_TYPE_GIF');
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('audit_time desc,id desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       
        $this->assign('user_joke',$user_joke);
        
        $page_str = str_replace('/Index/gif/p/', '/gif_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }
        $this->getseo('gif',$p);
        $this->display();
    }

    //视屏
     public function video() {
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and type='.C('JOKE_TYPE_VEDIO');
        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);
        
        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('audit_time desc,id desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user = D('user')->find($value['user_id']);
            $user_joke[$key]['user_info'] = $user;
        }
       
        $this->assign('user_joke',$user_joke);
        
        $page_str = str_replace('/Index/video/p/', '/video_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }
        $this->getseo('video',$p);
        $this->display();
    }

    //热门笑话
    public function hotjoke() {
        $where = 'status='.C('JOKE_STATUS_AUDIT');
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
       
        $this->assign('user_joke',$user_joke);
        
        $page_str = str_replace('/Index/hotjoke/p/', '/hotjoke_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }
        $this->getseo('hotjoke',$p);
        $this->display();
    }
    //神回复
    public function godreply() {
        $where = 'status='.C('JOKE_STATUS_AUDIT').' and god_reply=1';

        $count = $this->user_joke_mod->where($where)->count();
        import("ORG.Util.Page");
        $page = new Page($count,10);

        $user_joke = $this->user_joke_mod->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        foreach ($user_joke as $key => $value) {
            $record = $this->get_record($value['id']);
            $user_joke[$key]['record'] = $record;
            $user_joke[$key]['user_info'] = D('user')->where('id='.$value['user_id'])->find();
            //神回复
            $user_review_mod = D('user_review');
            $user_review = $user_review_mod->where('joke_id='.$value['id'])->order('good_num desc')->find();
            $user_review['user_info'] = D('user')->where('id='.$user_review['user_id'])->find();
            $user_joke[$key]['god_reply_info'] = $user_review;
        }
       
        $this->assign('user_joke',$user_joke);
        
        $page_str = str_replace('/Index/godreply/p/', '/godreply_', $page->show());
        $this->assign('page',$page_str);
        $p = '';
        if(isset($_GET['p']) && (int)$_GET['p'] > 0) {
            $p = '第'.$_GET['p'].'页';
        }
        $this->getseo('godreply',$p);
        $this->display();
    }

    public function rss() {
        echo '<?xml version="1.0" encoding="utf-8"?>
                <rss version="2.0">
                    <channel>
                        <title>'.$this->setting['site_name'].'</title>
                        <link>'.$this->setting['site_domain'].'</link>
                        <description>'.$this->setting['site_description'].'</description>
                        <language>zh_cn</language>
                        <generator>'.$this->setting['site_name'].'</generator>
                        <webmaster>50042748@qq.com</webmaster> ';

        $where = 'status='.C('JOKE_STATUS_AUDIT');
        $user_joke = $this->user_joke_mod->where($where)->limit('0,500')->order('audit_time desc,id desc')->select();
        foreach ($user_joke as $key => $value) {
            $type = '';
            if($value['type'] == C('JOKE_TYPE_TEXT')) {
                $type = '段子';
            }
            if($value['type'] == C('JOKE_TYPE_PIC')) {
                $type = '趣图';
            }
            if($value['type'] == C('JOKE_TYPE_GIF')) {
                $type = '动图';
            }
            if($value['type'] == C('JOKE_TYPE_VEDIO')) {
                $type = '视屏';
            }

            echo '  <item>
                        <title><![CDATA['.$value['title'].']]></title>
                        <link>'.$this->setting['site_domain'].'/xiaohua/'.$value['id'].'.html</link>
                        <category>'.$type.'</category>
                        <pubdate>'.date('D, d M Y H:i:s T',$value['audit_time']).'</pubdate>
                        <description><![CDATA['.$value['title'].']]></description>
                    </item>';
        }

        echo '        </channel>
                </rss>';
    }

}