<?php

class SiteController extends Controller {


    public function filters()
    {
     return array(
        'ajaxOnly + getcities, getcitiesbig, feedback, feedbackupdate, getmaps',
        );
    }
   
    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
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
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        
       // $cats = Category::model()->findAll(array('condition'=>'lft=1 and rgt=2 and level=1','order'=>'title'));
        $cats =  Category::getRubsByParentId();
        $lasts = Objects::model()->active()->findAll(array('order'=>'created_date DESC', 'limit'=>9));
        $last_array = array();
        if(!empty($lasts)){
            foreach ($lasts as $last) {
                $last_array[] = $last->id;
            }
        }
        $criteria = new CDbCriteria;
        $criteria->order = 'created_date DESC';
        $criteria->limit = 3;
        $criteria->addNotInCondition('org_id', $last_array);
        $lasts_poll = PollChoice::model()->findAll($criteria);
        if(!empty($lasts_poll)){
            foreach ($lasts_poll as $last_poll) {
                $lasts[] = $last_poll->org;
            }
        }
        $this->render('index',array(
            'cats'=>$cats,
            'lasts'=>$lasts,
        ));
 
        
    }
    
    public function actionReview_objects() {
        
        $count_items = 0;
        $term = Yii::app()->getRequest()->getParam('q',null);
        $type = Yii::app()->getRequest()->getParam('t',0);
        if ($term) {

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
                      'pageSize' => $type?11:10,
                      'pageVar'=>'page',
                     // 'params' => array('q'=>$query),
                     // 'route'=>$this->createUrl('site/review_objects'),
                  ),
              ));
            }
            /*
            Не удается обновить clistview, загруженную без параметров q и page через ajax
            Т.е. нажимаешь пагинацию в первый раз, идет переход
            См. в сторону jquery.yiilistview.js options.url = $.param.querystring(options.url, options.data);
            Или нужно на лету изменять параметры в адресной строке, но хотелось бы, чтобы всегда был адрес
            /review_objects без параметров.
            */
            if(Yii::app()->request->isAjaxRequest){
               $cs = Yii::app()->clientScript;

                $cs->scriptMap['jquery-2.1.1.min.js'] = false;
                $cs->scriptMap['jquery.debouncedresize.js'] = false;
                $cs->scriptMap['jquery.ba-bbq.js'] = false;
                $cs->scriptMap['jquery.yiilistview.js'] = false;

                $cs->scriptMap['styles.css'] = false;
                $cs->scriptMap['pager.css'] = false;
               $objects_view = 'application.views.fastreview._objects_view';
               if($type)
                    $objects_view = '_objects_view_blue';
               $this->renderPartial('_search',array(
                  'provider'=>$resultsPr,
                  'term'=>$term,
                  'objects_view'=>$objects_view,'type'=>$type), false, true);
              Yii::app()->end();
            } else {

            
            $this->render('review_objects',array(
                  'provider'=>$resultsPr,
                  'term'=>$term
                        ));
            }
        } else {
              $term = $query = '';
              $resultsPr = null;

              if(Yii::app()->request->isAjaxRequest){
                echo '';
                Yii::app()->end();
              } else {
                $this->render('review_objects',array(
                        'provider'=>$resultsPr,
                        'term'=>$term
                 ));
              }
        }


    }
    

    public function actionAbout()
    {

       // $this->layout = '//layouts/page';
        $this->render("/pages/about");
    }

    public function actionCorporate()
    {
       // $this->layout = '//layouts/page';
        $this->render("/pages/corporate");
    }

    public function actionPartner()
    {
       // $this->layout = '//layouts/page';
        $this->render("/pages/partner");
    }

    public function actionLegal()
    {
        // $this->layout = '//layouts/emptyerror';
        $this->render("/pages/legal");
    }

    public function actionPrivacy()
    {
        // $this->layout = '//layouts/emptyerror';
        $this->render("/pages/privacy");
    }
