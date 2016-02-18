<?php

/**
 * Create/update follow
 *
 * @var $model Follow
 */


$title = FollowModule::t('Edit followers');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	FollowModule::t('Followers')=>$this->createUrl('index'),
	CHtml::encode($model->email),
);

$this->pageHeader = $title;

?>

<div class="form wide padding-all">
	<?php echo $form; ?>
</div>