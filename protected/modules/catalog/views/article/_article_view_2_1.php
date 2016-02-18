<?php 
$im = '/img/cap_360x240.gif';
if($data->logotip){ 
  $data->setCap($im);
  $im = $data->getUrl('360x240','adaptiveResize',false,'logotip');
}
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array( 'id'=>$data->id, 'dash'=>'-', 'itemurl'=>$data->url));
?>
<div  class="col-md-4 col-sm-4  ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_bottom eluid3e112a5a ">
			<div style="width:100%;" class="imgboxes-wrapper">
			<a style="width:100%;" href="<?php echo $url; ?>">
			<div style="display:block;width:100%;height:66%;padding-bottom: 66%;background-image:url('<?php echo $im; ?>');" class="cover-bg imgbox_image"></div>
			<img height="" width="" class="hide img-responsive imgbox_image" alt="" 
			src="<?php echo $im; ?>">
			<span class="imgboxes-border-helper"></span>
			<h3 class="m_title imgboxes-title"><div class="trunk_1">
				<?php echo CHtml::encode($data->title); ?></div>
			</h3></a>
			</div>
  <?php 
 if(!Yii::app()->user->isGuest && Yii::app()->user->id == $data->author){ 
 	$url_red = Yii::app()->createAbsoluteUrl('catalog/article/update',array('id'=>$data->id));
 ?>
  <div class="actions kw-actions">
  <a href="<?php echo $url_red; ?>" class="actions-moreinfo">Редактировать</a>
  </div> 
  <?php } ?>

			</div><!-- end span -->		
</div>
