<?php
/* @var $this CityController */
/* @var $model City */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php 

$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>


	<div>
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>20,'maxlength'=>255)); ?>
	
		<?php echo $form->label($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>20,'maxlength'=>255)); ?>

		<?php echo CHtml::submitButton('Искать'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->