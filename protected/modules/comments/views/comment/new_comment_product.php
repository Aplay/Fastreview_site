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


?>

<div  id="leave_comment">

	<div>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'comment-create-form',
		'action'=>'#comment-create-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
		'htmlOptions'=>array('class'=>'form-horizontal', 'role'=>'form')
	)); ?>

	<?php if(Yii::app()->user->isGuest) { ?>
		<?php  ?>
		<div class="form-group">
			<?php echo $form->labelEx($comment,'name',array('class'=>'col-lg-4 control-label')); ?>
			<div class="col-lg-8"><?php echo $form->textField($comment,'name',array('class'=>'form-control wi192')); ?></div>
			<?php echo $form->error($comment,'name'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($comment,'email',array('class'=>'col-lg-4 control-label')); ?>
			<div class="col-lg-8"><?php echo $form->textField($comment,'email',array('class'=>'form-control wi192')); ?></div>
			<?php echo $form->error($comment,'email'); ?>
		</div><?php  ?>
	<?php } ?>
		<div class="form-group">
		<div class="col-lg-12"><?php echo $form->labelEx($comment,'text'); ?></div>
		</div>
		<div class="form-group">
			<div class="col-lg-12">
			<?php echo $form->error($comment,'text'); ?>
			<?php echo $form->textArea($comment,'text', array('style'=>'width: 420px; padding: 7px; font-size: 12px; border: 1px solid #C9C7AF; outline: none;  resize: none;',
                'id'=>'txtarea','rows'=>'3', 'class'=>'form-control wi192')); ?></div>
			
		</div>

		<?php if(Yii::app()->user->isGuest) { ?>
		<?php  ?><div class="form-group">
			<?php echo CHtml::activeLabelEx($comment, 'verifyCode');?>
			<?php $this->widget('CCaptcha', array(
				'clickableImage'=>true,
				'showRefreshButton'=>false,
			)) ?>
			<br/>
			<label>&nbsp;</label>
			<?php echo CHtml::activeTextField($comment, 'verifyCode'); ?>
			<?php echo $form->error($comment,'verifyCode'); ?>
		</div>
		<?php } else {  ?>
               
                <?php  } ?>

		<div class="form-group">
	
		<div class="col-lg-12">
		<button type="submit"  class="btn btn-blink-default blink-action">Публиковать &raquo;</button>
		</div>
		</div>

	<?php $this->endWidget(); ?><!-- /form -->
	</div>
</div>

<?php

// Display comments
if(!empty($comments))
{
	foreach($comments as $row)
	{
            
	?>
		<div class="comment mainly" style="margin-top:10px" id="comment_<?php echo $row->id; ?>">
			<?php 
                        if(!empty($row->user_id)){
                            echo '<div class="av_im">'.CHtml::link(CHtml::image($row->user->getAvatar(60),$row->user->username,array('width' => 30),array('style'=>'margin-right:5px;vertical-align:top')),Yii::app()->createUrl('/account/'.$row->user->username)).'</div>'; 
                          
                        } 
                        ?><div class="comment_text_comment">(<?php echo $row->created; ?>)
                        <p class="comment_body">
                     <span class="c57 f11" style="font-style: normal;">
                     <?php echo CHtml::link($row->user->username, Yii::app()->createUrl('account/'.$row->user->username),array('class'=>'redis')); ?></span>
                     <?php echo nl2br(CHtml::encode($row->text)); ?></p>
                        </div>
			<div style="clear:both"></div>
                       
		</div>
	<?php
	}
}
?>