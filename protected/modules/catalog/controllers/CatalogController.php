<?php
class CatalogController extends Controller
{
	public $layout = '//layouts/column1';
	public $breadcrumbs = array();
   // public $mmenu = array();
    public $city = null;
   // public $city_id;
   // public $city_rodpad;
   // public $city_im;
   // public $city_title;
    public $query;
    public $currentQuery;
    public $provider;
    public $allmodel;
    public $main_page;
   // public $city_lat;
   // public $city_lng;
   // public $city_utc;
   // public $city_file;
   // public $city_mestpad;

    public $allowedPageLimit = array();
    public $modules = 'catalog';
    public $uploadsession = 'orgsfiles';

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
   
   /* public function accessRules() {
        return array('allow', 'actions' => array('captcha'), 'users' => array('*'));
    }*/
 /*   public function allowedActions()
  {
    return 'index, captcha, view, item';
  }*/
	 /**
	 * Check if user is authenticated
	 * @return bool
	 * @throws CHttpException
	 */

    public function beforeAction($action){

      //  $this->city = Yii::app()->getRequest()->getQuery('city');
     /*   if(!$this->city){
            $this->redirectIt();
        } */
       // $cities_db = City::model()->findAll(array('condition'=>'pos>0','order'=>'pos ASC'));
        
        // чтобы старые ссылки работали

       /* $domainOld = Yii::app()->request->getQuery('cityold');
        if($domainOld){
            $pathInfo = Yii::app()->request->pathInfo;
            $pos = strpos($pathInfo, $domainOld);
            $str = '';
            if($pos!==false) {
              $str = substr_replace($pathInfo, '', $pos, strlen($domainOld));
            }  
            $this->redirect('http://'.$domainOld.'.'.Yii::app()->params['serverName'].$str);
        } */

        if($action->id != 'itemnocity')
        {
	        $domain = Yii::app()->request->getParam('city');

	        if($domain)
	            $this->city = City::model()->withUrl($domain)->find();

	        if(!$this->city) {
	        	
	            	$this->redirect('http://'.Yii::app()->params['serverName']);

	        }
    	}

        return true;
    }
	public function actionIndex()
    {
        
        $this->main_page = true;
        
        if(!isset($_GET['city'])){
          $this->pageTitle = 'Каталог организаций России — Зазадун';
          $cr = new CDbCriteria;
         /* $cr->with = array('cityOther'=>array(
	            'condition'=>'(hh_city_id is not null or sj_city_id is not null)'
	            )
	        );
	        $cr->together = true; */
	        $cr->condition = '(pos>0 and pos<10000)';
	        $cr->order = 'title';
          	$cities = City::model()->cache(4000)->findAll($cr);
          	$this->layout = '//layouts/main';

           $this->render('main', array('cities'=>$cities));
        } else {
          $this->layout = '//layouts/zazadun';
          $this->pageTitle = 'Справочник-каталог — '.$this->city->rodpad;
           $roots = $cats = array();
          // $roots = Category::model()->cache(4000)->active()->findAll(array('condition'=>'lft=1', 'order'=>'t.title'));
    	     
          /* $criteria = new CDbCriteria;
           $criteria->select = 'category';
           $criteria->distinct = true;
           $criteria->condition = 'city_id='.$this->city->id .' and status_org=1';
           $cats = OrgsCategory::model()->findAll($criteria); */

           $cats =  Category::getRubsByParentId($this->city->id);
           $criteria = new CDbCriteria;
         $criteria->with = array(
         	'city'=>array(
	         	'condition'=>'city.id='.$this->city->id
	         	),
         	'images'
         	);
         $criteria->limit = 5;
         $criteria->condition = 't.part is null';
         $dataProvider = new CActiveDataProvider(Article::model()->active(), array(
                'criteria' => $criteria,
                'sort'=>array(
                    'defaultOrder' => 't.created_date DESC',
                ),
                'pagination' => false,
            ));
         // Load last comments
		 $comments = Comment::getLastComments(5, $this->city->id);
		 $lastImages = ObjectsImages::getLastImages(5, $this->city->id);
         $this->beginClip('cityheader');
         $this->renderPartial('_city_header_no_slider'); 
		 $this->endClip();


           $this->render('index', array(
            'roots'=>$roots,
            'cats'=>$cats,
            'dataProvider'=>$dataProvider,
            'comments'=>$comments,
            'lastImages'=>$lastImages
            ));
        }
    }
    public function actionDistrict()
    {
    	$this->layout = '//layouts/zazadun';
    	$model = $this->_loadModel(Yii::app()->request->getQuery('url'));
    	$district = Yii::app()->request->getQuery('district');

    	$this->pageTitle = $model->title;
        if($this->city->id)
        {
          $this->pageTitle .= ' в '.$this->city->mestpad;
        }
        $ancestors = $model->ancestors()->findAll();
	    $name = $model->title;
	    if($ancestors)
	    {
	        foreach($ancestors as $c)
	        {
	            $this->breadcrumbs[$c->title] = $c->getViewUrl($this->city->url);
	        }
	    }
	    $this->breadcrumbs[] = $name;


        $this->render('_'.$district, array(
            'model' => $model,
            'allmodel'=>array(),
            'ancestors'=>$ancestors,
            'district'=>$district
        ));
                

    }

