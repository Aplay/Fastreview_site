<?php
class DefaultController extends SAdminController {

	public $layout = '//layouts/admin';
    public $pageTitle = 'Рубрики';
    public $active_link = 'catalog';
    public $uploadlogosession = 'categorylogofiles';

    public $CQtreeGreedView = array(
        'modelClassName' => 'Category', //название класса
        'adminAction' => '_form' //action, где выводится QTreeGridView.
    );


    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, movefirms'
        ));
    }
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
   /* public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'view', 'upload', 'addavatar', 'unlink', 'UpdateEmailNotification'),
                'users' => UsersModule::getAdmins(),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }*/

    public function actions()
    {
        return array(
            'create' => 'ext.QTreeGridView.actions.Create',
            'update' => 'ext.QTreeGridView.actions.Update',
            'delete' => 'ext.QTreeGridView.actions.Delete',
            'moveNode' => 'ext.QTreeGridView.actions.MoveNode',
            'makeRoot' => 'ext.QTreeGridView.actions.MakeRoot',
        );
    }

    public function actionIndex() {
    	
        $this->pageTitle = 'Рубрики';

        $model = new Category('search');
        $model->unsetAttributes();

        if(isset($_GET['Category']))
            $model->attributes=$_GET['Category'];


        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider'=>$dataProvider,
        ));
    }

    public function actionCreate() {
    
        $err = true;

        $model = new Category();
        if (isset($_POST['Category'])) {
            $model->attributes = $_POST['Category'];

            if ($model->validate()) {

                if (!empty($model->parent_id)) {
                    
                    $parent = $this->loadModel($model->parent_id);
                    if ($model->appendTo($parent)) {
                        $model->refresh();
                        $err = false;
                    }

                } else {
                    if($model->saveNode()   && $model->storeTypeAttributes(Yii::app()->getRequest()->getPost('attributes', []))){
                        $err = false;
                    }
                }

                if ($err) {
                    $this->addFlashMessage($model->errors,'error');
                } else {
                    $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/default'));
                }
            } else {
                $this->addFlashMessage($model->errors,'error');
            }
        }

        $this->render('_form', array('model' => $model));
    }
    /**
     * Update category
     */
     public function actionUpdate($id) {

        $err = true;

        $id = (int)$id;

        $model = $this->loadModel($id);


        $this->pageTitle = $model->title . ' - ' . Yii::app()->name;

        if (isset($_POST['Category'])) {

            $model->attributes = $_POST['Category'];

            if ($model->validate()) {

                if ($model->getParent() === null)
                    $parent_id_old = null;
                else
                    $parent_id_old = $model->getParent()->id;
                
                $target = null;
                if ($parent_id_old == $model->parent_id) { // if sending parent_id == current parrent_id do nothing;

                    if ($model->saveNode()  && $model->storeTypeAttributes(Yii::app()->getRequest()->getPost('attributes', []))){
                    	/*$model->addDropboxLogoFiles($this->uploadlogosession);
                		Yii::app()->session->remove($this->uploadlogosession);*/
                        $err = false;
                    }
                } else { // move node

                   // $model->deleteNode();
                   // $model->refresh();
                    

                    if($model->parent_id){

                        $pos = isset($_POST['position']) ? (int)$_POST['position'] : null;
                        $target = $this->loadModel($model->parent_id);
                        $childs = $target->children()->findAll();

                        if(!empty($pos) && isset($childs[$pos-1]) && $childs[$pos-1] instanceof Category && $childs[$pos-1]['id'] != $model->id){
                            $model->moveAfter($childs[$pos-1]);
                            if ($model->saveNode()){$err = false;}
                        } else if($target->id != $model->id) {
                            $model->moveAsLast($target);
                            if ($model->saveNode()){$err = false;}
                        }
                    } else {
                        $model->moveAsRoot();
                        if ($model->saveNode()){$err = false;}
                    }

                }
                if ($err === false) {
                    Yii::app()->user->setFlash('success', "Рубрика {$model->title} успешно обновлена");
                    $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/default'));
                } else {
                    Yii::app()->user->setFlash('error', "Ошибка обновления рубрики");
                }
                $this->redirect(array('index'));
            }
        }


        $this->render('_form', array(
            'model' => $model, 

        ));
    }

    public function actionReturnMove_Firms()
    {
        $this->excludeScripts();
        $text = '';
        //Figure out if we are updating a Model or creating a new one.
        if (isset($_POST['id'])) $model = $this->loadModel($_POST['id']);
        if (isset($_POST['text'])) $text = $_POST['text'];
        $this->renderPartial('category_move_firms', array(
                'model' => $model,
                'text'=>$text,
                'parent_id' => !empty($_POST['id']) ? $_POST['id'] : '',
                'modelClassName' => 'Category'
            ),
            false, true);
    }

    public function actionMovefirms(){

        if (isset($_POST['update_id'])) 
        {
            $update_id = (int)$_POST['update_id'];
            $moveto =  $table = null;
            $connection=Yii::app()->db;

            if(isset($_POST['moveto'])){
                $moveto = (int)$_POST['moveto'];
                $table = 'orgs_category';
            }
            
            if($table)
            {
	            $sql = "SELECT * from $table  WHERE category=".$update_id;
	            $command=$connection->createCommand($sql);
	            $rows=$command->queryAll();
	            if($rows){
	                foreach ($rows as $row) {
	                    $sql = "SELECT count(id) from $table  WHERE category=".$moveto." and org=".$row['org'];
	                    $command = $connection->createCommand($sql);
	                    $count = $command->queryScalar();
	                    if(!$count){
	                        $sql = "UPDATE $table  SET category=".$moveto." WHERE id=".$row['id'];
	                        $command=$connection->createCommand($sql);
	                        $ex=$command->execute();
	                    }
	                }
	                $sql = "DELETE from $table  WHERE category=".$update_id;
	                $command=$connection->createCommand($sql);
	                $ex=$command->execute();
	            }
        	}
        }

        echo CJSON::encode(array('success'=>true));
        Yii::app()->end();

    }

    public function actionDelete($id) {
        $model = $this->loadModel($id);

        $desc = $model->descendants()->findAll(array('order'=>'title'));
        if(!$desc){
                
        } else {
            $error = "Рубрика имеет подрубрики:\n ";
            foreach ($desc as $rub) {
                $error .= $rub->title."\n";
            }
            $error .= 'Сначала перенесите или удалите подрубрики.';
            echo CJSON::encode(array('success'=>false, 'message'=>$error, 'errortype'=>1));
            Yii::app()->end();
        }
        $count = OrgsCategory::model()->countByAttributes(array('category'=>$model->id));
        if($count > 0){
            $error = 'В рубрике имеются '.$count.' организаций. Перенесите сначала организации в другую рубрику.';
            echo CJSON::encode(array('success'=>false, 'message'=>$error, 'errortype'=>2));
            Yii::app()->end();
        }

     

        //Delete if not root node
        $model->tree->delete();
        echo CJSON::encode(array('success'=>true));

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
        $model = Category::model()->findByPk($id);
        if(!$model)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
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
    public function actionDeleteLogoFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = Category::model()->findByPk($id);
        if(!empty($file)){
           // Delete file
            $filename = $file->logotip;
            $imagePath = $file->getFileFolder() . $filename;
            if(file_exists($imagePath)) {
                unlink($imagePath); //delete file
            }
            $file->logotip = '';
            $file->logotip_realname = '';
            $file->saveNode(array('logotip','logotip_realname'));
                echo "[]";
                Yii::app()->end();
        }

        
        echo 'error';
        Yii::app()->end();
    }
     /**
     *  don't reload these scripts or they will mess up the page
     */
    private function excludeScripts()
    {
        Yii::app()->clientScript->scriptMap['*.js'] = false;
    }

}