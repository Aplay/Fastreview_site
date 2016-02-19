<?php 
$themeUrl = Yii::app()->theme->baseUrl;

// Yii::app()->clientScript->registerCssFile($themeUrl . '/css/template_article.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce-layout.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/kl-woocommerce_single.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/dp-styles.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/style.css');
?>

<div class="row m-t-20">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div style="margin-bottom:20px;font-size:25px;font-weight:bold;padding-left:30px;">
Обзоры</div>
<div class="itemListView">
<div class="itemList">
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.catalog.views.article._article_objectview',
    'id'=>'article_listview',       // must have id corresponding to js above
    'itemsCssClass'=>'article_listview',
    'ajaxUpdate' => true,
    'template'=>"{items}\n{pager}",
    'pager'=>array(

              'header' => '',
              'maxButtonCount'=>5,
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
<?php 
// $this->renderPartial('application.views.mebica._aside_article',array('popular'=>$popular));
?>
</div>


<?php
$script = "
$(document).ready(function(){
  

 
})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>