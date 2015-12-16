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
		<?php echo $form->labelEx($model,'user_id',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'user_id',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'user_id'); ?>
		</div>
	</div>

	<div class="form-group">
		<label for="Invoice_org_id" class="col-sm-2 control-label required">Id Компании <span class="required">*</span></label>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'org_id',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'org_id'); ?>
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
		<?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
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
		<?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
		<?php echo $form->labelEx($model,'sum',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'sum',array('class'=>'form-control','disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'sum'); ?>
		</div>
	</div>
	<div class="form-group">
                <?php echo $form->label($model, 'promo_id',  array('class'=>'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                <?php
                echo $form->dropDownList($model, 'promo_id', Promo::getPromos(), array(
                        'encode'=>false,
                        'empty'=>'Выбрать',
                        'class'=>'form-control',
                      //  'options' => array(Invoice::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'discount',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'discount',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'discount'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'sum_discount',array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'sum_discount',array('class'=>'form-control','disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'sum_discount'); ?>
		</div>
	</div>

	<div class="form-group">
                <?php echo $form->labelEx($model, 'status',  array('class'=>'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                <?php
                echo $form->dropDownList($model, 'status', Invoice::getStatusNames(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                        'options' => array(Invoice::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>


	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$scriptDd = "
$(function(){
  
$('#Invoice_promo_id').select2({
        // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите промо код'
}); 

});";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>