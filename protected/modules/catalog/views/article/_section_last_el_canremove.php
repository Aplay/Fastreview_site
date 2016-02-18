
<section id="eluid631417c6" class="zn_section eluid631417c6     section--no ">
<div class="zn_section_size container">
	<div data-droplevel="1" class="row zn_columns_container zn_content ">
<?php
$cnt = 0;
foreach ($lasts as $last) {
$cnt++;
$im = '/img/cap_360x240.gif';
if($last->images){ 
  $im = $last->images[0]->getUrl('360x240','adaptiveResize','filename');
}
$url = Yii::app()->createAbsoluteUrl('/mebica/item', array( 'id'=>$last->id, 'dash'=>'-', 'itemurl'=>$last->url));
 if($cnt != 3 ) { ?>
<div data-droplevel="2" class="eluidfae0a288  col-md-4 col-sm-4    zn_sortable_content zn_content ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_left eluiddda1b169 ">
			<a  href="<?php echo $url; ?>" class="imgboxes4_link imgboxes-wrapper">
			<img height="" width="" class="img-responsive imgbox_image" 
			 alt="" 
			src="<?php echo $im; ?>"><span class="imgboxes-border-helper"></span>
			<h3 class="m_title imgboxes-title trunk1" >
				<?php echo CHtml::encode($last->title); ?>
			</h3></a>
			<p class="trunk2"><?php echo CHtml::encode($last->description); ?></p>
			</div><!-- end span -->		
			</div>
<?php } else { ?>
<a  href="<?php echo $url; ?>">
<div data-droplevel="2" class="eluidba62844c  col-md-4 col-sm-4    zn_sortable_content zn_content ">
			<div class="box image-boxes imgboxes_style4 kl-title_style_bottom eluid3e112a5a "><div class="imgboxes-wrapper"><img height="" width="" class="img-responsive imgbox_image" title="MULTI-LANGUAGE READY" alt="MULTI-LANGUAGE READY" 
			src="<?php echo $im; ?>"><span class="imgboxes-border-helper"></span>
			<h3 class="m_title imgboxes-title trunk1">
				<?php echo CHtml::encode($last->title); ?>
			</h3></div>
			<p class="trunk2"><?php echo CHtml::encode($last->description); ?></p>
			</div><!-- end span -->		
			</div>
			</a>
 <?php }
 ?>			
		
<?php 
 
} ?>

				</div>
			</div>
 </section>
 <?php 
 $script = "
$(document).ready(function(){
	 $('.trunk1').trunk8({lines:1});
	 $('.trunk2').trunk8({lines:2});
})
";
Yii::app()->clientScript->registerScript("script", $script, CClientScript::POS_END);
?>
