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
<div class="media oblects_view_main">
<div class="pull-left">
<div class="iAvatar">
<img class="lv-img-lg" src="<?php echo $user_avatar; ?>" alt="">
</div>

</div>
<div class="media-body">
<div class="iAuthor">
		<span style="color:#333;font-size:17px;font-weight:300;display:block;line-height:1.3em;"><?php echo $user_name; ?></span>
		<span class="c-gray f-11" style="display:block;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created); ?></span>
	</div>
<span style="font-weight:bold;"><?php 
echo CHtml::link($org->title,$url,array('class'=>'nocolor')); 
?></span>

<div class="row">
<div class="col-xs-12 f-12" style="line-height:1.2em;margin-top:3px;">
<?php echo nl2br(CHtml::encode($model->text)); ?>
</div> 
</div>
</div>
</div>