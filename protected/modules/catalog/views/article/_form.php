<?php 
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->clientScript;
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/plugins/bootstrap-select/bootstrap-select.min.js', CClientScript::POS_END); 

?>
<section  class="zn_section">
<div class="zn_section_size container m-t-20">
<?php
if(!Yii::app()->user->isGuest){

Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/dropzone/dropzone.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/dropzone/dropzone.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/select2/css/select2.min.css');
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/select2/js/select2.min.js', CClientScript::POS_END);


Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/summernote-0.7.3/summernote.css');
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/summernote-0.7.3/summernote_mod.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/summernote-0.7.3/lang/summernote-ru-RU.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/view/article_form.js', CClientScript::POS_END);

if($model->isNewRecord){
    $url = Yii::app()->createAbsoluteUrl('/catalog/article/new',array('obj'=>$object->id));
} else {
   $url =  Yii::app()->createAbsoluteUrl('/catalog/article/update',array( 'id'=>$model->id,'obj'=>$object->id));
}
 
$js1 = <<< EOF_JS
function(){
  
  $('.in-bl-error').hide();
  $('.aj-loader').addClass('active');
  if ($('#Article_description').summernote('isEmpty')) {
      $('#Article_description_em_').text('Введите описание');
      $('#Article_description_em_').show();
      return false;
 }
  $('.btn-submit').prop('disabled', true);
  return true;
}
EOF_JS;
$js2 = <<< EOF_JS
function(form, data, hasError) {
    
    if(data.preview){
         $('#article_preview').show().html(data.message);
         
    } else if (!data.success) {
        $.each(data.message, function(key, val) {
            $('#'+key+'_em_').text(val);
            $('#'+key+'_em_').show();
        });

    } else { 
        clearStorage();
        $('#article_preview, #special_offer_preview').hide();
        $('#article-form')[0].reset();
        $('#Article_description').summernote('code', '');
        swal("Обзор добавлен!", "Ваш обзор появится на странице объекта после проверки.", "success")
       /* setTimeout(function(){
          window.location = data.message.url;
        }, 200); */
        
    }
    $('.btn-submit').prop('disabled', false); 
    $('.aj-loader').removeClass('active');
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
        'htmlOptions'=>array('role'=>'form','enctype' => 'multipart/form-data','class'=>'cf-elm-form'),
        'id' => 'article-form',
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
<div class="row m-t-20">
<div class="col-sm-9 col-sm-offset-1 col-md-8 col-md-offset-2">
<div style="margin-bottom:20px;font-size:25px;font-weight:bold;"><?php 
if($model->isNewRecord){
  echo 'Новый обзор'; 
} else {
  echo 'Редактировать обзор';
}
?></div>

<div class="row zn_columns_container zn_content">
<div class="col-xs-12 col-sm-12 col-md-12 zn_sortable_content zn_content">
 <div class="form-group kl-fancy-form zn_form_field zn_text" style="position:relative;">
  <?php echo $form->labelEx($model,'title',array('class'=>'')); ?>            
  <?php echo $form->textField($model,'title',array('class'=>'form-control inputbox','placeholder'=>'Введите ваш текст...')); ?>
  
  <?php echo $form->error($model,'title'); ?>
</div>
<div class="form-group" style="position:relative;">
            <div><button type="button" id="special_offer_addphoto" class="btn-element btn btn-default-over">Загрузить фотообложку</button>
            <span class="f-12" style="margin-left:10px;">Данная фотография используется как основное изображение к обзору</span>
            </div>
            <?php echo $form->hiddenField($model,'tmpLogotip',array('value'=>'', 'class'=>'dr-zone-inputs')); ?>
            <?php echo $form->error($model,'tmpLogotip'); ?>
            <div id="special_offer_preview" style="display:none;">
            <div class="gridPhotoGallery mfp-gallery mfp-gallery--misc gridPhotoGallery--ratio-square">
            <div class="gridPhotoGallery__item">
               <div class="gridPhotoGalleryItem--h1 gridPhotoGallery__link kl-fontafter-alt" >
               <div id="special_offer_preview_image_cover">
               <img src="" id="special_offer_preview_image" class="gridPhotoGallery__img img-responsive" />
               </div>
                <div id="special_offer_preview_div" class="hide gridPhotoGallery__img"></div>
                <i class="hide kl-icon glyphicon glyphicon-search circled-icon ci-large"></i></div>
             </div>
             </div>
             </div>
             <div class="clearfix"></div>
  </div>
  

    <div class="form-group">

        <div style="position:relative;">
        <?php echo $form->textarea($model, 'description', array('class' => 'form-control auto-size', 'placeholder'=>'Введите описание объявления')); ?>
        <?php echo $form->error($model,'description'); ?>
        </div>
    </div>
    
    <div class="clearfix"></div>
    

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
            $btn_text = 'ОПУБЛИКОВАТЬ';
         } else {
            $btn_text = 'РЕДАКТИРОВАТЬ';
         }

  ?>

        
</div>

<div class="form-actions col-xs-12 m-t-20" style="margin-bottom: 70px;">
<button name="formSubmit" id="reviewFormSubmit" class="btn-submit btn-element btn btn-default-over" type="submit"><?php echo $btn_text; ?></button>
<?php 
// if($model->isNewRecord){ 
 ?>
<button  id="predprosmotr_article" name="preview" value="1" class="btn-submit btn-element btn btn-default-over" type="submit">Предпросмотр</button>
<span class="aj-loader m-l-20"></span>
<?php 
// } 

?>
</div>
</div>   
</div>
</div>
<div class="row" id="article_preview">
    
</div><!-- row -->

<?php $this->endWidget(); ?>
<?php } else { ?>
<a class="kl-login-box" href="#login_panel">Войдите</a> чтобы добавить статью


<?php  } ?>
</div>
</section>


