<?php

class CacheAction extends BaseAction {

    public function _initialize() {
        parent::_initialize();
    }

    function index() {
        $this->display();
    }
    function clearCache(){
    	set_time_limit(0);
        $i = intval($_REQUEST['id']);
        if (!$i) {
            $this->error('操作失败');
        } else {
            import("ORG.Io.Dir");
            $dir = new Dir;
            switch ($i) {
                case 1:
                    //更新全站缓存
                    is_dir(CACHE_PATH) && $dir->del(CACHE_PATH);
                    is_dir(DATA_PATH . '_fields/') && $dir->del(DATA_PATH . '_fields/');
                    is_dir(TEMP_PATH) && $dir->del(TEMP_PATH);                  
                    if(is_dir("./Index/Runtime")){
                    	deleteCacheData("./Index/Runtime"); 
                    }
                    if(is_dir("./Wap/Runtime")){
                        deleteCacheData("./Wap/Runtime"); 
                    }
          			if(is_dir("./Antidisestablishmentarianism/runtime")){
                    	deleteCacheData("./Antidisestablishmentarianism/runtime"); 
                    }
                    //Api缓存
                    is_dir('./Apicache/') && delCache('./Apicache/');   
                    break;
                case 2:
                    //后台模版缓存
                    is_dir(CACHE_PATH) && $dir->del(CACHE_PATH);
                    break;
                case 3:
                    //前台模版缓存
                    is_dir("./Index/Runtime/Cache/") && $dir->del("./Index/Runtime/Cache/");
                    is_dir("./Wap/Runtime/Cache/") && $dir->del("./Wap/Runtime/Cache/");
                    is_dir("./Index/Html/") && $dir->del("./Index/Html/");
                    break;
                case 4:
                    //数据库缓存
                    is_dir(DATA_PATH . '_fields/') && $dir->del(DATA_PATH . '_fields/');
                    is_dir("./Index/Runtime/Data/_fields/") && $dir->del("./Index/Runtime/Data/_fields/");
                    break;
                case 5:
                    //Api缓存
                    is_dir('./Apicache/') && delCache('./Apicache/');   
                    break;
                default:break;
            }
			$runtime = defined('MODE_NAME') ? '~' . strtolower(MODE_NAME) . '_runtime.php' : '~runtime.php';
			$runtime_file_admin = RUNTIME_PATH . $runtime;
			$runtime_file_front = ROOT_PATH . '/Index/Runtime/' . $runtime;
			is_file($runtime_file_admin) && @unlink($runtime_file_admin);
			is_file($runtime_file_front) && @unlink($runtime_file_front);
            $this->success('更新完成', U('cache/index'));
        }
    }

}

?>