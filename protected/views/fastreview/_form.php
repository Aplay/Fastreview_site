<?php 
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->clientScript;
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
//Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/select2/css/select2.min.css');
//Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/select2/js/select2.min.js', CClientScript::POS_END); 
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/bootstrap-select/bootstrap-select.min.js', CClientScript::POS_END); 
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/dropzone/dropzone.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/dropzone/dropzone.css');
?>

<div class="row m-t-20">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div style="margin-bottom:40px;">ДОБАВЛЕНИЕ</div>
<?php
if($model->isNewRecord){
    $url = Yii::app()->createAbsoluteUrl('new_object');
} else {
   $url =  Yii::app()->createAbsoluteUrl('update_object',array('id'=>$model->id));
}
 
$js1 = <<< EOF_JS
function(){
  $('.btn-submit').prop('disabled', true);
  $('.in-bl-error').hide();
  return true;
}
EOF_JS;
$js2 = <<< EOF_JS
function(form, data, hasError) {
    if (!data.success) {
        $.each(data.message, function(key, val) {
            $('#'+key+'_em_').text(val);
            $('#'+key+'_em_').show();
        });
    } else { 
        clearStorage();
        window.location = data.message.url;
    }
    $('.btn-submit').prop('disabled', false);
    return false;
}
EOF_JS;
	 $form = $this->beginWidget('ext.yii-localstorage-activeform.LocalStorageActiveForm', array(
    // $form = $this->beginWidget('CActiveForm', array(
        'enableSaveToLocalStorage' => true, // Set false or closure for disable save
        'options' => array(
            'ignore' => array('HiddenPropertyValue'),
            'clearOnSubmit' => false,
        ),
        'htmlOptions'=>array('role'=>'form','enctype' => 'multipart/form-data'),
        'id' => 'objects-form',
        'action'=>$url,
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>false,
        'errorMessageCssClass'=>'in-bl-error',
        'clientOptions'=>array(
            'validateOnSubmit'=>true, 
            'validateOnChange' => false,
            'beforeValidate'=>"js:{$js1}",                               
            'afterValidate' => "js:{$js2}"
        ),
        ));

?>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12">
  <div class="form-group fg-line green">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('class' => 'form-control input-sm', 'placeholder'=>'Введите название', 'maxlength'=>55)); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
  <div class="form-group ">
  <?php echo $form->labelEx($model, 'categorie'); ?>
              
                <?php
                echo CHtml::dropDownList('Objects[categories_ar][]', $categories_ar, Category::TreeArrayActive(), array(
                        'encode'=>false,
                       // 'empty'=>'Выберите категорию',
                        'class'=>'selectpicker',
                       // 'multiple'=>'multiple',      
                ));

                ?>
         <?php echo $form->error($model,'categorie'); ?>  
          
     </div>
     <div class="form-group fg-line green">
    <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textarea($model, 'description', array('class' => 'form-control auto-size', 'placeholder'=>'Введите описание')); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>
	
	

    

    <div class="form-group">

        <div id="previewDz" class="lightbox row">
        <?php 

        ?>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
        <div class="m-b-10">
        <label for="Objects_address ">Добавить фото</label></div>
        <div>
            <button id="dropzone" class="btn bgm-lightblue btn-icon waves-effect waves-circle waves-float" type="button">
            <i class="zmdi zmdi-camera"></i>
            <div class="fallback">
                <input name="tmpFiles" type="file" multiple="" />
            </div>
            </button>
        </div>
        
        </div>
        <div class="form-group m-b-20">
            <div id="dropzone-tmp"  class="lightbox row"></div>
        </div>
        <div class="form-group fg-line green">
        <?php 
        echo $form->label($model, 'link'); ?>
        <?php echo $form->textField($model, 'link', array('class' => 'form-control input-sm', 'placeholder'=>'Введите ваш текст...')); ?>
        <?php echo $form->error($model,'link'); ?>
    </div>
    <div class="form-group fg-line green">
        <?php 
        echo $form->label($model, 'address'); ?> <span class="f-11 c-6 m-l-10">Если у вашего объекта есть адрес, то укажите его (Город, улица, дом)</span>
        <?php echo $form->textField($model, 'address', array('class' => 'form-control input-sm', 'placeholder'=>'Введите ваш текст...')); ?>
        <?php echo $form->error($model,'address'); ?>
    </div>
    <input type="hidden" name="redirectReview" id="redirectReview" value="<?php
    if(isset(Yii::app()->session['redirectReview']) && Yii::app()->session['redirectReview'] == 1){ 
    	echo 1;
    	unset(Yii::app()->session['redirectReview']);

    }
    ?>" />

    <input type="hidden" name="review_id" id="review_id" value="" />
    


        <div class="clearfix"></div>
        
        <?php
        if($model->isNewRecord){
            $btn_text = 'ДОБАВИТЬ';
         } else {
            $btn_text = 'РЕДАКТИРОВАТЬ';
         }

        ?>
</div>

   
        <div class="clearfix"></div>

        <div class="form-group" style="margin-bottom: 70px;">
        <button name="formSubmit"  id="reviewFormSubmit" class="btn btn-default-over m-t-30" type="submit"><?php echo $btn_text; ?></button>
        </div> <!-- / .form-actions -->
      
    
 </div>   



<?php $this->endWidget(); ?>



</div>
</div>



<?php


$uploadLink = Yii::app()->createAbsoluteUrl('file/file/upload');
$unlinkLink = Yii::app()->createAbsoluteUrl('file/file/unlink');
$deleteLink = Yii::app()->createAbsoluteUrl('file/file/deleteobjectsfile');

$scriptDd = "

function clearStorage(){
	 $('#objects-form').trigger('reset_state');
	 $(window).unbind('unload.rememberState'); 
}
function makeReviewAfterStorage(){
    
    
    $.when( $('#objects-form').rememberState('restoreState') ).done(function() {
    if( window.localStorage ) {
        var lstorage = JSON.parse(localStorage.getItem('objects-form'));
        
        if (lstorage != 'undefined' || lstorage != 'null') {
            
            lstorage = JSON.parse(localStorage.getItem('objects-form'));
           
        }
      } 

    $('#review_groups .form-group textarea').trigger('autosize.resize');

    });
  

}


$(function(){

$('.selectpicker').selectpicker();

";
if($model->isNewRecord){
    $availableFiles = $model->maxFiles;
    $scriptDd .= "makeReviewAfterStorage();";
} else {
    $existFiles = count($model->images);
    $availableFiles = $model->maxFiles - $existFiles;
}

$scriptDd .= 
"
var dropzone = new Dropzone('#dropzone', {
        url: '".$uploadLink."',
        paramName: 'tmpFiles', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        maxFiles:".$availableFiles.",
        parallelUploads: 10,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#dropzone-tmp',
        addRemoveLinks: true,
        removeLinksClass: 'dz-remove btn bgm-lightblue btn-icon waves-effect waves-circle waves-float',
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

        previewTemplate: '<div data-src=\"\" class=\"dz-preview dz-file-preview  col-xs-6 col-sm-3 lightbo\">' +
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


});
";
Yii::app()->clientScript->registerScript("scriptr", $scriptDd, CClientScript::POS_END);
?>