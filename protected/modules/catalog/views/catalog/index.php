<?php 
$themeUrl = Yii::app()->theme->baseUrl;

Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');

Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/salvattore/salvattore.min.js', CClientScript::POS_END);
 Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/isotope/jquery.isotope.min.js', CClientScript::POS_END);
 
$blocks = array();

if($dataProvider->totalItemCount > 0){
	foreach ($dataProvider->data as $k=>$value) {
		$blocks[$k]['time'] = $value->created_date;
		$blocks[$k]['html'] = $this->renderPartial('application.modules.catalog.views.article._article_listview2',array('data'=>$value),true);
	}
}

if(!empty($comments)) {
	foreach ($comments as $comment) {

		$k = count($blocks);
		$blocks[$k]['time'] = $comment->created;
		$blocks[$k]['html'] = $this->renderPartial('application.modules.comments.views.comment._itemmain',array('model'=>$comment, 'org'=>$comment->obj),true); 

	}
}
if(!empty($lastImages)) {
	
	foreach ($lastImages as $im) {
		
		$k = count($blocks);
		$blocks[$k]['time'] = $im['date'];
		$blocks[$k]['html'] = $this->renderPartial('application.modules.catalog.views.catalog._lastimages',array('model'=>$im),true); 
		
	}
}


/*
if($dataProvider->totalItemCount > 0){
//$this->renderPartial('application.modules.catalog.views.article.index',array('dataProvider'=>$dataProvider));
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.catalog.views.article._article_listview2',
    'id'=>'article_listview',       // must have id corresponding to js above
    'itemsCssClass'=>'item-article-list one-column',
    'template'=>"{items}"
));

} 
*/

if(!empty($blocks)){ ?>
<div class="row">
<div id="main_list_grid_m1"  data-columns>
<?php

	usort($blocks, MHelper::get('Array')->sortFunction('time'));
	$blocks = array_reverse($blocks);
	foreach ($blocks as $k=>$block) { 

		echo '<div class="item item-article-list one-column col-lg-4 col-md-6 col-sm-6 col-xs-12">';
		echo $block['html'];
		echo '</div>';
		
	 } ?>
</div> 
</div>
<?php } ?>

<div style="font-size:20px;font-weight:300;margin-left:26px;margin-bottom:18px;" class="rootCategory">
Каталог организаций
</div>
<div class="card">
<div class="card-body card-padding">
<div  id="main_page_cat" >
<div class="rootCategoryElement-list row-list-3">
<?php
if(!empty($cats)){
	foreach ($cats as $k1 => $v1) {
		echo CHtml::openTag('div',array('class'=>'key'));
		$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$v1['url']));	
		if(isset($v1['items'])){
			echo CHtml::link($v1['title'], $url, array('class'=>'rootCategory theme-color'));
			foreach ($v1['items'] as $k2 => $v2) {
				$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$v2['url']));	
				if(isset($v2['items'])){
					echo CHtml::link($v2['title'], $url, array('class'=>'parentCategoryElement'));
					foreach ($v2['items'] as $k3 => $v3) {
						$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$v3['url']));	
						echo CHtml::link($v3['title'], $url, array('class'=>'subparentCategoryElement'));
					}
				} else {
					echo CHtml::link($v2['title'], $url, array('class'=>'parentCategoryElement'));
				}
			}
		} else {
			echo CHtml::link($v1['title'], $url, array('class'=>'parentCategoryElement'));
		}
		echo CHtml::closeTag('div');

	}
}
if(!empty($roots)){
	
	foreach($roots as $root){
		
		/*$descendants = $root->descendants()
		->with(array(
            'categorization'=>array(
            	'condition'=>'categorization.category=t.id',
            	'together'=>true
            	),
            'organizations'=>array(
            	'condition'=>'organizations.city_id='.$this->city->id.' and organizations.status_org='.Orgs::STATUS_ACTIVE,
            	'together'=>true
            	),
            )
           )
		->findAll(array('order'=>'t.title')); */

		$descendantsrubs = Category::getRubs($this->city->id, $root);
		if(!empty($descendantsrubs)){
			echo CHtml::openTag('div',array('class'=>'key'));
			// $url = Yii::app()->request->addUrlParamCat(array($root->url), 'page');
			$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$root->url));
			echo CHtml::link($root->title, $url, array('class'=>'rootCategory theme-color'));

			foreach($descendantsrubs as $descendant){
				$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$descendant['url']));
				$title = $descendant['title'];
				echo CHtml::link($title, $url, array('class'=>'parentCategoryElement'));
			}
			echo CHtml::closeTag('div');
		} else {
			$descendants = Category::getRubs($this->city->id, null, '', $root->id);
			if($descendants){
				echo CHtml::openTag('div',array('class'=>'key'));
				$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$root->url));
			
				$title = $root->title;
				echo CHtml::link($title, $url, array('class'=>'rootCategory theme-color'));

				echo CHtml::closeTag('div');
			}
		}
		
	}
}
?>
 </div>
 </div>
 </div>
 </div>
<?php
$script = "
$(document).ready(function(){
	$('.article_caption_title').trunk8({lines:2, tooltip: false});
	 $(window).on('debouncedresize', function(){
	 	$('.article_caption_title').trunk8({lines:2, tooltip: false});
	 });

	clearBullets = function(){
	$('.rootCategoryElement-list .key .parentCategoryElement').addClass('bulleton');
	$('.rootCategoryElement-list .key .subparentCategoryElement').addClass('bulleton');	
	var position, prevdata = [];
	$.each($('.rootCategoryElement-list .key'), function(key, value){
		$(value).find('.parentCategoryElement').each(function(i, item){
			position = $(item).position().top;
			prevdata[i] = position;
			if(prevdata[i-1] && prevdata[i-1] != position){
				$(this).prev().removeClass('bulleton');
			}
		});
		$(value).find('.subparentCategoryElement').each(function(i, item){
			position = $(item).position().top;
			prevdata[i] = position;
			if(prevdata[i-1] && prevdata[i-1] != position){
				$(this).prev().removeClass('bulleton');
			}
		});
	});
	$('.rootCategoryElement-list .key .parentCategoryElement:last-child').removeClass('bulleton');
	$('.rootCategoryElement-list .key .subparentCategoryElement:last-child').removeClass('bulleton');
	}
	clearBullets();
	newImageHeight = function(){
		if(!$('.lightbox .lightbox-item').length)
			return;
		var w = $('.lightbox .lightbox-item:eq(0)').width();
		// console.log(w)
		$('.lightbox .lightbox-item img').css('height', w+'px');
	}
	
	$(window).on('debouncedresize', function(){
		// salvattore.recreateColumns(document.querySelector('#main_list_grid'));
         clearBullets();
		 newImageHeight();
		 $('#main_list_grid_m1').isotope('reloadItems');
    });
	 newImageHeight();

	//  $.getScript('//cdn.jsdelivr.net/isotope/1.5.25/jquery.isotope.min.js',function(){

	  /* activate jquery isotope */
	  $('#main_list_grid_m1').imagesLoaded( function(){
	    $('#main_list_grid_m1').isotope({
	      itemSelector : '.item'
	    });
	  });
	  
	// });

})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>