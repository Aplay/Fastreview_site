<?php

$themeUrl = '/themes/bootstrap_311/';
$url = Yii::app()->createAbsoluteUrl('/catalog/catalogue/item', array('city'=>$this->city->url,'id'=>$data->id,'itemurl'=>$data->url,'dash'=>'-'));

?>
 <a class="org_item" href="<?php echo $url; ?>">
<?php
  if($data->logotip){
  // $im = Yii::app()->createAbsoluteUrl('file/logotip',array('id'=>$data->id));
    $im = $data->getOrigFilePath().$data->logotip;
    $alt = $data->title;
 } else {
    $im = '/img/org_cap2.png';
    $alt = '';
 }

  echo '<div style="background-image: url('.$im.')" class="ologotip img-circle"></div><div class="clear_ologotip"></div><img alt="'.$alt.'" class="logotip" src="'.$im.'" />';
 ?>
 
 <div class="org_info" >
 <p>
 <span class="org_item_title"><?php echo $data->title;  ?></span>


</p>

 </div>
 </a>
 <div class="clearfix"></div>



