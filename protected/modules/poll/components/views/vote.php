<div class="card">
<div class="card-body card-padding p-l-20 p-r-20">
<?php 
/*$form = $this->beginWidget('CActiveForm', array(
                'id' => 'pinboard-form',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'afterValidate' => "js: function(form, data, hasError) {\n"

                                ."      if(jQuery.isEmptyObject(data)) {\n"

								."	    } else {\n"
								."        if('flag' in data && data.flag == true){\n"
								."		alert('done');\n"
								."			} \n"

                                ."    return false;\n"
                                ."}\n"
                    )
                ));*/

	if($type==1){
		echo '<div class="c-green f-17">Преимущества</div>';
	} else {
		echo '<div class="c-red f-17">Недостатки</div>';
	}
	echo '<div class="poll_box_type_'.$type.'">';
	if($modelChoices){
		$ip = MHelper::Ip()->getIp();
        foreach ($modelChoices as $choice) {
        	$diff = $choice->yes - $choice->no;
        	if($diff>-10){
        		$weight = '';
				if($choice->weight>0){
					$weight = 'считают '.$choice->weight.'%';
				}
	        	echo '<div class="m-t-20 poll_item_'.$type.'" id="poll_block_'.$choice->id.'" data-weight="'.$choice->weight.'">';
				echo '<div class="pull-left">'.CHtml::encode($choice->label).'<div class="c-gray f-11" id="mean_'.$choice->id.'">'.$weight.'</div></div>';
				echo '<div class="pull-right">';
				$this->render('application.modules.poll.views.poll.yes_no_poll',array('ip'=>$ip,'model'=>$choice));
				echo '</div>';
				echo '<div class="clearfix"></div></div>';
			}
		}
	}
	echo '</div>';
	if($type==1){
		echo '<div style="margin-top:20px"></div>';
		$place = 'Добавить преимущество';
	} else {
	    echo '<div style="margin-top:20px"></div>';
		$place = 'Добавить недостаток';
	} ?>
	<div class="row">
<div class="comment col-xs-12">
	<div>
    <div class="comment-body">
      <div class="fg-line">
        <input type="text" id="PollChoice_label_<?php echo $org_id; ?>_<?php echo $type; ?>" name="PollChoice[label]"  class="form-control" placeholder="<?php echo $place; ?>" />
        <div style="display: none;" id="PollChoice_label_em_<?php echo $org_id; ?>_<?php echo $type; ?>" class="PollChoice_label_em_ in-bl-error"></div>
        </div>
        <div class="clearfix"></div>
        <button type="button" onclick="sendPoll(<?php echo $org_id; ?>,<?php echo $type; ?>);" class="m-t-15 btn btn-success btn-sm pull-right">Отправить</button>
      </div>  
    </div> <!-- / .comment-body -->
  </div> <!-- / .comment -->
</div>
	<?php 
	// $this->endWidget();
?>
</div>
</div>