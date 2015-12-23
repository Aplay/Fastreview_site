<?php 
if($choice){ 

$diff = $choice->yes - $choice->no;
	if($diff>-10){
		$weight = '';
		if($choice->weight>0){
			$weight = 'Считают '.$choice->weight.'%';
		}
	}
	if($choice->type == PollChoice::TYPE_PLUS){
		$btnstyle = 'btn-success';
		$btnicon = 'fa-thumbs-up';
	} else {
		$btnstyle = 'btn-danger';
		$btnicon = 'fa-thumbs-down';
	}
?>
<div style="display:table;">
<div style="display:table-cell;position:relative;">
<button type="button" class="btn <?php echo $btnstyle; ?> btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa <?php echo $btnicon; ?>"></i></button>
</div>
<div style="display:table-cell;" class="p-l-10 c-6">
<div class="f-11"><?php echo CHtml::encode($choice->label); ?></div>
<div class="f-10"><?php echo $weight; ?></div>
</div>
</div>
<?php } ?>