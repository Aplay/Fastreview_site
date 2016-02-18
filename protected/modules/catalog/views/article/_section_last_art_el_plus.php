<?php
$cnt = 0;
foreach ($lasts as $k=>$last) {
  if($k == 0 ) { 
    $this->renderPartial('application.modules.catalog.views.article._article_view_3',array('data'=>$last));
  } else { 
    $this->renderPartial('application.modules.catalog.views.article._article_view_4',array('data'=>$last));
  } 
}
?>



