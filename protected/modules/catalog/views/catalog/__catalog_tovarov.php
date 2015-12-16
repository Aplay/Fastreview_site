<?php 
$lastJournal = $model->oneJournalFirst();
if($lastJournal)
{
$url = Yii::app()->createAbsoluteUrl('/catalog/catalogue/item', array('city'=>$this->city->url,'id'=>$lastJournal->id,'itemurl'=>$lastJournal->url,'dash'=>'-'));

?>
<div class="org_rubrics" style="padding-left:32px;margin-top:20px;margin-bottom:20px;">
  <span class="org_rubrics_title" style="margin-bottom:10px;">Регулярные каталоги товаров и акций</span><br>
  <p><a href="<?php echo $url; ?>"><?php 
  echo MHelper::String()->truncate($lastJournal->title,26); ?></a></p>
  <?php 
  $imgs = $lastJournal->images;
  if($imgs)
  {
  	echo CHtml::link(CHtml::image($lastJournal->getOrigFilePath().$imgs[0]->filename,'',array('style'=>'max-width:225px;height:auto;')),$url);
  }
  ?>
</div>
<?php
}
?>