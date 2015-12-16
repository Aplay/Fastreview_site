<?php

/**
 * Logger controller
 */
class DefaultController extends SAdminController
{

	public $layout = '//layouts/admin';
    public $pageTitle = 'Журнал изменений';
    public $active_link = 'logger';

    /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			 'ajaxOnly + delete'
		);
	}

	public function actionIndex()
	{
		$model = new ActionLog('search');

		if (!empty($_GET['ActionLog']))
			$model->attributes = $_GET['ActionLog'];

		$dataProvider = $model->search();
		$dataProvider->pagination->pageSize = 50;

		$this->render('index', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Delete products
	 */
	public function actionDelete($id = array())
	{

			$model = ActionLog::model()->findAllByPk($_REQUEST['id']);

			if (!empty($model))
			{
				foreach($model as $page)
					$page->delete();
			}

			if (!Yii::app()->request->isAjaxRequest){
				$this->redirect('index');
			} else {
				echo '[]';
				Yii::app()->end();
			}
	}

	public function actionAddData()
	{
		die();
		$settings = 0;
		if(isset($_REQUEST['Settings']))
			$settings = $_REQUEST['Settings'];

		$criteria=new CDbCriteria;
		$criteria->limit = 1000;
		$criteria->offset = $settings;
		$criteria->order = 'id';

		$orgs = Orgs::model()->findAll($criteria);
		$cnt = 0;
		if($orgs){
			foreach($orgs as $org){

				/* $logcheck = ActionLog::model()->find(array('condition'=>'model_name=:model_name and model_id=:model_id and event=:event','params'=>array(':model_name'=>'Orgs',':model_id'=>$org->id,':event'=>ActionLog::ACTION_CREATE)));
				if(!$logcheck){
					$log = new ActionLog;
					$log->event = ActionLog::ACTION_CREATE;
					$log->user_id = $org->author;
			        $log->model_name = 'Orgs';
			       // $log->model_title = $org->title;
			        $log->datetime = $org->created_date;
			        $log->model_id = $org->id;
			        if($log->save()){
			        	$cnt++;
			        } 
		    	} */
		    /*	$lastUpdateDate = ActionLog::model()->find(array('condition'=>'model_name=:model_name and model_id=:model_id and (event=:event or event=:event2)','params'=>array(':model_name'=>'Orgs',':model_id'=>$org->id,':event'=>ActionLog::ACTION_CREATE, ':event2'=>ActionLog::ACTION_UPDATE),'order'=>'datetime DESC'));
				if($lastUpdateDate){
	        		$now = strtotime($org->updated_date); 
				    $last_date = strtotime($lastUpdateDate->datetime);
				    if($now > $last_date){
					    $datediff = $now - $last_date;
					    if(floor($datediff/(60*60*24)) > 30 ){ // more 30 days left
					    	$log = new ActionLog;
							$log->event = ActionLog::ACTION_UPDATE;
							$log->user_id = $org->lasteditor?$org->lasteditor:$org->author;
					        $log->model_name = 'Orgs';
					       // $log->model_title = $org->title;
					        $log->datetime = $org->updated_date;
					        $log->model_id = $org->id;
					        if($log->save()){
					        	$cnt++;
					        } 
					    }
					}
	        	} */
			}
		}
		echo 'Добавлено '.$cnt;
		$sleeper = 2;
		$settings += 1000;
		if($settings < 23300){
	    	  echo '<META HTTP-EQUIV=Refresh CONTENT="'.$sleeper.'; URL='.Yii::app()->createUrl("/logger/admin/default/adddata",array('Settings'=>$settings)).'">';
	    	  exit;
		}
	}

	public function actionAddDataCat()
	{
		die();
		$settings = 0;
		if(isset($_REQUEST['Settings']))
			$settings = $_REQUEST['Settings'];

		$criteria=new CDbCriteria;
		$criteria->limit = 1000;
		$criteria->offset = $settings;
		$criteria->order = 'id';

		$orgs = Category::model()->findAll($criteria);
		$cnt = 0;
		if($orgs){
			foreach($orgs as $org){

				/* $logcheck = ActionLog::model()->find(array('condition'=>'model_name=:model_name and model_id=:model_id and event=:event','params'=>array(':model_name'=>'Category',':model_id'=>$org->id,':event'=>ActionLog::ACTION_CREATE)));
				if(!$logcheck){
					$log = new ActionLog;
					$log->event = ActionLog::ACTION_CREATE;
					$log->user_id = $org->author_id;
			        $log->model_name = 'Category';
			       // $log->model_title = $org->title;
			        $log->datetime = $org->created_date;
			        $log->model_id = $org->id;
			        if($log->save()){
			        	$cnt++;
			        } 
		    	} */
		    	/* $lastUpdateDate = ActionLog::model()->find(array('condition'=>'model_name=:model_name and model_id=:model_id and (event=:event or event=:event2)','params'=>array(':model_name'=>'Category',':model_id'=>$org->id,':event'=>ActionLog::ACTION_CREATE, ':event2'=>ActionLog::ACTION_UPDATE),'order'=>'datetime DESC'));
				if($lastUpdateDate){
	        		$now = strtotime($org->updated_date); 
				    $last_date = strtotime($lastUpdateDate->datetime);
				    if($now > $last_date){
					    $datediff = $now - $last_date;
					    if(floor($datediff/(60*60*24)) > 30 ){ // more 30 days left
					    	$log = new ActionLog;
							$log->event = ActionLog::ACTION_UPDATE;
							$log->user_id = $org->author_id;
					        $log->model_name = 'Category';
					       // $log->model_title = $org->title;
					        $log->datetime = $org->updated_date;
					        $log->model_id = $org->id;
					        if($log->save()){
					        	$cnt++;
					        } 
					    }
					}
	        	} */
			}
		}
		echo 'Добавлено '.$cnt;
		
	}

}
