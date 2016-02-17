<?php
class FastreviewController extends Controller {

	public $uploadsession = 'objectsfiles';
  /**
   * @var array Eav attributes used in http query
   */
  private $_eavAttributes;

    public function actionNew_object()
    {
    	$this->actionUpdate();
    }
    public function actionUpdate($id=null)
    {
    	$model = null;
    	if($id)
    	{
        $new = false;
        $this->pageTitle = 'Редактировать объект';
        if(Yii::app()->user->isGuest){
           $this->redirect(Yii::app()->createAbsoluteUrl('new_object'));
        }
    		$model = Objects::model()->findByPk($id,'author='.Yii::app()->user->id);
        if(!$model)
          $this->redirect(Yii::app()->createAbsoluteUrl('new_object'));
    	   $old_mesto = $model->address;
      }
    	if(!$model)
    	{
        $new = true;
        $this->pageTitle = 'Новый объект - '.Yii::app()->name;
      
    	$model = new Objects;
	    	
    	} 
    if(isset($_POST['Objects']))
    {
		  $model->attributes = $_POST['Objects'];
      

      if(!$model->categories_ar || !is_array($model->categories_ar)){
        $model->categories_ar = array();
        $model->categorie = null;
      } else {
        $model->categorie = $model->categories_ar[0];
      }

    	// Uncomment the following line if AJAX validation is needed
		if(isset($_POST['ajax']) && $_POST['ajax']==='objects-form')
		{
      
			$errors = CActiveForm::validate($model);
      
			if ($errors !== '[]') {
	           $errors = CJSON::decode($errors);
	           echo CJSON::encode(array('success'=>false, 'message'=>$errors));
	           Yii::app()->end();
	    } 

      
      if($new ||  (!empty($old_mesto) && $old_mesto != $model->address)){
        $words = explode(',',$model->address,2);

        if(!empty($words)){
          $city = trim($words[0]);
          $trueCity = City::model()->find('LOWER(title)=:title or LOWER(alternative_title)=:title',array(':title'=>MHelper::String()->toLower($city)));
          if(!$trueCity)
            $trueCity = City::addNewCity($city);
          if($trueCity)
            $model->city_id = $trueCity->id;
        }
        if(isset($words[1])){
			$words = explode(',',$words[1],2);
			if(isset($words[0]) && !empty($words[0])){
				$model->street = trim($words[0]);
			}
			if(isset($words[1]) && !empty($words[1])){
				$model->dom = trim($words[1]);
			}
		}

        
      }
		}

		
			$model->status = Objects::STATUS_ACTIVE;

			if($model->save()){

			  	if(isset(Yii::app()->session['deleteObjectsFiles']))
		        {
		            $sessAr = unserialize(Yii::app()->session['deleteObjectsFiles']);
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
              if(isset(Yii::app()->session['deleteObjectsFiles']))
            unset(Yii::app()->session['deleteObjectsFiles']);


                $objects_url =  Yii::app()->createAbsoluteUrl('/fastreview/item', array('id'=>$model->id,'dash'=>'-', 'itemurl'=>$model->url));
                if(Yii::app()->request->isAjaxRequest){
                	
                    echo CJSON::encode(array('success'=>true, 'message'=>array('url'=>$objects_url)));
					          Yii::app()->end();
                } else {
                    $this->redirect($objects_url);
                }
			} 
		}


        $categories_ar[] = $model->categorie;

    	$this->render('_form', array('model'=>$model,'categories_ar'=>$categories_ar));
    }

    
    public function actionView()
    {
                

          $model = $this->_loadModel(Yii::app()->request->getQuery('url'));
          
          $cr=new CDbCriteria;
          if ($model){
            $this->pageTitle = $model->title.' - Быстрые отзывы покупателей';
            $modelFirst = $model;
            $name = $model->title;
            $cr->addCondition('t.categorie='.$model->id);
          } 
          
         // $query = Objects::model()->cache(3600);
          $cr->select = 't.*';
          $query = new Objects(null);
          $query->attachBehaviors($query->behaviors());
          $query->applyAttributes($this->activeAttributes)->active();
                //  ->with('images');          
        
        
        $query->getDbCriteria()->mergeWith($cr);


        $provider = new CActiveDataProvider($query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id'=>false,
            'pagination'=>array(
                'pageSize'=>25,
            )
        ));

        $provider->sort = Objects::getCSort('t.created_date DESC, t.title');
            
        $itemView = '_objects_view';

                
        $count_items = $provider->totalItemCount;   
        

        $criteria = new CDbCriteria;
         $criteria->scopes = 'active';
         $dataProvider = new CActiveDataProvider('Objects', array(
                'criteria' => $criteria,
                'sort'=>array(
                    'defaultOrder' => 't.created_date DESC',
                ),
                'pagination' => array(
                    'pageSize' => 30,
                ),
            ));
          $sql = 'select count(*) cnt, categorie
                from objects
                WHERE objects.status='.Objects::STATUS_ACTIVE.'
                group by categorie';

          $connection = Yii::app()->db;
          $command = $connection->createCommand($sql);
          $rows = $command->queryAll();


      $search = false;    

        $this->render('view', array(
            'provider'=>$provider,
                        'model' => $model,
            'itemView'=>$itemView,
                        'per_page'=>25,
                        'count_items'=>$count_items,
                        'rows'=>$rows,
                        'search'=>$search
                        //'itemView' => (isset($_GET['view']) && $_GET['view']==='wide') ? '_product_wide' : '_product'

        ));
    }

