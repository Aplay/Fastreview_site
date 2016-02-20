<?php 
$im = '/img/cap_360x240.gif';
if($data->logotip){ 
  $data->setCap($im);
  $im = $data->getUrl('360x240','adaptiveResize',false,'logotip');
}
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array( 'id'=>$data->id, 'dash'=>'-', 'itemurl'=>$data->url));
?>
<div  class="  col-md-6 col-sm-6    ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_left eluiddda1b169 ">
			<a  href="<?php echo $url; ?>" class="imgboxes4_link imgboxes-wrapper">
			<div style="display:block;width:100%;height:66%;padding-bottom: 66%;background:url('<?php echo $im; ?>') no-repeat center center; background-size:cover;" class="cover-bg imgbox_image"></div>

			<span class="imgboxes-border-helper"></span>
			<div class="m_title imgboxes-title">
			<div class="media m-b-20" style="margin-top:8px;">
				<div class="pull-left">
					<?php $src = $data->authorid->getAvatar(); ?>
					<img class="lv-img-md" src="<?php echo $src; ?>" alt="">
				</div>
				<div class="media-body">
					<h4 class=" m-b-5" style="margin-top: 3px;">
					<div class="trunk_2 nocolor">
						<?php echo CHtml::encode($data->title); ?>
						</div>
					</h4>
				</div>
			</div>
			
			</div>
			</a>
			
			</div><!-- end span -->		
</div>