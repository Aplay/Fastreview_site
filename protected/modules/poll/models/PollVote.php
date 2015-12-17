<?php

/**
 * This is the model class for table "{{poll_vote}}".
 *
 * The followings are the available columns in table '{{poll_vote}}':
 * @property string $id
 * @property string $choice_id
 * @property string $poll_id
 * @property string $user_id
 * @property string $ip_address
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property User $user
 * @property PollChoice $choice
 * @property Poll $poll
 */
class PollVote extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @return PollVote the static model class
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
    return 'poll_vote';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    return array(
      array('choice_id', 'required'),
      array('choice_id, user_id, vote', 'numerical', 'integerOnly'=>true),
      array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
      array('ip_address', 'length', 'max'=>50),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(
      'user' => array(self::BELONGS_TO, 'User', 'user_id'),
      'choice' => array(self::BELONGS_TO, 'PollChoice', 'choice_id'),
     // 'poll' => array(self::BELONGS_TO, 'Poll', 'poll_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'choice_id' => 'Choice',
    );
  }

  /**
   * Before a PollVote is saved.
   */
  public function beforeSave()
  {
    if(parent::beforeSave()) {
    	if($this->isNewRecord) {
	    	$this->ip_address = Yii::app()->request->userHostAddress;
	    	if(!Yii::app()->user->isGuest)
	    		$this->user_id = Yii::app()->user->id;
	    	// Relation may not exist yet so find it normally
		    $choice = PollChoice::model()->findByPk($this->choice_id);
		    if ($choice) {
		     // $choice->votes += 1;
		      $choice->save();
		    }
		    else {
		      return FALSE;
		    }
		}
	    
	    return true;
    } else
        return false;
  }

  /**
   * After a PollVote is deleted.
   */
  public function afterDelete()
  {
    $this->choice->votes -= 1;
    $this->choice->save();

    parent::afterDelete();
  }

  
  /**
   * Before a PollVote is deleted.
   */
  public function beforeDelete()
  {
    if (!$this->poll->userCanCancelVote($this)) {
      return FALSE;
    }
    return parent::beforeDelete();
  }

}
