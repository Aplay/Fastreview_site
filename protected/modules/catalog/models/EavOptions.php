<?php



/**
 * This is the model class for table "eav_options".
 *
 * The followings are the available columns in table 'EavOptions':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $type
 * @property integer $position
 * @property boolean $s
 * @method Categories useInFilter()
 */
class EavOptions extends BaseModel
{

	const TYPE_TEXT          = 1;
	const TYPE_TEXTAREA      = 2;
	const TYPE_DROPDOWN      = 3;
	const TYPE_SELECT_MANY   = 4;
	const TYPE_RADIO_LIST    = 5;
	const TYPE_CHECKBOX_LIST = 6;
	const TYPE_YESNO         = 7;

	/**
	 * @var string attr name
	 */
	public $title;
	public $options_ar;


	/**
	 * @var string
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EavOptions the static model class
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
		return 'eav_options';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, title, unit', 'filter', 'filter' => 'strip_tags'),
            array('name, title, unit', 'filter', 'filter' => 'trim'),
			array('type, title', 'required'),
			array('required', 'boolean'),
			array('group_id, unit', 'default', 'value' => NULL, 'setOnEmpty' => true),
			array('name', 'match',
				'pattern'=>'/^([a-z0-9._-])+$/i',
				'message'=>'Идентификатор может содержать только латинские буквы, цифры и подчеркивания.'
			),
			 array('title, name', 'checkUniqueLabel'),
			array('type, position, group_id', 'numerical', 'integerOnly'=>true),
			array('name, title', 'length', 'max'=>255),
			array('unit', 'length', 'max' => 30),
			array('options_ar', 'safe'),
			array('id, name, title, type, group_id, unit', 'safe', 'on'=>'search'),
		);
	}

	public function checkUniqueLabel($attribute,$params)
 	{
 		if(!$this->$attribute)
 		  	return true;
		$name = MHelper::String()->simple_ucfirst($this->$attribute);
		if($this->group_id == null){
		  	$condition = 'LOWER('.$attribute.')=:label  AND group_id is null';
		  	$params = array(
		      	':label'=>MHelper::String()->toLower($name), 
		      	);
	  	} else {
	  		$condition = 'LOWER('.$attribute.')=:label  AND group_id=:group_id';
	  		$params = array(
		      	':label'=>MHelper::String()->toLower($name), 
		      	':group_id'=>$this->group_id
		      	);
	  	}
	  	if($this->id){
	  		$condition .= ' AND id!='.$this->id;
	  	}
	  	$cnt = EavOptions::model()->count($condition, $params);

		  if($cnt > 0)
		  { 
		  	  $this->addError($attribute, $this->getAttributeLabel($attribute).' уже существует');
		      return false;
		  } else { 
		  	  return true;
		  }
 	}



	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			//'attr_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
			//'options'        => array(self::HAS_MANY, 'ProductOptionVariants', 'attribute_id', 'order'=>'options.position ASC', 'scopes'=>'applyTranslateCriteria'),
            'options'        => array(self::HAS_MANY, 'EavOptionVariants', 'attribute_id'),
			// Used in types
			'types'          => array(self::HAS_MANY, 'EavTypeAttribute', 'attribute_id'),
			'group' => array(self::BELONGS_TO, 'EavOptionsGroup', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'               => 'ID',
			'name'             => 'Идентификатор',
			'title'            => 'Название',
			'type'             => 'Тип',
			'position'         => 'Позиция',
			'required'         => 'Обязательно к заполнению',
			'group_id' => 'Группа',
            'unit' => 'Единица измерения',

		);
	}

	/**
	 * Get types as key value list
	 * @static
	 * @return array
	 */
	public static function getTypesList()
	{
		return array(
			self::TYPE_TEXT           => 'Текст или число',
			/// self::TYPE_TEXTAREA       => 'Textarea',
			// self::TYPE_DROPDOWN       => 'Dropdown',
			// self::TYPE_SELECT_MANY    => 'Multiple Select',
			// self::TYPE_RADIO_LIST     => 'Radio List',
			self::TYPE_CHECKBOX_LIST  => 'Чекбоксы',
			// self::TYPE_YESNO          => 'Yes/No',
		);
	}

