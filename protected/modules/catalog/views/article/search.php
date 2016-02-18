<?php
if(isset($count_items) && $count_items > 0){  

$doptext = $count_items.' '.Yii::t('site','article|articles',$count_items);
?>
<div style="padding-left:35px;" class="catalog_list">
<?php
echo CHtml::tag('h2', array('class'=>'org_title'),  $dtitle .' <span class="cat_found_text">'.$doptext.'</span>');
echo CHtml::tag('div',array('class'=>'under_org_title','style'=>'margin-bottom:20px;'),'');

?>
</div>

<?php } else { 
$doptext = '0 статей';
	?>
<div style="padding-left:26px;" class="catalog_list">
<?php
echo CHtml::tag('h2', array('class'=>'org_title'),  $dtitle .' <span class="cat_found_text">'.$doptext.'</span>');
?>
</div>

<?php	} ?>
<div class="row">
<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">


<?php if ($dataProvider && $dataProvider->totalItemCount){ ?>


<?php

	$this->widget('zii.widgets.CListView', array(
	    'dataProvider'=>$dataProvider,
	    'itemView'=>'application.modules.catalog.views.article._article_listview',
	    'id'=>'article_listview',      // must have id corresponding to js above
	    'template'=>"{summary}\n{items}\n{pager}",
        'summaryText'=>'<div class="summary_show">Показано</div><div><span class="summary_end">{end}</span> из {count}</div>',
	    'itemsCssClass'=>'article_listview',
	    'emptyText'=>'',
	     'ajaxUpdate'=>false,
	    'pager'=>array(

              'header' => '',
              'maxButtonCount'=>5,
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination', 'style'=>'margin:250px 26px 23px 26px;')
            ),
	));
	?>


<?php } ?>

</div>
</div>
<?php
if(isset($count_items) && $count_items > 0){ 

$script = "
$(document).ready(function(){

	if($('.list-view .summary').length){
		$('.list-view .summary').insertAfter($('h2.org_title')).show();
	}
	
});";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
}
?>

