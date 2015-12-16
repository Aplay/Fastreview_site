<?php 
$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$data->city->url, 'id'=>$data->id,  'itemurl'=>$data->url));
 
?>
<a href="<?php echo $url; ?>" class="near_org">
<div class="f-15 c-3" style="line-height:1.2em;margin-bottom:3px;"><strong><?php echo $data->title; ?></strong></div>
<div>
<?php
$this->renderPartial('application.views.common._star',array('data'=>$data,'show_count'=>true));
$address = '';
if($data->street) { 
	$address .= $data->street;
}
 if($data->dom) { 
 	if($address)
 		$address .= ', ';
 	$address .= $data->dom; 
 }
?>
</div>
<div class="f-12 c-gray m-t-5"><?php echo $address; ?></div>
</a>