<?php

class FileController extends Controller {

	public $uploadsession = 'orgsfiles';
	
	public function actions()
    {
        return array(
            
            'upload' => 'application.modules.file.controllers.actions.Upload',
            'unlink' => 'application.modules.file.controllers.actions.Unlink',
            'deletefile' => 'application.modules.file.controllers.actions.Deletefile',

        );
    }

	/* Общий метод для получения картинки логотипа */
	public function actionLogotip($id,$model='Wherefind',$filename='filename',$realname=false){

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

    public function actionWherefind($id){

        $id   = (int)$id;
        $model = WherefindImages::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

       // if(Yii::app()->user->isGuest && $model->issue->is_public !== true)
       //     Yii::app()->user->loginRequired();

        $available_mime = Yii::app()->params['mime_fileview'];

        $filename = $model->filename;
        $realname = $model->realname;

        $uploadPath = $model->organization->getFileFolder();    

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
    public function actionWherefindImage($id){

        $id   = (int)$id;
        $model = WherefindImages::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

       // if(Yii::app()->user->isGuest && $model->issue->is_public !== true)
       //     Yii::app()->user->loginRequired();

        $available_mime = Yii::app()->params['mime_fileview'];

        $filename = $model->filename;
        $realname = $model->realname;

        $uploadPath = $model->organization->getFileFolder();    
        $thumbPath = $uploadPath.'160x160_'.$filename;
        if(file_exists($uploadPath.$filename) && !file_exists($thumbPath)) {
        	
        	Yii::import('ext.phpthumb.PhpThumbFactory');
			$thumb  = PhpThumbFactory::create($uploadPath.$filename);
			$thumb->setOptions(array('jpegQuality'=>100, 'resizeUp'=>true));
			$thumb->adaptiveResize(160,160)->save($thumbPath);
        }
        if(file_exists($thumbPath)) {

            $type = CFileHelper::getMimeType($thumbPath); // get yii framework mime
            
            if(in_array($type, $available_mime)){

                //.. get the content of the requested file 
                $content=file_get_contents($thumbPath);
                //.. send appropriate headers
                header('Content-Type:' . $type);
                header("Content-Length: ". filesize($thumbPath));

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

    /* Общий метод для получения данных о картинке логотипа */
     public function actionLogotipFile($id=null,$model='Wherefind',$filename='filename',$realname=false){
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

    public function actionWherefindFiles($id){
        $result = array();
        $id   = (int)$id;
        $model = Wherefind::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));
        $uploadPath = $model->getFileFolder();
        if($model->images){
        foreach($model->images as $file){ 
            $fileEx = $uploadPath.$file->filename;
           
                $obj['id'] = $file->id; //get the filename in array
                $obj['name'] = $file->realname;
                if(file_exists($fileEx)){
                    $obj['size'] = filesize($fileEx); //get the flesize in array
                } else {
                    $obj['size'] = '0';
                }
                $result[] = $obj; // copy it to another array
            
          }

           
       }
       if(!empty($result))
       		usort($result, MHelper::get('Array')->sortFunction('id'));
       header('Content-Type: application/json');
       echo CJSON::encode($result);
       Yii::app()->end();
   }
   
   

   public function actionCompanyFiles($id){
        $id   = (int)$id;
        $model = Orgs::model()->findByPk($id); 
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

   public function actionCompany($id){

        $id   = (int)$id;
        $model = OrgsImages::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

       // if(Yii::app()->user->isGuest && $model->issue->is_public !== true)
       //     Yii::app()->user->loginRequired();

        $available_mime = Yii::app()->params['mime_fileview'];

        $filename = $model->filename;
        $realname = $model->realname;

        $uploadPath = $model->organization->getFileFolder();    

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

    public function actionAnyFileData($id,$model=null,$attr='logotip'){
        $id   = (int)$id;
        if(!$model)
            $model = 'Orgs';
        $realmodel = array('Orgs','Jobspec');
        if(!in_array($model, $realmodel))
            throw new CHttpException(404, Yii::t('site','Page not found'));
        $model = $model::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));
        $uploadPath = $model->getFileFolder();

        if($model->$attr){

            $obj['id'] = $model->id; //get the filename in array
            $obj['name'] = $model->$attr;
            if(file_exists($uploadPath.$model->$attr)){
            	$obj['size'] = filesize($uploadPath.$model->$attr); //get the flesize in array
            } else {
            	$obj['size'] = '0';
            }
             $result[] = $obj; // copy it to another array
           header('Content-Type: application/json');
           echo json_encode($result); // now you have a json response which you can use in client side 
       }
   }
}
