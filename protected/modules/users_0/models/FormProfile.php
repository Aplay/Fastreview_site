<?php
/**
 * FormProfile class.
 * FormProfile is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class FormProfile extends CFormModel {
    public $oldPassword;
    public $password;
    public $verifyPassword;
    public $fullname;
    public $photo;
    public $phone;

    public function rules() {
        return  array(
            array('fullname, oldPassword, password, verifyPassword, phone', 'filter', 'filter' => 'strip_tags'),
            array('fullname, oldPassword, password, verifyPassword, phone', 'filter','filter' =>'trim'),
            array('fullname', 'required', 'message'=>'Заполните имя', 'on'=>'namechange'),
            array('phone', 'length', 'max' => 255, 'min' => 3, 'tooShort' => 'Телефон мин. 3 симв.', 'tooLong' => 'Телефон макс. 255 симв.', 'on'=>'namechange'),
            array('fullname', 'length', 'max' => 255, 'min' => 3, 'tooShort' => 'Имя мин. 3 симв.', 'tooLong' => 'Имя макс. 255 симв.',  'on'=>'namechange'),
            array('oldPassword', 'required', 'message'=>'Заполните старый пароль', 'on'=>'passchange'), 
            array('password', 'required',  'message'=>'Заполните новый пароль', 'on'=>'passchange'),
         //   array('verifyPassword', 'required',  'message'=>'Подтвердите новый пароль', 'on'=>'passchange'),
            array('oldPassword, password, verifyPassword', 'length', 'max' => 128, 'min' => 5, 'tooShort'=>"Мин. длина 5 симв.", 'on'=>'passchange'),
         //   array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Пароли не совпадают", 'on'=>'passchange'),
            array('oldPassword', 'verifyOldPassword', 'on'=>'passchange'),
            array('photo', 'safe'),
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