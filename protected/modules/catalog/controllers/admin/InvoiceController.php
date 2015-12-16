<?php
class InvoiceController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'invoice';
    public $pageTitle = 'Оплаты';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete',
        );
    }

   
    public function actionIndex() {

        $this->pageTitle = 'Оплаты';

        $model = new Invoice('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Invoice']))
            $model->attributes=$_GET['Invoice'];

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
            $model=new Invoice;
        else
            $model=$this->loadModel($_GET['id']);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Invoice']))
        {
            $model->attributes=$_POST['Invoice'];
            $model->sum = $model->amount * $model->period;

            if(!empty($model->discount)){
                $model->sum_discount = $model->sum - ($model->sum * $model->discount / 100);
                $model->sum_discount = round($model->sum_discount, 2);
            } else {
                $model->discount = 0;
                $model->sum_discount = $model->sum;
            }

            

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
        $model=Invoice::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
?>