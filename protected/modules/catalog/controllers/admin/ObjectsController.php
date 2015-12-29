<?php
class ObjectsController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'objects';
    public $uploadsession = 'objectsfiles';
    public $pageTitle = 'Объекты';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, statuslocator, updatestatus,  deletefile, deletelogofile, uploadlogo, unlinklogo, autocompletetitle,  autocompleteaddress, autocompleteloguser, autocompletecity, autocompletestreet, autocompletedom, autocompleterubrics',
        );
    }

   

    public function actionIndex() {

        $this->pageTitle = 'Объекты';
        $this->active_link = 'objects';

        $model = new Objects('search');
        $model->unsetAttributes();  // clear any default values

        if(!empty($_GET['Objects']))
            $model->attributes = $_GET['Objects'];

        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = true';

        $dataProvider = $model->search(array(),$additionalCriteria);

        $this->render('index',array(
            'model'=>$model, 'dataProvider'=>$dataProvider
        ));
    }

    public function actionNew_Objects() {

        $this->pageTitle = 'Новые объекты';
        $this->active_link = 'new_objects';

        $model = new Objects('search');
        $model->unsetAttributes();  // clear any default values

        if(!empty($_GET['Objects']))
            $model->attributes = $_GET['Objects'];

        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = false';

        $dataProvider = $model->search(array(),$additionalCriteria);
         
        $this->render('index',array(
            'model'=>$model, 'dataProvider'=>$dataProvider
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($new = false)
    {
        if ($new === true){
            $model = new Objects;
        } else {
            $id = (int)$_GET['id'];
            $model=$this->loadModel($id);
            if($model->verified == true){
                $this->pageTitle = 'Объекты';
                $this->active_link = 'objects';
            } else if($model->verified == false){
                $this->pageTitle = 'Новые объекты';
                $this->active_link = 'new_objects';
            } 
            $old_mesto = $model->address;
        }

        // Uncomment the following line if AJAX validation is needed
         $this->performAjaxValidation($model, 'objects-form');

        if(isset($_POST['Objects']))
        {

            $model->attributes=$_POST['Objects'];

            if($new ||  (!empty($old_mesto) && $old_mesto != $model->address)){
                $words = explode(',',$model->address,2);

                if(!empty($words)){
                  $city = trim($words[0]);
                  $trueCity = City::model()->find('LOWER(title)=:title or LOWER(alternative_title)=:title',array(':title'=>MHelper::String()->toLower($city)));
                  if(!$trueCity)
                    $trueCity = City::addNewCity($city);
                  if($trueCity)
                    $model->city_id = $trueCity->id;
                }
                if(isset($words[1])){
                    $words = explode(',',$words[1],2);
                    if(isset($words[0]) && !empty($words[0])){
                        $model->street = trim($words[0]);
                    }
                    if(isset($words[1]) && !empty($words[1])){
                        $model->dom = trim($words[1]);
                    }
                }
                
              }


            if($model->save()){
                
                $model->setHttp($model->video, array(), false, ObjectsHttp::TYPE_VIDEO);

                if(isset(Yii::app()->session['deleteObjectsFiles']))
                {
                    $sessAr = unserialize(Yii::app()->session['deleteObjectsFiles']);
                    if(isset($sessAr['id']) && $sessAr['id'] == $model->id && isset($sessAr['files']) && is_array($sessAr['files']))
                  {
                     $files = $model->images;
                     if($files)
                     {
                      foreach ($files as $file) {
                        if(in_array($file->id,$sessAr['files'])){
                          $file->delete();
                        }
                      }
                     }
                  }
                }
              $model->addDropboxFiles($this->uploadsession);
              Yii::app()->session->remove($this->uploadsession);
              if(isset(Yii::app()->session['deleteObjectsFiles']))
            unset(Yii::app()->session['deleteObjectsFiles']);
                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = $new? "Объект {$model->title} добавлен" : "Объект {$model->title} отредактирован";
                    $this->addFlashMessage($text,'success');
                    if($model->verified == true) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/objects'));
                    } else if($model->verified == false){
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/objects/new_objects'));
                    } 

                }

            } else {
                $this->addFlashMessage($model->errors,'error');
                $this->refresh();
            }
        }

        $categories_ar[] = $model->categorie;
        $video = $model->objectsVideo;

        $this->render('update',array(
            'model'=>$model,
            'categories_ar'=>$categories_ar,
            'video'=>$video,
        ));
    }



    public function actionUploadLogo(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadlogosession);
        $uploader->inputName = 'logotip';
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }

    public function actionUnlinkLogo(){

        $fileName = $_POST['name'];

        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(Yii::app()->session->itemAt($this->uploadlogosession)){
            $datas = Yii::app()->session->itemAt($this->uploadlogosession);
            if(is_array($datas)){
                $mm = $datas;
                foreach($mm as $key => $value){
                    if($fileName == $key){
                        if(file_exists($folder.$value )) {
                            unlink($folder.$value);
                            unset($datas[$key]);
                        }
                    break;
                    }
                }
                Yii::app()->session->add($this->uploadlogosession,$datas);
            }

        }

    }
 

    public function actionUpdatestatus($id, $status)
    {
        $id = (int)$id;
        $model = Objects::model()->findByPk($id);

        if (!empty($model) && ($status == Objects::STATUS_ACTIVE || $status == Objects::STATUS_NOT_ACTIVE))
        {
           $model->status = $status;

           if($model->save(false,'status')){
                
           }
        }
        echo '[]';
    }


    public function actionDelete($id)
    {
        $id = (int)$id;
        $model = Objects::model()->findByPk($id);

        if ($model)
        {
            $model->delete();
        }
        echo '[]';
    }

    public function actionDeleteFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = ObjectsImages::model()->findByPk($id);
        if(isset($file->objectid)){
            // remove it from disk also in model issueFile
            if($file->delete()) {
                echo "[]";
                Yii::app()->end();
            }
        }
        echo 'error';
        Yii::app()->end();
    }


   
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return City the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Objects::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param City $model the model to be validated
     */
    protected function performAjaxValidation($model, $form)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='city-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

 
}