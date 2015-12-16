<?php

class RecoveryController extends Controller {
    public $defaultAction = 'recovery';

    /**
     * Recovery password
     */
    public function actionRecovery() {

     //   $this->layout = '//layouts/login';

        $model = new UserLogin;
        $modelRecovery = new UserRecoveryForm;

        $this->performAjaxValidation($modelRecovery, 'form-reminder');
        

        if (Yii::app()->user->id) {
            $this->redirect('/');
        } else {
            $email = Yii::app()->request->getParam('email');
            $activkey = Yii::app()->request->getParam('activkey');
            $email = ((!empty($email)) ? $email : '');
            $activkey = ((!empty($activkey)) ? $activkey : '');
            if ($email && $activkey) {
                $form2 = new UserChangePassword;

                $this->performAjaxValidation($form2, 'change_password');


                $find = User::model()->notsafe()->findByAttributes(array('email' => $email));
                if (isset($find) && $find->activkey == $activkey) {
                    if (isset($_POST['UserChangePassword'])) {
                        $form2->attributes = $_POST['UserChangePassword'];
                        if ($form2->validate()) {
                            $find->password = Yii::app()->getModule('users')->encrypting($form2->password);
                            $find->activkey = Yii::app()->getModule('users')->encrypting(microtime() . $form2->password);
                            if ($find->status == 0) {
                                $find->status = 1;
                            }
                            $find->save();
                            $message = Yii::t('site','New password is saved.');
                            if(Yii::app()->request->isAjaxRequest){
                                echo CJSON::encode(array(
                                    'flag'=>true,
                                    'message'=>$message
                                ));
                                Yii::app()->end();
                            } else {
                                Yii::app()->user->setFlash('recoveryMessage', $message);
                                $this->redirect(Yii::app()->getModule('users')->recoveryUrl);
                            }
                            
                        }
                    }
                    $this->render('changepasswordnew', array('model' => $form2));
                } else {
                    Yii::app()->user->setFlash('recoveryMessage', UsersModule::t("Incorrect recovery link."));
                   // $this->redirect(Yii::app()->getModule('users')->recoveryUrl);
                    $this->redirect('/');
                }
            } else {
            	
                if (isset($_POST['UserRecoveryForm'])) {
                    $modelRecovery->attributes = $_POST['UserRecoveryForm'];

                    if ($modelRecovery->validate()) {

                        $user = User::model()->notsafe()->findbyPk($modelRecovery->user_id);
                        $activation_url = $this->createAbsoluteUrl(implode(Yii::app()->getModule('users')->recoveryUrl).'?activkey='.$user->activkey.'&email='.$user->email);

                        $subject = UsersModule::t("Request for password recovery in {site_name}",
                            array(
                                '{site_name}' => Yii::app()->name,
                            ));
                        $message = UsersModule::t("You have requested the password recovery for access to {site_name}.<br> To get the password and to set the new one follow the link: {activation_url}",
                            array(
                                '{site_name}' => Yii::app()->name,
                                '{activation_url}' => $activation_url,
                            ));

                       // UsersModule::sendMail($user->email, $subject, $message);
                        SendMail::send($user->email,$subject,$message,true);

                        $message = Yii::t('site',"Please check your e-mail.<br> Instruction was sent to your e-mail address.");

                        if(Yii::app()->request->isAjaxRequest){

                        echo CJSON::encode(array(
                            'flag'=>true,
                            'message'=>$message
                        ));
                        Yii::app()->end();

                        } else {
                            Yii::app()->user->setFlash('recoveryMessage', $message);
                            $this->refresh();
                        }
                    } 
                }
                $this->render('recovery', array('model' => $model, 'modelRecovery'=>$modelRecovery));
            }
        }
    }

}