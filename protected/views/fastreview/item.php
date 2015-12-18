<?php  
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');

$thisUrl = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$model->id, 'dash'=>'-', 'themeurl'=>$model->category->url,'itemurl'=>$model->url));
$search = null;
  $check = null;
  $term = null;

?>


<div class="block-header" style="margin-top:45px;">
     <h2 class="org_title" style="text-transform:none;"><?php echo CHtml::encode($model->title); ?></h2>
</div>
<div class="row">
<div class="col-sm-8 p-r-8">

<?php
$images = $model->images;
if(!empty($images)){
	$src = $images[0]->getUrl('800x300xC', 'adaptiveResizeQuadrant');
	// $image = Yii::app()->createAbsoluteUrl($model->getUrl('200x100xC','adaptiveResizeQuadrant'));
?>
<div class="card-body m-b-20">
<div style="width:100%;height:300px;overflow: hidden;">
<div id="article_page_header"  onclick="zoomclick();" style="cursor:pointer;background-image:url('<?php echo $src; ?>');">
</div>
<div  class="gallery-nav lightbox">
<?php
if(!empty($images)){ 
        
        foreach($images as $k=>$foto){
            if($foto){
                // $src = Yii::app()->createAbsoluteUrl('file/company', array('id'=>$foto->id));
                $src = $model->getOrigFilePath().$foto->filename;
            //  $image = $model->getThumbsFilePaths($foto->filename);
                $image = $foto->getUrl('500','resize');

              //  echo CHtml::link($image, $src, array('class'=>'', 'data-lightbox'=>'lb-'.$model->id));
                echo '<div  data-src="'.$src.'"><div class="lightbox-item"><img src="'.$image.'" /></div></div>';
            }
        }
        
    } 
    ?>
</div>
</div>
</div>
<div class="clearfix"></div>
<?php
}
?>
<div class="card">
<div class="card-body card-padding advert_item">
<?php 
?>
<?php 
if($model->address){
	echo '<p style="cursor:pointer;" data-lat="<?php echo $model->lat; ?>" data-lng="<?php echo $model->lng; ?>" data-target="#modal_mesto" data-toggle="modal"><span class="titles">Местоположение:</span>&nbsp; <span class="cities">'.CHtml::encode($model->address).'</span></p>';
}
?>
<p class="description"><?php echo nl2br(CHtml::encode($model->description)); ?></p>
<p class="c-gray m-b-0 f-13" style="vertical-align:middle;"><span style="display:inline-block;vertical-align:middle;"><a class="c-gray" href="<?php echo Yii::app()->createAbsoluteUrl('/fastreview/view', array('url'=>$model->category->url)); ?>"><?php echo $model->category->title; ?></a></span>&nbsp;&nbsp; <span style="font-size:20px;font-weight:300;vertical-align:middle;">|</span> &nbsp;&nbsp;<span style="display:inline-block;vertical-align:middle;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created_date); ?></span></p>



</div><!-- card-body -->
</div><!--card -->
</div>
<?php 
if(!Yii::app()->user->isGuest && Yii::app()->user->id == $model->author){ ?>
<div class="hide col-sm-4 p-l-8">
<div class="card m-mt-30">
<div class="card-body card-padding" style="padding-left:36px;">
<div><a href="<?php echo Yii::app()->createAbsoluteUrl('/fastreview/update', array('id'=>$model->id)); ?>" class="btn btn-success btn-icon">
<i class="md md-mode-edit" style="line-height:40px;"></i></a>
<span style="margin-left:10px;" class="f-12">Редактировать объект</span></div>
<div style="margin-top:12px;"><a id="sa-warning"  href="<?php echo Yii::app()->createAbsoluteUrl('/fastreview/deleteadvert', array('id'=>$model->id)); ?>" class="btn btn-success btn-icon">
<i class="md md-delete" style="line-height:40px;"></i></a>
<span style="margin-left:10px;" class="f-12">Удалить объект</span></div>
</div>              
</div>
</div> 
<?php } ?>
</div><!-- row -->

<?php
if(!empty($pohs)){?>
<div class="row">
<div class="col-xs-12">
<div class="rootCategory" style="font-size:20px;font-weight:300;margin-left:20px;margin-bottom:18px;">
      Похожие объекты
 </div>
 </div>
 </div>
<div class="row">
<div class="col-sm-8 p-r-8">
<div class="card">
<div class="col-xs-12 item-article-list five-column">
<div id="journal_listview" class="list-view">
<div class="row">
<?php 
foreach ($pohs as $poh) { 

$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$poh->id, 'dash'=>'-', 'themeurl'=>$poh->category->url,'itemurl'=>$poh->url));

