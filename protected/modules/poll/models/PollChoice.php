<?php

/**
 * This is the model class for table "{{poll_choice}}".
 *
 * The followings are the available columns in table '{{poll_choice}}':
 * @property string $id
 * @property string $poll_id
 * @property string $label
 * @property string $votes
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property Poll $poll
 * @property PollVote[] $pollVotes
 */
class PollChoice extends CActiveRecord
{
  /**
   * @var integer representing a closed poll status
   */
  const STATUS_CLOSED = 0;

  /**
   * @var integer representing an open poll status
   */
  const STATUS_OPEN = 1;

  const TYPE_PLUS = 1;
  const TYPE_MINUS = 2;

  /**
   * Returns the static model of the specified AR class.
   * @return PollChoice the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'poll_choice';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    return array(
      array('label', 'filter', 'filter' => 'strip_tags'),
	  array('label', 'filter','filter' =>'trim'),
      array('label, org_id, type', 'required'),
      array('weight, user_id, org_id, status, yes, no', 'numerical', 'integerOnly'=>true),
      array('votes', 'length', 'max'=>11),
      array('label', 'length', 'max'=>255),
      array('label', 'checkUniqueLabel'),
      array('label', 'nolinks'),
      array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
      array('ip_address', 'length', 'max'=>50),
      array('status, org_id, type, label', 'safe', 'on'=>'search'),
    );
  }

 public function checkUniqueLabel($attribute,$params)
 {
   $this->label = MHelper::String()->simple_ucfirst($this->label);
  if(PollChoice::model()->count('LOWER(label)=:label AND org_id=:org_id AND type=:type',
      array(':label'=>MHelper::String()->toLower($this->label),':org_id'=>$this->org_id, ':type'=>$this->type)) > 0)
  { 
  	  $this->addError($attribute, 'Название уже существует');
      return false;
  } else { 
  	  return true;
  }
 }

 public function nolinks($attribute,$params)
 {
  if (false !== mb_strpos($this->$attribute, '://')) {
     $this->addError($attribute, 'Размещение веб-ссылок запрещено');
      return false;
  }
  return true;
 }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(
      'user' => array(self::BELONGS_TO, 'User', 'user_id'),
      'org' => array(self::BELONGS_TO, 'Objects', 'org_id'),
    //  'poll' => array(self::BELONGS_TO, 'Poll', 'poll_id'),
      'pollVotes' => array(self::HAS_MANY, 'PollVote', 'choice_id'),
      'totalVotes' => array(self::STAT, 'PollChoice', 'choice_id', 'select' => 'SUM(pollVotes)'),
    );
  }
  /** 
   * @return array additional query scopes
   */
  public function scopes()
  {
    return array(
      'open'=>array(
        'condition'=>'status='. self::STATUS_OPEN,
       ),  
      'closed'=>array(
        'condition'=>'status='. self::STATUS_CLOSED,
       ),  
       'latest'=>array(
        'order'=>'id DESC',
       ),  
    );  
  }
  /**
   * @return array default scope.
   */
  public function defaultScope()
  {
    return array(
     // 'order' => 'weight ASC, label ASC',
    );
  }
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'status' => 'Status',
      'org_id'=>'Object ID',
      'type'=>'Type',
      'label'=>'Название'
    );
  }	

  public function typeLabels()
  {
    return array(
      self::TYPE_PLUS => 'Преимущества',
      self::TYPE_MINUS   => 'Недостатки',
    );
  }
  /**
   * @return array customized status labels
   */
  public function statusLabels()
  {
    return array(
      self::STATUS_CLOSED => 'Неактивно',
      self::STATUS_OPEN   => 'Активно',
    );
  }
  /**
   * Returns the text label for the specified status.
   */
  public function getStatusLabel($status)
  {
    $labels = self::statusLabels();

    if (isset($labels[$status])) {
      return $labels[$status];
    }

    return $status;
  }
  /**
   * Returns the text label for the specified status.
   */
 
   public function getTypeLabel($type)
  {
    $labels = self::typeLabels();

    if (isset($labels[$type])) {
      return $labels[$type];
    }

    return $type;
  }

  /**
   * @return array of weights for sorting
   */
  public function getWeights()
  {
    $weights = range(-5, 5);

    return array_combine($weights, $weights);
  }

  public function search()
  {
    $criteria=new CDbCriteria;

    $criteria->compare('org_id',$this->org_id);
    $criteria->compare('type',$this->type);
    $criteria->compare('status',$this->status);
    $criteria->compare('LOWER(label)',MHelper::String()->toLower($this->label),true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination' => array(
                'pageSize' => 30,
        ),
    ));
  }

  public function userCanCancelVote($pollVote)
  {
    if (!$pollVote->id || $this->status == self::STATUS_CLOSED) {
      return FALSE;
    }

    $module = Yii::app()->getModule('poll');
    $isGuest = Yii::app()->user->isGuest;
    $guestsCanCancel = $module->ipRestrict && $module->allowGuestCancel;

    if (!$isGuest || ($isGuest && $guestsCanCancel)) {
      return TRUE;
    }

    return FALSE;
  }

  public function userCanVote()
  {
    if ($this->status == self::STATUS_CLOSED) return FALSE;

    // Setup global query attributes
    $where = array('and', 'poll_id=:poll_id', 'user_id=:user_id');
    if(!Yii::app()->user->isGuest){
		$params = array(':poll_id' => $this->id, ':user_id' => (int) Yii::app()->user->id);
	} else {
		$where = array('and','poll_id=:poll_id','ip_address=:ip_address');
        $params[':ip_address'] = $_SERVER['REMOTE_ADDR'];
        $params[':poll_id'] = $this->id;
	}

    // Add IP restricted attributes if needed
    if (Yii::app()->getModule('poll')->ipRestrict === TRUE && Yii::app()->user->isGuest) {
     // $where[] = 'ip_address=:ip_address';
      $where = array('and','poll_id=:poll_id','ip_address=:ip_address');
      $params[':ip_address'] = $_SERVER['REMOTE_ADDR'];
      $params[':poll_id'] = $this->id;
    }

    // Retrieve true/false if a vote exists on poll by user
    $result = (bool) Yii::app()->db->createCommand(array(
      'select' => 'id',
      'from'   => 'poll_vote',
      'where'  => $where,
      'params' => $params,
    ))->queryRow();

    return !$result;
  }

  public function beforeSave()
  {
    if(parent::beforeSave()) {
    	if($this->isNewRecord) {

	    	$this->ip_address = Yii::app()->request->userHostAddress;

	    	if(!Yii::app()->user->isGuest)
	    		$this->user_id = Yii::app()->user->id;
    	}
 
		return true;
    } else
        return false;
  }
}
