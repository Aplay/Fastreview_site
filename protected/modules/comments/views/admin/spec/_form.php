<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

$this->renderPartial('application.views.common._flashMessage');

$addClass = '';
if($model->isNewRecord){ 
    
?>

    
<?php } else {
?>
    
    <?php
    $addClass = 'tab-content-padding';
    } ?>

<div class="<?php if($model->isNewRecord) { echo 'panel-body'; } ?>">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>"panel form-horizontal no-border no-margin-b $addClass")
)); ?>

	<div class="form-group">
            <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
            <?php  echo $form->labelEx($model, 'name', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textField($model,'name',array('class'=>'form-control','maxlength'=>50, 'placeholder'=>'Имя и фамилия')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->labelEx($model, 'email', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->emailField($model,'email',array('class'=>'form-control','maxlength'=>50, 'placeholder'=>'Email')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->labelEx($model, 'text', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textArea($model, 'text', 
                array(
                    'class'=>'form-control',
                    'rows'=>5,
                    'placeholder'=>'Отзыв'
                )); 
                ?>

            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
                <?php echo $form->label($model, 'status',  array('class'=>'col-md-1 col-sm-2 control-label')); ?>
                <div class="col-md-11 col-sm-10">
                <?php
                echo $form->dropDownList($model, 'status', CommentSpec::getStatuses(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',    
                ));

                ?>
                </div>
        </div>
  

       

<div style="margin-bottom: 0;" class="form-group">
            <div class="col-md-offset-1 col-md-11 col-sm-offset-2 col-sm-10">
                <button class="btn btn-primary" type="submit"><?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'; ?></button>
            </div>
        </div> <!-- / .form-group -->

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$scriptDd = "
$(function(){
  
$('#Comment_status').select2({
        minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите статус'
});

});
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>







    

    

    

    

 




