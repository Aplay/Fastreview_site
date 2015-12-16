<?php

/**
 * This is the model class for table "district".
 *
 * The followings are the available columns in table 'district':
 * @property integer $id
 * @property string $district_name
 * @property double $district_lat
 * @property double $district_lng
 * @property integer $city_id
 * @property integer $parent_district
 */
class District extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'district';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id, parent_district', 'numerical', 'integerOnly'=>true),
			array('district_lat, district_lng', 'numerical'),
			array('district_name, alternative_name, rodpad, mestpad, socr, url', 'length', 'max'=>255),
			array('district_name', 'unique', 'criteria'=>array(
	            'condition'=>'city_id=:city_id',
	            'params'=>array(
	                ':city_id'=>$this->city_id
	            )
	        )),
	        array('url', 'unique', 'criteria'=>array(
	            'condition'=>'city_id=:city_id',
	            'params'=>array(
	                ':city_id'=>$this->city_id
	            )
	        )),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, district_name, district_lat, district_lng, city_id, parent_district, rodpad, mestpad', 'safe', 'on'=>'search'),
		);
	}

	public function beforeValidate()
	{
	    if (parent::beforeValidate()) {

	        $validator = CValidator::createValidator('unique', $this, 'district_name', array(
	            'criteria' => array(
	                'condition'=>'city_id=:city_id',
	                'params'=>array(
	                    ':city_id'=>$this->city_id
	                )
	            )
	        ));
	        $validator2 = CValidator::createValidator('unique', $this, 'url', array(
	            'criteria' => array(
	                'condition'=>'city_id=:city_id',
	                'params'=>array(
	                    ':city_id'=>$this->city_id
	                )
	            )
	        ));
	        $this->getValidatorList()->insertAt(0, $validator); 
	        $this->getValidatorList()->insertAt(0, $validator2);

	        return true;
	    }
	    return false;
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'parent' => array(self::BELONGS_TO, 'District', 'parent_district'),
			'orgsDistrictD' => array(self::HAS_MANY, 'OrgsDistrict', 'district'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'district_name' => 'District Name',
			'district_lat' => 'District Lat',
			'district_lng' => 'District Lng',
			'city_id' => 'City',
			'parent_district' => 'Parent District',
		);
	}

	public function withUrl($url, $city_id)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'url=:url and city_id=:city_id',
            'params'    => array(':url'=>$url,':city_id'=>$city_id)
        ));
        return $this;
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
		$criteria->compare('district_name',$this->district_name,true);
		$criteria->compare('district_lat',$this->district_lat);
		$criteria->compare('district_lng',$this->district_lng);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('parent_district',$this->parent_district);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getDistricts($org)
	{
		$dist = array();
		if($org->orgsDistrict)
		{
			foreach ($org->orgsDistrict as $val) 
			{
				if($val->districtid->parent_district)
				{
					$dist[1] = $val->districtid->district_name;
				}
				else
				{
					$dist[0] = $val->districtid->district_name;
				}
			}
		}
		return $dist;
	}

	protected function beforeSave() 
    {
        if(parent::beforeSave()) 
        {
        	// Create slug
            if(!$this->url)
            {
                Yii::import('ext.SlugHelper.SlugHelper');
                $this->url = SlugHelper::run($this->district_name, 'yandex');
            }
        	return true;

        } else
            return false;
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return District the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
