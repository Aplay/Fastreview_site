<?php
/* @var $this ZalController */
/* @var $model Zal */



$this->renderPartial('application.views.common._flashMessage');
if($model->isNewRecord){ 
    $this->breadcrumbs=array(
    'Тарифы'=>array('index'),'Создать',
);
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Создать тариф</span>
    </div>
<?php } else {
    $this->breadcrumbs=array(
    'Тарифы'=>array('index'),'Обновить',
);
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Редактировать тариф</span>
    </div>
    <?php
    } 
$this->renderPartial('_form', array('model'=>$model)); ?>