    public function actionDistrictView()
    {
    	 $this->layout = '//layouts/zazadun';

    	$model = $this->_loadModel(Yii::app()->request->getQuery('url'));
    	$district = Yii::app()->request->getQuery('district');
    	if($district != 'metro')
    	{
    		$district_model = $this->_loadDistrict(Yii::app()->request->getQuery('district_url'));
    		$dtitle = $district_model->district_name;
    	}
    	elseif($district == 'metro')
    	{
    		$district_model = $this->_loadMetro(Yii::app()->request->getQuery('district_url'));
    		$dtitle = $district_model->metro_name;
    	}
    		
    	$this->pageTitle = $model->title;
        if($this->city->id)
        {
          $this->pageTitle .= ' в '.$this->city->mestpad.', '.$dtitle;
        }
        $ancestors = $model->ancestors()->findAll();
	    $name = $model->title;
	    if($ancestors)
	    {
	        foreach($ancestors as $c)
	        {
	            $this->breadcrumbs[$c->title] = $c->getViewUrl($this->city->url);
	        }
	    }
	    $this->breadcrumbs[] = $name;

	    $this->query = new Objects(null);

        $this->query->pureactive()
                ->with(array(
                    'images',
                    'city',
                    ));
          
        $this->query->applyCategoriesWithSub($model);

        $cr=new CDbCriteria;
        $cr->distinct = true; // предотвращает повтор объявлений на странице

        if($district != 'metro' && $this->city->id)
        {
        	$cr->join = 'LEFT JOIN "orgs_district" "orgsdistrict" ON ("orgsdistrict"."org"="t"."id")';
        	$cr->addCondition('city.id='.$this->city->id.' and orgsdistrict.district='.$district_model->id);
        	// $cr->addCondition('orgsdistrict.district='$district_model->id);
        }
        elseif($district == 'metro' && $this->city->id)
        {
        	$cr->addCondition('city.id='.$this->city->id.' and t.nearest_metro='.$district_model->id);
        }
          
        $this->query->getDbCriteria()->mergeWith($cr);

        $this->currentQuery = clone $this->query->getDbCriteria();
        $this->provider = new CActiveDataProvider($this->query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>array(
                'pageSize'=>25,
            )
        ));

