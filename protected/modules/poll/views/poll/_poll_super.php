<?php 
$diff = $model->yes - $model->no;
if($diff>-10){

	$weight = '';
	if($model->weight>0){
		$weight = 'считают '.$model->weight.'%';
	}
?>
<div class="m-t-20 poll_item_<?php echo $model->type; ?>" id="poll_block_'<?php echo $model->id; ?>" data-weight="<?php echo $model->weight; ?>">
<div class="pull-left"><?php echo CHtml::encode($model->label); ?><div class="c-gray f-11" id="mean_<?php echo $model->id; ?>"><?php echo $weight; ?></div></div>
<div class="pull-right">
<?php 
$ip = MHelper::Ip()->getIp();
$this->renderPartial('application.modules.poll.views.poll.yes_no_poll',array('ip'=>$ip,'model'=>$model));
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
