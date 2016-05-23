<?php
class IndexAction extends BaseAction{

	private $shop_cate_mod;
	private $shop_gift_mod;
	//初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->shop_cate_mod = D('shop_cate');
        $this->shop_gift_mod = D('shop_gift');

        //最新用户兑换
 		$user_gift = $this->get_new_exchange();
 		$this->assign('user_gift',$user_gift); 
       
    }

    //礼品列表
    public function index() {
    	$where = 'status=1';
        $cate_id = 0;
    	if(isset($_GET['cate_id']) && (int)$_GET['cate_id'] > 0) {
    		$cate_id = (int)$_GET['cate_id'];
	    	$where = 'cate_id='.$cate_id;
    	}
    	$count = $this->shop_gift_mod->where($where)->count();
		import("ORG.Util.Page");
		$page = new Page($count,12);

    	$gift = $this->shop_gift_mod->where($where)->limit($page->firstRow.','.$page->listRows)->select();
    	$this->assign('gift',$gift);
    	
        $page_str = str_replace('/Shop/Index/index', '', $page->show());
       
        if($cate_id) {
            $page_str = str_replace('/p/', '', $page_str);
            
            $page_str = str_replace('/cate_id/'.$cate_id.'.html', '.html?cate_id='.$cate_id, $page_str);
           
            $page_str = str_replace("href='", "href='/shop/index_", $page_str);    
        }else {
           
            $page_str = str_replace('/p/', '/shop/index_', $page_str);    
        }
        
        
        $this->assign('page',$page_str);
       
    	//分类
    	$cate = $this->shop_cate_mod->order('sort asc')->select();
    	$this->assign('cate',$cate);
       
       	$this->getseo('shop');
        $this->display();
    }

    //礼品详情页
    public function detail() {
    	if(isset($_GET['id']) && (int)$_GET['id'] > 0) {
    		$id = (int)$_GET['id'];
	    	$gift = $this->shop_gift_mod->where('id='.$id)->find();
	    	$this->assign('gift',$gift);
	    	$this->assign('title',$gift['title']);
	    	$this->display();
    	}else {
    		$this->msg('错误提示','非法访问！');
    	}
    }

    //礼品兑换页
    public function exchange() {
    	if(isset($_GET['id']) && (int)$_GET['id'] > 0) {
    		$id = (int)$_GET['id'];
	    	$gift = $this->shop_gift_mod->where('id='.$id)->find();
	    	$this->assign('gift',$gift);
	    	$this->assign('title','礼品兑换');
	    	$this->display();
    	}else {
    		$this->msg('错误提示','非法访问！');
    	}
    }

    //礼品下单
    public function order() {
    	if($this->isAjax()) { 
    		$gift = $this->shop_gift_mod->where('id='.(int)$_POST['gift_id'])->find();
    		$amount = $gift['price'] * (int)$_POST['num'];
    		if($this->user['money'] >= $amount) {
    			$user_gift_mod = D('user_gift');
	    		$user_gift = $user_gift_mod->create();
	    		$user_gift['order_id'] = time().$this->rand_six_num();
	    		$user_gift['created_time'] = time();
	    		$user_gift['amount'] = $amount;
	    		$user_gift['user_id'] = $this->user['id'];
	    		
	    		$gift_info = array('id' => $gift['id'], 'title' => $gift['title'], 'image' => $gift['image'], 'price' => $gift['price']);
	    		$user_gift['gift_info'] = json_encode($gift_info);
	    		//扣除囧币和保存
	    		if($this->reduce_money($amount) && $user_gift_mod->where('id='.$this->user['id'])->add($user_gift)) {
					//增加消费记录
					$params = array('user_id' => $this->user['id'],
									'value' => $amount,
									'type' => C('TRACE_STATUS_COST'),
									'content' => '兑换 '.$gift['title'].' 礼品，花了 '.$amount.' 囧币',
									'created_time' => time());
					D('user_trace')->add($params);

					$this->ajaxReturn(array('err' => 1,'msg' => '兑换成功!'));
				}
    		}
    		$this->ajaxReturn(array('err' => 0,'msg' => '当前囧币不够!'));
    	}
    }

    private function get_new_exchange() {
    	$user_gift_mod = D('user_gift');
    	$user_gift = $user_gift_mod->order('id desc')->limit('0,6')->select();
        foreach ($user_gift as $key => $value) {
            $user_gift[$key]['gift_info'] = json_decode($value['gift_info'],true);
            $user = D('user')->where('id='.$value['user_id'])->find();
            $user_gift[$key]['user_info'] = $user;
        }
    	return $user_gift;
    }
}