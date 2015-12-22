<?php

class WebUser extends RWebUser {

    private $_model;

   // check this article http://www.yiiframework.com/wiki/80/add-information-to-yii-app-user-by-extending-cwebuser-better-version/
 
     public function init()
    {

    parent::init();
    }
   

    public function getRole() {
        return $this->getState('__role');
    }
/*
    public function getId() {
        return $this->getState('__id') ? $this->getState('__id') : 0;
    }*/

//    protected function beforeLogin($id, $states, $fromCookie)
//    {
//        parent::beforeLogin($id, $states, $fromCookie);
//
//        $model = new UserLoginStats();
//        $model->attributes = array(
//            'user_id' => $id,
//            'ip' => ip2long(Yii::app()->request->getUserHostAddress())
//        );
//        $model->save();
//
//        return true;
//    }

    public function afterLogin($fromCookie) {

        parent::afterLogin($fromCookie);
      //  $this->updateSession();
    }

    
    
    public function updateSession() {
        $user = Yii::app()->getModule('users')->user($this->id);
        $userAttributes = array(
            'email' => $user->email,
            'username' => $user->username,
            'create_at' => $user->create_at,
            'lastvisit_at' => $user->lastvisit_at,
        );
        foreach ($userAttributes as $attrName => $attrValue) {
            $this->setState($attrName, $attrValue);
        }
    }
/*
    public function model($id = 0) {
        return Yii::app()->getModule('users')->user($id);
    }
    */
/*
    public function user($id = 0) {
        return $this->model($id);
    }*/

    public function getUserByName($username) {
        return Yii::app()->getModule('users')->getUserByName($username);
    }

    public function getAdmins() {
        return Yii::app()->getModule('users')->getAdmins();
    }

    public function isAdmin() {
        return Yii::app()->getModule('users')->isAdmin();
    }


    // load user data
    public function getAvatar($header=false)
    {
        
        $this->_loadModel();
        if(!$this->_model){
            return User::AVATAR_NULL;
        }
        return $this->_model->getAvatar($header);
    }

    public function getShowname()
    {
        $this->_loadModel();
        return $this->_model->fullname?$this->_model->fullname:$this->_model->username;
    }

    public function getFullname()
    {
        $this->_loadModel();
        return $this->_model->fullname;
    }
    public function getUsername()
    {
        $this->_loadModel();
        return $this->_model->username;
    }

    public function getEmail()
    {
        $this->_loadModel();
        return $this->_model->email;
    }

    protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
                $this->_model=User::model()->findByPk($id);
        }
        return $this->_model;
    }

    private function _loadModel()
    {
        if(!$this->_model)
            $this->_model = User::model()->findByPk($this->id);
    }
       
    public function getModel()
    {
        $this->_loadModel();
        return $this->_model;
    }
    

}