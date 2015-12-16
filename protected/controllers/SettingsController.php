<?php
class SettingsController extends SController {


    public function actionIndex() {
    	$this->pageTitle .= ' - Настройки';
    	$this->active_link = 'settings';
    	$this->render('index');
    }
}
?>