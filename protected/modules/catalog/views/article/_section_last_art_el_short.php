<?php
$cnt = 0;
foreach ($lasts as $last) {
	$cnt++;
	if($cnt != 3 ) { 
		$this->renderPartial('application.modules.catalog.views.article._article_view_1_1',array('data'=>$last));
	} else { 
		$this->renderPartial('application.modules.catalog.views.article._article_view_2_1',array('data'=>$last));
	}
}
?>

			

