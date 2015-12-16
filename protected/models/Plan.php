<?php

/**
 * This is the model class for table "plan".
 *
 * The followings are the available columns in table 'plan':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $amount
 * @property string $created_date
 * @property integer $status
 */
class Plan extends CActiveRecord
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;

	const PERIOD_DAYS = 1;
	const PERIOD_WEEKS = 7;
	const PERIOD_MONTH = 30;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'plan';
	}

	public function scopes()
	{
		
		$alias = $this->getTableAlias(true);
                
		return array(
			'active'=>array(
				'condition'=>$alias.'.status = '.self::STATUS_ACTIVE,
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, amount, text, period, period_type', 'required'),
			array('title, description, amount, text, period, position', 'filter', 'filter' => 'strip_tags'),
	        array('title, description, amount, text, period, position', 'filter','filter' =>'trim'),
			array('status, amount, period, period_type, position', 'numerical', 'integerOnly'=>true),
			array('title, description', 'length', 'max'=>255),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	        array('text','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, amount, created_date, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'amount' => 'Цена, руб.',
			'created_date' => 'Created Date',
			'status' => 'Status',
			'period'=>'Период',
			'period_type'=>'',
			'position'=>'Позиция'
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	 /**
	 * @return array
	 */
	public static function getStatusNames()
	{
		return array(
			self::STATUS_ACTIVE=>'Опубликовано',
			self::STATUS_INACTIVE=>'Не опубликовано',
		);
	}
	
	 /**
	 * @return array
	 */
	public static function getPeriods()
	{
		return array(
			self::PERIOD_DAYS=>'Дней',
			self::PERIOD_WEEKS=>'Недель',
			self::PERIOD_MONTH=>'Месяцев',
		);
	}

	 /**
	 * @return array
	 */
	public static function getPlanTime($period_type)
	{
		$ar = array(
			self::PERIOD_DAYS=>'день',
			self::PERIOD_WEEKS=>'неделю',
			self::PERIOD_MONTH=>'месяц',
		);
		if(isset($ar[$period_type]))
			return $ar[$period_type];
		return null;
	}

	 /**
	 * @return array
	 */
	public static function getPlanSclon($period_type)
	{
		$ar = array(
			self::PERIOD_DAYS=>'day|days',
			self::PERIOD_WEEKS=>'week|weeks',
			self::PERIOD_MONTH=>'month|months',
		);
		if(isset($ar[$period_type]))
			return $ar[$period_type];
		return null;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Plan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
