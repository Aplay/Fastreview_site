<?php

class CityModule extends BaseModule
{
	public $moduleName = 'city';

	public function init()
	{
		$this->setImport(array(
			'city.models.*',
			'city.components.*'
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