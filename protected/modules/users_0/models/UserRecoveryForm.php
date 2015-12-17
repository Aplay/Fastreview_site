<?php

/**
 * UserRecoveryForm class.
 * UserRecoveryForm is the data structure for keeping
 * user recovery form data. It is used by the 'recovery' action of 'UserController'.
 */
class UserRecoveryForm extends CFormModel {
    public $login_or_email, $user_id;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('login_or_email', 'filter', 'filter' => 'strip_tags'),
            array('login_or_email', 'filter','filter' =>'trim'),
            array('login_or_email', 'required', 'message'=>'Введите Логин или Email'),
            array('login_or_email', 'match', 'pattern' => '/^[A-Za-z-0-9@._]+$/u', 'message' => UsersModule::t("Incorrect symbols (A-z0-9).")),
           // password needs to be authenticated
            array('login_or_email', 'checkexists'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'login_or_email' => 'Логин или E-mail',
        );
    }

    public function checkexists($attribute, $params) {
        if (!$this->hasErrors()) // we only want to authenticate when no input errors
        {
            $value = (string)$this->login_or_email;
            if (strpos($this->login_or_email, "@")) {
               // $user = User::model()->findByAttributes(array('email' => $this->login_or_email));
                $user = User::model()->find(array('condition'=>'LOWER(email)=:email','params'=>array(':email'=>MHelper::String()->toLower($value))));
       
                if ($user)
                    $this->user_id = $user->id;
            } else {
               // $user = User::model()->findByAttributes(array('username' => $this->login_or_email));
                $user = User::model()->find(array('condition'=>'LOWER(username)=:username','params'=>array(':username'=>MHelper::String()->toLower($value))));
         
                if ($user)
                    $this->user_id = $user->id;
            }

            if ($user === null)
                if (strpos($value, "@")) {
                    $this->addError("login_or_email", UsersModule::t("Email is incorrect."));
                } else {
                    $this->addError("login_or_email", UsersModule::t("Username is incorrect."));
                }
        }
    }

}