<div style="margin-bottom:15px;">
<?php echo CHtml::link('Поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none;">

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>


	<div>
		<?php echo $form->label($model,'title',array('style'=>'width: 70px')); ?>
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
        <br>
        <?php echo $form->label($model,'rubric_title',array('style'=>'width: 70px')); ?>
        <?php echo $form->textField($model,'rubric_title',array('size'=>60,'maxlength'=>255)); ?>
        <br>
		<?php echo CHtml::submitButton('Искать'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
</div><!-- search-form -->
</div>