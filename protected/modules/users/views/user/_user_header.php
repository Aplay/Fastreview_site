<div class="card" >
<div class="card-body">
<div style="display: table; width:100%;height:172px;background-image:url('/img/bg_user.jpg');background-repeat: no-repeat;background-size: cover;">
	<div class="profile-block_up" >
	<div class="profile-block servant">
		<div class="panel profile-photo">
			<img id="profileAvatar" alt="" src="<?php echo $user->getAvatar(); ?>" class="my-avatar img-responsive">
		</div>
	</div>	
	</div>
	<div class="user_card_name">
	<?php echo $user->getShowname(); 
	if($user->id == Yii::app()->user->id){
	?>
	<div id="user_card_settings" onclick="$('#header_user_box').popover('hide');$('#user_profile_modal').modal();return false;"></div>
	<?php } ?>
	</div>
	<div class="clearfix"></div>
</div>
</div>
</div>