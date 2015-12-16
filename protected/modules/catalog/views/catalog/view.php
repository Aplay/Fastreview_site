<div class="row" >
<div class="col-lg-11 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 ">
<div class="catalog_list" style="padding-left:26px;">
<?php 
   // $ancestors = $this->model->excludeRoot()->ancestors()->find();
$setcat = $rootcat = null;
if(isset($ancestors) && !empty($ancestors)){
  foreach($ancestors as $c){
    $setcat = $c->id;
    $rootcat = $c->url;
    break;
  }
} else {
  if(isset($model)){
	  $setcat = $model->id; 
	  $rootcat = $model->url;
  }
}

if(isset($count_items) && !empty($count_items)){
$doptext = $count_items.' '.Yii::t('site','org|orgs',$count_items);
} else {
$doptext = 'не найдено';
}
if(isset($model)){
	echo CHtml::tag('h2', array('class'=>'org_title'), $model->title. ' '.$dtitle .' <span class="cat_found_text">'.$doptext.'</span>');
} else { 
	echo CHtml::tag('h2', array('class'=>'org_title'),  $dtitle .' <span class="cat_found_text">'.$doptext.'</span>');
}
if(isset($count_items) && !$count_items){ ?>
<div class="summary" style="display: block;"><div class="summary_show">Показано</div><div><span class="summary_end">0</span> из 0</div></div>
<?php }

echo CHtml::tag('div',array('class'=>'under_org_title','style'=>'margin-bottom:20px;'),'Адреса на карте, отзывы пользователей<br> с рейтингами и фотографиями');


if ( isset( $this->breadcrumbs ) ){ 
$this->widget('zii.widgets.CBreadcrumbs', array(
  'links'=>$this->breadcrumbs,
  'tagName'   =>'ul', // container tag
  'homeLink'=>'',
  'separator'=>'<li class="separator"> &#8594; </li>',
  'activeLinkTemplate'  =>'<li class="brcr"><a href="{url}">{label}</a></li>', // active link template
  'inactiveLinkTemplate'  =>'<li class="brcr">{label}</li>', // in-active link template
  'htmlOptions'=>array('class'=>'breadcrumb')
  
  ));

}
?>
</div> 
</div> 
</div>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-7 col-sm-12  col-xs-12 ">
<div class="catalog_list" >
<?php     

$this->widget('ext.widgets.AdsWidget',array('block_id'=>1));


?>
</div>
</div>
</div>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-7 col-sm-12  col-xs-12 ">
<div class="catalog_list" style="margin-bottom:100px;">
<div id="results-container" class="products_list">
      <?php  
           if($provider){
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'viewData'=>array('city'=>$this->city),
            'ajaxUpdate'=>false,
            'template'=>"{summary}\n{items}\n{pager}",
            'summaryText'=>'<div class="summary_show">Показано</div><div><span class="summary_end">{end}</span> из {count}</div>',
            'itemView'=>$itemView,
            'emptyText'=>'',
            'pager'=>array(
              'maxButtonCount'=>5,
              'header' => '',
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            ),
           ));
       }

// $this->widget('ext.widgets.AdsWidget',array('block_id'=>2));
?>

</div>

<?php 
if(isset($shop) && $shop == true && isset($showingCatalogues)){ ?>
<div class="catalog_list">
 <?php  

$this->widget('zii.widgets.CListView', array(
			    'dataProvider'=>$showingCatalogues,
			    'itemView'=>'application.modules.catalog.views.catalogue._journal_listview1',
			    'viewData'=>array('city'=>$this->city,'categories'=>array($model)),
			    'id'=>'journal_listview',       // must have id corresponding to js above
			    'itemsCssClass'=>'item-article-list',
			    'ajaxUpdate' => false,
			    'template'=>"{items}",
			));
    ?>  
</div>
<?php
}

