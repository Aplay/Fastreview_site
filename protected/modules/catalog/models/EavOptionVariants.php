<?php


/**
 * Store options for dropdown and multiple select
 * This is the model class for table "eav_option_variants".
 *
 * The followings are the available columns in table 'EavOptionVariants':
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 * @property integer $position
 */
class EavOptionVariants extends BaseModel
{


	/**
	 * @var string multilingual attr
	 */
	public $value;
        public $classname;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CActiveRecord the static model class
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
		return 'eav_option_variants';
	}




}