<?php

class FileModule extends BaseModule
{
	public $moduleName = 'file';

	public function init()
	{
		$this->setImport(array(
			'file.models.*',
			'file.components.*',
			'wherefind.models.*',
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
?>