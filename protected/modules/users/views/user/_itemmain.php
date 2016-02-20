<?php
$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$org->id, 'dash'=>'-', 'itemurl'=>$org->url));
if(!empty($model->user_id)){
	$user_avatar = $model->user->getAvatar();
	$user_name = $model->user->fullname;
} else {
	$user_avatar = '/img/avatar.png';
	$user_name = $model->name;
}
?>
<div class="oblects_view m-b-10">
<div class="media">
<div class="pull-left">
<div class="iAvatar">
<img class="lv-img-lg hide" src="<?php echo $user_avatar; ?>" alt="">
</div>

</div>
<div class="media-body">
<div class="iAuthor">
		<span class="hide" style="color:#333;font-size:17px;font-weight:300;display:block;line-height:1.3em;"><?php echo $user_name; ?></span>
		<div  style="font-weight:bold;"><?php 
echo CHtml::link($org->title,$url,array('class'=>'nocolor')); 
?></div>
		<div  class="m-t-5 m-b-10 c-gray f-11" style="display:block;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created); ?>
		</div>
	</div>


<div class="row">
<div class="col-xs-12 f-12" style="line-height:1.2em;margin-top:3px;">
<?php echo nl2br(CHtml::encode($model->text)); ?>
</div> 
</div>
</div>
</div>
</div>