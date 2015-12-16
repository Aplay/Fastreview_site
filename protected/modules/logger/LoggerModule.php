<?php

/**
 * Record user actions in admin panel
 */
class LoggerModule extends BaseModule
{
	public $moduleName = 'logger';

	public function init()
	{
		$this->setImport(array(
			'logger.models.*',
			'logger.components.*'
		));
	}

	/**
	 * Init admin-level models, componentes, etc...
	 */
	public function initAdmin()
	{
		//Yii::trace('Init users module admin resources.');
		parent::initAdmin();
	}
}
