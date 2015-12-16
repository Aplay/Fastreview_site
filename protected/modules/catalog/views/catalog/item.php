
<?php  
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;

$themeUrl = Yii::app()->theme->baseUrl;



Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/bootstrap-select/bootstrap-select.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/jquery-linkifier/jquery.linkify.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/dropzone/dropzone.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/mediaelement_2.18.1/mediaelement-and-player.min.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/mediaelement_2.18.1/mediaelementplayer.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/dropzone/dropzone.css');

Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');

// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/view_imagesloader.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/lightbox/js/lightbox_mine.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerCssFile($themeUrl.'/js/plugins/lightbox/css/lightbox.css');
 Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);
 


$cs = Yii::app()->clientScript;
// $params = 'js?&language=' . Yii::app()->language . '&v=3.exp&libraries=places';
$params = 'jsapi';
Yii::app()->clientScript->addPackage('googleMap', array(
  //'baseUrl'=>'https://maps.googleapis.com/maps/api',
	'baseUrl'=>'https://www.google.com/',
   'js'=>array($params)
));
$cs->registerPackage('googleMap');

// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai/assets/js/LAB.min.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai/assets/js/sabaidropdown.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai/assets/js/bootstrap.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai/assets/js/jquery.sabai.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai/assets/js/jquery.autosize.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai/assets/js/jquery.scrollTo.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai-directory/assets/js/sabai-googlemaps-directionmap.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/sabai129/sabai-directory/assets/js/sabai-googlemaps-autocomplete.js', CClientScript::POS_END);

// Yii::app()->clientScript->registerCssFile($themeUrl.'/js/plugins/sabai129/sabai/assets/css/main.css');


