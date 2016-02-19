<?php
class ArticleController extends Controller
{
	// public $layout = '//layouts/article';
	public $breadcrumbs = array();

    public $city;

    public $query;
    public $currentQuery;
    public $provider;
    public $allmodel;
    public $main_page;
    public $jobSpecModel;
    public $jobSpecTitle;
    public $modules = 'article';
    public $uploadsession = 'articlefiles';
    public $uploadlogosession = 'articlelogofiles';
    public $action_title = '';


    public $allowedPageLimit = array();

    public function filters()
    {
     return array(
        'ajaxOnly + starrating',
        );
    }
    
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'MyCCaptchaAction',
                'backColor' => 0xFFFFFF,
                'minLength'=>4,
                'maxLength'=>5,
                'height'=>35,
                'foreColor'=>0x999999, //цвет символов,
                'testLimit'=>0,
               // 'fontFile'=>'./font/arial.ttf'
               // 'backend'=>Yii::app()->params['captchaRender']
            ),

        );
    }

	 
	public function actionIndex()
    {
       // $this->layout = '//layouts/zazadun';

          $this->pageTitle = 'Обзоры - '.Yii::app()->name;
          $popular = Article::model()->fullactive()->findAll(array('limit'=>3,'order'=>'views_count DESC')); 

 
         $criteria = new CDbCriteria;
         $criteria->with = array(
         	'images'
         	);
         $criteria->condition = 't.part is null';
         $dataProvider = new CActiveDataProvider(Article::model()->fullactive(), array(
                'criteria' => $criteria,
                'sort'=>array(
                    'defaultOrder' => 't.created_date DESC',
                ),
                'pagination' => array(
                    'pageSize' => 36,
                    'pageVar'=>'page',
                ),
            ));
         if(Yii::app()->request->isAjaxRequest)
         {
         	$this->renderPartial('_ajax_index', array(
            'dataProvider'=>$dataProvider
            ));
         } 
         else 
         {
         	$this->render('view', array(
            'showother'=>false,
            'dataProvider'=>$dataProvider,
            'popular'=>$popular
            ));
         }
           
        
    }
    
    public function actionNew($obj)
    {
      $this->actionUpdate($obj);
    }
    public function actionUpdate($obj, $id=null)
    {
    //  $this->layout = '//layouts/new_item';
      $object = Objects::model()->active()->findByPk($obj);
      if(!$object)
        throw new CHttpException(404, 'Страница не найдена.');

      $model = null;
      if($id)
      {
        $new = false;
        $this->action_title = 'РЕДАКТИРОВАТЬ СТАТЬЮ';
        $this->pageTitle = 'Редактировать обзор';
        if(Yii::app()->user->isGuest){
           $this->redirect(Yii::app()->createAbsoluteUrl('/catalog/article/new',array('obj'=>$object->id)));
           Yii::app()->end();
        }
        $model = Article::model()->findByPk($id,'author='.Yii::app()->user->id);
        if(!$model)
          $this->redirect(Yii::app()->createAbsoluteUrl('/catalog/article/new',array('obj'=>$object->id)));
      } 
      if(!$model)
      {
        $new = true;
        $this->action_title = 'ДОБАВИТЬ СТАТЬЮ';
        $this->pageTitle = 'Добавление нового обзора - '.Yii::app()->name;
        $model = new Article;
      } 
    if(isset($_POST['Article']))
    {
      $model->attributes = $_POST['Article'];
      $model->object_id = $object->id;
      
      
      /*
      if(!$model->categories_ar || !is_array($model->categories_ar) || (isset($model->categories_ar[0]) && empty($model->categories_ar[0]))){
        $model->categories_ar = array();
        $model->categorie = null;
      } else {
        $model->categorie = 1;
      }*/

      // Uncomment the following line if AJAX validation is needed
    if(isset($_POST['ajax']) && $_POST['ajax']==='article-form')
    {
      
      $errors = CActiveForm::validate($model);
      
      if ($errors !== '[]') {
             $errors = CJSON::decode($errors);
             echo CJSON::encode(array('success'=>false, 'message'=>$errors));
             Yii::app()->end();
      } 

    }
    if(isset($_POST['preview']) && $_POST['preview'] == 1){

            $message = $this->renderPartial('_article_listview_preview',array('data'=>$model),true);
            echo CJSON::encode(array(
                'flag' => true,
                'preview' => true,
                'message'=>$message
            ));
            
            Yii::app()->end();
        }
    
      $model->status_org = Article::STATUS_ACTIVE;
      $model->verified = false;
      $model->mywork = false;
      if(isset($_POST['Article']['mywork'])){
        $model->mywork = true;
      }
     


      if($model->save()){
       // $model->setCategories($model->categories_ar);
        
        $model->addDropboxLogoFiles($this->uploadlogosession);
        Yii::app()->session->remove($this->uploadlogosession);


        $article_url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$object->id, 'dash'=>'-', 'itemurl'=>$object->url));
        if(Yii::app()->request->isAjaxRequest){
          
            echo CJSON::encode(array('success'=>true, 'message'=>array('url'=>$article_url)));
            Yii::app()->end();
        } else {
            $this->redirect($article_url);
        }
      } 
    }

     /* $categories_ar  = array();
        $categories = $model->categories;
        if($categories){
            foreach($categories as $cats){
                $categories_ar[] = $cats->id;
            }
        }*/

      $this->render('_form', array(
        'model'=>$model,
        'object'=>$object
       // 'categories_ar'=>$categories_ar
        ));
    }


    public function actionSearch()
    {
    //	$this->layout = '//layouts/zazadun';
      if(Yii::app()->request->isPostRequest  && Yii::app()->request->getPost('q') && Yii::app()->request->getPost('pereparam')){
        $this->redirect(Yii::app()->request->addUrlParam('catalog/article/search', array('q'=>Yii::app()->request->getPost('q'))));
      }
      $model = new Article;
        $count_items = 0;
        if (($term = Yii::app()->getRequest()->getQuery('q')) !== null) {
            $this->pageTitle = CHtml::encode($term);
            $query = $term;

            $s = MHelper::String()->toLower(trim($term));
            $resultsPr = null;
            if(!empty($s)){
            $s = addcslashes($s, '%_'); // escape LIKE's special characters

             $criteria = new CDbCriteria;
            $criteria->scopes='fullactive';


            $criteria->condition ='(( (LOWER(t.title) LIKE :s) or (LOWER(t.description) LIKE :s) ))';
            $criteria->params = array(':s'=>"%$s%");
            $dataProvider = new CActiveDataProvider('Article', array(
                'criteria' => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.created_date DESC',
                ),
                'pagination' => array(
                    'pageSize' => 25,
                    'pageVar'=>'page'
                ),
            ));
            
          //  VarDumper::dump($dataProvider); die(); // Ctrl + X  Delete line
          }
          $count_items = 0;
          if ($dataProvider && $dataProvider->totalItemCount) { 
            $count_items = $dataProvider->totalItemCount;
          } 

          if(!empty($term) && !isset($_GET['page'])){
            $searchquery = new Searchqueries;
            $searchquery->query = $term;
            $searchquery->quantity = $count_items;
            $searchquery->ip_address = MHelper::Ip()->getIp();
            $searchquery->save();

          }
          $dtitle = $query.' ';
          $this->render('search', compact('term', 'query','dataProvider','count_items','model','dtitle'));
        } else {
          $term = $query = '';
          $dtitle = $query;
          $dataProvider = null;
          $this->render('search', compact('term', 'query','dataProvider','count_items','model','dtitle'));
        }
        
    }

    public function actionView()
    {
        $model = $this->_loadModel(Yii::app()->request->getQuery('url'));
        $this->model = $model;

        $this->pageTitle = $model->title.' - '.Yii::app()->name;

        $this->breadcrumbs['Статьи'] = Yii::app()->createAbsoluteUrl('/catalog/article/index');
        $this->breadcrumbs[] = $model->title;

        $popular = Article::model()->fullactive()->findAll(array('limit'=>3,'order'=>'views_count DESC')); 

        $query = Article::model()->fullactive()->cache(3600);
        $query->applyCategoriesWithSub($model);

        $dataProvider = new CActiveDataProvider($query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>array(
                'pageSize'=>25,
            )
        ));

        $dataProvider->sort = Article::getCSort('t.created_date DESC, t.title');

        $this->render('view',array(
            'model'=>$model,
            'dataProvider'=>$dataProvider,
            'popular'=>$popular
        ));
    }
     public function getSimilarObj($model,$orgsCats)
    {

        $query = new Article(null);
        $query->fullactive()
                ->with(array(
                    'images',
                    ));
        // все категории организации
        $query->applyCategoriesWithSub($orgsCats);

        $cr=new CDbCriteria;
        $cr->distinct = true; // предотвращает повтор объявлений на странице
        $cr->order = 't.views_count DESC';
        $cr->addCondition('t.id!='.$model->id);
        
        $cr->limit = 5;  
        $query->getDbCriteria()->mergeWith($cr);
        $nearProvider = Article::model()->fullactive()->findAll($cr);
      /*  $nearProvider = new CActiveDataProvider($query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>false
        ));*/
        return $nearProvider;
    }
     /**
     * Просмотр записи
     *
     * @sitemap dataSource=getSitemapRecordUrl priority=0.8 changefreq=daily
     * @param $itemurl
     * @throws CHttpException
     */
    public function actionItem($id=null,$itemurl=null){

    //  $this->layout = '//layouts/article_item';	
      $model = $this->_loadItem($id, $itemurl);
  
     // $this->model = $model;

      $this->breadcrumbs['Обзоры'] = Yii::app()->createAbsoluteUrl('/catalog/article/index');

      $orgsCats = array();
      $ancestors = $categorie = null;
      $name = '';
     /* $categories = $model->categories;
      if($categories){
        foreach ($categories as $cat){
          $orgsCats[] = $cat->id;
        }
          $categorie = $categories[0]->id;
          $ancestors = $categories[0]->ancestors()->findAll();
          $name = $categories[0]->title;
          if($ancestors)
          {
              foreach($ancestors as $c)
              {
                  $this->breadcrumbs[$c->title] = $c->getViewUrl();
              }
          } 
          $this->breadcrumbs[$name] = $categories[0]->getViewUrl();
      } */
      
      $similar = $popular = null;
      $similar = $this->getSimilarObj($model,$orgsCats);
      if($similar)
        $popular = array_slice($similar, 0, 2);
      
     // $popular = Article::model()->active()->findAll(array('limit'=>3,'order'=>'views_count DESC')); 
      
      
      $this->pageTitle = $model->title.' - '.Yii::app()->name;

   

    if(!empty($_POST['CommentArticle'])){
        Yii::import('application.modules.comments.CommentsModule');
        Yii::import('application.modules.comments.models.CommentArticle');
        $comment = new CommentArticle;
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-create-form')
        {
          $comment->attributes = Yii::app()->request->getPost('CommentArticle');
          $comment->status = CommentArticle::STATUS_APPROVED;
          if(!Yii::app()->user->isGuest)
          {
            $comment->name = Yii::app()->user->getShowname();
            $comment->email = Yii::app()->user->email;
          }
            $errors = CActiveForm::validate($comment);
            
            if ($errors !== '[]') {
               echo $errors;
            } else {
              // Load module
              $module = Yii::app()->getModule('comments');
              // Validate and save comment on post request
              $comment = $module->processRequestArticle($model);
               echo '[]';
            }
            Yii::app()->end();
        }
      }
      
      $this->render('item', array(
                 'model' => $model,
                 'popular'=>$popular,
                 'similar'=>$similar
                 ));
    }

    /**
     * @return array массив ссылок записей для генерации карты сайты
     */
    public function getSitemapRecordUrl()
    {
    
        $map = array();

        $model = Article::model()
        ->fullactive()
        ->findAll();

        if ($model) {
            foreach ($model as $record) {
             
                $map[] = array(
                    'route' => '/catalog/article/item',
                    'params' => array(
                    	'dash'=>'-',
                        'id'=>$record->id,
                        'itemurl'=>$record->url,
                    ),
                    'priority' => 0.8,
                    'changefreq' => 'daily',
                    'lastmod' => date('Y-m-d', strtotime($record->updated_date)),
                );
              
            }
        }

        return $map;
    }

    public function _loadModel($url)
    {
        // Find category
        $model = CategoryArticle::model()
                          ->withUrl($url)
                          ->find();
        if (!$model) throw new CHttpException(404, 'Категория не найдена.');
                
        return $model;
    }

  protected function _loadItem($id=null, $url)
  {
       // $id = null;
       // $expl = explode('-',$url,2);
       // if(!empty($expl) && is_int($expl[0]))
  		if(!$id && !$url)
  			throw new CHttpException(404, 'Страница не найдена.');

  		if($id)
        {
        	// $id = $expl[0];
        	$model = Article::model()
		        ->fullactive()
		        ->with('images')
		        ->findByPk($id);

        }
        else 
        {
        	$model = Article::model()
		        ->fullactive()
		        ->with('images')
		        ->withUrl($url)
		        ->find(array(
		        'limit'=>1
		       ));
        }	
        
        if (!$model)
         throw new CHttpException(404, 'Страница не найдена.');

		if(!$id || $model->url != $url)
    	{

    	$red = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('dash'=>'-','id'=>$model->id,'itemurl'=>$model->url));
    	$this->redirect($red, TRUE, 301);
    	}

     $model->saveCounters(array('views_count'=>1));

    return $model;
}
    
}
?>