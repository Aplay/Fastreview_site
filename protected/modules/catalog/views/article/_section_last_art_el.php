<?php
$cnt = 0; $rednum = 3;
if(isset($view) && !empty($view)){
	$rednum = 2;
}
foreach ($lasts as $last) {
	$cnt++;
	if($cnt != $rednum ) { 
		$this->renderPartial('application.modules.catalog.views.article._article_view_1_1',array('data'=>$last));
	} else { 
		$this->renderPartial('application.modules.catalog.views.article._article_view_2_1',array('data'=>$last));
	}
} 
?>

			

