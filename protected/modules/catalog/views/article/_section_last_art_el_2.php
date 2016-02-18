

<?php
$cnt = 0; $rednum = 2;
if(isset($view) && !empty($view)){
	$rednum = 1;
}
$lasts = array_slice($lasts, 0, 2);
foreach ($lasts as $last) {
$cnt++;
$im = '/img/cap_540x380.gif';
if($last->logotip){ 
  $last->setCap($im);
  $im = $last->getUrl('555x320','adaptiveResize',false,'logotip');
}
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array( 'id'=>$last->id, 'dash'=>'-', 'itemurl'=>$last->url));
 if($cnt != $rednum ) { ?>
<div data-droplevel="2" class="eluidfae0a288  col-md-6 col-sm-6    zn_sortable_content zn_content ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_left eluiddda1b169 ">
			<a  style="width:100%;" href="<?php echo $url; ?>" class=" imgboxes4_link imgboxes-wrapper">
			<div style="display:block;width:100%;height:57%;padding-bottom: 57%;background-image:url('<?php echo $im; ?>');" class="cover-bg imgbox_image"></div>
			<img height=""  width="" class="hide img-responsive imgbox_image" 
			 alt="" 
			src="<?php echo $im; ?>">
			<span class="imgboxes-border-helper"></span>
			<h3 class="m_title imgboxes-title " >
			<div class="trunk_1">
				<?php echo CHtml::encode($last->title); ?>
				</div>
			</h3></a>
			<div class="trunk_2" style="margin-bottom:20px;">
			<?php echo MHelper::String()->purifyFromIm($last->description); ?>
			</div>
			</div><!-- end span -->		
			</div>
<?php } else { ?>

<div data-droplevel="2" class="eluidba62844c  col-md-6 col-sm-6    zn_sortable_content zn_content ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_bottom eluid3e112a5a ">
			<div style="width:100%;" class="imgboxes-wrapper">
			<a style="width:100%;" href="<?php echo $url; ?>">
			<div style="display:block;width:100%;height:57%;padding-bottom: 57%;background-image:url('<?php echo $im; ?>');" class="cover-bg imgbox_image"></div>
			<img height="" width="" class="hide img-responsive imgbox_image" alt="" 
			src="<?php echo $im; ?>">
			<span class="imgboxes-border-helper"></span>
			<h3 class="m_title imgboxes-title"><div class="trunk_1">
				<?php echo CHtml::encode($last->title); ?></div>
			</h3></a>
			</div>
			<div class="trunk_2" style="margin-bottom:20px;">
			<?php echo MHelper::String()->purifyFromIm($last->description); ?>
			</div>
			</div><!-- end span -->		
			</div>
			
 <?php }
 ?>			
		
<?php 
 
} ?>

			

