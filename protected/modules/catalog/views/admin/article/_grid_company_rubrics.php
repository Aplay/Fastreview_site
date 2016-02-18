<?php
$ar = array();

 if($data->categories){
 	$org = Article::model()->findByPk($data->id);
 	$allrubrics = $org->categories;
 	if($allrubrics){
	 	foreach ($allrubrics as $cat){

	 	//	$cat_url = Yii::app()->createAbsoluteUrl($data->city->url.'/catalog/'.$cat->url);
	 	//	echo CHtml::link($cat->title, $cat_url, array('class'=>'parentCategoryElement'));
	 	$ar[] = $cat->title;
	 	}
	 }
 }
 if(!empty($ar)){
 	echo implode(', ',$ar);
 }
 ?>