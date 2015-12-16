<?php
/* @var $this ZalController */
/* @var $model Zal */
/* @var $form CActiveForm */

?>

 <div class="panel-body">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'banners-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>"panel form-horizontal no-border no-margin-b")
)); 

?>


		
	
	<div class="form-group">
	   <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
		<?php echo $form->labelEx($model,'title',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'title',array('class'=>'form-control', 'placeholder'=>'Название')); ?>
		<?php echo $form->error($model,'title'); ?>
		</div>
	</div>
	<div class="form-group">
		
		<?php echo $form->label($model,'text',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textArea($model,'text',array('class'=>'form-control', 'placeholder'=>'Текст')); ?>
		<?php echo $form->error($model,'text'); ?>
		</div>
	</div>


	<div class="form-group">
                <?php echo $form->labelEx($model, 'type',  array('class'=>'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                <?php
                echo $form->dropDownList($model, 'type', Status::getStatusNames(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                        'options' => array(Status::TYPE_ADD => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>


	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->