<!--<div class="panel-body padding-sm">
        <span class="panel-title">Редактировать компанию</span>
</div>-->
<?php if(!$model->isNewRecord){ 

?>
<div style="margin-bottom:15px;">
<?php 
$siteUrl = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$model->id, 'dash'=>'-', 'itemurl'=>$model->url));

echo CHtml::link('Смотреть на сайте',$siteUrl,array('target'=>'_blank','style'=>'display:inline-block;margin-left:40px;')); 
?>
</div>
<?php } ?>
<?php $this->renderPartial('_form', array(
	'model'=>$model,
    'categories_ar'=>$categories_ar,
    'video'=>$video,
	)); ?>