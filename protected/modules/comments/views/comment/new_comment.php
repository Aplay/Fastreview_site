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
<?php if(!Yii::app()->user->isGuest): ?>
<div class="leave_comment" id="leave_comment">

	<div class="form wide ">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'comment-create-form',
		'action'=>'#comment-create-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
	)); ?>

	<?php if(Yii::app()->user->isGuest): ?>
		<?php /* ?><div class="row">
			<?php echo $form->labelEx($comment,'name'); ?>
			<?php echo $form->textField($comment,'name'); ?>
			<?php echo $form->error($comment,'name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($comment,'email'); ?>
			<?php echo $form->textField($comment,'email'); ?>
			<?php echo $form->error($comment,'email'); ?>
		</div><?php */ ?>
	<?php endif; ?>

		<div class="row" style="padding-bottom:0px;">
                    <?php echo $form->error($comment,'text'); ?>
			<?php echo $form->textArea($comment,'text', array('style'=>'width: 420px; padding: 7px; font-size: 12px; border: 1px solid #C9C7AF; outline: none;  resize: none;',
                'id'=>'txtarea','rows'=>'3')); ?>
			
		</div>

		<?php if(Yii::app()->user->isGuest): ?>
		<?php /* ?><div class="row">
			<?php echo CHtml::activeLabelEx($comment, 'verifyCode')?>
			<?php $this->widget('CCaptcha', array(
				'clickableImage'=>true,
				'showRefreshButton'=>false,
			)) ?>
			<br/>
			<label>&nbsp;</label>
			<?php echo CHtml::activeTextField($comment, 'verifyCode')?>
			<?php echo $form->error($comment,'verifyCode'); ?>
		</div><?php */ ?>
		<?php endif; ?>

		<div class="row submit">
		<button type="submit"  class="btn btn-blink-default blink-action">Публиковать &raquo;</button>

		</div>

	<?php $this->endWidget(); ?><!-- /form -->
	</div>
</div>
<?php endif; ?>
<?php

// Display comments
if(!empty($comments))
{
	foreach($comments as $row)
	{
            
	?>
		<div class="comment mainly" id="comment_<?php echo $row->id; ?>">
			<span class="username"><?php 
                        if(!empty($row->user_id)){
                            echo CHtml::link(CHtml::image($row->user->getAvatar(60),$row->user->username,array('width' => 30),array('style'=>'margin-right:5px;vertical-align:top')),Yii::app()->createUrl('/account/'.$row->user->username));
                            echo CHtml::link(CHtml::encode($row->user->username),Yii::app()->createUrl('/account/'.$row->user->username));
                        } else {
                           
                            echo MHelper::String()->wordwrap(nl2br(CHtml::encode($row->name)),39,"\n",true);
                        }
                        ?></span> <span class="created">(<?php echo $row->created; ?>)</span>
			<?php //echo CHtml::link('#', Yii::app()->request->getUrl().'#comment_'.$row->id) ?>
			<div class="message">
				<?php 
                                echo MHelper::String()->wordwrap(nl2br(CHtml::encode($row->text)),39,"\n",true);
                              ?>
			</div>
                       
		</div>
	<?php
	}
}
?>