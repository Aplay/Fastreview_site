<div class="row" >
<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-12 ">
<div  id="main_page_cat" style="margin-top:20px; margin-bottom: 160px">
   <div class="row-list-4">
   	<?php
   	
if(!empty($cities)){
	foreach($cities as $city){
		echo CHtml::openTag('div',array('class'=>'key'));

		$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/index', array('city'=>$city->url));
		echo CHtml::link($city->title, $url, array());

		echo CHtml::closeTag('div');
	}
}
?>
   </div>
</div>
</div>
</div>