<?php
if(!Yii::app()->user->isGuest){

$uploadLink = Yii::app()->createUrl('file/upload/upload',array('inputName'=>'tmpFiles','uploadsession'=>$this->uploadsession));
$unlinkLink = Yii::app()->createUrl('file/upload/unlink',array('uploadsession'=>$this->uploadsession));
$deleteLink = Yii::app()->createUrl('file/file/deletearticlefile');

$scriptDd = "

function clearStorage(){
  $('#article-form')[0].reset();
  if( window.localStorage ) {
	 
   window.localStorage.clear('article-form');
   localStorage.removeItem('article-form');
   $('#article-form').trigger('reset_state');
	 $(window).unbind('unload.rememberState'); 
   
 }
 
}
function makeReviewAfterStorage(){
    
    
    $.when( $('#article-form').rememberState('restoreState') ).done(function() {
    if( window.localStorage ) {
        var lstorage = JSON.parse(localStorage.getItem('article-form'));
        
        if (lstorage != 'undefined' || lstorage != 'null') {
            
            lstorage = JSON.parse(localStorage.getItem('article-form'));
            for (var i in lstorage) {

                if(lstorage[i].name == 'Article[description]'){

                    $('#Article_description').summernote('code',lstorage[i].value);
                }

            }
           
           
        }
      } 

    });
  

}


$(function(){

// $('.selectpicker').selectpicker();
$('#Article_categories_ar').select2({
       // minimumResultsForSearch: -1,
        allowClear: false,
        placeholder: ''
});
$('#article-form input.select2-search__field').focus(function(){
  $('#article-form .select2-selection').addClass('focused');
}).blur(function(){
  $('#article-form .select2-selection').removeClass('focused');
});

var im_src = ''; 
";
if($model->isNewRecord){
    $scriptDd .= "makeReviewAfterStorage();";
} 
else {
  $im_link = '';
  if($model->logotip){
    $im_link = $model->getUrl('720x400');
  } 
  $scriptDd .= "im_src = '".$im_link."'";

}
$scriptDd .= 
"
var dropzoneSpecial = new Dropzone('#special_offer_addphoto', {
        // Prevents Dropzone from uploading dropped files immediately
        autoProcessQueue: true,
        url: '/file/file/uploadarticle',
        maxFilesize: ".$sizeLimit.",
        uploadMultiple:false,
        paramName: 'tmpLogotip',
        thumbnailWidth: 560,
        thumbnailHeight: 280,
        dictRemoveFile:'',
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        init:function(){
          if(im_src){
                  $('#special_offer_preview').show();
                  $('#special_offer_preview_image').attr('src', im_src).show();
                  $('#special_offer_preview a').attr('href', im_src);
          }
                  this.on('success',function(file, serverResponse){
                    var id = $(this.element);
                    id.find('.progress').remove();
                    var response = $.parseJSON(serverResponse);
                    console.log(response)
                    if (response && response.success == true && response.name){
  
                      
                    }
                  });    
                 
        },
        previewsContainer : '#special_offer_preview_image',

        
}).on('addedfile', function(file) {
      // $('#article-form input[name=\"Article[tmpLogotip]\"]').remove();       
}).on('success', function(file, serverResponse) {
        var src;
        var response = $.parseJSON(serverResponse);
        if (response && response.success == true && response.fileName && response.tmpFile){
            src = '/uploads/tmp/'+response.tmpFile;
            $('#special_offer_preview').show();
            $('#special_offer_preview_image').attr('src', src).show();
            $('#special_offer_preview a').attr('href', src);
           // $('#special_offer_preview_div').css('background-image','url('+ src +')');
            // $('#article-form').append('<input type=\"hidden\" name=\"Article[tmpLogotip]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
            $('#article-form input[name=\"Article[tmpLogotip]\"]').attr('value', response.fileName);
       
        }
        
});
";
if($model->isNewRecord){
$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
//$url1 = str_replace("\\","/",$url1_thumb);
if(Yii::app()->session->itemAt($this->uploadlogosession)){
    $datas = Yii::app()->session->itemAt($this->uploadlogosession);
    end($datas);         // move the internal pointer to the end of the array
    $lastkey = key($datas);

    if(is_array($datas)){
        $cnt = 0; 

        foreach($datas as $key => $value){
            if($key == $lastkey){
                if(file_exists(Yii::getPathOfAlias('webroot').$folder.$value)){
                    $cnt++;
                        $scriptDd .='
                        var sessFileLogo'.$cnt.' = {
                            "name": "'.$key.'",
                            "size": "'.filesize(Yii::getPathOfAlias('webroot').$folder.$value).'",
                            "ext": "'.pathinfo(Yii::getPathOfAlias('webroot').$folder.$value, PATHINFO_EXTENSION).'"
                        };
                         var linkThumbLogo = "'.$folderLink.$value.'";
                        src = "/uploads/tmp/'.$value.'";
                        $("#special_offer_preview").show();
                        $("#special_offer_preview a").attr("href", src);
                        $("#special_offer_preview_image").attr("src", src).show();
                      //  $("#special_offer_preview_div").css("background-image","url("+ src +")");
                        $("#article-form input[name=\'Article[tmpLogotip]\']").attr("value", sessFileLogo'.$cnt.'.name);

                ';
                }
            }
        }
    }
}
}
$scriptDd .="
});
";
Yii::app()->clientScript->registerScript("scriptr", $scriptDd, CClientScript::POS_END);
}
?>