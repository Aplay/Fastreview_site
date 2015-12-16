<?php

$themeUrl = '/themes/bootstrap_311/';
if($data->city){
  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$data->city->url, 'id'=>$data->id,  'itemurl'=>$data->url));
} else {
  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/itemnocity', array('id'=>$data->id));
}
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


<?php $wProcess = OrgsWorktime::workingProcess($data->id, $city->utcdiff); 
if(!empty($wProcess)) { 
echo '<span class="org_worktime">'.$wProcess.'</span>';
}
echo '<br>';
?>

</p>
<p class="org_rating">
<?php
if ($data->rating_id && isset($data->rating) && is_numeric($data->rating->vote_average) ){
         $value = round($data->rating->vote_average,0);
         if($value > 5)
            $value = 5;
         $vote_count = $data->rating->vote_count;
         }else{
         $value = 0;
         $vote_count = 0;
 }
$star_text =  array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем');
/*if($value > 0){
$star_text_show = $star_text[$value];
$star_text = array(1=>$star_text_show,2=>$star_text_show,3=>$star_text_show,4=>$star_text_show,5=>$star_text_show);
}*/
$this->widget('CStarRating',array(
    'value'=>$value,
    'name'=>'star_rating_ajax_'.$data->id,
    'cssFile'=>$themeUrl.'css/star_rating.css',
    'starWidth'=>25,
    'minRating'=>1,
    'maxRating'=>5,
    'titles'=>$star_text,
    'readOnly'=>true,
    
  ));
if($vote_count){
  echo '<span class="afterstar">('.$vote_count.' '.Yii::t('site','review|reviews',$vote_count).')</span>';
}
echo "<br/>";
echo "<div id='mystar_voting'></div>";

?>
</p>
 <p class="org_address">
<?php

 echo '<span>Адрес: ';
echo $city->title;
if($data->street) { 
echo ', '.$data->street;
}
 if($data->dom) { 
  echo ', '.$data->dom; 
 }
 echo '</span>';
 echo '<br><span class="org_phone_description">'.$data->address_comment.'</span>';
 ?>
 </p>
 
<?php 
$phones = $data->orgsPhones;
if($phones){ ?>
<p class="org_phone">
<?php
if(count($phones) > 1)
 echo 'Телефоны:<br> ';
else
  echo 'Телефон: ';
foreach($phones as $phon){
  echo $phon->phone.' <span class="org_phone_description">'.$phon->description.'</span><br>';
}
?>
 </p>
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

      $arasymb['wt'][$worktime->week] = $from.' - ';
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
<div class="org_worktimer"><span>Режим работы:</span><br>
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
    echo $dweek . ' '.$arwork[$k].'<br>';
    
  }
}
?>
</div>
<?php
} 
?>
 </div>
 </a>
 <div class="clearfix"></div>
 <?php 
if($index == 2){
  $this->widget('ext.widgets.AdsWidget',array('block_id'=>2));
}
?>


