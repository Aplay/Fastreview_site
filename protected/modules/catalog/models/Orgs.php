<?php

/**
 * This is the model class for table "orgs".
 *
 * The followings are the available columns in table 'orgs':
 * @property integer $id
 * @property string $title
 * @property string $synonim
 * @property string $url
 * @property string $description
 * @property string $logotip
 * @property integer $city_id
 * @property string $street
 * @property string $dom
 * @property string $address_comment
 * @property string $email
 * @property string $vkontakte
 * @property string $facebook
 * @property string $twitter
 * @property integer $views_count
 * @property string $instagram
 * The followings are the available model relations:
 * @property OrgsCategory[] $orgsCategories
 * @property OrgsImages[] $orgsImages
 * @property City $city0
 * @property OrgsPhones[] $orgsPhones
 * @property OrgsHttp[] $orgsHttp
 * @property OrgsWorktime[] $orgsWorktimes
 */
class Orgs extends BaseModel
{
	const STATUS_NOT_ACTIVE = 0; // не оплачено
    const STATUS_ACTIVE = 1; // оплачено

    const STATUS_NOT_VERIFIED = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_UPDATE_VERIFIED = 2;

  
    public $site; // temp sites 
    public $tempphones;
	public $phones;
	public $phone_comments;
	public $http;
	public $http_comments;
	public $promo;
	public $plan;

	public $log_user;
	public $web_site;
	public $per_from;
	public $per_to;
	public $mass;


	public $reCaptcha;

