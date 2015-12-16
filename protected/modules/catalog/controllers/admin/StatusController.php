<?php
class StatusController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'status';
    public $pageTitle = 'Статусы';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete',
        );
    }

   
    public function actionIndex() {

        $this->pageTitle = 'Статусы';

        $model = new Status('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Status']))
            $model->attributes=$_GET['Status'];

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
        
        $this->actionUpdate(true);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($new=false)
    {
        if($new === true)
            $model=new Status;
        else
            $model=$this->loadModel($_GET['id']);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Status']))
        {
            $model->attributes=$_POST['Status'];
            if($model->save()){

                $this->redirect(array('index'));
            } else {
                $this->addFlashMessage($model->errors, 'error');
            }
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(Yii::app()->request->isAjaxRequest){
            echo '[]';
            Yii::app()->end();
        }

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Zal the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Status::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
?>