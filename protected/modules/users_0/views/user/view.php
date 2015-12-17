<?php 
$themeUrl = Yii::app()->theme->baseUrl;

Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/light-gallery/lightGallery.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/light-gallery/lightGallery.min.css');

Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/salvattore/salvattore.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/isotope/jquery.isotope.min.js', CClientScript::POS_END);
 

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
<div id="user_card">
<div class="card" >
<div class="card-body">
<div style="display: table; width:100%;height:172px;background-image:url('/img/bg_user.jpg');background-repeat: no-repeat;background-size: cover;">
	<div class="profile-block_up" >
	<div class="profile-block servant">
		<div class="panel profile-photo">
			<img id="profileAvatar" alt="" src="<?php echo $user->getAvatar(); ?>" class="my-avatar img-responsive">
		</div>
	</div>	
	</div>
	<div class="user_card_name">
	<?php echo $user->getShowname(); 
	if($this->user->id == Yii::app()->user->id){
	?>
	<div id="user_card_settings" onclick="$('#header_user_box').popover('hide');$('#user_profile_modal').modal();return false;"></div>
	<?php } ?>
	</div>
	<div class="clearfix"></div>
</div>
</div>
</div>
<?php

$this->renderPartial('application.views.layouts.__user');



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
	 $this->widget('ext.widgets.MasonryListView', array(
          
            'dataProvider'=>$provider,
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
            'itemView'=>'_view_profile',
            'itemId' => 'main_list_grid_m2',
            'pager'=>array(
               'maxButtonCount'=>5,
			    'header'=>'',
			  	'firstPageLabel'=>'&#171;',
			  	'lastPageLabel'=>'&#187;',
			  	'nextPageLabel' => '&#8250;',
			  	'prevPageLabel' => '&#8249;',
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
