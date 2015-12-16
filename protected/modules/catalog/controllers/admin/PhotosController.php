<?php
class PhotosController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'photos';
    public $pageTitle = 'Фотографии';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, unlink, deletefile',
        );
    }
    public function actionIndex() {

        $this->pageTitle = 'Фотографии';

        $model = new OrgsImages('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['OrgsImages']))
            $model->attributes=$_GET['OrgsImages'];
 
        $dataProvider = $model->search();
        $dataProvider->pagination->pageSize = 25;


        $this->render('index',array(
            'model'=>$model, 
            'model_search'=>$dataProvider
        ));
    }
}