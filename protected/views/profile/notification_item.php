<?php
// see also nfy/components/NotificationWidget

$text = $data->body;
$ago = MHelper::Date()->timeAgo($data->created_on, array('short'=>true)); 
$notification_icon = 'fa-truck bg-info';

	$notification_icon = 'fa-suitcase bg-info';
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
			return;
		$url =  CHtml::link($user->username, Yii::app()->createUrl('/users/user/view', array('url' => $user->username)), array('style'=>'text-decoration:underline'));
		$text = str_replace('{user}', $url, $text);
	}


$detailsUrl = $queueController->createMessageUrl('queue', $data);

echo       '<div class="notification">';

	if($data->notification_id){
		echo '<div class="notification-title text-info">'.Yii::t('site',$data->notification->form_title).'</div>
			<div class="notification-description">'.$text.'</div>';
            
            

		  
		
		if($data->notification->form_title == 'New contact'){

				$status_friend = $user->isFriendOf(Yii::app()->user->id);
        		if($status_friend == UserFriend::CONTACT_REQUEST) {// дружба еще не подтверждена
           		echo '<div class="notification-ago pull-left" style="padding-top:5px">'.$ago.'</div>';
		?>
		<div class="pull-right">
		<a data-original-title="Contact" data-toggle="tooltip" data-placement="left" href="<?php echo Yii::app()->createUrl('/profile/acceptContact', array('id'=>$data->subject_pk)); ?>" title="" class="btn btn-xs btn-outline btn-success tooltip-success add-tooltip"><i class="fa fa-check"></i></a>
		<a data-original-title="Cancel contact" data-toggle="tooltip" data-placement="left" href="<?php echo Yii::app()->createUrl('/profile/rejectContact', array('id'=>$data->subject_pk)); ?>" title="" class="btn btn-xs btn-outline btn-danger tooltip-danger add-tooltip"><i class="fa fa-times"></i></a>
		</div>
		<?php	
                 } else {
                 	echo '<div class="notification-ago pull-left">'.$ago.'</div>';
                 }
	        } else {
	        	echo '<div class="notification-ago pull-left">'.$ago.'</div>';
	        }
	        ?>
		<div class="clearfix"></div>
		<?php
	}
	
			



if($data->is_read == false){
	echo '<div class="notseen"></div>';
}
echo		'</div>';



// remove notice from active
Yii::app()->queue->readRightNow($data->id);			
?>
