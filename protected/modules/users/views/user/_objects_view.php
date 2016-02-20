<?php

$themeUrl = '/themes/bootstrap_311/';

$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$data->id, 'dash'=>'-', 'itemurl'=>$data->url));

$im = '/img/cap.gif';
$alt = '';

if($data->images){ 
  
  $im = $data->images[0]->getUrl('180x180','adaptiveResize','filename');
  $alt = $data->title;
}
?>
<a class="oblects_view m-b-10" href="<?php echo $url; ?>">
<div class="media">
    <div class="pull-left">
            <img alt="" src="<?php echo $im; ?>" class="lv-img-lg hide" />
        </div>
        <div class="media-body m-t-5">
        <div  style="font-weight:bold;"><?php 
echo $data->title; 
?></div>
    <div  class="m-t-5 m-b-10 c-gray f-11" style="display:block;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $data->created_date); ?>
    </div>
        <p class="hide m-b-5 t-uppercase nocolor"><?php echo CHtml::encode($data->title);  ?></p>
        <?php
        if(!empty($data->description)){
          echo '<p class="object_descr m-b-5 trunk_8">'.nl2br(CHtml::encode($data->description)).'</p>';
        } ?>
          <div class="pull-left" style="width:48%">
         <?php  $this->widget('application.modules.poll.widgets.Poll', array('org_id'=>$data->id, 'type'=>PollChoice::TYPE_PLUS)); ?>
         </div>
         <div class="pull-right" style="width:48%">
         <?php  $this->widget('application.modules.poll.widgets.Poll', array('org_id'=>$data->id, 'type'=>PollChoice::TYPE_MINUS)); ?>
         </div>
         <div class="clearfix"></div>
        </div>
  </div>
</a>
 <div class="clearfix"></div>



