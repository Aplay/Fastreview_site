<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $country_id
 * @property string $name_en
 * @property integer $maxmind_id
 * @property integer $district
 * @property integer $region
 * @property string $postal_code
 * @property double $latitude
 * @property double $longitude
 * @property integer $pos
 * @property integer $diff
 * @property integer $population
 * @property string metrika
 *
 * The followings are the available model relations:
 * @property Orgs[] $orgs
 */
class City extends CActiveRecord
{
	public $tmpFiles;
	public $addcoordinates = true;
	public $area;
	public $lat;
	public $lng;
	public $orgs_count;
	public $orgs_count_notactive;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('country_id, maxmind_id, district, region, pos, diff, population, utcdiff, updated, parent_area, id_parent', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('title, url, name_en, filename, realname, rodpad, mestpad, alternative_title, yandex_title', 'length', 'max'=>255),
           // array('title','unique', 'message'=>'Город с таким названием уже существует.'),
            array('title', 'unique', 'criteria'=>array(
	            'condition'=>'region=:region',
	            'params'=>array(
	                ':region'=>$this->region
	            )
	        )),
			array('metrika', 'type', 'type'=>'string'),
			array('postal_code, tmpFiles', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, url, country_id, name_en, maxmind_id, district, region, postal_code, latitude, longitude, pos, diff, population, filename, realname, rodpad, utcdiff, mestpad, alternative_title, orgs_count, orgs_count_notactive', 'safe', 'on'=>'search'),
		);
	}

