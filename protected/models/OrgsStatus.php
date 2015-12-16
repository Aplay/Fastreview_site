<?php

/**
 * This is the model class for table "orgs_status".
 *
 * The followings are the available columns in table 'orgs_status':
 * @property integer $id
 * @property integer $org_id
 * @property integer $status_id
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property Orgs $org
 * @property Status $status
 */
class OrgsStatus extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orgs_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, doptext', 'filter','filter' =>'strip_tags'),
            array('url, doptext', 'filter','filter' =>'trim'),
			array('org_id, status_id, user_id', 'required'),
			array('org_id, status_id, user_id', 'numerical', 'integerOnly'=>true),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	        array('url', 'length', 'max'=>255),
	        array('url', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
	        array('doptext','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, org_id, status_id, created_date', 'safe', 'on'=>'search'),
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
			'org' => array(self::BELONGS_TO, 'Orgs', 'org_id'),
			'status' => array(self::BELONGS_TO, 'Status', 'status_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'org_id' => 'Org',
			'status_id' => 'Статус',
			'created_date' => 'Created Date',
			'url'=>'Ссылка',
			'doptext'=>'Дополнительный текст'
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
	public function search($params = array(), $additionalCriteria = null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		if($additionalCriteria !== null)
			$criteria->mergeWith($additionalCriteria);
		
		$criteria->compare('id',$this->id);
		$criteria->compare('org_id',$this->org_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'created_date DESC')
		));
	}

	public function getFullText(){
		$message = str_replace('{company}', $this->org->title, $this->status->text);
        $message = str_replace('{site}', CHtml::link(Orgs::parseUrlShow($this->url), $this->url, array('target'=>'_blank')), $message);
        if($this->doptext){
        	if($message)
        		$message .= "<br>";
            $message .= $this->doptext;
        }
        return $message;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrgsStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