	/**
	 * Get type label
	 * @static
	 * @param $type
	 * @return string
	 */
	public static function getTypeTitle($type)
	{
		$list = self::getTypesList();
		return $list[$type];
	}

	/**
     * @return array
     */
    public static function getTypesWithOptions()
    {
        return [self::TYPE_CHECKBOX_LIST];
    }

    /**
     * @param $type
     * @return bool
     */
    public function isType($type)
    {
        return $type == $this->type;
    }
	/**
	 * @return string html field based on attribute type
	 */
/*	public function renderField($value = null)
	{
		$name = 'EavOptions['.$this->name.']';
		switch ($this->type):
			case self::TYPE_TEXT:
				return CHtml::textField($name, $value,array('style'=>'padding-left:5px;width:200px;margin-top:12px;'));
			break;
			case self::TYPE_TEXTAREA:
				return CHtml::textArea($name, $value);
			break;
			case self::TYPE_DROPDOWN:
				$data = CHtml::listData($this->options, 'id', 'value');
				return CHtml::dropDownList($name, $value, $data, array('empty'=>$this->title,'class'=>'chzn','style'=>"width:290px"));
			break;
			case self::TYPE_SELECT_MANY:
				$data = CHtml::listData($this->options, 'id', 'value');
				return CHtml::dropDownList($name.'[]', $value, $data, array('multiple'=>'multiple','class'=>'chzn', 'style'=>"width:200px", 'data-placeholder'=>"Выберите размер"));
			break;
			case self::TYPE_RADIO_LIST:
				$data = CHtml::listData($this->options, 'id', 'value');
				return CHtml::radioButtonList($name, (string)$value, $data, array('separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'));
			break;
			case self::TYPE_CHECKBOX_LIST:
				$data = CHtml::listData($this->options, 'id', 'value');
				return CHtml::checkBoxList($name.'[]', $value, $data,array('separator'=>''));
			break;
			case self::TYPE_YESNO:
				$data = array(
					1=>'Да',
					2=>'Нет'
				);
				return CHtml::dropDownList($name, $value, $data, array('empty'=>'---'));
			break;
		endswitch;
	}
*/
	/**
	 * Get attribute value
	 * @param $value
	 * @return string attribute value
	 */
	 public function renderValue($value, $gkey)
	{

		switch ($this->type):
			case self::TYPE_TEXT:
			case self::TYPE_TEXTAREA:
				$params = array('gfilter'=>$gkey.':'.$value);
				$url = Yii::app()->createAbsoluteUrl('fastreview/view').'?'.http_build_query($params, '', '&');
				return CHtml::link($value,$url);
			break;
			case self::TYPE_DROPDOWN:
			case self::TYPE_RADIO_LIST:
				$data = CHtml::listData($this->options, 'id', 'value');
				if(!is_array($value) && isset($data[$value]))
					return $data[$value];
			break;
			case self::TYPE_SELECT_MANY:
			case self::TYPE_CHECKBOX_LIST:
				$data = CHtml::listData($this->options, 'id', 'value');
				$result = array();

				if(!is_array($value))
					$value = array($value);

				foreach($data as $key=>$val)
				{

					if(in_array($key, $value)){
						$params = array('gfilter'=>$gkey.':'.$key);
						$url = Yii::app()->createAbsoluteUrl('fastreview/view').'?'.http_build_query($params, '', '&');
						$result[] = CHtml::link($val,$url);
					}
				}
				return implode(', ', $result);
			break;
			case self::TYPE_YESNO:
				$data = array(
					1=>'Да',
					2=>'Нет'
				);
				if(isset($data[$value]))
					return $data[$value];
			break;
		endswitch;
	} 
        
