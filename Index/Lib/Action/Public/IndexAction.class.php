<?php
class IndexAction extends BaseAction{

	public function index(){ 
        $this->_empty();
    }
	/**
	* 上传文件
	*/
	public function uploadify() {
		
		$targetFolder = UPLOAD_PATH.'avatar/';
		//print_r($_FILES);
		if (!empty($_FILES)) {
			$url = $this->_upload($targetFolder,false);
			$img_info = getimagesize($url['image']);
			$width = $img_info[0];
			$height = $img_info[1];

			$this->ajaxReturn(array('status' => 1,'info' => '上传成功','url' => $url['image'],'width' => $width, 'height' => $height));
		}
	}

	/**
	* 上传文件
	*/
	public function uploadfile() {
		$targetFolder = UPLOAD_PATH.'joke/'.date('Y/m/d',time()).'/';
		if(!is_dir($targetFolder)) {
			mkdir($targetFolder,0777,true);
		}

		if (!empty($_FILES)) {
			$url = $this->_upload($targetFolder);
			$this->ajaxReturn(array('status' => 1,'info' => '上传成功','url' => $url['image'], 'm_url' => $url['m_image']));
		}
	}

	//验证码
    public function verify(){
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }

    // 文件上传 
    private function _upload($savePath,$is_thumb = true) {
		$fileParts = pathinfo($_FILES['Filedata']['name']);
		$extension = $fileParts['extension'];

        import("ORG.Net.UploadFile"); 
        //导入上传类 
        $upload = new UploadFile(); 
        //设置上传文件大小 
        $upload->maxSize = 5120 * 5120; 
        //设置上传文件类型 
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg'); 
        //设置附件上传目录 
        $upload->savePath = $savePath; 
        if($is_thumb) {
	        //1 按设置大小截取 0 按原图等比例缩略
	        $upload->thumbType = 0;
	        //设置需要生成缩略图，仅对图像文件有效 
	        $upload->thumb = true; 
	        // 设置引用图片类库包路径 
	        $upload->imageClassPath = 'ORG.Util.Image'; 
	        //设置需要生成缩略图的文件后缀 
	        $upload->thumbPrefix = 'm_';  //生产2张缩略图 
	        //设置缩略图最大宽度 
	        $upload->thumbMaxWidth = '540'; 
	        //设置缩略图最大高度 
	        $upload->thumbMaxHeight = '10000'; 

			if($extension != 'gif' && $extension != 'GIF') {
				//删除原图 
        		$upload->thumbRemoveOrigin = true; 
			}
	    }
        //设置上传文件规则 
        $upload->saveRule = uniqid; 
       
        if (!$upload->upload()) { 
            //捕获上传异常 
            $this->error($upload->getErrorMsg()); 
        } else { 
            //取得成功上传的文件信息 
            $uploadList = $upload->getUploadFileInfo(); 
            import("ORG.Util.Image");
            
            $image = $uploadList[0]['savepath'] . $uploadList[0]['savename']; 
           	//缩略图路径
           	if($is_thumb) {
	            $m_image = $uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename']; 
	            
	            if($extension != 'gif' && $extension != 'GIF') {
	            	//给m_缩略图添加水印, Image::water('原文件名','水印图片地址') 
            		Image::water($m_image, $this->setting['site_short_domain']); 
					$image = $m_image;
				}
	        }
        } 
      	return array('image' => $image, 'm_image' => $m_image);
    } 
}