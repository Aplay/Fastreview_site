<?php

$themeUrl = '/themes/bootstrap_311/';
if($data->city){
  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$data->city->url, 'id'=>$data->id,  'itemurl'=>$data->url));
} else {
  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/itemnocity', array('id'=>$data->id));
}
?>

 <a class="org_item" href="<?php echo $url; ?>">
 <div class="card">
 <div class="card-body card-padding">
<?php
$im = '';
$alt = '';
/*
  if($data->logotip){
  // $im = Yii::app()->createAbsoluteUrl('file/logotip',array('id'=>$data->id));
    $im = $data->getOrigFilePath().$data->logotip;
    $alt = $data->title;
 } else {
   // $im = '/img/org_cap3.png';
 	$im = '';
    $alt = '';
 }*/
 if($data->images){ 
	
	$im = $data->images[0]->getUrl('180x180','adaptiveResize','filename');
    $alt = $data->title;
}
?>
<div>
<div class="org_item_title pull-left"><?php echo $data->title;  ?></div>
<?php $wProcess = OrgsWorktime::workingProcess($data->id, $city->utcdiff); 
if(!empty($wProcess)) { 
if($wProcess == 'Сейчас открыто'){
	echo '<div class="org_works  theme-color">'.$wProcess.'</div>';
} else {
	echo '<div class="org_works ">'.$wProcess.'</div>';
}

}

?>
<div class="clearfix"></div>
</div>
<div style="display:table">
<?php if($im){ ?>
  <div class="ologotip">
  <img src="<?php echo $im; ?>" class="img-responsive" />
  </div>
  <?php } ?>
 <div class="org_info  <?php if($im){ echo ' with-pad'; } ?>" >
 
<div class="org_rating" style="margin-bottom:5px;">
<?php
$this->renderPartial('application.views.common._star',array('data'=>$data,'show_count'=>true));
?>
</div>
<ul>
<li class="org_address"><i class="md md-room c-green"></i>
<?php

 echo '<strong>Адрес:</strong> ';
echo $city->title;
if($data->street) { 
echo ', '.$data->street;
}
 if($data->dom) { 
  echo ', '.$data->dom; 
 }
 echo '<br><span class="org_phone_description">'.$data->address_comment.'</span>';
 ?>
 </li>
 
<?php 
$phones = $data->orgsPhones;
if($phones){ ?>
<li class="org_phone"><i class="md md-phone c-green"></i>
<?php
if(count($phones) > 1){
	 echo '<div style="margin-bottom:6px;"><strong>Телефоны:</strong></div>';
	 foreach($phones as $phon){
	  echo '<div>'.$phon->phone.' <span class="org_phone_description">'.$phon->description.'</span></div>';
	}
} else {
	echo '<strong>Телефон:</strong> ';
	foreach($phones as $phon){
	  echo $phon->phone.' <span class="org_phone_description">'.$phon->description.'</span>';
	}
}
  

?>
 </li>

<?php } ?>
<?php
if($data->orgsWorktimes){ 
  $addClass = '';
  $day_number = date('w', time()); // 0 - воскресенье
  $arasymb = array('name'=>array('ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'));

  for($i=0;$i<=6;$i++){
    $arasymb['wt'][$i] = '';
  }
  foreach($data->orgsWorktimes as $worktime){
    if($worktime->iswork == true){
      $from = date('H:i', strtotime($worktime->from_work));
      $to = date('H:i',strtotime($worktime->to_work));

      $arasymb['wt'][$worktime->week] = $from.'-';
      if($from == '00:00' && $worktime->to_work == '23:59:59'){
          $arasymb['wt'][$worktime->week] = 'Круглосуточно';
        } else {
          if($worktime->to_work == '23:59:59'){
          $arasymb['wt'][$worktime->week] .= '24:00';
        } else {
          $arasymb['wt'][$worktime->week] .= $to;
        }
      }
      
    }
  }
  for($i=0;$i<=6;$i++){
    if(empty($arasymb['wt'][$i]))
      $arasymb['wt'][$i] = 'Выходной';
  }
  ?>
<li class="org_worktimer"><i class="md md-access-time c-green"></i>
<div style="margin-bottom:6px;"><strong>Режим работы:</strong></div>
<?php
$arwork = $arweek = array();
for($i=1;$i<=6;$i++){
  if(!in_array($arasymb['wt'][$i], $arwork)){
      $arweek[] = $arasymb['name'][$i];
      $arwork[] = $arasymb['wt'][$i];
  } else {
      $key = array_search($arasymb['wt'][$i], $arwork);
      $arweek[$key] = $arweek[$key].','.$arasymb['name'][$i];
  }
}
if(!in_array($arasymb['wt'][0], $arwork)){
      $arwork[] = $arasymb['wt'][0];
      $arweek[] = $arasymb['name'][0];
  } else {
      $key = array_search($arasymb['wt'][0], $arwork);
      $arweek[$key] = $arweek[$key].','.$arasymb['name'][0];
  }
if(!empty($arweek)){
  foreach($arweek as $k=>$dweek){
    switch ($dweek) {
      case 'ПН,ВТ,СР,ЧТ,ПТ,СБ,ВС':
        $dweek = 'Ежедневно';
        break;
      case 'ПН,ВТ,СР,ЧТ,ПТ,СБ':
        $dweek = 'ПН-СБ';
        break;
      case 'ПН,ВТ,СР,ЧТ,ПТ':
        $dweek = 'ПН-ПТ';
        break;
      case 'ВТ,СР,ЧТ,ПТ':
        $dweek = 'ВТ-ПТ';
        break;
      case 'ПН,ВТ,СР':
        $dweek = 'ПН-СР';
        break;
      case 'СР,ЧТ,ПТ':
        $dweek = 'СР-ПТ';
        break;
      case 'ПН,ВТ':
        $dweek = 'ПН-ВТ';
        break;
      case 'ЧТ,ПТ':
        $dweek = 'ЧТ-ПТ';
        break;
      case 'СБ,ВС':
        $dweek = 'СБ-ВС';
        break;
    }
    echo '<div><strong>'.$dweek . ':</strong> '.$arwork[$k].'</div>';
    
  }
}
?>
</li>
<?php
} 
?>
</ul>
 </div>
 </div>
 <div class="clearfix"></div>
 </div>
 </div>
 </a>
 <div class="clearfix"></div>
 <?php 
if($index == 2){
  $this->widget('ext.widgets.AdsWidget',array('block_id'=>2));
}
?>


