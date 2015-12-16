<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    private $_id;
    const ERROR_EMAIL_INVALID = 3;
    const ERROR_STATUS_NOTACTIV = 4;
    const ERROR_STATUS_BAN = 5;
    const ERROR_STATUS_DELETED = 10;


    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

    	
         if (strpos($this->username, "@")) {
            $user = User::model()->find(array('condition'=>'LOWER(email)=:email','params'=>array(':email'=>MHelper::String()->toLower($this->username))));
        } else {
            $user = User::model()->find(array('condition'=>'LOWER(username)=:username','params'=>array(':username'=>MHelper::String()->toLower($this->username))));
         
          //  $user = User::model()->notsafe()->findByAttributes(array('username' => $this->username));
        }
        if ($user === null)
            if (strpos($this->username, "@")) {
                $this->errorCode = self::ERROR_EMAIL_INVALID;
            } else {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            }
        else if (Yii::app()->getModule('users')->encrypting($this->password) !== $user->password)
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($user->status == 0 && Yii::app()->getModule('users')->loginNotActiv == false)
            $this->errorCode = self::ERROR_STATUS_NOTACTIV;
        else if ($user->status == -1)
            $this->errorCode = self::ERROR_STATUS_BAN;
        else if ($user->status == User::STATUS_DELETED)
            $this->errorCode = self::ERROR_STATUS_DELETED;
        else {
            $this->_id = $user->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }
}