	protected $_newRec = false;

 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orgs';
	}
	public function scopes()
	{
		
		$alias = $this->getTableAlias(true);
                
		return array(
			'active'=>array(
				'condition'=>$alias.'.status_org = '.self::STATUS_ACTIVE .' and '.$alias.'.verified = '.self::STATUS_VERIFIED,
			), 
			'notactive'=>array(
				'condition'=>$alias.'.status_org= '.self::STATUS_NOT_ACTIVE,
			),

		);
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$r =  array(
			array('tmpFiles', 'safe')
			);
		if($this->scenario == 'addPhoto'){
			if (Yii::app()->user->isGuest) {
				if (!($this->reCaptcha = Yii::app()->request->getPost(ReCaptchaValidator::CAPTCHA_RESPONSE_FIELD))) {
	            	$r[] = array('reCaptcha', 'required','on'=>'addPhoto');
	            	$r[] = array('reCaptcha', 'ReCaptchaValidator',  'secret'=>Yii::app()->reCaptcha->secret, 'message'=>'Неправильный код проверки','on'=>'addPhoto');
				
	        	}
	        }
		} else {
			$r =  array(
				
				array('title, address', 'required'),
				array('title, address, rubrictext', 'required', 'on' => 'step1'),
				array('plan', 'required', 'on' => 'step3'),
			    array('title, synonim, url, street, dom, rubrictext, tempphones, site', 'filter', 'filter' => 'strip_tags'),
	            array('title, synonim, url, street, dom, logotip_realname, rubrictext, tempphones, site', 'filter','filter' =>'trim'),
	            array('street, dom', 'filter', 'filter'=>array( $this, 'removeCommas' )),
				array('city_id, views_count, categorie, rating_id, nearest_metro, nearest_metro_distance, author, lasteditor, status_org, invoice_id, verified, plan', 'numerical', 'integerOnly'=>true),
				array('ip_address', 'length', 'max'=>50),
				array('title, url, synonim, logotip, logotip_realname, street, dom, email, fax, vkontakte, facebook, twitter, instagram, youtube, address, tempphones, site, promo', 'length', 'max'=>255),
				array('description, rubrictext, address_comment, tmpFiles, tmpLogotip, lat, lng', 'safe'),
				array('vkontakte, facebook, twitter, instagram, youtube', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
				array('created_date, paid_till', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	            array('updated_date', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
	            array('phones, phone_comments, categories_ar,  paid_till, from_transfer, http, http_comments, video, video_comments, area, region, district', 'safe'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, title, synonim, url, description, logotip, logotip_realname, city_id, street, dom, address_comment,  email, vkontakte, facebook, twitter, instagram, views_count, created_date, updated_date, categorie, nearest_metro, nearest_metro_distance, author, lasteditor, city_title, rubric_title,  address_search, city_search, log_user, web_site, per_from, per_to, status_org, mass, invoice_id', 'safe', 'on'=>'search'),
			);
		}
		
        return $r;
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
			'orgsHttp' => array(self::HAS_MANY, 'OrgsHttp', 'org', 'condition'=>'"orgsHttp"."type"='.OrgsHttp::TYPE_LINK),
			'orgsPhones' => array(self::HAS_MANY, 'OrgsPhones', 'org'),
			'orgsWorktimes' => array(self::HAS_MANY, 'OrgsWorktime', 'org'),
			'authorid'=>array(self::BELONGS_TO, 'User', 'author'),
			'lasteditorid'=>array(self::BELONGS_TO, 'User', 'lasteditor'),
			'lastlog' => array(self::HAS_ONE, 'ActionLog', 'model_id', 'order'=>'lastlog.datetime DESC', 'condition'=>'lastlog.model_name=\'Orgs\''),
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
			'synonim' => 'Синоним',
			'url' => 'Урл',
			'description' => 'Описание',
			'logotip' => 'Логотип',
			'city_id' => 'Город',
			'street' => 'Улица',
			'dom' => 'Дом',
			'address_comment' => 'Комментарий',
			'phones' => 'Телефон',
			'phone_comments' => 'Комментарий',
			'http'=>'Сайт',
            'http_comments'=>'Комментарий',
			'email' => 'Email',
			'vkontakte' => 'Vkontakte',
			'facebook' => 'Facebook',
			'twitter' => 'Twitter',
			'instagram'=>'Instagram',
			'youtube'=>'Youtube',
			'created_date' => Yii::t('site','Created Date'),
            'updated_date' => Yii::t('site','Updated Date'),
            'categorie'	=> 'Теги',
            'tmpFiles' => 'Фотографии',
            'author'=>'Автор',
            'lasteditor'=>'Редактор',
            'city_title'=>'Город',
            'rubric_title'=>'Теги',
            'log_user'=>'Пользователь',
            'web_site'=>'Вебсайт',
            'per_from'=>'с',
            'per_to'=>'по',
            'status_org'=>'Статус',
            'district'=>'Район/Округ',
            'reCaptcha'=>'Код проверки',
            'rubrictext'=>'Вид деятельности',
            'verified'=>'Проверка',
            'tempphones'=>'Телефон',
            'site'=>'Сайт',
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
			// $criteria->compare('LOWER(t.description)',MHelper::String()->toLower($this->title),true,'OR');
			// $criteria->compare('LOWER(t.synonim)',MHelper::String()->toLower($this->title),true,'OR');
		}
		$criteria->compare('LOWER(t.synonim)',MHelper::String()->toLower($this->synonim),true);
		$criteria->compare('LOWER(t.description)',MHelper::String()->toLower($this->description),true);

		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.logotip',$this->logotip,true);
		$criteria->compare('t.address_comment',$this->address_comment,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.vkontakte',$this->vkontakte,true);
		$criteria->compare('t.facebook',$this->facebook,true);
		$criteria->compare('t.twitter',$this->twitter,true);
		$criteria->compare('t.instagram',$this->instagram,true);
		$criteria->compare('t.created_date',$this->created_date,true); 
		$criteria->compare('t.status_org',$this->status_org);

		if ($this->updated_date)  {
			$criteria->addCondition("t.updated_date >='" . date('Y-m-d 00:00:00', strtotime($this->updated_date)) . "'");
			$criteria->addCondition("t.updated_date <='" . date('Y-m-d 23:59:59', strtotime($this->updated_date)) . "'");
		//	$criteria->compare('t.updated_date',$this->updated_date,true);
		}
		$criteria->compare('t.author', $this->author);
		$criteria->compare('t.lasteditor', $this->lasteditor);

		if($this->log_user){
			$criteria->with['lastlog'] = array(
			 	'select'=>'lastlog.datetime,lastlog.event',
			 	 'with'=>array('userid'=>array('select'=>'userid.username')),
			 	 'together'=>true
			 	);
			$criteria->addCondition('lastlog.user_id is NOT NULL','AND');
		    $criteria->compare('LOWER(userid.username)', MHelper::String()->toLower($this->log_user), true);
		}
		if($this->web_site){
			$criteria->with['orgsHttp'] = array(
			 	 'together'=>true
			 	);
		    $criteria->compare('LOWER("orgsHttp"."site")', MHelper::String()->toLower($this->web_site), true);
		}
		if($this->per_from){
			$criteria->addCondition("lastlog.datetime >='" . date('Y-m-d 00:00:00', strtotime($this->per_from)) . "'");
		}
		if($this->per_to){
			$criteria->addCondition("lastlog.datetime <='" . date('Y-m-d 00:00:00', strtotime($this->per_to)) . "'");
		}


        if($pagination){
        	return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => Orgs::getCSort(),
				'pagination' => array(
					'pageSize'=>50,
				)
			));
        } else {
        	return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => Orgs::getCSort('t.id'),
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
			self::STATUS_NOT_ACTIVE=>'Нет оплаты',
			self::STATUS_ACTIVE=>'Оплачена',
			
		);
	}


	public function beforeSave()
	{
		if(parent::beforeSave()) {

			if($this->isNewRecord) {
				$this->_newRec = true;
				$this->ip_address = Yii::app()->request->userHostAddress;
				$this->author = (Yii::app()->user)?Yii::app()->user->id:null;
				$this->lasteditor = (Yii::app()->user)?Yii::app()->user->id:null;
				$this->created_date = date('Y-m-d H:i:s');
				$this->paid_till = date('Y-m-d H:i:s');
			} else {
				$this->lasteditor = (Yii::app()->user)?Yii::app()->user->id:null;
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
	protected function afterSave() {

        parent::afterSave();

        $log = new ActionLog;
        $toUpdate = false;

        	// find last update date
        	$lastUpdateDate = ActionLog::model()->find(array('condition'=>'model_name=:model_name and model_id=:model_id and (event=:event or event=:event2)', 'params'=>array(':model_name'=>'Orgs',':model_id'=>$this->id, ':event'=>ActionLog::ACTION_CREATE, ':event2'=>ActionLog::ACTION_UPDATE),'order'=>'datetime DESC'));
        	if($lastUpdateDate){
        		$now = time(); 
			    $last_date = strtotime($lastUpdateDate->datetime);
			    $datediff = $now - $last_date;
			    if(floor($datediff/(60*60*24)) > 30 ){ // more 30 days left
			    	$toUpdate = true;
			    }
        	} else {
        		$toUpdate = true;
        	}
	        if(!$this->_newRec){
	        	$log->event = ActionLog::ACTION_UPDATE;
	        } else {
	        	$log->event = ActionLog::ACTION_CREATE;
	        }
	        if($toUpdate) {
		        $log->user_id = Yii::app()->user->id;
		        $log->model_name = 'Orgs';
		        $log->datetime = date('Y-m-d H:i:s');
		        $log->model_id = $this->id;
		        $log->save();
	    	}

        return true;
    }
    

	public function setHttp(array $http, array $http_comments, $add = false, $type = OrgsHttp::TYPE_LINK)
	{
        $errors = array();      
        $dontDelete = array();
        if(!empty($http)){ // массив http . Обычно есть хоть одна пустая строка
		foreach($http as $k=>$c){
			$c = trim(strip_tags($c));
			if(empty($c))
				continue;
			$found = OrgsHttp::model()->findByAttributes(array(
				'site'=>$c,
				'org'=>$this->id,
				'type'=>$type
			));
			// если не было email - делаем
			if(!$found){
				$record = new OrgsHttp;
				$record->site = $c;
				if(isset($http_comments[$k]))
					$record->description = $http_comments[$k];
				$record->org = $this->id;
				$record->type = $type;
				if($record->save()){
					$dontDelete[] = $record->id;
				} else {
					$errors['email'] = $c;
					$errors['error'] = $record->errors;
				}           
			} else { // обновляем описание
				$dontDelete[] = $found->id;
				if(isset($http_comments[$k])){
					$fk = trim(strip_tags($http_comments[$k]));
					if($found->description != $fk){
						$found->description = $fk;
						$found->save(true, array('description'));
						if(!$found->save(true,array('description'))){

						}
					}
				}
			}
		}
		}
		if($add === false){
			// Удаляем все категории, которых не было в массиве
			if(sizeof($dontDelete) > 0){
				$cr = new CDbCriteria;
				$cr->addNotInCondition('id', $dontDelete);

				OrgsHttp::model()->deleteAllByAttributes(array(
					'org'=>$this->id,
					'type'=>$type
				), $cr);
			} else { // удаляем все телефоны, т.к. пустой массив
				// Delete all relations
				OrgsHttp::model()->deleteAllByAttributes(array(
					'org'=>$this->id,
					'type'=>$type
				));
			}  
		}
		if(!empty($errors))
			return $errors;
		else
			return true;
	}

	public function setPhones(array $phones, array $phone_comments, $add = false)
	{  
        $errors = array(); 
        $dontDelete = array();
        if(!empty($phones)){
		foreach($phones as $k=>$c){ // массив телефонов . Обычно есть хоть одна пустая строка
			$c = trim(strip_tags($c));
			if(empty($c))
				continue;
			$found = OrgsPhones::model()->findByAttributes(array(
				'phone'=>$c,
				'org'=>$this->id
			));
			// если не было телефона - делаем
			if(!$found){
				$record = new OrgsPhones;
				$record->phone = $c;
				if(isset($phone_comments[$k]))
					$record->description = $phone_comments[$k];
				$record->org = $this->id;
				if($record->save()){
					$dontDelete[] = $record->id;
				} else {
					$errors['email'] = $c;
					$errors['error'] = $record->errors;
				}
				         
			} else { // обновляем описание
				$dontDelete[] = $found->id;
				if(isset($phone_comments[$k])){
					$fk = trim(strip_tags($phone_comments[$k]));
					if($found->description != $fk){
						$found->description = $fk;
						$found->save(true, array('description'));
						if(!$found->save(true,array('description'))){

						}
					}
				}
			}
		}
		}
		if($add === false){
			// Удаляем все категории, которых не было в массиве
			if(sizeof($dontDelete) > 0) {
				$cr = new CDbCriteria;
				$cr->addNotInCondition('id', $dontDelete);

				OrgsPhones::model()->deleteAllByAttributes(array(
					'org'=>$this->id,
				), $cr);
			} else { // удаляем все телефоны, т.к. пустой массив
				// Delete all relations
				OrgsPhones::model()->deleteAllByAttributes(array(
					'org'=>$this->id,
				));
			}  
		}
		if(!empty($errors))
			return $errors;
		else
			return true;
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
