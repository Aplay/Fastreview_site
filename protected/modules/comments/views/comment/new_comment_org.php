<?php
/**
 * @var $this Controller
 * @var $form CActiveForm
 */

				        
// Load module
$module = Yii::app()->getModule('comments');
// Validate and save comment on post request
$comment = $module->processRequest($model);
// Load model comments
$comments = Comment::getObjectComments($model);

$themeUrl = '/themes/bootstrap_311/';



?>
<!-- Modal -->
<div id="leave_comment" class="modal fade" tabindex="-1" role="dialog" style="display:none;">

	<div class="modal-dialog">
		<div class="modal-content">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'comment-create-form',
				'action'=>'#comment-create-form',
				'htmlOptions'=>array( 'role'=>'form'),
				'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'afterValidate' => "js: function(form, data, hasError) {\n"
                                                ."    //if no error in validation, send form data with Ajax\n"
                                                ."    if (! hasError) {\n"
                                                ."	   $('#leave_comment .modal-body.for_form,#leave_comment .modal-footer').hide();\n"
                                                ."	   $('#leave_comment .modal-body.success').show();\n"
                                                ."	  // $('#leave_comment').modal('hide');\n"
                                                ."    // location.reload();\n"
                                                ."    }\n"
                                                ."    return false;\n"
                                                ."}\n"
                ),
			)); 
			
			?>
		    <div class="modal-header" style="border-bottom:0">
			<div style="width:100%;padding:10px 20px; text-align: center; font-size:22px; position: relative;">
			Отзыв <span style="color:#ccc;"><?php echo $model->title; ?></span>
			<div style="position:absolute; top:0; right:0;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px">×</button></div>
			</div>
            </div>
            <div class="modal-body success" style="display:none; padding: 0 20px; text-align: center; margin:0 auto 40px auto;">
            Спасибо за Ваш отзыв! Ваш отзыв будет опубликован после проверки.
            </div>
			<div class="modal-body for_form" style="padding: 0 20px">
            	<div class="org_rating" style="text-align: center; margin:0 auto 0px auto;">
            	<span style="font-size:18px;">Ваша оценка:</span><br>
            	
				<?php
				$value = 0;

				if ($model->rating_id && isset($model->rating) && !empty($model->rating->vote_average) && is_numeric($model->rating->vote_average) ){
				         
				          $value = round($model->rating->vote_average,0);
				          if($value > 5)
				          	$value = 5;
				         
				  }
				 $data_starred = array(0=>"", 1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем');
			/*	 echo $form->hiddenField($comment,'rating',array('value'=>$value)); */
				echo $form->hiddenField($comment,'rating');
				$this->widget('CStarRating',array(
				    'value'=>0,
				    'name'=>'star_rating_review_modal_'.$model->id,
				    'cssFile'=>$themeUrl.'css/star_rating.css',
				    'starWidth'=>25,
				    'minRating'=>1,
				    'maxRating'=>5,
				    'htmlOptions'=>array('style'=>'display:inline-block;margin-top:10px;margin-bottom:0px'),
				    'titles'=>array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем'),
				    'focus'=>'function(){
				    	$(".star-rating > a").attr("title", "");
				    	$("#star_text_show").html($(this).attr("title"))

					}',
					'blur'=>'function(){
						var crat = ["","Ай-ай-ай, не советую","Так себе, могло быть и лучше","Вполне нормально","Да, мне нравится","Супер, советую всем"];
				    	$("#star_text_show").html(crat[$("#Comment_rating").val()])
					}',
				    'callback'=>'
				        function(){
				        		$("#star_text_show").html($(this).attr("title"))
				        		$("#Comment_rating").attr("value", $(this).val());
				                }'
				  ));
				echo "<br/>";
			//	echo "<div id='mystar_voting_modal'></div>";
				?>
				</div>
				<div style="font-size: 16px; width: 100%; height: 16px; margin: 0px 0 10px; text-align: center;" id="star_text_show" data-starred="<?php echo $data_starred[$value]; ?>"><?php echo $data_starred[$value]; ?></div>
				<?php echo $form->error($comment,'rating',array('style'=>'text-align:center')); ?>

				<div class="clearfix"></div>
				<div style="height:24px;width:100%"></div>
				<?php if(Yii::app()->user->isGuest) { ?>

				<div class="form-group">
					<?php echo $form->labelEx($comment,'name',array('class'=>'')); ?>
					<?php echo $form->textField($comment,'name',array('class'=>'form-control')); ?>
					<?php echo $form->error($comment,'name'); ?>
				</div>

				<div class="form-group">
					<?php echo $form->labelEx($comment,'email',array('class'=>'')); ?>
					<?php echo $form->textField($comment,'email',array('class'=>'form-control')); ?>
					<?php echo $form->error($comment,'email'); ?>
				</div><?php  ?>
			<?php } ?>
				<div class="form-group">
				<?php echo $form->labelEx($comment,'text'); ?>
					<?php echo $form->textArea($comment,'text', array('style'=>'word-wrap: break-word; min-height: 140px; width: 100%; resize: none; overflow:hidden;', 'class'=>'form-control')); ?>
		            <?php echo $form->error($comment,'text'); ?>
				</div>

				<?php if(Yii::app()->user->isGuest) { ?>
				<?php  ?><div class="form-group">
					<?php echo CHtml::activeLabelEx($comment, 'verifyCode');?>
					<?php 
				$session = Yii::app()->session;
                $prefixLen = strlen(MyCCaptchaAction::SESSION_VAR_PREFIX);
                foreach ($session->keys as $key) {
                    if (strncmp(MyCCaptchaAction::SESSION_VAR_PREFIX, $key, $prefixLen) == 0)
                        $session->remove($key);
                }
                $unique_id=rand(0,99999);

					$this->widget('CCaptcha', array(
						// 'captchaAction'=>'/site/captcha',

						'clickableImage'=>true,
						'showRefreshButton'=>false,
						'imageOptions'=>array('style'=>'cursor:pointer','id' => 'captcha'.$unique_id),
					)) ?>
					<br/>
					<?php echo CHtml::activeTextField($comment, 'verifyCode', array('class'=>'form-control')); ?>
					<?php echo $form->error($comment,'verifyCode'); ?>
				</div>
				<?php }  ?>
              
                 
			</div> <!-- / .modal-body -->
			<div class="modal-footer no-border-t" style="border-top:0; text-align: center;margin-top:20px;margin-bottom:20px;">
				<button type="submit" class="btn btn-lg btn-primary" style="font-size: 20px;">Оставить отзыв</button>
				
			</div>
             <?php $this->endWidget(); ?>
		</div> <!-- / .modal-content -->
	</div> <!-- / .modal-dialog -->
	
