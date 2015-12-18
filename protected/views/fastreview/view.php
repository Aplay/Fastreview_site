<?php
$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);
if(!$search){
  $check = null;
  $term = null;
}
?>
<div class="row m-t-30" >
<div class="col-lg-11 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 ">
<div class="catalog_list" style="padding-left:26px;">
<?php 

if(isset($count_items) && !empty($count_items)){
$doptext = $count_items.' '.Yii::t('site','object|objects',$count_items);
} else {
$doptext = 'не найдено';
}
if(isset($model)){
	echo CHtml::tag('h2', array('class'=>'org_title'), $model->title. ' <span class="cat_found_text">'.$doptext.'</span>');
} else { 
	echo CHtml::tag('h2', array('class'=>'org_title'),  ' <span class="cat_found_text">'.$doptext.'</span>');
}
if(isset($count_items) && !$count_items){ ?>
<div class="summary" style="display: block;"><div class="summary_show">Показано</div><div><span class="summary_end">0</span> из 0</div></div>
<?php }

echo CHtml::tag('div',array('class'=>'under_org_title','style'=>'margin-bottom:20px;'),'');


if ( isset( $this->breadcrumbs ) ){ 
$this->widget('zii.widgets.CBreadcrumbs', array(
  'links'=>$this->breadcrumbs,
  'tagName'   =>'ul', // container tag
  'homeLink'=>'',
  'separator'=>'<li class="separator"> &#8594; </li>',
  'activeLinkTemplate'  =>'<li class="brcr"><a href="{url}">{label}</a></li>', // active link template
  'inactiveLinkTemplate'  =>'<li class="brcr">{label}</li>', // in-active link template
  'htmlOptions'=>array('class'=>'breadcrumb')
  
  ));

}
?>
</div> 
</div> 
</div>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-7 col-sm-12  col-xs-12 ">
<div class="catalog_list" >

</div>
</div>
</div>
<div class="row" >
<div class="col-lg-6 col-lg-offset-1 col-md-7 col-sm-12  col-xs-12 ">
<div class="catalog_list" style="margin-bottom:50px;">
<div id="results-container" class="products_list">
      <?php  
           if($provider){
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'viewData'=>array('city'=>$this->city),
            'ajaxUpdate'=>false,
            'template'=>"{summary}\n{items}\n{pager}",
            'summaryText'=>'<div class="summary_show">Показано</div><div><span class="summary_end">{end}</span> из {count}</div>',
            'itemView'=>'_objects_view',
            'emptyText'=>'',
            'pager'=>array(
              'maxButtonCount'=>5,
              'header' => '',
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            ),
           ));
       }

// $this->widget('ext.widgets.AdsWidget',array('block_id'=>2));
?>

</div>

 
</div>

</div>

</div>
<?php 
if(!$search){ ?>
<div class="row">
<div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 ">
<div class="card">
<div class="card-body card-padding">
<div class="rootCategoryElement">
<div class="key">
<?php

if(!empty($roots)){
  
  foreach($roots as $root){
      $count = false;
        if(!empty($rows)){
          foreach ($rows as $items) {
            if($root->id == $items['categorie']){
              $count = $items['cnt'];
              break;
            }
          }
        } 
        if(!$count)
          continue;

      /* if($showother)
      {
        $descendantsrubs = CategoryAdvert::getRubs($root);
        
        if(!empty($descendantsrubs))
        {

          echo CHtml::openTag('div',array('class'=>'key', 'style'=>'min-height:25px;display:inline-block'));
          $url = Yii::app()->createAbsoluteUrl('/advert/advert/view', array('city'=>$this->city->url,'url'=>$root->url));
        //  echo CHtml::link($root->another_title?$root->another_title:$root->title, $url, array('class'=>'graytogreen', 'style'=>'display:block;background-image:url("'.Yii::app()->createAbsoluteUrl('file/file/logotip', array('id'=>$root->id,'model'=>'CategoryAdvert','filename'=>'logotip','realname'=>'logotip_realname')).'"); background-position: left center; background-size:25px 25px; min-height:40px;line-height:40px; padding-left: 35px; background-repeat:no-repeat;'));
          echo CHtml::link('<div style="display:inline-block;width:21px;text-align:center;"></div>', $url, array('class'=>'graytogreen', 'style'=>'display:inline-block;min-height:40px;line-height:40px;'));
          
          echo CHtml::link($root->title, $url, array('class'=>'graytogreen', 'style'=>'display:inline-block;
            min-height:40px;line-height:40px; padding-left: 10px;'));
          
          foreach($descendantsrubs as $descendant)
          {
            $url = Yii::app()->createAbsoluteUrl('/advert/advert/view', array('city'=>$this->city->url,'url'=>$descendant['url']));
            $title = $descendant['title'];
            echo CHtml::link($title, $url, array('class'=>'graytogreen','style'=>'display:inline-block;margin-right:20px;font-size:smaller;'));
          }
          echo CHtml::closeTag('div');
        }
        else
        {
          $descendants = CategoryAdvert::getRubs(null, '', $root->id);

          if($descendants){
            echo CHtml::openTag('div',array('class'=>'key', 'style'=>'min-height:25px;'));
            $url = Yii::app()->createAbsoluteUrl('/advert/advert/view', array('city'=>$this->city->url,'url'=>$root->url));
          
            $title = $root->title;
            echo CHtml::link($title, $url, array('class'=>'graytogreen'));

            echo CHtml::closeTag('div');
          }
        }
        
      }
      else 
      { */
        
        $url = Yii::app()->createAbsoluteUrl('/advert/advert/view', array('city'=>$this->city->url,'url'=>$root->url));
        $title = $root->title;
        echo CHtml::link($title.' <span class="c-green">'.$count.'</span>', $url, array('class'=>'parentCategoryElement graytogreen', 'style'=>'display:inline-block; 
           '));
        
        
      // }
      
    
    
  }
} 
?>
</div>
</div>
 </div>
 </div>
</div>
</div>
<?php
}
$script = "
$(document).ready(function(){
";
if(!$search){  
  $script .= "
	clearBullets = function(){
  $('.rootCategoryElement .key').find('br').remove();
  $('.rootCategoryElement .key .parentCategoryElement').addClass('bulleton');  
  var position, prevdata = [];
  $.each($('.rootCategoryElement .key'), function(key, value){
    $(value).find('.parentCategoryElement').each(function(i, item){
      if(i==3){
        $(this).removeClass('bulleton').after('<br>');
      }
      position = $(item).position().top;
      prevdata[i] = position;

      if((typeof prevdata[i-1] != 'undefined') && prevdata[i-1] != position){
        $(this).prev().removeClass('bulleton');
      }
    });
  });
  $('.rootCategoryElement .key .parentCategoryElement:last-child').removeClass('bulleton');
  }
  clearBullets();
  $(window).on('debouncedresize', function(){
         clearBullets();
    });
";
}
$script .= "
	$('.advert_descr').trunk8({lines:8, tooltip: false});
})
";

Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>




