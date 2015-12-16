<?php
/*
$amount = 100; //$
$orderId = 1;
$description = 'оплата Локатора 3мес.';
$clierntEmail = 'makarenok.roman@gmail.com';
$url = Yii::app()->robokassa->pay($amount, $orderId,$description,$clierntEmail); 
*/
$addclass = 'bgm-green'; $colclass = 'col-sm-4';
if (($key+1) % 3 == 0) {
    $addclass = 'bgm-amber';
} else if(($key+1) % 2 == 0){
    $addclass = 'bgm-cyan';
} else {
    $addclass = 'bgm-green';
}

if($count == 6 ){
    $colclass = 'col-sm-2';
} elseif($count == 4){
    $colclass = 'col-sm-3';
} elseif($count == 3){
    $colclass = 'col-sm-4';
} elseif($count == 2 && $key == 0){
    $colclass = 'col-sm-4 col-sm-offset-2';
} elseif($count == 2 && $key == 1){
    $colclass = 'col-sm-4';
} elseif($count == 1){
    $colclass = 'col-sm-4 col-sm-offset-4';
} else {
    $colclass = 'col-sm-1';
}
?>

<div class="<?php echo $colclass; ?>">
<div class="card pt-inner" style="cursor:pointer;" onclick="planSubmission(<?php echo $data->id; ?>);">
<div class="pti-header <?php echo $addclass; ?>">
<?php 
if($data->amount){ 
    $sum = $data->amount * $data->period;
    $sum = round($sum, 2);
    ?>
    <h2><small style="vertical-align:baseline;">р. </small><span class="plansum" data-default="<?php echo $data->amount; ?>" id="amount_<?php echo $data->id; ?>"><?php echo $data->amount; ?></span> 
    <small>| <?php echo Plan::getPlanTime($data->period_type); ?></small></h2>
    <div class="ptih-title"><?php echo $data->description; ?> 
    <span class="plansum" data-default="<?php echo $sum; ?>" id="plan_<?php echo $data->id; ?>"><?php 
    
    echo $sum; ?></span> рублей</div>
    <?php } else { ?> 
    <h2><?php echo $data->title; ?>
    <small>| <?php echo $data->period.' '.Yii::t('site',Plan::getPlanSclon($data->period_type),$data->period); ?></small></h2> 
    <div class="ptih-title"><?php echo $data->description; ?></div>
    <?php } ?>

</div>
<div class="pti-body">
<?php 
if(!empty($data->text)){
    $descriptions = explode('|', $data->text);
    if(!empty($descriptions)) {
    foreach ($descriptions as $desc) { ?>
        <div class="ptib-item">
        <?php echo $desc; ?>
        </div>
   <?php }
    } else { ?>
    <div class="ptib-item">
        <?php echo $data->text; ?>
    </div>
   <?php }
}
?>
    
   
</div>
<div class="pti-footer">
    <a href="javascript:void(0);" class="<?php echo $addclass; ?>"
    onclick="planSubmission(<?php echo $data->id; ?>);"
    ><i class="zmdi zmdi-check"></i></a>
</div>
</div>
</div>
