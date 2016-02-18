<?php
	//$this->breadcrumbs=array(
	//    Yii::t('site','Results for').': '. CHtml::encode($term),
	//);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/view/search.js', CClientScript::POS_END);
	?>

<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-12 ">
<div class="catalog_list" style=" margin-bottom:60px;">
<?php


echo CHtml::tag('h1', array('class'=>'org_title'), '<span>'.CHtml::encode($term).'</span> в '.$this->city->mestpad);
if($count_items){
$doptext = '<p>Мы нашли для вас '.$count_items.' '.Yii::t('site','foundorg|foundorgs', $count_items).'.'.
'<br>'.CHtml::encode($term).' - адреса на карте, отзывы пользователей<br>с рейтингами и фотографиями.</p>';
} else {
$doptext = '<p>Организаций не найдено</p>';
}
echo CHtml::tag('div', array('class'=>'cat_found_text'), $doptext);
?>

	
</div>
</div>
</div>
<?php 
if ($resultsPr && $resultsPr->totalItemCount) {
	?>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-12  col-xs-12 ">
<div class="catalog_list" style="margin-bottom:100px;">
<div id="results-container" class="products_list">
      <?php  
          
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$resultsPr,
            'viewData'=>array('city'=>$this->city),
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
            'itemView'=>'_organizations',
           /* 'sortableAttributes'=>array(
             'name', 'price'
             ),*/
            'pager'=>array(
              'maxButtonCount'=>7,
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


?>
</div>
</div>
</div>
<div class="col-lg-5 col-md-5 col-sm-12  col-xs-12 " id="list_of_orgs_map">

<!--<div id="map_canvas"></div>-->
<?php 
$mapparams = array();
if($resultsPr->getData()){
  foreach($resultsPr->getData() as $datas){
  	$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$datas->city->url, 'id'=>$datas->id,  'itemurl'=>$datas->url));

    if($datas->lat && $datas->lng && $datas->street){
      $address = '';
     $address .= $datas->street;
  if($datas->dom) { $address .= ', '.$datas->dom; }
    $mapparams[] = array(
      'lat'=>floatval($datas->lat),
      'lon'=>floatval($datas->lng),
      'properties'=>array(
                    
                    'balloonContentHeader'=>'<a href="'.$url.'">'.$datas->title.'</a>',
                    'balloonContentBody'=>$address,
                    'balloonContentFooter'=>$datas->phone,
                ),
      'options'=>array(
         // 'preset'=>'twirl#violetIcon',
          'iconLayout'=> 'default#image',
          'iconImageHref'=>'/img/address_sign25x39.png',
          // Размеры метки.
          'iconImageSize'=>array(25,39),
          // Смещение левого верхнего угла иконки относительно
          // её "ножки" (точки привязки).
          'iconImageOffset'=> array(-10,-40),
        ),
      );
    }
  }

if($this->city->latitude && $this->city->longitude){
 $this->widget('ext.yandexmap.YandexMap',array(
        'id'=>'map_canvas',
        'protocol'=>'//',
        'load'=>'package.standard,package.clusters',
        'width'=>'80%',
        'height'=>610,
        'center'=>array(floatval($this->city->latitude), floatval($this->city->longitude)),
        'controls' => array(
            'zoomControl' => false,
            'typeSelector' => false,
            'mapTools' => false,
            'smallZoomControl' => false,
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

</div>
</div>
<?php
} else {

}
/*	$this->widget('zii.widgets.CListView', array(
		'id'=>'search_organization_list',
	    'dataProvider' => $resultsPr,
	    'viewData'=>array('query'=>$query,'city'=>$this->city),
	    'itemView' => '_obj_list',
	    'itemsTagName'=>'ul',
	    'itemsCssClass'=>'search-classic panel',
	    //'template' => '{sorter}{items}{pager}',
	    'template' => "{items}\n{pager}",
	    'pager'=>array(
	            'header' => '',
	            'firstPageLabel'=>'first',
	            'lastPageLabel'=>'last',
	            'nextPageLabel' => '»',
	            'prevPageLabel' => '«',
	            'selectedPageCssClass' => 'active',
	            'hiddenPageCssClass' => 'disabled',
	            'htmlOptions' => array('class' => 'pagination')
	    ),
	    //'sorterHeader' => '',
	    'emptyText' => '&nbsp;',
	));
	}*/
	
?>

