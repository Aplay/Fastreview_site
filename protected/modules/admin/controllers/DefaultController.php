<?php
class DefaultController extends SAdminController {

	public $layout = '//layouts/admin';
    public $pageTitle = 'Дашбоард';


	/* public function allowedActions()
	{
		return 'index'; // allow all registered users
	}*/
    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
            'rights'
        ));
    }

    public function actionIndex() {

    	$this->active_link = 'dashboard';
    	//count users
    	$users = User::model()->count('status != '.User::STATUS_DELETED);
        $objects = Objects::model()->active()->count(array('select'=>'id'));

        $this->render('dashboard',array('users'=>$users,'objects'=>$objects,

        ));
    }

    
 
}