<!--<div class="panel-body padding-sm">
        <span class="panel-title">Редактировать компанию</span>
</div>-->
<?php if($model->status_org == Orgs::STATUS_NOT_ACTIVE) { echo '<h3 style="color:red">Компания не оплачена</h3><style>.theme-frost .panel{border-color: red;}</style>'; } ?>
<?php if(!$model->isNewRecord){ 

Yii::app()->clientScript->registerScript('search', "
$('.history-button').click(function(){
    $('.history-form').toggle();
    return false;
});

",CClientScript::POS_END);
?>
<div style="margin-bottom:15px;">
<?php // echo CHtml::link('История действий','#',array('class'=>'history-button')); ?>
<div class="history-form" style="display:none;">
<?php 
/*
$this->renderPartial('_history',array(
    'dataProviderHistory'=>$dataProviderHistory,
    'model'=>$modelLog
)); */
?>
</div><!-- search-form -->
</div>
<?php } ?>
<?php $this->renderPartial('_form', array(
	'model'=>$model,
    'phones'=>$phones,
    'http'=>$http,
    'worktime'=>$worktime,
    'dataProviderStatus'=>$dataProviderStatus
  // 'dataProviderHistory'=>$dataProviderHistory,
  //  'modelLog'=>$modelLog
	)); ?>