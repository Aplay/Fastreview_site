<?php
Yii::import('application.modules.users.models.User');
Yii::import('application.modules.catalog.models.Objects');
/**
 * This is the model class for table "Follow".
 *
 * The followings are the available columns in table 'Follow':
 * @property integer $id
 * @property integer $user_id
 * @property string $class_name
 * @property integer $object_pk
 * @property integer $status
 * @property string $email
 * @property string $name
 * @property string $text
 * @property string $created
 * @property string $updated
 * @property string $ip_address
 * @method approved()
 * @method orderByCreatedAsc()
 * @method orderByCreatedDesc()
 */
class Follow extends BaseModel
{

	const STATUS_WAITING = 0;
	const STATUS_APPROVED = 1;
	const STATUS_SPAM = 2;

	/**
	 * @var string
	 */
	public $verifyCode;

	/**
	 * @var int status for new follow
	 */
	public $defaultStatus;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Follow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Initialize
	 */
	public function init()
	{
		$this->defaultStatus = Follow::STATUS_APPROVED;
		return parent::init();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'follow';
	}

	public function scopes()
	{
		$alias = $this->getTableAlias();
		return array(
			'orderByCreatedAsc'=>array(
				'order'=>$alias.'.created ASC',
			),
			'orderByCreatedDesc'=>array(
				'order'=>$alias.'.created DESC',
			),
			'waiting'=>array(
				'condition'=>$alias.'.status='.self::STATUS_WAITING,
			),
			'approved'=>array(
				'condition'=>$alias.'.status='.self::STATUS_APPROVED,
			),
			'spam'=>array(
				'condition'=>$alias.'.status='.self::STATUS_SPAM,
			)
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$codeEmpty=!Yii::app()->user->isGuest;
		/*if(YII_DEBUG) // For tests
			$codeEmpty=true;*/
		return array(
                        array('name, text', 'filter','filter' =>'strip_tags'),
                        array('name, text', 'filter','filter' =>'trim'),
			array('email, name, text', 'required'),
			array('email', 'email'),
			array('status, created, updated', 'required', 'on'=>'update'),
			array('name', 'length', 'max'=>50),
                        array('text', 'length', 'max'=>3000),
			array('verifyCode','captcha','allowEmpty'=>$codeEmpty),
			// Search
			array('id, user_id, class_name, status, email, name, text, created, updated', 'safe', 'on'=>'search'),
		);
	}
        public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
                        'fromuser'=>array(self::BELONGS_TO, 'User', 'object_pk'),
                        'obj'=>array(self::BELONGS_TO, 'Products', 'object_pk'),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'         => 'ID',
			'user_id'    => FollowModule::t('Author'),
			'class_name' => FollowModule::t('Model'),
			'status'     => FollowModule::t('Status'),
			'email'      => FollowModule::t('Email'),
			'name'       => FollowModule::t('Name'),
			'text'       => FollowModule::t('Comment'),
			'created'    => FollowModule::t('Created date'),
			'updated'    => FollowModule::t('Updated date'),
			'owner_title'=> FollowModule::t('Owner'),
			'verifyCode' => FollowModule::t('Security code'),
			'ip_address' => FollowModule::t('IP address'),
		);
	}

	/**
	 * Before save.
	 */
	public function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->status = $this->defaultStatus;
			$this->ip_address = Yii::app()->request->userHostAddress;
			$this->created = date('Y-m-d H:i:s');
		}
		$this->updated = date('Y-m-d H:i:s');
		return parent::beforeSave();
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('class_name',$this->class_name);
		$criteria->compare('object_pk',$this->object_pk);
		$criteria->compare('status',$this->status);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		if($this->user_id)
			$criteria->compare('user_id',$this->user_id);

		$sort=new CSort;
		$sort->defaultOrder = $this->getTableAlias().'.created DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getStatuses()
	{
		return array(
			self::STATUS_WAITING  => FollowModule::t('Waiting for approval'),
			self::STATUS_APPROVED => FollowModule::t('Confirmed'),
			self::STATUS_SPAM     => FollowModule::t('Spam'),
		);
	}

	/**
	 * @return string status title
	 */
	public function getStatusTitle()
	{
		$statuses = self::getStatuses();
		return $statuses[$this->status];
	}

	/**
	 * @return string
	 */
	public function getOwner_title()
	{
		if(!$this->isNewRecord)
		{
			try{
				$className = Yii::import($this->class_name, true);
			}catch(CException $e){
				return null;
			}

			$model = $className::model()->findByPk($this->object_pk);
			if($model)
				return $model->getOwnerTitle();
		}
		return '';
	}

	public static function truncate(Follow $model, $limit)
	{
		$result = $model->text;
		$length = mb_strlen($result,'utf-8');
		if($length > $limit)
		{
			return mb_substr($result,0,$limit,'utf-8').'...';
		}
		return $result;
	}

	/**
	 * Load object follow
	 * @static
	 * @param CActiveRecord $model
	 * @return array
	 */
	public static function getObjectFollow(CActiveRecord $model)
	{
		return Follow::model()
			->approved()
			->orderByCreatedDesc()
			->findAllByAttributes(array(
				'class_name'=>$model->getClassName(),
				'object_pk'=>$model->id
		));
	}
        
        
}       