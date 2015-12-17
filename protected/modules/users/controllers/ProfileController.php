<?php

class ProfileController extends Controller {
    public $defaultAction = 'profile';
    public $uploadlogosession = 'profilefiles';
    public $city = null;

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    public function filters()
    {
        return array(
            'accessControl',
            'ajaxOnly + upload, addavatar'
        );
    }
  
    public function beforeAction($action){

        
	        $domain = Yii::app()->request->getParam('city');

	        if($domain)
	            $this->city = City::model()->withUrl($domain)->find();

	        if(!$this->city) {
	        	
	            //	$this->redirect('http://'.Yii::app()->params['serverName']);
	        	
	        }
    	

        return true;
    }
    public function actionUpload(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadlogosession);
        $uploader->inputName = 'photo';
        $result = $uploader->handleUpload($folder);
        $datasession = Yii::app()->session->itemAt($this->uploadlogosession);
        if(!empty($datasession)){
        		end($datasession);
        		$key = key($datasession);
        		$result['tmpFile'] = $datasession[$key];

                $tmpFile = Yii::getPathOfAlias('webroot').'/uploads/tmp/'.$result['tmpFile'];

        if(file_exists($tmpFile)) {

           

            $thumbTo = array(160,160);
            $folder = Yii::getPathOfAlias('webroot').'/uploads/tmp/';
            $uploadDirectoryUpload = rtrim($folder,'/');
            $check = MHelper::File()->getUniqueTargetPath($uploadDirectoryUpload, $result['tmpFile']);
            $target = $uploadDirectoryUpload.'/'.$check;

           // if (copy($tmpFile, $target)){
                
                Yii::import('ext.phpthumb.PhpThumbFactory');
                $thumb  = PhpThumbFactory::create($tmpFile);
                $sizes  = Yii::app()->params['storeImages']['sizes'];
                $method = $sizes['resizeThumbMethod'];
                $thumb->$method($thumbTo[0],$thumbTo[1])->save($target);

               if (copy($target, $tmpFile)){
                    unlink($target); //delete tmp file
               }

               /* $result['tmpFile'] = $check;
        

                    $data_sess = array();

                    if(Yii::app()->session->itemAt($this->uploadlogosession)){
                        $data = Yii::app()->session->itemAt($this->uploadlogosession);
                        if(!is_array($data)){
                            $data_sess[$key] = $check;
                        } else {
                            $data[$key] = $check;
                            $data_sess = $data;
                        }
                    } else {
                        $data_sess[$key] = $check;
                    }
                    Yii::app()->session->remove($this->uploadlogosession);
                    Yii::app()->session->add($this->uploadlogosession, $data_sess);
                    
                    unlink($tmpFile); //delete tmp file
                    */
                    

           // }
        }
}
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }
    	
    /**
     * Shows a particular model.
     */
    public function actionProfile() {

        if(Yii::app()->request->isAjaxRequest){


			 if (isset($_POST['FormProfile'])) {

			 	$form2 = new FormProfile;
			 	$old_name = Yii::app()->user->fullname;
			 	if(!empty($_POST['FormProfile']['oldPassword']) ||
			 		!empty($_POST['FormProfile']['password']) || 
			 		  !empty($_POST['FormProfile']['verifyPassword'])){
				$form2->scenario = 'passchange';

				}
				$this->performAjaxValidation($form2, 'form-profile');

			 	$find = Yii::app()->user->model;
               			$message = '';
                        $form2->attributes = $_POST['FormProfile'];
                        if ($form2->validate()) {
                        	if($form2->scenario == 'passchange'){
                        		$find->password = Yii::app()->getModule('users')->encrypting($form2->password);
                                $find->activkey = Yii::app()->getModule('users')->encrypting(microtime() . $form2->password);
                        		if($find->save(true,array('password','activkey')))
                        			$message .= 'Пароль успешно изменен';
                        	}
                        	
	                            $find->fullname = $form2->fullname;
	                            if($find->save(true,array('fullname'))){
	                            	if($find->fullname != $old_name){
		                            	if(!empty($message))
		                            		$message .= '<br>';
		                            	$message .= 'Имя успешно изменено';
	                            	}
	                            } 
	                        $find->photo = $form2->photo;
	                        if($find->addDropboxLogoFiles($this->uploadlogosession)){
	                        	if(!empty($message))
		                            		$message .= '<br>';
		                            	$message .= 'Фото успешно изменено';
	                        	 Yii::app()->session->remove($this->uploadlogosession); 
	                        }
			                  	
                            	

                            if(Yii::app()->request->isAjaxRequest){
                                echo CJSON::encode(array(
                                    'flag'=>true,
                                    'message'=>$message
                                ));
                                Yii::app()->end();
                            } else {
                                // Yii::app()->user->setFlash('recoveryMessage', $message);
                                $this->refresh();
                            }
                            
                        }
                    }
             echo '[]';
			Yii::app()->end();
		} else {
			if(!Yii::app()->user->isGuest)
				$this->redirect(Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username)));
			$this->redirect('/');

			$model = $this->loadUser();
	        $this->render('profile', array(
	            'model' => $model,
	        ));
		}
    }

    public function actionAddAvatar()
    {
    	
    	$saveAvatar = $this->actionUpload();
    	$jsonDecode = CJSON::decode($saveAvatar);
    	if(!empty($jsonDecode) && isset($jsonDecode['success']) && $jsonDecode['success'] == true && !empty($jsonDecode['uploadName'])){ // save photo
    		$user = Yii::app()->user->model;
    		$user->photo = $jsonDecode['uploadName'];
    		$user->saveAttributes(array('photo'));
    	}

    	echo $saveAvatar;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionEdit() {
        $model = $this->loadUser();

        // ajax validator
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form') {
            echo UActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];

            if ($model->validate()) {
                $model->save();
                Yii::app()->user->updateSession();
                Yii::app()->user->setFlash('profileMessage', UsersModule::t("Changes is saved."));
                $this->redirect(array('/user/profile'));
            }
        }

        $this->render('edit', array(
            'model' => $model,
        ));
    }

    /**
     * Change password
     */
    public function actionChangepassword() {
        $model = new UserChangePassword;
        if (Yii::app()->user->id) {

            // ajax validator
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'changepassword-form') {
                echo UActiveForm::validate($model);
                Yii::app()->end();
            }

            if (isset($_POST['UserChangePassword'])) {
                $model->attributes = $_POST['UserChangePassword'];
                if ($model->validate()) {
                    $new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
                    $new_password->password = UsersModule::encrypting($model->password);
                    $new_password->activkey = UsersModule::encrypting(microtime() . $model->password);
                    $new_password->save();
                    Yii::app()->user->setFlash('profileMessage', UsersModule::t("New password is saved."));
                    $this->redirect(array("profile"));
                }
            }
            $this->render('changepassword', array('model' => $model));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
     */
    public function loadUser() {
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = Yii::app()->getModule('users')->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->getModule('users')->loginUrl);
        }
        return $this->_model;
    }
}