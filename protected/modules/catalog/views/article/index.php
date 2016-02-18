<?php 
$themeUrl = Yii::app()->theme->baseUrl;

Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce-layout.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/kl-woocommerce_single.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/dp-styles.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/style.css');
?>

<section class="site-content shop_page woocommerce" id="content">
<div class="container">
<div class="row">

<div class="right_sidebar col-md-9">

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.catalog.views.article._article_listview',
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
<?php 
$this->renderPartial('application.views.mebica._aside_article',array('popular'=>$popular));
?>
</div>
</div>
</section>

<?php
$script = "
$(document).ready(function(){

})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>