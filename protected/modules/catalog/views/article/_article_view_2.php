<?php 
$im = '/img/cap_360x240.gif';
if($data->logotip){ 
  $data->setCap($im);
  $im = $data->getUrl('360x240','adaptiveResize',false,'logotip');
}
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array( 'id'=>$data->id, 'dash'=>'-', 'itemurl'=>$data->url));
?>
<div  class="  col-md-4 col-sm-4    ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_bottom eluid3e112a5a ">
			<div class="imgboxes-wrapper">
			<a  href="<?php echo $url; ?>">
			<img height="" width="" class="img-responsive imgbox_image" title="MULTI-LANGUAGE READY" alt="MULTI-LANGUAGE READY" 
			src="<?php echo $im; ?>">
			<span class="imgboxes-border-helper"></span>
			<h3 class="m_title imgboxes-title " >
				<div class="trunk_1">
				<?php echo CHtml::encode($data->title); ?>
				</div>
			</h3></a>
			</div>
			
			</div><!-- end span -->		
</div>