<?php
echo '<div class="">';
$num = substr ($data['id'], 0, 1); 
$id = substr_replace ($data['id'] , '' , 0 , 1 );
$id = (int)$id;
if($num == 2){
	$data['uploaded_by'] = $this->user->id;
	$bl = $this->renderPartial('application.modules.catalog.views.catalog._lastimages',array('model'=>$data,'addClass'=>'col-sm-3 col-xs-6'),true); 
} elseif($num==1) {
	$comment = Comment::model()->with('obj')->findByPk($id);
	$bl = $this->renderPartial('application.modules.comments.views.comment._itemmain',array('model'=>$comment, 'org'=>$comment->obj),true); 
} else {
	$object = Objects::model()->findByPk($id);
	$bl = $this->renderPartial('application.views.fastreview._objects_view',array('data'=>$object),true); 
}
echo $bl;
echo '</div>';
?>