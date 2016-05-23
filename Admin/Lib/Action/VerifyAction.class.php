<?php
class VerifyAction extends Action{
	//验证码
    public function verify(){
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }
}
?>