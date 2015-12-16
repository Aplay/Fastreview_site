<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User {
    public $verifyPassword;
    public $verifyCode;
    public $signup_confirm;

    public function rules() {
        $rules = array(
            array('username, fullname, email, password', 'filter', 'filter' => 'strip_tags'),
            array('username, fullname, email, password', 'filter','filter' =>'trim'),
            array('email', 'required', 'message'=>'Введите E-mail'),
            array('username, fullname', 'required'),
            array('password', 'required', 'message'=>'Введите пароль'),
            array('fullname', 'length', 'max' => 255, 'min' => 3, 'tooShort' => 'Имя мин. 3 симв.', 'tooLong' => 'Имя макс. 255 симв.'),
            array('username', 'length', 'max' => 255, 'min' => 3, 'tooShort' => 'Логин мин. 3 симв.', 'tooLong' => 'Логин макс. 255 симв.'),
            array('password', 'length', 'max' => 128, 'min' => 5, 'tooShort' => 'Пароль мин. 5 симв.','tooLong' => 'Пароль макс. 128 симв.'),
            array('email', 'email'),
            array('email', 'checkEmail'), 
            array('username', 'checkAvailableNoCase'),
            
         //   array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => "Только латинские символы"),
         //   array('signup_confirm','compare','compareValue'=>true,'message'=>Yii::t('site','Need agree with Terms and Conditions')),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
        );
        if (!(isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form')) {
           // array_push($rules, array('verifyCode', 'captcha', 'allowEmpty' => !UsersModule::doCaptcha('registration')));
        }

       // array_push($rules, array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => UsersModule::t("Retype Password is incorrect.")));
        return $rules;
    }

    public function checkAvailableNoCase($attr)
    {
        $labels = $this->attributeLabels();
        $value = (string)($this->$attr);
        $check = User::model()->find(array('condition'=>'LOWER('.$attr.')=:username','params'=>array('username'=>MHelper::String()->toLower($value))));
        if($check) {
            $this->addError($attr, 'Email занят');
            return false;
        }
        return true;
    } 
    // need for unique rule with caseSensitive = false
    public function stringify($value)
    {
        //to string
        $value = (string)($value);
        return $value;
    }
    public function checkEmail($attribute, $params) {

        $dbEmail = User::model()->find(array('condition'=>'LOWER(email)=:email','params'=>array('email'=>MHelper::String()->toLower($this->email))));
        if(!$dbEmail) {
            return true;
        } else{
            $this->addError($attribute, 'Email занят');
        }

        return false;
    }
}