$imgs = ObjectsImages::model()->findAll(array('condition'=>'object='.$poh->id,'order'=>'date_uploaded, id'));

if($imgs)
{
 $src = $imgs[0]->getUrl('320x320xT','adaptiveResizeQuadrant',false,'filename');
 } else {
    $src = '/img/cap.gif';
 }
    ?>
    <div class="article-bg-container" >
    <a href="<?php echo $url; ?>" class="article-bg" style="display:block;background-image:url('<?php echo $src; ?>');z-index:10;"></a>
    <a href="<?php echo $url; ?>" class="caption" style="display:block;color:#5e5e5e;background-color:#fff;padding:6px 15px; height:60px;width:100%;overflow:hidden;">
    <table  style="width:100%;height:36px;">
        <tr>
        <td  style="height:36px;overflow:hidden;vertical-align:middle;">
        <div class="article_caption_title" style="line-height:1em;height:28px;font-size:12px;overflow:hidden;"><?php echo CHtml::encode($poh->title); ?></div>
        <?php 
        ?>
        </td>
    </tr></table></a>
    </div>
<?php }
?>
</div>
</div>
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
<div id="modal_mesto" class="modal fade modal-vcenter" tabindex="-1" role="dialog" style="display:none;">
  <div class="modal-dialog modal-md" style="height:400px;">
        <div class="modal-content" style="width:100%;height:100%;">
            <div class="modal-body" style="width:100%;height:100%;padding:0;position:relative;">
            <button style="position:absolute;right:20px;top:20px;z-index:10;" type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px"><img src="/img/close_green.png" /></button>
            <div  style="width:100%;height:400px;position:absolute;left:0;top:0;z-index:2;">               
            <?php 
               $lat = $model->lat;
               $lng = $model->lng;
               $this->widget('ext.yandexmap.YandexMap',array(
                'id'=>'map_mesto',
                'protocol'=>'//',
                'load'=>'package.standard,package.clusters',
                'clusterIcon'=>'/img/clustergreen.png',
                'width'=>'100%',
                'height'=>400,
                'metro'=>false,
                'clustering'=>true,
                'zoom'=>14,
                'center'=>array($lat, $lng),
                'controls' => array(
                    'zoomControl' => false,
                    'typeSelector' => false,
                    'mapTools' => false,
                    'smallZoomControl' => true,
                    'miniMap' => false,
                    'scaleLine' => false,
                    'searchControl' => false,
                    'trafficControl' => false,
                    'fullscreenControl'=>false,
                    'geolocationControl'=>false,
                    'rulerControl'=>false
                ),
                'placemark' => $mapparams,
            ));
            ?>
            </div>
            </div>
        </div> 
    </div>
</div> <!-- /.modal -->
<?php
$scriptDd = "
$(document).ready(function(){


  /*  $('.modal-vcenter').on('show.bs.modal', reposition);
    $(window).on('resize', function() {
        $('.modal-vcenter:visible').each(reposition);
    }); */

$('#modal_mesto').on('shown.bs.modal', function (e) {
    var lat = $(e.relatedTarget).data('lat');
    var lng = $(e.relatedTarget).data('lng');
  
 });
";
if(!empty($images)){
    $scriptDd .= "
var gallery = $('.gallery-nav.lightbox').lightGallery();
zoomclick = function(){
    gallery.destroy();
    gallery = $('.gallery-nav.lightbox').lightGallery();
    $('.gallery-nav.lightbox img:eq(0)').trigger( 'click' );
}
";
}
$scriptDd .= "
$('#sa-warning').click(function(){
    swal({   
        title: 'Подтвердите удаление',   
        text: '',   
        type: 'warning',   
        showCancelButton: true,   
        confirmButtonColor: '#DD6B55',   
        cancelButtonText: 'Отмена',
        confirmButtonText: 'Удалить',   
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {
            window.location = $('#sa-warning').attr('href');
        } else {
            swal('Отмена', '', 'error');
        }
    });
    return false;
});
";
if($images){ 
	$scriptDd .= "
	$('#lightGallery').lightGallery({
		mode:'fade'
	});";
}	
$scriptDd .= "
})";
	Yii::app()->clientScript->registerScript("scriptgal", $scriptDd, CClientScript::POS_END);

?>