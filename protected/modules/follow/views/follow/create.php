<?php
/**
 * @var $this Controller
 * @var $form CActiveForm
 */

// Load module
$module = Yii::app()->getModule('follow');
// Validate and save follow on post request
$follow = $module->processRequest($model);
// Load model follows
$follows = Follow::getObjectFollow($model);

$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// Display follows
if(!empty($follows))
{
	foreach($follows as $row)
	{
	?>
		<div class="follow" id="follow_<?php echo $row->id; ?>">
			<span class="username"><?php echo CHtml::encode($row->name); ?></span> <span class="created">(<?php echo $row->created; ?>)</span>
			<?php echo CHtml::link('#', Yii::app()->request->getUrl().'#follow_'.$row->id) ?>
			<div class="message">
				<?php echo nl2br(CHtml::encode($row->text)); ?>
			</div>
			<hr>
		</div>
	<?php
	}
}
?>

<div class="leave_follow" id="leave_follow">
	<h3><?php echo FollowModule::t('Follow') ?></h3>
	<div class="form wide ">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'                     =>'follow-create-form',
		'action'                 =>$currentUrl.'#follow-create-form',
		'enableAjaxValidation'   =>false,
		'enableClientValidation' =>true,
	)); ?>

	<?php if(Yii::app()->user->isGuest): ?>
		<div class="row">
			<?php echo $form->labelEx($follow,'name'); ?>
			<?php echo $form->textField($follow,'name'); ?>
			<?php echo $form->error($follow,'name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($follow,'email'); ?>
			<?php echo $form->textField($follow,'email'); ?>
			<?php echo $form->error($follow,'email'); ?>
		</div>
	<?php endif; ?>

		<div class="row">
			<?php echo $form->labelEx($follow,'text'); ?>
			<?php echo $form->textArea($follow,'text', array('rows'=>5)); ?>
			<?php echo $form->error($follow,'text'); ?>
		</div>

		<?php if(Yii::app()->user->isGuest): ?>
		<div class="row">
			<?php echo CHtml::activeLabelEx($follow, 'verifyCode')?>
			<?php $this->widget('CCaptcha', array(
				'clickableImage'=>true,
				'showRefreshButton'=>false,
			)) ?>
			<br/>
			<label>&nbsp;</label>
			<?php echo CHtml::activeTextField($follow, 'verifyCode')?>
			<?php echo $form->error($follow,'verifyCode'); ?>
		</div>
		<?php endif ?>

		<div class="row buttons">
			<?php echo CHtml::submitButton(FollowModule::t('Send')); ?>
		</div>

	<?php $this->endWidget(); ?><!-- /form -->
	</div>
</div>