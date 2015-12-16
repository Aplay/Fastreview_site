<?php

/**
 * Saves sent messages and tracks subscriptions in a database.
 */

Yii::import('application.models.Notifications');
Yii::import('application.models.UserNotification');
Yii::import('application.models.Issue');
Yii::import('application.modules.users.models.User');
class NfyDbQueue extends NfyQueue
{

    public $name;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->blocking) {
            throw new CException(Yii::t('NfyModule.app', 'Not implemented. DbQueue does not support blocking.'));
        }
        $this->attachQueue($this->name);
    }

    protected function attachQueue($name)
    {
        $queue_table = NfyDbQueues::model()->find('name=:name', array(':name'=>$name));
        if($queue_table === null){
            $queue_table = new NfyDbQueues();
            $queue_table->name = $name;
            $queue_table->label = $this->label;
            if (!$queue_table->save()){
           
            throw new CException(Yii::t('NfyModule.app', 'Failed to subscribe {subscriber_id} to {queue_label}', array('{subscriber_id}' => $subscriber_id, '{queue_label}' => $this->label)));
            }
        }
        $this->id = $queue_table->id;
        $this->name = $queue_table->name;
        return $queue_table;
    }
    /**
     * Creates an instance of NfyDbMessage model. The passed message body may be modified, @see formatMessage().
     * This method may be overriden in extending classes.
     * @param string $body message body
     * @return NfyDbMessage
     */
    protected function createMessage($body, $object_pk = null, $subject_pk = null)
    {
        $message_body = new NfyMessageBody;
        $message_body->setAttributes(array(
            'body' => $body,
            'object_pk'=>$object_pk
        ), false);
        if (!$message_body->save()){
            Yii::log(Yii::t('NfyModule.app', "Not sending message '{msg}' to queue {queue_label}.", array('{msg}' => $body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');
            return;
        }
        $message = new NfyDbMessage;
        $message->setAttributes(array(
            'queue_id' => $this->id,
            'timeout' => $this->timeout,
            'sender_id' => Yii::app()->hasComponent('user') ? Yii::app()->user->getId() : null,
            'status' => NfyMessage::AVAILABLE,
            'message_body_id'=>$message_body->id,
            'body'=>$body,
            'object_pk'=>$object_pk,
            'subject_pk'=>$subject_pk
        ), true);
        return $this->formatMessage($message);
    }

    protected function createNotification($body, $notification_id, $object_pk = null, $subject_pk = null)
    {
        $sender_id = Yii::app()->hasComponent('user') ? Yii::app()->user->getId() : null;
        
        $message = new NfyDbMessage;
        $message->setAttributes(array(
            'queue_id' => $this->id,
            'timeout' => $this->timeout,
            'sender_id' => $sender_id,
            'status' => NfyMessage::AVAILABLE,
            'body'=>$body,
            'object_pk'=>$object_pk,
            'notification_id'=>$notification_id,
            'subject_pk'=>$subject_pk
        ), true);
        return $this->formatMessage($message);
    }

    /**
     * Formats the body of a queue message. This method may be overriden in extending classes.
     * @param NfyDbMessage $message
     * @return NfyDbMessage $message
     */
    protected function formatMessage($message)
    {
        return $message;
    }

    /**
     * @inheritdoc
     */
    public function send($message, $category = null, $object_pk = null, $subject_pk = null, $notification = null)
    {
        

        $by_email = false; $notifications = null;

        $subscriptions = NfyDbSubscription::model()->current()->withQueue($this->id)->matchingCategory($category)->findAll();

        if($message == null && !empty($notification)){
            
            $notifications = Notifications::model()->findByPk($notification);

            if($notifications){
                $message = $notifications->body;
            }

            if(empty($message)){
                $success = false;
            } else {
                $queueMessage = $this->createNotification($message, $notifications->id, $object_pk, $subject_pk);
                
            }

            
        } else {
            $queueMessage = $this->createMessage($message, $object_pk, $subject_pk);
        }

        if ($this->beforeSend($queueMessage) !== true) {
            Yii::log(Yii::t('NfyModule.app', "Not sending message '{msg}' to queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');
            return;
        }

        $success = true;

        

        $trx = $queueMessage->getDbConnection()->getCurrentTransaction() !== null ? null : $queueMessage->getDbConnection()->beginTransaction();

       
        if (!$queueMessage->save()) {
            Yii::log(Yii::t('NfyModule.app', "Failed to save message '{msg}' in queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_ERROR, 'nfy');
            return false;
        }
        
        if(!empty($subscriptions)){
            foreach ($subscriptions as $subscription) {
                $simple = true;
                $subscriptionMessage = clone $queueMessage;
                $subscriptionMessage->subscription_id = $subscription->id;
                $subscriptionMessage->message_id = $queueMessage->id;
                if ($this->beforeSendSubscription($subscriptionMessage, $subscription->subscriber_id) !== true) {
                    continue;
                }
                if($notifications){
                    $user_notification = UserNotification::model()->find('user_id=:user_id and notification_id=:notification_id',array(':user_id'=>$subscription->subscriber_id,':notification_id'=>$notification));
                
                    if($user_notification && $user_notification->by_email == true){
                        if(!$this->sendMessageByEmail($queueMessage, $subscription)) {
                           Yii::log(Yii::t('NfyModule.app', "Failed to send message '{msg}' by email in queue {queue_label} for the subscription {subscription_id}.", array(
                                '{msg}' => $queueMessage->body,
                                '{queue_label}' => $this->label,
                                '{subscription_id}' => $subscription->id,
                            )), CLogger::LEVEL_ERROR, 'nfy');
                            $success = false;  
                        }
                    }

                    if($notifications->available == false){ // not send simple notification
                        $simple = false;
                    }
                    
                } 
                if ($simple == false) {
                    continue;
                }
                
                if (!$subscriptionMessage->save()) {
                    Yii::log(Yii::t('NfyModule.app', "Failed to save message '{msg}' in queue {queue_label} for the subscription {subscription_id}.", array(
                        '{msg}' => $queueMessage->body,
                        '{queue_label}' => $this->label,
                        '{subscription_id}' => $subscription->id,
                    )), CLogger::LEVEL_ERROR, 'nfy');
                    $success = false;
                }

                $this->afterSendSubscription($subscriptionMessage, $subscription->subscriber_id);
            }
        }

        $this->afterSend($queueMessage);

        if ($trx !== null) {
            $trx->commit();
        }

        Yii::log(Yii::t('NfyModule.app', "Sent message '{msg}' to queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');

        return $success;
    }

    protected function sendMessageByEmail($message, $subscription)
    {

        $user = User::model()->findByPk($subscription->subscriber_id);
        $userMain = User::model()->findByPk($message->sender_id);
        $text = $message->notification->email;
        $sub = $message->notification->email_subject;
        
        if(!empty($message->subject_pk)){
            $subject = User::model()->findByPk($message->subject_pk);
            $subject_url =  CHtml::link($subject->username, Yii::app()->createAbsoluteUrl('/users/user/view', array('url' => $subject->username)));
            $text = str_replace('{user}', $subject_url, $text);
        }

      if(!$sub)
        $sub = 'Notification from '.Yii::app()->name;
      $send = SendMail::send($user->email,$sub,$text,true);
      if($send === true){
        return true;
      } else {
        Yii::log(Yii::t('NfyModule.app', 'Failed to send notification {message_id} to user {user_id} via email.', array('{message_id}' => $message->id, '{user_id}' => $subscription->subscriber_id)), 'error', 'nfy');
        return false;
      }

    }
    public function deleteMessage($sender_id, $category = null, $subject_pk = null, $notification = null){

        $subscriptions = NfyDbSubscription::model()->current()->withQueue($this->id)->matchingCategory($category)->findAll();
        if(!empty($subscriptions)){
            foreach ($subscriptions as $subscription) {
                $messages = NfyDbMessage::model()->findAll('notification_id=:notification_id and sender_id=:sender_id and subscription_id=:subscription_id and subject_pk=:subject_pk',
                    array(':notification_id'=>$notification,':sender_id'=>$sender_id,':subscription_id'=>$subscription->id,':subject_pk'=>$subject_pk));

                if(!empty($messages)){
                    foreach($messages as $message){
                        if(!empty($message->message_id)){
                            NfyDbMessage::model()->deleteByPk($message->message_id);
                        }
                        NfyDbMessage::model()->deleteByPk($message->id);
                    }
                }
            }
        }

        
    }
    /**
     * @inheritdoc
     */
    public function peek($subscriber_id = null, $limit = -1, $status = NfyMessage::AVAILABLE)
    {
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;
        $messages = NfyDbMessage::model()->withQueue($this->id)->withSubscriber($subscriber_id)->withStatus($status, $this->timeout)->findAll(array('index' => $pk, 'limit' => $limit));
        return NfyDbMessage::createMessages($messages);
    }

    public function peekReadable($subscriber_id = null, $readable)
    {
        $limit = -1;
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;
        $messages = NfyDbMessage::model()->withSubscriber($subscriber_id)->withReadable($readable, $this->timeout, $this->id)->findAll(array('index' => $pk, 'limit' => $limit));
        return NfyDbMessage::createMessages($messages);
    }

     public function peekReadableAll($subscriber_id = null)
    {
        $limit = -1;
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;
        $messages = NfyDbMessage::model()->withSubscriber($subscriber_id)->withReadableAll($this->timeout, $this->id)->findAll(array('index' => $pk, 'limit' => $limit));
        return NfyDbMessage::createMessages($messages);
    }
    

    /**
     * @inheritdoc
     */
    public function reserve($subscriber_id = null, $limit = -1)
    {
        return $this->receiveInternal($subscriber_id, $limit, self::GET_RESERVE);
    }

    /**
     * @inheritdoc
     */
    public function receive($subscriber_id = null, $limit = -1)
    {
        return $this->receiveInternal($subscriber_id, $limit, self::GET_DELETE);
    }

    /**
     * Perform message extraction.
     */
    protected function receiveInternal($subscriber_id = null, $limit = -1, $mode = self::GET_RESERVE)
    {
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;
        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
        $messages = NfyDbMessage::model()->withQueue($this->id)->withSubscriber($subscriber_id)->available($this->timeout)->findAll(array('index' => $pk, 'limit' => $limit));
        if (!empty($messages)) {
          //  $now = new DateTime('now', new DateTimezone('UTC'));
            $now = new DateTime('now');
            if ($mode === self::GET_DELETE) {
                $attributes = array('status' => NfyMessage::DELETED, 'deleted_on' => $now->format('Y-m-d H:i:s'));
            } elseif ($mode === self::GET_RESERVE) {
                $attributes = array('status' => NfyMessage::RESERVED, 'reserved_on' => $now->format('Y-m-d H:i:s'));
            }
            NfyDbMessage::model()->updateByPk(array_keys($messages), $attributes);
        }
        if ($trx !== null) {
            $trx->commit();
        }
        return NfyDbMessage::createMessages($messages);
    }

    /**
     * @inheritdoc
     */
    public function delete($message_id, $subscriber_id = null)
    {
        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;

        $messages = NfyDbMessage::model()->withQueue($this->id)->withSubscriber($subscriber_id)->reserved($this->timeout)->findAllByPk($message_id, array('select' => $pk, 'index' => $pk));

        $message_ids = array_keys($messages);
        $now = new DateTime('now');

    

        NfyDbMessage::model()->updateByPk($message_ids, array('status' => NfyMessage::DELETED, 'deleted_on' => $now->format('Y-m-d H:i:s')));
        if ($trx !== null) {
            $trx->commit();
        }
        return $message_ids;
    }

    public function deleteRightNow($message_id)
    {

        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;

        $messages = NfyDbMessage::model()->withQueue($this->id)->findAllByPk($message_id, array('select' => $pk, 'index' => $pk));

        $message_ids = array_keys($messages);
        $now = new DateTime('now');

        NfyDbMessage::model()->updateByPk($message_ids, array('status' => NfyMessage::DELETED, 'deleted_on' => $now->format('Y-m-d H:i:s')));
        if ($trx !== null) {
            $trx->commit();
        }
        return $message_ids;
    }

    public function readRightNow($message_id)
    {

        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;

        $messages = NfyDbMessage::model()->withQueue($this->id)->findAllByPk($message_id, array('select' => $pk, 'index' => $pk));

        $message_ids = array_keys($messages);
        $now = new DateTime('now');

        NfyDbMessage::model()->updateByPk($message_ids, array('is_read' => true));
        if ($trx !== null) {
            $trx->commit();
        }
        return $message_ids;
    }

    /**
     * @inheritdoc
     */
    public function release($message_id, $subscriber_id = null)
    {
        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;
        $messages = NfyDbMessage::model()->withQueue($this->id)->withSubscriber($subscriber_id)->reserved($this->timeout)->findAllByPk($message_id, array('select' => $pk, 'index' => $pk));
        $message_ids = array_keys($messages);
        NfyDbMessage::model()->updateByPk($message_ids, array('status' => NfyMessage::AVAILABLE));
        if ($trx !== null) {
            $trx->commit();
        }
        return $message_ids;
    }

    /**
     * Releases timed-out messages.
     * @return array of released message ids
     */
    public function releaseTimedout()
    {
        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
        $pk = NfyDbMessage::model()->tableSchema->primaryKey;
        $messages = NfyDbMessage::model()->withQueue($this->id)->timedout($this->timeout)->findAllByPk($message_id, array('select' => $pk, 'index' => $pk));
        $message_ids = array_keys($messages);
        NfyDbMessage::model()->updateByPk($message_ids, array('status' => NfyMessage::AVAILABLE));
        if ($trx !== null) {
            $trx->commit();
        }
        return $message_ids;
    }

    /**
     * @inheritdoc
     */
    public function subscribe($subscriber_id, $label =null, $categories = null, $exceptions = null)
    {

        $trx = NfyDbSubscription::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbSubscription::model()->getDbConnection()->beginTransaction();

        $subscription = NfyDbSubscription::model()->withQueue($this->id)->withSubscriber($subscriber_id)->find();

        if ($subscription === null) {

            $subscription = new NfyDbSubscription;
            $subscription->setAttributes(array(
                'queue_id' => $this->id,
                'subscriber_id' => $subscriber_id,
            ));

            if (!$subscription->save()){
               
                throw new CException(Yii::t('NfyModule.app', 'Failed to subscribe {subscriber_id} to {queue_label}', array('{subscriber_id}' => $subscriber_id, '{queue_label}' => $this->label)));
            }
        } else if ($subscription->is_deleted) {
            $subscription->is_deleted = false;
        }

        $this->saveSubscriptionCategories($categories, $subscription, false);
        $this->saveSubscriptionCategories($exceptions, $subscription, true);

        if ($trx !== null) {
            $trx->commit();
        }

        return true;
    }

    protected function saveSubscriptionCategories($categories, $subscription, $are_exceptions = false)
    {
        if ($categories === null)
            return true;
        if (!is_array($categories))
            $categories = array($categories);



        foreach ($categories as $category) {
            $subscription_cat = NfyDbSubscriptionCategory::model()->findByAttributes(array('subscription_id' => $subscription->id, 'category'=>$category, 'is_exception'=>$are_exceptions));
            try {
                if($subscription_cat){
                    $subscriptionCategory = $subscription_cat;
                } else {
                    $subscriptionCategory = new NfyDbSubscriptionCategory;
                }
               
                $subscriptionCategory->setAttributes(array(
                    'subscription_id' => $subscription->primaryKey,
                    'category' => str_replace('*', '%', $category),
                    'is_exception' => $are_exceptions ? 1 : 0,
                ));

                if (!$subscriptionCategory->save()) {
                    throw new CException(Yii::t('NfyModule.app', 'Failed to save category {category} for subscription {subscription_id}', array('{category}' => $category, '{subscription_id}' => $subscription->primaryKey)));
                }
            } catch (CDbException $ex) {
                // this is probably due to constraint violation, ignore
                // TODO: distinct from constraint violation and other database exceptions
            }
        }
 
        return true;
    }

    /**
     * @inheritdoc
     * @param boolean @permanent if false, the subscription will only be marked as removed and the messages will remain in the storage; if true, everything is removed permanently
     */
    public function unsubscribe($subscriber_id, $categories = null, $permanent = true)
    {
        $trx = NfyDbSubscription::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbSubscription::model()->getDbConnection()->beginTransaction();
        $subscription = NfyDbSubscription::model()->withQueue($this->id)->withSubscriber($subscriber_id)->matchingCategory($categories)->find();
        if ($subscription !== null) {
            $canDelete = true;
            if($categories !== null) {
                // it may be a case when some (but not all) categories are about to be unsubscribed
                // if that happens and this subscription ends up with some other categories, only given categories
                // should be deleted, not the whole subscription
                NfyDbSubscriptionCategory::model()->deleteByPk(array_map(function($c) { return $c->id; }, $subscription->categories));

                $canDelete = NfyDbSubscriptionCategory::model()->countByAttributes(array('subscription_id' => $subscription->id)) <= 0;
            }

            if($canDelete) {
                if ($permanent) {
                    $subscription->delete();
                } else {
                    $subscription->saveAttributes(array('is_deleted' => true));
                }
            }
        }
        if ($trx !== null) {
            $trx->commit();
        }
    }

    /**
     * @inheritdoc
     */
    public function isSubscribed($subscriber_id, $category = null)
    {
        $subscription = NfyDbSubscription::model()->current()->withQueue($this->id)->withSubscriber($subscriber_id)->matchingCategory($category)->find();
        return $subscription !== null;
    }

    /**
     * @param mixed $subscriber_id
     * @return NfyDbSubscription[]
     */
    public function getSubscriptions($subscriber_id = null)
    {
        NfyDbSubscription::model()->current()->withQueue($this->id)->with(array('categories'));
        $dbSubscriptions = $subscriber_id === null ? NfyDbSubscription::model()->findAll() : NfyDbSubscription::model()->findByAttributes(array('subscriber_id' => $subscriber_id));
        return NfyDbSubscription::createSubscriptions($dbSubscriptions);
    }

    /**
     * Removes deleted messages from the storage.
     * @return array of removed message ids
     */
    public function removeDeleted()
    {
        $trx = NfyDbMessage::model()->getDbConnection()->getCurrentTransaction() !== null ? null : NfyDbMessage::model()->getDbConnection()->beginTransaction();
      //  $pk = NfyDbMessage::model()->tableSchema->primaryKey;

      //  $messages = NfyDbMessage::model()->withQueue($this->id)->deleted()->findAllByPk($message_id, array('select' => $pk, 'index' => $pk));
        $messages = NfyDbMessage::model()->withQueue($this->id)->deleted()->findAllByPk($message_id);
        $message_ids = array();
        if(!empty($messages)){
            foreach($messages as $message){
                $message_ids[] = $message->id;
                $message->delete();
            }
        }
      //  $message_ids = array_keys($messages);
      //  NfyDbMessage::model()->deleteByPk($message_ids);
        if ($trx !== null) {
            $trx->commit();
        }
        return $message_ids;
    }
}
