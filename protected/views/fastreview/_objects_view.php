<?php

$themeUrl = '/themes/bootstrap_311/';

$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$data->id, 'dash'=>'-', 'themeurl'=>$data->category->url,'itemurl'=>$data->url));

?>

 <a class="org_item" href="<?php echo $url; ?>">
 <div class="card">
 <div class="card-body card-padding p-t-15">
<?php
$im = '';
$alt = '';

 if($data->images){ 
	
	$im = $data->images[0]->getUrl('180x180','adaptiveResize','filename');
    $alt = $data->title;
}
?>
<div>
<div class="org_item_title pull-left"><?php echo $data->title;  ?></div>

<div class="clearfix"></div>
</div>
<div style="display:table">
<?php if($im){ ?>
  <div class="ologotip">
  <img src="<?php echo $im; ?>" class="img-responsive" />
  </div>
  <?php } ?>
 <div class="org_info  <?php if($im){ echo ' with-pad'; } ?>" >
 
<ul>
<?php 

if($data->description){
	echo '<li class="p-l-0 p-t-0 advert_descr">'.nl2br(CHtml::encode($data->description)).'</li>';
}

?>
<li class="c-gray m-b-0 f-13 p-l-0 p-t-0" style="vertical-align:middle;">
<span style="display:inline-block;vertical-align:middle;">
<?php echo $data->category->title; ?></span>&nbsp;&nbsp; 
<span style="font-size:20px;font-weight:300;vertical-align:middle;">|</span> &nbsp;&nbsp;<span style="display:inline-block;vertical-align:middle;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $data->created_date); ?></span>
</li>
</ul>
 </div>
 </div>
 <div class="clearfix"></div>
 </div>
 </div>
 </a>
 <div class="clearfix"></div>



