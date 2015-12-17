<?php
Yii::import('application.modules.users.models.User');
Yii::import('application.modules.catalog.models.Objects');

/**
 * This is the model class for table "Comments".
 *
 * The followings are the available columns in table 'Comments':
 * @property integer $id
 * @property integer $user_id
 * @property integer $object_pk
 * @property integer $status
 * @property string $email
 * @property string $name
 * @property string $text
 * @property string $created
 * @property string $updated
 * @property string $ip_address
 * @method approved()
 * @method orderByCreatedAsc()
 * @method orderByCreatedDesc()
 */
class Comment extends BaseModel
{

	const STATUS_WAITING = 0;
	const STATUS_APPROVED = 1;
	const STATUS_SPAM = 2;

	/**
	 * @var string
	 */
	public $verifyCode;
	public $reCaptcha;


	/**
	 * @var int status for new comments
	 */
	public $defaultStatus;

	private $_curr;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Objects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Initialize
	 */
	public function init()
	{
		$this->defaultStatus = Comment::STATUS_WAITING;
		return parent::init();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'comments';
	}

	public function scopes()
	{
		$alias = $this->getTableAlias();
		return array(
			'orderByCreatedAsc'=>array(
				'order'=>$alias.'.created ASC',
			),
			'orderByCreatedDesc'=>array(
				'order'=>$alias.'.created DESC',
			),
			'waiting'=>array(
				'condition'=>$alias.'.status='.self::STATUS_WAITING,
			),
			'approved'=>array(
				'condition'=>$alias.'.status='.self::STATUS_APPROVED,
			),
			'spam'=>array(
				'condition'=>$alias.'.status='.self::STATUS_SPAM,
			)
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// $codeEmpty = !CCaptcha::checkRequirements() || !Yii::app()->user->isGuest;
		/*if(YII_DEBUG) // For tests
			$codeEmpty=true;*/

		if($this->scenario == 'subcomment'){
			$r =  array(
				array('text', 'filter','filter' =>'strip_tags'),
	            array('text', 'filter','filter' =>'trim'),
	            array('id_parent,status', 'required'),
	            array('text', 'required', 'message'=>'Необходим комментарий.'),
	            array('id_parent,status', 'numerical', 'integerOnly'=>true),
	            array('text', 'length', 'min'=>3),
	            array('text', 'length', 'max'=>5000),
	            array('created', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
				array('updated', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
			);
		} else {
			$r =  array(
	            array('name, text, email', 'filter','filter' =>'strip_tags'),
	            array('name, text, email', 'filter','filter' =>'trim'),
	            array('rating', 'required', 'message'=>'Необходимо указать оценку'),
				array('email, name, text', 'required'),
				array('email', 'email'),
				array('status, created, updated', 'required', 'on'=>'update'),
				array('rating, id_parent, yes, no', 'numerical', 'integerOnly'=>true),
				array('name', 'length', 'max'=>50),
				array('text', 'length', 'min'=>3),
	            array('text', 'length', 'max'=>5000),
	            array('reCaptcha', 'ReCaptchaValidator',  'secret'=>Yii::app()->reCaptcha->secret, 'message'=>'Неправильный код проверки'),
	            array('created', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	            array('updated', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
				// array('verifyCode','captcha','allowEmpty'=>$codeEmpty),
				// Search
				array('id, user_id, id_parent, yes, no, status, email, name, text, created, updated,rating,ip_address', 'safe', 'on'=>'search'),
			);
			if (Yii::app()->user->isGuest) {
				if (!($this->reCaptcha = Yii::app()->request->getPost(ReCaptchaValidator::CAPTCHA_RESPONSE_FIELD))) {
	            	$r[] = array('reCaptcha', 'required');
	        	}
	        }
    	}
        return $r;
	}
        public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
            'fromuser'=>array(self::BELONGS_TO, 'User', 'object_pk'),
            'obj'=>array(self::BELONGS_TO, 'Objects', 'object_pk'),
            'children'=>array(self::HAS_MANY, 'Comment', 'id_parent', 'condition'=>'children.status='.self::STATUS_APPROVED, 'order'=>'children.created'),
            'countComments'=>array(self::STAT, 'Comment', 'object_pk', 'condition'=>'id_parent is NULL  and status='.self::STATUS_APPROVED, 'select'=>'count(id)')
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'         => 'ID',
			'user_id'    => Yii::t('CommentsModule.core','Автор'),
			'status'     => Yii::t('CommentsModule.core','Статус'),
			'email'      => Yii::t('CommentsModule.core','Email'),
			'name'       => 'Имя',
			'text'       => 'Ваш отзыв',
			'created'    => Yii::t('CommentsModule.core','Дата создания'),
			'updated'    => Yii::t('CommentsModule.core','Дата обновления'),
			'owner_title'=> Yii::t('CommentsModule.core','Владелец'),
			'verifyCode' => Yii::t('CommentsModule.core','Код проверки'),
			'ip_address' => Yii::t('CommentsModule.core','IP адрес'),
			'reCaptcha'=>'Код проверки',
			'id_parent' => 'Id Parent',
			'yes' => 'Yes',
			'no' => 'No',
		);
	}

	/**
	 * Before save.
	 */
	public function beforeSave()
	{
		if($this->isNewRecord)
		{

			$this->ip_address = Yii::app()->request->userHostAddress;
			$this->created = date('Y-m-d H:i:s');
			if($this->status == self::STATUS_APPROVED){
				if($this->rating) {
					$this->starRating($this->object_pk, $this->rating);
				}
			}
		} else {
			$this->_curr = self::findByPk($this->id);
			if($this->status == self::STATUS_APPROVED && $this->_curr->status != self::STATUS_APPROVED){
	            if($this->rating) {
	            	$this->starRating($this->object_pk, $this->rating);
	            }
	        } else if($this->status != self::STATUS_APPROVED && $this->_curr->status == self::STATUS_APPROVED){
	        	if($this->rating) {
			        if($this->_curr->rating != $this->rating){
				       $this->starRatingBack($this->object_pk, $this->_curr->rating);
				    } else {
				       $this->starRatingBack($this->object_pk, $this->rating);
				    }
				}
	        } else {

	        	if($this->_curr->rating != $this->rating){
		        	$this->starRatingBack($this->object_pk, $this->_curr->rating);
		        	$this->starRating($this->object_pk, $this->rating);
		        } else { // do nothing with rating

		        }
		        
	    	}
		}
		
		$this->updated = date('Y-m-d H:i:s');
		return parent::beforeSave();
	}

	public function beforeDelete()
	{
		if($this->status == self::STATUS_APPROVED){
			$this->starRatingBack($this->object_pk, $this->rating);
		}
		if($this->id_parent == null){
			self::model()->deleteAllByAttributes(array('id_parent'=>$this->id));
		}
		return parent::beforeDelete();
	}
	public function starRating($object_pk, $ratingAjax) {

      $object_pk = (int)$object_pk;


		$model = Objects::model()->findByPk($object_pk);

      if($model){
          $rating = ObjectsRating::model()->findByAttributes(array(
            'object'=>$object_pk
          ));
          // если не было рейтинга - делаем
          if(!$rating)
          {
            $rating = new ObjectsRating;
            $rating->object = $object_pk;
            $rating->vote_count = 1;
            $rating->vote_sum = $ratingAjax;
            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
            $rating->save(false);

            $model->rating_id = $rating->id;
            $model->save(false,array('rating_id'));                    
          } else {
            $rating->vote_count = $rating->vote_count + 1;
            $rating->vote_sum = $rating->vote_sum + $ratingAjax;
            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
            if(!$rating->save()){
               // VarDumper::dump($rating->errors); die(); // Ctrl + X  Delete line
            }
          }
          
    }
    return true;
    }

    public function starRatingBack($object_pk, $ratingAjax) 
    {
        $object_pk = (int)$object_pk;


		$model = Objects::model()->findByPk($object_pk);

        if($model)
        {
           
            if($model->rating)
            {     
            	$rating = $model->rating;
            	if(($rating->vote_count - 1) <= 0)  
            	{
            		$rating->vote_count = 0;
            		$rating->vote_sum = 0;
            		$rating->vote_average = 0;
            		$rating->save();
            		return true;
            	}           
	            $rating->vote_count = $rating->vote_count - 1;
	            $rating->vote_sum = $rating->vote_sum - $ratingAjax;
	            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
	            if($rating->save()){

	            } else {
	            	// VarDumper::dump($rating->errors); die(); // Ctrl + X	Delete line
	            }
        	} 
    	} 
    	return true;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($additionalCriteria = null)
	{
		$criteria=new CDbCriteria;

		if($additionalCriteria !== null)
			$criteria->mergeWith($additionalCriteria);

		$criteria->compare('id',$this->id);
		$criteria->compare('object_pk',$this->object_pk);
		$criteria->compare('status',$this->status);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('id_parent',$this->id_parent);
		$criteria->compare('yes',$this->yes);
		$criteria->compare('no',$this->no);

		if($this->user_id)
			$criteria->compare('user_id',$this->user_id);

		$sort=new CSort;
		$sort->defaultOrder = $this->getTableAlias().'.created DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getStatuses()
	{
		return array(
			self::STATUS_WAITING  => Yii::t('CommentsModule.core', 'Ждет одобрения'),
			self::STATUS_APPROVED => Yii::t('CommentsModule.core', 'Подтвержден'),
			// self::STATUS_SPAM     => Yii::t('CommentsModule.core', 'Спам'),
		);
	}

	/**
	 * @return string status title
	 */
	public function getStatusTitle()
	{
		$statuses = self::getStatuses();
		return $statuses[$this->status];
	}

	/**
	 * @return string
	 */
	public function getOwner_title()
	{
		if(!$this->isNewRecord)
		{


			$model = Objects::model()->findByPk($this->object_pk);
			if($model){

				return $model->title;
			}
		}
		return '';
	}
    public function getViewUrl()
	{
		
                $model = Objects::model()->findByPk($this->object_pk);
   				
                if($model){
                	$ret = '';
                	if((get_class($model) == 'Objects') && (!empty($model->city_id))){
					$ret .= $model->city->url;
					} 
					$ret .= '/'.$model->url;
                	return Yii::app()->createUrl($ret);  
                }
  
	}
	public function getViewUrlAdmin()
	{
		
                $model = Objects::model()->findByPk($this->object_pk);
   				
                if($model){
                	$ret = '';
                	if(get_class($model) == 'Objects'){
					$ret .= Yii::app()->createUrl("/catalog/admin/company/update", array("id"=>$model->id));
					} 
                	return Yii::app()->createAbsoluteUrl($ret);  
                }
  
	}
	public static function truncate(Comment $model, $limit)
	{
		$result = $model->text;
		$length = mb_strlen($result,'utf-8');
		if($length > $limit)
		{
			return mb_substr($result,0,$limit,'utf-8').'...';
		}
		return $result;
	}

	/**
	 * Load object comments
	 * @static
	 * @param CActiveRecord $model
	 * @return array
	 */
	public static function getObjectComments(CActiveRecord $model)
	{
		return Comment::model()
			->approved()
			->orderByCreatedDesc()
			->findAll(array('condition'=>'object_pk='.$model->id .' and id_parent is null'));
	}
	public static function getLastComments($limit,$city_id)
	{
		return Comment::model()
			->with('obj')
			->approved()
			->orderByCreatedDesc()
			->findAll(array('condition'=>'t.id_parent is null and obj.city_id='.$city_id,'limit'=>$limit));
	}
        public static function getObjectCommentsFrom(CActiveRecord $model)
	{
		return Comment::model()
			->approved()
			->orderByCreatedDesc()
			->findAllByAttributes(array(
				'user_id'=>$model->id
		));
	}
        public static function getAllComments(CActiveRecord $model)
	{
	       $users_adv = Products::model()
                            ->active()
                            ->findAllByAttributes(array(
                                'user_id'=>$model->id
                            ));
 
               $ids=array();   
                foreach($users_adv as $adv):        
                    $ids[]=$adv->id;
                endforeach;
               
           //   $criteria = new CDbCriteria();
            //   $criteria->addInCondition("object_pk", $ids); 

               return Comment::model()
			->approved()
			->orderByCreatedDesc()
			->findAllByAttributes(array('object_pk'=>$ids));
           }
        
        
}       