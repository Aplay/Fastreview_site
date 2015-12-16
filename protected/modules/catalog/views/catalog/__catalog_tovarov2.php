<?php 
$allJournal = $model->allJournal();
if($allJournal)
{

?>
<div class="row" style="margin-bottom:10px;">
<div class="col-xs-12">
<div class="org_item">
<div style="font-size:20px;font-weight:300;margin-left:20px;margin-bottom:18px;" class="rootCategory">
      Каталоги и акции
      </div>
<div class="row">
<div class="col-xs-12 item-article-list">

  <?php 
 foreach($allJournal as $journal){
 $url = Yii::app()->createAbsoluteUrl('/catalog/catalogue/item', array('city'=>$this->city->url,'id'=>$journal->id,'itemurl'=>$journal->url,'dash'=>'-'));
//$imgs = $journal->images;
 $imgs = JournalImages::model()->findAll(array('condition'=>'journal='.$journal->id,'order'=>'date_uploaded'));

if($imgs)
{
 $src = $imgs[0]->getUrl('320x320xT','adaptiveResizeQuadrant',false,'filename');
 } else {
 	$src = '/img/cap.gif';
 }

  if($imgs)
  {
  	// echo CHtml::link(CHtml::image($lastJournal->getOrigFilePath().$imgs[0]->filename,'',array('style'=>'max-width:225px;height:auto;')),$url);
  }
  ?>

<a class="article-bg-container" href="<?php echo $url; ?>" >
	<div class="article-bg" style="background-image:url('<?php echo $src; ?>');"></div>
	<div class="caption" style="color:#5e5e5e;background-color:#fff;padding:12px 20px; height:60px;width:100%;overflow:hidden;">
	<table  style="width:100%;height:36px;">
		<tr><?php 
		echo '<td  style="height:36px;overflow:hidden;vertical-align:middle;"><div class="article_caption_title">'.$journal->title.'</div></td>'; ?>
	</tr></table></div>
</a>
<?php } ?>
</div> 
</div> 
</div> 
</div> 
</div>
<?php
}
?>