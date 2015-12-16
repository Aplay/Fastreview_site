<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
$this->renderPartial('application.views.common._flashMessage');

$addClass = '';
if($model->isNewRecord){ 
    
?>

    <div class="panel-body padding-sm">
        <span class="panel-title">Создать тег фирмы</span>
    </div>
<?php } else {
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Редактировать тег фирмы</span>
    </div>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.history-button').click(function(){
    $('.history-form').toggle();
    return false;
});

",CClientScript::POS_END);
?>
<div style="margin-bottom:15px;">
<?php echo CHtml::link('История действий','#',array('class'=>'history-button')); ?>
<div class="history-form" style="display:none;">
<?php $this->renderPartial('application.modules.catalog.views.admin.company._history',array(
    'dataProviderHistory'=>$dataProviderHistory,
    'model'=>$modelLog
)); ?>
</div><!-- search-form -->
</div>
    <?php
    $addClass = 'tab-content-padding';
    } ?>
    <div class="<?php if($model->isNewRecord) { echo 'panel-body'; } ?>">
    <?php
        $form = $this->beginWidget('CActiveForm', array(
                'id' => 'project-form',
                'enableAjaxValidation'=>false,
                'htmlOptions'=>array('class'=>"panel form-horizontal no-border no-margin-b $addClass",'enctype' => 'multipart/form-data')
                ));
                ?>
        <div class="form-group">
            <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Название')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'url', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textField($model,'url',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'На латинице')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'keywords', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textArea($model,'keywords',array('class'=>'form-control')); ?>
            <span class="help-block">Через запятую</span>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'description', array('class'=>'col-md-1 col-sm-2 control-label','id'=>'project_description')); ?>
            <div class="col-md-11 col-sm-10">
            <?php echo $form->textArea($model,'description',array('class'=>'form-control')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
                <?php echo $form->labelEx($model, 'parent_id',  array('class'=>'col-md-1 col-sm-2 control-label')); ?>
                <div class="col-md-11 col-sm-10">
                <?php
                echo $form->dropDownList($model, 'parent_id', Category::TreeArrayActive(), array(
                        'encode'=>false,
                        'empty'=>Yii::t('site', 'None'),
                        'class'=>'form-control'
                ));

                ?>
                </div>
        </div>
        <!-- logotip -->
 		<div class="form-group">
        <?php echo $form->label($model, 'logotip', array('class' => 'col-md-1 col-sm-2  control-label')); ?>
        <div class="col-md-11 col-sm-10"  style="padding-top:7px">
                    <div id="previewDz_logo">
                    </div>
                    <button class="btn btn-primary btn-outline" type="button" id="dropzone_opener_logo"><?php echo Yii::t('site', 'Select file from disk'); ?></button>
                    <div id="dropzone_logo" class="dropzone-box" style="display:none;min-height: 200px; margin-top: 10px;">
                        <div class="dz-default dz-message">
                            <i class="fa fa-cloud-upload"></i>
                            Переместите файл сюда<br><span class="dz-text-small">или нажмите, чтобы выбрать вручную</span>
                        </div>
                            <div class="fallback">
                                <input name="logotip" type="file" multiple="" />
                            </div>
                    </div>
                </div>
        </div>
  

        <div style="margin-bottom: 0;" class="form-group">
            <div class="col-md-offset-1 col-md-11 col-sm-offset-2 col-sm-10">
                <button class="btn btn-primary" type="submit"><?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'; ?></button>
            </div>
        </div> <!-- / .form-group -->
     <?php $this->endWidget(); ?>   
    </div>


<?php
$uploadLinkLogo = Yii::app()->createUrl('catalog/admin/default/uploadlogo');
$unlinkLinkLogo = Yii::app()->createUrl('catalog/admin/default/unlinklogo');
$deleteLogo = Yii::app()->createUrl('catalog/admin/default/deletelogofile');
$scriptDd = "
$(function(){

$('#switcherInherit > input').switcher();

$('#Category_parent_id').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите рубрику'
    }).on('change',function(e){
            
            if(e.val){
                $('#inherit-block').show();
                $('#switcherInherit > input').switcher('enable');
            } else {
                $('#inherit-block').hide();
                $('#switcherInherit > input').switcher('disable');
            }
    });
var dropzoneLogo = new Dropzone('#dropzone_logo', {
        url: '".$uploadLinkLogo."',
        paramName: 'logotip', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        parallelUploads: 1,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#previewDz_logo',
        addRemoveLinks: true,
        dictRemoveFile:'',
       /* accept: function(file, done) {
            console.log(file);
            if (file.type != 'image/jpeg' || file.type != 'image/png') {
                done('Error! Files of this type are not accepted');
            }
            else { done(); }
        }, */
        acceptedFiles: 'image/*',
        init: function() {
              var thisDropzone = this;
                $.getJSON('".Yii::app()->createAbsoluteUrl('file/file/logotipFile', array('id'=>$model->id,'model'=>'Category','filename'=>'logotip','realname'=>'logotip_realname'))."', function(data) { // get the json response
                    $.each(data, function(key,value){ //loop through it
                        var mockFile = { id: value.id, name: value.name, size: value.size }; // here we get the file name and size as response 
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/file/file/logotip/'+value.id+'?model=Category&filename=logotip&realname=logotip_realname');
                    });
                });
        },
        removedfile: function(file) {
            
            var name = file.name, removedlink;  
            if(file.id){
                removedlink = '".$deleteLogo."/?id='+ file.id;
            }  else {
                removedlink = '".$unlinkLinkLogo."';
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
        thumbnailWidth: 138,
        thumbnailHeight: 120,

        previewTemplate: '<div class=\"dz-preview dz-file-preview\"><div class=\"dz-details\">' +
        '<div class=\"dz-thumbnail-wrapper\">' +
        '<div class=\"dz-thumbnail\">' +
        '<img data-dz-thumbnail><span class=\"dz-nopreview\">No preview</span>' +
        '<div class=\"dz-error-mark\"><i class=\"fa fa-times-circle-o\"></i></div>' +
        '<div class=\"dz-error-message\"><span data-dz-errormessage></span></div></div></div></div>' +
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

                id.find('.progress').remove();
                var response = $.parseJSON(serverResponse);
                if (response && response.success == true && response.fileName){
                    $('#project-form').append('<input type=\"hidden\" name=\"Category[tmpLogotip][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
                }
                
    }); 
";
$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
//$url1 = str_replace("\\","/",$url1_thumb);
if(Yii::app()->session->itemAt($this->uploadlogosession)){
    $datas = Yii::app()->session->itemAt($this->uploadlogosession);
    if(is_array($datas)){
        $cnt = 0; 
        foreach($datas as $key => $value){
            if(file_exists(Yii::getPathOfAlias('webroot').$folder.$value)){
                $cnt++;
                    $scriptDd .='
                    var sessFileLogo'.$cnt.' = {
                        "name": "'.$key.'",
                        "size": "'.filesize(Yii::getPathOfAlias('webroot').$folder.$value).'",
                        "ext": "'.pathinfo(Yii::getPathOfAlias('webroot').$folder.$value, PATHINFO_EXTENSION).'"
                    };
                     var linkThumbLogo = "'.$folderLink.$value.'";
                    dropzoneLogo.options.addedfile.call(dropzoneLogo, sessFileLogo'.$cnt.'); 
                    dropzoneLogo.options.thumbnail.call(dropzoneLogo, sessFileLogo'.$cnt.', linkThumbLogo);

                     $("#project-form").append("<input type=\'hidden\' name=\'Category[tmpLogotip][]\' value=\'" + sessFileLogo'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

            ';
            }
        }
    }
}
$scriptDd .="
$('#dropzone_opener_logo').on('click', function(){
    if($('#dropzone_logo').is(':visible')){
        $('#dropzone_logo').hide();
        $('#dropzone_logo input.dr-zone-inputs').attr('disabled', true);
    } else {
        $('#dropzone_logo').show();
        $('#dropzone_logo input.dr-zone-inputs').attr('disabled', false);
    }
});
});
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);

?>


    

    

    

    

 




