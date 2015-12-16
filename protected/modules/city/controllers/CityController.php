<?php

class CityController extends CommonController
{

public function actionFile($id){

        $id   = (int)$id;
        $model = City::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

       // if(Yii::app()->user->isGuest && $model->issue->is_public !== true)
       //     Yii::app()->user->loginRequired();

        $available_mime = Yii::app()->params['mime_fileview'];

        $filename = $model->filename;
        $realname = $model->realname;

        $uploadPath = $model->getFileFolder();    

        if(file_exists($uploadPath.$filename )) {

            $type = CFileHelper::getMimeType($uploadPath.$filename); // get yii framework mime
            
            if(in_array($type, $available_mime)){

                //.. get the content of the requested file 
                $content=file_get_contents($uploadPath.$filename);
                //.. send appropriate headers
                header('Content-Type:' . $type);
                header("Content-Length: ". filesize($uploadPath.$filename));

                header('Content-Disposition: inline; filename="' . $realname . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');

                echo $content;
                exit; 
                
            } else {
                throw new CHttpException(404, Yii::t('site','Page not found'));
            }
        }
        else{
            throw new CHttpException(404, Yii::t('site','Page not found'));
        }   

    }

}