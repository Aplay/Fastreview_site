<?php

class Upload extends CAction {

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
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $controller->uploadsession);
        $uploader->inputName = 'tmpFiles';
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }
}
?>