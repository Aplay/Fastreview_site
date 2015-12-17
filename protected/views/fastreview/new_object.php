<div class="card">
<div class="card-body" style="padding:23px 0px;"> 
<div class="tab-content">

<div id="spec" class="tab-pane animated fadeIn in active" role="tabpanel"> 

<?php 

$js3 = <<< EOF_JS
function(){
  $('#form-addspec-submit').prop('disabled', true);
  return true;
}
EOF_JS;
$js4 = <<< EOF_JS
function(form, data, hasError) {
      
      if(!jQuery.isEmptyObject(data)) {
       
        if('flag' in data && data.flag == false){
        $.each(data.message,function(i,v){
            if(i=='city_id'){
               $('#FormAddSpec_address_em_').html('Не определился город').show();
            }
            if(i=='categories_ar'){
                $('#FormAddSpec_cats_em_').html('Не определился вид услуг, специализация').show();
            }
        })
       }
        if('flag' in data && data.flag == true){
         window.location = data.url
    } else {
      $('#form-addspec-submit').prop('disabled', false);
    }
      }
    
    return false;
}
EOF_JS;
    $url2 = Yii::app()->createAbsoluteUrl('/site/newexpert');
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form-addspec',
        'action'=>$url2,
        'htmlOptions'=>array( 'role'=>'form'),
        'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'beforeValidate'=>"js:{$js3}",
                    'afterValidate' => "js:{$js4}"
                ),
      )); 
      
      ?>
<div style="padding:0 41px">

<?php
if(!Yii::app()->user->isGuest){
 echo $form->hiddenField($model, 'author', array('value'=>Yii::app()->user->id)); 
}
?>
<div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line green">
          <?php echo $form->labelEx($model,'title',array()); ?>
          <?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'Введите ваш текст...')); ?>
          <?php echo $form->error($model,'title'); ?>
    </div>
    </div> 
</div>
<div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line green">

      <label class="required" for="FormAddSpec_address">Адрес (Город, улица, дом, корпус, офис) <span class="required">*</span></label>
      <?php echo $form->textField($model,'address',array('class'=>'form-control', 'placeholder'=>'Город, улица, дом, корпус, офис')); ?>
      <?php echo $form->error($model,'address'); ?>
    </div>
    </div>
    </div>
  <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line green">
      <?php echo $form->labelEx($model,'description'); ?>
      <?php echo $form->textArea($model,'description', array('style'=>'word-wrap: break-word; min-height: 90px; width: 100%; resize: none; overflow:hidden;','class'=>'form-control auto-size','placeholder'=>'Используйте это поле, чтобы прорекламировать себя')); ?>
      <?php echo $form->error($model,'description'); ?>
    </div>
    </div>
    </div>
<div class="clearfix"></div>
<button type="submit" id="form-addspec-submit" class="btn btn-sm btn-success" style="margin-top:55px;margin-bottom:50px;">Добавить</button>

</div>        
      <?php $this->endWidget(); ?>

</div> 
</div>
</div> 
</div>
<?php 
$script = "
$(document).ready(function(){



})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>