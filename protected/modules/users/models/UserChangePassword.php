<?php
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserChangePassword extends CFormModel {
    public $oldPassword;
    public $password;
    public $verifyPassword;

    public function rules() {
        return Yii::app()->controller->id == 'recovery' ? array(
            array('password, verifyPassword', 'filter', 'filter' => 'strip_tags'),
            array('password, verifyPassword', 'filter','filter' =>'trim'),
            array('password', 'required',  'message'=>'Заполните пароль'),
            array('verifyPassword', 'required',  'message'=>'Подтвердите пароль'),
            array('password, verifyPassword', 'length', 'max' => 128, 'min' => 5, 'tooShort'=>"Мин. длина 5 симв."),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Пароли не совпадают"),
        ) : array(
            array('oldPassword, password, verifyPassword', 'filter', 'filter' => 'strip_tags'),
            array('oldPassword, password, verifyPassword', 'filter','filter' =>'trim'),
            array('oldPassword', 'required', 'message'=>'Заполните старый пароль'),
            array('password', 'required',  'message'=>'Заполните новый пароль'),
            array('verifyPassword', 'required',  'message'=>'Подтвердите новый пароль'),
            array('oldPassword, password, verifyPassword', 'length', 'max' => 128, 'min' => 5, 'tooShort'=>"Мин. длина 5 симв."),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Пароли не совпадают"),
            array('oldPassword', 'verifyOldPassword'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'oldPassword' => 'Старый пароль',
            'password' => 'Новый пароль',
            'verifyPassword' => 'Подтвердите новый пароль',
        );
    }

    /**
     * Verify Old Password
     */
    public function verifyOldPassword($attribute, $params) {
        if (User::model()->notsafe()->findByPk(Yii::app()->user->id)->password != Yii::app()->getModule('users')->encrypting($this->$attribute))
            $this->addError($attribute, UsersModule::t("Old Password is incorrect."));
    }
}