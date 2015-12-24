<?php 
$currentUrl = Yii::app()->getRequest()->getHostInfo().Yii::app()->getRequest()->getUrl();
$this->widget('ext.widgets.Login', array('return_url' => $currentUrl)); 
?>
<div
  class="fb-like"
  data-share="true"
  data-width="450"
  data-show-faces="true">
</div>