<?php
/**
 * @link https://github.com/himiklab/yii2-recaptcha-widget
 * @copyright Copyright (c) 2014 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */



/**
 * ReCaptcha widget validator.
 *
 * @author HimikLab
 * @package himiklab\yii2\recaptcha
 */
class ReCaptchaValidator extends CValidator
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const CAPTCHA_RESPONSE_FIELD = 'g-recaptcha-response';

    /** @var boolean Whether to skip this validator if the input is empty. */
    public $skipOnEmpty = false;

    /** @var string The shared key between your site and ReCAPTCHA. */
    public $secret;

    public function init()
    {
        parent::init();
        if (empty($this->secret)) {
            if (!empty(Yii::$app->reCaptcha->secret)) {
                $this->secret = Yii::$app->reCaptcha->secret;
            } else {
                throw new Exception('Required `secret` param isn\'t set.');
            }
        }

        if ($this->message === null) {
            $this->message = Yii::t('yii', 'The verification code is incorrect.');
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @return string
     */
    public function clientValidateAttribute($model, $attribute)
    {
        $message = Yii::t(
            'yii',
            '{attribute} cannot be blank.',
            ['attribute' => $model->getAttributeLabel($attribute)]
        );
        return "(function(messages){if(!grecaptcha.getResponse()){messages.push('{$message}');}})(messages);";
    }

    protected function validateAttribute($object,$attribute)
	{	
		/*if($this->isEmpty($object->$attribute)) {
            $this->addError($object,$attribute,'Необходимо заполнить код проверки');
            return;
        }*/
		$this->validateValue($object->$attribute);
		$message = Yii::t(
            'yii',
            '{attribute} cannot be blank.',
            ['attribute' => $object->getAttributeLabel($attribute)]
        );
        return "(function(messages){if(!grecaptcha.getResponse()){messages.push('{$message}');}})(messages);";
   }

    /**
     * @param string $value
     * @return array|null
     * @throws Exception
     */
    protected function validateValue($value)
    {

    	if (empty($value)) {
            if (!($value = Yii::app()->request->getPost(self::CAPTCHA_RESPONSE_FIELD))) {
            	
                	return [$this->message, []];
            	
            }
        }
        $request = self::SITE_VERIFY_URL . '?' . http_build_query(
            [
                'secret' => $this->secret,
                'response' => $value,
                'remoteip' => Yii::app()->request->getUserHostAddress()
            ]
        );
        $response = $this->getResponse($request);
        if (!isset($response['success'])) {
            throw new Exception('Invalid recaptcha verify response.');
        }
        return $response['success'] ? null : [$this->message, []];
    }

    /**
     * @param string $request
     * @return mixed
     */
    protected function getResponse($request)
    {
        $response = file_get_contents($request);
        return CJSON::decode($response, true);
    }
}
