<?php

/**
 * This is the model class for table "mails".
 *
 * The followings are the available columns in table 'mails':
 * @property integer $id
 * @property string $email
 * @property integer $user_id
 * @property string $message
 * @property string $name
 * @property string $ip
 * @property string $created_date
 * @property integer $type
 */
class Mails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,email,message', 'filter', 'filter' => 'strip_tags'),
	        array('name,email,message', 'filter','filter' =>'trim'),  
			array('user_id, type', 'numerical', 'integerOnly'=>true),
			array('name, email', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>50),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'), 
			array('message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('email, user_id, message, name, ip, created_date, type', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'user_id' => 'User',
			'message' => 'Message',
			'name' => 'Name',
			'ip' => 'Ip',
			'created_date' => 'Created Date',
			'type' => 'Type',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave()
	{
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				$this->ip = Yii::app()->request->userHostAddress;
			}
			return true;
        } else
            return false;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
