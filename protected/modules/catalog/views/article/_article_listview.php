<?php  


if($index == 0 || $index % 2 == 0){
	$this->renderPartial('_article_listview_zero', array('data'=>$data));
} else {
	$this->renderPartial('_article_listview_second', array('data'=>$data));
}
?>