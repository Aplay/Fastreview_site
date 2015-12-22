<?php  
$themeUrl = Yii::app()->theme->baseUrl;
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$cs = Yii::app()->ClientScript;
  
  Yii::app()->clientScript->addPackage('googleMap', array(
      'baseUrl'=>'https://maps.googleapis.com/maps/api',
      'js'=>array('js?&sensor=false&language=' . Yii::app()->language . '&region='.Yii::app()->language)
  ));
  Yii::app()->clientScript->registerPackage('googleMap');
  Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/lib/gmap3.js', CClientScript::POS_END);
  Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/view/script-map.js', CClientScript::POS_END);

Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/jquery-linkifier/jquery.linkify.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/dropzone/dropzone.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/dropzone/dropzone.css');
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/swipe/swipe.js', CClientScript::POS_END);



$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
$thisUrl = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$model->id, 'dash'=>'-', 'themeurl'=>$model->category->url,'itemurl'=>$model->url));

Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($this->pageDescription, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag('article', null, null, array('property' => "og:type"));
Yii::app()->clientScript->registerMetaTag($thisUrl, null, null, array('property' => "og:url"));





$search = null;
  $check = null;
  $term = null;



if (!empty($model->address)) {

  /*
  непонятные коордирнаты, могут быть не верны
  $latlng = '';
  if (!empty($record->strReserved)) {
    $strReserved = CJSON::decode($record->strReserved);
    if(is_array($strReserved) && !empty($strReserved['coords'])  && isset($strReserved['coords']['x']) && isset($strReserved['coords']['y'])){
      $latlng = '['.$strReserved['coords']['x'].','.$strReserved['coords']['y'].']';
    }
  }*/
  $words = explode(' ',$model->address);
    $addressParts = ''; $addressArray = array(); $cnt = 0;
  foreach($words as $word){


        if($cnt == 0){
            $addressParts .= $word;
        } else {
            $addressParts .= ' '.$word;
        }
        
        $addressArray[] = $addressParts;


        $cnt++;
   }   
  if(!empty($addressArray)){
      $addressArrayReverse = array_reverse($addressArray);
      foreach($addressArrayReverse as $addr){
          echo '<input type="hidden" name="addr" class="addrParts" value="'.$addr.'" />';
      }
  }
$scriptAdd ='
      var addrAr = [];
            var linkList = $(".addrParts");
            linkList.each(function (i, item) {
                var $item = $(item);
                addrAr.push($item.attr("value")) ;

            });
            var i =0;
            doLoop(addrAr);

            function doLoop(addrAr) {
               //exit condition
               if (i >= addrAr.length) {
                  return;
               }
               //loop body- call ajax
               $.ajax({
               url: "http://maps.googleapis.com/maps/api/geocode/json?address="+ addrAr[i] +"&sensor=false&language='.Yii::app()->language.'",
               cache: false,
               success: function(data) {
                  if (data.status == "OK" && data.results) {

                        var mapLocation = $("#map"),
                        mapPoint = [data.results[0].geometry.location.lat, data.results[0].geometry.location.lng];
                        marker = "/img/markers/mark-place-w.png";
                        //$("#Record_coords_lat").attr("value", data.results[0].geometry.location.lat);
                        //$("#Record_coords_lng").attr("value", data.results[0].geometry.location.lng);
                        initializSimpleeMap(mapLocation, mapPoint, marker);

                    } else {
                          i++;
                          //go to next iteration of the loop
                          doLoop(addrAr);
                    }
               }
               });
            }

      ';
Yii::app()->clientScript->registerScript('add-map', $scriptAdd, CClientScript::POS_END);    
  }
  ?>
<div class="row m-t-25">
<div class="col-sm-9 col-sm-offset-1 col-md-8 col-md-offset-2">
<div class="card-body m-b-20 p-0">
<?php
$images = $model->images;
$imageShare = '';

if(!empty($images)){
// $imageShare = Yii::app()->createAbsoluteUrl($images[0]->getOrigFile());
$imageShare = Yii::app()->createAbsoluteUrl($images[0]->getUrl('500','resize'));
  Yii::app()->clientScript->registerMetaTag($imageShare, null, null, array('property' => "og:image"));
 ?>
<div data-interval="false" data-ride="carousel" class="carousel slide gallery" id="carouselFull">
            <ol class="carousel-indicators">
            <?php
        $cntI = count($images);
        if($cntI > 1){  
        foreach($images as $k=>$foto){
            if($foto){
                // $src = Yii::app()->createAbsoluteUrl('file/company', array('id'=>$foto->id));
                $src = $model->getOrigFilePath().$foto->filename;
            //  $image = $model->getThumbsFilePaths($foto->filename);
                $image = $foto->getUrl('800x500','resize');

              //  echo CHtml::link($image, $src, array('class'=>'', 'data-lightbox'=>'lb-'.$model->id));
               // echo '<div  data-src="'.$src.'"><div class="item-gal-im" style="background-image:url('.$image.');"></div></div>';
           echo  '<li ';
           if($k==0){
            echo ' class="active" ';
           }
           echo ' data-slide-to="'.$k.'" data-target="#carouselFull"></li>';
            
            }
        }
      }
        ?>
   </ol>
                
            <div class="carousel-inner" role="listbox">
                <?php  foreach($images as $k=>$foto){
            if($foto){
                $src = $model->getOrigFilePath().$foto->filename;
                echo '<div style="background-image: url('.$src.')" class="galleryItem item';
                if($k==0){
                  echo ' active ';
                  } 
                echo '"></div>';
               } 
          } ?>             
          </div>
          <?php if($cntI > 1){ ?>   
                <a data-slide="prev" role="button" href="#carouselFull" class="left carousel-control"><span class="fa fa-chevron-left"></span></a>
                <a data-slide="next" role="button" href="#carouselFull" class="right carousel-control"><span class="fa fa-chevron-right"></span></a>
          <?php } ?>
  </div>
  <?php
    } 
    ?>
   


<div class="clearfix"></div>
<?php if (!empty($model->address)) { ?>

<div class="card">
<div class="map_box">
<div class="triangle"></div>
<div class="map fcolor" id="map"></div>
<div class="map_bg">
<?php if (!empty($model->address)) { ?>
<div class="address">
  <?php echo CHtml::encode($model->address); ?>
</div>
<?php } ?></div>
</div>
</div>
<?php } ?>

<div class="card m-t-25">
<div class="card-body advert_item">
<p class="t-uppercase f-18"><?php echo CHtml::encode($model->title); ?></p>
<?php 
if($model->description){ ?>
<p class="description"><?php echo nl2br(CHtml::encode($model->description)); ?></p>
<?php
  } ?>
<?php 
if($model->link){
  echo '<p  ><span class="url-link"><a target="_blank" href="'.$model->link.'">'.CHtml::encode($model->link).'</a></span></p>';
}
?>
<p class="c-gray m-b-0 f-13" style="vertical-align:middle;"><span style="display:inline-block;vertical-align:middle;"><a class="c-gray" href="<?php echo Yii::app()->createAbsoluteUrl('/fastreview/view', array('url'=>$model->category->url)); ?>"><?php echo $model->category->title; ?></a></span>&nbsp;&nbsp; 
<span class="hide" style="font-size:20px;font-weight:300;vertical-align:middle;">|</span> &nbsp;&nbsp;
<span style="display:inline-block;vertical-align:middle;">
<?php // echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created_date); ?></span>
</p>

</div><!-- card-body -->
</div><!-- card -->
</div><!-- card-body -->
</div><!-- col -->

<div class="col-sm-2">
<div class="text-left">
<div>ДОБАВИТЬ:</div>
<div><button data-toggle="modal" data-target="#add_photo" class="m-t-15 btn bgm-lightblue btn-icon waves-effect waves-circle waves-float">
<i class="zmdi zmdi-camera"></i></button>
</div>
<div style="margin-top:60px;">ПОДЕЛИТЬСЯ:</div>
<?php $this->renderPartial('application.views.common._share',array('thisUrl'=>$thisUrl,'image'=>$imageShare));
 ?>           

</div>
</div> 

</div><!-- row -->
<div class="row m-t-25">
<div class="col-sm-9 col-sm-offset-1 col-md-8 col-md-offset-2">
<div class="row" >
<div  class="col-sm-6 p-r-8">
<?php  $this->widget('EPoll', array('org_id'=>$model->id,'type'=>PollChoice::TYPE_PLUS)); ?>
</div>
<div  class="col-sm-6 p-l-8">
<?php  $this->widget('EPoll', array('org_id'=>$model->id,'type'=>PollChoice::TYPE_MINUS)); ?>
</div>
</div>

<?php  $this->renderPartial('application.modules.comments.views.comment.new_comment_obj', array(
    'model'=>$model,
  ));  ?>
</div>
</div>

<?php
if(!empty($pohs)){ ?>
<div class="row">
<div class="col-sm-12  col-md-10 col-md-offset-1">
<div class="rootCategory" style="font-size:18px;text-align:center;margin-bottom:18px;">
      ПОХОЖЕЕ
 </div>
<div class="card">
<div class="item-article-list three-column">

<?php 
foreach ($pohs as $poh) { 

$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$poh->id, 'dash'=>'-', 'themeurl'=>$poh->category->url,'itemurl'=>$poh->url));

$imgs = ObjectsImages::model()->find(array('condition'=>'object='.$poh->id,'order'=>'date_uploaded, id'));

if($imgs)
{

 $src = $imgs->getUrl('320x320xT','adaptiveResizeQuadrant',false,'filename');
 } else {
    $src = '/img/cap.gif';
 }
    ?>
    <div class="article-bg-container" >
    <a href="<?php echo $url; ?>" class="article-bg  c-white t-uppercase text-center" style="background-image:url('<?php echo $src; ?>');">
     <div class="article_caption_title vertical-align-center p-l-20 p-r-20"><?php echo CHtml::encode($poh->title); ?></div>
    </a>
    </div>
<?php }
?>

