<?php
// $data->saveCounters(array('views_count'=>1));

if(!empty($data->user_id)){
	$user_avatar = $data->user->getAvatar();
	$user_name = '<a class="nocolor" href="'.Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$data->user->username)).'">'.$data->user->getShowname().'</a>';
	$avatar = '<a href="'.Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$data->user->username)).'"><img alt="" src="'.$user_avatar.'" class="lv-img-md" /></a>';
} else {
	$user_avatar = '/img/avatar.png';
	$user_name = $data->name;
	$avatar = '<img alt="" src="'.$user_avatar.'" class="lv-img-md" />';
}
// $countComments = $data->countComments;


?>
<li id="li-comment-<?php echo $data->id; ?>" class="comment " itemtype="http://schema.org/Review" itemscope="" itemprop="review">
<div id="comment-<?php echo $data->id; ?>" class="comment_container media m-b-30">
<div class="pull-left">
<?php echo $avatar; ?>
</div>
<div class="media-body">		
<p class="meta m-b-0">
	<strong itemprop="author"><?php echo $user_name; ?></strong> &ndash; 
	<time  itemprop="datePublished">
	<?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $data->created); ?></time>:
</p>

<div class="description" itemprop="description">
<p><?php echo nl2br(CHtml::encode($data->text)); ?></p>
</div>
		</div>
	</div>
	</li>





