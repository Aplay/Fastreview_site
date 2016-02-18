<style>
body.mainy .main{
  margin-top: 0px;
}
</style>
<?php  

$themeUrl = '/themes/bootstrap_311/';

$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('city'=>$this->city->url,'id'=>$model->id,'itemurl'=>$model->url,'dash'=>'-'));

//Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:site_name"));
Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($this->pageDescription, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag('article', null, null, array('property' => "og:type"));
Yii::app()->clientScript->registerMetaTag($url, null, null, array('property' => "og:url"));


$fotos = array();

?>
<div class="row">

<div class="header-map hidden-xs header-article" id="header-map" >

	<div class="main-map-view">
	<?php
			?>
<div class="page-slider" id="main-slider">
      <div id="fullwidthbanner-container" class="fullwidthbanner-container  revolution-slider">
        <div  id="fullwidthbanner" class="fullwidthabnner">
          <ul id="revolutionul">
            <!-- THE NEW SLIDE -->
            <li data-transition="zoomin" data-slotamount="7" data-masterspeed="100">
            <!--<li  data-transition="zoomin" data-slotamount="7" data-masterspeed="1000">-->
              <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
              <?php 
              $image = Yii::app()->createAbsoluteUrl('/favicon-160x160.png');

              if(!empty($model->logotip)){
              	$src = $model->getUrl('1920x562xC', 'adaptiveResizeQuadrant');
              	$image = Yii::app()->createAbsoluteUrl($model->getUrl('200x200xC','adaptiveResizeQuadrant'));
              } elseif(!$this->city->filename){
                $src = '/img/russia_bg.jpg';
              } else {
                $src = '/uploads/city/'.$this->city->id.'/'.$this->city->filename;
              }

               ?>
              <img src="<?php echo $src; ?>" alt="img1" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
<?php 
if(empty($fotos)){
	$slide_class = 'slide_title_white';
} else {
	$slide_class = 'slide_title_white_sm';
}
?>
             <div class="proxima caption lft <?php echo $slide_class; ?> slide_item_left"
                data-x="center"
                data-y="center"
                data-speed="400"
                data-start="1500"
                data-easing="easeOutExpo">
              <?php echo $model->title; ?>
              </div>
   
            </li>     
          </ul>
            </div>
            <div class="underfullbanner"></div>
        </div>
    </div>

			<?php
		
?>
	</div>

</div>
</div>
<div class="row top-to-article">
<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
	<?php echo $model->description; ?>
</div>
</div>
<div class="row">
<div  class="text-center col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
	<h4 style="margin-top:100px;">Расскажите друзьям</h4>
	<div id="addshare">		
	<noindex>
	<span id="ya_share1"></span>
	</noindex>
	</div>
</div>
</div>
<div class="row">
<div  class="text-center item-article-list col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
	<h4 style="margin-top:100px;">Похожие статьи</h4>
	<?php
$criteria = new CDbCriteria;
 $criteria->with = array(
 	'city'=>array(
     	'condition'=>'city.id='.$this->city->id
     	),
 	'images',
 	'organization'=>array(
 		'condition'=>'organization.article != '.$model->id
 		)
 	);
 $criteria->limit = 3;
 $dataProvider = new CActiveDataProvider(Article::model()->active(), array(
        'criteria' => $criteria,
        'sort'=>array(
            'defaultOrder' => 't.created_date DESC',
        ),
        'pagination' => false,
    ));

if(isset($dataProvider) && !empty($dataProvider->totalItemCount)){
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.catalog.views.article._article_listview',
    'id'=>'article_listview',       // must have id corresponding to js above
    'itemsCssClass'=>'article_listview',
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
}
?>
</div>
</div>
 <?php
 $cs = Yii::app()->clientScript;
	$themeUrl = '/themes/bootstrap_311/';
	$assetsPackage=array(
                    'baseUrl'=>$themeUrl,
                    'js'=>array(
                       // 'js/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.plugins.min.js',
                        'js/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
                        'js/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js',
                        // 'js/metro/revo-slider-init.js',
                    ),
                    'css'=>array(
                        'css/metro/style-revolution-slider.css',
                        'js/plugins/slider-revolution-slider/rs-plugin/css/settings.css',
                    ),
                    'depends'=>array('jquery'),
                );
$cs->addPackage('slider', $assetsPackage);
  $cs->registerPackage('slider');
 // if(empty($fotos)){
  	$startwidth = 1920;
  	$startheight = 562;
 // } else {
 // 	$startwidth = 620;
 // 	$startheight = 440;
 // }
$scriptAdd = "
$(document).ready(function(){
	// создаем блок
        $.getScript('http://yastatic.net/share/share.js', function () {
        new Ya.share({
        element: 'ya_share1',

        theme: 'counter',
            elementStyle: {
              'text': '',
                'type': 'button',
                'border': false,
                'quickServices': ['facebook', 'vkontakte', 'twitter', 'odnoklassniki', 'gplus']
            },

            link: '".$url."',
            title: '".addslashes($this->pageTitle)."',
            description: '".addslashes($this->pageDescription)."',
            image: '".$image."'
                 
});
    })
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
   
})";
Yii::app()->clientScript->registerScript("article", $scriptAdd, CClientScript::POS_END);
?>