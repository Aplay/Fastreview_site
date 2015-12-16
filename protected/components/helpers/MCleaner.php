<?php

class MCleaner 
{
    public static function cleanTempImg($image, $dir = null, $addImages = array(), $sessionName = 'images')
    {
        $sizes = array('60','60x60','100x75','100x100','120x100','176','170x170','188x188','190x150','216','220','220x114','232','264','284','392','465','960','1300');
        $a = array();

        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['tmp']).'/'.$image;
        foreach($sizes as $size){
            $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/'.$size.'/'.$image;
        }
        if($dir){
            foreach($sizes as $size){
                $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/'.$dir.'/'.$size.'/'.$image;
            }
        }
        if(!empty($addImages)){
            foreach($addImages as $im){
                $a[] = $im;
            }
        }
        foreach($a as $v){
            if(file_exists($v))
                unlink($v);
        }

        if(Yii::app()->session->itemAt($sessionName)){
            $images = Yii::app()->session->itemAt($sessionName);
            if(is_array($images)){
                $mm = $images;
                foreach($mm as $k=>$im){
                    if($im == $image){
                        unset($images[$k]);
                    }
                }

                Yii::app()->session->add($sessionName,$images);
            } else {
                if($images == $image){
                    Yii::app()->session->remove($sessionName);
                }
            }
        }        
    }
    public static function cleanAllTempImageFolders()
    {
        $a = array();

        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/60/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/60x60/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/100x75/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/100x100/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/170/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/170x170/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/190x150/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/216/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/220/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/232/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/264/';
        $a[] = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/465/';

        foreach($a as $v){
            if(file_exists($v)){
                self::deleteDir($v);
                  //  unlink($v);
            }

        }
    }
    public static function showAllTempImageFolders()
    {
        $tempdir =  Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']);
        self::show_dir($tempdir);
    }
    public static function checkUsersFolders()
    {
        $tempdir =  Yii::getPathOfAlias(Yii::app()->params['storeImages']['users']);
        self::chmod_R($tempdir, 0666, 0775);
    }
    public static function setAllTempImageFolders($path = null)
    {
        if(!empty($path)) {
            $a = false;
            $arr = Yii::app()->params['storeImages'];
            foreach($arr as $params => $value){
                if($params == $path){
                    $a = true;
                    break;
                }
            }
            if($a === true){
                $param = Yii::app()->params['storeImages'][$path];
                if(isset($param) && !empty($param)){
                    $tempdir =  Yii::getPathOfAlias($param);
                }
            } else {
                echo 'error, wrong param';
                return false;

            }
        } else {
            $tempdir =  Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']);
        }
             MPath::setPermissions($tempdir,null,Yii::app()->params['storeImages']['dirMode']); // without octdec becose it will set in MPath
         }
         public static function deleteDir($dirPath, $selfremove = true) {
            if (! is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            if($selfremove)
                rmdir($dirPath);
            
            return 0;
        }
        public static function show_dir($dir) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir),
              RecursiveIteratorIterator::CHILD_FIRST);
            
            foreach ($iterator as $path) {

                echo str_repeat('---', $iterator->getDepth()).$path;
                if($path->isDir()){
                    clearstatcache(null,$path);
                    $perm = decoct(fileperms($path) & 0777);
                    if ( ($path->getFilename() !== "..") ) {
                        if(0775 !== $perm){
                            echo '<span style="color:red">'.$perm.'</span>';
                        }
                   // @chmod($path . "/" . $file, $perm);
                   // if ( !is_file($path."/".$file) && ($file !== ".") )
                   //   chmod_R($path . "/" . $file, $perm);
                    }
                    
                }
                echo '<br>';
            /*  if ($path->isDir()) {
                 rmdir($path->__toString());
              } else {
                 unlink($path->__toString());
             }*/
         }
        //    rmdir($dir);
     }
     public static function user_dir($dir) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir),
          RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $path) {

            echo str_repeat('---', $iterator->getDepth()).$path;
            if($path->isDir()){
                clearstatcache(null,$path);
                $perm = decoct(fileperms($path) & 0775);
                if ( ($path->getFilename() !== "..") ) {
                      //  if(0775 !== $perm){
                    echo $path;
                    echo '<span style="color:red"> '.$perm.'</span>';
                        //chmod($path, 0775);  
                    $perm = decoct(fileperms($path) & 0775);
                    echo '<span style="color:green"> '.$perm.'</span>';

                }



            }
            echo '<br>';
            /*  if ($path->isDir()) {
                 rmdir($path->__toString());
              } else {
                 unlink($path->__toString());
             }*/
         }
        //    rmdir($dir);
     }

        // use chmod_R( 'mydir', 0666, 0777); 
     public static function chmod_R($path, $filemode, $dirmode) { 
        if (is_dir($path) ) { 
            if (!chmod($path, $dirmode)) { 
                $dirmode_str=decoct($dirmode); 
                print "Failed applying filemode '$dirmode_str' on directory '$path'\n"; 
                print "  `-> the directory '$path' will be skipped from recursive chmod\n"; 
                return; 
            } 
            $dh = opendir($path); 
            while (($file = readdir($dh)) !== false) { 
            if($file != '.' && $file != '..') {  // skip self and parent pointing directories 
                $fullpath = $path.'/'.$file; 
                self::chmod_R($fullpath, $filemode,$dirmode); 
            } 
        } 
        closedir($dh); 
    } else { 
        if (is_link($path)) { 
            print "link '$path' is skipped\n"; 
            return; 
        } 
        if (!chmod($path, $filemode)) { 
            $filemode_str=decoct($filemode); 
            print "Failed applying filemode '$filemode_str' on file '$path'\n"; 
            return; 
        } 
    } 
} 
}
