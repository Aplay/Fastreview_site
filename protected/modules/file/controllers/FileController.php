<?php

class FileController extends Controller {

	public $uploadsession = 'objectsfiles';
	
	public function actions()
    {
        return array(
            
            'upload' => 'application.modules.file.controllers.actions.Upload',
            'unlink' => 'application.modules.file.controllers.actions.Unlink',
            'deleteobjectsfile' => 'application.modules.file.controllers.actions.Deleteobjectsfile',
            'uploadarticle' => 'application.modules.file.controllers.actions.Uploadarticle',
        );
    }

	/* Общий метод для получения картинки логотипа */
	public function actionLogotip($id,$model='Objects',$filename='filename',$realname=false){

        $id   = (int)$id;
        $model = $model::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

        $available_mime = Yii::app()->params['mime_fileview'];

        $filename = $model->$filename;

        $uploadPath = $model->getFileFolder();    

        if(file_exists($uploadPath.$filename )) {

            $type = CFileHelper::getMimeType($uploadPath.$filename); // get yii framework mime
            
            if(in_array($type, $available_mime)){

                //.. get the content of the requested file 
                $content=file_get_contents($uploadPath.$filename);
                //.. send appropriate headers
                header('Content-Type:' . $type);
                header("Content-Length: ". filesize($uploadPath.$filename));
                if($realname){
                	$realname =  $model->$realname;
                	header('Content-Disposition: inline; filename="' . $realname . '"');
                } else {
                	header('Content-Disposition: inline; filename="' . $filename . '"');
            	}
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



    /* Общий метод для получения данных о картинке логотипа */
     public function actionLogotipFile($id=null,$model='Objects',$filename='filename',$realname=false){
        $result = array();
        $id   = (int)$id;
        $model = $model::model()->findByPk($id); 
        if(!$model){
        	echo '[]';
            Yii::app()->end();
            throw new CHttpException(404, Yii::t('site','Page not found'));
        }
            
        $uploadPath = $model->getFileFolder();

        if($model->$filename){

            $obj['id'] = $model->id; //get the filename in array
            if($realname){
            	$obj['name'] = $model->$realname;
            } else {
            	$obj['name'] = $model->$filename;
        	}
            if(file_exists($uploadPath.$model->$filename)){
            	$obj['size'] = filesize($uploadPath.$model->$filename); //get the flesize in array
            } else {
            	$obj['size'] = '0';
            }
            $result[] = $obj; // copy it to another array
           
       } 
       header('Content-Type: application/json');
       echo CJSON::encode($result);
       Yii::app()->end();
   }

  

   public function actionObjectsFiles($id){
        $id   = (int)$id;
        $model = Objects::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));
        $uploadPath = $model->getFileFolder();
        if($model->images){
        foreach($model->images as $file){ //get an array which has the names of all the files and loop through it 
            $obj['id'] = $file->id; //get the filename in array
            $obj['name'] = $file->realname;
            if(file_exists($uploadPath.$file->filename)){
            	$obj['size'] = filesize($uploadPath.$file->filename); //get the flesize in array
            } else {
            	$obj['size'] = '0';
            }
            $result[] = $obj; // copy it to another array
          }
           header('Content-Type: application/json');
           echo json_encode($result); // now you have a json response which you can use in client side 
       }
   }

   public function actionObject($id){

        $id   = (int)$id;
        $model = ObjectsImages::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

       // if(Yii::app()->user->isGuest && $model->issue->is_public !== true)
       //     Yii::app()->user->loginRequired();

        $available_mime = Yii::app()->params['mime_fileview'];

        $filename = $model->filename;
        $realname = $model->realname;

        $uploadPath = $model->objectid->getFileFolder();    

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
