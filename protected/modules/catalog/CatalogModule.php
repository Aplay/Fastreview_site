<?php

class CatalogModule extends BaseModule
{
	public $moduleName = 'catalog';

	public function init()
	{
		$this->setImport(array(
			'catalog.models.*',
			'catalog.components.*'
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