public function actionFile($id){
        

        $id   = (int)$id;
        $model = IssueFile::model()->findByPk($id); 
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));

        $available_mime = Yii::app()->params['mime_fileview'];
        $available_mime_microsoft = Yii::app()->params['mime_fileview_microsoft'];

        $filename = $model->filename;
        $realname = $model->realname;

        $uploadPath = $model->issue->getFileFolder();    

        if(file_exists($uploadPath.$filename )) {

            $type = CFileHelper::getMimeType($uploadPath.$filename); // get yii framework mime
            
            if(in_array($type, $available_mime) || in_array($type, $available_mime_microsoft)){

                //.. get the content of the requested file 
                $content=file_get_contents($uploadPath.$filename);
                //.. send appropriate headers
                header('Content-Type:' . $type);
                header("Content-Length: ". filesize($uploadPath.$filename));

                header('Content-Disposition: inline; filename="' . $realname . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');

                echo $content;
                exit; 
                
            } else {
                Yii::app()->getRequest()->sendFile( $realname , file_get_contents( $uploadPath.$filename ) );
            }
        }
        else{
            throw new CHttpException(404, Yii::t('site','Page not found'));
        }   
   

    }
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {

     //  $this->layout = '//layouts/emptyerror';

    	$error = array();

    	$error['code'] = Yii::app()->request->getQuery('code');

        if (!empty($error['code']) || $error = Yii::app()->errorHandler->error) {

            if (Yii::app()->request->isAjaxRequest){

                if($error)
                    echo $error['message'];
            }
            else{

                if($error){
                    if(YII_DEBUG == true){
                       // 
                    }

                    if($error['code'] == '404'){
                        
                        $this->pageTitle = 'Error 404';
                        $this->render('404', array('error'=>$error));
                    } else if($error['code'] == '500'){

                        $this->pageTitle = 'Error 500';
                        $this->render('500', array('error'=>$error));
                    } else if($error['code'] == '403'){

                        $this->pageTitle = 'Error 403';
                        $this->render('403', array('error'=>$error));
                    }else {
                    	$this->pageTitle = 'Error 404';
                       $this->render('error', array('error'=>$error));  
                    }

                } 
                
            }
        } else {
        	
        	$this->pageTitle = 'Error 404';
        	$this->render('error', array('error'=>'404'));  
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionFeedback()
    {
        $model = new FormContact; $send = null;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && ($_POST['ajax'] === 'form-contact') || ($_POST['ajax'] === 'form-contact_about')) {
            $model->attributes = Yii::app()->request->getPost('FormContact');
            $errors = CActiveForm::validate($model);
            if ($errors !== '[]') {
               echo $errors;
            } else {

              if ($model->validate()) {
              /*  $mails = new Mails;
                $mails->name = $model->name;
                $mails->email = $model->email;
                $mails->message = $model->content;
                if(!Yii::app()->user->isGuest){
                    $mails->user_id = Yii::app()->user->id;
                }*/

              //  if($mails->save()){
              //  $model->subject = 'Обратная связь #'.$mails->id;
                $model->subject = 'Обратная связь';
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: ".$name." <".$model->email.">\r\n" .
                    "Reply-To: ".$model->email."\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-type: text/plain; charset=UTF-8";


                $content = null;
                if(!Yii::app()->user->isGuest){
                  $content .= 'Пользователь: ' .  Yii::app()->user->email.' '.Yii::app()->user->fullname . "\r\n";
                }
                $content .= 'Имя: ' .  $model->name . "\r\n";
                $content .= 'E-mail: ' .  $model->email . "\r\n"
                         .'Сообщение: '."\r\n". $model->content;
                

 // $mailto = 'info@inlocator.ru';
  $mailto = 'makarenok.roman@gmail.com';
                $send = SendMail::send($mailto,$subject,$content,false);
          //  }
                //if (mail($mailto, $subject, $content, $headers)){
                if(!isset($send->ErrorInfo) && !empty($send->ErrorInfo)){
                    VarDumper::dump($send->ErrorInfo); die(); // Ctrl + X    Delete line
                }
                if($send){
                    $message = 'Ваше сообщение успешно отправлено.';
                    if (Yii::app()->request->isAjaxRequest){
                        echo CJSON::encode(array(
                            'flag' => true,
                            'message' => $message
                        ));
                    } else {
                        Yii::app()->user->setFlash('success', $message);
                        $this->refresh();
                    }
                } else {
                    $message = 'Ошибка отправки сообщения.';
                   // $message .= $send->ErrorInfo;
                    if (Yii::app()->request->isAjaxRequest){
                        echo CJSON::encode(array(
                            'flag' => false,
                            'message' => $message
                        ));
                    } else {
                        Yii::app()->user->setFlash('error', $message);
                        $this->refresh();
                    }
                }

                Yii::app()->end();
            }
            }
        }

        Yii::app()->end();
    }
    public function actionFeedbackUpdate()
    {
        $model = new FormFeedbackUpdate;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form-feedback-update') {
            $model->attributes = Yii::app()->request->getPost('FormFeedbackUpdate');
            $errors = CActiveForm::validate($model);
            if ($errors !== '[]') {
               echo $errors;
            } else {

              if ($model->validate()) { 
              	$org = Orgs::model()->findByPk($model->org);
              	$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/item', array('city'=>$org->city->url, 'id'=>$org->id,  'itemurl'=>$org->url));
 
                $subject = 'Обновление: '.$org->title;

                $content = 'Обновить данные <a href="'.$url.'">'  .  $org->title.  '</a><br><br>';
                $content .= $model->content.'<br><br>';
                if(!Yii::app()->user->isGuest){
                  $content .= 'Пользователь: ' .  Yii::app()->user->username;
                } else {
                  $content .= "Пользователь: Аноним";
                }
                
                

  // $mailto = 'info@zazadun.ru';
  $mailto = 'makarenok.roman@gmail.com';
                $send = SendMail::send($mailto,$subject,$content,true);

                if(!isset($send->ErrorInfo) && !empty($send->ErrorInfo)){
                    VarDumper::dump($send->ErrorInfo); die(); // Ctrl + X    Delete line
                }
                if($send){
                    $message = 'Ваше сообщение успешно отправлено.';
                    if (Yii::app()->request->isAjaxRequest){
                        echo CJSON::encode(array(
                            'flag' => true,
                            'message' => $message
                        ));
                    } else {
                        Yii::app()->user->setFlash('success', $message);
                        $this->refresh();
                    }
                } else {
                    $message = 'Ошибка отправки сообщения.';
                   // $message .= $send->ErrorInfo;
                    if (Yii::app()->request->isAjaxRequest){
                        echo CJSON::encode(array(
                            'flag' => false,
                            'message' => $message
                        ));
                    } else {
                        Yii::app()->user->setFlash('error', $message);
                        $this->refresh();
                    }
                }

                Yii::app()->end();
            }
            }
        }

        Yii::app()->end();
    }
     public function actionLogin() {
      // Yii::app()->user->loginRequired();
      //  Yii::app()->end();
        $this->redirect('/login');
        Yii::app()->end();

        $this->layout = '//layouts/login';
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }


    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        
        Yii::app()->user->logout();
        Yii::app()->user->clearStates();
        $this->redirect(Yii::app()->homeUrl);
    }


 

    public function actionGetCities($id=null)
    {
        $id = (int)$id;
        $cr = new CDbCriteria;
      /*  $cr->with = array('cityOther'=>array(
            'condition'=>'(hh_city_id is not null or sj_city_id is not null)'
            )
        );
        $cr->together = true; */
        $cr->condition = '(pos>0 and pos<10000)';
        $cr->order = 'title';
        $cities_db = City::model()->findAll($cr);
        $cities = $arWrapper = array();
        if($cities_db){
            foreach($cities_db as $city){
              if($id != $city->id){
                $fs = MHelper::String()->my_ucfirst($city->title);
                $fs = MHelper::String()->substr($fs,0,1);
                $cities[$city->id] = array('url'=>$this->createUrl('/catalog/catalog/index',array('city'=>$city->url)), 'title'=>$city->title, 'data_ukfirst'=>$fs);
                 //  $cities .= '<div class="key"><a href="'.$this->createUrl('/catalog/catalog/index',array('city'=>$value['url'])).'">'.$value['title'].'</a></div>';
              }
              
            }
            if(!empty($cities)){
                $arWrapper['k'] = array_keys($cities);
                $arWrapper['v'] = array_values($cities);
                echo CJSON::encode(array('status'=>'OK','arWrapper'=>$arWrapper));
                Yii::app()->end();
            }
        }
        echo CJSON::encode(array('status'=>'ERROR'));
        Yii::app()->end();
    }

    public function actionGetCitiesBig($id=null)
    {
    	$id = (int)$id; $add = '';
    	if(isset($_POST['term']) && !empty(trim($_POST['term']))) {
            $term = $_POST['term'];
            $match = MHelper::String()->toLower(addslashes(trim($_POST['term'])));
            $add .= " and (LOWER(title) LIKE '%$match%') ";
        }
        $limit = null;
        if(isset($_POST['limit']) && !empty(trim($_POST['limit']))) {
        	$limit = (int)$_POST['limit'];
        }
    	$cities = $arWrapper = array();
    	if(!empty($add)){
    		
    	} else {
	        $cr = new CDbCriteria;
	      	$cr->condition = 'pos=1'.$add;
	        $cities_db = City::model()->findAll($cr);
	        if($cities_db){
	            foreach($cities_db as $city){

	                $fs = MHelper::String()->my_ucfirst($city->title);
	                $fs = MHelper::String()->substr($fs,0,1);
	                $cities[$city->id] = array('url'=>$this->createUrl('/catalog/catalog/index',array('city'=>$city->url)), 'title'=>$city->title, 'data_ukfirst'=>$fs, 'data_star'=>true);
	              
	              if(!empty($cities)){
	                $arWrapper['k'] = array_keys($cities);
	                $arWrapper['v'] = array_values($cities);
	              }
	        	}
	    	}
    	}
    	$cr = new CDbCriteria;
    	if(!empty($add)){
    		$cr->condition = '(pos>0 and pos<10000) '.$add;
    	} else {
    		if($limit)
    			$cr->condition = '(pos>1 and pos<21)';
    		else
    			$cr->condition = '(pos>1 and pos<10000)';
    	}
        $cr->order = 'title';
        if($limit)
        	$cr->limit = 20;
        $cities_db = City::model()->findAll($cr);
        
        if($cities_db){
            foreach($cities_db as $city){
             // if($id && $id != $city->id){
                $fs = MHelper::String()->my_ucfirst($city->title);
                $fs = MHelper::String()->substr($fs,0,1);
                $cities[$city->id] = array('url'=>$this->createUrl('/catalog/catalog/index',array('city'=>$city->url)), 'title'=>$city->title, 'data_ukfirst'=>$fs, 'data_star'=>false);
                 //  $cities .= '<div class="key"><a href="'.$this->createUrl('/catalog/catalog/index',array('city'=>$value['url'])).'">'.$value['title'].'</a></div>';
             // }
              
            }
            if(!empty($cities)){
                $arWrapper['k'] = array_keys($cities);
                $arWrapper['v'] = array_values($cities);
                echo CJSON::encode(array('status'=>'OK','arWrapper'=>$arWrapper));
                Yii::app()->end();
            }
        }
        echo CJSON::encode(array('status'=>'OK','arWrapper'=>$arWrapper));
        Yii::app()->end();
    }



    
    public function addNewCity($city)
    {
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
    public function addAddress($words)
    {
    	$words = explode(',',$words);
    	$street = $dom = null;
    	if(!empty($words)){

							$now = null;
							foreach($words as $k => $word){
								if((mb_strpos($word, 'ул.', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'просп.', 0, 'UTF-8') !== false ) || 
									(mb_strpos($word, 'пер.', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'пл.', 0, 'UTF-8') !== false ) || 
									(mb_strpos($word, 'пр-д', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'ш.', 0, 'UTF-8') !== false )
									|| (mb_strpos($word, 'наб.', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'тупик', 0, 'UTF-8') !== false )
									|| (mb_strpos($word, 'дор.', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'линия', 0, 'UTF-8') !== false )
									|| (mb_strpos($word, 'аллея', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'шос.', 0, 'UTF-8') !== false ) 
									|| (mb_strpos($word, 'бул.', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'прд.', 0, 'UTF-8') !== false )
									|| (mb_strpos($word, 'алл.', 0, 'UTF-8') !== false ) || (mb_strpos($word, 'мкр.', 0, 'UTF-8') !== false )){
									// еще бы добавить МКАД, тракт, пос., микрорайон
									$street = trim($word);
									$now = $k;
									break;
								}
							}
							if(($now === 0 || $now>0) && isset($words[$now+1])){
								$dom = trim($words[$now+1]);
								if(isset($words[$now+2])) $dom .= ', '. trim($words[$now+2]);
								if(isset($words[$now+3])) $dom .= ', '. trim($words[$now+3]);
							}
							if(empty($street)){ // если таки улица не определилась
								if(isset($words[0])){
									$street = $words[0];
								}
							}

						}
		$ret = array('street'=>$street,'dom'=>$dom);
		return $ret; 
    }

     /* Авторизация через Facebook
     */
    public function actionFblogin()
    {

        $redirectUrl = '/';
        if(Yii::app()->session['socredirect']){
            $redirectUrl = Yii::app()->session['socredirect'];
            unset(Yii::app()->session['socredirect']);
        }

        

        if (empty($_GET['code'])){
            $this->redirect($redirectUrl);
            die();
        }

        $params = array(
            'client_id' => Yii::app()->fbApi->clientId,
            'client_secret' => Yii::app()->fbApi->clientSecret,
            'redirect_uri' => Yii::app()->fbApi->redirectUri,
            'code' => $_GET['code']
        );

      //  $contents = @file_get_contents('https://graph.facebook.com/oauth/access_token?' . http_build_query($params));
        $contents = Controller::getRemoteContents('https://graph.facebook.com/oauth/access_token?' . http_build_query($params));
        parse_str($contents, $tokenInfo);


        $accountInfo = array();
        if (!empty($tokenInfo['access_token'])){
            $params = array('access_token' => $tokenInfo['access_token']);
          //  $userInfo = @json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);
            $userInfo = @json_decode(Controller::getRemoteContents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);
            if (!empty($userInfo['id']))
                $accountInfo = $userInfo;
        }

        // если пользователь не найден
        if (empty($accountInfo)){
            $this->redirect($redirectUrl);
            die();
        }

        $login = 'fb'.$accountInfo['id'];
        $password = crc32($accountInfo['id']);
        $authenticate = $this->enterSite($login, $password);

        if (!Yii::app()->user->isGuest){ // вошли

            $model = Yii::app()->user->getModel();
            $this->redirect($redirectUrl);
            die();
        }


        if (!$authenticate) {
            
            $avatarUrl = "http://graph.facebook.com/" . $accountInfo['id'] . "/picture?type=large";

            Yii::app()->session['registerAccountInfo'] = array(
                'username' => $login,
                'password' => $password,
                'interests' => '',
                'avatarUrl' => $avatarUrl,
                'token' => $tokenInfo['access_token'],
                'social' => 'facebook'
            );

            $model = new FormRegisterSocial;
            $model->username = $login;
            $model->password = $password;
            $model->photo = $avatarUrl;
            $model->fullname = !empty($accountInfo['name'])?$accountInfo['name']:$login;
            $model->email = !empty($accountInfo['email'])?$accountInfo['email']:null;
            $model->avatar_enc=!empty($model->photo)?base64_encode(Controller::getRemoteContents($model->photo)):'';
            $model->from_soc_network=true;
            $model->soc_network_name='facebook';


            // если подхваченные данные валидны - регистрируем
            if ($model->validate()){
                $soucePassword = $model->password;
                $model->activkey = UsersModule::encrypting(microtime() . $model->password);
                $model->password = UsersModule::encrypting($model->password);
                $model->superuser = 0;
                $model->status = User::STATUS_ACTIVE;
                if($model->save()){

                    // удаляем регистрационные данные из сессии
                    Yii::app()->session['registerAccountInfo'] = null;

                   
                    $model->chickPhoto();
                    $this->enterSite($login, $soucePassword);
                    
                }
                $this->redirect($redirectUrl);
                Yii::app()->end();
            }
            else{
                $this->addFlashMessage($model->errors, 'error');
                $model->clearErrors();
            }

            $this->redirect($redirectUrl);
            Yii::app()->end();
            /*
            $this->render('registersocial', array(
                'model' => $model,
                'login' => $login,
                'filetoken' => sha1(time() . rand()),
            ));*/
        }
    }
    protected function enterSite($login, $password){

        $identity = new UserIdentity($login, $password);

        if ($identity->authenticate())
        {
            $duration = 3600 * 24 * 30;
            Yii::app()->user->login($identity, $duration);
            if (!Yii::app()->user->isGuest){

                $lastVisit = User::model()->findByPk(Yii::app()->user->id);
                $lastVisit->lastvisit = time();
                $lastVisit->save();
                
            }
            return true;
        }
        return false;
    }
    /**
     * Авторизация через ВКонтакте
     */
    public function actionVklogin()
    {
       
        $redirectUrl = '/';
       if(Yii::app()->session['socredirect']){
            $redirectUrl = Yii::app()->session['socredirect'];
            unset(Yii::app()->session['socredirect']);
        }

        

        if (empty($_GET['code'])){
            $this->redirect($redirectUrl);
            die();
        }

        $accountInfo = array();
        $tokenInfo = Yii::app()->vkApi->getTokenClient($_GET['code'], Yii::app()->vkApi->redirectUri);

        if (!empty($tokenInfo['access_token']))
        {
            $userInfo = Yii::app()->vkApi->callMethod('users.get', array(
                'uids' => $tokenInfo['user_id'],
                'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,city,interests,movies,tv,books,games,about'
            ));
            if (!empty($userInfo['response'][0]['uid']))
                $accountInfo = $userInfo['response'][0];
        }

        // если пользователь не найден
        if (empty($accountInfo)){
            $this->redirect($redirectUrl);
            die();
        }

        $login = 'vk' . $accountInfo['uid'];
        $password = crc32($accountInfo['uid']);

       // пробуем авторизоваться и войти
        $authenticate = $this->enterSite($login, $password);
        if (!Yii::app()->user->isGuest){ // вошли
            $model = Yii::app()->user->getModel();
            $this->redirect($redirectUrl);
            die();
        }


        if (!$authenticate) {

            $avatarUrl = null;
            if (!empty($accountInfo['photo_big']))
                $avatarUrl = $accountInfo['photo_big'];

            Yii::app()->session['registerAccountInfo'] = array(
                'username' => $login,
                'password' => $password,
                'avatarUrl' => $avatarUrl,
                'uid' => $tokenInfo['user_id'],
                'token' => $tokenInfo['access_token'],
                'social' => 'vkontakte'
            );
            $fullname = !empty($accountInfo['first_name'])?$accountInfo['first_name']:null;
            $fullname .= !empty($accountInfo['last_name'])?" " . $accountInfo['last_name']:null;

            $model = new FormRegisterSocial;
            $model->username = $login;
            $model->password = $password;
            $model->photo = $avatarUrl;
            $model->fullname = !empty($fullname)?$fullname:$login;
            $model->email = null;
            $model->avatar_enc=!empty($model->photo)?base64_encode(Controller::getRemoteContents($model->photo)):'';
            $model->from_soc_network=true;
            $model->soc_network_name='vkontakte';

            
            // если подхваченные данные валидны - регистрируем
            if ($model->validate()){
                $soucePassword = $model->password;
                $model->activkey = UsersModule::encrypting(microtime() . $model->password);
                $model->password = UsersModule::encrypting($model->password);
                $model->superuser = 0;
                $model->status = User::STATUS_ACTIVE;
                if($model->save()){

                    // удаляем регистрационные данные из сессии
                    Yii::app()->session['registerAccountInfo'] = null;

                   
                    $model->chickPhoto();
                    $this->enterSite($login, $soucePassword);
                    
                }
  
                $this->redirect($redirectUrl);
                Yii::app()->end();
            }
            else{
                $this->addFlashMessage($model->errors, 'error');
                $model->clearErrors();
            }

            $this->redirect($redirectUrl);
            Yii::app()->end();

          /*  $this->render('registersocial', array(
                'model' => $model,
                'login' => $login,
                'filetoken' => sha1(time() . rand()),
            ));*/
        }
    }

    public function actionTwlogin()
    {

        $redirectUrl = '/';
        if(isset(Yii::app()->session['twredirect']) && !empty(Yii::app()->session['twredirect'])){
            $redirectUrl = Yii::app()->session['twredirect'];
            unset(Yii::app()->session['twredirect']);
        } 
        
        if (isset($_REQUEST['oauth_token']) && Yii::app()->session['oauth_token'] !== $_REQUEST['oauth_token']) {
            Yii::app()->session['oauth_status'] = 'oldtoken';
        }
 
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $twitter = Yii::app()->twitter->getTwitterTokened(Yii::app()->session['oauth_token'], Yii::app()->session['oauth_token_secret']);   
 
        /* Request access tokens from twitter */
        if(!isset($_REQUEST['oauth_verifier'])){
            $this->redirect($redirectUrl);
            die();
        }
      //  $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
        $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']);
        /* Save the access tokens. Normally these would be saved in a database for future use. */
        Yii::app()->session['access_token'] = $access_token;
 
        /* Remove no longer needed request tokens */
        unset(Yii::app()->session['oauth_token']);
        unset(Yii::app()->session['oauth_token_secret']);
 
        if (200 == $twitter->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            Yii::app()->session['status'] = 'verified';
 
            //get an access twitter object
            $twitter = Yii::app()->twitter->getTwitterTokened($access_token['oauth_token'],$access_token['oauth_token_secret']);
 
            //get user details
            $accountInfo= $twitter->get("account/verify_credentials");
            //get friends ids
           // $friends= $twitter->get("friends/ids");
                        //get followers ids
            //    $followers= $twitter->get("followers/ids");
            //tweet
             //           $result=$twitter->post('statuses/update', array('status' => "Tweet message"));
 

        // если пользователь не найден
        if (empty($accountInfo)){
            $this->redirect($redirectUrl);
            die();
        }

        $login = 'tw'.$accountInfo->id;
        $password = crc32($accountInfo->id);

        // пробуем авторизоваться и войти
        $authenticate = $this->enterSite($login, $password);
        if (!Yii::app()->user->isGuest){ // вошли
            $model = Yii::app()->user->getModel();
            $this->redirect($redirectUrl);
            die();
        }

        if (!$authenticate) {

            Yii::app()->session['registerAccountInfo'] = array(
                'username' => $login,
                'password' => $password,
                'interests' => '',
                'avatarUrl' => $accountInfo->profile_image_url,
                'token' => $access_token['oauth_token'],
                'social' => 'twitter'
            );

            if(!empty($accountInfo->profile_image_url)){
                $img = str_replace('_normal','_bigger', $accountInfo->profile_image_url);
            } else {
                $img = null;
            }
            $model = new FormRegisterSocial;
            $model->username = $login;
            $model->password = $password;
            $model->photo = $img;
            $model->fullname = !empty($accountInfo->name)?$accountInfo->name:$login;
            $model->email = null;
            $model->avatar_enc=!empty($model->photo)?base64_encode(Controller::getRemoteContents($model->photo)):'';
            $model->from_soc_network=true;
            $model->soc_network_name='twitter';

            

            // если подхваченные данные валидны - регистрируем
            if ($model->validate()){
                $soucePassword = $model->password;
                $model->activkey = UsersModule::encrypting(microtime() . $model->password);
                $model->password = UsersModule::encrypting($model->password);
                $model->superuser = 0;
                $model->status = User::STATUS_ACTIVE;
                if($model->save()){

                    // удаляем регистрационные данные из сессии
                    Yii::app()->session['registerAccountInfo'] = null;

                   
                    $model->chickPhoto();
                    $this->enterSite($login, $soucePassword);
                    
                }
     
                $this->redirect($redirectUrl);
                Yii::app()->end();
            }
            else{
                $this->addFlashMessage($model->errors, 'error');
                $model->clearErrors();
            }

            $this->redirect($redirectUrl);
            Yii::app()->end();

           /* $this->render('registersocial', array(
                'model' => $model,
                'login' => $login,
                'filetoken' => sha1(time() . rand()),
            ));*/
        }
        } else {
            /* Save HTTP status for error dialog on connnect page.*/
            //header('Location: /clearsessions.php');
          //  $this->redirect(Yii::app()->homeUrl);
            $this->redirect($redirectUrl);
            Yii::app()->end();
        }
    
    }
    public function actionGotwitter()
     {
        $redirectUrl = '/';
        if(Yii::app()->session['socredirect']){
            $redirectUrl = Yii::app()->session['socredirect'];
            unset(Yii::app()->session['socredirect']);
        }


        Yii::app()->session['twredirect'] = $redirectUrl;


        $twitter = Yii::app()->twitter->getTwitter(); 
        $request_token = $twitter->getRequestToken();

         //set some session info
         Yii::app()->session['oauth_token'] = $token =  $request_token['oauth_token'];
         Yii::app()->session['oauth_token_secret'] = $request_token['oauth_token_secret'];
       // Yii::app()->session['oauth_token'] = $token =  Yii::app()->twitter->oauth_token;
       //  Yii::app()->session['oauth_token_secret'] = Yii::app()->twitter->oauth_secret;
 
        if($twitter->http_code == 200){
            //get twitter connect url
            $url = $twitter->getAuthorizeURL($token);

            //send them
            $this->redirect($url);
        }else{
            //error here
            $this->redirect($redirectUrl);
        }
 
 
    }

    public function actionGetMapS()
    {
    	$this->renderPartial('getmaps');
    }
}