</div>
</div>
</div>
</div>
<?php
}
$mapparams = array();
if($model->lat && $model->lng){
      $address = $model->address;
    $mapparams[] = array(
      'lat'=>floatval($model->lat),
      'lon'=>floatval($model->lng),
      'properties'=>array(
                    
                   // 'balloonContentHeader'=>$model->title,
                   // 'balloonContentBody'=>$address,
                  //  'balloonContentFooter'=>$model->phone,
                    'content'=> '<a href="#"><div class="ya_hint_box"><div class="ya_hint_list"><div class="ya_hint_title">'.CHtml::encode($model->title).'</div><div>'.$address.'</div></div></div></a>',

                ),
      'options'=>array(
         // 'preset'=>'twirl#violetIcon',
          'iconLayout'=> 'default#image',
          'iconImageHref'=>'/img/address_sign38x54_green.png',
          // Размеры метки.
          'iconImageSize'=>array(38,54),
          // Смещение левого верхнего угла иконки относительно
          // её "ножки" (точки привязки).
          'iconImageOffset'=> array(-19,-54),
        'balloonShadow'=>false,
        'balloonAutoPan'=> true,
        'balloonAutoPanMargin'=> [10,10,10,10],
          'balloonShape'=> [
                    'type'=> 'Rectangle',
                    'coordinates'=> [
                        [-100, -120],
                        [100, 0]
                    ]
                ],
         // 'balloonLayout'=>'MyBalloonLayout',

          'balloonPanelMaxMapArea'=> 0,
          'balloonCloseButton'=> false,
           // Балун будем открывать и закрывать mouseover по иконке метки.
          'hideIconOnBalloonOpen'=> false,
        ),
      );
    }
    ?>

