<?php
$themeUrl = '/themes/bootstrap_311/';
//Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css');
//Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-timepicker/js/bootstrap-timepicker_mod.min.js', CClientScript::POS_END);

$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;

$cs = Yii::app()->clientScript;



// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/summernote_new/summernote.min.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/view/article_form.js', CClientScript::POS_END);


$this->renderPartial('application.views.common._flashMessage');

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'objects-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>"no-border no-margin-b", 'enctype' => 'multipart/form-data')
)); ?>
<div class="row">
<div class="col-md-12">
<div class="panel">
    <div class="panel-heading">
        <span class="panel-title">Объявление</span>
    </div>
    <div class="panel-body">

        <div class="form-group">

            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-10 col-md-12 col-sm-12">

            <div>
                
                <?php echo $form->textField($model, 'title', 
                array(
                    'class'=>'form-control',
                   // 'id'=>'Issue_description',
                   // 'rows'=>10
                )); 
                ?>
            </div>


            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
                <?php echo $form->label($model, 'categorie',  array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-10 col-md-12 col-sm-12">
                <?php
                echo CHtml::dropDownList('Objects[categories_ar][]', $categories_ar, Category::TreeArrayActive(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                       // 'multiple'=>'multiple',      
                ));
                ?>
                </div>
        </div>
        <div class="form-group">

            <?php  echo $form->label($model, 'description', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textArea($model,'description',array('class'=>'form-control')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'link', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'link',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'address', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'address',array('class'=>'form-control','maxlength'=>255)); ?>
            </div>
        </div> <!-- / .form-group -->
        

        <div class="hide form-group">
                <?php echo $form->labelEx($model, 'city_id',  array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-10 col-md-12 col-sm-12">
                <?php
                echo $form->dropDownList($model, 'city_id', City::getBigCities(), array(
                        'encode'=>false,
                        'empty'=>'Выбрать',
                        'class'=>'form-control'
                ));

                ?>
                </div>
        </div>

        
        
        <div class="form-group">
                <?php echo $form->label($model, 'status',  array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-10 col-md-12 col-sm-12">
                <?php
                echo $form->dropDownList($model, 'status', Objects::getStatusNames(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                       // 'options' => array(Advert::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>
        <div class="form-group">
                <?php echo $form->label($model, 'verified',  array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-10 col-md-12 col-sm-12">
                <?php
                $verified = $model->verified?1:0;
                echo CHtml::dropDownList('Objects[verified]', $verified, array(0=>'Не проверено',1=>'Проверено'), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                      //  'multiple'=>'multiple',      
                ));
                ?>
                </div>
        </div>
          <?php
        if($video){
                    foreach($video as $key=>$ht){
                        if($key == 0){
                            $buttn = '<button type="button" class="btn btn-success addVideo"><span class="btn-label icon fa fa-plus-square"></span></button>';
                        } else {
                            $buttn = '<button type="button" class="btn btn-danger remVideo"><span class="btn-label icon fa fa-minus-square"></span></button>';
                        }
                        ?>
                   <div class="form-group">
                    <?php  echo $form->label($model, 'video', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                    <div class="col-lg-10 col-md-12 col-sm-12">
                    <div class="input-group">
                        <input type="text" value="<?php echo $ht->site; ?>"  name="Objects[video][]" placeholder="Видео" maxlength="255" class="form-control">
                        <span class="input-group-btn">
                            <?php echo $buttn; ?>
                        </span>
                    </div>
                    </div>
                    </div> <!-- / .form-group -->
                

                    <?php
                    if($key == 0){ ?>
                    <div id="customFieldsVideo">
                    <?php }

                    } ?>
                    </div>
                    <?php

        } else {

        ?>
        <div class="form-group">
            <?php  echo $form->label($model, 'video', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <div class="input-group">
                <input type="text" value=""  name="Objects[video][]" placeholder="Видео" maxlength="255" class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success addVideo"><span class="btn-label icon fa fa-plus-square"></span></button>
                </span>
            </div>
            </div>
        </div> <!-- / .form-group -->
         <div id="customFieldsVideo"></div>
        <?php } ?>
        <!-- logotip -->
        <div class="form-group widget-comments">
        <?php echo $form->label($model, 'tmpFiles', array('class' => 'col-lg-2 col-md-2 col-sm-3 control-label')); ?>
        <div class="col-lg-10 col-md-10 col-sm-9"  style="padding-top:7px">
                    <div id="previewDz">
        
                    </div>
                    <div class="clearfix"></div>
                    <button class="btn btn-primary btn-outline" type="button" id="dropzone_opener"><?php echo Yii::t('site', 'Select file from disk'); ?></button>

                    <div id="dropzone" class="dropzone-box" style="display:none;min-height: 200px; margin-top: 10px;">
                        <div class="dz-default dz-message">
                            <i class="fa fa-cloud-upload"></i>
                            Переместите файл сюда<br><span class="dz-text-small">или нажмите, чтобы выбрать вручную</span>
                        </div>
                            <div class="fallback">
                                <input name="tmpFiles" type="file" multiple="" />
                            </div>
                    </div>
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


<?php 
$this->endWidget(); 


$uploadLink = Yii::app()->createUrl('file/file/upload');
$unlinkLink = Yii::app()->createUrl('file/file/unlink');
$deleteLink = Yii::app()->createUrl('file/file/deleteobjectsfile');

$scriptDd = "
$(function(){

$('#Objects_city_id').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите город'
}); 
$('#Objects_categories_ar').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите рубрику'
});
$('#Objects_status').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
});
$('#Objects_verified').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
});
// select2 bug on ipad opens keyboard  
// Hide focusser and search when not needed so virtual keyboard is not shown
$('.select2-container').each(function () {
    $(this).find('.select2-focusser').hide();
    $(this).find('.select2-drop').not('.select2-with-searchbox').find('.select2-search').hide();
});


var dropzone = new Dropzone('#dropzone', {
        url: '".$uploadLink."',
        paramName: 'tmpFiles', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        parallelUploads: 10,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#previewDz',
        addRemoveLinks: true,
        dictRemoveFile:'',
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
";
if(!$model->isNewRecord){ 
$scriptDd .= "
        init: function() {
              var thisDropzone = this;
                $.getJSON('".Yii::app()->createAbsoluteUrl('file/file/objectsFiles', array('id'=>$model->id))."', function(data) { // get the json response
                    $.each(data, function(key,value){ //loop through it
                        var mockFile = { id: value.id, name: value.name, size: value.size }; // here we get the file name and size as response 
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/file/file/object/'+value.id);
                    });
                });
        },
";
} 
$scriptDd .= "
        removedfile: function(file) {
            
            var name = file.name, removedlink;  
            if(file.id){
                removedlink = '".$deleteLink."/?id='+ file.id;
            }  else {
                removedlink = '".$unlinkLink."';
            }         
            $.ajax({
                type: 'POST',
                url: removedlink,
                data: {
                    'name':name,
                    '".$csrfTokenName."': '".$csrfToken."'

                },
                dataType: 'html'
            });
        $('input[value=\"'+ name +'\"]').remove();
        var _ref;
        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
        },
        dictResponseError: 'Can\'t upload file!',
        autoProcessQueue: true,
        thumbnailWidth: 140,
        thumbnailHeight: 140,

        previewTemplate: '<div data-src=\"\" class=\"dz-preview dz-file-preview col-sm-3 col-xs-6 lightbo\">' +
        '<div class=\"dz-thumbnail lightbox-item\">' +
        '<img data-dz-thumbnail><span class=\"dz-nopreview\">No preview</span>' +
        '<div class=\"dz-error-mark\"><i class=\"md md-highlight-remove\"></i></div>' +
        '<div class=\"dz-error-message\"><span data-dz-errormessage></span></div></div>' +
        '</div>',

        resize: function(file) {
            var info = { srcX: 0, srcY: 0, srcWidth: file.width, srcHeight: file.height },
                srcRatio = file.width / file.height;
            if (file.height > this.options.thumbnailHeight || file.width > this.options.thumbnailWidth) {
                info.trgHeight = this.options.thumbnailHeight;
                info.trgWidth = info.trgHeight * srcRatio;
                if (info.trgWidth > this.options.thumbnailWidth) {
                    info.trgWidth = this.options.thumbnailWidth;
                    info.trgHeight = info.trgWidth / srcRatio;
                }
            } else {
                info.trgHeight = file.height;
                info.trgWidth = file.width;
            }
            return info;
        }
    }).on('addedfile', function(file) {
                
    }).on('success', function(file, serverResponse){

                var id = $(this.element);
              //  console.log(id);
                id.find('.progress').remove();
                var response = $.parseJSON(serverResponse);
                if (response && response.success == true && response.fileName){
                    $('#objects-form').append('<input type=\"hidden\" name=\"Objects[tmpFiles][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
                }
                
    });
";

$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
//$url1 = str_replace("\\","/",$url1_thumb);
if(Yii::app()->session->itemAt($this->uploadsession)){
    $datas = Yii::app()->session->itemAt($this->uploadsession);
    if(is_array($datas)){
        $cnt = 0; 
        foreach($datas as $key => $value){
            if(file_exists(Yii::getPathOfAlias('webroot').$folder.$value)){
            $cnt++;
                $scriptDd .='
                var sessFile'.$cnt.' = {
                    "name": "'.$key.'",
                    "size": "'.filesize(Yii::getPathOfAlias('webroot').$folder.$value).'",
                    "ext": "'.pathinfo(Yii::getPathOfAlias('webroot').$folder.$value, PATHINFO_EXTENSION).'"
                };
                 var linkThumb = "'.$folderLink.$value.'";
                dropzone.options.addedfile.call(dropzone, sessFile'.$cnt.'); 
                dropzone.options.thumbnail.call(dropzone, sessFile'.$cnt.', linkThumb);

                 $("#objects-form").append("<input type=\'hidden\' name=\'Objects[tmpFiles][]\' value=\'" + sessFile'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

                ';
            }
        }
    }
}
$scriptDd .="
$('#dropzone_opener').on('click', function(){
    if($('#dropzone').is(':visible')){
        $('#dropzone').hide();
        $('input.dr-zone-inputs').attr('disabled', true);
    } else {
        $('#dropzone').show();
        $('input.dr-zone-inputs').attr('disabled', false);
    }
});
$('.addVideo').click(function(){
    $('#customFieldsVideo').append('<div class=\"form-group added\">' +
            '<label class=\"col-lg-2 col-md-12 col-sm-12 control-label\">Видео</label>' +
            '<div class=\"col-lg-10 col-md-12 col-sm-12\">' +
            '<div class=\"input-group\">' +
            '<input type=\"text\" value=\"\"  name=\"Objects[video][]\" placeholder=\"Видео\" maxlength=\"255\" class=\"form-control\">' +
                '<span class=\"input-group-btn\">' +
                    '<button type=\"button\" class=\"btn btn-danger remVideo\"><span class=\"btn-label icon fa fa-minus-square\"></span></button>' +
                '</span>' +
            '</div>' +
            '</div>' +
        '</div>');
});
$('#customFieldsVideo').on('click', '.remVideo', function(){
  
    $(this).parent().parent().parent().parent().remove();
});

})

";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>
