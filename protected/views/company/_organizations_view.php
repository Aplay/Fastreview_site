<?php

$url = Yii::app()->createAbsoluteUrl('/company/update', array('id'=>$data->id));

?>

 <a class="org_item item item-article-list one-column col-lg-4 col-md-6 col-sm-6 col-xs-12" href="<?php echo $url; ?>">
 <div class="card">
 <div class="card-body card-padding">

<div>
<div class="org_item_title pull-left"><?php echo $data->title;  ?></div>
<div class="clearfix"></div>
</div>
<div style="display:table">
<div class="org_info" >
<ul>
<li class="org_address"><i class="zmdi zmdi-pin c-theme"></i>
<?php


echo $data->address;
 ?>
 </li>
<?php 
$phones = $data->orgsPhones;
if($phones){ ?>
<li class="org_phone"><i class="zmdi zmdi-phone c-theme"></i>
<?php
if(count($phones) > 1){
	 echo '<div style="margin-bottom:6px;"></div>';
	 foreach($phones as $phon){
	  echo '<div>'.$phon->phone.' <span class="org_phone_description">'.$phon->description.'</span></div>';
	}
} else {

	foreach($phones as $phon){
	  echo $phon->phone.' <span class="org_phone_description">'.$phon->description.'</span>';
	}
}
  

?>
 </li>

<?php } 
if($data->orgsHttp || $data->vkontakte || $data->facebook  || $data->twitter || $data->instagram || $data->youtube)
{ 
$https = $data->orgsHttp;

if($https){

  echo '<li class="org_site"><i class="zmdi zmdi-link c-theme"></i>';
  $site = '';
  foreach ($https as $key => $st) {
      $linktext = Orgs::parseUrlShow($st->site);
      $sitelink = CHtml::link($linktext, $st->site, array('target'=>'_blank'));
      $site .= $st->site.'<br>';
  }
  if($site){
     $site = rtrim($site, '<br>');
     echo $site;
  }
   echo '</li>';

}

if($data->vkontakte){
  echo '<li class="org_site org_vkontakte redir"><i class="socicon socicon-vkontakte c-green"></i>';
  $url = CHtml::link(Orgs::parseUrlShow($data->vkontakte,true), $data->vkontakte, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
  echo CHtml::tag('span', array(), $url).'</li>';
}
if($data->facebook){
  echo '<li class="org_site org_facebook redir"><i class="socicon socicon-facebook c-green"></i>';
  $url = CHtml::link(Orgs::parseUrlShow($data->facebook,true), $data->facebook, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
  echo CHtml::tag('span', array(), $url).'</li>';
}
if($data->twitter){
  echo '<li class="org_site org_twitter redir"><i class="socicon socicon-twitter c-green"></i>';
  $url = CHtml::link(Orgs::parseUrlShow($data->twitter,true), $data->twitter, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
  echo CHtml::tag('span', array(), $url).'</li>';
}
if($data->instagram){
  echo '<li class="org_site org_instagram redir"><i class="socicon socicon-instagram c-green"></i>';
  $url = CHtml::link(Orgs::parseUrlShow($data->instagram,true), $data->instagram, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
  echo CHtml::tag('span', array(), $url).'</li>';
}
if($data->youtube){
  echo '<li class="org_site org_youtube redir"><i class="socicon socicon-youtube c-green"></i>';
  $url = CHtml::link('<span style="text-decoration:underline;">Видео канал на Youtube</span>', $data->youtube, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow','class'=>'redir'));
  echo CHtml::tag('span', array(), $url).'</li>';
}
}

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
<li class="org_worktimer"><i class="zmdi zmdi-time c-theme"></i>
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



