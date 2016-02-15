<?php
$this->breadcrumbs=array(
  'Атрибуты'=>array('index'),
  'Атрибут',
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
		<span class="panel-title">Атрибут</span>
	</div>
	<div class="panel-body">
	<div class="form-group">
    
    <?php  echo $form->labelEx($model, 'type', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
    <div class="col-lg-10 col-md-9 col-sm-8">
    
        <?php echo $form->dropDownList(
            $model,
            'type',
            $model->getTypesList(),
            array(
                'encode'=>false,
                'empty'=>'Выберите тип',
                'class'=>'form-control'
            )); ?>
    </div>
  </div>
  <?php 
  $addClass = 'hidden';
  if($options && !empty($options)){
  	$addClass = '';
  }
  ?>
  <div id="optionsField" class="<?php echo $addClass; ?>">
  <div class="col-lg-2 col-lg-offset-2 col-md-12 col-sm-12 control-label"><strong>Параметры</strong></div>
  <?php
        if($options){
                foreach($options as $key=>$ht){

                    if($key == 0){
                        $buttn = '<button type="button" class="btn btn-success addOptions"><span class="btn-label icon fa fa-plus-square"></span></button>';
                    } else {
                        $buttn = '<button type="button" class="btn btn-danger remOptions"><span class="btn-label icon fa fa-minus-square"></span></button>';
                    }
                    ?>
                   <div class="form-group">
                    <?php  // echo $form->label($model, 'option_title', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); 
                    ?>
                    <div class="col-lg-2 col-lg-offset-2 col-md-12 col-sm-12 control-label"></div>
                    <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="input-group">
                        <input type="text" value="<?php echo $ht; ?>"  name="EavOptions[options_ar][]"  maxlength="255" class="form-control">
                        <span class="input-group-btn">
                            <?php echo $buttn; ?>
                        </span>
                    </div>
                    </div>
                    </div> <!-- / .form-group -->

                    <?php
                    if($key == 0){ ?>
                    <div id="customFieldsOptions">
                    <?php }

                    } ?>
                    </div>
                    <?php

        } else {

        ?>
        <div class="form-group">
            <?php  // echo $form->label($model, 'option_title', array('class'=>'col-lg-2 col-lg-offset-2 col-md-12 col-sm-12 control-label')); 

            ?>
            <div class="col-lg-2 col-lg-offset-2 col-md-12 col-sm-12 control-label"></div>
            <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="input-group">
                <input type="text" value=""  name="EavOptions[options_ar][]"  maxlength="255" class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success addOptions"><span class="btn-label icon fa fa-plus-square"></span></button>
                </span>
            </div>
            </div>
        </div> <!-- / .form-group -->
         <div id="customFieldsOptions"></div>

        <?php } ?>
  </div>
  <div class="form-group">
            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->
   <div class="form-group hide">
    <?php  echo $form->label($model, 'group_id', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
    <div class="col-lg-10 col-md-9 col-sm-8">
        <?php echo $form->dropDownList(
            $model,
            'group_id',
            EavOptionsGroup::model()->getFormattedList(),
            array(
                'encode'=>false,
                'empty'=>'Выберите группу',
                'class'=>'form-control'
            )); ?>
    </div>
    
</div>
        
        <div class="form-group">
            <?php  echo $form->label($model, 'name', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'name',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'unit', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
            <?php echo $form->textField($model,'unit',array('class'=>'form-control','maxlength'=>30)); ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
            	<p class="help-block">(См, Гб, мм и т.п.)</p>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
		<?php  echo $form->label($model, 'required', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
		 <div class="col-lg-10 col-md-12 col-sm-12">
		<?php echo $form->checkBox($model,'required',array('class'=>'')); ?>   
		 </div>

		 </div>
		 
        
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
<?php

$scriptDd = "
$(function(){

$('#EavOptions_type').change(function () {
    if ($.inArray(parseInt($(this).val()), [".join(',', EavOptions::getTypesWithOptions())."]) >= 0) {
        $('#optionsField').removeClass('hidden');
    }
    else {
        $('#optionsField').addClass('hidden');
    }
});
$('#EavOptions_type').trigger('change');

$('.addOptions').click(function(){
    $('#customFieldsOptions').append('<div class=\"form-group added\">' +
            '<label class=\"col-lg-2 col-lg-offset-2 col-md-12 col-sm-12 control-label\"></label>' +
            '<div class=\"col-lg-8 col-md-12 col-sm-12\">' +
            '<div class=\"input-group\">' +
            '<input type=\"text\" value=\"\"  name=\"EavOptions[options_ar][]\"  maxlength=\"255\" class=\"form-control\">' +
                '<span class=\"input-group-btn\">' +
                    '<button type=\"button\" class=\"btn btn-danger remOptions\"><span class=\"btn-label icon fa fa-minus-square\"></span></button>' +
                '</span>' +
            '</div>' +
            '</div>' +
        '</div>'
        );
});

$('#customFieldsOptions').on('click', '.remOptions', function(){
    $(this).parent().parent().parent().parent().next().remove();
    $(this).parent().parent().parent().parent().remove();
});

});
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>