<?php

class RegistrationController extends Controller {
    public $defaultAction = 'registration';

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     * Registration user
     */
    public function actionRegistration() {
        
        $this->layout = '//layouts/login';

        $model = new RegistrationForm;

        // ajax validator
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'signup-form_id') {
           /* echo UActiveForm::validate($model);
            Yii::app()->end(); */
            $errors = CActiveForm::validate($model);
            echo $errors;
            Yii::app()->end(); 
        }

        if (Yii::app()->user->id) {
            $this->redirect('/');
        } else {
        	$this->redirect('/login');
            if (isset($_POST['RegistrationForm'])) {
                $model->attributes = $_POST['RegistrationForm'];
                $model->verifyPassword = $model->password;
                if ($model->validate()) {
                    $soucePassword = $model->password;
                    $model->activkey = UsersModule::encrypting(microtime() . $model->password);
                    $model->password = UsersModule::encrypting($model->password);
                    $model->verifyPassword = UsersModule::encrypting($model->verifyPassword);
                    $model->superuser = 0;
                    $model->status = ((Yii::app()->getModule('users')->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);

                    if ($model->save()) {

                        Yii::app()->queue->subscribe($model->id, null, "User.{$model->id}");

                        if (Yii::app()->getModule('users')->sendActivationMail) {
                            $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activkey" => $model->activkey, "email" => $model->email));
                            UsersModule::sendMail($model->email, UsersModule::t("You registered from {site_name}", array('{site_name}' => Yii::app()->name)), UsersModule::t("Please activate you account go to {activation_url}", array('{activation_url}' => $activation_url)));
                        }


                      // wellcome email
                      $subject = Yii::t('email','Welcome');
                      $message = Yii::t('email', 'Welcome to <a href="{url}">{catalog}</a>.', array('{url}'=>$this->createAbsoluteUrl('/'), '{catalog}'=>Yii::app()->name));
                      SendMail::send($model->email,$subject,$message,true);


                        if ((Yii::app()->getModule('users')->loginNotActiv || (Yii::app()->getModule('users')->activeAfterRegister && Yii::app()->getModule('users')->sendActivationMail == false)) && Yii::app()->getModule('users')->autoLogin) {
                            $identity = new UserIdentity($model->username, $soucePassword);
                            $identity->authenticate();
                            Yii::app()->user->login($identity, 0);
                            $this->redirect(Yii::app()->getModule('users')->returnUrl);
                        } else {
                            if (!Yii::app()->getModule('users')->activeAfterRegister && !Yii::app()->getModule('users')->sendActivationMail) {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Contact Admin to activate your account."));
                            } elseif (Yii::app()->getModule('users')->activeAfterRegister && Yii::app()->getModule('users')->sendActivationMail == false) {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Please {{login}}.", array('{{login}}' => CHtml::link(UsersModule::t('Login'), Yii::app()->getModule('users')->loginUrl))));
                            } elseif (Yii::app()->getModule('users')->loginNotActiv) {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Please check your email or login."));
                            } else {
                                Yii::app()->user->setFlash('registration', UsersModule::t("Thank you for your registration. Please check your email."));
                            }
                            $this->refresh();
                        }
                    }
                } else {
                   // var_dump($model->errors);die();
                }
            }
            $this->render('/user/registration', array('model' => $model));
        }
    }
}