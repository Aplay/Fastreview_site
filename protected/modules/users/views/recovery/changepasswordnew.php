<?php 
$this->pageTitle = Yii::app()->name; 
$themeUrl = Yii::app()->theme->baseUrl;

$this->pageTitle = Yii::app()->name;
$themeUrl = Yii::app()->theme->baseUrl;
$email = ((isset($_GET['email'])) ? $_GET['email'] : '');
$activkey = ((isset($_GET['activkey'])) ? $_GET['activkey'] : '');
?>
<div style="min-height: 400px">
<div class="form-group">
<?php $this->renderPartial('application.views.common._flashMessage'); ?>
</div>
<?php if(Yii::app()->user->isGuest) { ?>
<!-- Modal -->
<div id="recovery_modal" class="modal modal-styled fade" tabindex="-1" role="dialog" style="display:none;">

        <div class="modal-dialog">
        <div id="l-register" class="lc-block toggled">
        <div class="modal-header bg-blue" style="padding:13px 20px 13px 70px;position:relative;">
        	<i class="close md md-highlight-remove c-white" style="margin:7px 0;"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></i>
        	<h4 class="modal-title light-head-title c-white text-center">Новый пароль</h4>
       </div>

        <div class="log-w modal-body h-w-g" style="min-height:306px;">
         <?php
                $form3 = $this->beginWidget('CActiveForm', array(
                'id' => 'change_password',
                'action'=>'/recovery',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
	            'validateOnSubmit'=>true, 
	            'validateOnChange' => false,                              
	            'afterValidate' => "js: function(form, data, hasError) {\n"
                                ."    //if no error in validation, send form data with Ajax\n"
								."		if (!hasError) {\n"
								."      location.reload();\n"
								."		}\n"
                                ."    return false;\n"
                                ."}\n"
        			),
                'htmlOptions'=>array()
                ));
            ?>  
            <input type="hidden" name="email" value="<?php echo $email; ?>" />
            <input type="hidden" name="activkey" value="<?php echo $activkey; ?>" />
                
            <div class="form-group fg-line green m-b-20">
	          <?php echo $form3->labelEx($model,'password',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'Новый пароль')); ?>
	          <?php echo $form3->error($model,'password'); ?>
	        </div>
	        <div class="form-group fg-line green p-t-5">
	          <?php echo $form3->labelEx($model,'verifyPassword',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($model,'verifyPassword',array('class'=>'form-control','placeholder'=>'Подтвердите новый пароль')); ?>
	          <?php echo $form3->error($model,'verifyPassword'); ?>
	        </div>

            
            <div class="clearfix"></div>
            
            <div class="text-center">
             	<button type="submit" class="btn btn-default-over" style="margin-top:20px;padding-left:30px;padding-right:30px;">Изменить</button>
           
            </div>
            <?php $this->endWidget(); ?>
        </div>
        
       
</div>
</div>
</div>
<?php } ?>
</div>
<?php
$scriptDd = "
$(function(){

$('#recovery_modal').modal();

})";
Yii::app()->clientScript->registerScript("scriptr", $scriptDd, CClientScript::POS_END);
?>