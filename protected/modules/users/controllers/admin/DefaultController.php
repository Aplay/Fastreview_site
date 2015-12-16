<?php
class DefaultController extends SAdminController {

	public $layout = '//layouts/admin';
    public $user;
    public $active_link = 'users';
    public $pageTitle = 'Пользователи';
    
    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, updateEmailNotification, upload, unlink, addavatar, updateuserstatusinproject'
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

    public function actionIndex() {

    	$users = new User('search');
        $users->unsetAttributes();


        $criteria = new CDbCriteria;
        $criteria->condition = 'status != '.User::STATUS_DELETED;
        if(Yii::app()->request->getQuery('s')){
            $s = MHelper::String()->toLower(Yii::app()->request->getQuery('s'));
            $s = addcslashes($s, '%_'); // escape LIKE's special characters
            $criteria->condition ='status != '.User::STATUS_DELETED.' AND ((LOWER(email) LIKE :s)OR(LOWER(username) LIKE :s)OR(LOWER(fullname) LIKE :s))';
            $criteria->params = array(':s'=>"%$s%");
        }

        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'sort'=>array(
            	'attributes'=>array(
                'id','username','fullname'
            	),
            	'defaultOrder' => 'id ASC'
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $this->render('index',array('dataProvider'=>$dataProvider));
    }
    public function actionCreate() {
        $user = new RegistrationForm;
        if (isset($_POST['RegistrationForm'])) {
            // ajax validator
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
           /* echo UActiveForm::validate($model);
            Yii::app()->end(); */
            $errors = CActiveForm::validate($user);
            echo $errors;
            Yii::app()->end(); 
        }

            $data = Yii::app()->request->getPost('RegistrationForm');
             if($data){

                $user->attributes = $data;
            }
            $user->verifyPassword = $user->password;
            $user->signup_confirm = 1;
            if ($user->validate()) {
                $soucePassword = $user->password;
                $user->activkey = UsersModule::encrypting(microtime() . $user->password);
                $user->password = UsersModule::encrypting($user->password);
                $user->verifyPassword = UsersModule::encrypting($user->verifyPassword);
                $user->superuser = 0;
                $user->status =  User::STATUS_ACTIVE;
                

                if($user->save()){
                    if(isset(Yii::app()->session['usertmpimage']) && !empty(Yii::app()->session['usertmpimage'])){
                        if(rename($folder = Yii::getPathOfAlias('webroot').'/uploads/tmp/'.Yii::app()->session['usertmpimage'], $user->getFileFolder().Yii::app()->session['usertmpimage'])){
                            $user->photo = Yii::app()->session['usertmpimage'];
                            $user->saveAttributes(array('photo'));
                            unset(Yii::app()->session['usertmpimage']);
                        }
                        
                    }
                    Yii::app()->queue->subscribe($user->id, null, "User.{$user->id}");
                    $this->redirect(Yii::app()->createUrl('users/admin/default'));
                    $this->addFlashMessage('Пользователь успешно добавлен.');
                } else {
                    $this->addFlashMessage($user->errors,'red');
                }
            } else {

                $this->addFlashMessage($user->errors,'red');
            }
        }
        $this->render('create', array('user'=>$user));
    }
    public function actionUpdate($id) {


        $user = $this->_loadUser($id);

        
      //  if($user->id == Yii::app()->user->id)
       //     $this->redirect('/settings');
        // форма изменения пароля
        $changePassword = new UserChangePassword;

        if (isset($_POST['User'])) {


            $this->performAjaxValidation($user, 'form-fullname');
            $this->performAjaxValidation($user, 'form-about');
            $this->performAjaxValidation($user, 'form-social');

            if(isset($_POST['ajax']) && $_POST['ajax']==='form-account-username')
            {
                
                $errors = CActiveForm::validate($user);
                if ($errors !== '[]') {
                 // echo CJSON::encode($errors);
                    //echo CJSON::encode(false);
                    echo 'false';
                   Yii::app()->end();
                } 
            }
            if(isset($_POST['ajax']) && $_POST['ajax']==='form-account-email')
            {
                
                $errors = CActiveForm::validate($user);
                if ($errors !== '[]') {
                  //echo CJSON::encode($errors);
                    //echo CJSON::encode(false);
                    echo 'false';
                   Yii::app()->end();
                } 
            }
            

            $data = Yii::app()->request->getPost('User');
             if($data)
                $user->attributes = $data;

                if(!$user->save()){
                    VarDumper::dump($user->errors);
                }

        }
        if(isset($_POST['UserChangePassword'])){

            $this->performAjaxValidation($changePassword, 'form-changepassword');

            $data = Yii::app()->request->getPost('UserChangePassword');
            $changePassword->attributes = $data;
            if ($changePassword->validate()) {
                $new_password = User::model()->notsafe()->findbyPk($user->id);
                $new_password->password = UsersModule::encrypting($changePassword->password);
                $new_password->activkey = UsersModule::encrypting(microtime() . $changePassword->password);
                if($new_password->save(false)){
                     echo 'done';
                } else {
                    // VarDumper::dump($new_password->errors);
                }
               

            }

        }

        if(Yii::app()->request->isAjaxRequest){
            Yii::app()->end();
        } else {
            $this->render('view', array('user'=>$user,'changePassword'=>$changePassword));
        }
    }

