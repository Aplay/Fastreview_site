<?php

class UserController extends Controller {

	public $modules = '';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    public $user;
    public $city = null;

    /**
     * @return array action filters
     */
    public function filters() {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
        ));
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
  /*  public function accessRules() {
        return array(
            array('allow', // allow authenticated users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'viewProfile'),
                'users'=>array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    } */

   /*  public function beforeAction($action){

        
	        $domain = Yii::app()->request->getParam('city');

	        if($domain)
	            $this->city = City::model()->withUrl($domain)->find();

	        if(!$this->city) {
	        	
	            //	$this->redirect('http://'.Yii::app()->params['serverName']);
	        	
	        }
    	

        return true;
    }*/

    /**
     * Displays a particular model.
     */
    public function actionView($url) {

      //  if(Yii::app()->user->isGuest)
      //      Yii::app()->user->loginRequired();
      
    	$this->modules = 'userprofile';

      //  $this->layout = '//layouts/zazadun';
        $this->breadcrumbs = array('Account');

      /*  if($url == Yii::app()->user->username){
          $this->actionViewProfile();
          Yii::app()->end();
        }
        */

        $user = User::model()->active()->find(array('condition'=>'username=:username','params'=>array(':username'=>$url)));
        if(!$user){
           $user = User::model()->active()->find(array('condition'=>'LOWER(username)=:username','params'=>array(':username'=>MHelper::String()->toLower($url))));
          
        }
        if(!$user)
            throw new CHttpException(404, Yii::t('site','Page not found'));


        $this->pageTitle = Yii::app()->name. ' - ';;
        $this->pageTitle .= $user->fullname?$user->fullname:$user->username;
        
        $this->user = $user;

      /*  if(!$user->status == User::STATUS_DELETED){
            $this->render('accountdeleted');
            Yii::app()->end();
        } */

        // Load last comments
		// $comments = Comment::getLastComments(10, null, $user->id);
		// $lastImages = OrgsImages::getLastImages(10, null, $user->id);
		/* $comments = Comment::model()
			->with('obj')
			->approved()
			->orderByCreatedDesc()
			->findAll(array('condition'=>'t.id_parent is null and t.user_id='.$user->id,'limit'=>10)); 

		$sql = "SELECT uploaded_by, org, max(date_uploaded) as date FROM orgs_images
				LEFT OUTER JOIN orgs o
                ON (o.id=orgs_images.org) 
                WHERE orgs_images.uploaded_by = {$user->id}
			  	GROUP BY uploaded_by, cast(date_trunc('day',date_uploaded) as text), org
			  	ORDER BY date DESC
				LIMIT 10 ";
		$command = Yii::app()->db->createCommand($sql);
		$lastImages = $command->queryALL(); */
		$need_status = Objects::STATUS_ACTIVE;
		$need_status_c = Comment::STATUS_APPROVED;
		$count = "SELECT count(id) FROM
				(SELECT
				  CAST(CAST (1 AS TEXT) || CAST (T .id AS TEXT) AS NUMERIC (24, 0)) AS id, 
				  t.object_pk as org, t.name, t.text,  t.created as date,
				  t.yes, t.no,  t.rating, null as filename
				  FROM comments t
				  LEFT OUTER JOIN objects organizations 
				  ON (organizations.id = t.object_pk)
				  WHERE	T .status = {$need_status_c} AND  t.user_id = {$user->id} AND (organizations.status = {$need_status})
				  
				UNION
					(SELECT 
				  CAST (CAST (2 AS TEXT) || CAST (max(i.id) AS TEXT) AS NUMERIC (24, 0)) AS id, 
				  i.object, null, null, max(i.date_uploaded) as date,
				  null, null, null, 
				  array_to_string(array_agg(filename), ',') as filename
				  FROM objects_images i
				  LEFT OUTER JOIN objects organizations 
				  ON (organizations.id = i.object)
					WHERE i.uploaded_by =  {$user->id} and (organizations.status = {$need_status} )
				  GROUP BY  i.uploaded_by, cast(date_trunc('day',i.date_uploaded) as text),i.object
				  ORDER BY date DESC)
        UNION 
         (SELECT
         CAST (CAST (3 AS TEXT) || CAST (o.id AS TEXT) AS NUMERIC (24, 0)) AS id,
         o.id, null, null, o.created_date as date,
         null, null, null, null
         FROM objects o
         WHERE o.status={$need_status} and o.author = {$user->id}
         ORDER BY date DESC)
				) ss

					";
			$sql = "SELECT * FROM
				(SELECT
				  CAST(CAST (1 AS TEXT) || CAST (T .id AS TEXT) AS NUMERIC (24, 0)) AS id, 
				  t.object_pk as org, t.name, t.text,  t.created as date,
				  t.yes, t.no,  t.rating, null as filename
				  FROM comments t
				  LEFT OUTER JOIN objects organizations 
				  ON (organizations.id = t.object_pk)
				  WHERE	T .status = {$need_status_c} AND  t.user_id = {$user->id} AND (organizations.status = {$need_status} )
				  
				UNION
					(SELECT 
				  CAST (CAST (2 AS TEXT) || CAST (max(i.id) AS TEXT) AS NUMERIC (24, 0)) AS id, 
				  i.object, null, null, max(i.date_uploaded) as date,
				  null, null, null, 
				  array_to_string(array_agg(filename), ',') as filename
				  FROM objects_images i
				  LEFT OUTER JOIN objects organizations 
				  ON (organizations.id = i.object)
					WHERE i.uploaded_by =  {$user->id} and (organizations.status = {$need_status} )
				  GROUP BY  i.uploaded_by, cast(date_trunc('day',i.date_uploaded) as text),i.object
				  ORDER BY date DESC)
          UNION 
           (SELECT
           CAST (CAST (3 AS TEXT) || CAST (o.id AS TEXT) AS NUMERIC (24, 0)) AS id,
           o.id, null, null, o.created_date as date,
           null, null, null, null
           FROM objects o
           WHERE o.status={$need_status} and o.author = {$user->id}
           ORDER BY date DESC)
				) ss

					ORDER BY ss.date DESC";
		$total=Yii::app()->db->createCommand($count)->queryScalar();

		$provider = new CSqlDataProvider($sql, array(
            // Set id to false to not display model name in
            // sort and page params
            'totalItemCount'=>$total,
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));

		/*$command = Yii::app()->db->createCommand($sql);
		$blocks = $command->queryALL();
		$total = count($blocks);
		$pages = new CPagination($total);
        $pages->setPageSize(2);*/

        $this->render('view', array('user'=>$user, 'provider'=>$provider));
    }

    public function actionViewProfile()
    {
      $user = User::model()->findByPk(Yii::app()->user->id);
      if(!$user)
        throw new CHttpException(404, Yii::t('site','Page not found'));

      $this->layout = '//layouts/zazadun';
      $this->breadcrumbs = array('Profile');
      $this->pageTitle = Yii::app()->name. ' - ';;
      $this->pageTitle .= $user->fullname?$user->fullname:$user->username;

      $this->user = $user;

    //  $dataProviderContactList = $this->user->getContactList();


      $this->render('view');
    }
    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => 'status>' . User::STATUS_BANNED,
            ),

            'pagination' => array(
                'pageSize' => Yii::app()->getModule('users')->user_page_size,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }


}
