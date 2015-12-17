<?php
$this->renderPartial('application.views.common._flashMessage');

 $form=$this->beginWidget('CActiveForm', array(
  'id'=>'poll-form',
  'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>"no-border no-margin-b")
)); ?>
<div class="row">
<div class="col-md-12">
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">Голосование</span>
	</div>
	<div class="panel-body">

        <div class="form-group">
            <?php  echo $form->labelEx($model, 'label', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'label',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->labelEx($model, 'org_id', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'org_id',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->labelEx($model, 'type', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->dropDownList($model,'type',$model->typeLabels(),array('class'=>'form-control')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->labelEx($model, 'status', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->dropDownList($model,'status',$model->statusLabels(),array('class'=>'form-control')); ?>
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



<?php
$callback = Yii::app()->createUrl('/poll/pollchoice/ajaxcreate');
$js = <<<JS
$('#PollChoice_type').select2({
        minimumResultsForSearch: -1,
        allowClear: true,
}); 
$('#PollChoice_status').select2({
        minimumResultsForSearch: -1,
        allowClear: true,
}); 
var PollChoice = function(o) {
  this.target = o;
  this.labelch  = jQuery(".labelch input", o);
  this.weight = jQuery(".weight select", o);
  this.errorMessage = jQuery(".errorMessage", o);

  var pc = this;

  pc.labelch.blur(function() {
    pc.validate();
  });
}
PollChoice.prototype.validate = function() {
  var valid = true;

  if (this.labelch.val() == "") {
    valid = false;
    this.errorMessage.fadeIn();
  }
  else {
    this.errorMessage.fadeOut();
  }

  return valid;
}

var newChoiceCount = 0;
var addPollChoice = new PollChoice(jQuery("#add-pollchoice-row"));

jQuery("tr", "#poll-choices tbody").each(function() {
  new PollChoice(jQuery(this));
});

jQuery("#add-pollchoice").click(function() {
  if (addPollChoice.validate()) {
  	var datav = {'id':"new"+ newChoiceCount,label:addPollChoice.labelch.val()},
          hpv = $('#csfr').attr('name'),
          hpt = $('#csfr').attr('value');
          datav[hpv] = hpt;
    jQuery.ajax({
      url: "{$callback}",
      type: "POST",
      dataType: "json",
      data: datav,
      success: function(data) {
        addPollChoice.target.before(data.html);
        addPollChoice.labelch.val('');
        new PollChoice(jQuery('#'+ data.id));
      }
    });

    newChoiceCount += 1;
  }

  return false;
});
JS;


Yii::app()->clientScript->registerScript('pollHelp', $js, CClientScript::POS_END);
?>
