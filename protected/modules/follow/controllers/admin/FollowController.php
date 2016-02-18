<?php

/**
 * Admin site follow
 */
class FollowController extends SAdminController
{

	/**
	 * Display all site follow
	 */
	public function actionIndex()
	{
		$model = new Follow('search');

		if(!empty($_GET['Follow']))
			$model->attributes = $_GET['Follow'];

		$dataProvider = $model->search();
		$dataProvider->pagination->pageSize = Yii::app()->settings->get('core', 'productsPerPageAdmin');

		$this->render('index', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider
		));
	}

	/**
	 * Update follow
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionUpdate($id)
	{
		$model = Follow::model()->findByPk($id);

		if(!$model)
			throw new CHttpException(404, FollowModule::t('Page not found'));

		$form = new CForm('follow.views.admin.follow.followForm', $model);

		if (Yii::app()->request->isPostRequest)
		{
			$model->attributes = $_POST['Follow'];
			if($model->validate())
			{
				$model->save();

				$this->setFlashMessage(FollowModule::t('Changes saved successfully'));

				if (isset($_POST['REDIRECT']))
					$this->smartRedirect($model);
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model' => $model,
			'form'  => $form
		));
	}

	public function actionUpdateStatus()
	{
		$ids       = Yii::app()->request->getPost('ids');
		$status    = Yii::app()->request->getPost('status');
		$models    = Follow::model()->findAllByPk($ids);

		if(!array_key_exists($status, Follow::getStatuses()))
			throw new CHttpException(404, FollowModule::t('Ошибка'));

		if(!empty($models))
		{
			foreach ($models as $follow)
			{
				$follow->status = $status;
				$follow->save();
			}
		}

		echo FollowModule::t('Status changed successfully');
	}

	/**
	 * Delete follows
	 * @param array $id
	 */
	public function actionDelete($id = array())
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = Follow::model()->findAllByPk($_REQUEST['id']);

			if (!empty($model))
			{

				foreach($model as $m){
					$m->delete();
				}
				echo 'Successfully';
			}

			if (!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
		}
	}

}
