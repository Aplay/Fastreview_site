<?php 
class Login extends CWidget
{
	public $return_url;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		if(Yii::app()->user->isGuest) 
		{ 
			$modelLogin = new UserLogin;
	        $modelRegister = new RegistrationForm;
	        $modelRecovery = new UserRecoveryForm;
			$themeUrl = Yii::app()->theme->baseUrl;
			
			$this->render('login',array(
				'return_url'=>$this->return_url,
				'themeUrl'=>$themeUrl,
				'modelLogin'=>$modelLogin,
				'modelRegister'=>$modelRegister,
				'modelRecovery'=>$modelRecovery
				));
		}
	}
    
}
