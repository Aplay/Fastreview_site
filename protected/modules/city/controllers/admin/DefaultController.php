<?php

class DefaultController extends SAdminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/admin';
	public $active_link = 'city';
	public $uploadsession = 'cityfiles';
	public $pageTitle = 'Города';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'ajaxOnly + delete, upload, unlink, deletefile, autocompletetitle, autocompleteurl'
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	/*public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}*/

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		

		$model = new City('search');
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['City']))
			$model->attributes=$_GET['City'];

		$this->render('index',array(
			'model'=>$model,
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new City;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model,'city-form');

		if(isset($_POST['City']))
		{
			$model->attributes=$_POST['City'];
			if($model->save()){
				$model->addDropboxFiles($this->uploadsession);
                Yii::app()->session->remove($this->uploadsession);
                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = "Город {$model->title} успешно добавлен";
                    $this->addFlashMessage($text,'success');
                    $this->redirect(Yii::app()->createAbsoluteUrl('city/admin/default'));
                }
			} else {

                $this->addFlashMessage($model->errors,'error');
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model, 'city-form');

		if(isset($_POST['City']))
		{
			$model->attributes=$_POST['City'];
			if($model->save()){
				$model->addDropboxFiles($this->uploadsession);
                Yii::app()->session->remove($this->uploadsession);
                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = "Город {$model->title} отредактирован";
                    $this->addFlashMessage($text,'success');
                    $this->redirect(Yii::app()->createAbsoluteUrl('city/admin/default'));
                }

			} else {
				$this->addFlashMessage($model->errors,'error');
			}
		}

		$sql = 'SELECT 
			COUNT(CASE WHEN oco.status_org=1 THEN 1 END) AS orgs_count,
			COUNT(CASE WHEN oco.status_org=2 THEN 1 END) AS orgs_count_notactive 
			FROM (SELECT DISTINCT org, status_org, city_id FROM orgs_category WHERE city_id='.$model->id.') oco';
		$connection=Yii::app()->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryRow();
		if($result && isset($result['orgs_count'])){
			$model->orgs_count = $result['orgs_count'];
		}
		if($result && isset($result['orgs_count_notactive'])){
			$model->orgs_count_notactive = $result['orgs_count_notactive'];
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// не удаляем, ибо родительские регионы могут быть
		// $this->loadModel($id)->delete();
		echo '[]';
		Yii::app()->end();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
	//	if(!isset($_GET['ajax']))
	//		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	public function actionUpload(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadsession);
        $uploader->inputName = 'tmpFiles';
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }

    public function actionUnlink(){

        $fileName = $_POST['name'];

        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(Yii::app()->session->itemAt($this->uploadsession)){
            $datas = Yii::app()->session->itemAt($this->uploadsession);
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
                Yii::app()->session->add($this->uploadsession,$datas);
            }

        }

    }
    public function actionAutoCompleteTitle($term)
    {

         if(isset($_GET['term'])) {
            $arr = array();
         
            $criteria = new CDbCriteria();
            $criteria->compare('LOWER(title)',MHelper::String()->toLower($_GET['term']),true);
            $criteria->limit = 50;
            $model = new City;
            foreach($model->findAll($criteria) as $m)
            {
                $arr[] = $m->title;
            }
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }

    public function actionAutoCompleteUrl($term)
    {

         if(isset($_GET['term'])) {
            $arr = array();
         
            $criteria = new CDbCriteria();
            $criteria->compare('LOWER(url)',MHelper::String()->toLower($_GET['term']),true);
            $criteria->limit = 50;
            $model = new City;
            foreach($model->findAll($criteria) as $m)
            {
                $arr[] = $m->url;
            }
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }

	public function actionDeleteFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = City::model()->findByPk($id);
        if(!empty($file)){
	       // Delete file
	        $filename = $file->filename;
	        $imagePath = $file->getFileFolder() . $filename;
	        if(file_exists($imagePath)) {
	            unlink($imagePath); //delete file
	        }
	        $file->filename = '';
	        $file->realname = '';
	        $file->save(false,array('filename','realname'));
	            echo "[]";
	            Yii::app()->end();
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
		$model=City::model()->findByPk($id);
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
