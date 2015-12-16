<?php
/* @var $this ZalController */
/* @var $model Zal */



$this->renderPartial('application.views.common._flashMessage');
if($model->isNewRecord){ 
    $this->breadcrumbs=array(
    'Статусы'=>array('index'),'Создать',
);
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Создать статус</span>
    </div>
<?php } else {
    $this->breadcrumbs=array(
    'Статусы'=>array('index'),'Обновить',
);
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Редактировать статус</span>
    </div>
    <?php
    } 
$this->renderPartial('_form', array('model'=>$model)); ?>