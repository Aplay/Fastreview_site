<!--
 /**
  * Form for JsTreeBehavior model.
  *
  * Date: 1/29/13
  * Time: 12:00 PM
  *
  * @author: Spiros Kabasakalis <kabasakalis@gmail.com>
  * @link http://iws.kabasakalis.gr/
  * @link http://www.reverbnation.com/spiroskabasakalis
  * @copyright Copyright &copy; Spiros Kabasakalis 2013
  * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
  * @version 1.0.0
  */
  -->

<?php if ($model->isNewRecord) : ?>
<h3><?php echo Yii::t('global', 'Create') ?> <?php echo Yii::t('global', $modelClassName) ?></h3>
<?php elseif (!$model->isNewRecord): ?>
<h4 style="margin-top:0"><?php 
if(!empty($text))
	echo $text.'<br><br>';
echo 'Переместить все фирмы из рубрики '. $model->title; ?> </h4>
<?php endif; ?>

<?php      $val_error_msg = Yii::t('global', "Error.$modelClassName  was not saved.");
                   $val_success_message = ($model->isNewRecord) ?
                   Yii::t('global', "$modelClassName has been created successfully.") :
                    Yii::t('global', "$modelClassName  has been updated successfully.");
?>

<div id="success-note" class="alert alert-success"
     style="display:none;">
    <?php   echo $val_success_message;  ?>
</div>

<div id="error-note" class="alert alert-error"
     style="display:none;">
    <?php   echo $val_error_msg;  ?>
</div>

<div id="ajax-form" class='form'>
    <?php
    $formId = "$modelClassName-form";

    $actionUrl=CController::createUrl($this->id.'/movefirms',array('id'=>$model->id));

    $form = $this->beginWidget('CActiveForm', array(
           'id' => $formId,
           //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
           'action' => $actionUrl,
          // 'enableAjaxValidation' => false,
           'enableClientValidation' => true,

           'errorMessageCssClass' => 'alert alert-error',
           'clientOptions' => array(
               'validateOnSubmit' => true,
               'validateOnType' => false,
               'inputContainer' => '.control-group',
               'errorCssClass' => 'error',
               'successCssClass' => 'success',
               'afterValidate' => 'js:function(form,data,hasError){
              
                $.js_afterValidate(form,data,hasError);  
             
              }',
           ),
      ));
    ?>

    <?php
         echo $form->errorSummary($model,
       '<div style="font-weight:bold">Please correct these errors:</div>',
        NULL,
        array('class' => 'alert alert-error')
);
    ?>
    <fieldset>
       <div class="form-group">
        <div class="col-md-11 col-sm-10">
        <?php
        echo CHtml::dropDownList('moveto', '', Category::TreeArrayActive(), array(
                'encode'=>false,
                'class'=>'form-control',
                'id'=>'moveto'
        ));

        ?>
        </div>
        </div>

        <input type="hidden" name="<?php echo Yii::app()->request->csrfTokenName; ?>"
               value="<?php echo Yii::app()->request->csrfToken; ?>"/>
        <input type="hidden" name= "parent_id" value="<?php echo isset($_POST['parent_id'])?$_POST['parent_id']:''; ?>"  />

        <?php  if (!$model->isNewRecord): ?>
        <input type="hidden" name="update_id"
               value="<?php echo $model->id; ?>"/>
        <?php endif; ?>
        <div class="control-group">

            <?php echo CHtml::submitButton('Переместить',array(
            'class' => 'btn btn-large pull-right',
            'id'=>'move-firm-'.uniqid(),
            'ajax'=>array(
                'type'=>'POST',
                'url'=>$actionUrl,
                'success'=>'function(data) {
                  
                  $("#success-note").fadeOut(1000, "linear", function() {
                          $(this).fadeIn(2000, "linear")
                      }
                  );
            	  $("#ajax-form  > form").slideToggle(500, function() {
                    	$.fancybox.update();
                  });

                }',
            )
        )); ?>

        </div>
</fieldset>
        <?php  $this->endWidget(); ?>
</div>
<!-- form -->

<?php

$scriptDd = "


// $('#switcherInherit > input').switcher();
$(document).ready(function(){

$('#moveto').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите рубрику'
    }).on('change',function(e){
            
    });
})
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);

?>
