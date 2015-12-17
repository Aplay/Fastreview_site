<?php
/**
 * Форма регистрации пользователя
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormRegisterSocial extends User
{
    /**
     * @return type Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('username, fullname, email, password', 'filter', 'filter' => 'strip_tags'),
            array('username, fullname, email, password', 'filter','filter' =>'trim'),
            array('username, fullname, password', 'required'),
            array('username, fullname, email, password, photo, soc_network_name', 'length', 'max' => 255),
            array('email', 'email'),
            array('from_soc_network', 'boolean'),
            array('username', 'checkAvailableNoCase'),
          //  array('username', 'unique', 'message' => 'Имя уже занято'),
            array('email', 'checkEmail'),
          //  array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => "Допустимы только символы (A-z0-9_)."),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('avatar_enc', 'safe')
        );
    }

    public function checkAvailableNoCase($attr)
    {
        $labels = $this->attributeLabels();
        $value = (string)($this->$attr);
        $check = User::model()->find(array('condition'=>'LOWER('.$attr.')=:username','params'=>array('username'=>MHelper::String()->toLower($value))));
        if($check)
            $this->addError($attr, 'Логин занят');
    } 

    public function checkEmail($attribute, $params) {
        if($this->email != '') {
            $dbEmail = User::model()->find('LOWER(email)=?',array(MHelper::String()->toLower($this->email)));
            if($dbEmail == null) {
                return true;
                // $this->addError($attribute, 'Email does not exist in the database.');
            } elseif($dbEmail->email != $this->email) {
                $this->addError($attribute, 'Email занят');
                 return false;
            } elseif ($dbEmail->email == $this->email) {
                $this->addError($attribute, 'Email занят');
                 return false;
            }
        } else {
            return true;
        }
        return true;
    }



    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'Email',
            'fullname' => 'Имя',
        );
    }

}