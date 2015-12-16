<?php
/* @var $this ZalController */
/* @var $model Zal */



$this->renderPartial('application.views.common._flashMessage');
if($model->isNewRecord){ 
   
?>
   
<?php } else {
    $this->breadcrumbs=array(
    'Оплаты'=>array('index'),'Редактировать',
);
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Редактировать оплату</span>
    </div>
    <?php
    } 
$this->renderPartial('_form', array('model'=>$model)); ?>