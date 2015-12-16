<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
$this->renderPartial('application.views.common._flashMessage');

$addClass = '';
if($model->isNewRecord){ 
    
?>

    
<?php } else {
?>
    
    <?php
    $addClass = 'tab-content-padding';
    } ?>

<div class="<?php if($model->isNewRecord) { echo 'panel-body'; } ?>">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'city-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>"panel form-horizontal no-border no-margin-b $addClass", 'enctype' => 'multipart/form-data')
)); ?>

	<div class="form-group">
            <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-lg-2 col-md-3 col-sm-4 control-label')); ?>

            <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textField($model,'title',array('class'=>'form-control', 'placeholder'=>'Название')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
                <?php echo $form->label($model, 'region',  array('class'=>'col-lg-2 col-md-2 col-sm-4 control-label')); ?>
                <div class="col-lg-10 col-md-9 col-sm-8">
                <?php
                echo $form->dropDownList($model, 'region', City::getRegions(), array(
                        'encode'=>false,
                        'empty'=>'Выберите регион',
                        'class'=>'form-control'
                ));

                ?>
                </div>
        </div>
        <div class="form-group">

            <?php  echo $form->labelEx($model, 'rodpad', array('class'=>'col-lg-2 col-md-3 col-sm-4 control-label')); ?>

            <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textField($model,'rodpad',array('class'=>'form-control', 'placeholder'=>'Москвы')); ?>
            <span class="help-block">Справочник Москвы</span>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->labelEx($model, 'mestpad', array('class'=>'col-lg-2 col-md-3 col-sm-4 control-label')); ?>

            <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textField($model,'mestpad',array('class'=>'form-control', 'placeholder'=>'Москве')); ?>
            <span class="help-block">В Москве</span>
            </div>
        </div> <!-- / .form-group -->
    <div class="form-group">

            <?php  echo $form->labelEx($model, 'url', array('class'=>'col-lg-2 col-md-3 col-sm-4 control-label')); ?>

            <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textField($model,'url',array('class'=>'form-control', 'placeholder'=>'На латинице')); ?>
            </div>
        </div> <!-- / .form-group -->

    <div class="form-group">

            <?php  echo $form->labelEx($model, 'pos', array('class'=>'col-lg-2 col-md-3 col-sm-4 control-label')); ?>

            <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textField($model,'pos',array('class'=>'form-control','maxlength'=>50, 'placeholder'=>'1000')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->labelEx($model, 'utcdiff', array('class'=>'col-lg-2 col-md-3 col-sm-4 control-label')); ?>

            <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textField($model,'utcdiff',array('class'=>'form-control','maxlength'=>50, 'placeholder'=>'3')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group widget-comments">
        <?php echo $form->label($model, 'tmpFiles', array('class' => 'col-lg-2 col-md-3 col-sm-4 control-label')); ?>
        <div class="col-lg-10 col-md-9 col-sm-8"  style="padding-top:7px">
                    <div id="previewDz"></div>
                    <div>Рекомендуемый размер картинки 1920x562</div>
                    <div id="dropzone" class="dropzone-box" style="min-height: 200px; margin-top: 10px;">
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
        <div class="form-group widget-comments">
        <?php echo $form->label($model, 'metrika', array('class' => 'col-lg-2 col-md-3 col-sm-4 control-label')); ?>
        <div class="col-lg-10 col-md-9 col-sm-8">
            <?php echo $form->textArea($model,'metrika',array('class'=>'form-control')); ?>
        </div>
        </div>


<div style="margin-bottom: 0;" class="form-group">
            <div class="col-lg-2 col-lg-offset-2 col-md-offset-3 col-md-9 col-sm-offset-4 col-sm-8">
                <button class="btn btn-primary" type="submit"><?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'; ?></button>
            </div>
        </div> <!-- / .form-group -->

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$uploadLink = Yii::app()->createUrl('city/admin/default/upload');
$unlinkLink = Yii::app()->createUrl('city/admin/default/unlink');
$scriptDd = "
$(function(){
   
$('#City_region').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите регион'
}); 

var dropzone = new Dropzone('#dropzone', {
        url: '".$uploadLink."',
        paramName: 'tmpFiles', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        parallelUploads: 1,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#previewDz',
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
        removedfile: function(file) {
            
            var name = file.name;        
            $.ajax({
                type: 'POST',
                url: '".$unlinkLink."',
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
              //  console.log(id);
                id.find('.progress').remove();
                var response = $.parseJSON(serverResponse);
                if (response && response.success == true && response.fileName){
                    $('#city-form').append('<input type=\"hidden\" name=\"City[tmpFiles][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
                   // saveTheNote(1000);
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

                 $("#city-form").append("<input type=\'hidden\' name=\'City[tmpFiles][]\' value=\'" + sessFile'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

        ';
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



";
if(!$model->isNewRecord && !empty($model->filename)){ 

$file = Yii::app()->createAbsoluteUrl('city/city/file', array('id'=>$model->id));
$unlinkLink = Yii::app()->createUrl('city/admin/default/deletefile',array('id'=>$model->id));
$scriptDd .="


var dz_prv =  '<div class=\"dz-preview dz-file-preview\"><div class=\"dz-details\">' +
        '<div class=\"dz-thumbnail-wrapper\">' +
        '<div class=\"dz-thumbnail\">' +
        '<img data-dz-thumbnail>' +
        '<img data-dz-thumbnail=\"\" src=\"".$file."\" alt=\"\" >' + 
        '<span class=\"dz-nopreview\">No preview</span>' +
        '<div class=\"dz-error-mark\"><i class=\"fa fa-times-circle-o\"></i></div>' +
        '<div class=\"dz-error-message\"><span data-dz-errormessage></span></div></div></div></div>' +
        '<a data-dz-remove=\"\"  href=\"".$unlinkLink."\" class=\"dz-remove deletePinboardFile\"></a>' + 
        '</div>';
        $('#previewDz').append(dz_prv);

    $('.deletePinboardFile').bind('click',function(e){
    e.preventDefault();
    var filetag = $(this).parent('.dz-file-preview');
    var urldel = $(this).attr('href');
            $.ajax({
                type: 'get',
                url: urldel,
                success:function(data) {
                    if (data != '[]') {
                        $.growl.error({ message: 'Error'});
                    } else {
                        $(filetag).remove();
                      //  $.growl.notice({ message: 'File removed successfully' });
                         
                    }
                    
                }
            });

    })
";
}

$scriptDd .="
});
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);



?>







    

    

    

    

 




