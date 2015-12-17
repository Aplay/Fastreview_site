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
        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = true';
        $model_search = $model->search(array(),$additionalCriteria);
        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
        ));
    }

    public function actionNew_Objects() {

        $this->pageTitle = 'Новые объекты';
        $this->active_link = 'new_objects';
        $model = new Objects('search');
        $model->unsetAttributes();  // clear any default values


        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = false';

        $model_search = $model->search(array(),$additionalCriteria);
         
        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
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
            if($model->verified == Objects::STATUS_VERIFIED){
                $this->pageTitle = 'Объекты';
                $this->active_link = 'objects';
            } else if($model->verified == Objects::STATUS_NOT_VERIFIED){
                $this->pageTitle = 'Новые объекты';
                $this->active_link = 'new_objects';
            } 
        }

        // Uncomment the following line if AJAX validation is needed
         $this->performAjaxValidation($model, 'objects-form');

        if(isset($_POST['Objects']))
        {

            $model->attributes=$_POST['Objects'];

            if($model->save()){
                

                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = $new? "Объект {$model->title} добавлен" : "Объект {$model->title} отредактирован";
                    $this->addFlashMessage($text,'success');
                    if($model->verified == Objects::STATUS_VERIFIED) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/objects'));
                    } else if($model->verified == Objects::STATUS_NOT_VERIFIED){
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/company/new_objects'));
                    } 

                }

            } else {
                $this->addFlashMessage($model->errors,'error');
                $this->refresh();
            }
        }


        $this->render('update',array(
            'model'=>$model,
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