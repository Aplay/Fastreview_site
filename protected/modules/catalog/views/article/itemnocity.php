
<?php  

$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/view_imagesloader.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/lightbox/js/lightbox_mine.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl.'/js/plugins/lightbox/css/lightbox.css');

  $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/itemnocity', array('id'=>$model->id));

//Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:site_name"));
Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($this->pageDescription, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag('article', null, null, array('property' => "og:type"));
Yii::app()->clientScript->registerMetaTag($url, null, null, array('property' => "og:url"));


$addStyle = ''; $cnt_fotos = 0;
$fotos = array();

$imageI = $imageF = $feeds = $feedsF = array();

if($model->images){ 
	foreach($model->images as $image){
		$fotos[] = $image;
	}
}
  if(empty($fotos)){
  	$addStyle = ' display:none;';
  }
?>
<div class="row" >
<div id="header-left-photo" class="header-left-photo" style="<?php echo $addStyle; ?>">
	<div class="item-photo-view">
	<div  id="item-photo-view" style="width:100%; <?php echo $addStyle; ?>">
	<?php if(!empty($fotos)){ 
		$cnt_fotos = count($fotos);
		if(!empty($imageI))
			$cnt_fotos += count($imageI);
		if(!empty($imageF))
			$cnt_fotos += count($imageF);
		// $src = Yii::app()->createAbsoluteUrl('file/company', array('id'=>$fotos[0]->id)); 
		 $src = $model->getOrigFilePath().$fotos[0]->filename;
		// echo CHtml::image($src, '', array('class'=>'img-responsive')); 
		echo $model->getThumbsFilePaths($fotos[0]->filename, 'bg');
		echo CHtml::link('<i></i>', $src, array('class'=>'zoom lazy-load-src','data-lightbox'=>'lb-'.$model->id,'data-title'=>$model->title));

		Yii::app()->clientScript->registerMetaTag($src, null, null, array('property' => "og:image"));
		
	?>
<?php } elseif(!empty($imageI)){
			$cnt_fotos = count($imageI);
			if(!empty($imageF))
				$cnt_fotos += count($imageF);
			$src = $imageI[0]['standard']; 
			echo '<div class="item-gallery-bg" style="background-image:url('.$src.'); "></div>';
			echo '<img class="undergal" alt="'.$model->title.'" src="'.$src.'">';
			echo CHtml::link('<i></i>', $src, array('class'=>'zoom lazy-load-src','data-lightbox-open'=>'lb-'.$model->id,'data-title'=>$model->title));

	  } elseif(!empty($imageF)){
	  		$cnt_fotos = count($imageF);
	  		$src = $imageF[0]['standard']; 
	  		echo '<div class="item-gallery-bg" style="background-image:url('.$src.'); "></div>';
			echo '<img class="undergal" alt="'.$model->title.'" src="'.$src.'">';
			echo CHtml::link('<i></i>', $src, array('class'=>'zoom lazy-load-src','data-lightbox-open'=>'lb-'.$model->id,'data-title'=>$model->title));
	  }
	?>
	<div class="gallery-controls">
	<div class="gallery-photos-size">
		<i class="photo_gal_size"></i>
		<span id="cnt_fotos" style="vertical-align: middle;"><?php echo $cnt_fotos; ?></span>
	</div>
	<div style="display: none !important;" class="gallery-nav">
	<?php
	if(!empty($fotos)){
	foreach($fotos as $k=>$foto){
		if($k != 0 && $foto){
			// $src = Yii::app()->createAbsoluteUrl('file/company', array('id'=>$foto->id));
			$src = $model->getOrigFilePath().$foto->filename;
			$image = $model->getThumbsFilePaths($foto->filename);
		    echo CHtml::link($image, $src, array('class'=>'lazy-load-src', 'data-lightbox'=>'lb-'.$model->id));
		}
	}
	}

	?>

	</div>
	</div>
</div>
</div>
</div>
<?php

$mapparams = array();
if($model->lat && $model->lng && $model->street){
      $address = '';
     $address .= $model->street;
  if($model->dom) { $address .= ', '.$model->dom; }
    $mapparams[] = array(
      'lat'=>floatval($model->lat),
      'lon'=>floatval($model->lng),
      'properties'=>array(
                    
                    'balloonContentHeader'=>$model->title,
                    'balloonContentBody'=>$address,
                    'balloonContentFooter'=>$model->phone,
                ),
      'options'=>array(
         // 'preset'=>'twirl#violetIcon',
          'iconLayout'=> 'default#image',
          'iconImageHref'=>'/img/address_sign25x39.png',
          // Размеры метки.
          'iconImageSize'=>array(25,39),
          // Смещение левого верхнего угла иконки относительно
          // её "ножки" (точки привязки).
          'iconImageOffset'=> array(-10,-40),
        ),
      );
    }

?>

</div>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-12 ">
<div class="catalog_list" style="margin-top:60px; ">
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>4));
?>
<div class="org_item">
<?php

