<!--<div class="panel-body padding-sm">
        <span class="panel-title">Редактировать компанию</span>
</div>-->
<?php if(!$model->isNewRecord){ 

?>
<div style="margin-bottom:15px;">

</div>
<?php } ?>
<?php $this->renderPartial('_form', array(
	'model'=>$model,
    'categories_ar'=>$categories_ar,
    'video'=>$video,
	)); ?>