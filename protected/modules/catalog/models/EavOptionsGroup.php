<?php

/**
 * This is the model class for table "eav_options_group".
 *
 * @property integer $id
 * @property string $name
 * @property integer $position
 *
 * @property Options[] groupOptions
 */
class EavOptionsGroup extends BaseModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'eav_options_group';
    }

    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'filter', 'filter' => 'strip_tags'),
            array('name', 'filter', 'filter' => 'trim'),
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
            array('name', 'checkUniqueLabel'),
            array('id, name, position', 'safe', 'on' => 'search'),
        );
    }

    public function checkUniqueLabel($attribute,$params)
    {

          $name = MHelper::String()->simple_ucfirst($this->$attribute);
          $condition = 'LOWER('.$attribute.')=:label';
          if($this->id){
            $condition .= ' AND id!='.$this->id;
          }
          $cnt = EavOptionsGroup::model()->count($condition,
              array(
                ':label'=>MHelper::String()->toLower($name), 
                ));

          if($cnt > 0)
          { 
              $this->addError($attribute, $this->getAttributeLabel($attribute).' уже существует');
              return false;
          } else { 
              return true;
          }
    }

    public function relations()
    {
        return array(
            'groupAttributes' => array(self::HAS_MANY, 'EavOptions', 'group_id',  'order'=>'"groupAttributes"."title" ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название группы',
            'position' => 'Позиция',
        );
    }

    public function behaviors()
    {
        return array(
            'sortable' => array(
                'class' => 'application.components.behaviors.SortableBehavior'
            )
        );
    }


    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('position', $this->position);

        return new CActiveDataProvider(
            $this, array(
                'criteria' => $criteria,
                'sort' => array('defaultOrder' => 't.position')
            )
        );
    }

    public function getFormattedList()
    {
        $groups = $this->findAll(array('order' => 'name ASC'));
        $list = array();
        foreach ($groups as $key => $group) {
            $list[$group->id] = $group->name;
        }
        return $list;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
