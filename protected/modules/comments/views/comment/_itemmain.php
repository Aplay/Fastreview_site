<?php
$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$org->city->url, 'id'=>$org->id,  'itemurl'=>$org->url));
 
if(!empty($model->user_id)){
	$user_avatar = $model->user->getAvatar();
	$user_name = $model->user->fullname;
} else {
	$user_avatar = '/img/avatar.png';
	$user_name = $model->name;
}
?>
<div class="innerReviewInColor">
<div class="iReview">
<div class="iTable pull-left">
<div class="iAvatar">
	<img class="img-circle" src="<?php echo $user_avatar; ?>" /></div>
	<div class="iAuthor">
		<span style="color:#333;font-size:17px;font-weight:300;display:block;line-height:1.3em;"><?php echo $user_name; ?></span>
		<span class="c-gray f-11" style="display:block;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created); ?></span>
	</div>
</div>
<div class="clearfix"></div>
<div style="margin-top:6px;">
<span style="font-weight:bold;"><?php 
echo CHtml::link($org->title,$url,array('class'=>'nocolor')); 
?></span>
<?php 
$address = '';
if($org->street) { 
	$address .= $org->street;
}
 if($org->dom) { 
 	if($address)
 		$address .= ', ';
 	$address .= $org->dom; 
 }
 if($address && $org->city->title)
 		$address .= ', ';
 $address .= $org->city->title;
 ?>
 <span class="c-gray f-11"><?php echo $address; ?></span>
</div>
<div class="ratinge pull-left" style="margin:8px 4px 8px 0;line-height:1.2em;">
<?php

$themeUrl = Yii::app()->theme->baseUrl;
?>
<?php

if ($model->rating)
{
 $value = $model->rating;
 if($value > 5)
    $value = 5;
} else {
	$value = 0;
}

$star_text =  array(0=>'',1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем');

$this->widget('ext.widgets.MyStarRating',array(

    'value'=>$value,
    'name'=>'star_rating_review_comment_'.$model->id,
    'cssFile'=>$themeUrl.'/css/star_rating2.css',
    'starWidth'=>25,
    'minRating'=>1,
    'maxRating'=>5,
    'titles'=>$star_text,
    'readOnly'=>true,
    'htmlOptions'=>array('class'=>'rl-star', 'id'=>'rl-star-comment-'.$model->id),
                   
    
  ));
echo ' <span class="c-gray f-11">'.$star_text[$value].'</span>';
?>

</div>
<div class="clearfix"></div>
<div class="row">
<div class="col-xs-12 f-12" style="line-height:1.2em;margin-top:3px;">
<?php echo nl2br(CHtml::encode($model->text)); ?>
</div> 
</div>
</div>
</div>