if($model->city){
  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$model->city->url, 'id'=>$model->id,  'itemurl'=>$model->url));
 // $url_metro = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$model->city->url, 'metro'=>'aviamotornaya'));
} else {
  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/itemnocity', array('id'=>$model->id));
 // $url_metro = '#';
}
//Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:site_name"));
Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($this->pageDescription, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag('article', null, null, array('property' => "og:type"));
Yii::app()->clientScript->registerMetaTag($url, null, null, array('property' => "og:url"));


$addStyle = ''; $cnt_fotos = 0;
$fotos = array();

$imageI = $imageF = $feeds = $feedsF = array();

if($model->images){ 
	foreach($model->images as $image){
		$fotos[] = $image;
	}
}
  if(empty($fotos)){
  	$addStyle = ' display:none;';
  }
?>
<?php 
if($model->verified == false 
	&& $model->views_count <=1 
	){
?>
<div class="row" id="new_org_box">
<div class="col-xs-12">
<div class="card m-b-15">
<div class="card-body card-padding" style="position:relative;padding-left:50px;padding-right:40px;padding-bottom:40px;">
<div class="closebox" onclick="$('#new_org_box').hide();"></div>
<div style="font-size:25px;font-weight:300;margin-top:10px;margin-bottom:24px;">Вы успешно добавили организацию.</div>
<p>Теперь она всегда будет доступна по этой ссылке: <?php echo CHtml::link($url,$url); ?></p>
<p>После проверки, организация будет доступна в общем каталоге.</p>
<p style="margin-top:40px;">Чтобы привлечь к себе пользователей и улучшить данные, вы можете:</p>
<div class="row">
	<div class="col-sm-4  p-r-8"> 
		<div class="h-w-g" style="border:1px solid #f1f1f1;border-radius:6px;vertical-align:middle;text-align:center;">
		<button type="button" style="width:54px;height:54px;margin-top:50px;margin-bottom:50px;" class="btn btn-lg btn-success btn-icon" data-toggle="modal" data-target="#add_photo"><i class="md md-photo-camera" style="font-size:28px;"></i></button><span class="f-12" style="margin-left:10px;vertical-align:middle;line-height:1.3em;">Добавить фотографии</span></div>
	</div>
	<div class="col-sm-4 p-l-8"> 
	<div class="h-w-g" style="border:1px solid #f1f1f1;border-radius:6px;vertical-align:middle;text-align:center;">
	<button type="button" style="width:54px;height:54px;margin-top:50px;margin-bottom:50px;"  class="btn btn-lg btn-success btn-icon"  ><i class="md md-wallet-giftcard" style="font-size:28px;"></i></button><span class="f-12" style="vertical-align:middle;margin-left:10px;display:inline-block;text-align:left;line-height:1.3em;">Добавить специальные<br>предложения и акции</span></div>
	
	</div>
</div>
</div>
</div>
</div> 
</div>
<?php
}
?>
<?php if($model->status_org == Orgs::STATUS_ACTIVE){ ?>
<div class="row" >
<div class="col-sm-45 p-r-8">
<div id="header-left-photo" class="header-left-photo">
	<div class="item-photo-view">
	<div  id="item-photo-view" style="width:100%;">
	<?php if(!empty($fotos)){ 
		$cnt_fotos = count($fotos);
		if(!empty($imageI))
			$cnt_fotos += count($imageI);
		if(!empty($imageF))
			$cnt_fotos += count($imageF);
		// $src = $model->getOrigFilePath().$fotos[0]->filename;
		 $src = $fotos[0]->getUrl('500','resize');
		// echo CHtml::image($src, '', array('class'=>'img-responsive')); 
		 echo '<div style="width:100%;height:250px;background:url('.$src.') no-repeat center center; background-size:cover;"></div>';
		// echo $model->getThumbsFilePaths($fotos[0]->filename, 'bg');
		// echo CHtml::link('<i></i>', $src, array('class'=>'zoom lazy-load-src','data-lightbox'=>'lb-'.$model->id,'data-title'=>$model->title));
		 echo '<i class="zoom lazy-load-src" onclick="zoomclick();"></i>';
		Yii::app()->clientScript->registerMetaTag($src, null, null, array('property' => "og:image"));
		
	?>
<?php } else { 

	 echo '<div class="empty-add" style="width:100%;height:250px;background:url(/img/item_bg.jpg) no-repeat center center; background-size:cover;"></div>';
		
}
/* elseif(!empty($imageI)){
			$cnt_fotos = count($imageI);
			if(!empty($imageF))
				$cnt_fotos += count($imageF);
			$src = $imageI[0]['standard']; 
			echo '<div class="item-gallery-bg" style="background-image:url('.$src.'); "></div>';
			echo '<img class="undergal" alt="'.$model->title.'" src="'.$src.'">';
			echo CHtml::link('<i></i>', $src, array('class'=>'zoom lazy-load-src','data-lightbox-open'=>'lb-'.$model->id,'data-title'=>$model->title));

	  } elseif(!empty($imageF)){
	  		$cnt_fotos = count($imageF);
	  		$src = $imageF[0]['standard']; 
	  		echo '<div class="item-gallery-bg" style="background-image:url('.$src.'); "></div>';
			echo '<img class="undergal" alt="'.$model->title.'" src="'.$src.'">';
			echo CHtml::link('<i></i>', $src, array('class'=>'zoom lazy-load-src','data-lightbox-open'=>'lb-'.$model->id,'data-title'=>$model->title));
	  } */
	?>
	<div class="gallery-controls"><div class="gallery-photos-size" <?php 
	if(!empty($fotos)){ echo ' style="display:block;" ';}
	?>>
		<i class="photo_gal_size"></i>
		<span id="cnt_fotos" style="vertical-align: middle;"><?php echo $cnt_fotos; ?></span>
	    </div>
	<?php
	echo '<div  class="gallery-nav lightbox">';
	if(!empty($fotos)){ 
		
		foreach($fotos as $k=>$foto){
			if($foto){
				// $src = Yii::app()->createAbsoluteUrl('file/company', array('id'=>$foto->id));
				$src = $model->getOrigFilePath().$foto->filename;
			//	$image = $model->getThumbsFilePaths($foto->filename);
				$image = $foto->getUrl('500','resize');

			  //  echo CHtml::link($image, $src, array('class'=>'', 'data-lightbox'=>'lb-'.$model->id));
				echo '<div  data-src="'.$src.'"><div class="lightbox-item"><img src="'.$image.'" /></div></div>';
			}
		}
		
	} 
	echo '</div>';
	if(empty($fotos)){
		echo '<div  class="gallery-nav empty-add">';
		// echo '<a href="javascript:void(0);" class="zoom lazy-load-src"><i></i></a>';
		echo '<button style="width:54px;height:54px;" type="button" class="zoom  btn btn-success btn-icon btn-lg" data-toggle="modal" data-target="#add_photo"><i class="md md-photo-camera" style="font-size:28px;"></i></button>';
		echo '<div class="empty-zoom-text"><span class="f-20">Фотографий еще нет</span><br><span class="f-12">Будьте первым, кто добавит фотографии</span></div>';
		echo '</div>';
	}

	?>
	</div>
	
	
</div>
</div>
</div>
</div> 
<div class="col-sm-75 p-l-8">
<?php

$mapparams = array();
if($model->lat && $model->lng && $model->street){
      $address = '';
     $address .= $model->street;
  if($model->dom) { $address .= ', '.$model->dom; }
    $mapparams[] = array(
      'lat'=>floatval($model->lat),
      'lon'=>floatval($model->lng),
      'properties'=>array(
                    
                   // 'balloonContentHeader'=>$model->title,
                   // 'balloonContentBody'=>$address,
                  //  'balloonContentFooter'=>$model->phone,
                    'content'=> '<a href="#"><div class="ya_hint_box"><div class="ya_hint_list"><div class="ya_hint_title">'.$model->title.'</div><div>'.$address.'</div></div></div></a>',

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
<div class="header-map" id="header-map">
	<div class="main-map">
	
	<div class="main-map-view">
	<div class="map-text">
	<?php 

	if(!empty($model->nearest_metro) && !empty($model->nearest_metro_distance) && $model->nearestmetro){

		$size = MHelper::Ip()->size($model->nearest_metro_distance);
		echo 'до '.$model->nearestmetro->metro_name.' - '.$size;
	}
	?>
	</div>
	<?php
	$truemap = false;
	if(($model->lat && $model->lng) || ($model->city->latitude && $model->city->longitude)){
		 if($model->lat && $model->lng){
		 	$lat = $model->lat;
		 	$lng = $model->lng;
		 	$zoom = 14;
		 } else if($model->city->latitude && $model->city->longitude){
		 	$lat = $model->city->latitude;
		 	$lng = $model->city->longitude;
		 	$zoom = 12;
		 }
		 $truemap = true;
		 $this->widget('ext.yandexmap.YandexMap',array(
		        'id'=>'map_canvas',
		        'protocol'=>'//',
		        'load'=>'package.standard,package.clusters',
		        'clusterIcon'=>'/img/clustergreen.png',
		        'width'=>'100%',
		        'height'=>250,
		        'metro'=>false,
		        'clustering'=>true,
		        'zoom'=>$zoom,
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
		} else { // show city
			?>
<div class="page-slider" id="main-slider">
      <div id="fullwidthbanner-container" class="fullwidthbanner-container  revolution-slider">
        <div  id="fullwidthbanner" class="fullwidthabnner">
          <ul id="revolutionul">
            <!-- THE NEW SLIDE -->
            <li data-transition="zoomin" data-slotamount="7" data-masterspeed="100">
            <!--<li  data-transition="zoomin" data-slotamount="7" data-masterspeed="1000">-->
              <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
              <img src="<?php 
             // $src = Yii::app()->createAbsoluteUrl('city/city/file', array('id'=>$this->city_id));
              if(!$this->city->filename){
                $src = '/img/russia_bg.jpg';
              } else {
                $src = '/uploads/city/'.$this->city->id.'/'.$this->city->filename;
              }

              echo $src; ?>" alt="img1" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
<?php 
if(empty($fotos)){
	$slide_class = 'slide_title_white';
} else {
	$slide_class = 'slide_title_white_sm';
}
?>
            
   
            </li>     
          </ul>
            </div>
        </div>
    </div>

			<?php
		}
?>
	</div>
	<div class="main_map_buttons hidden-xs">
	<div class="card m-b-0" style="margin-left:16px;width:100%;min-height:250px;">
	<div class="card-body card-padding p-t-10 p-b-15">
		<div>Как проехать</div> 
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="DRIVING" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-car"></i></button><span style="margin-left:10px;" class="f-12">на машине</span></div>
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="TRANSIT" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-bus"></i></button><span style="margin-left:10px;" class="f-12">на транспорте</span></div>
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="BICYCLING" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-bike"></i></button><span style="margin-left:10px;" class="f-12">на велосипеде</span></div>
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="WALKING" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-walk"></i></button><span style="margin-left:10px;" class="f-12">пешком</span></div>
	</div>
	</div>
	</div>
	<div class="clearfix"></div>
</div><!-- main-map -->
    

</div>

    <div class="main_map_buttons visible-xs">
	<div class="card m-b-0" style="width:100%;min-height:250px;">
	<div class="card-body card-padding p-t-10 p-b-15">
		<div>Как проехать</div> 
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="DRIVING" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-car"></i></button><span style="margin-left:10px;" class="f-12">на машине</span></div>
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="TRANSIT" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-bus"></i></button><span style="margin-left:10px;" class="f-12">на транспорте</span></div>
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="BICYCLING" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-bike"></i></button><span style="margin-left:10px;" class="f-12">на велосипеде</span></div>
		<div style="margin-top:12px;"><button data-target="#modal_get_way" data-toggle="modal" data-type-id="WALKING" class="btn btn-success btn-icon waves-effect waves-button waves-float" type="button"><i class="md md-directions-walk"></i></button><span style="margin-left:10px;" class="f-12">пешком</span></div>
	</div>
	</div>
	</div>

</div>
</div>
<?php } else { ?>
<div class="card m-b-0">
<div class="card-body card-padding text-center" style="min-height:250px;"> 
<div class="md md-highlight-remove" style="font-size:90px;color:red;line-height:105px;"></div>
<div style="font-size:20px;font-weight:300;margin-bottom:5px;">По нашим данным, данная организация закрылась</div>
<div>Будьте внимательнее, данные не актуальны и отображаются, чтобы вы могли узнать о прошлом местонахождении.</div>

</div>
</div>
<?php } ?>
<div class="row" >
<div class="col-sm-8 p-r-8">
<div class="card m-t-30">
<div class="card-body card-padding org_item">
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>4));


/*
 if($model->logotip){
   $im = Yii::app()->createAbsoluteUrl('file/logotip',array('id'=>$model->id));
 } else {
    $im = '/img/org_cap.png';
 }
*/
 // echo CHtml::link(CHtml::image($im, '', array('class'=>'logotip img-responsive')), $url);

 ?>
<div  style="padding-left:0" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Organization" >
<div class="org_item_title pull-left">
<?php echo $model->title; ?>
</div>
<?php $wProcess = OrgsWorktime::workingProcessAgo($model->id, $this->city->utcdiff); 
if(!empty($wProcess)) { 
if($wProcess && $wProcess[1] && $wProcess[0] == 'Сейчас закрыто'){
	echo '<div class="org_works pull-left">откроется через '.$wProcess[1].'</div>';
} elseif($wProcess && $wProcess[0] == 'Сейчас открыто'){
	echo '<div class="org_works pull-left theme-color">'.$wProcess[0].'</div>';
} elseif($wProcess && $wProcess[0]) {
	echo '<div class="org_works pull-left">'.$wProcess[0].'</div>';
}

}
?>
<div class="clearfix"></div>
<div class="org_info">
<div class="org_rating" style="margin-bottom:5px;">
<?php
$this->renderPartial('application.views.common._star',array('data'=>$model,'show_count'=>false));
?>
</div>
<?php 
$categs = $model->categories;
 if($categs){
 	foreach ($categs as $cat){

 		$cat_url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$cat->url));
			
 		if(!empty($cat->logotip))
 		echo CHtml::link($cat->title, $cat_url, array('class'=>'f-11 nocolor', 'style'=>'display:block;background-image:url("'.Yii::app()->createAbsoluteUrl('file/file/logotip', array('id'=>$cat->id,'model'=>'Category','filename'=>'logotip','realname'=>'logotip_realname')).'"); background-position: top center;min-height:56px;background-size:34px 34px; padding-top:40px; margin:10px 10px 10px 0; background-repeat:no-repeat; max-width:55px; text-align:center'));	
 		// echo CHtml::link($cat->title, $cat_url, array('class'=>'parentCategoryElement'));
 	}
 }
?>
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>5));
?>
<?php
if(!empty($model->description)){
	echo CHtml::tag('p', array('class'=>'org_description'), $model->description);
}

