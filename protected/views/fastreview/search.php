<?php
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);

?>
<div class="row m-t-20">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div style="margin-bottom:20px;font-size:25px;font-weight:bold;padding-left:30px;">
<?php echo CHtml::encode($term); ?></div>
<div class="catalog_list">
<?php 

?>
</div> 
</div> 
</div>

<div class="row" >
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div class="catalog_list" style="margin-bottom:50px;">
<div id="results-container" class="products_list">
      <?php  
           if($provider){
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'viewData'=>array('city'=>$this->city),
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
          //  'summaryText'=>'<div class="summary_show">Показано</div><div><span class="summary_end">{end}</span> из {count}</div>',
            'itemView'=>'_objects_view',
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
       }


?>

</div>
</div>
</div>
</div>
<?php 
$script = "
$(document).ready(function(){
	$('.object_descr').trunk8({lines:8, tooltip: false});
})
";

Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>




