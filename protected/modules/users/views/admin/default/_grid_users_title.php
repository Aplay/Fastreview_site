
<div class="follower">
<a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>$data->username)); ?>"><img src="<?php echo $data->getAvatar(); ?>" alt="" title="<?php echo $data->username; ?>" class="follower-avatar"></a>
	<div class="body">
		<div class="follower-controls"></div>
		 <a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>$data->username)); ?>" class="follower-username"><?php echo $data->username; ?></a>
	</div>
</div>