?>

<ul>
<li class="org_address"><i class="md md-room c-green"></i>

<?php
//if($model->city)
 echo '<strong>Адрес:</strong> ';
echo '<span><span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
echo '<span itemprop="addressLocality">'.$this->city->title.'</span><span itemprop="streetAddress">';
if($model->street) { 
echo ', '.$model->street;
}
 if($model->dom) { 
 	echo ', '.$model->dom; 
 }
 echo '</span></span>';
 $districts = District::getDistricts($model);
if(($this->city->id==1 or $this->city->id==22) && !empty($districts) && isset($districts[1]))
{
 	echo '<br><span>'.$districts[1].'</span>';
}
elseif($this->city->id!=1 and $this->city->id!=22 && !empty($districts))
{
	$subaddr = '';
	if(!empty($model->nearest_metro) && $model->nearestmetro)
	{
		$subaddr .= '<br><span>Рядом с '.$model->nearestmetro->metro_name.'</span>';
	}
	foreach ($districts as $district) 
	{
		if(mb_strpos($district, 'микрорайон', 0, 'UTF-8') !== false )
		{
			if(!empty($subaddr))
				$subaddr .= ', ';
			$subaddr .='<br><span>'.$district.'</span>';
			break;
		}
	}
	echo $subaddr;
}
 echo '</span>';
 echo '<br><span class="org_phone_description">'.$model->address_comment.'</span>';
 ?>
 </li>
 <?php 
