<?php

/**
 * Store type attributes
 * This is the model class for table "EavTypeAttribute".
 *
 * The followings are the available columns in table 'EavTypeAttribute':
 * @property integer $id
 * @property integer $type_id
 * @property integer $attribute_id
 */
class EavTypeAttribute extends BaseModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EavTypeAttribute the static model class
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
		return 'eav_type_attribute';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'attribute'=>array(self::BELONGS_TO, 'EavOptions', 'attribute_id'),
		);
	}


}