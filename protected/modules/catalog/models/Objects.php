<?php
Yii::import('application.modules.comments.models.Comment');
/**
 * This is the model class for table "objects".
 *
 * The followings are the available columns in table 'objects':
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property integer $city_id
 * @property string $street
 * @property string $dom
 * @property integer $views_count
 * The followings are the available model relations:
 * @property ObjectsImages[] $ObjectsImages
 * @property City $city0
 */
class Objects extends BaseModel
{
	const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $categories_ar;
    public $maxFiles = 25;
    public $tmpFiles;

 	protected $_newRec = false;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'objects';
	}
	public function scopes()
	{
		
		$alias = $this->getTableAlias(true);
                
		return array(
			'active'=>array(
				'condition'=>$alias.'.status = '.self::STATUS_ACTIVE,
			), 
			'notactive'=>array(
				'condition'=>$alias.'.status= '.self::STATUS_NOT_ACTIVE,
			),

		);
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

			return  array(
				array('title, categorie', 'required'),
			    array('title, description, link, address', 'filter', 'filter' => 'strip_tags'),
	            array('title, description, link, address', 'filter','filter' =>'trim'),
				array('city_id, views_count, categorie, rating_id, author, status', 'numerical', 'integerOnly'=>true),
				array('street, dom', 'filter', 'filter'=>array( $this, 'removeCommas' )),
				array('ip_address', 'length', 'max'=>50),
				array('title, link, address, url', 'length', 'max'=>255),
				array('description, tmpFiles, lat, lng', 'safe'),
				array('link', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
				array('verified','boolean'),
				array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	            array('updated_date', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
	            array('tmpFiles, categories_ar', 'safe'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, title, description, link, address, city_id, street, dom, views_count, created_date, updated_date, categorie, author, url, status', 'safe', 'on'=>'search'),
			);
	}

	

	public function removeCommas($value)
	{
	    //trim out commas
	    $value = trim($value,',');
	    return strtoupper($value);
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'authorid'=>array(self::BELONGS_TO, 'User', 'author'),
			'category'=>array(self::BELONGS_TO, 'Category', 'categorie'),
			'images' => array(self::HAS_MANY, 'ObjectsImages', 'object'),
			'comments' => array(self::HAS_MANY, 'Comment', 'object_pk', 'condition'=>'comments.parent_id is NULL'),
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
			'link' => 'Ссылка',
			'description' => 'Описание',
			'city_id' => 'Город',
			'street' => 'Улица',
			'dom' => 'Дом',
			'created_date' => Yii::t('site','Created Date'),
            'updated_date' => Yii::t('site','Updated Date'),
            'categorie'	=> 'Категория',
            'tmpFiles' => 'Фото',
            'author'=>'Автор',
            'status'=>'Статус',
            'district'=>'Район/Округ',
            'reCaptcha'=>'Код проверки',
            'url'=>'URL',
            'verified'=>'Проверка',
            'address'=>'Адрес'
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
	public function search($params = array(), $additionalCriteria = null, $pagination = true)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		

		if($additionalCriteria !== null)
			$criteria->mergeWith($additionalCriteria);

		if($this->id)
			$this->id = (int)$this->id;
		$criteria->compare('t.id',$this->id);
		if($this->title){
			$criteria->compare('LOWER(t.title)',MHelper::String()->toLower($this->title),true);
		}
		$criteria->compare('LOWER(t.description)',MHelper::String()->toLower($this->description),true);
		$criteria->compare('LOWER(t.address)',MHelper::String()->toLower($this->address),true);
		$criteria->compare('LOWER(t.street)',MHelper::String()->toLower($this->street),true);
		$criteria->compare('LOWER(t.dom)',MHelper::String()->toLower($this->dom),true);
		$criteria->compare('t.city_id',$this->city_id);
		$criteria->compare('t.link',$this->link,true);
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.created_date',$this->created_date,true); 
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.categorie', $this->categorie);

		$criteria->compare('t.author', $this->author);

        if($pagination){
        	return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => Objects::getCSort(),
				'pagination' => array(
					'pageSize'=>50,
				)
			));
        } else {
        	return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => Objects::getCSort('t.id'),
				'pagination' => false
			));
        }
		
	}

	/**
	 * @return CSort to use in gridview, listview, etc...
	 */
	public static function getCSort($order = '')
	{	
		// используется и в админке и при выдаче организаций в категориях на сайте, поэтому $order
		$sort = new CSort;
		if($order){
			$sort->defaultOrder = $order;
		} else {
			// $sort->defaultOrder = 'lastlog.datetime DESC';
			$sort->defaultOrder = 't.updated_date DESC';
		}

		$sort->attributes=array(
			
		    'id' => array(
				'asc'   => 't.id',
				'desc'  => 't.id DESC',
			),
		   'created' => array(
				'asc'   => 't.created_date',
				'desc'  => 't.created_date DESC',
			),
		   'title' => array(
				'asc'   => 't.title',
				'desc'  => 't.title DESC',
			),
		   'updated_date'=>array(
                'asc'=>'t.updated_date',
                'desc'=>'t.updated_date DESC',
            ),
		  
		);

		return $sort;
	}



	/**
	 * @return array
	 */
	public static function getStatusNames()
	{
		return array(
			self::STATUS_ACTIVE=>'Опубликовано',
			self::STATUS_NOT_ACTIVE=>'Не опубликовано',
		);
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
            if($this->isNewRecord) {
                $test = Objects::model()
                    ->withUrl($unique)
                    ->count();
            } else {
                $test = Objects::model()
                    ->withUrl($unique)
                    ->count('id!=:id', array(':id'=>$this->id));
            }
            return $test;
    }
    protected function beforeDelete() {

        $this->getDeleteFileFolder();

        // all comments remove
        Comment::model()->deleteAllByAttributes(array(
				'object_pk'=>$this->id
		));

        return parent::beforeDelete();
    }
    public function addDropboxFiles($uploadsession)
    {
        $files = $this->tmpFiles;
        $existFiles = count($this->images);
        $availableFiles = $this->maxFiles - $existFiles;
        $cnt = 0;
        if($files){

            if(Yii::app()->session->itemAt($uploadsession)){

                $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
                

                foreach($files as $fileUploadName){

                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {

                                	if($cnt >= $availableFiles)	
                                		break 2;

                                    $file = $folder.$value;
                                    $ext = pathinfo($folder.$value, PATHINFO_EXTENSION);

                                    $base = md5(rand(1000,4000));
                                    $unique = $base.'_obj';
                                    $suffix = 1;

                                    while (file_exists($this->getFileFolder() . $unique . $ext)){
                                        $unique = $base.'_'.$suffix;
                                        $suffix++;
                                    }
                                    $filename =  $unique . '.' . $ext;
                                    $fullPath = $this->getFileFolder() . $filename;
                                    
                                    if (copy($folder.$value, $fullPath)) {
                                        unlink($folder.$value);
                                        $image = new ObjectsImages();
                                        $image->object = $this->getPrimaryKey();
                                        $image->filename = $filename;
                                        $image->realname = $key;

                                        Yii::import('ext.phpthumb.PhpThumbFactory');
										$thumb  = PhpThumbFactory::create($fullPath);
										$thumb->setOptions(array('jpegQuality'=>100));
										$thumb->resize(1500)->save($fullPath);

                                        $image->save();
                                        $cnt++;
                                        	
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
    public function getFileFolder()
    {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/objects/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
        $folder = Yii::getPathOfAlias('webroot').'/uploads/objects/'.$this->id.'/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
        return $folder;
    }

    public function getOrigFilePath() {
        return Yii::app()->baseUrl.'/uploads/objects/'.$this->id.'/';
    }

    protected function getDeleteFileFolder() {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/objects/'.$this->id;
        if (is_dir($folder) == true)
        	CFileHelper::removeDirectory($folder);
           // rmdir($folder);
        return true;
    }

	public function beforeSave()
	{
		if(parent::beforeSave()) {

			if($this->isNewRecord) {
				$this->_newRec = true;
				$this->ip_address = Yii::app()->request->userHostAddress;
				$this->author = (Yii::app()->user)?Yii::app()->user->id:null;
				$this->created_date = date('Y-m-d H:i:s');
			} 

			// Create slug
			Yii::import('ext.SlugHelper.SlugHelper');
	        if(!$this->url){
	            $this->url = SlugHelper::run($this->title, 'yandex');
	        } else {
	        	$this->url = SlugHelper::run($this->url, 'yandex');
	        }
	       

	        $this->updated_date = date('Y-m-d H:i:s');

    		return true;
        } else
            return false;
	}


	public function getFullAddress($parts = array('region','city','street','dom'))
    {
    	return $this->address;
    	
    }
    public static function parseUrlShow($url, $social = false){
    	$url = parse_url(CHtml::encode($url));
		$host = isset($url['host']) ? $url['host'] : '';
		if(stripos($host, "www.") === 0)
	                $host = substr($host, 4);

		$path  = isset($url['path']) ? $url['path'] : '';
		if(!$social){
			$url = $host.$path;
		} else {
			$url = trim($path,'\/');
		}

		return $url;
    }
	/**
	 * Delete related data.
	 */
	public function afterDelete()
	{
		return parent::afterDelete();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orgs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
