<?php
class AttributeController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'attribute';
    public $pageTitle = 'Атрибуты';

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
	  	$this->pageTitle = 'Атрибуты';
	    $model_group=new EavOptionsGroup('search');
	    $model_group->unsetAttributes();  // clear any default values
	    if(isset($_GET['EavOptionsGroup']))
	      $model_group->attributes=$_GET['EavOptionsGroup'];

      $model=new EavOptions('search');
      $model->unsetAttributes();  // clear any default values
      if(isset($_GET['EavOptions']))
        $model->attributes=$_GET['EavOptions'];

	    $this->render('index',array(
	      'model_group'=>$model_group,
        'model'=>$model,
	    ));
	  }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    public function actionCreateGroup() {
        $this->actionUpdateGroup(true);
    }
  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($new = false)
  {
        if ($new === true){
            $model = new EavOptions;
        } else {
            $id = (int)$_GET['id'];
            $model=$this->loadModel($id);
        }

      $options = array();  

      if (isset($_POST['EavOptions'])) {
         $model->attributes=$_POST['EavOptions'];
      
         if ($model->save()) {
            if(!$model->options_ar || !is_array($model->options_ar))
                $model->options_ar = array();
            $model->saveOptions($model->options_ar);
            $this->redirect(array('admin/attribute/index'));
         } else {

           $this->addFlashMessage($model->errors,'error');
           $options = $model->options_ar;
         }
     } else {
       
       if($model->options){
        foreach ($model->options as $key => $value) {
          $options[] = $value->value;
        }
       }
     }

    
    

    $this->render('_form',array(
      'model'=>$model,
      'options'=>$options

    ));
  }

  

   public function actionUpdateGroup($new = false)
  {
    if ($new === true){
        $model = new EavOptionsGroup;
    } else {
        $id = (int)$_GET['id'];
        $model=EavOptionsGroup::model()->findByPk($id);
        if($model===null)
          throw new CHttpException(404,'The requested page does not exist.');
    }

  

      if (isset($_POST['EavOptionsGroup'])) {
        $model->attributes = $_POST['EavOptionsGroup'];
         if ($model->save()) {
            $this->redirect(array('admin/attribute/index'));
         } else {
           $this->addFlashMessage($model->errors,'error');
         }
     }
     

    $this->render('_formgroup',array(
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
 
      
      $model = $this->loadModel($id);
      if ($model)
      {
            $model->delete();
      }
        echo '[]';

  }

  public function actionDeleteGroup($id)
  {
 
      $model=EavOptionsGroup::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
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

      $this->render('view', array(
        'model' => $model,
      ));
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
    $model=EavOptions::model()->findByPk($id);
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