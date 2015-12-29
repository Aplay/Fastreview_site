<?php
class FastreviewController extends Controller {

	public $uploadsession = 'objectsfiles';

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

          $this->pageTitle = $model->title.' - Быстрые отзывы покупателей';
          
          $modelFirst = $model;
          
          $name = $model->title;
          $query = Objects::model()->cache(3600);
          $query->active()
                  ->with('images');
                  
        $cr=new CDbCriteria;
        $cr->addCondition('t.categorie='.$model->id);
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
        if (!$model) throw new CHttpException(404, 'Категория не найдена.');
                
        return $model;
    }
}
?>