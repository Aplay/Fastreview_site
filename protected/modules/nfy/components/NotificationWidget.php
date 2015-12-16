<?php

class NotificationWidget extends CWidget
{
	/**
	 * @var array Keys must be queue component names and values must be arrays of NfyMessage objects.
	 */
    public $messages = array();

	public $whiteIcons = false;

	public $elements;

	protected function countMessages()
	{
		$count = 0;
		foreach($this->messages as $queueName => $messages) {
			$count += count($messages);
		}
		return $count;
	}
    
	public function createMenuItem()
	{
		$count = $this->countMessages();
		return array(
			'url' => '/nfy/queue',
			'label' => ($count > 0 ? ('<span class="label label-warning">' . $count . '</span>') : ''),
			'visible' => !Yii::app()->user->isGuest,
			'icon' => ($this->whiteIcons ? 'white ' : '').'comment',
			'id' => $this->getId(),
		);
	}

	public function show(){
		echo $this->elements;
	}

	public function run()
	{
		$elements = '';
		
		$cnt = 0;
		$extraCss = '';

		Yii::import('nfy.controllers.QueueController');
		if ($this->owner instanceof QueueController) {
			$queueController = $this->owner;
		} else {
			$queueController = new QueueController('queue', Yii::app()->getModule('nfy'));
		}
        if(!empty($this->messages)) {  
		foreach($this->messages as $queueName => $messages) {
			foreach($messages as $data) {
			//	$text = addcslashes($message->body, "'\r\n");
				
     

				$text = $data->body;
				$ago = MHelper::Date()->timeAgo($data->created_on, array('short'=>true)); 
				$notification_icon = 'fa-truck bg-info';
				
				if(!empty($data->subject_pk)  && !empty($data->notification_id)  && $data->class_name == 'Users'){
					$user = User::model()->findByPk($data->subject_pk);
					if(!$user)
						return;
					$url_user =  CHtml::link($user->fullname, Yii::app()->createUrl('/users/user/view', array('url' => $user->username)), array('style'=>'text-decoration:underline'));
					$text = str_replace('{user}', $url_user, $text);
					$notification_icon = 'fa-users bg-success';


				} else if(!empty($data->subject_pk)){
					$user = User::model()->findByPk($data->subject_pk);
					if(!$user)
						continue;
					$url =  CHtml::link($user->username, Yii::app()->createUrl('/users/user/view', array('url' => $user->username)), array('style'=>'text-decoration:underline'));
					$text = str_replace('{user}', $url, $text);
				}


				$detailsUrl = $queueController->createMessageUrl($queueName, $data);
				
				$elements .= '<div class="notification">';
				
				$elements .= '<div class="notification-title text-info">'.Yii::t('site','New contact').'</div>';
				
				$elements .= '<div class="notification-description">'.$text.'</div>
                            <div class="notification-ago">'.$ago.'</div>
							<div class="notification-icon fa '.$notification_icon.'"></div>
							</div>';
				
				
			}
		}
		}

		$label = Yii::t('NfyModule.app', 'Mark all as read');
		//! @todo fix this
		//$deleteUrl = $this->owner->createUrl('/nfy/message/mark');
		$widgetId = $this->getId();

		$this->elements = $elements;
		
    }


}
