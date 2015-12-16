<?php

Yii::import("ext.MyAcrop.qqFileUploader");

class EAjaxUploadAction extends CAction
{

        public function run()
        {
                
                $folder = 'uploads/tmp';
                $allowedExtensions = array("jpg","jpeg","gif","png");
                // max file size in bytes
                $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
                $sess = 'images';
                $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $sess);
                $result = $uploader->handleUpload($folder);
                // to pass data through iframe you will need to encode all html tags
                $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
                echo $result;
        }
}
