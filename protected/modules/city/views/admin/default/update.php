
<div class="panel-body padding-sm">
        <span class="panel-title">Редактировать город <?php echo $model->title; ?></span>
        <br>
        <span>Опубликовано <?php echo $model->orgs_count; ?></span>
        <span>Не опубликовано <?php echo $model->orgs_count_notactive; ?></span>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>

