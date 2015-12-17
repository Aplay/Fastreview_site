<?php
class DefaultController extends SAdminController {

	public $layout = '//layouts/admin';
    public $user;
    public $active_link = 'review';
    public $pageTitle = 'Отзывы';
    
    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, updatestatus'
        ));
    }
 

    public function actionIndex() {


        $model = new Comment('search');
        $model->unsetAttributes();

        if(!empty($_GET['Comment']))
            $model->attributes = $_GET['Comment'];

        $criteria = new CDbCriteria;
        $criteria->condition = 'id_parent is null';
        $dataProvider = $model->search($criteria);
        $dataProvider->pagination->pageSize = 25;

        $this->render('index', array(
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ));
    }

    public function actionUpdate($id)
    {
        $model = Comment::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');

        // Uncomment the following line if AJAX validation is needed
         $this->performAjaxValidation($model, 'comment-form');

        if(isset($_POST['Comment']))
        {
            $model->attributes=$_POST['Comment'];
            if($model->save()){

                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = "Отзыв отредактирован";
                    $this->addFlashMessage($text,'success');
                    $this->redirect(Yii::app()->createAbsoluteUrl('comments/admin/default'));
                }

            } else {
                $this->addFlashMessage($model->errors,'error');
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    public function actionUpdatestatus($id, $status)
    {
        $id = (int)$id;
        $model = Comment::model()->findByPk($id);

        if (!empty($model) && ($status == Comment::STATUS_APPROVED || $status == Comment::STATUS_WAITING))
        {
           $model->status = $status;
           if($model->save(false,'status')){
                
           }
        }
        echo '[]';
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        echo '[]';
        Yii::app()->end();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    //  if(!isset($_GET['ajax']))
    //      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function loadModel($id)
    {
        $model=Comment::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

 
}