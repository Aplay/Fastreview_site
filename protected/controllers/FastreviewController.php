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
      if (false !== mb_strpos($model->description, '://')) {
             echo CJSON::encode(array('success'=>false, 'message'=>array('Objects_description'=>array('Размещение веб-ссылок запрещено'))));
             Yii::app()->end();
      }
      
      if($new ||  (!empty($old_mesto) && $old_mesto != $model->address)){
        $words = explode(',',$model->address,2);

        if(!empty($words)){
          $city = trim($words[0]);
          $trueCity = City::model()->find('LOWER(title)=:title or LOWER(alternative_title)=:title',array(':title'=>MHelper::String()->toLower($city)));
          if(!$trueCity)
            $trueCity = $this->addNewCity($city);
          if($trueCity)
            $model->city_id = $trueCity->id;
        }
        
      }
		}

		
			$model->status = Objects::STATUS_ACTIVE;

			if($model->save()){
        		$model->setCategories($model->categories_ar);
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


                $objects_url =  Yii::app()->createAbsoluteUrl('/fastreview/item', array('id'=>$model->id,'dash'=>'-', 'themeurl'=>$model->category->url,'itemurl'=>$model->url));
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
}
?>