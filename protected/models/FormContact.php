<?php
/**
 * Форма обратной связи
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormContact extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $content;
	public $verifyCode;
	public $reCaptcha;


    /**
     * @return type Правила валидации атрибутов
     */
	public function rules()
	{
		$r =  array(
            array('name, email, content', 'filter','filter' =>'trim'),
			array('name, email, content', 'required'),
			array('name, email', 'length', 'max'=>255),
			array('content', 'length', 'max'=>5000),
			array('email', 'email'),
			// array('reCaptcha', 'ReCaptchaValidator',  'secret'=>Yii::app()->reCaptcha->secret, 'message'=>'Неправильный код проверки'),
		);
	/*	if (Yii::app()->user->isGuest) {
			if (!($this->reCaptcha = Yii::app()->request->getPost(ReCaptchaValidator::CAPTCHA_RESPONSE_FIELD))) {
            	$r[] = array('reCaptcha', 'required');
        	}
        } */
        return $r;
	}
	public function captchaValidate() {  
	    $code = Yii::app()->controller->createAction('captcha')->getVerifyCode();  
	    if ($code != $this->verifyCode)  
	        $this->addError('verifyCode', 'Неправильный код проверки');  
	} 
    /**
     * @return array Метки атрибутов (name=>label)
     */
	public function attributeLabels()
	{
		return array(
			'name' => 'Имя',
			'email' => 'E-mail',
			'content' => 'Сообщение',
			'verifyCode'=>'Код проверки',
			'reCaptcha'=>'Код проверки',
		);
	}
}