$phones = $model->orgsPhones;
if($model->status_org == Orgs::STATUS_ACTIVE && $phones){ 
?>
<li class="org_phone"><i class="md md-phone c-green"></i>
<?php
if(count($phones) > 1){
	 echo '<div style="margin-bottom:6px;"><strong>Телефоны:</strong></div>';
	 foreach($phones as $phon){
	  echo '<div>'.$phon->phone.' <span class="org_phone_description">'.$phon->description.'</span></div>';
	}
} else {
	echo '<strong>Телефон:</strong> ';
	foreach($phones as $phon){
	  echo $phon->phone.' <span class="org_phone_description">'.$phon->description.'</span>';
	}
}
  
?>
 </li>
<?php 
}

if($model->orgsHttp || $model->vkontakte || $model->facebook  || $model->twitter || $model->instagram || $model->youtube)
{ 
$https = $model->orgsHttp;
if($https){

 echo '<li class="org_site"><i class="md md-link c-green"></i>';
  $site = '';
  foreach ($https as $key => $st) {
  		$linktext = $st->description?$st->description:Orgs::parseUrlShow($st->site);
  		$sitelink = CHtml::link($linktext, $st->site, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
   		$site .= CHtml::tag('span', array(), $st->description?$sitelink:$sitelink.' <span class="org_phone_description">Официальный сайт</span>').'<br>';
  }
  if($site){
    $site = rtrim($site, '<br>');
    echo CHtml::tag('span', array(), $site);
  }
  echo '</li>';

	// echo CHtml::tag('span', array(), 'Официальный сайт:').'<br>';
	// $url = CHtml::link(Orgs::parseUrlShow($model->site), $model->site, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	// echo CHtml::tag('span', array(), $url).'<br>';
}

if($model->vkontakte){
	echo '<li class="org_site org_vkontakte redir"><i class="socicon socicon-vkontakte c-green"></i>';
	$url = CHtml::link(Orgs::parseUrlShow($model->vkontakte,true), $model->vkontakte, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
	echo CHtml::tag('span', array(), $url).'</li>';
}
if($model->facebook){
	echo '<li class="org_site org_facebook redir"><i class="socicon socicon-facebook c-green"></i>';
	$url = CHtml::link(Orgs::parseUrlShow($model->facebook,true), $model->facebook, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
	echo CHtml::tag('span', array(), $url).'</li>';
}
if($model->twitter){
	echo '<li class="org_site org_twitter redir"><i class="socicon socicon-twitter c-green"></i>';
	$url = CHtml::link(Orgs::parseUrlShow($model->twitter,true), $model->twitter, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
	echo CHtml::tag('span', array(), $url).'</li>';
}
if($model->instagram){
	echo '<li class="org_site org_instagram redir"><i class="socicon socicon-instagram c-green"></i>';
	$url = CHtml::link(Orgs::parseUrlShow($model->instagram,true), $model->instagram, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
	echo CHtml::tag('span', array(), $url).'</li>';
}
if($model->youtube){
	echo '<li class="org_site org_youtube redir"><i class="socicon socicon-youtube c-green"></i>';
	$url = CHtml::link('<span style="text-decoration:underline;">Видео канал на Youtube</span>', $model->youtube, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
	echo CHtml::tag('span', array(), $url).'</li>';
}
}


$worktimes = $model->orgsWorktimes;
if($worktimes){ 
	$addClass = '';
	$day_number = date('w', time()); // 0 - воскресенье
	$arasymb = array('name'=>array('ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'));

	for($i=0;$i<=6;$i++){
		$arasymb['wt'][$i] = '';
	}
	foreach($worktimes as $worktime){
		if($worktime->iswork == true){
			$from = date('H:i', strtotime($worktime->from_work));
			$to = date('H:i',strtotime($worktime->to_work));

			$arasymb['wt'][$worktime->week] = $from.' - ';
			if($from == '00:00' && $worktime->to_work == '23:59:59'){
		    	$arasymb['wt'][$worktime->week] = 'Круглосуточно';
		    } else {
		    	if($worktime->to_work == '23:59:59'){
					$arasymb['wt'][$worktime->week] .= '24:00';
				} else {
					$arasymb['wt'][$worktime->week] .= $to;
				}
			}
			
		}
	}
	foreach($worktimes as $worktime){
		if($worktime->iswork == false){
			$arasymb['wt'][$worktime->week] .=  ' <span class="wtimerest">Перерыв: '.date('H:i', strtotime($worktime->from_work)).' - '.date('H:i',strtotime($worktime->to_work)).'</span>';
		}
	}
	for($i=0;$i<=6;$i++){
		if(empty($arasymb['wt'][$i]))
			$arasymb['wt'][$i] = '&nbsp;&nbsp;Выходной';
	}
echo '<li class="org_worktimer" ><i class="md md-access-time c-green"></i>
<div><strong>Режим работы:</strong></div></li>';
for($i=1;$i<=6;$i++){

	echo '<div class="wtimec ';
	if($day_number == $i) echo  ' dn ';
	if(($arasymb['wt'][$i]) == '&nbsp;&nbsp;Выходной') echo ' weeknd ';
	echo '"';
	if($i==1){
		echo ' style="margin-left:0" ';
	}
	echo '>'.$arasymb['name'][$i].'<div class="wtimet ';
	if($day_number == $i) echo  'dn';
	echo '">'.$arasymb['wt'][$i].'</div></div>';
}

echo '<div class="wtimec ';
if($day_number == 0) echo  ' dn ';
if(($arasymb['wt'][0]) == '&nbsp;&nbsp;Выходной') echo ' weeknd ';
echo '">'.$arasymb['name'][0].'<div class="wtimet ';
if($day_number == 0) echo  'dn';
echo '">'.$arasymb['wt'][0].'</div></div>';
echo '<div class="clearfix"></div>';

} 
?>
</ul>
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>6));
?>
<ul>
 <li class="org_rubrics"><i class="md md-list c-green"></i>
  <div style="margin-bottom:6px;"><strong>Теги</strong></div>
  <span class="org_rubrics_rubrics key">
 <?php

 if($categs){
 	foreach ($categs as $cat){

 		$cat_url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$cat->url));
			
 		// $cat_url = Yii::app()->createAbsoluteUrl($this->city.'/catalog/'.$cat->url);
 		if(empty($cat->logotip))
 		echo CHtml::link($cat->title, $cat_url, array('class'=>'parentCategoryElement'));
 	}
 }
 ?>
 </span>
 </li>
 </ul>
 </div>
 </div>
 </div><!-- .card_body -->
 </div>
 <?php 
$videos = $model->orgsVideo;
if($videos){ ?>
<div style="font-size:20px;font-weight:300;margin-left:20px;margin-bottom:18px;" class="rootCategory">
      Видео <span style="font-weight:normal;"><?php echo $model->title; ?></span>
      </div>
<div class="item-article-list">
<?php

	foreach ($videos as $key => $video) {

		$this->renderPartial('_video',array('data'=>$video));
	} ?>
</div>
<?php
}
$criteria = new CDbCriteria;
 /*$criteria->with = array(
 	'city'=>array(
     	'condition'=>'city.id='.$this->city->id
     	),
 	'images'
 	);*/

 $criteria->condition = 't.part_org='.$model->id;
 $dataProvider = new CActiveDataProvider('Article', array(
        'criteria' => $criteria,
        'sort'=>array(
            'defaultOrder' => 't.created_date DESC',
        ),
        'pagination' => false,
    ));

if(isset($dataProvider) && !empty($dataProvider->totalItemCount)){

?>
<div class="row" style="margin-bottom:10px;">
<div class="col-xs-12">
<div class="org_item">
<div style="font-size:20px;font-weight:300;margin-left:20px;margin-bottom:18px;" class="rootCategory">
      Упоминается в статьях
      </div>
<div class="row">
<div class="col-xs-12">
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.catalog.views.article._article_listview3',
    'id'=>'article_listview',       // must have id corresponding to js above
    'itemsCssClass'=>'item-article-list',
    'ajaxUpdate' => true,
    'template'=>"{items}\n{pager}",
    'pager'=>array(

              'header' => '',
              'maxButtonCount'=>5,
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            ),

    
));
?>

</div>
</div>

</div>
</div>
</div>


<?php 
}
$this->renderPartial('__catalog_tovarov2',array('model'=>$model));
?>
<div class="row" >
<div  class="col-xs-12">
<div style="font-size:20px;font-weight:300;margin-left:20px;margin-bottom:18px;" class="rootCategory">
      Голосование
 </div>
 </div>
 </div>
 <div class="row" >
