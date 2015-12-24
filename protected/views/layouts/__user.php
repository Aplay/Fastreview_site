<?php if(!Yii::app()->user->isGuest) { 
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSizeAvatar']/1024/1024;
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/dropzone/dropzone.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/dropzone/dropzone.css');

	?>
<!-- Modal -->
<div id="user_profile_modal" class="modal-styled modal fade" tabindex="-1" role="dialog" style="display:none;">

<div class="modal-dialog" style="max-width:660px;">
<div id="l-user-profile" class="lc-block toggled">

        <div class="modal-header bg-blue" style="padding:13px 20px 13px 40px;position:relative;">
        	<i class="close zmdi zmdi-close-circle c-white" style="margin:7px 0;"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></i>
        	<h4 class="modal-title light-head-title c-white">Обновление данных</h4>
       </div>


<ul style="padding: 0px 26px; overflow: hidden;" tabindex="1" class="tab-nav tn-left" role="tablist">
<li role="presentation" class="active">
   <a data-toggle="tab" role="tab" aria-controls="user_modal_profile" href="#user_modal_profile"  aria-expanded="true">Профиль</a>
</li>
</ul>   
<div class="log-w modal-body">
<div class="tab-content">

<div id="user_modal_profile" class="tab-pane animated fadeIn in active" role="tabpanel"> 
		
        <div style="margin-bottom:20px;">Аватар</div>
        <div class="profile-block pull-left">
			<div class="panel profile-photo">
				<img id="profileAvatar" alt="" src="<?php echo Yii::app()->user->getAvatar(); ?>" class="my-avatar img-responsive">
			</div>
		</div>	
			<div class="pull-left f-11 c-gray p-l-10" style="padding-top:18px; line-height:1.2em;">
					Квадратная фотография<br>
					Минимум 110x110px<br>
					Максимум 2Mb
			</div>
			<div class="clearfix"></div>
			<div id="profilePreviewAvatar" style="display:none"></div>
			
		
		<div class="text-center m-t-20" style="margin-bottom:40px;width:100%;">
			<button class="btn btn-default-over" id="profileAvatarAdd">Изменить фото</button></div>
         <?php
         $modelChPass = new FormProfile;
         $modelChPass->fullname = Yii::app()->user->fullname;
                $form3 = $this->beginWidget('CActiveForm', array(
                'id' => 'form-profile',
                'action'=>'/profile',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
	            'validateOnSubmit'=>true, 
	            'validateOnChange' => false,                              
	            'afterValidate' => "js: function(form, data, hasError) {\n"
	            				."    $('.request_message').html('').hide();\n"
                                ."    //if no error in validation, send form data with Ajax\n"
								."		if (!hasError) {\n"
								."		if('flag' in data && data.flag == true){\n"
								."      $('.request_message').html(data.message).show();\n"
								."      $('#form-profile input[type=password]').val('')\n"
								."		}\n"
								."		}\n"
                                ."    return false;\n"
                                ."}\n"
        			),
                'htmlOptions'=>array()
                ));

            ?> 
            <div class="form-group fg-line green m-b-20">
	          <label for="FormProfile_fullname">Отображаемое имя</label>
	          <?php echo $form3->textField($modelChPass,'fullname',array('class'=>'form-control','placeholder'=>'Имя')); ?>
	          <?php echo $form3->error($modelChPass,'fullname'); ?>
	        </div>   

          <?php
	        if(Yii::app()->user->model->from_soc_network != true){
	        ?>
	        <div style="margin:40px 0;"><strong>Изменить пароль</strong></div>
	        <div class="form-group fg-line green m-b-20">
	          <?php echo $form3->labelEx($modelChPass,'oldPassword',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($modelChPass,'oldPassword',array('class'=>'form-control','placeholder'=>'Старый пароль')); ?>
	          <?php echo $form3->error($modelChPass,'oldPassword'); ?>
	        </div>   
            <div class="form-group fg-line green m-b-20 p-t-5">
	          <?php echo $form3->labelEx($modelChPass,'password',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($modelChPass,'password',array('class'=>'form-control','placeholder'=>'Новый пароль')); ?>
	          <?php echo $form3->error($modelChPass,'password'); ?>
	        </div>
	        <div class="form-group fg-line green p-t-5">
	          <?php echo $form3->labelEx($modelChPass,'verifyPassword',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($modelChPass,'verifyPassword',array('class'=>'form-control','placeholder'=>'Подтвердите новый пароль')); ?>
	          <?php echo $form3->error($modelChPass,'verifyPassword'); ?>
	        </div>
	        <?php  } ?>
            
            <div class="clearfix"></div>
            <div class="m-b-20 request_message c-green" style="display:none;"></div>
            
            <div class="text-center">
             	<button type="submit" class="btn btn-default-over" style="margin-top:20px;">Сохранить изменения</button>
           
            </div>
            <?php $this->endWidget(); ?>
</div><!-- user_modal_profile -->
</div><!-- tab-content -->
</div><!-- log-w modal-body -->
</div><!-- l-user-profile -->
</div><!-- modal-dialog -->
</div><!-- modal -->
<input type="hidden" id="file_w" name="file_w" value="0" />
<input type="hidden" id="file_h" name="file_h" value="0" />
<!--<input type="hidden" id="tmpFile" name="tmpFile" value="" />-->
<?php
$src = '/img/avatar.png';

$scriptDd = "
function getHashValue() {
  return window.location.hash.substr(1);
}
$(document).ready(function(){

var hash = getHashValue();
if(hash=='settings'){
$('#header_user_box').popover('hide');$('#user_profile_modal').modal();
}

var dropzone = new Dropzone('#profileAvatarAdd', {
	    // Prevents Dropzone from uploading dropped files immediately
		autoProcessQueue: true,
		url: '/users/profile/addavatar',
		maxFilesize: ".$sizeLimit.",
		uploadMultiple:false,
		paramName: 'photo',
		thumbnailWidth: 80,
        thumbnailHeight: 80,
        dictRemoveFile:'',
		params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        previewsContainer : '#profilePreviewAvatar',
        
}).on('addedfile', function(file) {
   $('#form-profile input[name=\"FormProfile[photo][]\"]').remove();              
}).on('success', function(file, serverResponse) {
		var src;
		var response = $.parseJSON(serverResponse);
        if (response && response.success == true && response.fileName && response.tmpFile){
        	src = '/uploads/tmp/'+response.tmpFile;

		    $('.my-avatar').attr('src', src);
        	$('#form-profile').append('<input type=\"hidden\" name=\"FormProfile[photo][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
       
        }

		
});

});
";

Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>
<?php } ?>