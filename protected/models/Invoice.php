<?php

/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $id
 * @property integer $user_id
 * @property string $amount
 * @property integer $invoice_id
 * @property string $created_date
 * @property integer $org_id
 * @property integer $status
 */
class Invoice extends CActiveRecord
{
	
	
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice';
	}

	public function scopes()
	{
		
		$alias = $this->getTableAlias(true);
                
		return array(

			'active'=>array(
				'condition'=>$alias.'.status = '.self::STATUS_ACTIVE,
			),
			'notactive'=>array(
				'condition'=>$alias.'.status= '.self::STATUS_INACTIVE,
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
			array('user_id, amount, invoice_id, org_id, period, period_type, sum, sum_discount', 'required'),
			array('user_id, invoice_id,  org_id, status, period, period_type, promo_id', 'numerical', 'integerOnly'=>true),
			array('amount, sum, sum_discount', 'numerical', 'integerOnly' => false, 'min' => 0.01),
			array('discount', 'numerical', 'integerOnly' => false, 'min' => 0),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	        array('paid_at, paid_till',  'safe'),   
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, amount,  invoice_id, created_date, org_id, status', 'safe', 'on'=>'search'),
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
			'org' => array(self::BELONGS_TO, 'Orgs', 'org_id'),
			'promo' => array(self::BELONGS_TO, 'Promo', 'promo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Id Юзера',
			'amount' => 'Стоимость, руб.',
			'invoice_id' => 'Тариф',
			'created_date' => 'Дата',
			'org_id' => 'Id Компании',
			'status' => 'Статус',
			'period'=>'Период',
			'period_type'=>'',
			'sum'=>'Сумма, руб.',
			'promo_id'=>'Промо код',
			'discount'=>'Скидка, %',
			'sum_discount'=>'Сумма со скидкой, руб.'

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
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('invoice_id',$this->invoice_id);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('org_id',$this->org_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'created_date DESC'
			)
		));
	}


	 /**
	 * @return array
	 */
	public static function getStatusNames()
	{
		return array(
			self::STATUS_ACTIVE=>'Оплачено',
			self::STATUS_INACTIVE=>'Не оплачено',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
