<?php
// $model->saveCounters(array('views_count'=>1));

if(!empty($model->user_id)){
	$user_avatar = $model->user->getAvatar();
	$user_name = $model->user->fullname;
} else {
	$user_avatar = '/img/avatar.png';
	$user_name = $model->name;
}
$countComments = $model->countComments;
/*
$city_c = '';
if(isset($model->city))
   $city_c .= $model->city->city_url;
if(isset($model->city) && isset($model->city->countryid))
    $city_c .= '-'.$model->city->countryid->country_url;
$review_url = Yii::app()->createAbsoluteUrl('/review/view', array('id'=>$model->id, 'dash'=>'-', 'url'=>$city_c));
*/

?>
<div class="innerReviewInColor">
<div class="iReview">
<div class="iTable pull-left">
<div class="iAvatar">
	<img class="img-circle" src="<?php echo $user_avatar; ?>" /></div>
	<div class="iAuthor">
		<span style="color:#333;font-size:17px;font-weight:300;"><?php echo $user_name; ?></span>
	</div>
	<div class="iAuthor" style="padding-top:3px;">
		<span class="c-gray f-11" ><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created); ?></span>
	</div>
</div>

<div class="ratinge pull-right" style="padding-top:9px;">
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

$star_text =  array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем');

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

?>

</div>
<div class="clearfix"></div>
<div class="row">
<div class="col-xs-12 f-12" style="line-height:1.2em;margin-top:3px;">
<?php echo nl2br(CHtml::encode($model->text)); ?>
</div> 
</div>
	<div class="row">
	<div class="col-sm-6 col-xs-12">
	<?php
	if(!Yii::app()->user->isGuest && Yii::app()->user->id == $model->user_id){
	  //  $url = Yii::app()->createAbsoluteUrl('review/update',array('id'=>$model->id));
	  //	echo CHtml::tag('div',array(),CHtml::link('Редактировать',$url,array()));
	}
	?>
	</div>
	<div class="col-sm-6 col-xs-12">
	<?php
	$vote = SpecVote::model()->find(array('condition'=>'review=:id and ip=:ip','params'=>array(':id'=>$model->id,':ip'=>$ip)));
	if($vote){ ?>

	<div class="vote text-right c9" style="margin-top:10px;" id="vote<?php echo $model->id; ?>">
   <span class="c-gray f-11" style="margin-right:5px;">Полезен ли отзыв?</span> <span class="user_votes c-9"><span  class="user_pro  <?php if($vote->vote == 1) { echo 'user_mine';} ?>"><i class="md md-thumb-up"></i></span> <span class="user_num"><?php echo $model->yes?$model->yes:' '; ?></span>  <span class="user_contra <?php if($vote->vote != 1) { echo 'user_mine';} ?>" ><i class="md md-thumb-down"></i></span> <span class="user_contra-num"><?php echo $model->no?$model->no:' '; ?></span></span></div>
	
	<?php } else {
       
	?>
  <div class="vote text-right c9 active" style="margin-top:10px;" id="vote<?php echo $model->id; ?>">
  <span class="c-gray f-11" style="margin-right:5px;">Полезен ли отзыв?</span> <span class="user_votes"><span onclick="toVoteSpec(<?php echo $model->id; ?>, 1);" class="user_pro"><i class="md md-thumb-up"></i></span> <span class="user_num"><?php echo $model->yes?$model->yes:' '; ?></span>  <span class="user_contra" onclick="toVoteSpec(<?php echo $model->id; ?>, 0);"><i class="md md-thumb-down"></i></span> <span class="user_contra-num"><?php echo $model->no?$model->no:' '; ?></span></span></div>
	<?php
	}
	?>
	</div>
	</div>
	<div class="row">
	<div class="col-xs-12">

	<?php 

	if($showcommenttext == true){ 

		 $comments = new CommentSpec('search');
         $comments->unsetAttributes();

        if(!empty($model->user_id)){
			$user_name = $model->user->fullname;
		} else {
			$user_name = $model->name;
		}

         if(!empty($_GET['CommentSpec']))
            $comments->attributes = $_GET['CommentSpec'];
         $criteria = new CDbCriteria;
         $criteria->limit = 3;
         $criteria->with = array('user');
         $criteria->condition = 't.object_pk='.$org_id.' and t.id_parent='.$model->id.'  and t.status='.CommentSpec::STATUS_APPROVED;
         $dataProviderComments = new CActiveDataProvider('CommentSpec', array(
                'criteria' => $criteria,
                'sort'=>array(
                    'defaultOrder' => 't.created DESC',
                ),
                'pagination' => false,
            ));
         
         $this->renderPartial('application.modules.comments.views.comment._review_comments_spec',array('dataProviderComments'=>$dataProviderComments,'model'=>$model,'org_id'=>$org_id, 'countComments'=>$countComments));
		

		
	 }
	 	?>

	</div> 
	</div>
	
</div>
</div>