        $this->provider->sort = Orgs::getCSort('t.title, t.created_date DESC');
        $itemView = '_organizations_view';
        $count_items = $this->provider->totalItemCount; 
        $this->render('view', array(
            'provider'=>$this->provider,
            'model' => $model,
            'allmodel'=>array(),
            'itemView'=>$itemView,
            'per_page'=>25,
            'ancestors'=>$ancestors,
            'dtitle'=>$dtitle,
            'count_items'=>$count_items
        ));
                

    }
     public function actionLegal()
    {
    	 $this->pageTitle = 'Пользовательское соглашение';
    	 $this->layout = '//layouts/zazadun';
    	 $this->render('legal');

    }
      public function actionPrivacy()
    {
    	 $this->pageTitle = 'Политика конфеденциальности';
    	 $this->layout = '//layouts/zazadun';
    	 $this->render('privacy');

    }
    public function actionView()
    {
                
               $this->layout = '//layouts/zazadun';

                $this->allowedPageLimit = array(25,50,100,200);

                $model = $this->_loadModel(Yii::app()->request->getQuery('url'));

                $this->pageTitle = $model->title;
                if($this->city->id){
                  $this->pageTitle .= ' в '.$this->city->mestpad;
                }
                
                if(is_array($model))
                {
                    $modelFirst = $this->lastmodel;
                } else {
                    $modelFirst = $model;
                }
                // Create breadcrumbs
                    $ancestors = $modelFirst->ancestors()->findAll();
                    $name = $modelFirst->title;
                if($ancestors)
                {
                    foreach($ancestors as $c)
                    {
                        $this->breadcrumbs[$c->title] = $c->getViewUrl($this->city->url);
                    }
                }
                
                $this->breadcrumbs[] = $name;
                 
                $this->doSearch($model, $modelFirst, 'view', $ancestors);
    }

   /**
     * Search products
     * @param $data Categories|string
     * @param string $view
     */
      public function doSearch($data, $model, $view, $ancestors = '')
    {
        $this->query = new Orgs(null);
      //  $this->query->attachBehaviors($this->query->behaviors());
                $this->query->pureactive()
                        ->with(
                            'images',
                            'city'
                                 //   array(
                                  //   'account'=>array(
                                  //       'select'=>'id'
                                   //  ))
                                );
                  
                 $allmodel = array();
                if(is_array($data)){
                    $allmodel = $data;
                   foreach($data as $c){
                        $cat[] = $c->id;
                    }
                    $categories = $cat;
                    
                    $this->query->applyCategoriesWithSub($categories);
                   
                    $cr=new CDbCriteria;
                    $cr->distinct = true; // предотвращает повтор объявлений на странице
                    $this->query->getDbCriteria()->mergeWith($cr);
                    
                } elseif($data instanceof Category) {
 
                    //$this->query->applyCategories($model);

                    $this->query->applyCategoriesWithSub($model);
                  //  VarDumper::dump($this->query); die(); // Ctrl + X    Delete line
                    $cr=new CDbCriteria;
                    $cr->distinct = true; // предотвращает повтор объявлений на странице
                    if($this->city->id)
                      $cr->addCondition('city.id='.$this->city->id);
                    $this->query->getDbCriteria()->mergeWith($cr);

                } else {
                    $cr=new CDbCriteria;
                    $cr->addSearchCondition('title', $data,  'LIKE');

                    $this->query->getDbCriteria()->mergeWith($cr);

                }
              /* if(Yii::app()->request->getQuery('categories'))
        {
            $category = explode(';', Yii::app()->request->getParam('categories', ''));
            $this->query->applyCategory($category);
        }*/

        // Create clone of the current query to use later to get min and max prices.
        $this->currentQuery = clone $this->query->getDbCriteria();

        $per_page = $this->allowedPageLimit[0];
        if(isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $this->allowedPageLimit)){
            $per_page = (int) $_GET['per_page'];
                        $conf = Yii::app()->session->cookieParams;
                        $ck = new CHttpCookie('perp',$per_page);
                        $ck->expire = time()+60*60*24*30; // на 30 дней
                        $ck->domain = $conf['domain'];
                        Yii::app()->request->cookies['perp']=$ck;
                        
                } else {
                      $pp = Yii::app()->request->cookies['perp'];
                      if(isset($pp->value)){
                            $per_page = $pp->value;
                      } 
                }


        $this->provider = new CActiveDataProvider($this->query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>array(
                'pageSize'=>$per_page,
            )
        ));

        $this->provider->sort = Orgs::getCSort('t.title, t.created_date DESC');
            
                $itemView = '_organizations_view';
                
              /*  if(isset($_GET['view']) && $_GET['view']==='wide'){
                    
                    $itemView = '_product_wide';
                    $conf = Yii::app()->session->cookieParams;
                    $ck = new CHttpCookie('itv',$itemView);
                    $ck->expire = time()+60*60*24*30; // на 30 дней 
                    $ck->domain = $conf['domain'];
                    Yii::app()->request->cookies['itv']=$ck;

                    
                } elseif (isset($_GET['view']) && $_GET['view']==='norm') {
             
                    $itemView = '_product';
                    $conf = Yii::app()->session->cookieParams;
                    $ck = new CHttpCookie('itv',$itemView);
                    $ck->expire = time()+60*60*24*30; // на 30 дней 
                    $ck->domain = $conf['domain'];
                    Yii::app()->request->cookies['itv']=$ck;
                    
                } 
                
                
                
                if(!$itemView){
                    
                    $co = Yii::app()->request->cookies['itv'];
             
             
                    if(isset($co->value)){
                        $itemView = $co->value;
                    } else {
                        $itemView = '_product';
                        $conf = Yii::app()->session->cookieParams;
                        $ck = new CHttpCookie('itv',$itemView);
                        $ck->expire = time()+60*60*24*30; // на 30 дней  
                        $ck->domain = $conf['domain'];
                        Yii::app()->request->cookies['itv']=$ck;
                    }
                }
                */
                
        $dtitle = $this->city->rodpad; 
      	$count_items = $this->provider->totalItemCount;   
                
        $this->render($view, array(
            'provider'=>$this->provider,
                        'model' => $model,
                        'allmodel'=>$allmodel,
            'itemView'=>$itemView,
                        'per_page'=>$per_page,
                        'ancestors'=>$ancestors,
                        'dtitle'=>$dtitle,
                        'count_items'=>$count_items
                        //'itemView' => (isset($_GET['view']) && $_GET['view']==='wide') ? '_product_wide' : '_product'

        ));
    }
     /**
     * Просмотр записи
     *
     * @sitemap dataSource=getSitemapRecordUrl priority=0.8 changefreq=daily
     * @param $itemurl
     * @throws CHttpException
     */
    public function actionItem($id=null,$itemurl=null){

      $model = $this->_loadItem($id, $itemurl);
      $model->scenario = 'addPhoto';

     $this->layout = '//layouts/zazadun';

      $this->pageTitle = $model->title;
      if($this->city->id){
        $this->pageTitle .= ' в '.$this->city->mestpad;
        if(!empty($model->nearest_metro) && $model->nearestmetro)
        {
          $this->pageTitle .= ', рядом с '.$model->nearestmetro->metro_name;
        }
        if($this->city->id == 1 and $model->orgsDistrict) // Москва
        {
        	foreach ($model->orgsDistrict as $district) 
        	{
        		if(!empty($district->districtid->socr))
        			$this->pageTitle .= ', '.$district->districtid->socr;
        	}
        }
        elseif($this->city->id == 22 and $model->orgsDistrict) // Санкт-Петербург
        {
        	
        }
        else 
        {
        	$districts = District::getDistricts($model);
        	if(!empty($districts))
        	{
        		$this->pageTitle .= ', '.$districts[0];
        	}
        }
      }
      if($model->orgsHttp){
        foreach ($model->orgsHttp as $st) {
          if(!empty($st->description)){
            $this->pageTitle .= ', '. $st->description;
          }
        }
      }
      $this->pageKeywords = $model->title;
      if($model->categories){
        foreach ($model->categories as $cat){
          $this->pageKeywords .= ', '.$cat->title;
        }
      }
      $this->pageDescription = $model->title;
      if($this->city->id){
        $this->pageKeywords .= ', '.$this->city->title;
        if(!empty($model->nearest_metro) && $model->nearestmetro){
          $this->pageDescription .= ', '.$model->nearestmetro->metro_name;
        }
        $this->pageDescription .= '. Адрес: '.$this->city->title.', '.$model->street;
        $part_description = 'Адрес: '.$this->city->title.', '.$model->street;
        if($model->dom) { 
          $this->pageDescription .= ', '.$model->dom; 
          $part_description .= ', '.$model->dom;
        }
      }
      $titleKeywords = array();
      $orgsCats = array();
      if($model->categories){
        foreach ($model->categories as $cat){
          $orgsCats[] = $cat->id;
          $rootcategory = null;
          if($cat->root && $cat->root != $cat->id){
            $rootcategory = Category::model()->findByPk($cat->root);
          }
          if($rootcategory && !empty($rootcategory->keywords)){
              $keywords = MHelper::String()->toLower($rootcategory->keywords);
              $kw = explode(',', $keywords);
              $titleKeywords = $kw;
          }

          if(!empty($cat->keywords)){
              $keywords = MHelper::String()->toLower($cat->keywords);
              $kw = explode(',', $keywords);
              $titleKeywords = array_merge($titleKeywords, $kw);
          }
        }
      }
      $titleKeywords = array_unique($titleKeywords);
      if(!empty($titleKeywords)){
        $tkw = implode(',', $titleKeywords);
        $tkw = MHelper::String()->toUpper(MHelper::String()->substr($tkw, 0, 1, "UTF-8"), "UTF-8").MHelper::String()->substr($tkw, 1, MHelper::String()->len($tkw), "UTF-8" );
        $this->pageTitle .= '. '.$tkw;
      }
        
        $count_items_near = 0;
        $nearProvider = $this->getNearMetroOrg($model,$orgsCats);
        $nearProviderMicroRayon = null;
        $nearProviderRayon = null;
        if($nearProvider)
        	$count_items_near = $nearProvider->totalItemCount;
        if($count_items_near < 5){
        	$nearProviderMicroRayon = $this->getNearMicroRayonOrg($model,$orgsCats,5-$count_items_near,$nearProvider);
        	if($nearProviderMicroRayon)
        		$count_items_near += $nearProviderMicroRayon->totalItemCount;
        }
        if($count_items_near < 5){
        	$nearProviderRayon = $this->getNearRayonOrg($model,$orgsCats,5-$count_items_near,$nearProvider,$nearProviderMicroRayon);
        	if($nearProviderRayon)
        		$count_items_near += $nearProviderRayon->totalItemCount;
        }
      //  $nearProvider->sort = Orgs::getCSort('t.title, t.created_date DESC');

      if(!empty($_POST['Comment'])){
        Yii::import('application.modules.comments.CommentsModule');
        Yii::import('application.modules.comments.models.Comment');
        $comment = new Comment;
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-create-form')
        {
          $comment->attributes = Yii::app()->request->getPost('Comment');
          $comment->status = Comment::STATUS_WAITING;
          if(!Yii::app()->user->isGuest)
          {
            $comment->name = Yii::app()->user->username;
            $comment->email = Yii::app()->user->email;
          }
            $errors = CActiveForm::validate($comment);
            
            if ($errors !== '[]') {
               echo $errors;
            } else {
              // Load module
              $module = Yii::app()->getModule('comments');
              // Validate and save comment on post request
              $comment = $module->processRequest($model);
               echo '[]';
            }
            Yii::app()->end();
        }
      }
      

		if(isset($_POST['Orgs']))
		{
			if(isset($_POST['ajax']) && $_POST['ajax']==='pinboard-form')
			{
				$errors = CActiveForm::validate($model);
				if ($errors !== '[]') {
		           echo $errors;
		           Yii::app()->end();
		        } 
			}

			$model->attributes=$_POST['Orgs'];

			if($model->validate()){
			  
			  if(isset(Yii::app()->session['deleteFiles']))
			  {
			  	  $sessAr = unserialize(Yii::app()->session['deleteFiles']);
			  	  if(isset($sessAr['id']) && $sessAr['id'] == $model->id && isset($sessAr['files']) && is_array($sessAr['files']))
				  {
				  	 $files = $model->images;
				  	 if($files)
				  	 {
				  	 	foreach ($files as $file) {
				  	 		if(in_array($file->id,$sessAr['files'])){
				  	 			$file->delete();
				  	 		}
				  	 	}
				  	 }
				  }
			  }
			  
			  $model->addDropboxFiles($this->uploadsession);
              Yii::app()->session->remove($this->uploadsession);
              if(isset(Yii::app()->session['deleteFiles']))
			  		unset(Yii::app()->session['deleteFiles']);

              
                if(Yii::app()->request->isAjaxRequest){
                	echo CJSON::encode(array('flag'=>true, 'message'=>'done'));
					Yii::app()->end();
                } else {
                    $this->refresh();
                    Yii::app()->end();
                }
			} 
		}
      $this->render('item', array(
                 'model' => $model,
                 'part_description'=>$part_description,
                 'city_utc'=>$this->city->utcdiff,
                 'nearProvider'=>$nearProvider,
                 'nearProviderMicroRayon'=>$nearProviderMicroRayon,
                 'nearProviderRayon'=>$nearProviderRayon,
                 'count_items_near'=>$count_items_near
                 ));
    }

    public function actionItemNoCity($id){

      $model =  Orgs::model()
		        ->active()
		        ->with('images')
		        ->findByPk($id);
	 if (!$model)
        throw new CHttpException(404, 'Страница не найдена.');
    $this->layout = '//layouts/emptyerror';
     if($model->city_id)
     {
     	$red = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$model->city->url, 'id'=>$model->id,  'itemurl'=>$model->url));
     	$this->redirect($red, TRUE, 301);
     }


      $this->pageTitle = $model->title;
      if($model->orgsHttp){
        foreach ($model->orgsHttp as $st) {
          if(!empty($st->description)){
            $this->pageTitle .= ', '. $st->description;
          }
        }
        
      }
      $this->pageKeywords = $model->title;
      if($model->categories){
        foreach ($model->categories as $cat){
          $this->pageKeywords .= ', '.$cat->title;
        }
      }
      $this->pageDescription = $model->title;


      if(!empty($model->nearest_metro) && $model->nearestmetro){
          $this->pageDescription .= ', '.$model->nearestmetro->metro_name;
      }
        $this->pageDescription .= '. Адрес: '.$model->street;
        $part_description = 'Адрес: '.$model->street;
        if($model->dom) { 
          $this->pageDescription .= ', '.$model->dom; 
          $part_description .= ', '.$model->dom;
        }
      
      $titleKeywords = array();
      if($model->categories){
        foreach ($model->categories as $cat){
          $rootcategory = null;
          if($cat->root && $cat->root != $cat->id){
            $rootcategory = Category::model()->findByPk($cat->root);
          }
          if($rootcategory && !empty($rootcategory->keywords)){
              $keywords = MHelper::String()->toLower($rootcategory->keywords);
              $kw = explode(',', $keywords);
              $titleKeywords = $kw;
          }

          if(!empty($cat->keywords)){
              $keywords = MHelper::String()->toLower($cat->keywords);
              $kw = explode(',', $keywords);
              $titleKeywords = array_merge($titleKeywords, $kw);
          }
        }
      }
      $titleKeywords = array_unique($titleKeywords);
      if(!empty($titleKeywords)){
        $tkw = implode(',', $titleKeywords);
        $tkw = MHelper::String()->toUpper(MHelper::String()->substr($tkw, 0, 1, "UTF-8"), "UTF-8").MHelper::String()->substr($tkw, 1, MHelper::String()->len($tkw), "UTF-8" );
        $this->pageTitle .= '. '.$tkw;
      }
      

      if(!empty($_POST['Comment'])){
        Yii::import('application.modules.comments.CommentsModule');
        Yii::import('application.modules.comments.models.Comment');
        $comment = new Comment;
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-create-form')
        {
          $comment->attributes = Yii::app()->request->getPost('Comment');

          if(!Yii::app()->user->isGuest)
          {
            $comment->name = Yii::app()->user->username;
            $comment->email = Yii::app()->user->email;
          }
            $errors = CActiveForm::validate($comment);
            
            if ($errors !== '[]') {
               echo $errors;
            } else {
              // Load module
              $module = Yii::app()->getModule('comments');
              // Validate and save comment on post request
              $comment = $module->processRequest($model);
               echo '[]';
            }
            Yii::app()->end();
        }
      }
      $this->render('itemnocity', array(
                 'model' => $model,
                 'part_description'=>$part_description,
              //   'city_utc'=>$this->city->utcdiff
                 ));
    }
    /**
     * @return array массив ссылок записей для генерации карты сайты
     */
    public function getSitemapRecordUrl()
    {
    
        $map = array();

        $model = Orgs::model()
        ->active()
        ->with('city')
        ->findAll();

        if ($model) {
            foreach ($model as $record) {
             // $url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$record->city->url, 'itemurl'=>$record->url));
                if(!empty($record->city_id) && isset($record->city)){
                $map[] = array(
                    'route' => '/catalog/catalog/item',
                    'params' => array(
                        'city' => $record->city->url,
                        'id'=>$record->id,
                        'itemurl'=>$record->url,
                    ),
                    'priority' => 0.8,
                    'changefreq' => 'daily',
                    'lastmod' => date('Y-m-d', strtotime($record->updated_date)),
                );
              }
            }
        }

        return $map;
    }
    public function actionStarRating($id) {
      $ratingAjax=isset($_POST['rate']) ? $_POST['rate'] : 0;
      $id = (int)$id;
      $org = Orgs::model()->findByPk($id);
      if($org){

          $rating = OrgsRating::model()->findByAttributes(array(
            'org'=>$id
          ));
          // если не было категории - делаем
          if(!$rating)
          {
            $rating = new OrgsRating;
            $rating->org = $id;
            $rating->vote_count = 1;
            $rating->vote_sum = $ratingAjax;
            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
            $rating->save(false);

            $org->rating_id = $rating->id;
            $org->save(false,array('rating_id'));                    
          } else {
            $rating->vote_count = $rating->vote_count + 1;
            $rating->vote_sum = $rating->vote_sum + $ratingAjax;
            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
            if(!$rating->save()){
                VarDumper::dump($rating->errors); die(); // Ctrl + X  Delete line
            }
          }
          echo CJSON::encode(array('status'=>'OK'));
          Yii::app()->end();
    }
    }

    public function actionSearch()
    {
      if(Yii::app()->request->isPostRequest  && Yii::app()->request->getPost('q') && Yii::app()->request->getPost('pereparam')){
        $this->redirect(Yii::app()->request->addUrlParam('catalog/catalog/search', array('q'=>Yii::app()->request->getPost('q'))));
      }
       $this->layout = '//layouts/zazadun';
        $count_items = 0;
        if (($term = Yii::app()->getRequest()->getQuery('q')) !== null) {
            $this->pageTitle = CHtml::encode($term).' в '.$this->city->mestpad;
            $query = $term;

            $s = MHelper::String()->toLower(trim($term));
            $resultsPr = null;
            if(!empty($s)){
            $s = addcslashes($s, '%_'); // escape LIKE's special characters

            $criteria = new CDbCriteria;
            $criteria->scopes='active';
            $criteria->with = array(
              'categorization',
              'categories'=>array(
                 'together'=>true
                ),
              'city'=>array('together'=>true)
              );
           // $criteria->together = true;
         /*   $criteria->compare('t.city_id',$this->city_id);
            $criteria->compare('LOWER(t.title)',$s,true);
            $criteria->compare('LOWER(t.description)',$s,true,'OR');
            $criteria->compare('LOWER(t.synonim)',$s,true,'OR');
            $criteria->compare('LOWER(categories.title)', $s, true, 'OR');
            $criteria->compare('LOWER(categories.keywords)', $s, true, 'OR');
          */

            $criteria->condition ='(t.city_id = '.$this->city->id.' and ( (LOWER(t.title) LIKE :s) or (LOWER(t.synonim) LIKE :s) or (LOWER(t.description) LIKE :s) or (LOWER(categories.title) LIKE :s) or (LOWER(categories.keywords) LIKE :s)))';
            $criteria->params = array(':s'=>"%$s%");
            $resultsPr = new CActiveDataProvider('Orgs', array(
                'criteria' => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.title',
                ),
                'pagination' => array(
                    'pageSize' => 20,
                    'pageVar'=>'page'
                ),
            ));
            
          //  VarDumper::dump($resultsPr); die(); // Ctrl + X  Delete line
          }
          
          if ($resultsPr && $resultsPr->totalItemCount) { 
            $count_items = $resultsPr->totalItemCount;
          } 
          if(!empty($term) && !isset($_GET['page'])){
            $searchquery = new Searchqueries;
            $searchquery->query = $term;
            $searchquery->city_id = $this->city->id;
            $searchquery->quantity = $count_items;
            $searchquery->ip_address = MHelper::Ip()->getIp();
            $searchquery->save();

          }
         // $this->render('search', compact('term', 'query','resultsPr','count_items'));
          $dtitle = $term . ' '. $this->city->rodpad;
          $this->render('view',array(
          				'provider'=>$resultsPr,
                        'dtitle'=>$dtitle,
                        'itemView'=>'_organizations_view',
                        'count_items'=>$count_items
                        ));
        } else {
          $term = $query = '';
          $resultsPr = null;
          $dtitle = 'Поиск';
          $this->render('view',array(
          				'provider'=>$resultsPr,
                        'dtitle'=>$dtitle,
                        'itemView'=>'_organizations_view',
                        'count_items'=>$count_items
                        ));
        }
        
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
        	$model = Orgs::model()
		        ->active()
		        ->with('images','city')
		        ->findByPk($id);

		 //   if(isset($expl[1]))
		 //   {
		    	// if($model->url != $expl[1] || $model->city_id != $this->city->id)

		   // }
        }
        else 
        {
        	$model = Orgs::model()
		        ->active()
		        ->with('images','city')
		        ->withUrl($url)
		        ->find(array(
		        'limit'=>1
		       ));
		    if(!$model) // try search by name
		    {
		    	$redo = new Redo404;
				$model = $redo->detranslit($url,$this->city);
		    }
        }	
        
        if (!$model)
         throw new CHttpException(404, 'Страница не найдена.');

		if(!$id || $model->url != $url || $model->city_id != $this->city->id)
    	{

    		if($model->city_id)
    			$red = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$model->city->url, 'id'=>$model->id,  'itemurl'=>$model->url));
    		else
    			$red = Yii::app()->createAbsoluteUrl('/catalog/catalog/itemnocity', array('id'=>$model->id));
    		 $this->redirect($red, TRUE, 301);
    	}

     $model->saveCounters(array('views_count'=>1));

    return $model;
}

	/**
     * This is the action to handle external exceptions.
     */
    public function actionError() {

    	if(!$this->city)
    		$this->layout = '//layouts/emptyerror';
    	else
    		$this->layout = '//layouts/zazadun';
    	$this->modules = 'empty';

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest){
                if($error)
                    echo $error['message'];
            }
            else{
                if($error){

                    if(YII_DEBUG == true){
                       // VarDumper::dump($error); die(); // Ctrl + X    Delete line
                    }

                    if($error['code'] == '404'){
                        $this->pageTitle = 'Error 404';
                        $this->render('application.views.site.404', array('error'=>$error));
                    } else if($error['code'] == '500'){
                        $this->pageTitle = 'Error 500';
                        $this->render('application.views.site.500', array('error'=>$error));
                    } else if($error['code'] == '403'){
                        $this->pageTitle = 'Error 403';
                        $this->render('application.views.site.403', array('error'=>$error));
                    } else {
                    	$this->pageTitle = 'Error 404';
                       $this->render('application.views.site.error', array('error'=>$error));  
                    }

                } 
                
            }
        } else {
        	$this->pageTitle = 'Error 404';
        	$this->render('application.views.site.error', array('error'=>$error));  
        }
    }

    public function getNearMetroOrg($model,$orgsCats)
    {
    	/* ближайшие организации */
        $district = 'metro';
        $district_model = null;
    	
    	$district_model = $model->nearestmetro;
    	if(!$district_model) {
        	return null;
        }
        $query = new Orgs(null);
        $query->active()
                ->with(array(
                    'images',
                    'city',
                    ));
        // все категории организации
        $query->applyCategoriesWithSub($orgsCats);

        $cr=new CDbCriteria;
        $cr->distinct = true; // предотвращает повтор объявлений на странице

        $cr->addCondition('city.id='.$this->city->id.' and t.id!='.$model->id.' and t.nearest_metro='.$district_model->id);
        
        $cr->limit = 5;  
        $query->getDbCriteria()->mergeWith($cr);
        $nearProvider = new CActiveDataProvider($query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>false
        ));
        return $nearProvider;
    }

    public function getNearMicroRayonOrg($model,$orgsCats,$limit,$near)
    {
    	/* ближайшие организации */
        $district = 'mikrorayon';
        $districts = $model->orgsDistrict;
        $district_model = null;
        if(!empty($districts)){
        	foreach ($districts as $d) {
        		if((mb_strpos($d->districtid->district_name, 'микрорайон', 0, 'UTF-8') !== false )){
        			$district_model = $d->districtid;
        			break;
        		}
        	}
        }
        if(!$district_model) {
        	return null;
        }
        $query = new Orgs(null);
        $query->active()
                ->with(array(
                    'images',
                    'city',
                    ));
        // все категории организации
        $query->applyCategoriesWithSub($orgsCats);

        $cr=new CDbCriteria;
        $cr->distinct = true; // предотвращает повтор объявлений на странице

    	$cr->join = 'LEFT JOIN "orgs_district" "orgsdistrict" ON ("orgsdistrict"."org"="t"."id")';
    	$cr->addCondition('city.id='.$this->city->id.'  and orgsdistrict.district='.$district_model->id);
    	$notin = array();
    	if($near){
    		
    		foreach ($near->data as $n) {
    			$notin[] = $n->id;
    		}
    		$notin[] = $model->id;
    		
    	} else {
    		$notin[] = $model->id;
    	}
    	$cr->addNotInCondition('orgsdistrict.org',$notin);
    	

        $cr->limit = $limit;  
        $query->getDbCriteria()->mergeWith($cr);
        $nearProvider = new CActiveDataProvider($query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>false
        ));
        return $nearProvider;
    }

    public function getNearRayonOrg($model,$orgsCats,$limit,$near,$nearmicro)
    {
    	/* ближайшие организации */
        $district = 'rayon';
        $districts = $model->orgsDistrict;
        $district_model = null;
        if(!empty($districts)){
        	foreach ($districts as $d) {
        		if((mb_strpos($d->districtid->district_name, 'район', 0, 'UTF-8') !== false ) && (mb_strpos($d->districtid->district_name, 'микрорайон', 0, 'UTF-8') === false )){
        			$district_model = $d->districtid;
        			break;
        		}
        	}
        }
        if(!$district_model) {
        	return null;
        }
        $query = new Orgs(null);
        $query->active()
                ->with(array(
                    'images',
                    'city',
                    ));
        // все категории организации
        $query->applyCategoriesWithSub($orgsCats);

        $cr=new CDbCriteria;
        $cr->distinct = true; // предотвращает повтор объявлений на странице

    	$cr->join = 'LEFT JOIN "orgs_district" "orgsdistrict" ON ("orgsdistrict"."org"="t"."id")';
    	$cr->addCondition('city.id='.$this->city->id.'  and orgsdistrict.district='.$district_model->id);
    	$notin = array();
    	if($near){
    		foreach ($near->data as $n) {
    			$notin[] = $n->id;
    		}
    	} 
    	if($nearmicro){
    		foreach ($nearmicro->data as $n) {
    			$notin[] = $n->id;
    		}
    	} 
    	$notin[] = $model->id;
    	$cr->addNotInCondition('orgsdistrict.org',$notin);
    	

        $cr->limit = $limit;  
        $query->getDbCriteria()->mergeWith($cr);
        $nearProvider = new CActiveDataProvider($query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>false
        ));
        return $nearProvider;
    }
    /**
     * Load category by url
     * @param $url
     * @return Category
     * @throws CHttpException
     */
    public function _loadModel($url)
    {
        
            
                
             /*   $categories = explode(';', $url);

                $cnt = count($categories);
                if($cnt > 1){
                  $url = end($categories);
                  $this->lastmodel = Category::model()
                          ->excludeRoot()
                          ->withUrl($url)
                          ->find();
                   // $categories = implode(',',$categories);
                    $model = Category::model()
                          //->excludeRoot()
                          ->withUrls($categories)
                          ->findAll();



                } else { */
                   
                  // Find category
                  $model = Category::model()

                          ->withUrl($url)
                          ->find();
               //  }
                
                
               
        if (!$model) throw new CHttpException(404, 'Категория не найдена.');
                
        return $model;
    }

    public function _loadDistrict($url)
    {
       $model = District::model()->withUrl($url,$this->city->id)->find();
       if (!$model) throw new CHttpException(404, 'Страница не найдена.');
        return $model;
    }
    public function _loadMetro($url)
    {
       $model = Metro::model()->withUrl($url,$this->city->id)->find();
       if (!$model) throw new CHttpException(404, 'Страница не найдена.');
        return $model;
    }
     protected function redirectIt(){
        $route = '/moscow';
        $set = array();
            if($_GET){
            foreach($_GET as $key=>$value){
                $set[$key] = $value;
            }
           }
        $route = Yii::app()->createUrl($route, $set);
        $this->redirect($route);
    }

}
?>