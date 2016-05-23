<?php
function isAndroid(){
	if(strstr($_SERVER['HTTP_USER_AGENT'],'Android')) {
		return 1;
	}
	return 0;
}
//删除商品图片和目录可以是数组或者文件
function delDirFile($path,$arr){
    if(is_array($arr)){
        foreach($arr as $v){
            $delPath = $path.'/'.$v;
            $allFile = scandir($delPath);
            foreach($allFile as $val){
                if($val != '.' || $val != '..'){
                    $delfile = $delPath.'/'.$val;
                    unlink($delfile);
                }   
            }
            rmdir($delPath);
        }   
    }else{
        $delfile = $path.'/'.$arr;
        unlink($delfile);
    }
}
//清除api缓存
function delCache($dir){	//删除目录
	    $handle=@opendir($dir);
	    while ($file = @readdir($handle)) {
	        $bdir=$dir.'/'.$file;
	        if (filetype($bdir)=='dir') {
	            if($file!='.' && $file!='..')
	            delCache($bdir);
	        } else {
	            @unlink($bdir);
	        }
	    }
	    closedir($handle);
	    @rmdir($dir);		
		return true;
}
//清除所有缓存新方法
function deleteCacheData($dir){
		$fileArr	=	file_list($dir);		
	 	foreach($fileArr as $file)
	 	{
	 		if(strstr($file,"Logs")==false)
	 		{	 			
	 			@unlink($file);	 			
	 		}
	 	}
	 
	 	$fileList	=	array();
	}
function file_list($path)
{
 	global $fileList;
 	if ($handle = opendir($path)) 
 	{
 		while (false !== ($file = readdir($handle))) 
 		{
 			if ($file != "." && $file != "..") 
 			{
 				if (is_dir($path."/".$file)) 
 				{ 					
 						
 					file_list($path."/".$file);
 				} 
 				else 
 				{
 						//echo $path."/".$file."<br>";
 					$fileList[]	=	$path."/".$file;
 				}
 			}
 		}
 	}
 	return $fileList;
}
?>