<?php

/**
 * This is the model class for table "article_images".
 *
 * The followings are the available columns in table 'article_images':
 * @property integer $id
 * @property integer $article
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
class ArticleImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'article_images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article, filename, realname', 'required'),
			array('realname', 'filter','filter' =>'trim'),
			array('article, is_main, uploaded_by', 'numerical', 'integerOnly'=>true),
			array('filename, realname', 'length', 'max'=>255),
			array('date_uploaded', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, article, filename, realname, is_main, uploaded_by, date_uploaded', 'safe', 'on'=>'search'),
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
			'organization' => array(self::BELONGS_TO, 'Article', 'article'),
			'uploadedBy' => array(self::BELONGS_TO, 'Users', 'uploaded_by'),
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
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'article' => 'Статья',
			'filename' => 'Name',
			'realname' => 'Realname',
			'is_main' => 'Is Main',
			'uploaded_by' => 'Uploaded By',
			'date_uploaded' => 'Date Uploaded',
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
		$criteria->compare('article',$this->article);
		$criteria->compare('filename',$this->name,true);
		$criteria->compare('realname',$this->realname,true);
		$criteria->compare('is_main',$this->is_main);
		$criteria->compare('uploaded_by',$this->uploaded_by);
		$criteria->compare('date_uploaded',$this->date_uploaded,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave()
	{
		if(parent::beforeSave()) {
			if(!Yii::app()->user->isGuest){
				$this->uploaded_by = Yii::app()->user->id;
			}
			return true;
        } else
            return false;

	}

	public function beforeDelete()
    {
        // Delete file
        $filename = $this->filename;
        $imagePath = $this->organization->getFileFolder() . $filename;
        if(file_exists($imagePath)) {
            unlink($imagePath); //delete file
        }
        $thumbFolders = Yii::app()->params['storeImages']['thumbFolders'];
        $thumbUrl = Yii::app()->params['storeImages']['thumbUrl'];
        if(!empty($thumbFolders)){
            foreach($thumbFolders as $size){
                $getfolder = Yii::getPathOfAlias('webroot').$thumbUrl.$size;
                $thumbPath = $getfolder.'/'.$this->article.'_'.$filename;
                if(file_exists($thumbPath)){
                	unlink($thumbPath); //delete file
                }
            }
        }
        return parent::beforeDelete();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
