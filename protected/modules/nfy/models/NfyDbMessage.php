<?php

/**
 * This is the model class for table "{{nfy_messages}}".
 *
 * The followings are the available columns in table '{{nfy_messages}}':
 * @property integer $id
 * @property integer $queue_id
 * @property timestamp $created_on
 * @property integer $sender_id
 * @property integer $message_id
 * @property integer $subscription_id
 * @property integer $status
 * @property integer $timeout
 * @property timestamp $reserved_on
 * @property timestamp $deleted_on
 * @property integer $message_body_id
 *
 * The followings are the available model relations:
 * @property NfyDbMessage $mainMessage
 * @property NfyDbMessage[] $subscriptionMessages
 * @property NfyDbSubscription $subscription
 * @property Users $sender
 */
class NfyDbMessage extends CActiveRecord
{
    public $body;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return NfyDbMessage the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nfy_messages';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('queue_id, sender_id', 'required', 'except' => 'search'),
            array('sender_id, subscription_id, timeout, message_id, message_body_id, object_pk, subject_pk, notification_id, status', 'numerical', 'integerOnly' => true),
            array('is_read', 'boolean', 'allowEmpty'=>true),
            array('body', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'mainMessage' => array(self::BELONGS_TO, 'NfyDbMessage', 'message_id'),
            'message_body' => array(self::BELONGS_TO, 'NfyMessageBody', 'message_body_id'),
            'sender' => array(self::BELONGS_TO, Yii::app()->getModule('nfy')->userClass, 'sender_id'),
            'subscription' => array(self::BELONGS_TO, 'NfyDbSubscription', 'subscription_id'),
            'subscriptionMessages' => array(self::HAS_MANY, 'NfyDbMessage', 'message_id'),
            'queue' => array(self::BELONGS_TO, 'NfyDbQueues', 'queue_id'),
            'notification' => array(self::BELONGS_TO, 'Notifications', 'notification_id'),
            'subject' => array(self::BELONGS_TO, 'User', 'subject_pk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'queue_id' => 'Queue ID',
            'created_on' => 'Created On',
            'sender_id' => 'Sender ID',
            'message_id' => 'Message ID',
            'subscription_id' => 'Subscription ID',
            'status' => 'Status',
            'timeout' => 'Timeout',
            'reserved_on' => 'Reserved On',
            'deleted_on' => 'Deleted On',
            'message_body_id'=>'Message Body'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('queue_id', $this->queue_id);
        $criteria->compare('sender_id', $this->sender_id);
        $criteria->compare('message_id', $this->message_id);
        $criteria->compare('subscription_id', $this->subscription_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('timeout', $this->timeout);
        $criteria->compare('message_body_id', $this->message_body_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeSave()
    {
        if ($this->isNewRecord && $this->created_on === null) {
            $now = new DateTime('now');
            $this->created_on = $now->format('Y-m-d H:i:s');
        }
        return true;
    }

    public function __clone()
    {
        $this->primaryKey = null;
        $this->subscription_id = null;
        $this->isNewRecord = true;
    }

    public function scopes()
    {
        $t = $this->getTableAlias(true);
        return array(
            'deleted' => array('condition' => "$t.status=" . NfyMessage::DELETED),
        );
    }

    public function available($timeout = null)
    {
        return $this->withStatus(NfyMessage::AVAILABLE, $timeout);
    }

    public function reserved($timeout = null)
    {
        return $this->withStatus(NfyMessage::RESERVED, $timeout);
    }

    public function timedout($timeout = null)
    {
        if ($timeout === null) {
            $this->getDbCriteria()->mergeWith(array('condition' => '1=0'));
            return $this;
        }
        $now = new DateTime("-$timeout seconds");
        $t = $this->getTableAlias(true);
        $criteria = array(
            'condition' => "($t.status=" . NfyMessage::RESERVED . " AND $t.reserved_on <= :timeout)",
            'params' => array(':timeout' => $now->format('Y-m-d H:i:s')),
        );
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function withStatus($statuses, $timeout = null)
    {
        if (!is_array($statuses))
            $statuses = array($statuses);

        if ($timeout !== null)
            $now = new DateTime("-$timeout seconds");

        $t = $this->getTableAlias(true);
        $criteria = new CDbCriteria;
        $conditions = array();

        foreach ($statuses as $status) {
            switch ($status) {
                case NfyMessage::AVAILABLE:
                    $conditions[] = "$t.status=" . $status;
                    if ($timeout !== null) {
                        $conditions[] = "($t.status=" . NfyMessage::RESERVED . " AND $t.reserved_on <= :timeout)";
                        $criteria->params = array(':timeout' => $now->format('Y-m-d H:i:s'));
                    }
                    break;
                case NfyMessage::RESERVED:
                    if ($timeout !== null) {
                        $conditions[] = "($t.status=$status AND $t.reserved_on > :timeout)";
                        $criteria->params = array(':timeout' => $now->format('Y-m-d H:i:s'));
                    } else {
                        $conditions[] = "$t.status=" . $status;
                    }
                    break;
                case NfyMessage::DELETED:
                    $conditions[] = "$t.status=" . $status;
                    break;
            }
        }

        if (!empty($conditions)) {
            $criteria->addCondition('(' . implode(') OR (', $conditions) . ')', 'OR');
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    public function withReadableAll($timeout = null, $queue_id)
    {

        if ($timeout !== null)
            $now = new DateTime("-$timeout seconds");

        $t = $this->getTableAlias(true);
        $criteria = new CDbCriteria;
        $conditions = array();

        $conditions[] = "$t.queue_id=:queue_id AND $t.status=" . NfyMessage::AVAILABLE;
                    if ($timeout !== null) {
                        $conditions[] = "($t.status=" . NfyMessage::RESERVED . " AND $t.reserved_on <= :timeout)";
                        $criteria->params = array(':queue_id'=>$queue_id, ':timeout' => $now->format('Y-m-d H:i:s'));
                    } else {
                        $criteria->params = array(':queue_id'=>$queue_id);
                    }

        if (!empty($conditions)) {
            $criteria->order = "$t.created_on DESC";
            $criteria->addCondition('(' . implode(') OR (', $conditions) . ')', 'OR');
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    public function withReadable($readable, $timeout = null, $queue_id)
    {

        if ($timeout !== null)
            $now = new DateTime("-$timeout seconds");

        $t = $this->getTableAlias(true);
        $criteria = new CDbCriteria;
        $conditions = array();

        $conditions[] = "$t.queue_id=:queue_id AND $t.status=" . NfyMessage::AVAILABLE. " AND $t.is_read = :is_read";
                    if ($timeout !== null) {
                        $conditions[] = "($t.status=" . NfyMessage::RESERVED . " AND $t.reserved_on <= :timeout AND $t.is_read=:is_read)";
                        $criteria->params = array(':queue_id'=>$queue_id, ':timeout' => $now->format('Y-m-d H:i:s'),':is_read'=>$readable);
                    } else {
                        $criteria->params = array(':queue_id'=>$queue_id, ':is_read'=>$readable);
                    }

        if (!empty($conditions)) {
            $criteria->order = "$t.created_on DESC";
            $criteria->addCondition('(' . implode(') OR (', $conditions) . ')', 'OR');
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    public function withQueue($queue_id)
    {
        $t = $this->getTableAlias(true);
        $pk = $this->tableSchema->primaryKey;
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $t . '.queue_id=:queue_id',
            'params' => array(':queue_id' => $queue_id),
            'order' => "$t.$pk ASC",
        ));
        return $this;
    }

    public function withSubscriber($subscriber_id = null)
    {
        if ($subscriber_id === null) {
            $t = $this->getTableAlias(true);
            $criteria = array('condition' => "$t.subscription_id IS NULL");
        } else {
            $schema = $this->getDbConnection()->getSchema();
            $criteria = array(
                'together' => true,
                'with' => array('subscription' => array(
                    'condition' => $schema->quoteSimpleTableName('subscription') . '.subscriber_id=:subscriber_id',
                    'params' => array(':subscriber_id' => $subscriber_id),
                )),
            );
        }
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public static function createMessages($dbMessages)
    {
        if (!is_array($dbMessages)) {
            $dbMessages = array($dbMessages);
        }
        $result = array();

        foreach ($dbMessages as $dbMessage) {
            $attributes = $dbMessage->getAttributes();
            $attributes['subscriber_id'] = $dbMessage->subscription_id === null ? null : $dbMessage->subscription->subscriber_id;
            unset($attributes['queue_id']);
            unset($attributes['subscription_id']);
         //   unset($attributes['mimetype']);
            $message = new NfyMessage;
            $message->setAttributes($attributes);

            if(!empty($dbMessage->notification_id)){
                $message->notification = $dbMessage->notification;
                $message->body = $dbMessage->notification->body;
                $message->class_name = $dbMessage->notification->class_name;
                $message->object_pk = $dbMessage->object_pk;
                $message->notification_id = $dbMessage->notification_id;
                $message->subject_pk = $dbMessage->subject_pk;

            } else {
                $message->body = $dbMessage->message_body->body;
                $message->class_name = $dbMessage->message_body->class_name;
                $message->object_pk = $dbMessage->message_body->object_pk;
            }
            
            
            $result[] = $message;
        }
        
        return $result;
    }

    public function beforeDelete(){

        if(parent::beforeDelete()){
            $check = NfyDbMessage::model()->countByAttributes(array('message_body_id'=>$this->message_body_id), 'id != :id', array(':id'=>(int)$this->id));
            if(empty($check)){
                NfyMessageBody::model()->deleteByPk($this->message_body_id);
            }
            return true;
        }
        return false;
    }
}