<div  class="col-sm-6 p-r-8">
<?php  $this->widget('EPoll', array('org_id'=>$model->id,'type'=>PollChoice::TYPE_PLUS)); ?>
</div>
<div  class="col-sm-6 p-l-8">
<?php  $this->widget('EPoll', array('org_id'=>$model->id,'type'=>PollChoice::TYPE_MINUS)); ?>
</div>
</div>
 </div>
 <div class="col-sm-4 p-l-8">
<div class="card m-t-30">
<div class="card-body card-padding" style="padding-left:36px;">
<div><button type="button" class="btn btn-success btn-icon" data-toggle="modal" data-target="#leave_comment"><i class="md md-speaker-notes"></i></button><span class="f-12" style="margin-left:10px;">Оставить отзыв</span></div>
<div style="margin-top:12px;"><button type="button" class="btn btn-success btn-icon" data-toggle="modal" data-target="#add_photo"><i class="md md-photo-camera"></i></button><span class="f-12" style="margin-left:10px;">Добавить фото</span></div>
<div style="margin-top:32px;">Вы владелец? Ошибка в описании?</div>
<div style="margin-top:22px;"><button type="button" class="btn btn-success btn-icon" data-toggle="modal" data-target="#feedback_update"><i class="md md-autorenew"></i></button><span class="f-12" style="margin-left:10px;">Обновить данные</span></div>

</div> 
</div>
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>7));
?>
<?php 
 
if($count_items_near){ ?>
<div style="font-size:20px;font-weight:300;margin-left:36px;margin-bottom:18px;" class="rootCategory">
      Похожие организации рядом
</div>
<div class="card m-t-20">
<div class="card-body">
<?php
if($nearProvider){
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$nearProvider,
    'itemView'=>'_near_orgs',
    'template'=>"{items}",
    'emptyText'=>''
 ));
}
if($nearProviderMicroRayon){
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$nearProviderMicroRayon,
    'itemView'=>'_near_orgs',
    'template'=>"{items}",
    'emptyText'=>''
 ));
}
if($nearProviderRayon){
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$nearProviderRayon,
    'itemView'=>'_near_orgs',
    'template'=>"{items}",
    'emptyText'=>''
 ));
}
?>
</div> 
</div>
<?php } ?>
</div>
 </div><!-- .row -->


<div class="row" >
<div  class="col-xs-12">
<div style="font-size:20px;font-weight:300;margin-left:20px;margin-bottom:18px;" class="rootCategory">
      <div>Отзывы <span style="font-weight:normal;"><?php echo $model->title; ?></span></div>
 	  <div style="margin-top:20px;"><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#leave_comment" ><i class="md md-speaker-notes"></i> Оставить отзыв</button></div>
 	  
 </div>
 	
 	 

