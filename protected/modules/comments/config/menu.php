<?php

/**
 * @var $this SSystemMenu
 */

Yii::import('comments.CommentsModule');

/**
 * Admin menu items for pages module
 */
return array(
	'cms'=>array(
		'items'=>array(
			array(
				'label'=>Yii::t('CommentsModule.core', 'Комментарии'),
				'url'=>array('/admin/comments/comments'),
				'position'=>3,
                               // 'itemOptions' => array(
                               //  'class'       => 'hasRedCircle circle-comments',
                               // ),
			),
		)
	),
);