/*
 if($model->logotip){
   $im = Yii::app()->createAbsoluteUrl('file/logotip',array('id'=>$model->id));
 } else {
    $im = '/img/org_cap.png';
 }
*/
 // echo CHtml::link(CHtml::image($im, '', array('class'=>'logotip img-responsive')), $url);

 ?>
 <div itemprop="itemReviewed" itemscope itemtype="http://schema.org/Organization" class="org_info" style="padding-left:0">
 <p class="org_title">
<span itemprop="name"><?php echo $model->title; ?></span>
<?php $wProcess = OrgsWorktime::workingProcess($model->id, 3); 
if(!empty($wProcess)) { 
echo '<span class="org_worktime" style="vertical-align: middle;">'.$wProcess.'</span>';
}
echo '<br>';
?>
</p>
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>5));
?>
<p class="org_rating">
<?php
if ($model->rating_id && isset($model->rating) && is_numeric($model->rating->vote_average) ){
         $value = round($model->rating->vote_average,0);
         if($value > 5)
         	$value = 5;
         $vote_count = $model->rating->vote_count;
         }else{
         $value = 0;
         $vote_count = 0;
 }
$this->widget('CStarRating',array(
    'value'=>$value,
    'name'=>'star_rating_view_'.$model->id,
    'cssFile'=>$themeUrl.'/css/star_rating.css',
    'starWidth'=>25,
    'titles'=>array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем'),
    'minRating'=>1,
	'maxRating'=>5,
    'readOnly'=>true,
  ));
if($vote_count){
  echo '<span class="afterstar">('.$vote_count.' '.Yii::t('site','review|reviews',$vote_count).')</span>';
}
echo "<br/>";
?>
</p>
<?php
if(!empty($model->description)){
	echo CHtml::tag('p', array('class'=>'org_description'), $model->description);
}
?>

 <p class="org_address">
<?php
//if($model->city)
//	echo $model->city->title;
echo '<span>Адрес: <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
echo '<span itemprop="streetAddress">';
if($model->street) { 
echo ', '.$model->street;
}
 if($model->dom) { 
 	echo ', '.$model->dom; 
 }
 echo '</span></span>';
 echo '</span>';
 echo '<br><span class="org_phone_description">'.$model->address_comment.'</span>';
 ?>
 </p>
 <?php 
$phones = $model->orgsPhones;
if($phones){ 
?>
<p class="org_phone">
<?php
$detect = Yii::app()->mobileDetect;
$detect->isMobile();
if(count($phones) > 1)
 echo 'Телефоны:<br> ';
else
  echo 'Телефон: ';
foreach($phones as $k=>$phon){
	$det_tel = ($detect->isMobile()) ? 'tel:' : 'callto:';
	$phone = CHtml::link('<span itemprop="telephone">'.$phon->phone.'</span>',$det_tel.$phon->clear_phone); 	
    echo $phone.' <span class="org_phone_description">'.$phon->description.'</span><br>';

}
?>
 </p>
<?php 
}


