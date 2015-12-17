<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$themeUrl = '/themes/bootstrap_311/';

//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/autocomplete/jquery.autoGrowInput.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/autocomplete/jquery.tagedit.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/js/autocomplete/css/jquery.tagedit.css'); 
//Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/js/autocomplete/css/jquery.ui.autocomplete.css'); 


// Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/view/profile_settings.js', CClientScript::POS_END);
?><div class="row">
<div class="col-md-12 queue">
<?php

$this->renderPartial('application.views.common._flashMessage');
?>
</div>
</div>
<?php
               /* $form = $this->beginWidget('CActiveForm', array(
                'id' => 'signup-form_id',
                )); */
                $form = $this->beginWidget('CActiveForm', array(
                'id' => 'user-form',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                ),
                ));
            ?> 
<div class="row page-profile page-settings">
<div class="col-md-6">
	<div class="panel">
			<div class="panel-heading">
				<span class="panel-title"><?php echo Yii::t('site','Information'); ?></span>
			</div>
			<div class="panel-body">
				<div class="row">
				<div class="col-lg-3 col-md-12">
					<div class="profile-block">
						<div class="panel profile-photo">
							<img id="profileAvatar" alt="" src="<?php echo $user->getAvatar(); ?>" class="my-avatar img-responsive">
						</div>
						<div id="profilePreviewAvatar" style="display:none"></div>
						<p style="margin-bottom:5px">
						<a class="btn btn-primary" id="profileAvatarAdd"><?php echo Yii::t('site','Upload photo'); ?></a></p>
						<a class="" href="#" id="profileAvatarDelete"><?php echo Yii::t('site','Delete photo'); ?></a>
					</div>
				</div>
				<div class="col-lg-9 col-md-12">
					
				<div class="form-group no-margin-hr dark">
				<?php echo $form->labelEx($user, 'fullname', array('class'=>'control-label')); ?>
				<div class="input_wrapper">
				<?php echo $form->textField($user, 'fullname', array('class'=>'submitField form-control', 'placeholder'=>Yii::t('site', 'Name and surname'))); ?>
				</div>
				<?php echo $form->error($user,'fullname'); ?>		
					</div>
					<div class="form-group no-margin-hr dark">
					<?php echo $form->label($user, 'about', array('class'=>'control-label')); ?>
					<div class="input_wrapper">
					<?php echo $form->textarea($user, 'about', array('class'=>'submitField form-control', 'rows'=>5)); ?>
					</div>
					<?php echo $form->error($user,'about'); ?>	
					</div>
				</div>
				</div>
			
				<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="form-group no-margin-hr dark">

					<?php echo $form->label($user, 'phone', array('class'=>'control-label')); ?>	
					<div class="input_wrapper">
					<?php echo $form->textField($user, 'phone', array('class'=>'submitField form-control')); ?>
					</div>
					<?php echo $form->error($user,'phone'); ?>	
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="form-group no-margin-hr dark">
						<?php echo $form->label($user, 'soc_facebook', array('class'=>'control-label')); ?>	
						<div class="input_wrapper">
						<?php echo $form->textField($user, 'soc_facebook', array('class'=>'submitField form-control')); ?>
					</div>
					<?php echo $form->error($user,'soc_facebook'); ?>	
				</div>
				</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_skype', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_skype', array('class'=>'submitField form-control')); ?>
							</div>
							<?php echo $form->error($user,'soc_skype'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_linkedin', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_linkedin', array('class'=>'submitField form-control')); ?>
							</div>
							<?php echo $form->error($user,'soc_linkedin'); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_envelope', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_envelope', array('class'=>'submitField form-control')); ?>
							</div>
							<?php echo $form->error($user,'soc_envelope'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_twitter', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_twitter', array('class'=>'submitField form-control')); ?>
							</div>
							<?php echo $form->error($user,'soc_twitter'); ?>
						</div>
					</div>
				</div>
			</div><!-- panel-body -->
		
	</div>
</div>
<div class="col-md-6">
	<div class="panel">
			<div class="panel-heading">
				<span class="panel-title"><?php echo Yii::t('site','Account'); ?></span>
			</div>
			<div class="panel-body">
			
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
						<?php echo $form->labelEx($user, 'username', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'username', array('class'=>'submitField form-control')); ?>
							</div>
							<?php echo $form->error($user,'username'); ?>
					</div>
				</div>
				</div>
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
					
						<?php echo $form->labelEx($user, 'email', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'email', array('class'=>'submitField form-control')); ?>

							</div>
							<?php echo $form->error($user,'email'); ?>
					</div>
				</div>
				</div>
				
			</div>
			<div class="panel-footer">
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
					   <?php echo $form->labelEx($user, 'password', array('class'=>'control-label')); ?>
					   <?php echo $form->textField($user, 'password', array('class'=>'form-control')); ?>
					   <?php echo $form->error($user,'password'); ?>
					</div>
				</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="form-group no-margin tab-content-padding">
				 <input type="submit" value="Создать" class="btn btn-primary">
				</div>
			</div>
	</div>
</div>
</div>
<?php $this->endWidget(); ?>
<?php
$src = $themeUrl.'/img/avatars/male.png';


$scriptDd = "
$(document).ready(function(){



var dropzone = new Dropzone('#profileAvatarAdd', {
	    // Prevents Dropzone from uploading dropped files immediately
		autoProcessQueue: true,
		url: '".Yii::app()->createUrl('/users/admin/default/addavatarnew')."',
		maxFilesize: 5,
		paramName: 'qqfile',
		thumbnailWidth: 160,
        thumbnailHeight: 160,
		params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer : '#profilePreviewAvatar',
}).on('addedfile', function(file) {
                
}).on('success', function(file) {
		var src = $('#profilePreviewAvatar .dz-preview:last .dz-details img').attr('src');
	//	$('.page-settings .profile-photo').css('width','auto');
		$('.my-avatar').attr('src', src);
	//	var url = src.replace(/^data:image\/[^;]/, 'data:application/octet-stream');
    //		location.href = url;
		
});
$('#profileAvatarDelete').on('click', function(e){
	e.preventDefault();
    $.ajax({
				type: 'POST',
				url: '".Yii::app()->createUrl('/users/admin/default/unlinknew')."',
				data: { '".$csrfTokenName."': '".$csrfToken."'},
		}).done(function(data){
				
				$('.my-avatar').attr('src', '".$src."');
				dropzone.removeAllFiles();
		});
})

});
";

Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>