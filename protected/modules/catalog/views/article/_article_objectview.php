<?php

$themeUrl = '/themes/bootstrap_311/';

$url = Yii::app()->createAbsoluteUrl('catalog/article/item', array( 'id'=>$data->id, 'dash'=>'-', 'itemurl'=>$data->url));

$im = '/img/cap.gif';
$alt = '';


if(!empty($data->logotip))
{
 $alt = $data->title;
 $im = $data->getUrl('180x180','adaptiveResize',false,'logotip');
} 
?>
<a class="oblects_view" href="<?php echo $url; ?>">
<div class="media">
    <div class="pull-left">
            <img alt="" src="<?php echo $im; ?>" class="lv-img-lg" />
        </div>
        <div class="media-body m-t-5">
        <p class="m-b-5 t-uppercase nocolor"><?php echo CHtml::encode($data->title);  ?></p>
        <?php
        if(!empty($data->description)){
          echo '<p class="object_descr m-b-5 trunk_8">'.MHelper::String()->purifyFromIm($data->description).'</p>';
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



