<?php

class FollowModule extends BaseModule
{
	/**
	 * @var string
	 */
	public $moduleName = 'follow';

	/**
	 * Init module
	 */
	public function init()
	{
		$this->setImport(array(
			'follow.models.Follow',
		));
	}

	/**
     * @param $str
     * @param $params
     * @param $dic
     * @return string
     */
    public static function t($str = '', $params = array(), $dic = 't') {
        if (Yii::t("FollowModule", $str) == $str)
            return Yii::t("FollowModule." . $dic, $str, $params);
        else
            return Yii::t("FollowModule", $str, $params);
    }
	/**
	 * @param $model
	 * @return Follow
	 */
	public function processRequest($model)
	{
            Yii::import('application.modules.users.models.User');
            Yii::import('application.modules.catalog.models.Objects');
		$follow = new Follow;
		if((Yii::app()->request->isPostRequest) && (Yii::app()->request->getPost('Follow')))
		{
			$follow->attributes = Yii::app()->request->getPost('Follow');

			if(!Yii::app()->user->isGuest)
			{
				$follow->name = Yii::app()->user->username;
				$follow->email = Yii::app()->user->email;
			}

			if($follow->validate())
			{
				$pkAttr = $model->getObjectPkAttribute();
				$follow->class_name = $model->getClassName();
				$follow->object_pk = $model->$pkAttr;
				$follow->user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
				$follow->save();

				$url = Yii::app()->getRequest()->getUrl();

				if($follow->status==Follow::STATUS_WAITING)
				{
					$url.='#';
					Yii::app()->user->setFlash('messages', 'Успешно подписаны');
                                        
                                        
				}
				elseif($follow->status==Follow::STATUS_APPROVED)
					$url.='#follow_'.$follow->id;

				// Refresh page
				Yii::app()->request->redirect($url, true);
			}
		}
		return $follow;
	}
        
        public $urlRules = array(
	'/admin/follow'=>'/follow/admin/follow',
	'/admin/follow/<action>'=>'/follow/admin/follow/<action>',

        );
}