</div> 
</div>


<?php  $this->renderPartial('application.modules.comments.views.comment.new_comment_org1', array(
		'model'=>$model,
		'themeUrl'=>$themeUrl,
		'city_utc'=>$this->city->utcdiff
	));  ?>
           

<div style="height: 100px;width:100%"></div>
 <div class="hvalues" style="display:none;">
 	<span class="title"><?php echo $model->title; ?></span>
 	<span class="part_description"><?php echo $part_description; ?></span>
 </div>


 <!-- Modal -->
<div id="feedback_update" class="modal fade" tabindex="-1" role="dialog" style="display:none;">

  <div class="modal-dialog">
    <div class="modal-content">
    <?php 

    $modelFeedbackUpdate = new FormFeedbackUpdate;
    $url1 = Yii::app()->createAbsoluteUrl('/site/feedbackupdate');
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form-feedback-update',
        'action'=>$url1,
        'htmlOptions'=>array( 'role'=>'form'),
        'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'afterValidate' => "js: function(form, data, hasError) {\n"
                    							."      $('#FormFeedbackUpdate_reCaptcha_em_').hide();\n"
                                                ."      if(jQuery.isEmptyObject(data)) {\n"
                                                ."       $('#feedback_update .modal-body.for_form,#feedback_update .modal-footer').hide();\n"
                                                ."       $('#feedback_update .modal-body.success').html(data.message).show();\n"
												."	    } else {\n"
												."        if('flag' in data && data.flag == true){\n"
												."       $('#feedback_update .modal-body.for_form,#feedback_update .modal-footer').hide();\n"
                                                ."       $('#feedback_update .modal-body.success').html(data.message).show();\n"
												."			} \n"
                                                ."		if ('FormFeedbackUpdate_reCaptcha' in data)\n" 
												."		 $('#FormFeedbackUpdate_reCaptcha_em_').show().html(data['FormFeedbackUpdate_reCaptcha']);\n"
                                                ."      }\n"
                                                ."    return false;\n"
                                                ."}\n"
                ),
      )); 
      echo $form->hiddenField($modelFeedbackUpdate,'org',array('value'=>$model->id));
      ?>
       <div class="modal-header" style="border-bottom:0">
			<div style="width:100%; font-size:17px; position: relative;padding-left:10px; ">
			Обновить данные
			<div style="position:absolute; top:0; right:0;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px">×</button></div>
			</div>
            </div>
            <div class="modal-body success" style="display:none; 10px 36px 5px 36px; text-align: center; margin:0 auto;padding-bottom:40px;">
            </div>
      <div class="modal-body for_form" style="padding: 0 36px">
              
        <div class="clearfix"></div>
        <div style="height:24px;width:100%"></div>

        <div class="form-group fg-line green">
        <?php echo $form->labelEx($modelFeedbackUpdate,'content'); ?>
          <?php echo $form->textArea($modelFeedbackUpdate,'content', array('style'=>'word-wrap: break-word; min-height: 90px; width: 100%; resize: none; overflow:hidden;','class'=>'form-control auto-size','placeholder'=>'Введите ваш текст...')); ?>
                <?php echo $form->error($modelFeedbackUpdate,'content'); ?>
        </div>

        <?php if(Yii::app()->user->isGuest) { ?>
        <div class="form-group text-center">
          <?php // echo CHtml::activeLabelEx($modelFeedbackUpdate, 'reCaptcha');

          $this->widget('application.components.ReCaptcha', array(
            'name'=>'reCaptcha',
          //  'size'=>'compact',
            'siteKey'=>Yii::app()->reCaptcha->siteKey,
            'htmlOptions'=>array('id'=>'recaptcha_feedback_update'),
          )); 
          ?>
          <br/>
          <?php 
           echo $form->error($modelFeedbackUpdate,'reCaptcha'); 
          ?>
        </div>
        <?php }  ?>
              
                 
      </div> <!-- / .modal-body -->
      <div class="modal-footer no-border-t p-t-0" style="border-top:0; text-align: center;">
        <button type="submit" class="btn btn-sm btn-success" style="margin-top:10px;margin-bottom:50px;">Отправить</button>
        
      </div>
             <?php $this->endWidget(); ?>
    </div> <!-- / .modal-content -->
  </div> <!-- / .modal-dialog -->
  
</div> <!-- /.modal -->

<!-- Modal -->
<div id="add_photo" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="width:100%;padding:10px 20px;border-bottom:0;">
				<div class="pull-left" style="width:90%">
			    </div>
			    <div class="pull-right" style="width:10%">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px">×</button>
				</div>
			</div>
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
    							."      $('#Orgs_reCaptcha_em_').hide();\n"
                                ."      if(jQuery.isEmptyObject(data)) {\n"
                                ."          $('#add_photo').modal('hide');\n"
								."	    } else {\n"
								."        if('flag' in data && data.flag == true){\n"
								."          $('#add_photo').modal('hide');\n"
								."			location.reload();\n"
								."		  } \n"
                                ."		if ('Orgs_reCaptcha' in data)\n" 
								."		 $('#Orgs_reCaptcha_em_').show().html(data['Orgs_reCaptcha']);\n"
                                ."      }\n"
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
                            <button type="button" style="width:54px;height:54px;margin-bottom:10px;" class="btn btn-success btn-icon btn-lg" ><i class="md md-cloud-upload" style="font-size:28px;"></i></button><br>
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
             <?php  if(Yii::app()->user->isGuest) { 
             	?>
	        <div class="form-group text-center">
	          <?php // echo CHtml::activeLabelEx($modelFeedback, 'reCaptcha');

	          $this->widget('application.components.ReCaptcha', array(
	            'name'=>'reCaptcha',
	          //  'size'=>'compact',
	            'siteKey'=>Yii::app()->reCaptcha->siteKey,
	            'htmlOptions'=>array('id'=>'recaptcha_addphoto'),
	          )); 
	           echo $form->error($model,'reCaptcha'); 
	          ?>
	        </div>
	        <?php  } 
	         ?>    
			</div> <!-- / .modal-body -->
			
			<div class="modal-footer b-t-0 p-t-0 p-r-20 p-b-20 text-right pull-right">
				<!--<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Отмена</button>-->
				<button type="submit" class="btn btn-success btn-sm" id="subButPin">Добавить</button>
			</div>
            
            <div class="clearfix"></div>
            <?php $this->endWidget(); ?> 
		</div> <!-- / .modal-content -->
	</div> <!-- / .modal-dialog -->
