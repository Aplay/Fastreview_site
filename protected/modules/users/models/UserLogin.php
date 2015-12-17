<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserLogin extends CFormModel {
    public $username;
    public $password;
    public $rememberMe;
    public $verifyCode;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username', 'required', 'message'=>'Введите Логин или E-mail'),
            array('password', 'required','message'=>'Введите пароль'),
            // rememberMe needs to be a boolean
            //array('rememberMe', 'boolean'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!Yii::app()->user->isGuest || !CCaptcha::checkRequirements(), 'on' => 'admin'),
            // password needs to be authenticated
            array('password', 'authenticate'),
            
        );
    }

    public function captchaValidate() {  
        $code = Yii::app()->controller->createAction('captcha')->getVerifyCode();  
        if ($code != $this->verifyCode)  
            $this->addError('verifyCode', Yii::t('site', 'Wrong code'));  
    } 

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'rememberMe' => UsersModule::t("Remember me next time"),
            'username' => 'Логин или E-mail',
            'password' => 'Пароль',
            'verifyCode'=>'Код проверки',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) // we only want to authenticate when no input errors
        {
            $identity = new UserIdentity($this->username, $this->password);
            $identity->authenticate();
            switch ($identity->errorCode) {
                case UserIdentity::ERROR_NONE:
                    $duration = $this->rememberMe ? Yii::app()->getModule('users')->rememberMeTime : 0;
                    Yii::app()->user->login($identity, $duration);
                    break;
                case UserIdentity::ERROR_EMAIL_INVALID:
                    $this->addError("username", UsersModule::t("Email is incorrect."));
                    break;
                case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError("username", UsersModule::t("Username is incorrect."));
                    break;
                case UserIdentity::ERROR_STATUS_NOTACTIV:
                    $this->addError("status", UsersModule::t("Your account is not activated."));
                    break;
                case UserIdentity::ERROR_STATUS_BAN:
                    $this->addError("status", UsersModule::t("Your account is blocked."));
                    break;
                case UserIdentity::ERROR_STATUS_DELETED:
                    $this->addError("status", UsersModule::t("Your account is deleted."));
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError("password", UsersModule::t("Password is incorrect."));
                    break;
            }
        }
    }
}
