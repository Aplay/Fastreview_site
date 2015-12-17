<?php
class PollController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'poll';
    public $pageTitle = 'Голосование';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete',
        );
    }

    /**
	   * Manages all models.
	   */
	  public function actionIndex()
	  {
	  	$this->pageTitle = 'Голосование';
	    $model=new PollChoice('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['PollChoice']))
	      $model->attributes=$_GET['PollChoice'];

	    $this->render('index',array(
	      'model'=>$model,
	    ));
	  }
    /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model = new PollChoice;

   // $this->performAjaxValidation($model);

	   if (isset($_POST['PollChoice'])) {
	      $model->attributes = $_POST['PollChoice'];
	       if ($model->save()) {
	       	  $this->redirect(array('index'));
	       } else {
	       	 $this->addFlashMessage($model->errors,'error');
	       }
	   }

    $this->render('create', array(
      'model' => $model,
    ));
  }

  public function actionUpdatestatus($id, $status)
    {
        $id = (int)$id;
        $model = PollChoice::model()->findByPk($id);

        if (!empty($model) && ($status == PollChoice::STATUS_OPEN || $status == PollChoice::STATUS_CLOSED))
        {
           $model->status = $status;

           if($model->save(false,'status')){
                
           }
        }
        echo '[]';
    }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id)
  {
    $model = $this->loadModel($id);
    // $choices = $model->choices;
    //$this->performAjaxValidation($model);

    if (isset($_POST['PollChoice'])) {
      $model->attributes = $_POST['PollChoice'];

      // Setup poll choices
  /*    $choices = array();

      if (isset($_POST['PollChoice'])) {
        foreach ($_POST['PollChoice'] as $id => $choice) {
          $pollChoice = $this->loadChoice($model, $choice['id']);
          $pollChoice->attributes = $choice;
          $choices[$id] = $pollChoice;
        }
      }
*/
      if (isset($_POST['PollChoice'])) {
	      $model->attributes = $_POST['PollChoice'];
	       if ($model->save()) {
	       	  $this->redirect(array('index'));
	       } else {
	       	 $this->addFlashMessage($model->errors,'error');
	       }
	   }
     /* if ($model->save()) {
        // Save any poll choices too
        foreach ($choices as $choice) {
          $choice->poll_id = $model->id;
          
          if(!$choice->save()){
          	VarDumper::dump($choice->errors); die(); // Ctrl + X	Delete line
          }
          
        }

      //  $this->redirect(array('view', 'id' => $model->id));
      } */
    }

    $this->render('update',array(
      'model'=>$model,

    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
 
      // we only allow deletion via POST request
      $model = $this->loadModel($id);
      if ($model)
      {
            $model->delete();
           
      }
        echo '[]';

  }
    /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id)
  {
    $model = $this->loadModel($id);

  /*  if (Yii::app()->getModule('poll')->forceVote && $model->userCanVote()) {
      $this->redirect(array('vote', 'id' => $model->id)); 
    }
    else {*/
      $userVote = $this->loadVote($model);
      $userChoice = $this->loadChoice($model, $userVote->choice_id);

      $this->render('view', array(
        'model' => $model,
        'userVote' => $userVote,
        'userChoice' => $userChoice,
        'userCanCancel' => $model->userCanCancelVote($userVote),
      ));
   // }
  }
  /**
   * Returns the PollChoice model based on primary key or a new PollChoice instance.
   * @param Poll the Poll model 
   * @param integer the ID of the PollChoice to be loaded
   */
  public function loadChoice($poll, $choice_id)
  {
    if ($choice_id) {
      foreach ($poll->choices as $choice) {
        if ($choice->id == $choice_id) return $choice;
      }
    }

    return new PollChoice;
  }
  /**
   * Returns the PollVote model based on primary key or a new PollVote instance.
   * @param object the Poll model 
   */
  public function loadVote($model)
  {
    $userId = (int) Yii::app()->user->id;
    $isGuest = Yii::app()->user->isGuest;

    foreach ($model->votes as $vote) {
      if ($vote->user_id == $userId) {
        if (Yii::app()->getModule('poll')->ipRestrict && $isGuest && $vote->ip_address != $_SERVER['REMOTE_ADDR'])
          continue;
        else
          return $vote;
      }
    }

    return new PollVote;
  }
    public function actionUploadLogo(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadlogosession);
        $uploader->inputName = 'tmpLogotip';
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

 
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return City the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
  {
    $model=PollChoice::model()->with('pollVotes')->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']===$form)
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

 
}