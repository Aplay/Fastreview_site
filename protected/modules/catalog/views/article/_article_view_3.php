<?php
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array( 'id'=>$data->id, 'dash'=>'-', 'itemurl'=>$data->url));
$im = '/img/cap_750x350.gif';
if($data->logotip){ 
  $data->setCap($im);
  $im = $data->getUrl('750x350','resize',false,'logotip');
}
?>
<div class="col-sm-8">
<div class="imgboxes_style4 kl-title_style_left post big-post" style="height:350px;">

<a class="hoverBorder" href="<?php echo $url; ?>" >
<span class="hoverBorderWrapper" style="display:block;height:350px;width:100%">
<div style="display:block;width:100%;z-index:10;height:350px;background:url(<?php echo $im; ?>) no-repeat center center;background-size:cover;">
</div>
<span class="theHoverBorder"></span>
</span>
</a>
<div class="post-details"><h3 class="m_title drift_ imgboxes-title trunk_1" style="padding:0;height:auto;position:relative;">
<a href="<?php echo $url; ?>">
<?php echo CHtml::encode($data->title); ?></a>
</h3><em class=""><?php echo Yii::app()->dateFormatter->format('dd MMMM, yyyy',  date('Y-m-d H:i:s', strtotime($data->created_date))); ?>  
Автор: <?php echo $data->authorid->showname; ?>
<?php 
 if(!empty($data->categories)) { ?>
 Тэги: 
 <?php 
 $tags = '';
foreach ($data->categories as $tag) { 
    
    $turl = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$tag->url)); 

    $tags .= '<a rel="category tag" href="'.$turl.'">'.$tag->title.'</a>, ';

}
if(!empty($tags)){
$tags = rtrim($tags, ', ');
}
echo $tags;
    ?>

<?php } ?>
</em>
</div>
<?php 
 if(!Yii::app()->user->isGuest && Yii::app()->user->id == $data->author){ 
 	$url_red = Yii::app()->createAbsoluteUrl('catalog/article/update',array('id'=>$data->id));
 ?>
  <div class="actions kw-actions">
  <a href="<?php echo $url_red; ?>" class="actions-moreinfo">Редактировать</a>
  </div> 
  <?php } ?>
</div>
</div>