</div> <!-- /.modal -->
<!-- / Modal -->


<!-- Modal -->
<div id="modal_get_way" class="modal fade" tabindex="-1" role="dialog" style="display:none;">
  <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="width:100%;padding:30px 40px;border-bottom:0;">
				<div class="pull-left" style="width:90%">
				Как проехать
			    </div>
			    <div class="pull-right" style="width:10%">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px">×</button>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="modal-body" style="padding:10px 40px 100px 40px">
		    <div id="map_get_way">
		    	<div id="sabai-directory-map-direction-search" class="row  sabai-directory-search m-b-10">
		    				
		    				<div class="col-sm-7">
		    				<div class="form-group fg-line green m-b-5 sabai-span6 sabai-directory-direction-location">
	                            <label for="sabai-input">Откуда выезжать?</label>
	                        	<input id="sabai-input" type="text" class="form-control input-sm" value="" placeholder="Введите ваш адрес..." />
	                        </div>
			    			</div>
			    			<div class="col-sm-5">
			    			<div class="na-chem-but m-t-20 sabai-span3 sabai-directory-search-btn pull-right" >
                             	<button class="btn btn-success sabai-directory-search-submit">Найти путь</button>
                             </div>
                             <div class="pull-right na-chem">
			    			 		<p class="sabai-span3 sabai-directory-direction-mode f-500 m-b-0">На чем?</p>
                                    
                                    <select class="selectpicker" data-width="100%">
                                    <option  value="DRIVING">на машине</option>
						            <option  value="TRANSIT">на транспорте</option>
						            <option  value="BICYCLING">на велосипеде</option>
						            <option  value="WALKING">пешком</option>
                                    </select>
                            </div>
						    </div>
						   
			</div>
<div id="sabai-directory-map" class="sabai-googlemaps-map" style="width:100%;height:400px;"></div>
<div id="sabai-directory-map-direction-panel" style="height:200px; overflow:scroll; display:none;"></div>
		    </div>
			</div>
		</div> 
	</div>
</div> <!-- /.modal -->
 <?php

$uploadLink = Yii::app()->createUrl('file/file/upload');
$unlinkLink = Yii::app()->createUrl('file/file/unlink');
$deleteLink = Yii::app()->createUrl('file/file/deletefile');

