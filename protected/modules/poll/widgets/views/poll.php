<?php 
if($choice){ 

$diff = $choice->yes - $choice->no;
	if($diff>-10){
		$weight = '';
		if($choice->weight>0){
			$weight = 'Считают '.$choice->weight.'%';
		}
	}
?>
<div class="pull-left">
<button type="button" class="btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa fa-thumbs-up"></i></button>
</div>
<div class="pull-left p-l-10 c-6">
<div class="f-11"><?php echo CHtml::encode($choice->label); ?></div>
<div class="f-10"><?php echo $weight; ?></div>
</div>
<?php } ?>