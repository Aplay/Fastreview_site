<?php

class LoginController extends Controller {
    public $defaultAction = 'login';
	public $layout = '//layouts/main';

    /**
     * Displays the login page
     */
    public function actionLogin() {


        if (Yii::app()->user->isGuest) 
        {

            $modelLogin = new UserLogin;
            $modelRegister = new RegistrationForm;
            $modelRecovery = new UserRecoveryForm;

            // collect user input data
            if(isset($_POST['ajax']) && $_POST['ajax']==='form-login') {

                $errors = CActiveForm::validate($modelLogin);

                echo $errors;
               /* if(Yii::app()->request->urlReferrer && Yii::app()->request->urlReferrer == 'http://'.Yii::app()->request->serverName.'/mkreview'){
                	// Сохраняем в сессию единицу, чтобы сохранить данные в localStorage при создании отзыва
        			 Yii::app()->session['redirectReview'] = 1;
                }*/
                Yii::app()->end();
            }
            if(isset($_POST['ajax']) && $_POST['ajax']==='form-register') {
            	if(isset($_POST['RegistrationForm']['email'])){
                    $modelRegister->username = $_POST['RegistrationForm']['email'];
            		$modelRegister->fullname = $_POST['RegistrationForm']['email'];
                }
                $errors = CActiveForm::validate($modelRegister);
                
                if($errors != '[]'){
                	echo $errors;
                	Yii::app()->end();
                }
                
            }

            if (isset($_POST['UsersLogin'])) {

                $modelLogin->attributes = $_POST['UsersLogin'];
                // validate user input and redirect to previous page if valid

                if ($modelLogin->validate()) {
                    $this->lastViset();
                    
                   /* if(Yii::app()->request->urlReferrer && Yii::app()->request->urlReferrer == 'http://'.Yii::app()->request->serverName.'/mkreview'){
                    	// Сохраняем в сессию единицу, чтобы сохранить данные в localStorage при создании отзыва
            			Yii::app()->session['redirectReview'] = 1;
                    }*/
                    if (Yii::app()->user->returnUrl == '/index.php' || Yii::app()->user->returnUrl == '/')
                        $this->redirect(Yii::app()->getModule('users')->returnUrl);
                    else
                        $this->redirect(Yii::app()->user->returnUrl);
                    
                } else {
                   // VarDumper::dump($modelLogin->errors); die(); // Ctrl + X    Delete line
                }
            }
            if (isset($_POST['RegistrationForm'])) {

               

                $modelRegister->attributes = $_POST['RegistrationForm'];
                $modelRegister->username = $modelRegister->email;
                $modelRegister->fullname = $modelRegister->email;
                $modelRegister->verifyPassword = $modelRegister->password;

                if ($modelRegister->validate()) {
                    $soucePassword = $modelRegister->password;
                    $modelRegister->activkey = UsersModule::encrypting(microtime() . $modelRegister->password);
                    $modelRegister->password = UsersModule::encrypting($modelRegister->password);
                    $modelRegister->verifyPassword = UsersModule::encrypting($modelRegister->verifyPassword);
                    $modelRegister->superuser = 0;

                    $modelRegister->status = ((Yii::app()->getModule('users')->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);

                    if ($modelRegister->save()) 
                    {

                        if (Yii::app()->getModule('users')->sendActivationMail) {
                            $activation_url = $this->createAbsoluteUrl('/users/activation/activation', array("activkey" => $modelRegister->activkey, "email" => $modelRegister->email));
                            UsersModule::sendMail($modelRegister->email, UsersModule::t("You registered from {site_name}", array('{site_name}' => Yii::app()->name)), UsersModule::t("Please activate you account go to {activation_url}", array('{activation_url}' => $activation_url)));
                        }

                        if ((Yii::app()->getModule('users')->loginNotActiv || (Yii::app()->getModule('users')->activeAfterRegister && Yii::app()->getModule('users')->sendActivationMail == false)) && Yii::app()->getModule('users')->autoLogin) 
                        {
                            $identity = new UserIdentity($modelRegister->username, $soucePassword);
                            $identity->authenticate();
                            Yii::app()->user->login($identity, 0);
                            $this->lastViset();
                            if(Yii::app()->request->isAjaxRequest){
                                echo '[]';
                                Yii::app()->end();
                            }  else {
            
                                 if(Yii::app()->request->urlReferrer && Yii::app()->request->urlReferrer != 'http://'.Yii::app()->request->serverName.'/login'){
					                    $url = Yii::app()->request->urlReferrer;
					                    $this->redirect($url);

					            } else {

					                $this->redirect('/');
					            }
                            }
                        } else {
                            if (!Yii::app()->getModule('users')->activeAfterRegister && !Yii::app()->getModule('users')->sendActivationMail) {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Contact Admin to activate your account."));
                            } elseif (Yii::app()->getModule('users')->activeAfterRegister && Yii::app()->getModule('users')->sendActivationMail == false) {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Please {{login}}.", array('{{login}}' => CHtml::link(UserModule::t('Login'), Yii::app()->getModule('users')->loginUrl))));
                            } elseif (Yii::app()->getModule('users')->loginNotActiv) {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Please check your email or login."));
                            } else {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Please check your email."));
                            }
                            if(Yii::app()->request->isAjaxRequest){
                                echo '[]';
                                Yii::app()->end();
                            }  else {
                            
                                if(Yii::app()->request->urlReferrer && Yii::app()->request->urlReferrer != 'http://'.Yii::app()->request->serverName.'/login'){
					                    $url = Yii::app()->request->urlReferrer;
					                    $this->redirect($url);

					            } else {

					                $this->redirect('/');
					            }
                            }
                            
                        }
                    }
                } else {
                    // var_dump($modelRegister->errors);die();
                }
            }
            
            // display the login form
            $this->render('application.views.site.login', array(
                'return_url'=>Yii::app()->createAbsoluteUrl('/'),
                'themeUrl'=>Yii::app()->theme->baseUrl,
                'modelLogin' => $modelLogin, 
                'modelRecovery'=>$modelRecovery, 
                'modelRegister'=>$modelRegister));
        } else {

        	if(Yii::app()->request->urlReferrer && Yii::app()->request->urlReferrer != 'http://'.Yii::app()->request->serverName.'/login'){
                    $url = Yii::app()->request->urlReferrer;
                    $this->redirect($url);

            } else {

                $this->redirect('/');
            }
        }
           
    }

    private function lastViset() {
        $lastVisit = User::model()->findByPk(Yii::app()->user->id);
        $lastVisit->lastvisit = time();
        $lastVisit->save();
    }

}