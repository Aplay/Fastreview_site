<?php

/**
 * This is the model class for table "EavProductVariant".
 *
 * The followings are the available columns in table 'EavProductVariant':
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $option_id
 * @property integer $product_id
 * @property float $price
 * @property integer $price_type
 * @property string $sku
 */
class EavProductVariant extends CActiveRecord
{
	/**
     * @var array
     */
    private $attributes = [];

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EavProductVariant the static model class
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
		return 'eav_variants';

	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('product_id', 'required'),
			array('value', 'filter', 'filter' => 'strip_tags'),
            array('value', 'filter', 'filter' => 'trim'),
            array('value', 'match', 'pattern' => '/^[A-Za-zА-Яа-я0-9_.\-]+$/u', 'message' => "Correct symbols (A-zА-я0-9_.-)."),
			array('attribute_id, option_id, product_id, price_type', 'numerical', 'integerOnly'=>true),
			array('price, banch_id', 'numerical'),
			array('sku, value', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, value,  attribute_id, option_id, product_id, price, price_type, sku, banch_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'attribute' => array(self::BELONGS_TO, 'EavOptions', 'attribute_id'),
			'option'    => array(self::BELONGS_TO, 'EavOptionVariants', 'option_id'),
			'banch'     => array(self::BELONGS_TO, 'EavVariantsBanch', 'banch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'attribute_id' => 'Attribute',
			'option_id' => 'Option',
			'product_id' => 'Product',
			'price' => 'Price',
			'price_type' => 'Price Type',
			'sku' => 'Sku',
			'banch_id'=> 'Banch_id'
		);
	}
	
	/**
     * @param $attributeId
     * @param $value
     * @param Objects $product
     * @return bool
     */
    public function store($attributeId, $value, Objects $product, $forcheck = false)
    {
        $attribute = null;
        if (!isset($this->attributes[$attributeId])) {
            $attribute = EavOptions::model()->findByPk($attributeId);
            $this->attributes[$attributeId] = $attribute;
        } else {
            $attribute = $this->attributes[$attributeId];
        }

        switch ($attribute->type) {
            case EavOptions::TYPE_DROPDOWN:
                $this->option_id = empty($value) ? null : (int)$value;
                break;
            case EavOptions::TYPE_CHECKBOX_LIST:

				$this->option_id = empty($value) ? null : (int)$value;
				$this->value = $this->option_id;
				break;
          /*  case EavOptions::TYPE_CHECKBOX:
                $this->value = (bool)$value;
                break;
            case EavOptions::TYPE_NUMBER:
                $this->number_value = empty($value) ? null : (float)$value;
                break;*/
            case EavOptions::TYPE_TEXT:
                $this->value = $value;
                break;
          /*  case EavOptions::TYPE_SHORT_TEXT:
                $this->string_value = $value;
                break;*/
            default:
                throw new InvalidArgumentException('Error attribute!');
        }
        
	        $this->product_id = $product->id;
	        $this->attribute_id = $attribute->id;

	        if($forcheck == true){
	        	return $this->validate();
	        } else {
	        	return $this->save();
	        }
	        
    }

    /**
     * @param null $default
     * @return bool|float|int|null|string
     */
    public function value($default = null)
    {
    	

        switch ($this->attribute->type) {
            case EavOptions::TYPE_DROPDOWN:
                return (int)$this->option_id;
                break;
            case EavOptions::TYPE_CHECKBOX_LIST:
                return (int)$this->option_id;
                break;
          /*  case EavOptions::TYPE_CHECKBOX:
                return (bool)$this->number_value;
                break;
            case EavOptions::TYPE_NUMBER:
                return (float)$this->number_value;
                break;*/
            case EavOptions::TYPE_TEXT:
                return $this->value;
                break;
          /*  case EavOptions::TYPE_SHORT_TEXT:
                return $this->string_value;
                break;*/
            default:
                return $default;
        }
    }
}