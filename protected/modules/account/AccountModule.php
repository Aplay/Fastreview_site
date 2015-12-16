<?php

class AccountModule extends CWebModule
{
	public $moduleName = 'account';

	public function init()
	{
		$this->setImport(array(
			'account.models.*',
			'account.components.*',
		));
	}


	
}
?>