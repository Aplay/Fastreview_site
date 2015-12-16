<?php
echo '<div class="item item-article-list one-column col-lg-6 col-md-6 col-sm-6 col-xs-12 ">';
$num = substr ($data['id'], 0, 1); 
$id = substr_replace ($data['id'] , '' , 0 , 1 );
$id = (int)$id;
if($num == 2){
	$data['uploaded_by'] = $this->user->id;
	$bl = $this->renderPartial('application.modules.catalog.views.catalog._lastimages',array('model'=>$data,'addClass'=>'col-sm-3 col-xs-6'),true); 
} else {
	$comment = Comment::model()->with('obj')->findByPk($id);
	$bl = $this->renderPartial('application.modules.comments.views.comment._itemmain',array('model'=>$comment, 'org'=>$comment->obj),true); 
}
echo $bl;
echo '</div>';
?>