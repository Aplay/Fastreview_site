<?php

/**
 * Display follow list
 *
 * @var $model Follow
 **/

Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/admin/follow.index.js');

$this->pageHeader = FollowModule::t('Followers');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	FollowModule::t('Followers'),
);

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider' => $dataProvider,
	'id'           => 'followListGrid',
	'filter'       => $model,
	'customActions'=>array(
		array(
			'label'=>FollowModule::t('Confirmed'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setFollowStatus(1, this);',
			)
		),
		array(
			'label'=>FollowModule::t('Waiting for approval'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setFollowStatus(0, this);',
			)
		),
		array(
			'label'=>FollowModule::t('Spam'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setFollowStatus(2, this);',
			)
		),
	),
	'columns' => array(
		array(
			'class'=>'CCheckBoxColumn',
		),
		array(
			'class'=>'SGridIdColumn',
			'name'=>'id',
		),
		array(
			'name'  => 'name',
			'type'  => 'raw',
			'value' => 'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
		),
		array(
			'name'=>'email',
		),
		array(
			'name'=>'text',
			'value'=>'Follow::truncate($data, 100)'
		),
		array(
			'name'=>'status',
			'filter'=>Follow::getStatuses(),
			'value'=>'$data->statusTitle',
		),
		array(
			'name'=>'owner_title',
                        'type'=>'raw',
			'value'=>'CHtml::link($data->getOwner_title(), $data->getViewUrl(), array("target"=>"_blank"))',
			'filter'=>false
		),
		'ip_address',
		array(
			'name'=>'created',
		),
		// Buttons
		array(
			'class'=>'CButtonColumn',
			//'template'=>'{update}{delete}',
			'template'=>'{update}',
		),
	),
));