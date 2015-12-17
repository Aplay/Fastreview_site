<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name;
$themeUrl = Yii::app()->theme->baseUrl;
?>
<div style="min-height: 400px">
<div class="form-group">
<?php $this->renderPartial('application.views.common._flashMessage'); ?>
</div>
<?php if(Yii::app()->user->isGuest) { ?>
<?php $this->widget('ext.widgets.Login', array('return_url' => Yii::app()->createAbsoluteUrl('/login'))); ?>
<?php } ?>
</div>

<?php
$scriptDd = "
$(function(){

$('.lc-block').removeClass('toggled');
$('#l-login').addClass('toggled');
$('#login_modal').modal();

})";
Yii::app()->clientScript->registerScript("scriptr", $scriptDd, CClientScript::POS_END);
?>