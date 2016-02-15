<?php
$this->breadcrumbs=array(
  'Атрибуты'=>array('index'),
  'Группа атрибутов',
);

$this->renderPartial('application.views.common._flashMessage');

 $form=$this->beginWidget('CActiveForm', array(
  'id'=>'attribute-form',
  'enableAjaxValidation'=>false,
  'htmlOptions'=>array('class'=>"no-border no-margin-b")
)); ?>
<div class="row">
<div class="col-md-12">
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">Группа атрибутов</span>
	</div>
	<div class="panel-body">
	
        
        <div class="form-group">
            <?php  echo $form->labelEx($model, 'name', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'name',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->

</div> 
</div>
</div> 
</div> 
<div class="row">
 <div class="col-md-12">
 <div class="panel no-border">

            <div class="pull-left">
                <button class="btn btn-primary" type="submit"><?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'; ?></button>
            </div>
       
   </div>
</div>
</div>



<?php $this->endWidget(); ?>
