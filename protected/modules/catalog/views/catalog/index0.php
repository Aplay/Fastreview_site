<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-12 ">
<div  id="main_page_cat" style="margin-top:20px; margin-bottom: 160px">
<div class="rootCategoryElement-list">
<?php

if(!empty($roots)){
	foreach($roots as $root){
		
		$descendants = $root->descendants()
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
		->findAll(array('order'=>'t.title'));
		
		if(!empty($descendants)){
			echo CHtml::openTag('div',array('class'=>'key'));
			// $url = Yii::app()->request->addUrlParamCat(array($root->url), 'page');
			$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$root->url));
			echo CHtml::link($root->title, $url, array('class'=>'rootCategory'));

			foreach($descendants as $descendant){
				$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$descendant->url));
			//	$url = Yii::app()->request->addUrlParamCat(array($descendant->url), 'page');
			//	$url = Yii::app()->createUrl('catalog/catalog/')
				echo CHtml::link($descendant->title, $url, array('class'=>'parentCategoryElement'));
			}
			echo CHtml::closeTag('div');
		} else {
			$descendants = Category::model()
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
			->findByPk($root->id);
			if($descendants){
				echo CHtml::openTag('div',array('class'=>'key'));
				$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$this->city->url,'url'=>$root->url));
			
				// $url = Yii::app()->request->addUrlParamCat(array($root->url), 'page');
				echo CHtml::link($root->title, $url, array('class'=>'rootCategory'));

				echo CHtml::closeTag('div');
			}
		}
		
	}
}
?>
 </div>
 </div>
 </div>
 <div class="col-lg-5  col-md-5  col-sm-4 col-xs-12">
 </div>
 </div>

<?php
$script = "
$(document).ready(function(){
	clearBullets = function(){
	$('.rootCategoryElement-list .key .parentCategoryElement').addClass('bulleton');	
	var position, prevdata = [];
	$.each($('.rootCategoryElement-list .key'), function(key, value){
		$(value).find('.parentCategoryElement').each(function(i, item){
			position = $(item).position().top;
			prevdata[i] = position;
			if(prevdata[i-1] && prevdata[i-1] != position){
				$(this).prev().removeClass('bulleton');
			}
		});
	});
	$('.rootCategoryElement-list .key .parentCategoryElement:last-child').removeClass('bulleton');
	}
	clearBullets();
	$(window).on('debouncedresize', function(){
         clearBullets();
    });
})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>