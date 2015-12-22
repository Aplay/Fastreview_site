<?php
Yii::import('ext.phpthumb.PhpThumbFactory');
class User extends BaseModel {

	public $verifyPassword;

    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const STATUS_DELETED = 10;
    const STATUS_BANNED = -1;

    //TODO: Delete for next version (backward compatibility)
    const STATUS_BANED = -1;

    const STATUS_SUPERUSER = 1;
    const STATUS_USER = 0;

    const AVATAR_NULL = '/img/avatar.png';
    /**
     * The followings are the available columns in table 'users':
     * @var integer $id
     * @var string $username
     * @var string $password
     * @var string $email
     * @var string $activkey
     * @var integer $createtime
     * @var integer $lastvisit
     * @var integer $superuser
     * @var integer $status
     * @var timestamp $create_at
     * @var timestamp $lastvisit_at
     */

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return Yii::app()->getModule('users')->tableUsers;
    }

    public function scopes() {
        return array(
            'active' => array(
                'condition' => 'status=' . self::STATUS_ACTIVE,
            ),
            'notactive' => array(
                'condition' => 'status=' . self::STATUS_NOACTIVE,
            ),
            'banned' => array(
                'condition' => 'status=' . self::STATUS_BANNED,
            ),
            'superuser' => array(
                'condition' => 'superuser=1',
            ),
            'notsafe' => array(
                'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status',
            ),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.CConsoleApplication
        return ((get_class(Yii::app()) == 'CConsoleApplication' || (get_class(Yii::app()) != 'CConsoleApplication' && (Yii::app()->getModule('users')->isAdmin() && Yii::app()->user->id != $this->id))) ? array(
            array('username, fullname, email, phone, soc_twitter, soc_facebook,soc_envelope,soc_linkedin, soc_skype, about', 'filter', 'filter' => 'strip_tags'),
            array('username, fullname, email, phone, soc_twitter, soc_facebook,soc_envelope,soc_linkedin, soc_skype, about', 'filter','filter' =>'trim'),
            array('username, fullname, email', 'required'),
            array('fullname', 'length', 'max' => 30, 'min' => 3, 'tooShort' => 'Имя мин. 3 симв.', 'tooLong' => 'Имя макс. 30 симв.'),
            array('username', 'length', 'max' => 20, 'min' => 3, 'tooShort' => 'Логин мин. 3 симв.', 'tooLong' => 'Логин макс. 20 симв.'),
            array('password', 'length', 'max' => 128, 'min' => 5, 'tooShort' => 'Пароль мин. 5 симв.','tooLong' => 'Пароль макс. 128 симв.'),
            array('phone', 'length', 'max' => 255, 'min' => 3, 'message' => UsersModule::t("Incorrect phone (length between 3 and 255 characters).")),
            array('soc_twitter', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect twitter (length maximum 255 characters).")),
            array('soc_facebook', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect facebook (length maximum 255 characters).")),
            array('soc_envelope', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect e-mail (length maximum 255 characters).")),
            array('soc_linkedin', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect linkedIn (length maximum 255 characters).")),
            array('soc_skype', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect skype (length maximum 255 characters).")),
            array('email, soc_envelope', 'email'),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => UsersModule::t("Incorrect symbols (A-z0-9).")),
            array('username', 'checkIfAvailableNoCase'),
            array('email', 'checkEmailAvailable'),
            // array('username', 'unique', 'message' => UsersModule::t("This user's name already exists.")),
           // array('email', 'unique', 'message' => UsersModule::t("This user's email address already exists.")),
           // array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => UsersModule::t("Incorrect symbols (A-z0-9).")),
            array('status', 'in', 'range' => array(self::STATUS_NOACTIVE, self::STATUS_ACTIVE, self::STATUS_BANNED, self::STATUS_DELETED)),
            array('superuser', 'in', 'range' => array(0, 1)),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('lastvisit_at', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
            array('username, email, superuser, status', 'required'),
            array('superuser, status', 'numerical', 'integerOnly' => true),
            array('about', 'type', 'type'=>'string'),
            array('id, fullname, username, password, email, activkey, create_at, lastvisit_at, superuser, status, about', 'safe', 'on' => 'search'),
        ) : ((Yii::app()->user->id == $this->id) ? array(
            array('username, fullname, email, phone, soc_twitter, soc_facebook,soc_envelope,soc_linkedin, soc_skype, about', 'filter', 'filter' => 'strip_tags'),
            array('username, fullname, email, phone, soc_twitter, soc_facebook,soc_envelope,soc_linkedin, soc_skype, about', 'filter','filter' =>'trim'),
            array('username, fullname, email', 'required'),
            array('fullname', 'length', 'max' => 255, 'min' => 3, 'message' => UsersModule::t("Incorrect full name (length between 3 and 255 characters).")),
            array('username', 'length', 'max' => 20, 'min' => 3, 'message' => UsersModule::t("Incorrect username (length between 3 and 20 characters).")),
            array('password', 'length', 'max' => 128, 'min' => 5, 'message' => 'Пароль мин. 5 симв.'),
            array('phone', 'length', 'max' => 255, 'min' => 3, 'message' => UsersModule::t("Incorrect phone (length between 3 and 255 characters).")),
            array('soc_twitter', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect twitter (length maximum 255 characters).")),
            array('soc_facebook', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect facebook (length maximum 255 characters).")),
            array('soc_envelope', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect e-mail (length maximum 255 characters).")),
            array('soc_linkedin', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect linkedIn (length maximum 255 characters).")),
            array('soc_skype', 'length', 'max' => 255, 'message' => UsersModule::t("Incorrect skype (length maximum 255 characters).")),
            array('email, soc_envelope', 'email'),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => UsersModule::t("Symbols (A-z0-9_) only.")),
            array('email', 'checkEmailAvailable'),
            array('username', 'checkIfAvailableNoCase'),
            array('email', 'checkEmailAvailable'), array('about', 'type', 'type'=>'string'),
            array('id, fullname, username, password, email, activkey, create_at, lastvisit_at, superuser, status, about', 'safe', 'on' => 'search'),
            
        ) : array()));
    }

    public function checkIfAvailableNoCase($attr)
    {
        $labels = $this->attributeLabels();
        $id = (int)$this->id;
        $check = User::model()->find(array('condition'=>'id !=:id  and LOWER('.$attr.')=:username','params'=>array(':id'=>$id,'username'=>MHelper::String()->toLower($this->$attr))));
        if($check)
            $this->addError($attr, Yii::t('site', 'This user\'s name already exists.'));
    } 
   public function checkIfAvailable($attr)
    {
        $labels = $this->attributeLabels();
        $check = User::model()->countByAttributes(array(
            $attr=>$this->$attr,
        ), 't.id != :id', array(':id'=>(int)$this->id));

        if($check>0)
            $this->addError($attr, Yii::t('site', 'This user\'s name already exists.'));
    }

   public function checkEmailAvailable($attribute, $params) {
            $id = (int)$this->id;
            $dbEmail = User::model()->find('id != '.$id.' and LOWER(email)=?',array(MHelper::String()->toLower($this->email)));
            if($dbEmail == null) {
                return true;
                // $this->addError($attribute, 'Email does not exist in the database.');
            } elseif($dbEmail->email != $this->email) {
                $this->addError($attribute, Yii::t('site', 'This user\'s email already exists.'));
            } elseif ($dbEmail->email == $this->email) {
                $this->addError($attribute, Yii::t('site', 'This user\'s email already exists.'));
            }
        
        return false;
    }
    public function checkEmail($attribute, $params) {
        if($this->email != '') {
            $dbEmail = User::model()->find('LOWER(email)=?',array(MHelper::String()->toLower($this->email)));
            if($dbEmail == null) {
                return true;
                // $this->addError($attribute, 'Email does not exist in the database.');
            } elseif($dbEmail->email != $this->email) {
                $this->addError($attribute, Yii::t('site', 'This user\'s email address already exists.'));
            } elseif ($dbEmail->email == $this->email) {
                $this->addError($attribute, Yii::t('site', 'This user\'s email address already exists.'));
            }
        } else {
            $this->addError($attribute, 'Email is empty.');
        }
        return false;
    }
    public function behaviors(){
          return array(  
            'fileBehavior'=> array(
                'class' => 'application.components.behaviors.FileBehavior',
                'attribute' => 'photo',
                'cap' => self::AVATAR_NULL
            ));
          /* 'comments' => array(
                'class'       => 'application.modules.comments.components.CommentBehavior',
                'class_name'  => 'application.modules.users.models.User',
                'owner_title' => 'username', // Attribute name to present comment owner in admin panel
            ), */

    }

    public function addDropboxLogoFiles($uploadsession)
    {
        $files = $this->photo;
        $ret = false;
        if($files){

            if(Yii::app()->session->itemAt($uploadsession)){

                $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
               
                foreach($files as $fileUploadName){

                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {

								   $filename =  MHelper::File()->getUniqueTargetPath($this->getFileFolder(),$value);
                                    
                                    if (copy($folder.$value, $this->getFileFolder() . $filename)) {
                                        unlink($folder.$value);
                                        if($this->saveAttributes(array('photo'=>$filename))){
                                        		$ret = true;
                                        }
                                    } 
                                    
                                }
                            break;
                            }
                        }
                    }

                    
                }
            }
            
        }
        return $ret;
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => UsersModule::t("Id"),
            'fullname' => UsersModule::t("Name and surname"),
            'username' => UsersModule::t("Username"),
            'password' => UsersModule::t("Password"),
            'verifyPassword' => UsersModule::t("Retype Password"),
            'email' => UsersModule::t("E-mail"),
            'verifyCode' => UsersModule::t("Verification Code"),
            'activkey' => UsersModule::t("activation key"),
            'createtime' => UsersModule::t("Registration date"),
            'create_at' => UsersModule::t("Registration date"),

            'lastvisit_at' => UsersModule::t("Last visit"),
            'superuser' => UsersModule::t("Superuser"),
            'status' => UsersModule::t("Status"),
            'about' => Yii::t("site", 'About me'),
            'phone'=>Yii::t("site", 'Phone'),
            'soc_twitter'=>Yii::t("site", 'Twitter'),
            'soc_facebook'=>Yii::t("site", 'Facebook'),
            'soc_envelope'=>Yii::t("site", 'e-mail to communicate with colleagues'),
            'soc_linkedin'=>Yii::t("site", 'LinkedIn'),
            'soc_skype'=>Yii::t("site", 'Skype'),
        );
    }

    

    public function defaultScope() {
        return array(
           // 'alias' => 'idUser',
           // 'select' => 'idUser.id, idUser.username, idUser.email, idUser.create_at, idUser.lastvisit_at, idUser.superuser, idUser.status',
        );
    }
    
    public static function itemAlias($type, $code = NULL) {
        $_items = array(
            'UserStatus' => array(
                self::STATUS_NOACTIVE => UsersModule::t('Not active'),
                self::STATUS_ACTIVE => UsersModule::t('Active'),
                self::STATUS_BANNED => UsersModule::t('Banned'),
            ),
            'AdminStatus' => array(
                '0' => UsersModule::t('No'),
                '1' => UsersModule::t('Yes'),
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('fullname', $this->fullname, true);
        $criteria->compare('password', $this->password);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('activkey', $this->activkey);
        $criteria->compare('create_at', $this->create_at);
        $criteria->compare('lastvisit_at', $this->lastvisit_at);
        $criteria->compare('superuser', $this->superuser);
        $criteria->compare('status', $this->status);
        $criteria->compare('about', $this->about, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
               // 'pageSize' => Yii::app()->getModule('users')->user_page_size,
                'pageSize' => 100,
            ),
        ));
    }

    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at = date('Y-m-d H:i:s', $value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        $this->lastvisit_at = date('Y-m-d H:i:s', $value);
    }

    public static function getActiveUsers() {
        return CHtml::listData(self::model()->active()->findAll(), 'id', 'username');
    }
    public static function getAssignedUsers() {
       $users = CHtml::listData(self::model()->active()->findAll('id != '.Yii::app()->user->id), 'id', 'username');
       $users = array(Yii::app()->user->id => Yii::t('site', 'Assigned to me')) + $users;
       return $users;
    }

    public function getAvatar($header = false){
        $themeUrl = Yii::app()->theme->baseUrl;

        if(!empty($this->photo)){
        	
            if (false === strpos($this->photo, '://')) {
                return $this->getUrl('160x160');
               // return $this->getOrigFilePath().$this->photo;
            }
            return $this->photo;
            
        } else {
        	if($header)
        		return false;
            return self::AVATAR_NULL;
        }
    }


    public function getFileFolder()
    {
        $dir = Yii::getPathOfAlias('webroot').'/uploads/user/'.$this->id.'/';
        if (is_dir($dir) == false){
            CFileHelper:: createDirectory($dir, Yii::app()->params['storeImages']['dirMode']);
        }
        return $dir;
    }

    public function getOrigFilePath() {
        return Yii::app()->baseUrl.'/uploads/user/'.$this->id.'/';
    }

    protected function getDeleteFileFolder() {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/user/'.$this->id;
        if (is_dir($folder) == true)
            CFileHelper::removeDirectory($folder);
        return true;
    }

    public function deletePhoto()
    {
        $imagePath = $this->getFileFolder() . $this->photo;
        if(file_exists($imagePath)) {
            unlink($imagePath); //delete file
        }   
        $this->photo = null;
        $this->saveAttributes(array('photo'));
        
        return true; 
    }

    public function isFriendOf($invited_id)
    {
        

                $c = $this->getFriendships();
                if($c){
                    foreach ($c as $friendship) {
                        if (($friendship->user_id == $this->id && $friendship->friend_id == $invited_id) || ($friendship->friend_id == $this->id && $friendship->user_id == $invited_id)){
                            return $friendship->is_accepted;
                        }
                    }
                }
        return false;
    }
        public function heInviteMe($invited_id)
    {
        

                $condition = 'user_id = :uid  and friend_id = :fid and is_accepted=:is_accepted';
        return UserFriend::model()->find($condition, array(':uid' => $this->id,':fid' => $invited_id, ':is_accepted'=>UserFriend::CONTACT_REQUEST));

    }
        public function getFriendships()
    {
        //return $this->id;
                $condition = 'user_id = :uid or friend_id = :uid';
        return UserFriend::model()->findAll($condition, array(':uid' => $this->id));
    }
        public function getFriendshipsnewexcept()
    {
        //return $this->id;
                $condition = 'friend_id = :uid and is_accepted=:is_accepted';
        return UserFriend::model()->findAll($condition, array(':uid' => $this->id, ':is_accepted'=>UserFriend::CONTACT_REQUEST));
    }
        public function getFriendshipsnew()
    {
        //return $this->id;
                $condition = '(user_id = :uid or friend_id = :uid) and is_accepted=:is_accepted';
        return UserFriend::model()->findAll($condition, array(':uid' => $this->id, ':is_accepted'=>UserFriend::CONTACT_REQUEST));
    }
         public function getFriendshipsold()
    {
        //return $this->id;
                $condition = '(user_id = :uid or friend_id = :uid)  and is_accepted=:is_accepted';
        return UserFriend::model()->findAll($condition, array(':uid' => $this->id, ':is_accepted'=>UserFriend::CONTACT_ACCEPTED));
    }
        /*
         * общие друзья
         */
        public function getOurFriendships($my_user_id) 
    {
        //return $this->id;
                $him1_mass = array();  $my1_mass = array(); 
                $condition = 'user_id = :uid or friend_id = :uid';
                $him1 = UserFriend::model()->findAllBySql("SELECT DISTINCT friend_id FROM user_friend WHERE user_id=:user_id and is_accepted=".UserFriend::CONTACT_ACCEPTED, array(':user_id'=>$this->id));
                $him2 = UserFriend::model()->findAllBySql("SELECT DISTINCT user_id FROM user_friend WHERE friend_id=:user_id and is_accepted=".UserFriend::CONTACT_ACCEPTED, array(':user_id'=>$this->id));
                if($him1){
                    foreach($him1 as $hm1){
                        $him1_mass[] = $hm1->friend_id;
                    }
                }
                if($him2){
                    foreach($him2 as $hm2){
                        $him1_mass[] = $hm2->user_id;
                    }
                }
                $my1 = UserFriend::model()->findAllBySql("SELECT DISTINCT friend_id FROM user_friend WHERE user_id=:user_id and is_accepted=".UserFriend::CONTACT_ACCEPTED, array(':user_id'=>$my_user_id));
                $my2 = UserFriend::model()->findAllBySql("SELECT DISTINCT user_id FROM user_friend WHERE friend_id=:user_id and is_accepted=".UserFriend::CONTACT_ACCEPTED, array(':user_id'=>$my_user_id));
                if($my1){
                    foreach($my1 as $m1){
                        $my1_mass[] = $m1->friend_id;
                    }
                }
                if($my2){
                    foreach($my2 as $m2){
                        $my1_mass[] = $m2->user_id;
                    }
                }
              
                return array_intersect($my1_mass, $him1_mass);
               // return UserFriend::model()->findAllBySql("select distinct uf.friend_id from user_friend uf where uf.is_accepted=2 AND uf.user_id IN (select uf0.friend_id from user_friend uf0 where uf0.is_accepted and uf0.user_id=:user_id) order by uf.id DESC", array(':user_id'=>$this->id));
        //return UserFriend::model()->findAll($condition, array(':uid' => $this->id));
    }
        public static function getAllFriends($user_id){
                $my1_mass = array(); 
                $my1 = UserFriend::model()->findAllBySql("SELECT DISTINCT friend_id FROM user_friend WHERE user_id=:user_id and is_accepted=".UserFriend::CONTACT_ACCEPTED, array(':user_id'=>$user_id));
                $my2 = UserFriend::model()->findAllBySql("SELECT DISTINCT user_id FROM user_friend WHERE friend_id=:user_id and is_accepted=".UserFriend::CONTACT_ACCEPTED, array(':user_id'=>$user_id));
                if($my1){
                    foreach($my1 as $m1){
                        $my1_mass[] = $m1->friend_id;
                    }
                }
                if($my2){
                    foreach($my2 as $m2){
                        $my1_mass[] = $m2->user_id;
                    }
                }
   
                return $my1_mass;
        }
        public function countNewFriends()
    {
        //return $this->id;
                $condition = '(user_id = :uid or friend_id = :uid) and is_accepted=:is_accepted';
        return UserFriend::model()->count($condition, array(':uid' => $this->id, ':is_accepted'=>UserFriend::CONTACT_ACCEPTED));
    }
    public function getContactList($additionalCriteria = null, $list = 'all', $other = false)
    {
    // get contact list
        $criteria = new CDbCriteria;
        $criteria->condition = 'status = :status';
        $criteria->params = array(':status' => User::STATUS_ACTIVE);
        $criteria->order = 'fullname ASC';

        if($additionalCriteria !== null)
            $criteria->mergeWith($additionalCriteria);

        if($list == 'old'){
            $fr = $this->getFriendshipsold();
        } else {
            $fr = $this->getFriendships();
        }
        
        $fr_mass = array();
        if($fr){

            foreach($fr as $hm1){
                
                    $fr_mass[] = $hm1->friend_id;
                    $fr_mass[] = $hm1->user_id;
            }
        }

      //  $in = "\"\""; // важно, иначе, при пустом $in IN($in) выдаст ошибку
        if($fr_mass){
            $fr_mass = array_unique($fr_mass);
            if($other == false){
                if(($key = array_search(Yii::app()->user->id, $fr_mass)) !== false) {
                    unset($fr_mass[$key]);
                }
            } else {
                if(($key = array_search($this->id, $fr_mass)) !== false) {
                    unset($fr_mass[$key]);
                }
            }
            
            $in = implode(',',$fr_mass);

          

        } else {
            $in = "-1";
        }

        $criteria->addCondition('t.id IN ('.$in.')');
        

     /*   $merge = new CDbCriteria;
        $merge->select = 'id';
        $merge->mergeWith($criteria);
        $kkeys = CHtml::listData(User::model()->findAll($merge),'id','id');
        */
        
       // $criteria->order = 'create_at DESC';
        return new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'pagination'=>array(
                   'pageSize'=>100,
                  // 'pageVar' =>'page',
               ),
            ));
    }

    public function getShowname()
    {
        return $this->fullname?$this->fullname:$this->username;
    }

    public function withUrl($url)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'username=:url',
            'params'=>array(':url'=>$url)
        ));
        return $this;
    }

    public function chickPhoto(){
        $photo = $this->photo;
        if (true === strpos($this->photo, '://')) {
                
        $save = MHelper::File()->getRemoteImg($photo, true);
        $tmpFile = Yii::getPathOfAlias('webroot').'/uploads/tmp/'.$save;

        if(file_exists($tmpFile)) {
            $thumbTo = array(160,160);
            $folder = $this->getFileFolder();
            $check = MHelper::File()->getUniqueTargetPath($uploadDirectoryUpload, $save);
            $target = $uploadDirectoryUpload.'/'.$check;
 
            if (copy($tmpFile, $target)){
               
                $thumb  = PhpThumbFactory::create($target);
                $sizes  = Yii::app()->params['storeImages']['sizes'];
                $method = $sizes['resizeThumbMethod'];
                $thumb->$method($thumbTo[0],$thumbTo[1])->save($target);
                $this->photo = $check;
                $this->save(true,array('photo'));
                unlink($tmpFile); //delete tmp file
            } 
        }
        }
        return true;
    }
     protected function beforeDelete(){
        if(!parent::beforeDelete())
            return false;
        Comment::model()->updateAll(array('user_id'=>0),array('condition'=>'user_id='.$this->id));
        OrgsImages::model()->updateAll(array('uploaded_by'=>null),array('condition'=>'uploaded_by='.$this->id));
        Orgs::model()->updateAll(array('author'=>null),array('condition'=>'author='.$this->id));

        $this->deleteModelDir(); // удалили модель? удаляем и файл и всю папку
        return true;
    }
 
    public function deleteModelDir(){
        // Delete folder and all files on it.
        $dir = Yii::getPathOfAlias('webroot').'/uploads/user/'.$this->id;
        CFileHelper::removeDirectory($dir);
    }
}