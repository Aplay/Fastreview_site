<?php
// $model->saveCounters(array('views_count'=>1));

if(!empty($model->user_id)){
	$user_avatar = $model->user->getAvatar();
	$user_name = $model->user->getShowname();
} else {
	$user_avatar = '/img/avatar.png';
	$user_name = $model->name;
}
// $countComments = $model->countComments;
$sql = "SELECT COUNT(id) FROM comments WHERE object_pk=".$model->object_pk." and id_parent is NULL and status=".Comment::STATUS_APPROVED;
$countComments = Yii::app()->db->createCommand($sql)->queryScalar();

/*
$city_c = '';
if(isset($model->city))
   $city_c .= $model->city->city_url;
if(isset($model->city) && isset($model->city->countryid))
    $city_c .= '-'.$model->city->countryid->country_url;
$review_url = Yii::app()->createAbsoluteUrl('/review/view', array('id'=>$model->id, 'dash'=>'-', 'url'=>$city_c));
*/

?>

<div class="media m-b-30">
	<div class="pull-left">
	    <img alt="" src="<?php echo $user_avatar; ?>" class="lv-img-md" />
	</div>
	<div class="media-body m-t-0">
	<p class="m-b-5 nocolor f-15 f-500"><?php echo $user_name; ?> 
	<span class="c-gray f-12" >&nbsp;<?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created); ?></span></p>
	<p class="m-b-0 p-b-0 f-12" style="line-height:1.2em;margin-top:3px;"><?php echo nl2br(CHtml::encode($model->text)); ?></p>
	</div>
</div>
