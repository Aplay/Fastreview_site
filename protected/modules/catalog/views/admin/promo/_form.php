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
		<?php 
if($model->isNewRecord){ 
	
	echo $form->labelEx($model,'promo',array('class'=>'col-sm-2 control-label')); ?>
	<div class="col-sm-10">
	<?php echo $form->textField($model,'promo',array('class'=>'form-control', 'placeholder'=>'Промо код')); ?>
	<?php echo $form->error($model,'promo'); ?>
	</div>
<?php } else {
	echo $form->labelEx($model,'promo',array('class'=>'col-sm-2 control-label')); ?>
	<div class="col-sm-10">
	<?php echo $form->textField($model,'promo',array('class'=>'form-control', 'placeholder'=>'Промо код', 'disabled'=>'disabled')); ?>
	<?php echo $form->error($model,'promo'); ?>
	</div>
<?php } ?>
		
	</div>
	<div class="form-group">
		
		<?php echo $form->labelEx($model,'description',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textArea($model,'description',array('class'=>'form-control', 'placeholder'=>'Описание')); ?>
		<?php echo $form->error($model,'description'); ?>
		</div>
	</div>
	<div class="form-group">
                <?php echo $form->labelEx($model, 'status',  array('class'=>'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                <?php
                echo $form->dropDownList($model, 'status', Promo::getStatusNames(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                        'options' => array(Promo::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>


	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->