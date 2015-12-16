<?php

/**
 * This is the model class for table "orgs_phones".
 *
 * The followings are the available columns in table 'orgs_phones':
 * @property integer $id
 * @property integer $org
 * @property string $phone
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Orgs $org0
 */
class OrgsPhones extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orgs_phones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('org, phone', 'required'),
			array('org, phone, description', 'filter', 'filter' => 'strip_tags'),
            array('org, phone, description', 'filter','filter' =>'trim'),
			array('org', 'numerical', 'integerOnly'=>true),
			array('phone, clear_phone', 'length', 'max'=>255),
			array('description, clear_phone', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, org, phone, description, clear_phone', 'safe', 'on'=>'search'),
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
			'organization' => array(self::BELONGS_TO, 'Orgs', 'org'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'org' => 'Org',
			'phone' => 'Phone',
			'description' => 'Description',
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
		$criteria->compare('org',$this->org);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('clear_phone',$this->clear_phone,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * Strip a Phone number of all non alphanumeric characters
	 */
	public function clearPhone($phone){

		$clear_phone = preg_replace('/\D+/', '', $phone); // \D Match a non-digit character
		return $clear_phone;
	}

	public function beforeSave()
	{
		if(parent::beforeSave()) {
			$this->clear_phone = $this->clearPhone($this->phone);
			return true;
        } else
            return false;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrgsPhones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