	public function beforeValidate()
	{
	    if (parent::beforeValidate()) {

	        $validator = CValidator::createValidator('unique', $this, 'title', array(
	            'criteria' => array(
	                'condition'=>'region=:region',
	                'params'=>array(
	                    ':region'=>$this->region
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
			'orgs' => array(self::HAS_MANY, 'Orgs', 'city_id'),
			'regionid'=>array(self::BELONGS_TO, 'Region', 'region'),
			'cityOther' => array(self::HAS_ONE, 'CityOther', 'city_id'),
			'parent' => array(self::BELONGS_TO, 'City', 'id_parent'),
			'parentarea'=>array(self::BELONGS_TO, 'City', 'parent_area'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'url' => 'Название для урл',
			'country_id' => 'Country',
			'name_en' => 'Name En',
			'maxmind_id' => 'Maxmind',
			'district' => 'District',
			'region' => 'Регион',
			'postal_code' => 'Postal Code',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'pos' => 'Позиция',
			'diff' => 'Diff',
			'population' => 'Population',
			'filename' => 'Картинка',
			'tmpFiles' => 'Картинка',
			'rodpad'=>'Родительный падеж',
			'utcdiff'=>'Разница UTC',
			'mestpad'=>'Местный падеж',
			'metrika'=>'Счетчик метрики'
		);
	}
	public function behaviors()
	{
		return array(
		    'fileBehavior'=> array(
          		'class' => 'application.components.behaviors.FileBehavior',
          		'attribute' => 'filename',
          		'cap'=>'/img/russia2.jpg'
          	),
  
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

	/*	$criteria->join="LEFT JOIN (SELECT DISTINCT org, city_id, status_org FROM orgs_category) oco ON t.id = oco.city_id";
        $criteria->select=array('t.*','COUNT(CASE WHEN oco.status_org=1 THEN 1 END) AS orgs_count,COUNT(CASE WHEN oco.status_org=2 THEN 1 END) AS orgs_count_notactive ');
	    $criteria->group='t.id';
	*/

		$criteria->compare('id',$this->id);
		$criteria->compare('LOWER(title)',MHelper::String()->toLower($this->title),true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('maxmind_id',$this->maxmind_id);
		$criteria->compare('district',$this->district);
		$criteria->compare('region',$this->region);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('diff',$this->diff);
		$criteria->compare('population',$this->population);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('realname',$this->realname,true);
		$criteria->compare('rodpad',$this->rodpad,true);
		$criteria->compare('alternative_title',$this->alternative_title,true);
	    

	    $criteria->addCondition('pos is not null');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
			  //  'defaultOrder'=>'pos = 0, pos ASC, title',
				'defaultOrder'=>'title',
				/*'attributes'=>array(
					'orgs_count'=>array(
		                'asc' => 'orgs_count ASC',
		                'desc' => 'orgs_count DESC',
	            	),
	            	'orgs_count_notactive'=>array(
		                'asc' => 'orgs_count_notactive ASC',
		                'desc' => 'orgs_count_notactive DESC',
	            	),
	            	'*'
	            	),*/
			  ),
			'pagination' => array(
                'pageSize' => 50,
            ),

		));
	}



	public function addDropboxFiles($uploadsession)
    {
        $files = $this->tmpFiles;

        if($files){

            if(Yii::app()->session->itemAt($uploadsession)){

                $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
                

                foreach($files as $fileUploadName){

                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {

                                    $file = $folder.$value;
                                    $ext = pathinfo($folder.$value, PATHINFO_EXTENSION);

                                    $base = md5(rand(1000,4000));
                                    $unique = $base.'_city';
                                    $suffix = 1;

                                    while (file_exists($this->getFileFolder() . $unique . $ext)){
                                        $unique = $base.'_'.$suffix;
                                        $suffix++;
                                    }
                                    $filename =  $unique . '.' . $ext;
                                    
                                    if (copy($folder.$value, $this->getFileFolder() . $filename)) {
                                        unlink($folder.$value);
                                        $this->filename = $filename;
                                        $this->realname = $key;
                                        $this->save(true,array('filename','realname'));
                                    } else {
                                       // throw new CHttpException(404, Yii::t('Site', 'Cannot copy file to folder.'));
                                    }
                                    

                                }
                            break;
                            }
                        }
                    }

                    
                }
            }
            
        }
    }
   /* public function getFileFolder()
    {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/city/'.$this->id.'/';
        if (is_dir($folder) == false)
            mkdir($folder, 0755, true);
        return $folder;
    }

    public function getOrigFilePath() {
        return Yii::app()->baseUrl.'/uploads/city/'.$this->id.'/';
    }
*/
    protected function getDeleteFileFolder() {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/city/'.$this->id;
        if (is_dir($folder) == true)
        	CFileHelper::removeDirectory($folder);
           // rmdir($folder);
        return true;
    }

    public function getDistrictLinks($rubr,$module = 'catalog')
    {
    	$districts = District::model()->findAll(array('condition'=>'city_id=:city_id','params'=>array(':city_id'=>$this->id)));
   		$links = '';
   		if($districts)
   		{
   			foreach ($districts as $district) 
   			{
   				if((mb_strpos($district->district_name, 'район', 0, 'UTF-8') !== false) && (mb_strpos($district->district_name, 'микрорайон', 0, 'UTF-8') === false))
				{
					$url = Yii::app()->createAbsoluteUrl('/'.$module.'/catalog/district', array('city'=>$this->url,  'url'=>$rubr->url, 'district'=>'rayon'));
					$links .= '<a class="parentCategoryElement" href="'.$url.'">'.$rubr->title.' по районам города</a>';
					break;
				}
   			}
   			foreach ($districts as $district) 
   			{
   				if(mb_strpos($district->district_name, 'микрорайон', 0, 'UTF-8') !== false )
				{
					$url = Yii::app()->createAbsoluteUrl('/'.$module.'/catalog/district', array('city'=>$this->url,  'url'=>$rubr->url, 'district'=>'mikrorayon'));
					$links .= '<a class="parentCategoryElement" href="'.$url.'">'.$rubr->title.' по микрорайонам города</a>';
					break;
				}
   			}
   			foreach ($districts as $district) 
   			{
   				if(mb_strpos($district->district_name, 'округ', 0, 'UTF-8') !== false )
				{
					$url = Yii::app()->createAbsoluteUrl('/'.$module.'/catalog/district', array('city'=>$this->url,  'url'=>$rubr->url, 'district'=>'okrug'));
					$links .= '<a class="parentCategoryElement" href="'.$url.'">'.$rubr->title.' по округам</a>';
					break;
				}
   			}

   		}
   		 if($module == 'catalog'){
	   		$metros = Metro::model()->find(array('condition'=>'city_id=:city_id','params'=>array(':city_id'=>$this->id)));
	   		if($metros)
	   		{
	   			$url = Yii::app()->createAbsoluteUrl('/'.$module.'/catalog/district', array('city'=>$this->url,  'url'=>$rubr->url, 'district'=>'metro'));
				$links .= '<a class="parentCategoryElement" href="'.$url.'">'.$rubr->title.' по станциям метро</a>';
	   		}
	   	}
   		return $links;
    }
    
	public static function getBigCities()
    {
    	$cr = new CDbCriteria;
    	$cr->with = array('regionid'=>array(
    		// 'select'=>'regionid.title as regtitle',
    	));
        $cr->together = true;
    	$cr->condition = 't.pos>0';
    	$cr->order = 't.title';
    	$cities = City::model()->findAll($cr);
    	// $cities = CHtml::listData($cities, 'id','title');
    	foreach ($cities as $city) {
    		$citieslist[$city->id] = $city->title;
    		if($city->regionid)
    		{
				$citieslist[$city->id] = $city->title. ' ('.$city->regionid->title.')';
    		}
    	}
    //	VarDumper::dump($citieslist); die(); // Ctrl + X	Delete line
       return $citieslist;
    }
    public static function getAllCities()
    {
    	$cities = City::model()->findAll(array('order'=>'title'));
    	$cities = CHtml::listData($cities, 'id','title');
      return $cities;
    }

    public static function getRegions()
    {
    	$regions = Region::model()->findAll(array('order'=>'title'));
    	$regions = CHtml::listData($regions, 'id','title');
      	return $regions;
    }

    public function withUrl($url, $alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.url=:url',
            'params'    => array(':url'=>$url)
        ));
        return $this;
    }

    protected function checkUniqueUrl($unique){
        // Check if url available
            if($this->isNewRecord)
            {
                $test = City::model()
                    ->withUrl($unique)
                    ->count();
            }
            else
            {
                $test = City::model()
                    ->withUrl($unique)
                    ->count('id!=:id', array(':id'=>$this->id));
            }
            return $test;
    }

    protected function beforeSave() 
    {
        if(parent::beforeSave()) 
        {

        	if($this->title)
        	{
				if($this->addcoordinates == true)
				{
					$address = '';
					if($this->region)
					{
						$address .= $this->regionid->title.', ';
					}
					$address .= $this->title;
					$params = array(
					    'geocode' => $address, // адрес
					    'format'  => 'json',                          // формат ответа
					    'results' => 1,                               // количество выводимых результатов
					   // 'kind'=>'locality'
					);
					$response = json_decode(@file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));

					$result = ''; 
					if ($response && isset($response->response) && $response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
					{
					    $result = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
					    if($result){
					    	$exp_str1 = explode(" ", $result);
							$this->lat = $exp_str1[1];
							$this->lng = $exp_str1[0]; 
							
						    $this->yanData();
					    }
					}
					
				}
			}
            // Create slug
            if(!$this->url)
            {
                Yii::import('ext.SlugHelper.SlugHelper');
                $this->url = SlugHelper::run($this->title, 'yandex');
            }

            $unique = $this->url;

	        $suffix = '';
	        if($this->region)
	        {
	        	$suffix .= SlugHelper::run('-'.$this->region, 'yandex');
	        } 

	        $addsuffix = 0;
	        while ($this->checkUniqueUrl($unique) > 0)
	        {
	            if($addsuffix == 0)
	            {
	            	$unique = $this->url.$suffix; // добавляется регион
	            } else {
	            	$unique = $this->url.$suffix.'-'.$addsuffix; // если региона нет - цифра
	            }
	            $addsuffix++;
	        }
	        $this->url =  $unique;

            return true;
        } else
            return false;
    }

    public function yanData()
    {
    	$kinds = array();

		if(!empty($this->lat) && !empty($this->lng))
		{

				$params = array(
				    'geocode' => $this->lng.','.$this->lat,         // координаты
				    'format'  => 'json',                          // формат ответа
				);
				$response = json_decode(@file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));

    				if ($response && $response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
					{
						if(isset($response->response->GeoObjectCollection->featureMember))
						{
							foreach($response->response->GeoObjectCollection->featureMember as $collection)
							{
								if(isset($collection->GeoObject->metaDataProperty->GeocoderMetaData->kind))
								{
									$kind = $collection->GeoObject->metaDataProperty->GeocoderMetaData->kind;
									if($kind == 'locality') // город/поселок/деревня/село/
									{ 
										$lat = $lng = null;
										if($collection->GeoObject->Point->pos){
											$exp_str2 = explode(" ", $collection->GeoObject->Point->pos);
											$lat = $exp_str2[1];
											$lng = $exp_str2[0];
										}
										$kinds['locality'][] = array('name'=>$collection->GeoObject->name,'lat'=>$lat,'lng'=>$lng);
										continue;
									}
									if($kind == 'area') // округ
										{ 
											$lat = $lng = null;
											if($collection->GeoObject->Point->pos){
												$exp_str2 = explode(" ", $collection->GeoObject->Point->pos);
												$lat = $exp_str2[1];
												$lng = $exp_str2[0];
											}
											$kinds['area'][] = array('name'=>$collection->GeoObject->name,'lat'=>$lat,'lng'=>$lng);
											continue;
										}
										if($kind == 'province'){ // край, фед.округ
											$lat = $lng = null;
											if($collection->GeoObject->Point->pos){
												$exp_str2 = explode(" ", $collection->GeoObject->Point->pos);
												$lat = $exp_str2[1];
												$lng = $exp_str2[0];
											}
											$kinds['province'][] = array('name'=>$collection->GeoObject->name,'lat'=>$lat,'lng'=>$lng);
											continue;
										}
								}
							}
							if(isset($kinds['province'])) // заносим/проверяем край,фед.округ,область?
							{
								$this->setProvince($kinds['province']);
							}
							if(isset($kinds['area']))
							{
								$this->setArea($kinds['area']);
							}
							if(isset($kinds['locality']))
							{
								$this->setLocality($kinds['locality']);
							}
						}
					}
				}
				return true;
    }
    /**
	 * Метод для занесения фед.округов,краев,областей
	 * @param array $province Массив область/край/фед.округ
	 * @return bool
	 */
	public function setProvince(array $province)
	{
		
		$dontDelete = array();
        if(!empty($province))
        { 
        	$parent = null;
			for ($i=(count($province)-1); $i>=0; $i--) 
			{
				$c = trim(strip_tags($province[$i]['name']));
				$region = Region::model()->find(array('condition'=>'LOWER(title)=:title','params'=>array(':title'=>MHelper::String()->toLower($c))));
				if(!$region)
				{
					$region = new Region;
					$region->title = $c;
					$region->parent_region = $parent;
					$region->region_lat = $province[$i]['lat'];
					$region->region_lng = $province[$i]['lng'];
					$region->updated = 1;
					$region->save();
					
				} 
				else
				{
					if(!$region->updated)
					{
						$region->parent_region = $parent;
						$region->region_lat = $province[$i]['lat'];
						$region->region_lng = $province[$i]['lng'];
						$region->updated = 1;
						$region->save();
					}
					
				}
				$parent = $region->id;
				$this->region = $region->id;

			}

		}
		return true;
	}

	/**
	 * Метод для занесения конгломераций
	 * @param array $area Массив конгломераций
	 * @return bool
	 */
	public function setArea(array $area)
	{
		$dontDelete = array();
        if(!empty($area))
        { 
        	$parent = null;
			for ($i=(count($area)-1); $i>=0; $i--) 
			{
				$c = trim(strip_tags($area[$i]['name']));
				$cityArea = City::model()->find(array('condition'=>'LOWER(title)=:title','params'=>array(':title'=>MHelper::String()->toLower($c))));
				if(!$cityArea)
				{
					$cityArea = new City;
					$cityArea->addcoordinates = false;
					$cityArea->title = $c;
					$cityArea->yandex_title = $c;
					$cityArea->region = $this->region;
					$cityArea->parent_area = $parent;
					$cityArea->latitude = $area[$i]['lat'];
					$cityArea->longitude = $area[$i]['lng'];
					$cityArea->updated = 1;

					// склонение 
					$params = array(
					    'format'  => 'json', 
					    'name'=>$c
					);
					$response = CJSON::decode(@file_get_contents('http://export.yandex.ru/inflect.xml?' . http_build_query($params, '', '&')));
					if ($response) 
					{
						if(isset($response[2]))
							$cityArea->rodpad = $response[2];
						if(isset($response[6]))
							$cityArea->mestpad = $response[6];
					}

					$cityArea->save();
				}
				else
				{
					if(!$cityArea->updated)
					{
						$cityArea->addcoordinates = false;
						$cityArea->yandex_title = $c;
						$cityArea->region = $this->region;
						$cityArea->parent_area = $parent;
						$cityArea->latitude = $area[$i]['lat'];
						$cityArea->longitude = $area[$i]['lng'];
						$cityArea->updated = 1;
						$cityArea->save();
					}
					
				}
				$parent = $cityArea->id;
				$this->area = $cityArea->id;
			}
		}
		return true;
	}

	/**
	 * Метод для занесения городов
	 * @param array $locality Массив конгломераций
	 * @return bool
	 */
	public function setLocality(array $locality)
	{
		$dontDelete = array();
        if(!empty($locality))
        { 
        	$parent = null;
			for ($i=(count($locality)-1); $i>=0; $i--) 
			{
				
				$c = trim(strip_tags($locality[$i]['name']));
				$city = City::model()->find(array('condition'=>'(LOWER(title)=:title or LOWER(alternative_title)=:alternative_title or LOWER(yandex_title)=:yandex_title) and region=:region','params'=>array(':title'=>MHelper::String()->toLower($c),'alternative_title'=>MHelper::String()->toLower($c),'yandex_title'=>MHelper::String()->toLower($c),'region'=>$this->region)));
				if(!$city)
				{
					if($i!=0)
					{
						$city = new City;
						$city->addcoordinates = false;
						$city->title = $c;
						$city->yandex_title = $c;
						$city->region = $this->region;
						$city->parent_area = $this->area;
						$city->id_parent = $parent;
						$city->latitude = $locality[$i]['lat'];
						$city->longitude = $locality[$i]['lng'];
						$city->updated = 1;
						$city->pos = 10000;

						// склонение 
						$params = array(
						    'format'  => 'json', 
						    'name'=>$c
						);
						$response = CJSON::decode(@file_get_contents('http://export.yandex.ru/inflect.xml?' . http_build_query($params, '', '&')));
						if ($response) 
						{
							if(isset($response[2]))
								$city->rodpad = $response[2];
							if(isset($response[6]))
								$city->mestpad = $response[6];
						}
						$city->save();
					} 
					elseif($i==0)
					{
						$this->addcoordinates = false;
						$this->yandex_title = $c;
						$this->parent_area = $this->area;
						$this->id_parent = $parent;
						$this->latitude = $locality[$i]['lat'];
						$this->longitude = $locality[$i]['lng'];
						$this->updated = 1;

						// склонение 
						$params = array(
						    'format'  => 'json', 
						    'name'=>$c
						);
						$response = CJSON::decode(@file_get_contents('http://export.yandex.ru/inflect.xml?' . http_build_query($params, '', '&')));
						if ($response) 
						{
							if(!$this->rodpad && isset($response[2]))
								$this->rodpad = $response[2];
							if(!$this->mestpad && isset($response[6]))
								$this->mestpad = $response[6];
						}
					}
				}
				else
				{
					if(!$city->updated)
					{
						if($i!=0)
						{
							$city->addcoordinates = false;
							$city->yandex_title = $c;
							$city->region = $this->region;
							$city->parent_area = $this->area;
							$city->id_parent = $parent;
							$city->latitude = $locality[$i]['lat'];
							$city->longitude = $locality[$i]['lng'];
							$city->updated = 1;
							$city->save();
						} 
						elseif($i==0)
						{
							$this->addcoordinates = false;
							$this->yandex_title = $c;
							$this->parent_area = $this->area;
							$this->id_parent = $parent;
							$this->latitude = $locality[$i]['lat'];
							$this->longitude = $locality[$i]['lng'];
							$this->updated = 1;
						}
					}
					
				}
				if($city)
				$parent = $city->id;

			}
		}
		return true;
	}

	public static function addNewCity($city)
    {
    	$trueCity = null;
    	if(!empty($city)){ // добавляем город
						$trueCity = new City;
						$trueCity->title = $city;
						$trueCity->alternative_title = $city;

						$address_city = $city;
						$params = array(
						    'geocode' => $address_city,         // координаты
						    'format'  => 'json',                          // формат ответа
						    'results' => 1,                               // количество выводимых результатов
						    'kind'=>'locality'
						  //  'key'     => '...',                           // ваш api key
						);
						$trueRegion = $result_region = null;
						$response = json_decode(@file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
						if ($response && $response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
						{
							$result = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
						    if($result){
						    	$exp_str1 = explode(" ", $result);
								$trueCity->latitude = $exp_str1[1];
								$trueCity->longitude = $exp_str1[0]; 
						    } 
						    $result = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->name;
						    if($result){ 
						    	$trueCity->title = $result;
						    	$trueCity->alternative_title = $result;
						     }
						    $result_region = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName;
						    if($result_region){
						    	$trueRegion = Region::model()->find('title=:title',array(':title'=>$result_region));
									if(!$trueRegion){
										$trueRegion = new Region;
										$trueRegion->title = $result_region;
									    $trueRegion->save();
									}
						    } 
						}
						if($trueCity->latitude)
						{
							// склонение 
							$params = array(
							    'format'  => 'json',                          // формат ответа
							    'name'=>$trueCity->title
							);
							$response = CJSON::decode(@file_get_contents('http://export.yandex.ru/inflect.xml?' . http_build_query($params, '', '&')));
							if ($response) 
							{
								if(isset($response[2]))
									$trueCity->rodpad = $response[2];
								if(isset($response[6]))
									$trueCity->mestpad = $response[6];
							}

							if($trueRegion){
								$trueCity->region = $trueRegion->id;
							}
							$trueCity->pos = 0;
							$trueCityCheck = City::model()->find('LOWER(title)=:title or LOWER(alternative_title)=:alternative_title',array(':title'=>MHelper::String()->toLower($trueCity->title),':alternative_title'=>MHelper::String()->toLower($trueCity->title)));
							if($trueCityCheck) // потому-что ввести могут что угодно, а город обозначится только после запроса к яндексу.
								return $trueCityCheck;

						    if($trueCity->save()){
						    	
						    } else {

						    	if($trueCity->errors && isset($trueCity->errors['title'])){
						    		if($trueCity->errors['title'][0] == 'Город с таким названием уже существует.'){
						    			$trueCity = City::model()->find('LOWER(title)=:title or LOWER(alternative_title)=:alternative_title',array(':title'=>MHelper::String()->toLower($trueCity->title),':alternative_title'=>MHelper::String()->toLower($trueCity->title)));
						    		}
						    	}
						    }
						}


					} 
					return $trueCity;
    }

    public function beforeDelete()
    {
        // Delete file
        $filename = $this->filename;
        if(!empty($filename)){
	        $imagePath = $this->getFileFolder() . $filename;
	        if(file_exists($imagePath)) {
	            @unlink($imagePath); //delete file
	        }
	        $this->getDeleteFileFolder();
    	}
        return parent::beforeDelete();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
