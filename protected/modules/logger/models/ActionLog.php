<?php

Yii::import('application.modules.logger.LoggerModule');

/**
 * Saves admin logs
 * This is the model class for table "ActionLog".
 *
 * The followings are the available columns in table 'ActionLog':
 * @property integer $id
 * @property string $username
 * @property string $event
 * @property string $model_name
 * @property string $model_title
 * @property string $datetime
 */
class ActionLog extends BaseModel
{

	/**
	 * Actions
	 */
	const ACTION_CREATE = 1;
	const ACTION_UPDATE = 2;
	const ACTION_DELETE = 3;

	public $search_user;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActionLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'actionlog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, event, model_id', 'numerical', 'integerOnly'=>true),
			array('model_name, model_title', 'length', 'max'=>255),
			array('datetime', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
			array('id, user_id, event, model_name, model_title, datetime, search_user', 'safe', 'on'=>'search'),
		);
	}
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'userid' => array(self::BELONGS_TO, 'User', 'user_id'),
			'org' => array(self::BELONGS_TO, 'Orgs', 'model_id'),
			'cat' => array(self::BELONGS_TO, 'Category', 'model_id'),
			);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'event' => 'Действие',
			'model_name' => 'Обьект',
			'model_title' => 'Название',
			'datetime' => 'Дата',
			'search_user'=>'Пользователь'
		);
	}

	/**
	 * @return mixed
	 */
	public function getActionTitle()
	{
		if($this->event)
		{
			return $this->eventNames[$this->event];
		}
	}

	/**
	 * @return array
	 */
	public static function getEventNames()
	{
		return array(
			self::ACTION_CREATE=>'Добавление',
			self::ACTION_UPDATE=>'Обновление',
			self::ACTION_DELETE=>'Удаление',
		);
	}

	public function getHumanModelName()
	{
		return $this->logClasses[$this->model_name]['human_name'];
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getLogClasses()
	{
		return array(
			'Orgs'=>array(
				'title_attribute'=>'title',
				'human_name'=>'Организация'
			),
			'Category'=>array(
				'title_attribute'=>'title',
				'human_name'=>'Рубрика'
			),
		);
	}

	public function getModelNameFilter()
	{
		$result = array();
		foreach($this->logClasses as $class=>$data)
			$result[$class]=$data['human_name'];
		return $result;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($additionalCriteria = null)
	{
		$criteria=new CDbCriteria;

		$criteria->with = array( 'userid'=>array('together'=>true) );

		if($additionalCriteria !== null)
			$criteria->mergeWith($additionalCriteria);

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('event',$this->event);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('model_title',$this->model_title,true);
		// $criteria->compare('datetime',$this->datetime,true);
		
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('LOWER(userid.username)', MHelper::String()->toLower($this->search_user), true);
		if(!empty($this->datetime)){
			$criteria->addCondition("datetime >='" . date('Y-m-d 00:00:00', strtotime($this->datetime)) . "'");
			$criteria->addCondition("datetime <='" . date('Y-m-d 23:59:59', strtotime($this->datetime)) . "'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder' => 't.datetime DESC',
				'attributes'=>array(
		            'search_user'=>array(
		                'asc'=>'userid.username ASC',
		                'desc'=>'userid.username DESC',
		            ),
		            '*',
		        ))
		));
	}
}