<?php
class AdminModule extends BaseModule {
    
    public $moduleName = 'admin';


    public function init() {
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));

      //  Yii::app()->getComponent('bootstrap');
    }

    /**
     * Init admin-level models, componentes, etc...
     */
    public function initAdmin()
    {
        //Yii::trace('Init users module admin resources.');
        parent::initAdmin();
    }
/*
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {

            if (!Yii::app()->user->isGuest) {
              
                if (!Yii::app()->user->checkAccess('Admin')) {
                    Yii::app()->user->logout();
                    $model->addError('role',Yii::t('error','Access denied'));
                    $this->redirect(Yii::app()->createUrl('/'.Yii::app()->params['adminPath']));
                    return false;
                } 
            }
            return true;
        } else
            return false;
    }*/
}