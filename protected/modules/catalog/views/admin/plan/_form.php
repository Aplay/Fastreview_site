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
		<?php echo $form->labelEx($model,'title',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'title',array('class'=>'form-control', 'placeholder'=>'Текст')); ?>
		<?php echo $form->error($model,'title'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
		<?php echo $form->labelEx($model,'description',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textArea($model,'description',array('class'=>'form-control', 'placeholder'=>'Описание')); ?>
		<?php echo $form->error($model,'description'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
		<?php echo $form->labelEx($model,'text',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textArea($model,'text',array('class'=>'form-control', 'placeholder'=>'Текст')); ?>
		<?php echo $form->error($model,'text'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'period',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-5">
		<?php echo $form->textField($model,'period',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'period'); ?>
		</div>
		<div class="col-sm-5">
		<?php
		echo $form->dropDownList($model, 'period_type', Plan::getPeriods(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                      //  'options' => array(Plan::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
		 ?>
         <?php echo $form->error($model,'period_type'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'amount',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'amount',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'amount'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'position',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'position',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'position'); ?>
		</div>
	</div>
	<div class="form-group">
                <?php echo $form->labelEx($model, 'status',  array('class'=>'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                <?php
                echo $form->dropDownList($model, 'status', Plan::getStatusNames(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                        'options' => array(Plan::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>


	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->