<?php
/**
 * Форма обратной связи
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormFeedbackUpdate extends CFormModel
{
	public $org;
	public $subject;
	public $content;
	public $verifyCode;
	public $reCaptcha;


    /**
     * @return type Правила валидации атрибутов
     */
	public function rules()
	{
		// $codeEmpty = !CCaptcha::checkRequirements() || !Yii::app()->user->isGuest;
		$r =  array(
            array('content', 'filter','filter' =>'trim'),
			array('content', 'required', 'message'=>'Напишите нам, что необходимо исправить'),
			array('org', 'required'),
			array('org', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>10000),
			array('reCaptcha', 'ReCaptchaValidator',  'secret'=>Yii::app()->reCaptcha->secret, 'message'=>'Неправильный код проверки'),
		);
		if (Yii::app()->user->isGuest) {
			if (!($this->reCaptcha = Yii::app()->request->getPost(ReCaptchaValidator::CAPTCHA_RESPONSE_FIELD))) {
            	$r[] = array('reCaptcha', 'required');
        	}
        }
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
			'content' => 'Напишите нам, что необходимо исправить',
			'verifyCode'=>'Код проверки',
			'reCaptcha'=>'Код проверки',
			'org'=>'Фирма'
		);
	}
}