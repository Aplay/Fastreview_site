<?php

/**
 * This is the model class for table "objects_http".
 *
 * The followings are the available columns in table 'objects_http':
 * @property integer $int
 * @property integer $object
 * @property string $site
 * @property integer $user_id
 */
class ObjectsHttp extends CActiveRecord
{
	const TYPE_LINK = 0;
	const TYPE_VIDEO = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'objects_http';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object', 'required'),
			array('object, user_id', 'numerical', 'integerOnly'=>true),
			array('site', 'length', 'max'=>255),
			array('site', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	            
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('int, object, site, user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'objectid'=>array(self::BELONGS_TO, 'Objects', 'object'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'object' => 'Object',
			'site' => 'Site',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('object',$this->object);
		$criteria->compare('site',$this->site,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ObjectsHttp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
