<?php

$themeUrl = '/themes/bootstrap_311/';

$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$data->id, 'dash'=>'-', 'themeurl'=>$data->category->url,'itemurl'=>$data->url));

$im = '/img/cap.gif';
$alt = '';

if($data->images){ 
  
  $im = $data->images[0]->getUrl('180x180','adaptiveResize','filename');
  $alt = $data->title;
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
          echo '<p class="object_descr m-b-5">'.nl2br(CHtml::encode($data->description)).'</p>';
        } ?>
        
        <div class="pull-left">
        <!--<img alt="" src="/img/gud.png" />-->
        <button type="button" class="btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa fa-thumbs-up"></i></button>
        </div>
        <div class="pull-left p-l-10 c-6">
          <div class="f-11">Местоположение</div>
          <div class="f-10">Считают 56%</div>
        </div>
        </div>
  </div>
</a>
 <div class="clearfix"></div>



