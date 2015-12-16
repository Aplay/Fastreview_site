<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-12 ">
<div class="catalog_list" >
<?php     
    // $ancestors = $this->model->excludeRoot()->ancestors()->find();
if($ancestors) {
  foreach($ancestors as $c){
    $setcat = $c->id;
    $rootcat = $c->url;
    break;
  }
} else {
  $setcat = $model->id; 
  $rootcat = $model->url;
}
$count_items = $provider->totalItemCount;
echo CHtml::tag('h1', array('class'=>'org_title'), $model->title. ' '.$dtitle);
if($count_items){
$doptext = 'Мы нашли для вас '.$count_items.' '.Yii::t('site','foundorg|foundorgs',$count_items).' в городе '.$this->city->mestpad.'.'.
'<br>'.$model->title.' - адреса на карте, отзывы пользователей<br>с рейтингами и фотографиями.';
} else {
$doptext = 'Организаций не найдено.';
}
echo CHtml::tag('div', array('class'=>'cat_found_text'), $doptext);


if ( isset( $this->breadcrumbs ) ){ 
$this->widget('zii.widgets.CBreadcrumbs', array(
  'links'=>$this->breadcrumbs,
  'tagName'   =>'ul', // container tag
  'homeLink'=>'',
  'separator'=>'<li class="separator"></li>',
  'activeLinkTemplate'  =>'<li class="brcr"><a href="{url}">{label}</a></li>', // active link template
  'inactiveLinkTemplate'  =>'<li class="brcr">{label}</li>', // in-active link template
  'htmlOptions'=>array('class'=>'breadcrumb')
  
  ));

}
$this->widget('ext.widgets.AdsWidget',array('block_id'=>1));

echo CHtml::tag('div', array('class'=>'cat_descr'), $model->description);
?>
<div style="height:60px;"></div>
</div>
</div>
</div>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-12  col-xs-12 ">
<div class="catalog_list" style="margin-bottom:100px;">
<div id="results-container" class="products_list">
      <?php  
          
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'viewData'=>array('city'=>$this->city),
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
            'itemView'=>$itemView,
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

// $this->widget('ext.widgets.AdsWidget',array('block_id'=>2));
?>

</div>

<?php 
if($setcat){
        $category=Category::model()->findByPk($setcat);
        $descendants = Category::getRubs($this->city->id, $category,null,null,1,$model->id);

$districtlist = $this->city->getDistrictLinks($model);

if(!isset($shop) && !empty($districtlist)){ ?>
<div class="catalog_list" style="margin-top:40px;">
      <div class="rootCategoryElement">
      	<div class="key">
      	<?php echo $districtlist; ?>
      	</div>
      </div>
</div>
<?php
}
}
  if(!isset($shop) &&  $descendants){ ?>
<div id="tag_block" class="catalog_list">
      <div class="rootCategory">
      Схожие рубрики
      </div>
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
    <?php } ?>
</div>
</div>
<div class="col-lg-5 col-md-5 col-sm-12  col-xs-12 " id="list_of_orgs_map">

<!--<div id="map_canvas"></div>-->
<?php 
$mapparams = array();
if($provider->getData()){
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
})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>




