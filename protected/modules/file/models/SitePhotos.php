<?php

/**
 * This is the model class for table "site_photos".
 *
 * The followings are the available columns in table 'site_photos':
 * @property integer $id_photo
 * @property integer $id_record
 * @property integer $id_user
 * @property string $info
 * @property string $extention
 * @property integer $date
 * @property integer $status
 * @property integer $type_old
 * @property integer $ord
 * @property integer $id_city
 * @property integer $type
 */
class SitePhotos extends CActiveRecord
{
	const OBJ_ADDRESS = 4;
	const OBJ_BRANCH = 20;
	const OBJ_EVENT = 1004;

	const STATUS_DELETED = 2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company_photos';
	}
	/*
	protected function beforeFind() {
        $this->cache(30, new CTagCacheDependency(get_class($this)));
        parent::beforeFind();
    } */
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_record, id_user, date, status, type_old, ord, id_city, type', 'numerical', 'integerOnly'=>true),
			array('extention', 'length', 'max'=>10),
			array('info', 'filter', 'filter' => 'strip_tags'),
            array('info', 'filter','filter' =>'trim'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_photo, id_record, id_user, info, extention, date, status, type_old, ord, id_city, type, filename', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
			'company' => array(self::BELONGS_TO, 'Company', 'id_record'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_photo' => 'Id Photo',
			'id_record' => 'Id Record',
			'id_user' => 'Id User',
			'info' => 'Info',
			'extention' => 'Extention',
			'date' => 'Date',
			'status' => 'Status',
			'type_old' => 'Type Old',
			'ord' => 'Ord',
			'id_city' => 'Id City',
			'type' => 'Type',
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

		$criteria->compare('id_photo',$this->id_photo);
		$criteria->compare('id_record',$this->id_record);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('extention',$this->extention,true);
		$criteria->compare('date',$this->date);
		$criteria->compare('status',$this->status);
		$criteria->compare('type_old',$this->type_old);
		$criteria->compare('ord',$this->ord);
		$criteria->compare('id_city',$this->id_city);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function getDataTypes()
	{
		return array(self::OBJ_ADDRESS,self::OBJ_BRANCH,self::OBJ_EVENT);
	}
	public function getFileFolder($subfolder = '', $id = '')
	{
		$dir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads';
        if($subfolder){
            $dir .= DIRECTORY_SEPARATOR . $subfolder;
            if (is_dir($dir) == false){
                CFileHelper:: createDirectory($dir, Yii::app()->params['storeImages']['dirMode']);
            }
        }
        if($id)
            $dir .= DIRECTORY_SEPARATOR . $id;

        $dir .= DIRECTORY_SEPARATOR;

        if (is_dir($dir) == false){
                CFileHelper:: createDirectory($dir, Yii::app()->params['storeImages']['dirMode']);
            }
            
        return $dir;
	}

	public function getOrigFilePath($subfolder = '', $id = '') {
		$path = Yii::app()->baseUrl.'/uploads/';
		if($subfolder)
			$path .= $subfolder.'/';
		if($id)
			$path .= $id.'/';
        return $path;
    }

	public function getPhotoFromUrl($url, $data, $w, $h)
	{

		if(Yii::app()->user->isGuest)
			return false;

		$id_record=(int)$data['id_record'];
		if(!$id_record)
			return false;

		$type = $data['type'];
		if(!in_array($type,$this->dataTypes))
			return false;

		Yii::import('application.vendors.*');
		require_once('/phpthumb/phpthumb.class.php');

		$extention='jpg';

		$id_photo = time();
		$file_name = $id_photo.'.'.$extention;
		$upload_path = Yii::getPathOfAlias('webroot') . Yii::app()->params['tmpFolder'].$file_name;

		$PT = new phpThumb();
		$PT->src = $url;
		$PT->w = (int)$w;
		$PT->h = (int)$h;
		$PT->bg = "#ffffff";
		$PT->q = 90;
		$PT->far = false;
		$PT->f = $extention;
		$PT->GenerateThumbnail();
		$success = $PT->RenderToFile($upload_path);

		if($success)
			return $id_photo.'.'.$extention;
		else
			return false;
	}
	/**
	 * Get url to product image. Enter $size to resize image.
	 * @param mixed $size New size of the image. e.g. '150x150'
	 * @param mixed $resizeMethod Resize method name to override config. resize/adaptiveResize
	 * @param mixed $random Add random number to the end of the string
	 * @return string
	 */
	public function getUrl($subfolder = '', $id, $size = false, $resizeMethod = false, $random = false)
	{
		// Path to source image
		$fullPath  = Yii::getPathOfAlias(FileImageConfig::get('path'));
		if($subfolder)
			$fullPath  .= '/'.$subfolder.'/';
		if($id)
			$fullPath  .= '/'.$id;
		$fullPath  .= '/'.$this->id_photo.'.'.$this->extention;

		if($size !== false)
		{
			//$thumbPath = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/'.$size;
            $thumbPath = Yii::getPathOfAlias(FileImageConfig::get('thumbPath')).'/'.$size;
			if(!file_exists($thumbPath)){
				CFileHelper:: createDirectory($thumbPath, Yii::app()->params['storeImages']['dirMode']);
             }

			
			// Path to thumb
			$thumbPath = $thumbPath.'/'.$this->id_photo.'.'.$this->extention;

			if(!file_exists($fullPath)){
				return false;
			}
		
			if(!file_exists($thumbPath) && file_exists($fullPath))
			{
                            
				// Resize if needed
				Yii::import('ext.phpthumb.PhpThumbFactory');
				$sizes  = explode('x', $size);
				$thumb  = PhpThumbFactory::create($fullPath);

				if($resizeMethod === false)
                   $resizeMethod = FileImageConfig::get('resizeThumbMethod');
				if(!empty($sizes[1])){
                   $thumb->$resizeMethod($sizes[0],$sizes[1])->save($thumbPath); 
                } else {
                    $thumb->$resizeMethod($size)->save($thumbPath);
                }
                            
			} 

			return ImagesConfig::get('thumbUrl').$size.'/'.$this->id_photo.'.'.$this->extention;
		}
		
		if ($random === true){
			return $fullPath.'?'.rand(1, 10000);
		}
		return $fullPath;
	}
	protected function beforeSave() {
        if(parent::beforeSave()) {
        	// Yii::app()->cache->set(get_class($this), microtime(true), 0);
            return true;
        } else
            return false;
    }

     public function beforeDelete()
    {
        
        if(parent::beforeDelete()){
          //  Yii::app()->cache->set(get_class($this), microtime(true), 0);
            return true;
        }
        else
            return false;
    }
	/**
     * Delete the files on delete.
     */
    public function afterDelete()
    {
        // Delete file
        $filename = $this->id_photo.'.'.$this->extention;
        $subfolder = '';
        $id = '';
        if($this->type == self::OBJ_BRANCH){
        	$subfolder = 'company';
        	$id = $this->id_record;
        }
        $imagePath = $this->getFileFolder($subfolder, $id) . $filename;
        if(file_exists($imagePath)) {
            unlink($imagePath); //delete file
        }
      /*  $thumbFolders = Yii::app()->params['storeImages']['thumbFolders'];
        $thumbUrl = Yii::app()->params['storeImages']['thumbUrl'];
        if(!empty($thumbFolders)){
            foreach($thumbFolders as $size){
                $getfolder = Yii::getPathOfAlias('webroot').$thumbUrl.$size;
                $thumbPath = $getfolder.'/'.$filename;
                if(file_exists($thumbPath)){
                	unlink($thumbPath); //delete file
                }
            }
        } */
        return parent::afterDelete();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SitePhotos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