</div> <!-- /.modal -->

<?php

// Display comments
if(!empty($comments))
{
	foreach($comments as $row)
	{
            
	?>
		<div itemscope itemtype="http://schema.org/Review" class="comment_review" id="comment_<?php echo $row->id; ?>">
			<meta itemprop="url" content="<?php echo Yii::app()->createAbsoluteUrl(Yii::app()->request->getUrl()); ?>" />
			<span itemprop="author" itemscope itemtype="http://schema.org/Person">
			<span class="name" itemprop="name">
			<?php 
            if(!empty($row->user_id)){
               // echo CHtml::link(CHtml::image($row->user->getAvatar(60),$row->user->username,array('width' => 30),array('style'=>'margin-right:5px;vertical-align:top')),Yii::app()->createUrl('/account/'.$row->user->username));
               // echo CHtml::link(CHtml::encode($row->user->username),Yii::app()->createUrl('/account/'.$row->user->username));
            	echo $row->user->username;
            } else {
                echo CHtml::encode($row->name);
                
            }
            ?></span></span> <span class="created">
            <meta itemprop="datePublished" content="<?php echo date('Y-m-d', strtotime($row->created)  + ($city_utc*60*60)); ?>" />
            <?php echo Yii::app()->dateFormatter->format('dd MMMM yyyy HH:mm',  date('Y-m-d H:i:s', strtotime($row->created)  + ($city_utc*60*60))); ?></span>
			
			<?php //echo CHtml::link('#', Yii::app()->request->getUrl().'#comment_'.$row->id)
			if($row->rating){
			 ?>
			
			<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"  class="review_rating">
			<meta itemprop="worstRating" content="1" />
			<meta itemprop="bestRating" content="5" />
			<meta itemprop="ratingValue" content="<?php echo $row->rating; ?>"/>
			<?php
			
			$this->widget('CStarRating',array(
			    'value'=>$row->rating,
			    'name'=>'star_rating_'.$row->id,
			    'cssFile'=>$themeUrl.'/css/star_rating_18.css',
			    'titles'=>array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем'),
			    'starWidth'=>18,
			    'minRating'=>1,
				'maxRating'=>5,
			    'readOnly'=>true,
			  //  'controller'=>'site'
			  ));
			?>
			</div>
			<div class="clearfix"></div>
			<?php } ?>
			<div itemprop="reviewBody" class="message">
				<?php 
                echo MHelper::String()->wordwrap(nl2br(CHtml::encode($row->text)),39,"\n",true);
                ?>
			</div>
                       
		</div>
	<?php
	}
}
?>
<?php
$cscript = "
$(document).ready(function(){
$('textarea').autosize({
	append:false
});
})
";
Yii::app()->clientScript->registerScript("commentscript", $cscript, CClientScript::POS_END);
?>