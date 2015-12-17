<?php

/**
 * This is the model class for table "objects_rating".
 *
 * The followings are the available columns in table 'objects_rating':
 * @property integer $id
 * @property integer $object
 * @property integer $vote_count
 * @property double $vote_average
 * @property integer $vote_sum
 *
 * The followings are the available model relations:
 */
class ObjectsRating extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'objects_rating';
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
			array('object, vote_count, vote_sum', 'numerical', 'integerOnly'=>true),
			array('vote_average', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, object, vote_count, vote_average, vote_sum', 'safe', 'on'=>'search'),
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
			'objects' => array(self::HAS_MANY, 'Objects', 'rating_id'),
			'object0' => array(self::BELONGS_TO, 'Objects', 'object'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'object' => 'Объект',
			'vote_count' => 'Vote Count',
			'vote_average' => 'Vote Average',
			'vote_sum' => 'Vote Sum',
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
		$criteria->compare('object',$this->object);
		$criteria->compare('vote_count',$this->vote_count);
		$criteria->compare('vote_average',$this->vote_average);
		$criteria->compare('vote_sum',$this->vote_sum);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ObjectsRating the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
