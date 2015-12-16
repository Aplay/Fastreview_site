<?php


class MyDbQueue extends NfyDbQueue {

	public $name;

	public function init()
    {
        parent::init();
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

	protected function formatMessage($message)
    {
    	$message_body = new NfyMessageBody;
        $message_body->setAttributes(array(
            'body' => $message['body'],
        ), false);
        if (!$message_body->save()){
            Yii::log(Yii::t('NfyModule.app', "Not sending message '{msg}' to queue {queue_label}.", array('{msg}' => $body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');
            return;
        }
        $message['message_body_id'] = $message_body->id,
        return $message;
    }
}