    public function actionDelete($id) {
        $user = $this->_loadUser($id);
        $user->status = User::STATUS_DELETED;
        if($user->id != Yii::app()->user->id){
	        if($user->delete()){
	      //  Yii::app()->user->logout();
	      //  $this->redirect(Yii::app()->homeUrl);
	       
	        } 
	    }
	     echo '[]';
    }

    public function actionUpload($id){

        $user = $this->_loadUser($id);
        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder = $user->getFileFolder(); // folder for uploaded files
        $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $thumbTo = array(160,160);
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, null, null, null, $thumbTo, $getUploadName = true);
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        return $result;
    }
    public function actionUploadNew(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder = Yii::getPathOfAlias('webroot').'/uploads/tmp'; // folder for uploaded files
        $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $thumbTo = array(160,160);
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, null, null, null, $thumbTo, $getUploadName = true);
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        return $result;
    }

    public function actionAddAvatar($id)
    {
        $user = $this->_loadUser($id);
        $saveAvatar = $this->actionUpload($id);
        $jsonDecode = CJSON::decode($saveAvatar);
        if(!empty($jsonDecode) && isset($jsonDecode['success']) && $jsonDecode['success'] == true && !empty($jsonDecode['uploadName'])){ // save photo
            $user->photo = $jsonDecode['uploadName'];
            $user->saveAttributes(array('photo'));
        }

        echo $saveAvatar;
    }

    public function actionAddAvatarNew()
    {

        $saveAvatar = $this->actionUploadNew();
        $jsonDecode = CJSON::decode($saveAvatar);
        if(!empty($jsonDecode) && isset($jsonDecode['success']) && $jsonDecode['success'] == true && !empty($jsonDecode['uploadName'])){ // save photo
            Yii::app()->session['usertmpimage'] = $jsonDecode['uploadName'];
        }

        echo $saveAvatar;
    }
    public function actionUnlinknew(){
        if(isset(Yii::app()->session['usertmpimage']) && !empty(Yii::app()->session['usertmpimage'])){
            $imagePath = Yii::getPathOfAlias('webroot').'/uploads/tmp/'.Yii::app()->session['usertmpimage'];
            if(file_exists($imagePath)) {
                unlink($imagePath); //delete file
            } 
        }
        echo '[]';
    }
    public function actionUnlink($id){

        $user = $this->_loadUser($id);
        $user->deletePhoto();
        
        echo '[]';

    }

    public function actionUpdateEmailNotification($id)
    {
        $user = $this->_loadUser($id);
        $newValues = array();
        if(!empty($_POST['value']))
            $newValues = (array)($_POST['value']);

        $oldValues = UserNotification::model()->findAll('user_id=:user_id', array(':user_id'=>$user->id));
        if(!empty($oldValues)){
            foreach($oldValues as $key=>$value){

                    if (false !== $key_ar = array_search($value->notification_id, $newValues)) { // get key of $newValues array if value = id
                        $value->by_email = true;
                        $value->save(false);
                        unset($newValues[$key_ar]);
                    } else {
                        $value->by_email = false;
                        $value->save(false); 
                    }
            }
        } 
        if(!empty($newValues)){
            foreach($newValues as $value){
        
                $userNotification = new UserNotification();
                $userNotification->user_id = $user->id;
                $userNotification->notification_id = (int)$value;
                $userNotification->by_email = true;
                if(!$userNotification->save()){
                    header('HTTP 400 Bad Request', true, 400);
                    echo CJSON::encode($userNotification->errors);
                    Yii::app()->end();
                }
            }
        }
            
        
        echo '[]';
    }
    public function actionUpdateUserStatusInProject() {
        $pk = (int)$_POST['pk'];
        $status = (int)$_POST['value'];
        $statuses = array(User::STATUS_SUPERUSER, User::STATUS_USER);
        if(!in_array($status, $statuses)){
            header('HTTP 400 Bad Request', true, 400);
            echo Yii::t('error','Wrong status');
            Yii::app()->end();
        }
        // need check for updater was admin
        if(Yii::app()->user->isSuperuser){
            $pruser = User::model()->findByPk($pk);
            if($pruser){
                $authorizer = Yii::app()->getModule("rights")->getAuthorizer();
                if($status == 1){
                    $type = 'Admin';

                } else {
                    $type = 'User';
                    if ($authorizer->authManager->getAuthAssignment('Admin', $pruser->id) !== null){
                        $authorizer->authManager->revoke('Admin', $pruser->id);
                    }
                }
                
                // http://www.yiiframework.com/doc/api/1.1/IAuthManager#assign-detail
                $authorizer->authManager->assign($type, $pruser->id);
              //  $pruser->updateByPk($pk, array('superuser'=>$status));
                if($pruser->id == Yii::app()->user->id){
                    echo 'me';
                } else {
                    echo '[]';
                }
            } else {
                header('HTTP 400 Bad Request', true, 400);
                echo Yii::t('error','Error');
            }
            
        } else {
            header('HTTP 400 Bad Request', true, 400);
            echo Yii::t('error','You are not admin');
        }
    }
    protected function _loadUser($id)
    {
        $user = User::model()->findByPk($id);
        if(!$user)
            throw new CHttpException(404, Yii::t('site','Page not found'));
        return $user;
    }  

 
}