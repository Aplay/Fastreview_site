<?php

class Uploadarticlefiles extends CAction {

    public function run() {

        $controller = $this->getController();


         // get the Model Name
    	// $model_class = ucfirst($controller->getId());
 
    	// create the Model
    	// $model = new $model_class();
    	Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, 'articlefiles');
        $uploader->inputName = 'tmpFiles';
        $result = $uploader->handleUpload($folder);

        $datasession = Yii::app()->session->itemAt('articlefiles');

        if(!empty($datasession)){
                end($datasession);
                $key = key($datasession);
                $result['tmpFile'] = $datasession[$key];

                $tmpFile = Yii::getPathOfAlias('webroot').'/uploads/tmp/'.$result['tmpFile'];

        if(file_exists($tmpFile)) {

            $thumbTo = array(720,400);
            $folder = Yii::getPathOfAlias('webroot').'/uploads/tmp/';
            $uploadDirectoryUpload = rtrim($folder,'/');
            $check = MHelper::File()->getUniqueTargetPath($uploadDirectoryUpload, $result['tmpFile']);
            $target = $uploadDirectoryUpload.'/'.$check;
                
                Yii::import('ext.phpthumb.PhpThumbFactory');
                $thumb  = PhpThumbFactory::create($tmpFile);
                $sizes  = Yii::app()->params['storeImages']['sizes'];
               // $method = $sizes['resizeThumbMethod'];
               // $thumb->$method($thumbTo[0],$thumbTo[1])->save($target);
                $thumb->resize(1000)->save($target);

               if (copy($target, $tmpFile)){
                    unlink($target); //delete tmp file
               }
           }
       }
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;

    }
}

?>