$scriptAddBr = "
$(document).ready(function(){
	var lisInRow = 0; var counterI; var dataCount;
	
	clearBullets = function(){
	$('.org_rubrics_rubrics.key .parentCategoryElement').addClass('bulleton');	
	var position, prevdata = [];
	$.each($('.org_rubrics_rubrics.key'), function(key, value){
		$(value).find('.parentCategoryElement').each(function(i, item){
			position = $(item).position().top;
			prevdata[i] = position;
			if(prevdata[i-1] && prevdata[i-1] != position){
				$(this).prev().removeClass('bulleton');
			}
		});
	});
	$('.key .parentCategoryElement:last-child').removeClass('bulleton');
	}
	clearBullets();
	$(window).on('debouncedresize', function(){
         clearBullets();
    });

	$('.wtimec').on('click', function(){
		$('.wtimec .wtimet').hide();
		$('.wtimec').removeClass('dn');
		$(this).addClass('dn');
		$(this).find('.wtimet').show();
	});
	
	var gallery = $('.gallery-nav.lightbox').lightGallery();

	// $('ul.i-list li').hide();
    imagesILoaded = function() {
       // function to invoke for loaded images
       // decrement the counter
       counterI--; 
     
       if( counterI === 0 ) {
           // counter is 0 which means the last
           // one loaded, so do something else

		    $('ul.i-list li').each(function() {
		        if($(this).prev().length > 0) {
		            if($(this).position().top != $(this).prev().position().top) return false;
		            lisInRow++;
		        }
		        else {
		            lisInRow++;   
		        }
		    });
			

       }
    }
    
	

";

if(isset($truemap) && !empty($truemap)){
$link = Yii::app()->createAbsoluteUrl('site/getinsta');	
$scriptAddBr .= "

setTimeout(function(){
        loadinsta('".$model->lat."','".$model->lng."','".$model->title."','".$model->id."');
    },150); 
	loadinsta = function(lat,lng,title,id){
        hpv = '".Yii::app()->request->csrfToken."',
        hpt = '".Yii::app()->request->csrfTokenName."';
            var datav = {'lat':lat,'lng':lng,'title':title,'id':id};
            datav[hpt] = hpv;
            $.ajax({
                  type:'POST',
                  dataType: 'json',
                  data: datav,
                  url:'".$link."',
                  success:function(data) {
                    if(!$.isEmptyObject(data)){
                    	var cnt_fotos = parseInt($('#cnt_fotos').text());
                    	if(!$.isEmptyObject(data.imagei)){
                    		if(cnt_fotos == 0){
                    			$('#item-photo-view').append('<div class=\"item-gallery-bg\" style=\"background-image:url('+ data.imagei[0]['standard'] +'); \"></div>'+
                    				'<img class=\"undergal\" alt=\"'+ title +'\" src=\"'+ data.imagei[0]['standard'] +'\">');	
                    		}
                    		cnt_fotos = cnt_fotos + data.imagei.length;
                    	}
                    	if(!$.isEmptyObject(data.imagef)){
                    		if(cnt_fotos == 0){
                    			$('#item-photo-view').append('<div class=\"item-gallery-bg\" style=\"background-image:url('+ data.imagef[0]['standard'] +'); \"></div>'+
                    				'<img class=\"undergal\" alt=\"'+ title +'\" src=\"'+ data.imagef[0]['standard'] +'\">');
									
                    		}
                    		cnt_fotos = cnt_fotos + data.imagef.length;
                    	}
                    	if(cnt_fotos > 0){
                    		// $('#header-map').css({'width':'61%'});
                    		// $('#item-photo-view').show();
                    		$('.gallery-photos-size').show();
                    		$('.empty-add').hide();
                    	} else {
                    		// $('#header-map').css({'width':'100%'});

                    	}
                    	$('#cnt_fotos').text(cnt_fotos);
                    	if(!$.isEmptyObject(data.html)){
                    		
                    		$( '.gallery-nav.lightbox' ).append(data.html);
                    		gallery.destroy();
                    		gallery = $('.gallery-nav.lightbox').lightGallery();
                    		if(!$('#item-photo-view i.zoom.lazy-load-src').length)
                    			$('#item-photo-view').append('<i onclick=\"zoomclick();\" class=\"zoom lazy-load-src\"></i>');
							                    		
							
						} else {

						}
                    	
                    }
                  },
                  error: function (xhr, status) {  
  
                  } 
              });
    }
    ";
} else {
	$cs = Yii::app()->clientScript;

	$assetsPackage=array(
                    'baseUrl'=>$themeUrl,
                    'js'=>array(
                        '/js/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
                        '/js/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js',
                    ),
                    'css'=>array(
                        '/css/style-revolution-slider.css',
                        '/js/plugins/slider-revolution-slider/rs-plugin/css/settings.css',
                    ),
                    'depends'=>array('jquery'),
                );

  $cs->addPackage('slider', $assetsPackage);
  $cs->registerPackage('slider');
  if(empty($fotos)){
  	$startwidth = 1200;
  	$startheight = 300;
  } else {
  	$startwidth = 750;
  	$startheight = 300;
  }
$scriptAddBr .= "

  $.when(
  jQuery('#fullwidthbanner').revolution({ 
                      delay:200,
                      startwidth:".$startwidth.",
                      startheight:".$startheight.",
                      hideThumbs:10,
                    })
  ).then(function(){
    $('.tp-bgimg.defaultimg').addClass('zoombo');
  });

";
}
$scriptAddBr .="
	$('.article_caption_title').trunk8({lines:2, tooltip: false});
	 $(window).on('debouncedresize', function(){
	 	$('.article_caption_title').trunk8({lines:2, tooltip: false});
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
                    $('#pinboard-form').append('<input type=\"hidden\" name=\"Orgs[tmpFiles][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
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

                $scriptAddBr .='
                var sessFile'.$cnt.' = {
                    "name": "'.$key.'",
                    "size": "'.filesize(Yii::getPathOfAlias('webroot').$folder.$value).'",
                    "ext": "'.pathinfo(Yii::getPathOfAlias('webroot').$folder.$value, PATHINFO_EXTENSION).'"
                };
                 var linkThumb = "'.$folderLink.$value.'";
                dropzone.options.addedfile.call(dropzone, sessFile'.$cnt.'); 
                dropzone.options.thumbnail.call(dropzone, sessFile'.$cnt.', linkThumb);

                $("#pinboard-form").append("<input type=\'hidden\' name=\'Orgs[tmpFiles][]\' value=\'" + sessFile'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

        	';
        	}
    	}
    }
}
$scriptAddBr .="
$('#dropzone_opener').on('click', function(){
    if($('#dropzone').is(':visible')){
        $('#dropzone').hide();
        $('input.dr-zone-inputs').attr('disabled', true);
    } else {
        $('#dropzone').show();
        $('input.dr-zone-inputs').attr('disabled', false);
    }
});

zoomclick = function(){
    gallery.destroy();
    gallery = $('.gallery-nav.lightbox').lightGallery();
	$('.gallery-nav.lightbox img:eq(0)').trigger( 'click' );
}
 // $('.selecstpicker').selectpicker({width:'100px'});

 $('#modal_get_way').on('shown.bs.modal', function (e) {
 	var optVal = $(e.relatedTarget).data('type-id');
 	$('.selectpicker').selectpicker();
 	$('.selectpicker').selectpicker('val', optVal);
	LoadGoogle();
 });
if($('video')[0]) {
      //  $('video').mediaelementplayer();
    }
});";


if(!empty($model->lat) && !empty($model->lng)){

$scriptAddBr .= "


function LoadGoogle()
    {
        if(typeof google != 'undefined' && google && google.load)
        {
                google.load('maps', '3', {other_params:'sensor=false&libraries=places&language=ru', callback:function() {

			       SABAI.GoogleMaps.directionMap(
			                '#sabai-directory-map',
			                ".$model->lat.",
			                ".$model->lng.",
			                '#sabai-directory-map-direction-search .sabai-directory-search-btn button',
			                '#sabai-directory-map-direction-search .sabai-directory-direction-location input',
			                '#sabai-directory-map-direction-search .sabai-directory-direction-mode select',
			                '',
			                '#sabai-directory-map-direction-panel',
			                {'marker_clusters':'1','marker_cluster_imgurl':'','scrollwheel':false,'icon':'/img/address_sign38x54_green.png','zoom':15, 'styles':null});

							SABAI.GoogleMaps.autocompletes('.sabai-directory-direction-location input', {componentRestrictions: {}});
			
			}});
        }
        else
        {
            // Retry later...
            setTimeout(LoadGoogle, 30);
        }
    }

";

}

Yii::app()->clientScript->registerScript("fwban", $scriptAddBr, CClientScript::POS_END);
?>