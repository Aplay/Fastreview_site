<?php 
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/isotope/jquery.isotope.min.js', CClientScript::POS_END);
 
?>

<div  id="main_page_cat" >
<div class="rootCategoryElement-list row-list-3 products_list">
<div class="row">
<div id="main_list_grid_m1"  data-columns>
      <?php  

           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
            'itemView'=>'_organizations_view',
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
            ),
           ));
       
?>
</div>
</div>
</div>
</div>


<a href="/company/newcompany" class="btn btn-primary theme-locator"><i class="zmdi zmdi-plus"></i> Добавить компанию / филиал</a>

<?php
$script = "
$(document).ready(function(){
	$('#main_list_grid_m1').imagesLoaded( function(){
	    $('#main_list_grid_m1').isotope({
	      itemSelector : '.item'
	    });
	  });
$(window).on('debouncedresize', function(){
     $('#main_list_grid_m1').isotope('reloadItems');
    });
});";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>