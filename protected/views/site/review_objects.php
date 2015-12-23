<?php 
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/view/search.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/query-object/jquery.query-object.js', CClientScript::POS_END);
             
?>
<div class="row m-t-20">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div class="p-l-30">О ЧЕМ ХОТИТЕ НАПИСАТЬ?</div></br>
<form class="p-l-30" role="form" style="margin-top:30px;" id="mainSearchFormReviewObject">
<div class="form-group" style="max-width:400px;margin:0;position:relative;">
<input  type="search" value="<?php echo $term; ?>" placeholder="" id="searchFieldReviewObject" class="form-control" autocomplete="off">
<div id="searchFieldIcon" style="cursor:default;"><i class="fa"></i></div>
</div>
</form>
<div id="results" class="m-t-30">
<?php 
$this->renderPartial('_search',array('provider'=>$provider));
?>
</div>
</div>
</div>


<div class="row" style="margin-top:60px;">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div class="p-l-30">НЕ НАШЛИ НУЖНЫЙ ТОВАР ИЛИ ОБЪЕКТ?<br>
<a class="btn btn-default-over m-t-30" href="new_object">Добавить</a>
</div>
</div>
</div>
<?php 
$script = "
$(document).ready(function(){
    
})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>