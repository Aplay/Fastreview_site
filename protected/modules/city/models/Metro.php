<?php

/**
 * This is the model class for table "metro".
 *
 * The followings are the available columns in table 'metro':
 * @property integer $id
 * @property string $metro_name
 * @property double $metro_lat
 * @property double $metro_lng
 * @property integer $city_id
 *
 * The followings are the available model relations:
 * @property City $city
 */
class Metro extends CActiveRecord
{
	
	const EARTH_RADIUS = 6372795; // Радиус земли
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'metro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id', 'numerical', 'integerOnly'=>true),
			array('metro_lat, metro_lng', 'numerical'),
			array('metro_name, alternative_name, url', 'length', 'max'=>255),
			array('url', 'unique', 'criteria'=>array(
	            'condition'=>'city_id=:city_id',
	            'params'=>array(
	                ':city_id'=>$this->city_id
	            )
	        )),
	        array('active', 'type','type'=>'boolean'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, metro_name, metro_lat, metro_lng, city_id, active', 'safe', 'on'=>'search'),
		);
	}
	public function beforeValidate()
	{
	    if (parent::beforeValidate()) {

	        $validator = CValidator::createValidator('unique', $this, 'url', array(
	            'criteria' => array(
	                'condition'=>'city_id=:city_id',
	                'params'=>array(
	                    ':city_id'=>$this->city_id
	                )
	            )
	        ));
	        $this->getValidatorList()->insertAt(0, $validator); 

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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'metro_name' => 'Metro Name',
			'metro_lat' => 'Metro Lat',
			'metro_lng' => 'Metro Lng',
			'city_id' => 'City',
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
		$criteria->compare('metro_name',$this->metro_name,true);
		$criteria->compare('metro_lat',$this->metro_lat);
		$criteria->compare('metro_lng',$this->metro_lng);
		$criteria->compare('city_id',$this->city_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/*
	 * Расстояние между двумя точками
	 * $org_lat, $org_lng - широта, долгота 1-й точки,
	 * $metro_lat, $metro_lng - широта, долгота 2-й точки
	 * Написано по мотивам http://gis-lab.info/qa/great-circles.html
	 * Михаил Кобзарев <kobzarev@inforos.ru>
	 * точность сравнима с данными проверки через сайт http://livegpstracks.com/default.php?ch=converter
	 */
	public static function distance($org_lat, $metro_lat, $org_lng, $metro_lng){

		$dist = '';
		// перевести координаты в радианы
	    $lat1 = floatval($org_lat) * M_PI / 180;
	    $lat2 = floatval($org_lng) * M_PI / 180;
	    $long1 = floatval($metro_lat) * M_PI / 180;
	    $long2 = floatval($metro_lng) * M_PI / 180;
	 
	    // косинусы и синусы широт и разницы долгот
	    $cl1 = cos($lat1);
	    $cl2 = cos($lat2);
	    $sl1 = sin($lat1);
	    $sl2 = sin($lat2);
	    $delta = $long2 - $long1;
	    $cdelta = cos($delta);
	    $sdelta = sin($delta);
	 
	    // вычисления длины большого круга
	    $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
	    $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;
	 
	    //
	    $ad = atan2($y, $x);
	    $dist = $ad * Metro::EARTH_RADIUS;
	    $dist = round($dist);
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
                $this->url = SlugHelper::run($this->metro_name, 'yandex');
            }
        	return true;
        	
        } else
            return false;
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Metro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