    public function actionSearch(){
        $count_items = 0;
        
        if (($term = Yii::app()->getRequest()->getQuery('q')) !== null) {

            $trunc_text = MHelper::String()->truncate(CHtml::encode($term), 250, '..', true, true, false);
            $this->pageTitle = $trunc_text.' - '.Yii::app()->name;
            $this->pageTitle = trim(preg_replace('/\s+/', ' ', $this->pageTitle));

            $query = $term;

            $s = MHelper::String()->toLower(trim($term));
            $resultsPr = null;
            if(!empty($s)){
              $s = addcslashes($s, '%_'); // escape LIKE's special characters

              $criteria = new CDbCriteria;
              $criteria->scopes='active';
              $criteria->condition =' ((LOWER(title) LIKE :s))';
              $criteria->params = array(':s'=>"%$s%");
              $resultsPr = new CActiveDataProvider('Objects', array(
                  'criteria' => $criteria,
                  'sort'       => array(
                      'defaultOrder' => 'title',
                  ),
                  'pagination' => array(
                      'pageSize' => 10,
                      'pageVar'=>'page'
                  ),
              ));
            }
            if(Yii::app()->request->isAjaxRequest){
               $this->renderPartial('_search',array(
                  'provider'=>$resultsPr,
                  'term'=>$term
                        ));
              Yii::app()->end();
            } else {
            $this->render('search',array(
                  'provider'=>$resultsPr,
                  'term'=>$term
                        ));
            }
        } else {
              $term = $query = '';
              $resultsPr = null;
              $this->pageTitle = 'Поиск - '.Yii::app()->name;
              if(Yii::app()->request->isAjaxRequest){
                echo '';
                Yii::app()->end();
              } else {
                $this->render('search',array(
                        'provider'=>$resultsPr,
                        'term'=>$term
                 ));
              }
        }

    }
    public function actionItem($id)
    {
    	$model = $this->_loadItem($id);
      $trunc_text = MHelper::String()->truncate($model->title, 400, '..', true, true, false);
      $this->pageTitle = $trunc_text.' - Быстрые отзывы покупателей';
      $this->pageTitle = trim(preg_replace('/\s+/', ' ', $this->pageTitle));
      

      $pohs = Objects::model()->active()->findAll(array(
        'condition'=>'categorie='.$model->categorie.' and id!='.$id,
        'limit'=>5,
        'order'=>'created_date DESC'
        ));

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
              $comment = $module->processRequest($model);
               echo '[]';
            }
            Yii::app()->end();
        }
      }

    if(isset($_POST['Objects']) && isset($_POST['itemData']) && $_POST['itemData'] == 1)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='pinboard-form'){
          $errors = CActiveForm::validate($model);
          if ($errors !== '[]') {
                 echo $errors;
                 Yii::app()->end();
          } 
      }
      

      $model->attributes=$_POST['Objects'];

      if($model->validate()){
        
        if(isset(Yii::app()->session['deleteObjectsFiles']))
        {
            $sessAr = unserialize(Yii::app()->session['deleteObjectsFiles']);
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
              if(isset(Yii::app()->session['deleteObjectsFiles']))
            unset(Yii::app()->session['deleteObjectsFiles']);

              
                if(Yii::app()->request->isAjaxRequest){
                  echo CJSON::encode(array('flag'=>true, 'message'=>'done'));
                  Yii::app()->end();
                } else {
                    $this->refresh();
                    Yii::app()->end();
                }
      } 
    }
    if(isset($_POST['Objects']) && isset($_POST['itemData']) && $_POST['itemData'] == 2)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='pinboard-video-form'){
        $errors = CActiveForm::validate($model);
        if ($errors !== '[]') {
               echo $errors;
               Yii::app()->end();
        } 
      }
        $model->attributes=$_POST['Objects'];

        if(!empty($model->video_link) && $model->validate()){
          $model->video = array($model->video_link);
          $model->video_comments = array('');
          $model->setHttp($model->video, $model->video_comments, false, ObjectsHttp::TYPE_VIDEO);
          echo CJSON::encode(array('flag'=>true, 'message'=>'done'));
          Yii::app()->end();
        }
        echo '[]';
        Yii::app()->end();
    }
      $this->render('item', array(
                 'model' => $model,
                 'pohs'=>$pohs

                 ));
    }
   public function clearstr($str){
      return preg_replace('/[^A-Za-zА-Яа-я0-9_.\-]+/u', '', $str); // Removes special chars.
   } 
  /**
   * @return array of attributes used in http query and available in category
   */
  public function getActiveAttributes()
  {
    $data =  $filters = array();
    $query = explode('&', $_SERVER['QUERY_STRING']);
    $params = $ars = array();
    if(!empty($query)){
      foreach( $query as $param )
      {
        if(strpos($param, '=') !== false){

          list($name, $value) = explode('=', $param,2);
          $params[urldecode($name)][] = urldecode($value);
        }
      }
      if(isset($params['gfilter']))
        $filters = $params['gfilter'];
    }

    if(!empty($filters)){
      foreach ($filters as $filter) {
        if(strpos($filter, ':') === false){
         // $data[$filter] = '';
        } else {
          list($id, $value) = explode(':', $filter);
          if(strpos($value, ',') === false){

              if(array_key_exists($id,$data)){
                $data[$id] = array_merge(array($data[$id]),array($this->clearstr($value)));
              } else {
                $data[$id] = $this->clearstr($value);
              }
          } else {
            $ars = explode(',', $value);
            if(!empty($ars)){
              foreach ($ars as $ar) {
                if(array_key_exists($id,$data)){
                  $data[$id] = array_merge(array($data[$id]),array($this->clearstr($ar)));
                } else {
                  $data[$id] = $this->clearstr($ar);
                }
              }
            }
            
          }
        }
        
      }
    }

   /* foreach(array_keys($_GET) as $key)
    {

      if(array_key_exists($key, $this->eavAttributes))
      {
        if((boolean) $this->eavAttributes[$key]->select_many === true)
          $data[$key] = explode(';', $_GET[$key]);
        else
          $data[$key] = array($_GET[$key]);
      }
    }
    */
    
    return $data;
  }

  /**
   * @return array of available attributes in category
   */
  public function getEavAttributes()
  {
    if(is_array($this->_eavAttributes))
      return $this->_eavAttributes;
                
    // Find category types
    /*$model = new Products(null);
    $criteria = $model
      ->applyCategories($this->model)
      ->active()
                        ->withcity()
      ->getDbCriteria();

    unset($model);

    $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());

    $criteria->select    = 'type_id';
    $criteria->group     = 'type_id';
    $criteria->distinct  = true;
    $typesUsed = $builder->createFindCommand(Products::model()->tableName(), $criteria)->queryColumn();*/
                $typesUsed[]=1;
                $typesUsed[]=2;
    // Find attributes by type
    $criteria = new CDbCriteria;
  //  $criteria->addInCondition('types.type_id', $typesUsed);
    $query = EavOptions::model()
    // ->useInFilter()
      ->with(array( 'options'))
      ->findAll($criteria);

    $this->_eavAttributes = array();
    if(!empty($query)){
    foreach($query as $attr)
      $this->_eavAttributes[$attr->id] = $attr;
    }
    return $this->_eavAttributes;
  }
  protected function _loadItem($id=null)
  {
  		if(!$id && !$url)
  			throw new CHttpException(404, 'Страница не найдена.');

  	  if($id)
      {
      	$model = Objects::model()
	        ->active()
	        ->findByPk($id);
      }
        if (!$model)
         throw new CHttpException(404, 'Страница не найдена.');

	    // $model->saveCounters(array('views_count'=>1));

	    return $model;
	}
	public function _loadModel($url)
    {
        // Find category
        $model = Category::model()
                          ->withUrl($url)
                          ->find();
       // if (!$model) throw new CHttpException(404, 'Категория не найдена.');
                
        return $model;
    }
}
?>