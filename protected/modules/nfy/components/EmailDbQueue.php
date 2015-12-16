<?php

class EmailDbQueue extends NfyDbQueue {

    public function send($message, $category = null, $class_name = null, $object_pk = null)
    {
        $queueMessage = $this->createMessage($message, $class_name, $object_pk);

        if ($this->beforeSend($queueMessage) !== true) {
            Yii::log(Yii::t('NfyModule.app', "Not sending message '{msg}' to queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');
            return;
        }

        $success = true;

        $subscriptions = NfyDbSubscription::model()->current()->withQueue($this->id)->matchingCategory($category)->findAll();


        $trx = $queueMessage->getDbConnection()->getCurrentTransaction() !== null ? null : $queueMessage->getDbConnection()->beginTransaction();

        // empty($subscriptions) &&
        if (!$queueMessage->save()) {
            Yii::log(Yii::t('NfyModule.app', "Failed to save message '{msg}' in queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_ERROR, 'nfy');
            return false;
        }

        foreach ($subscriptions as $subscription) {
            $subscriptionMessage = clone $queueMessage;
            $subscriptionMessage->subscription_id = $subscription->id;
            $subscriptionMessage->message_id = $queueMessage->id;
            if ($this->beforeSendSubscription($subscriptionMessage, $subscription->subscriber_id) !== true) {
                continue;
            }
            if(!$this->sendMessage($queueMessage)){
               Yii::log(Yii::t('NfyModule.app', "Failed to send message '{msg}' by email in queue {queue_label} for the subscription {subscription_id}.", array(
                    '{msg}' => $queueMessage->body,
                    '{queue_label}' => $this->label,
                    '{subscription_id}' => $subscription->id,
                )), CLogger::LEVEL_ERROR, 'nfy');
                $success = false;  
            }
            if($success){
                if (!$subscriptionMessage->save()) {
                    Yii::log(Yii::t('NfyModule.app', "Failed to save message '{msg}' in queue {queue_label} for the subscription {subscription_id}.", array(
                        '{msg}' => $queueMessage->body,
                        '{queue_label}' => $this->label,
                        '{subscription_id}' => $subscription->id,
                    )), CLogger::LEVEL_ERROR, 'nfy');
                    $success = false;
                }
            }

            $this->afterSendSubscription($subscriptionMessage, $subscription->subscriber_id);
        }

        $this->afterSend($queueMessage);

        if ($trx !== null) {
            $trx->commit();
        }

        Yii::log(Yii::t('NfyModule.app', "Sent message '{msg}' to queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');

        return $success;
    }

    protected function sendMessage($message)
    {

        $user = User::model()->findByPk($subscription->user_id);
        $mail = Yii::app()->mail;
        $mail->ClearAddresses();
        $mail->AddAddress($user->email, $user->fullname);
        $mail->Subject = 'Notification from '.Yii::app()->name;
        $mailer->Body = $message->body;
        $mailer->isHtml(false);
       // $mail->MsgHTML($message);
        if (!$mail->Send()) {
            Yii::log(Yii::t('NfyModule.app', 'Failed to send notification {message_id} to user {user_id} via email.', array('{message_id}' => $message->id, '{user_id}' => $subscription->user_id)), 'error', 'nfy');
            return false;
        } else {
            return true;
        }
    }

    protected function deliver($transport, $message, $subscription, $msg) {
        switch($transport) {
            default: return parent::delivery($transport, $message, $subscription, $msg);
            case 'email':
                $message = $this->formatLogMessage($msg, $message->user_id, $subscription->user_id);
                $user = User::model()->findByPk($subscription->user_id);
                $mail = Yii::app()->mailer;
                $mail->ClearAddresses();
                $mail->AddAddress($user->email, $user->firstname.' '.$user->lastname);
                $mail->Subject = 'Notification from '.Yii::app()->name;
                $mail->MsgHTML($message);
                if (!$mail->Send()) {
                    Yii::log(Yii::t('NfyModule.app', 'Failed to send notification {message_id} to user {user_id} via email.', array('{message_id}' => $message->id, '{user_id}' => $subscription->user_id)), 'error', 'nfy');
                }
                break;
            case 'xmpp':
                $message = $this->formatLogMessage($msg, $message->user_id, $subscription->user_id);
                $user = User::model()->findByPk($subscription->user_id);
                Yii::app()->jabberSender->sendMessage($user->jabber, $message);
                //! @todo too bad there's no way to stop sendMessage from dying on errors
                break;
        }
    }
	
}

?>