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

    public function addNewCity($city)
    {
    	$trueCity = null;
    	if(!empty($city)){ // добавляем город
						$trueCity = new City;
						$trueCity->title = $city;
						$trueCity->alternative_title = $city;

						$address_city = $city;
						$params = array(
						    'geocode' => $address_city,         // координаты
						    'format'  => 'json',                          // формат ответа
						    'results' => 1,                               // количество выводимых результатов
						    'kind'=>'locality'
						  //  'key'     => '...',                           // ваш api key
						);
						$trueRegion = $result_region = null;
						$response = json_decode(@file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
						if ($response && $response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
						{
							$result = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
						    if($result){
						    	$exp_str1 = explode(" ", $result);
								$trueCity->latitude = $exp_str1[1];
								$trueCity->longitude = $exp_str1[0]; 
						    } 
						    $result = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->name;
						    if($result){ 
						    	$trueCity->title = $result;
						    	$trueCity->alternative_title = $result;
						     }
						    $result_region = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName;
						    if($result_region){
						    	$trueRegion = Region::model()->find('title=:title',array(':title'=>$result_region));
									if(!$trueRegion){
										$trueRegion = new Region;
										$trueRegion->title = $result_region;
									    $trueRegion->save();
									}
						    } 
						}
						if($trueCity->latitude)
						{
							// склонение 
							$params = array(
							    'format'  => 'json',                          // формат ответа
							    'name'=>$trueCity->title
							);
							$response = CJSON::decode(@file_get_contents('http://export.yandex.ru/inflect.xml?' . http_build_query($params, '', '&')));
							if ($response) 
							{
								if(isset($response[2]))
									$trueCity->rodpad = $response[2];
								if(isset($response[6]))
									$trueCity->mestpad = $response[6];
							}

							if($trueRegion){
								$trueCity->region = $trueRegion->id;
							}
							$trueCity->pos = 10000;
						    if($trueCity->save()){
						    	
						    } else {

						    	if($trueCity->errors && isset($trueCity->errors['title'])){
						    		if($trueCity->errors['title'][0] == 'Город с таким названием уже существует.'){
						    			$trueCity = City::model()->find('title=:title or alternative_title=:alternative_title',array(':title'=>$trueCity->title,':alternative_title'=>$trueCity->title));
						    		}
						    	}
						    }
						}


					} 
					return $trueCity;
    }
}
?>