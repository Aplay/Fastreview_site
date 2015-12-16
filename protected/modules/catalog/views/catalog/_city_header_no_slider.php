<?php 
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/transit/jquery.transit.min.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/vendors/animate-css/animate.min.css');

$im =  $this->city->getUrl('1190x412xC', 'adaptiveResizeQuadrant');

?>

<div style="position:relative;width:100%;height:412px;overflow:hidden;" >
<div  class="header_slcity" style="position:absolute;width:100%;height:412px;background-image:url(<?php echo $im; ?>);">
</div>
<div class="animated" style="color: #fff;
    position: absolute;
    text-align: center;
    top: 80px;
    width: 100%;
    z-index: 800;">
<h3 class="c-white" style="font-size: 50px;
    font-weight: bold;
    text-shadow: 1px 1px #666;margin-bottom:0;">Справочник <?php echo $this->city->rodpad; ?></h3>
<h4 class="c-white" style="font-size: 25px;
    font-weight: 300;
    line-height: 1.2em;
    text-shadow: 1px 1px #666;margin-top:5px;">Фирмы, заведения, работа и отдых</h4>
<?php
$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/search', array('city'=>$this->city->url));?>
<form id="form_search" class="hidden-xs" action="<?php echo $url; ?>"  role="search" method="post">
         <input type="hidden" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>" />
         <input type="hidden" name="pereparam" value="1" />
         <input type="text" name="q" required class="form-control" placeholder="Что вы ищете?"> 
         <button type="submit" class="btn btn-success">ПОИСК</button>
</form>
</div>
</div>
<?php

$scriptAddBr = '
$(document).ready(function(){
 // $(".animated") .addClass("fadeInDownBig");
  $(".header_slcity").addClass("zoombo");
 //$(".header_slcity").css({ scale: 1.2 });

})';
Yii::app()->clientScript->registerScript("fwban", $scriptAddBr, CClientScript::POS_END);
?>