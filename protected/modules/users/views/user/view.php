<?php 
$themeUrl = Yii::app()->theme->baseUrl;

Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');

Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/salvattore/salvattore.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/isotope/jquery.isotope.min.js', CClientScript::POS_END);
 
$user_avatar = $this->user->getAvatar();
/*
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
		$blocks[$k]['html'] = $this->renderPartial('application.modules.catalog.views.catalog._lastimages',array('model'=>$im,'addClass'=>'col-sm-3 col-xs-6'),true); 
		
	}
}
*/
?>
<div id="user_card" class="row m-t-20">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">

<div class="media " style="margin-bottom:30px;">
<div class="pull-left">
<img class="lv-img-lg" style="height: 120px;
    width: 120px;margin-left:15px;" src="<?php echo $user_avatar; ?>" alt="">
</div>
<div class="media-body">
<div  style="margin-top:40px;margin-bottom:20px;font-size:25px;font-weight:bold;padding-left:30px;">
	<?php 
echo $this->user->showname; 
?>
</div>
</div>
</div>

<?php 
// $this->renderPartial('_user_header',array('user'=>$user));


// $this->renderPartial('application.views.layouts.__user');



 if(!empty($provider->data)){ 
?>
<div class="row">

<?php

	/* usort($blocks, MHelper::get('Array')->sortFunction('time'));
	$blocks = array_reverse($blocks);
	foreach ($blocks as $k=>$block) { 

		echo '<div class="item-article-list one-column col-xs-12">';
		echo $block['html'];
		echo '</div>';
		
	 } */
	// $this->widget('ext.widgets.MasonryListView', array(
       $this->widget('zii.widgets.CListView', array(   
            'dataProvider'=>$provider,
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
            'itemView'=>'_view_profile',
           // 'itemId' => 'main_list_grid_m2_',
            'emptyText'=>'',
            'pager'=>array(
              'maxButtonCount'=>5,
              'header' => '',
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            )
           ));
	 /*
if(!empty($blocks)) {
	foreach ($blocks as $block) {
		echo '<div class="item-article-list one-column col-xs-12">';
		$num = substr ($block['id'], 0, 1); 
		$id = substr_replace ($block['id'] , '' , 0 , 1 );
		$id = (int)$id;
		if($num == 2){
			$block['uploaded_by'] = $user->id;
			$bl = $this->renderPartial('application.modules.catalog.views.catalog._lastimages',array('model'=>$block,'addClass'=>'col-sm-3 col-xs-6'),true); 
		} else {
			$comment = Comment::model()->with('obj')->findByPk($id);
			$bl = $this->renderPartial('application.modules.comments.views.comment._itemmain',array('model'=>$comment, 'org'=>$comment->obj),true); 
		}
		echo $bl;
		echo '</div>';

	}*/
	
// }
	 ?>

</div>
</div>
<?php


$script = "
$(document).ready(function(){
	$('.article_caption_title').trunk8({lines:2, tooltip: false});
	 $(window).on('debouncedresize', function(){
	 	$('.article_caption_title').trunk8({lines:2, tooltip: false});
	 });

	newImageHeight = function(){
		if(!$('.lightbox .lightbox-item').length)
			return;
		var w = $('.lightbox .lightbox-item:eq(0)').width();
		// console.log(w)
		$('.lightbox .lightbox-item img').css('height', w+'px');
	}
	$(window).on('debouncedresize', function(){
		// salvattore.recreateColumns(document.querySelector('#main_list_grid_2'));
		 newImageHeight();
		 $('#main_list_grid_m2').isotope('reloadItems');
    });
	 newImageHeight();
  //  $.getScript('//cdn.jsdelivr.net/isotope/1.5.25/jquery.isotope.min.js',function(){

	  /* activate jquery isotope */
	  $('#main_list_grid_m2').imagesLoaded( function(){
	    $('#main_list_grid_m2').isotope({
	      itemSelector : '.item'
	    });
	  });
	  
	// });
})
";
Yii::app()->clientScript->registerScript("scriptView", $script, CClientScript::POS_END);
?>
<?php } ?>
