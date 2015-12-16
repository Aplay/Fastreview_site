<?php


class AuthController extends SAdminController
{

	public $layout = '//layouts/adminlogin';

	public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'MyCCaptchaAction',
                'backColor' => 0xFFFFFF,
                'minLength'=>4,
                'maxLength'=>5,
                'height'=>35,
                'foreColor'=>0x999999, //цвет символов,
               // 'fontFile'=>'./font/arial.ttf'
               // 'backend'=>Yii::app()->params['captchaRender']
            ),

        );
    }

	public function allowedActions()
	{
		return 'index, logout, captcha';
	}

	/*public function actionRedirect(){
		if (Yii::app()->user->isGuest) {

			$this->redirect(Yii::app()->createUrl('/'));
		}
	}*/
	/**
	 * Display admin login page.
	 */
	public function actionIndex()
	{
		
		Yii::import('application.modules.users.models.UserLogin');
		$model = new UserLogin('admin');

		if (Yii::app()->user->isGuest) {

		if(isset($_POST['ajax']) && $_POST['ajax']==='signin-form_id') {
                $errors = CActiveForm::validate($model);
                echo $errors;
                Yii::app()->end();
        }

		if(isset($_POST['UserLogin'])){

		$model->attributes = $_POST['UserLogin'];
            // validate user input and redirect to previous page if valid
            if ($model->validate()) {

            	
                $this->lastViset();
             	$this->redirect(Yii::app()->createUrl('/'.Yii::app()->params['adminPath']));
            }
        }
		

		$this->render('auth', array(
			'model'=>$model,
		));

		} else {
			
			if(!Yii::app()->user->checkAccess('Admin')){
		            Yii::app()->user->logout();
		            $model->addError('username',Yii::t('site','Access denied'));
		            $this->render('auth', array('model'=>$model,));
		            Yii::app()->end();
		        }
			$this->redirect(Yii::app()->createUrl('/'.Yii::app()->params['adminPath']));
		}
	}

	private function lastViset() {
        $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
        $lastVisit->lastvisit = time();
        $lastVisit->save();
    }

	/**
	 * Logout user
	 */
	public function actionLogout()
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(405, Yii::t('AdminModule.admin', 'Ошибка. Вы еще  неавторизовались.'));

		Yii::app()->user->logout();
		Yii::app()->request->redirect($this->createUrl('/'.Yii::app()->params['adminPath']));
	}

}
