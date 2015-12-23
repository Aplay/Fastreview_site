<?php

if($index == 10){
  echo '<div style="width:100%;margin-top:30px;text-align:center;"><a class="btn btn-default-over" href="/search/q/'.$term.'">Показать все</button></a>';
  return;
}
$themeUrl = '/themes/bootstrap_311/';

$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$data->id, 'dash'=>'-', 'themeurl'=>$data->category->url,'itemurl'=>$data->url));

$im = '/img/cap.gif';
$alt = '';

if($data->images){ 
  
  $im = $data->images[0]->getUrl('180x180','adaptiveResize','filename');
  $alt = $data->title;
}
?>
<a class="oblects_view oblects_view_blue" href="<?php echo $url; ?>">
<div class="media">
    <div class="pull-left">
            <img alt="" src="<?php echo $im; ?>" class="lv-img-lg" />
        </div>
        <div class="media-body m-t-5">
        <p class="m-b-5 t-uppercase nocolor"><?php echo CHtml::encode($data->title);  ?></p>
        <?php
        if(!empty($data->description)){
          echo '<p class="object_descr m-b-5">'.nl2br(CHtml::encode($data->description)).'</p>';
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



