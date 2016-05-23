<?php
class EmptyAction extends Action{ 

    public function _empty(){ 
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
        $this->display('./Index/Tpl/Public/default/Index_404.html');
        exit;
    } 
 } 
?>