        /**
	 * @return string html id based on name
	 */
	public function getIdByName()
	{
		$name = 'EavOptions['.$this->name.']';
		return CHtml::getIdByName($name);
	}
        
	

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($additionalCriteria = null)
	{
		$criteria = new CDbCriteria;
		if($additionalCriteria !== null)
			$criteria->mergeWith($additionalCriteria);
	

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.type',$this->type);
		$criteria->compare('t.position',$this->position);

		$sort = new CSort;
		$sort->defaultOrder = 't.title ASC';
		$sort->attributes=array(
			'*',
			'title' => array(
				'asc'   => 't.title',
				'desc'  => 't.title DESC',
			),
		);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	public function withName($name, $alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.name=:name',
            'params'    => array(':name'=>$name)
        ));
        return $this;
    }

	protected function checkUniqueUrl($unique){
        // Check if url available
            if($this->isNewRecord)
            {
                $test = EavOptions::model()
                    ->withName($unique)
                    ->count();
            }
            else
            {
                $test = EavOptions::model()
                    ->withName($unique)
                    ->count('id!=:id', array(':id'=>$this->id));
            }
            return $test;
    }

	public function beforeSave()
	{
		if(parent::beforeSave()) {
			// Create slug
			Yii::import('ext.SlugHelper.SlugHelper');
	        if(!$this->name){
	           $this->name = SlugHelper::run($this->title, 'yandex');
	        } else {
	           $this->name = SlugHelper::run($this->name, 'yandex');
	        }
	        $unique = $this->name;

            $suffix = 1;
            while ($this->checkUniqueUrl($unique) > 0){
                $unique = $this->name.$suffix;
                $suffix++;
            }
            $this->name =  $unique;

          	return true;

		} else
            return false;

	}

	public function afterDelete()
	{
		// Delete options
		foreach($this->options as $o)
			$o->delete();

		// Delete relations used in product type.
		EavTypeAttribute::model()->deleteAllByAttributes(array('attribute_id'=>$this->id));

		// Delete attributes assigned to products
		$conn = $this->getDbConnection();
		$command = $conn->createCommand("DELETE FROM eav_variants WHERE attribute_name='{$this->name}'");
		$command->execute();

		return parent::afterDelete();
	}

	public function saveOptions(array $options)
	{
		$dontDelete = array();
		if(!empty($options))
		{
			$position = 0;
			foreach($options as $key=>$val)
			{

				if(!empty($val))
				{
					$index = 0;

					$attributeOption = EavOptionVariants::model()
						->findByAttributes(array(
							'id'=>$key,
							'attribute_id'=>$this->id
						));

					if(!$attributeOption)
					{
						$attributeOption = new EavOptionVariants;
						$attributeOption->attribute_id = $this->id;
					}
					$attributeOption->position = $position;
					$attributeOption->save(false);

					
						$attributeOption = EavOptionVariants::model()
							//->language($lang->id)
							->findByAttributes(array(
								'id'=>$attributeOption->id
							));
						$attributeOption->value = $val;
						$attributeOption->save(false);

						++$index;
					

					array_push($dontDelete, $attributeOption->id);

					$position++;
				}
			}
		}

		if(sizeof($dontDelete))
		{
			$cr = new CDbCriteria;
			$cr->addNotInCondition('t.id', $dontDelete);
			$optionsToDelete = EavOptionVariants::model()->findAllByAttributes(array(
				'attribute_id'=>$this->id
			), $cr);
		}
		else
		{
			// Clear all attribute options
			$optionsToDelete = EavOptionVariants::model()->findAllByAttributes(array(
				'attribute_id'=>$this->id
			));
		}

		if(!empty($optionsToDelete))
		{
			foreach($optionsToDelete as $o)
				$o->delete();
		}
	}
}