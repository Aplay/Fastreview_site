<?php

/**
 * This is the model class for table "promo".
 *
 * The followings are the available columns in table 'promo':
 * @property integer $id
 * @property string $promo
 * @property string $description
 * @property integer $status
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property InvoicePromo[] $invoicePromos
 */
class Promo extends CActiveRecord
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promo';
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
			array('promo, description', 'filter', 'filter' => 'strip_tags'),
	        array('promo, description', 'filter','filter' =>'trim'),
			array('promo', 'required'),
			array('promo', 'checkIfAvailableNoCase'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('promo', 'length', 'max'=>255),
			array('description', 'safe'),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	        
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, promo, description, status, created_date', 'safe', 'on'=>'search'),
		);
	}

	public function checkIfAvailableNoCase($attr)
    {
        $labels = $this->attributeLabels();
        $id = (int)$this->id;
        $check = Promo::model()->find(array('condition'=>'id !=:id  and LOWER('.$attr.')=:promo','params'=>array(':id'=>$id,':promo'=>MHelper::String()->toLower($this->$attr))));
        if($check)
            $this->addError($attr, 'Такой промо-код уже существует');
    } 
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'invoicePromos' => array(self::HAS_MANY, 'InvoicePromo', 'promo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'promo' => 'Промо код',
			'description' => 'Описание',
			'status' => 'Статус',
			'created_date' => 'Дата',
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
		$criteria->compare('promo',$this->promo,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_date',$this->created_date,true);

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
			self::STATUS_INACTIVE=>'Не опубликовано',
			self::STATUS_ACTIVE=>'Опубликовано',
			
		);
	}

	public static function getPromos()
    {
    	$promos = Promo::model()->active()->findAll(array('order'=>'promo ASC'));
    	$promos = CHtml::listData($promos, 'id','promo');
        return $promos;
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Promo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
