<?php 
$currentUrl = Yii::app()->getRequest()->getHostInfo().Yii::app()->getRequest()->getUrl();
$this->widget('ext.widgets.Login', array('return_url' => $currentUrl)); 
?>
