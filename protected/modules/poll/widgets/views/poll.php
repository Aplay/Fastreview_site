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
<div style="display:table;">
<div style="display:table-cell;vertical-align:middle;">
<button type="button" class="btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa fa-thumbs-up"></i></button>
</div>
<div style="display:table-cell;vertical-align:middle;" class="p-l-10 c-6">
<div class="f-11"><?php echo CHtml::encode($choice->label); ?></div>
<div class="f-10"><?php echo $weight; ?></div>
</div>
</div>
<?php } ?>