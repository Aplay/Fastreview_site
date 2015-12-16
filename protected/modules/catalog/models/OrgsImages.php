<?php

/**
 * This is the model class for table "orgs_images".
 *
 * The followings are the available columns in table 'orgs_images':
 * @property integer $id
 * @property integer $org
 * @property string $filename
 * @property string $realname
 * @property integer $is_main
 * @property integer $uploaded_by
 * @property string $date_uploaded
 *
 * The followings are the available model relations:
 * @property Orgs $org0
 * @property Users $uploadedBy
 */
class OrgsImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orgs_images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('org, filename, realname', 'required'),
			array('realname', 'filter','filter' =>'trim'),
			array('org, is_main, uploaded_by, verified', 'numerical', 'integerOnly'=>true),
			array('filename, realname, ip_address', 'length', 'max'=>255),
			array('date_uploaded', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, org, filename, realname, is_main, uploaded_by, date_uploaded, ip_address, verified', 'safe', 'on'=>'search'),
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
			'organization' => array(self::BELONGS_TO, 'Orgs', 'org'),
			'uploadedBy' => array(self::BELONGS_TO, 'User', 'uploaded_by'),
		);
	}

	public function behaviors()
	{
		return array(
		    'fileBehavior'=> array(
          		'class' => 'application.components.behaviors.FileBehavior',
          		'attribute' => 'filename',
          		'folderUploadPath'=>'orgs',
          		'attributeId'=>'org'
          	),
		);
	}

	/**
	 * @return array
	 */
	public function defaultScope()
	{
		return array(
			'order'=>'date_uploaded DESC',
		);
	}
	public function scopes()
	{
		$alias = $this->getTableAlias();
		return array(

			'orderByCreatedDesc'=>array(
				'order'=>$alias.'.date_uploaded DESC',
			),

		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'org' => 'Org',
			'filename' => 'Name',
			'realname' => 'Realname',
			'is_main' => 'Is Main',
			'uploaded_by' => 'Uploaded By',
			'date_uploaded' => 'Date Uploaded',
			'ip_address' => 'IP адрес'
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

		$criteria->compare('id',$this->id);
		$criteria->compare('org',$this->org);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('realname',$this->realname,true);
		$criteria->compare('is_main',$this->is_main);
		$criteria->compare('uploaded_by',$this->uploaded_by);
		$criteria->compare('date_uploaded',$this->date_uploaded,true);
		$criteria->compare('verified',$this->verified);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder' => 'date_uploaded DESC')
		));
	}

	public static function getLastImages($limit, $city_id)
	{

		$sql = "SELECT uploaded_by, org, max(date_uploaded) as date FROM orgs_images
				LEFT OUTER JOIN orgs o
                ON (o.id=orgs_images.org) 
                WHERE o.city_id = {$city_id}
			  	GROUP BY uploaded_by, cast(date_trunc('day',date_uploaded) as text), org
			  	ORDER BY date DESC
				LIMIT {$limit} ";
		$command = Yii::app()->db->createCommand($sql);
		$data = $command->queryALL();
		return $data;
	}

	public function beforeSave()
	{
		if(parent::beforeSave()) {
			if($this->isNewRecord)
			{
				if(!Yii::app()->user->isGuest){
					$this->uploaded_by = Yii::app()->user->id;
				}
				$this->ip_address = Yii::app()->request->userHostAddress;
			}

			return true;
        } else
            return false;
	}
	public function afterDelete()
    {
        // Delete file
        $filename = $this->filename;
        $imagePath = $this->organization->getFileFolder() . $filename;
        if(file_exists($imagePath)) {
            @unlink($imagePath); //delete file
        }
        $thumbFolders = Yii::app()->params['storeImages']['thumbFolders'];
        $thumbUrl = Yii::app()->params['storeImages']['thumbUrl'];
        if(!empty($thumbFolders)){
            foreach($thumbFolders as $size){
                $getfolder = Yii::getPathOfAlias('webroot').$thumbUrl.$size;
                $thumbPath = $getfolder.'/'.$this->org.'_'.$filename;
                if(file_exists($thumbPath)){
                	@unlink($thumbPath); //delete file
                }
            }
        }
        return parent::afterDelete();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrgsImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