<!-- Modal -->
<div id="add_photo" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
 
      <div class="clearfix"></div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'pinboard-form',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'afterValidate' => "js: function(form, data, hasError) {\n"
                                ."      if(jQuery.isEmptyObject(data)) {\n"
                                ."          $('#add_photo').modal('hide');\n"
                ."      } else {\n"
                ."        if('flag' in data && data.flag == true){\n"
                ."          $('#add_photo').modal('hide');\n"
                ."      location.reload();\n"
                ."      } \n"

                                ."    return false;\n"
                                ."}\n"
                ),
                'htmlOptions'=>array('class'=>'', 'enctype' => 'multipart/form-data')
                )); 
            ?>
      <div class="modal-body p-l-20 p-r-20 ">
            
            <input type="hidden" name="PinboardStrict_id" value="" id="hid" />
            <?php echo $form->hiddenField($model, 'id', array('id'=>'hproject')); ?>

      <div class="form-group m-b-20">
                    
                    <div id="dropzone" class="dropzone-box" style="min-height: 270px; margin-top: 10px;margin-bottom:20px;">
                        <div class="dz-default dz-message f-20 text-center" style="font-weight:normal;color:#5e5e5e;margin-top:-70px;">
                            <button type="button" style="width:54px;height:54px;margin-bottom:10px;" class="btn bgm-lightblue btn-icon waves-effect waves-circle waves-float btn-lg" ><i class="zmdi zmdi-camera" style="font-size:28px;"></i></button><br>
                            Перетащите файлы сюда<br><span class="f-12 c-gray">или нажмите на иконку чтобы выбрать вручную</span>
                        </div>
                            <div class="fallback">
                                <input name="tmpFiles" type="file" multiple="" />
                            </div>
                    </div>
                </div>
                <div class="form-group m-b-20">
                    <div id="dropzone-tmp"  class="lightbox row"></div>
                </div>  
      </div> <!-- / .modal-body -->
      
      <div class="modal-footer b-t-0 p-t-0 p-r-20 p-b-20 text-right pull-right">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Отмена</button>
        <button type="submit" class="btn btn-default-over btn-sm" id="subButPin">Добавить</button>
      </div>
            
            <div class="clearfix"></div>
            <?php $this->endWidget(); ?> 
    </div> <!-- / .modal-content -->
  </div> <!-- / .modal-dialog -->
