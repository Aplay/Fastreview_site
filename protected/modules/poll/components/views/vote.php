<div class="card " style="border:1px solid #ccc;">
<div class="card-body p-t-20 p-b-20">
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
		echo '<div><button type="button" style="display:block;float:left;" class="m-l-20 btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle m-r-10"><i class="fa fa-thumbs-up"></i></button> <div style="display:block;float:left;" class="card f-14 t-uppercase p-t-5">Отзывы-Преимущества</div></div>';
	} else {
		echo '<div><button type="button" style="display:block;float:left;" class="m-l-20 btn btn-danger btn-icon btn-icon waves-effect waves-circle waves-float finger-circle m-r-10"><i class="fa fa-thumbs-down"></i></button> <div style="display:block;float:left;" class="card f-14 t-uppercase p-t-5">Отзывы-Недостатки</div></div>';
	}
	echo '<div class="clearfix"></div><div class=" poll_box_type_'.$type.'">';
	if($modelChoices){
		$ip = MHelper::Ip()->getIp();
        foreach ($modelChoices as $choice) {
        	$diff = $choice->yes - $choice->no;
        	if($diff>-10){
        		$weight = '';
				if($choice->weight>0){
					$weight = 'считают '.$choice->weight.'%';
				}
	        	echo '<div class="vote-line poll_item_'.$type.'" id="poll_block_'.$choice->id.'" data-weight="'.$choice->weight.'">';
				echo '<div class="vote-line-title">'.CHtml::encode($choice->label).'<div class="c-gray f-11" id="mean_'.$choice->id.'">'.$weight.'</div></div>';
				echo '<div class="vote-fingers">';
				$this->render('application.modules.poll.views.poll.yes_no_poll',array('ip'=>$ip,'model'=>$choice));
				echo '</div>';
				echo '<div class="clearfix"></div></div>';
			}
		}
	}
	echo '</div>';
	if($type==1){
		echo '<div style="margin-top:20px"></div>';
		$place = 'ДОБАВИТЬ ПРЕИМУЩЕСТВО';
	} else {
	    echo '<div style="margin-top:20px"></div>';
		$place = 'ДОБАВИТЬ НЕДОСТАТОК';
	} ?>
	<div class="row">
<div class="new-poll comment col-xs-12">
	<div>
    <div class="comment-body m-l-20 m-r-20 m-b-5">
      <div class="fg-line">
        <input data-obj="<?php echo $org_id; ?>" data-type="<?php echo $type; ?>" type="text" id="PollChoice_label_<?php echo $org_id; ?>_<?php echo $type; ?>" name="PollChoice[label]"  class="form-control" placeholder="<?php echo $place; ?>" />
        <div style="display: none;" id="PollChoice_label_em_<?php echo $org_id; ?>_<?php echo $type; ?>" class="PollChoice_label_em_ in-bl-error"></div>
        </div>
        <div class="clearfix"></div>
        <button type="button" onclick="sendPoll(<?php echo $org_id; ?>,<?php echo $type; ?>);" class="hide m-t-15 btn btn-success btn-sm pull-right">Отправить</button>
      </div>  
    </div> <!-- / .comment-body -->
  </div> <!-- / .comment -->
</div>
	<?php 
	// $this->endWidget();
?>
</div>
</div>