if($setcat){
        $category=Category::model()->findByPk($setcat);
        $descendants = Category::getRubs($this->city->id, $category,null,null,1,$model->id);

$districtlist = $this->city->getDistrictLinks($model);

if(!isset($shop) && !empty($districtlist)){ ?>
<div class="catalog_list" style="margin-top:60px;">
<div style="font-size:20px;font-weight:300;margin-left:26px;margin-bottom:18px;">Местонахождение в <?php echo $this->city->mestpad; ?></div>
      <div class="card">
      <div class="card-body card-padding">
      <div class="rootCategoryElement">
      	<div class="key">
      	<?php echo $districtlist; ?>
      	</div>
      </div>
      </div>
      </div>
</div>
<?php
}
}
 if(!isset($shop) &&  isset($descendants) && count($descendants)>0){ ?>
<div id="tag_block" class="catalog_list">
      <div style="font-size:20px;font-weight:300;margin-left:26px;margin-bottom:18px;" class="rootCategory">
      Схожие рубрики
      </div>
      <div class="card">
      <div class="card-body card-padding">
      <div class="rootCategoryElement">
      <?php
       // shuffle($descendants);
        $cntd = count($descendants);
        $rnd = $cntd>8?8:$cntd;
        $desc = array_rand($descendants,$rnd); 
        echo CHtml::openTag('div',array('class'=>'key'));
        foreach($descendants as $k=>$rub){
          if(is_array($desc)){
            if(!in_array($k,$desc))
              continue;
          } 
          $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,  'url'=>$rub['url']));
        //  echo '<a href="'.$url.'">'.$rub['title'].'</a>';
          echo CHtml::link($rub['title'], $url, array('class'=>'parentCategoryElement'));
        }
        echo CHtml::closeTag('div');
         ?>
      </div>

      <div class="clearfix"></div>
      </div>
      </div>
    </div>  
    <?php } ?>

   <?php
if(isset($model) && !empty($model->description)){

 echo CHtml::tag('div', array('class'=>'card','style'=>'margin-top:40px;'), CHtml::tag('div',array('class'=>'card-body card-padding'),$model->description));
}	
?>
 
</div>

</div>
<div class="col-lg-5 col-md-5 col-sm-12  col-xs-12 " id="list_of_orgs_map">

<!--<div id="map_canvas"></div>-->
<?php 
$mapparams = array();
if($provider && $provider->getData()){
  foreach($provider->getData() as $datas){

    $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$datas->city->url, 'id'=>$datas->id, 'itemurl'=>$datas->url));

    if($datas->lat && $datas->lng && $datas->street){
      $address = '';
     $address .= $datas->street;
  if($datas->dom) { $address .= ', '.$datas->dom; }
    $mapparams[] = array(
      'lat'=>floatval($datas->lat),
      'lon'=>floatval($datas->lng),
      'properties'=>array(
                    
                   // 'balloonContentHeader'=>'<a href="'.$url.'">'.$datas->title.'</a>',
                   // 'balloonContentBody'=>$address,
                   // 'balloonContentFooter'=>$datas->phone,
                   // 'hintContent'=> '<a href="'.$url.'"><div class="ya_hint_box"><div class="ya_hint_list"><div>'.$datas->title.'</div><div>'.$address.'</div></div></div></a>',
               	    'content'=> '<a href="'.$url.'"><div class="ya_hint_box"><div class="ya_hint_list"><div class="ya_hint_title">'.$datas->title.'</div><div>'.$address.'</div></div></div></a>',

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
  }

if($this->city->latitude && $this->city->longitude){
 $this->widget('ext.yandexmap.YandexMap',array(
        'id'=>'map_canvas',
        'protocol'=>'//',
        'load'=>'package.standard,package.clusters',
        'clusterIcon'=>'/img/clustergreen.png',
        'width'=>'80%',
        'height'=>555,
        'center'=>array(floatval($this->city->latitude), floatval($this->city->longitude)),
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
}
}

?>
<div class="clearfix"></div>
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>3));
?>
</div>
</div>
<?php
$script = "
$(document).ready(function(){
  clearBullets = function(){
  $('.rootCategoryElement .key').find('br').remove();
  $('.rootCategoryElement .key .parentCategoryElement').addClass('bulleton');  
  var position, prevdata = [];
  $.each($('.rootCategoryElement .key'), function(key, value){
    $(value).find('.parentCategoryElement').each(function(i, item){
      if(i==3){
        $(this).removeClass('bulleton').after('<br>');
      }
      position = $(item).position().top;
      prevdata[i] = position;

      if((typeof prevdata[i-1] != 'undefined') && prevdata[i-1] != position){
        $(this).prev().removeClass('bulleton');
      }
    });
  });
  $('.rootCategoryElement .key .parentCategoryElement:last-child').removeClass('bulleton');
  }
  clearBullets();
  $(window).on('debouncedresize', function(){
         clearBullets();
    });

	if($('.list-view .summary').length){
		$('.list-view .summary').insertAfter($('h2.org_title')).show();
	}
	

	if($('#ads3').length){
		var map_container = $('#ads3');
		var map_offset_top = map_container.offset().top;
		$(window).scroll(function(){
			if($(window).scrollTop()-map_offset_top>=0){
				map_container.css({'position':'fixed','top':0});
			} else {		
				map_container.css({'position':'relative','top':0});
		    }
		});
	}

	
})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>




