<?php
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/summernote-0.7.3/summernote.css');
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/summernote-0.7.3/summernote.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/view/article_form.js', CClientScript::POS_END);

$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;

$cs = Yii::app()->clientScript;



$this->renderPartial('application.views.common._flashMessage');

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'company-form',
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
		<span class="panel-title">Данные</span>
	</div>
	<div class="panel-body">
	<div class="form-group">
            <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Название')); ?>
            </div>
        </div> <!-- / .form-group -->

        <div class="form-group">

            <?php  echo $form->label($model, 'url', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'url',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'На латинице')); ?>
            </div>
        </div> <!-- / .form-group -->
        
        
        <div class="form-group">
        <?php  echo $form->labelEx($model, 'description', array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-10 col-md-12 col-sm-12">
            <?php echo $form->textarea($model, 'description', array('class' => 'form-control auto-size', 'placeholder'=>'Введите описание объявления')); ?>
            </div>
           
        </div>

        <!-- logotip -->
 		<div class="form-group widget-comments">
        <?php echo $form->label($model, 'logotip', array('class' => 'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
        <div class="col-lg-10 col-md-12 col-sm-12"  style="padding-top:7px">
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
    

        <div class="form-group">
                <?php echo $form->label($model, 'status_org',  array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-10 col-md-12 col-sm-12">
                <?php
                echo $form->dropDownList($model, 'status_org', Article::getStatusNames(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                        'options' => array(Article::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>
        <div class="form-group">
                <?php echo $form->label($model, 'verified',  array('class'=>'col-lg-2 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-10 col-md-12 col-sm-12">
                <?php
                $verified = $model->verified?1:0;
                echo CHtml::dropDownList('Article[verified]', $verified, array(0=>'Не проверено',1=>'Проверено'), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                      //  'multiple'=>'multiple',      
                ));
                ?>
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

$uploadLinkLogo = Yii::app()->createUrl('catalog/admin/article/uploadlogo');
$unlinkLinkLogo = Yii::app()->createUrl('catalog/admin/article/unlinklogo');
$deleteLogo = Yii::app()->createUrl('catalog/admin/article/deletelogofile');
$scriptDd = "
$(function(){
$('.auto-size').autosize();
$('#Article_city_id').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите город'
}); 
$('#Article_categories_ar').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите теги'
});


// select2 bug on ipad opens keyboard  
// Hide focusser and search when not needed so virtual keyboard is not shown
$('.select2-container').each(function () {
    $(this).find('.select2-focusser').hide();
    $(this).find('.select2-drop').not('.select2-with-searchbox').find('.select2-search').hide();
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
                $.getJSON('".Yii::app()->createAbsoluteUrl('file/file/logotipFile', array('id'=>$model->id,'model'=>'Article','filename'=>'logotip','realname'=>'logotip_realname'))."', function(data) { // get the json response
                    $.each(data, function(key,value){ //loop through it
                        var mockFile = { id: value.id, name: value.name, size: value.size }; // here we get the file name and size as response 
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/file/file/logotip/'+value.id+'?model=Article&filename=logotip&realname=logotip_realname');
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
                    $('#company-form').append('<input type=\"hidden\" name=\"Article[tmpLogotip][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
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

                     $("#company-form").append("<input type=\'hidden\' name=\'Article[tmpLogotip][]\' value=\'" + sessFileLogo'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

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


";
if(!$model->isNewRecord){ 


$scriptDd .="

$('.deleteLogoFile').bind('click',function(e){
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
$js = <<< EOF_JS

$('#addfirm').click(function(){
    $('#customFields').append('<div class=\"panel\" style=\"margin:0 15px 15px 15px;\">'+
        '<div class=\"panel-heading accordion\">'+
        '<span class=\"panel-title\">Фирма <span class=\"panel-title-num\"></span></span>'+
        '<div class=\"panel-heading-controls\" style=\"width:auto;\">'+
			'<button type=\"button\" class=\"btn btn-xs btn-danger btn-outline remfirm\"><span class=\"fa fa-times\"></span></button>'+
		'</div>'+
        '</div>'+
        '<div class=\"panel-body\">'+
        '<div class=\"form-group\">'+
            '<label  class=\"col-lg-2 col-md-12 col-sm-12 control-label\">Фирма</label>'+
            '<div class=\"col-lg-10 col-md-12 col-sm-12\">'+
            	'<input type=\"text\" value=\"\" name=\"Article[article_ar][firmname][]\" placeholder=\"Выводимое название в статье\" maxlength=\"255\" class=\"form-control\">'+
            '</div>'+
        '</div>'+
        '<div class=\"form-group\">'+
            '<label  class=\"col-lg-2 col-md-12 col-sm-12 control-label\">Фирма, url <span class=\"text-danger\">*</span></label>'+
            '<div class=\"col-lg-10 col-md-12 col-sm-12\">'+
                '<input type=\"text\" value=\"\"  name=\"Article[article_ar][firmurl][]\" placeholder=\"http://moscow.zazadun.ru/1234 или 1234\" maxlength=\"255\" class=\"form-control\">'+
            '</div>'+
        '</div>'+
        '<div class=\"form-group\">'+
        '<label  class=\"col-lg-2 col-md-12 col-sm-12 control-label\">Фото фирмы</label>'+
        '<div class="col-lg-10 col-md-12 col-sm-12"  style=\"padding-top:7px\">'+
                    '<div id=\"previewDz_logo_\">'+
                    '</div>'+
                    '<button class=\"btn btn-primary btn-outline\" type=\"button\" id=\"dropzone_opener_logo_\">Загрузить файл с диска</button>'+
                    '<div id=\"dropzone_logo_\" class=\"dropzone-box\" style=\"display:none;min-height: 200px; margin-top: 10px;\">'+
                        '<div class=\"dz-default dz-message\">'+
                            '<i class=\"fa fa-cloud-upload\"></i>'+
                            'Переместите файл сюда<br><span class=\"dz-text-small\">или нажмите, чтобы выбрать вручную</span>'+
                        '</div>'+
                            '<div class=\"fallback\">'+
                                '<input name=\"Article[article_ar][firmphoto][]\" type=\"file\" multiple=\"\" />'+
                            '</div>'+
                    '</div>'+
                '</div>'+
        '</div>'+
        '<div class=\"form-group\">'+
            '<label  class=\"col-lg-2 col-md-12 col-sm-12 control-label required\">Описание <span class=\"text-danger\">*</span></label>'+
            '<div class=\"col-lg-10 col-md-12 col-sm-12\">'+
                '<textarea rows=\"9\" name=\"Article[article_ar][firmdescription][]\" class=\"form-control auto-size\"></textarea>'+
            '</div>'+
        '</div>'+
        '</div>'+
        '</div>');

		recount();
});

EOF_JS;
//$scriptDd .= $js . "

$js2 = <<< EOF_JS
$('#addfirm').click(function(){
	      var datav = {};
          var that = $(this);
          hpv = $('#csfr').attr('value'),
          hpt = $('#csfr').attr('name');
          datav[hpt] = hpv;
          $.ajax({
                type:'POST',
                data: datav,
                url:'/catalog/admin/article/getnewpart',
                success:function(data) {
                    $('#customFields').append(data);
                    recount();
                    $('.auto-size').autosize();
                },
                error: function (xhr, status) {  
                    alert('Unknown error ' + status); 
                } 
            });
});
EOF_JS;
$scriptDd .= $js2 . "

$('body').on('click', '.remfirm', function(){
    if(window.confirm('Действительно удалить?')){
    $(this).parent().parent().parent().remove();
    recount();
    }
});

$('#content-wrapper').on('click', '.panel-heading.accordion', function(e){
	if(e.target.nodeName == 'DIV')
	$(this).parent('.panel').children('.panel-body').toggle();
});

})

function recount(){
	var len = $('.panel-firm').length;
	$('.panel-firm .panel-title-num').each(function(i,v){
		$(this).text(i+1);
	});
}
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>