</div> <!-- /.modal -->
<!-- / Modal -->

<?php
$uploadLink = Yii::app()->createUrl('file/file/upload');
$unlinkLink = Yii::app()->createUrl('file/file/unlink');
$deleteLink = Yii::app()->createUrl('file/file/deleteobjectsfile');

$scriptDd = "
$(document).ready(function(){

  $('.map_box .map_bg').click(function(e){
    $('.map_box').toggleClass('active');
  });

var dropzone = new Dropzone('#dropzone', {
        url: '".$uploadLink."',
        paramName: 'tmpFiles', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        parallelUploads: 10,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#dropzone-tmp',
        addRemoveLinks: true,
        dictRemoveFile:'',
        removeLinksClass: 'dz-remove btn bgm-lightblue btn-icon waves-effect waves-circle waves-float',
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
                    $('#pinboard-form').append('<input type=\"hidden\" name=\"Objects[tmpFiles][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
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

                $("#pinboard-form").append("<input type=\'hidden\' name=\'Objects[tmpFiles][]\' value=\'" + sessFile'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

          ';
          }
      }
    }
}
if(!empty($images)){
    $scriptDd .= "
var gallery = $('.gallery-nav.lightbox').lightGallery();
zoomclick = function(){
    gallery.destroy();
    gallery = $('.gallery-nav.lightbox').lightGallery();
    $('.gallery-nav.lightbox img:eq(0)').trigger( 'click' );
}

	$('#lightGallery').lightGallery({
		mode:'fade'
	});
// $('.carousel').carousel();

 /* $('.carousel-inner').Swipe( {
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
            $(this).parent().carousel('next'); 
        },
        swipeRight: function() {
            $(this).parent().carousel('prev');
        }
    });*/
  
";
}	

$scriptDd .= "


})";
	Yii::app()->clientScript->registerScript("scriptgal", $scriptDd, CClientScript::POS_END);

?>