if($model->email){ 
	echo CHtml::tag('p', array('class'=>'org_email'), CHtml::mailto($model->email, $model->email,array('itemprop'=>"email")));
} 
if($model->orgsHttp || $model->vkontakte || $model->facebook  || $model->twitter || $model->instagram || $model->youtube){ ?>

<?php
if($model->orgsHttp){

 echo '<p class="org_site">';
  $site = '';
  foreach ($model->orgsHttp as $key => $st) {
  		$linktext = $st->description?$st->description:Orgs::parseUrlShow($st->site);
  		$sitelink = CHtml::link($linktext, $st->site, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
   		$site .= CHtml::tag('span', array(), $st->description?$sitelink:$sitelink.' <span class="org_phone_description">Официальный сайт</span>').'<br>';
  }
  if($site){
    $site = rtrim($site, '<br>');
    echo CHtml::tag('span', array(), $site);
  }
  echo '</p>';

	// echo CHtml::tag('span', array(), 'Официальный сайт:').'<br>';
	// $url = CHtml::link(Orgs::parseUrlShow($model->site), $model->site, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	// echo CHtml::tag('span', array(), $url).'<br>';
}
if($model->vkontakte){
	echo '<p class="org_vkontakte">';
	$url = CHtml::link(Orgs::parseUrlShow($model->vkontakte,true), $model->vkontakte, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	echo CHtml::tag('span', array(), $url).'</p>';
}
if($model->facebook){
	echo '<p class="org_facebook">';
	$url = CHtml::link(Orgs::parseUrlShow($model->facebook,true), $model->facebook, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	echo CHtml::tag('span', array(), $url).'</p>';
}
if($model->twitter){
	echo '<p class="org_twitter">';
	$url = CHtml::link(Orgs::parseUrlShow($model->twitter,true), $model->twitter, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	echo CHtml::tag('span', array(), $url).'</p>';
}
if($model->instagram){
	echo '<p class="org_instagram">';
	$url = CHtml::link(Orgs::parseUrlShow($model->instagram,true), $model->instagram, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	echo CHtml::tag('span', array(), $url).'</p>';
}
if($model->youtube){
	echo '<p class="org_youtube">';
	$url = CHtml::link('<span style="text-decoration:underline;">Видео канал на Youtube</span>', $model->youtube, array('loc'=>Yii::app()->createAbsoluteUrl('/redirect.php'),'target'=>'_blank','rel'=>'nofollow'));
	echo CHtml::tag('span', array(), $url).'</p>';
}
?>

<?php
}
if($model->orgsWorktimes){ 
	$addClass = '';
	$day_number = date('w', time()); // 0 - воскресенье
	$arasymb = array('name'=>array('ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'));

	for($i=0;$i<=6;$i++){
		$arasymb['wt'][$i] = '';
	}
	foreach($model->orgsWorktimes as $worktime){
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
	foreach($model->orgsWorktimes as $worktime){
		if($worktime->iswork == false){
			$arasymb['wt'][$worktime->week] .=  ' <span class="wtimerest">Перерыв: '.date('H:i', strtotime($worktime->from_work)).' - '.date('H:i',strtotime($worktime->to_work)).'</span>';
		}
	}
	for($i=0;$i<=6;$i++){
		if(empty($arasymb['wt'][$i]))
			$arasymb['wt'][$i] = '&nbsp;&nbsp;Выходной';
	}
echo '<div class="org_worktimer"><span>Режим работы:</span><br>';
for($i=1;$i<=6;$i++){
	echo '<div class="wtimec ';
	if($day_number == $i) echo  ' dn ';
	if(($arasymb['wt'][$i]) == '&nbsp;&nbsp;Выходной') echo ' weeknd ';
	echo '">'.$arasymb['name'][$i].'<div class="wtimet ';
	if($day_number == $i) echo  'dn';
	echo '">'.$arasymb['wt'][$i].'</div></div>';
}

echo '<div class="wtimec ';
if($day_number == 0) echo  ' dn ';
if(($arasymb['wt'][0]) == '&nbsp;&nbsp;Выходной') echo ' weeknd ';
echo '">'.$arasymb['name'][0].'<div class="wtimet ';
if($day_number == 0) echo  'dn';
echo '">'.$arasymb['wt'][0].'</div></div>';
echo '<div class="clearfix"></div>';
echo '</div>';
} 
?>
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>6));
?>
 <p class="org_rubrics">
  <span class="org_rubrics_title">Рубрики</span><br>
  <span class="org_rubrics_rubrics key">
 <?php
 if($model->categories){
 	foreach ($model->categories as $cat){

 		$cat_url = Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>1,'url'=>$cat->url));
			
 		// $cat_url = Yii::app()->createAbsoluteUrl($this->city.'/catalog/'.$cat->url);

 		echo CHtml::link($cat->title, $cat_url, array('class'=>'parentCategoryElement'));
 	}
 }
 ?>
 </span>
 </p>
 </div><!--  .org_info -->
 </div><!-- .org_item -->
 </div><!-- .catalog_list -->
 </div>
 <div class="col-lg-5 col-md-5 col-sm-4  col-xs-12 ">
<?php 
$this->widget('ext.widgets.AdsWidget',array('block_id'=>7));
?>
</div>
 </div><!-- .row -->
 <div class="row" >
 <div id="instafor" class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 " >

</div>
</div>



<div class="row" >
<div  class="col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-12 " style="margin-bottom: 100px;">
 <div class="org_reviews" >
 	<p class="org_review">
 	Отзывы <span><?php echo $model->title; ?></span>
 	</p>
 	<div id="comment_module" style="margin-top:20px;margin-bottom:30px;">
        <?php $this->renderPartial('application.modules.comments.views.comment.new_comment_org', array(
				'model'=>$model,
				'themeUrl'=>$themeUrl,
				'city_utc'=>3
			)); ?>
           
    </div>
    <button  class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#leave_comment" style="font-size:20px;">Оставить отзыв</button>
 </div>
 </div>
 </div>

 <div class="hvalues" style="display:none;">
 	<span class="title"><?php echo $model->title; ?></span>
 	<span class="part_description"><?php echo $part_description; ?></span>
 </div>
 <?php

$script = "
$(document).ready(function(){
	var lisInRow = 0; var counterI; var dataCount;
	
	clearBullets = function(){
	$('.org_rubrics_rubrics.key .parentCategoryElement').addClass('bulleton');	
	var position, prevdata = [];
	$.each($('.org_rubrics_rubrics.key'), function(key, value){
		$(value).find('.parentCategoryElement').each(function(i, item){
			position = $(item).position().top;
			prevdata[i] = position;
			if(prevdata[i-1] && prevdata[i-1] != position){
				$(this).prev().removeClass('bulleton');
			}
		});
	});
	$('.key .parentCategoryElement:last-child').removeClass('bulleton');
	}
	clearBullets();
	$(window).on('debouncedresize', function(){
         clearBullets();
    });

	$('.wtimec').on('click', function(){
		$('.wtimec .wtimet').hide();
		$('.wtimec').removeClass('dn');
		$(this).addClass('dn');
		$(this).find('.wtimet').show();
	});
	
	// $('ul.i-list li').hide();
    imagesILoaded = function() {
       // function to invoke for loaded images
       // decrement the counter
       counterI--; 
     
       if( counterI === 0 ) {
           // counter is 0 which means the last
           // one loaded, so do something else

		    $('ul.i-list li').each(function() {
		        if($(this).prev().length > 0) {
		            if($(this).position().top != $(this).prev().position().top) return false;
		            lisInRow++;
		        }
		        else {
		            lisInRow++;   
		        }
		    });
			var  chif = false;
		    if(lisInRow > 0 && lisInRow < dataCount){
		    	chif = true;
		    	var offset = $('ul.i-list li:eq('+(lisInRow-1)+')').offset();
		    	var offsetw = $('#i-container-widther').offset();
		    	var wither = Math.round(offsetw.left - offset.left);
		    	var padd = 0;
		    	if(wither)
		    		padd = wither-150;

				$('ul.i-list li:gt('+(lisInRow-1)+')').hide();
				$('#i-container').after('<button style=\"margin-right: '+ padd +'px;\" id=\"showMoreButton\" class=\"btn btn-primary btn-outline pull-right\" onclick=\"showMoreI();\">Показать больше фото</button>');
		    } 
		    if(!chif){
		    	var inwidth = $('#i-container').width();
		    	var cntim; 
        		if(inwidth){
        			cntim = Math.round(inwidth/150);
        		}
        		var offset = $('ul.i-list li:eq('+(cntim-1)+')').offset();
        		console.log(offset)
		    	var offsetw = $('#i-container-widther').offset();
		    	var wither = Math.round(offsetw.left - offset.left);
		    	var padd = 0;
		    	if(wither)
		    		padd = wither-150;
        		$('ul.i-list li:gt('+(cntim-1)+')').hide();
				$('#i-container').after('<button style=\"margin-right: '+ padd +'px;\" id=\"showMoreButton\" class=\"btn btn-primary btn-outline pull-right\" onclick=\"showMoreI();\">Показать больше фото</button>');
		   
		    }

       }
    }
    
	
    showMoreI = function(){
    	var hidn = $('ul.i-list li').filter(':hidden');
		$(hidn).each(function(index,value) {
			if(index <= (lisInRow*2-1)){
				$(this).show();
			}
		});
		hidn = $('ul.i-list li').filter(':hidden');
		if(!hidn.length){
			$('#showMoreButton').hide();
		}
		
    }

})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>
