<?php
class CompanyController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'company';
    public $uploadsession = 'orgsfiles';
    public $uploadlogosession = 'orgslogofiles';
    public $pageTitle = 'Компании';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, statuslocator, updatestatus,  deletefile, deletelogofile, uploadlogo, unlinklogo, autocompletetitle,  autocompleteaddress, autocompleteloguser, autocompletecity, autocompletestreet, autocompletedom, autocompleterubrics',
        );
    }

    public function actions()
    {
        return array(
            'massupdate' => 'application.modules.catalog.controllers.admin.actions.Massupdate',
        );
    }
   

    public function actionIndex() {

        $this->pageTitle = 'Компании';
        $this->active_link = 'company';
        $model = new Orgs('search');
        $model->unsetAttributes();  // clear any default values
        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = '.Orgs::STATUS_VERIFIED;
        $model_search = $model->search(array(),$additionalCriteria);
        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
        ));
    }

    public function actionNewCompanies() {

        $this->pageTitle = 'Новые компании';
        $this->active_link = 'newcompany';
        $model = new Orgs('search');
        $model->unsetAttributes();  // clear any default values


        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = '.Orgs::STATUS_NOT_VERIFIED;

        $model_search = $model->search(array(),$additionalCriteria);
         
        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
        ));
    }

    public function actionUpdateCompanies() {

        $this->pageTitle = 'Измененные компании';
        $this->active_link = 'updatecompany';
        $model = new Orgs('search');
        $model->unsetAttributes();  // clear any default values


        $additionalCriteria = new CDbCriteria;
        $additionalCriteria->condition = 't.verified = '.Orgs::STATUS_UPDATE_VERIFIED;

        $model_search = $model->search(array(),$additionalCriteria);
         
        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
        ));
    }
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($new = false)
    {
        if ($new === true){
            $model = new Orgs;
        } else {
            $id = (int)$_GET['id'];
            $model=$this->loadModel($id);
            if($model->verified == Orgs::STATUS_VERIFIED){
                $this->pageTitle = 'Компании';
                $this->active_link = 'company';
            } else if($model->verified == Orgs::STATUS_NOT_VERIFIED){
                $this->pageTitle = 'Новые компании';
                $this->active_link = 'newcompany';
            } else {
                $this->pageTitle = 'Измененные компании';
                $this->active_link = 'updatecompany';
            }
        }

        // Uncomment the following line if AJAX validation is needed
         $this->performAjaxValidation($model, 'orgs-form');

        if(isset($_POST['Orgs']))
        {

            $model->attributes=$_POST['Orgs'];

            if($model->save()){
                
                $model->setPhones($model->phones, $model->phone_comments);

                $model->setHttp($model->http, $model->http_comments);

                $open_door = $close_door = $break_door = $endbreak_door = array();
                if(!empty($_POST['open_door'])){ $open_door = $_POST['open_door']; }
                if(!empty($_POST['close_door'])){ $close_door = $_POST['close_door']; }
                if(!empty($_POST['break_door'])){ $break_door = $_POST['break_door']; }
                if(!empty($_POST['endbreak_door'])){ $endbreak_door = $_POST['endbreak_door']; }



                OrgsWorktime::setWorktime($model->id, $open_door, $close_door, $break_door, $endbreak_door);


                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = $new? "Компания {$model->title} добавлена" : "Компания {$model->title} отредактирована";
                    $this->addFlashMessage($text,'success');
                    if($model->verified == Orgs::STATUS_VERIFIED) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/company'));
                    } else if($model->verified == Orgs::STATUS_NOT_VERIFIED){
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/company/newcompanies'));
                    } else {
                        $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/company/updatecompanies'));
                   
                    }

                }

            } else {
                $this->addFlashMessage($model->errors,'error');
                $this->refresh();
            }
        }
        $phones = $model->orgsPhones;


        $http = $model->orgsHttp;
   
        $worktime = $model->orgsWorktimes;

     /*   $criteria = new CDbCriteria;
        $criteria->condition = 'model_name=:model_name and model_id='.$model->id;
        $criteria->params = array(':model_name'=>'Orgs');
        $modelLog = new ActionLog('search');

        if (!empty($_GET['ActionLog']))
            $modelLog->attributes = $_GET['ActionLog'];

        $dataProviderHistory = $modelLog->search($criteria);
        $dataProviderHistory->pagination->pageSize = 10;*/

        $criteria = new CDbCriteria;
        $criteria->condition = 'org_id=:org_id';
        $criteria->params = array(':org_id'=>$model->id);
        $criteria->order = 'created_date DESC';
        $modelStatus = new OrgsStatus('search');

        if (!empty($_GET['OrgsStatus']))
            $modelStatus->attributes = $_GET['OrgsStatus'];

        $dataProviderStatus = $modelStatus->search(array(),$criteria);
        $dataProviderStatus->pagination->pageSize = 20;

        $this->render('update',array(
            'model'=>$model,
            'phones'=>$phones,
            'http'=>$http,
            'worktime'=>$worktime,
            'dataProviderStatus'=>$dataProviderStatus
           // 'dataProviderHistory'=>$dataProviderHistory,
           // 'modelLog'=>$modelLog
        ));
    }

    public function actionStatusLocator($id){

        $model = new OrgsStatus;
        if(isset($_POST['ajax']) && $_POST['ajax']==='form-statuslocator')
        {
           $model->attributes = $_POST['OrgsStatus'];
           $errors = CActiveForm::validate($model);
                if ($errors !== '[]') {
                   echo $errors;
                   Yii::app()->end();
            } 
        }
        if(isset($_POST['preview']) && $_POST['preview'] == 1){
            $message = $model->getFullText();
            echo CJSON::encode(array(
                'flag' => true,
                'preview' => true,
                'message'=>$message
            ));
            
            Yii::app()->end();
        }
        if($model->save()){

            $mailto = $model->org->authorid->email;
            $subject = 'Уведомление о компании '.$model->org->title;
            $content = $model->getFullText();
            $content .= '<br><br>Подробнее <a href="http://'.Yii::app()->params['serverName'].'">inlocator.ru</a>';
            $send = SendMail::send($mailto,$subject,$content,true);

            echo CJSON::encode(array(
                'flag' => true,
                'preview' => false,
            ));
        }
    }

    public function actionUploadLogo(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadlogosession);
        $uploader->inputName = 'logotip';
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }

    public function actionUnlinkLogo(){

        $fileName = $_POST['name'];

        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(Yii::app()->session->itemAt($this->uploadlogosession)){
            $datas = Yii::app()->session->itemAt($this->uploadlogosession);
            if(is_array($datas)){
                $mm = $datas;
                foreach($mm as $key => $value){
                    if($fileName == $key){
                        if(file_exists($folder.$value )) {
                            unlink($folder.$value);
                            unset($datas[$key]);
                        }
                    break;
                    }
                }
                Yii::app()->session->add($this->uploadlogosession,$datas);
            }

        }

    }
 

    public function actionUpdatestatus($id, $status)
    {
        $id = (int)$id;
        $model = Orgs::model()->findByPk($id);

        if (!empty($model) && ($status == Orgs::STATUS_ACTIVE || $status == Orgs::STATUS_NOT_ACTIVE))
        {
           $model->status_org = $status;
           // $model->isNewRecord = false;
           // $model->saveAttributes(array('status_org'));
           if($model->save(false,'status_org')){
                
           }
        }
        echo '[]';
    }


    public function actionDelete($id)
    {
        $id = (int)$id;
        $model = Orgs::model()->findByPk($id);

        if ($model)
        {

            $log = new ActionLog;
            $log->user_id = Yii::app()->user->id;
            $log->event = ActionLog::ACTION_DELETE;
            $log->model_name = 'Orgs';
            $log->datetime = date('Y-m-d H:i:s');
            $log->model_id = $model->id;
            $log->save();

            $logcheck = ActionLog::model()->findAll(array('condition'=>'model_name=:model_name and model_id=:model_id','params'=>array(':model_name'=>'Orgs',':model_id'=>$id)));
            if($logcheck) {
                foreach ($logcheck as  $logch) {
                    $logch->model_title = $model->title;
                    $logch->save(false, array('model_title'));
                }
            }  
            $model->delete();
           
        }
        echo '[]';
    }

    public function actionDeleteFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = OrgsImages::model()->findByPk($id);
        if(isset($file->organization)){
           // $pin = $this->_loadModel($file->pinboard->id);
            // remove it from disk also in model issueFile
            if($file->delete()) {
                
                echo "[]";
                Yii::app()->end();
            }
        }
        echo 'error';
        Yii::app()->end();
    }
    public function actionDeleteLogoFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = Orgs::model()->findByPk($id);
        if(!empty($file)){
           // Delete file
            $filename = $file->logotip;
            $imagePath = $file->getFileFolder() . $filename;
            if(file_exists($imagePath)) {
                unlink($imagePath); //delete file
            }
            $file->logotip = '';
            $file->logotip_realname = '';
            $file->save(false,array('logotip','logotip_realname'));
                echo "[]";
                Yii::app()->end();
        }

        
        echo 'error';
        Yii::app()->end();
    }
    public function actionAutoCompleteTitle($term)
    {

         if(isset($_GET['term'])) {
            $arr = array();
          /*  $criteria = new CDbCriteria();
            $criteria->compare('LOWER(title)',MHelper::String()->toLower($_GET['term']),true);
            $criteria->compare('LOWER(description)',MHelper::String()->toLower($_GET['term']),true,'OR');
            $criteria->compare('LOWER(synonim)',MHelper::String()->toLower($_GET['term']),true,'OR');
            $criteria->limit = 50;
            $model = new Orgs;
            if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $find = $model->deleted()->findAll($criteria);
             } else if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_ACTIVE){
                $find = $model->active()->findAll($criteria);
             } else {
                $find = $model->findAll($criteria);
             }*/
             $status = '';
            if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $status .= ' and orgs.status_org='.Orgs::STATUS_NOT_ACTIVE;
             } else if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_ACTIVE){
                $status .= ' and orgs.status_org='.Orgs::STATUS_ACTIVE;
             } 
            $street = '';
            if(isset($_GET['street'])) {
                $matchstreet = MHelper::String()->toLower(addslashes(trim($_GET['street'])));
                $street .= " and (LOWER(orgs.street) LIKE '%$matchstreet%' )";
            }
            $dom = '';
            if(isset($_GET['dom'])) {
                $matchdom = MHelper::String()->toLower(addslashes(trim($_GET['dom'])));
                $dom .= " and (LOWER(orgs.dom) LIKE '%$matchdom%' )";
            }
            $city = '';
            if(isset($_GET['city_search'])) {
                $matchcity = MHelper::String()->toLower(addslashes(trim($_GET['city_search'])));
                $city = " and (LOWER(city.title) LIKE '%$matchcity%')";
            }
            $rubric_title = '';
            if(isset($_GET['rubric_title'])) {
                $matchrubric = MHelper::String()->toLower(addslashes(trim($_GET['rubric_title'])));
                $rubric_title = " and (LOWER(category.title) LIKE '%$matchrubric%')";
            }
            $match = MHelper::String()->toLower(addslashes(trim($_GET['term'])));
            $title = "(LOWER(orgs.title) LIKE '%$match%')";

            $connection=Yii::app()->db;
            $sql = 'SELECT DISTINCT orgs.title FROM orgs
            LEFT OUTER JOIN city  
            ON (city.id = orgs.city_id) 
            LEFT OUTER JOIN orgs_category
            ON (orgs_category.org = orgs.id) 
            LEFT OUTER JOIN category 
            ON (orgs_category.category = category.id) 
            WHERE   '.$title.' '.$rubric_title.' '.$status.' '.$city.' '.$street.'   '.$dom.'
            ORDER BY orgs.title
            LIMIT 30';
            $command=$connection->createCommand($sql);
            $find = $command->queryAll();
            if(!empty($find)){
                foreach($find as $m){
                    $addr = $m['title'];
                   // if(!in_array($addr, $arr))
                    $arr[] = $addr;
                }
            }

 
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }

    public function actionAutoCompleteCity($term)
    {
         if(isset($_GET['term'])) {
            $term = $_GET['term'];
            $arr = array();
            $status = '';
            if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $status .= ' and orgs.status_org='.Orgs::STATUS_NOT_ACTIVE;
             } else if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_ACTIVE){
                $status .= ' and orgs.status_org='.Orgs::STATUS_ACTIVE;
             } 

            $title = '';
            if(isset($_GET['title'])) {
                $matchtitle = MHelper::String()->toLower(addslashes(trim($_GET['title'])));
                $title .= " and (LOWER(orgs.title) LIKE '%$matchtitle%' )";
            }

            $street = '';
            if(isset($_GET['street'])) {
                $matchstreet = MHelper::String()->toLower(addslashes(trim($_GET['street'])));
                $street .= " and (LOWER(orgs.street) LIKE '%$matchstreet%' )";
            }
            $dom = '';
            if(isset($_GET['dom'])) {
                $matchdom = MHelper::String()->toLower(addslashes(trim($_GET['dom'])));
                $dom .= " and (LOWER(orgs.dom) LIKE '%$matchdom%' )";
            }
            $rubric_title = '';
            if(isset($_GET['rubric_title'])) {
                $matchrubric = MHelper::String()->toLower(addslashes(trim($_GET['rubric_title'])));
                $rubric_title = " and (LOWER(category.title) LIKE '%$matchrubric%')";
            }
     
            $match = MHelper::String()->toLower(addslashes(trim($term)));
            $city = "(LOWER(city.title) LIKE '%$match%')";
            $connection=Yii::app()->db;
            if(isset($_GET['status_org'])){
            
            $sql = 'SELECT DISTINCT city.title from city
            LEFT OUTER JOIN orgs  
            ON (city.id = orgs.city_id) 
            LEFT OUTER JOIN orgs_category
            ON (orgs_category.org = orgs.id) 
            LEFT OUTER JOIN category 
            ON (orgs_category.category = category.id)
            WHERE   '.$city.'  '.$title.' '.$rubric_title.' '.$status.'  '.$street.'   '.$dom.'
            ORDER BY city.title
            LIMIT 30';
          } 
            $command=$connection->createCommand($sql);
            $find = $command->queryAll();
            if(!empty($find)){
                foreach($find as $m){
                    $addr = $m['title'];
                   // if(!in_array($addr, $arr))
                    $arr[] = $addr;
                }
            }
 
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }
     public function actionAutoCompleteStreet($term)
    {
         if(isset($_GET['term'])) {
            $term = $_GET['term'];
            $arr = array();

            $status = '';
            if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $status .= ' and orgs.status_org='.Orgs::STATUS_NOT_ACTIVE;
             } else if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_ACTIVE){
                $status .= ' and orgs.status_org='.Orgs::STATUS_ACTIVE;
             } 

            $title = '';
            if(isset($_GET['title'])) {
                $matchtitle = MHelper::String()->toLower(addslashes(trim($_GET['title'])));
                $title .= " and (LOWER(orgs.title) LIKE '%$matchtitle%' )";
            }

            $city = '';
            if(isset($_GET['city_search'])) {
                $matchcity = MHelper::String()->toLower(addslashes(trim($_GET['city_search'])));
                $city = " and (LOWER(city.title) LIKE '%$matchcity%')";
            }
            $dom = '';
            if(isset($_GET['dom'])) {
                $matchdom = MHelper::String()->toLower(addslashes(trim($_GET['dom'])));
                $dom .= " and (LOWER(orgs.dom) LIKE '%$matchdom%' )";
            }
            $rubric_title = '';
            if(isset($_GET['rubric_title'])) {
                $matchrubric = MHelper::String()->toLower(addslashes(trim($_GET['rubric_title'])));
                $rubric_title = " and (LOWER(category.title) LIKE '%$matchrubric%')";
            }

            $match = MHelper::String()->toLower(addslashes(trim($_GET['term'])));
            $street = " (LOWER(orgs.street) LIKE '%$match%') ";

            $connection=Yii::app()->db;
            if(isset($_GET['status_org'])){
            $sql = 'SELECT DISTINCT orgs.street FROM orgs
            LEFT OUTER JOIN city  
            ON (city.id = orgs.city_id) 
            LEFT OUTER JOIN orgs_category
            ON (orgs_category.org = orgs.id) 
            LEFT OUTER JOIN category 
            ON (orgs_category.category = category.id) 
            WHERE   '.$street.' '.$title.' '.$rubric_title.' '.$status.'  '.$city.'   '.$dom.' 
            ORDER BY orgs.street
            LIMIT 30';
            } 
            $command=$connection->createCommand($sql);
            $find = $command->queryAll();
            if(!empty($find)){
                foreach($find as $m){
                    $addr = $m['street'];
                   // if(!in_array($addr, $arr))
                    $arr[] = $addr;
                }
            }
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }
    public function actionAutoCompleteDom($term)
    {
         if(isset($_GET['term'])) {
            $term = $_GET['term'];
            $arr = array();

            $status = '';
            if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $status .= ' and orgs.status_org='.Orgs::STATUS_NOT_ACTIVE;
             } else if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_ACTIVE){
                $status .= ' and orgs.status_org='.Orgs::STATUS_ACTIVE;
             } 

            $title = '';
            if(isset($_GET['title'])) {
                $matchtitle = MHelper::String()->toLower(addslashes(trim($_GET['title'])));
                $title .= " and (LOWER(orgs.title) LIKE '%$matchtitle%' )";
            }

            $city = '';
            if(isset($_GET['city_search'])) {
                $matchcity = MHelper::String()->toLower(addslashes(trim($_GET['city_search'])));
                $city = " and (LOWER(city.title) LIKE '%$matchcity%')";
            }
            $street = '';
            if(isset($_GET['street'])) {
                $matchstreet = MHelper::String()->toLower(addslashes(trim($_GET['street'])));
                $street .= " and (LOWER(orgs.street) LIKE '%$matchstreet%' )";
            }
            $dom = '';
            if(isset($_GET['dom'])) {
                $matchdom = MHelper::String()->toLower(addslashes(trim($_GET['dom'])));
                $dom .= " and (LOWER(orgs.dom) LIKE '%$matchdom%' )";
            }
            $rubric_title = '';
            if(isset($_GET['rubric_title'])) {
                $matchrubric = MHelper::String()->toLower(addslashes(trim($_GET['rubric_title'])));
                $rubric_title = " and (LOWER(category.title) LIKE '%$matchrubric%')";
            }

            $match = MHelper::String()->toLower(addslashes(trim($_GET['term'])));
            $dom = " (LOWER(orgs.dom) LIKE '%$match%') ";

            $connection=Yii::app()->db;
            if(isset($_GET['status_org'])){
            $sql = 'SELECT DISTINCT orgs.dom FROM orgs
            LEFT OUTER JOIN city  
            ON (city.id = orgs.city_id) 
            LEFT OUTER JOIN orgs_category
            ON (orgs_category.org = orgs.id) 
            LEFT OUTER JOIN category 
            ON (orgs_category.category = category.id) 
            WHERE  '.$dom.'  '.$title.' '.$rubric_title.' '.$status.'  '.$city.'  '.$street.'  
            ORDER BY orgs.dom
            LIMIT 30';
        } 
            $command=$connection->createCommand($sql);
            $find = $command->queryAll();
            if(!empty($find)){
                foreach($find as $m){
                    $addr = $m['dom'];
                   // if(!in_array($addr, $arr))
                    $arr[] = $addr;
                }
            }
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }
    public function actionAutoCompleteAddress($term)
    {
         if(isset($_GET['term'])) {
            $term = $_GET['term'];
            $arr = array();
            // http://www.yiiframework.com/doc/guide/database.dao
           /* $condition ="SELECT id, street FROM {{orgs}} WHERE street LIKE :someField";
            $params = array(":someField"=>'%'.$_GET['term'].'%');
            $rows = Orgs::model()->findAllBySql($condition,$params);
            foreach ($rows as $row)
                {
                        $arr[] = array(
                                'label'=>$row->street,  // label for dropdown list
                                'value'=>$row->street,  // value for input field
                                'id'=>$row->id,            // return value from autocomplete
                        );
                };
            */
            $criteria = new CDbCriteria();
         /*   if(isset($_GET['title']) && !empty($_GET['title'])){
                $criteria->compare('LOWER(t.title)',MHelper::String()->toLower($_GET['title']),true);
                $criteria->compare('LOWER(t.description)',MHelper::String()->toLower($_GET['title']),true,'OR');
                $criteria->compare('LOWER(t.synonim)',MHelper::String()->toLower($_GET['title']),true,'OR');
           
            } */
            $criteria->with = array(
                'city'=>array('together'=>true)
            );
            if (strpos($term,',') !==false) {
                $terms = explode(',',$term); 
                
                if(isset($terms[2]) && !empty($terms[0]) && !empty($terms[1]) && !empty($terms[2])){

                    $criteria->compare('LOWER(city.title)', MHelper::String()->toLower(trim($terms[0])), true);
                    $criteria->compare('LOWER(street)',MHelper::String()->toLower(trim($terms[1])),true);
                    $criteria->compare('LOWER(dom)',MHelper::String()->toLower(trim($terms[2])),true);


                } elseif(isset($terms[1]) && !empty($terms[0]) && !empty($terms[1])){
                   /* $criteria->compare('LOWER(city.title)', MHelper::String()->toLower(trim($terms[0])), true);
                    $criteria->compare('LOWER(street)',MHelper::String()->toLower(trim($terms[1])),true);
                    $criteria->compare('LOWER(dom)',MHelper::String()->toLower(trim($terms[1])),true,'OR');*/
                    $match1 = MHelper::String()->toLower(addslashes(trim($terms[0])));
                    $match2 = MHelper::String()->toLower(addslashes(trim($terms[1])));
                    $criteria->addCondition("LOWER(city.title) LIKE :match1 AND ( LOWER(street) LIKE :match2 OR LOWER(dom) LIKE :match2 )");
                    $criteria->params  = array(':match1' => "%$match1%", ':match2' => "%$match2%");
                    
                }  else {
                    $criteria->compare('LOWER(city.title)', MHelper::String()->toLower($term), true);
                    $criteria->compare('LOWER(street)',MHelper::String()->toLower($term),true);
                    $criteria->compare('LOWER(dom)',MHelper::String()->toLower($term),true);
                }            
                
            } else {
                $criteria->compare('LOWER(city.title)', MHelper::String()->toLower($term), true);
                $criteria->compare('LOWER(street)',MHelper::String()->toLower($term),true, 'OR');
                $criteria->compare('LOWER(dom)',MHelper::String()->toLower($term),true, 'OR');
            }

            $criteria->select = 'city.title, street, dom';
            $criteria->order = 'city.title, street, dom';
            $criteria->limit = 30;
/*          
            $criteria = new CDbCriteria();
            $criteria->compare('LOWER(street)',MHelper::String()->toLower($term),true);
            $criteria->compare('LOWER(dom)',MHelper::String()->toLower($term),true, 'OR');
*/


            $model = new Orgs;
            if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $find = $model->notactive()->findAll($criteria);
             } else if(isset($_GET['status_org']) && $_GET['status_org'] == Orgs::STATUS_ACTIVE){
                $find = $model->pureactive()->findAll($criteria);
             }  else {
                $find = $model->findAll($criteria);
             }
            foreach($find as $m)
            {
                $addr = '';
                if($m->city && $m->city->title)
                    $addr = $m->city->title;
                if($addr && $m->street)
                    $addr .= ', '.$m->street;
                if($addr && $m->dom)
                    $addr .= ', '.$m->dom;
                if(!in_array($addr, $arr))
                    $arr[] = $addr;
            }
 
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }
    public function actionAutoCompleteRubrics()
    {
         
         if(isset($_POST['term'])) {
            $arr = array();
            $term = $_POST['term'];
           /* $criteria = new CDbCriteria();
            $criteria->compare('LOWER(title)', MHelper::String()->toLower($_POST['term']), true);
            $criteria->limit = 50;
            $model = new Category;

            foreach($model->findAll($criteria) as $m)
            {
                $arr[] = $m->title;
            }*/
            $status = '';
            if(isset($_POST['status_org']) && $_POST['status_org'] == Orgs::STATUS_NOT_ACTIVE) {
                $status .= ' and orgs.status_org='.Orgs::STATUS_NOT_ACTIVE;
             } else if(isset($_POST['status_org']) && $_POST['status_org'] == Orgs::STATUS_ACTIVE){
                $status .= ' and orgs.status_org='.Orgs::STATUS_ACTIVE;
             }  
            $title = '';
            if(isset($_POST['title'])) {
                $matchtitle = MHelper::String()->toLower(addslashes(trim($_POST['title'])));
                $title .= " and (LOWER(orgs.title) LIKE '%$matchtitle%' )";
            }
            $city = '';
            if(isset($_POST['city_search'])) {
                $matchcity = MHelper::String()->toLower(addslashes(trim($_POST['city_search'])));
                $city = " and (LOWER(city.title) LIKE '%$matchcity%')";
            }
            $street = '';
            if(isset($_POST['street'])) {
                $matchstreet = MHelper::String()->toLower(addslashes(trim($_POST['street'])));
                $street .= " and (LOWER(orgs.street) LIKE '%$matchstreet%' )";
            }
            $dom = '';
            if(isset($_POST['dom'])) {
                $matchdom = MHelper::String()->toLower(addslashes(trim($_POST['dom'])));
                $dom .= " and (LOWER(orgs.dom) LIKE '%$matchdom%' )";
            }

            $match = MHelper::String()->toLower(addslashes(trim($_POST['term'])));
            $rubric_title = " (LOWER(category.title) LIKE '%$match%') ";

            $connection=Yii::app()->db;
          /*  $sql = 'SELECT DISTINCT category.title FROM category
            LEFT OUTER JOIN orgs_category
            ON (orgs_category.category = category.id) 
            LEFT OUTER JOIN orgs 
            ON (orgs_category.org = orgs.id)
            LEFT OUTER JOIN city  
            ON (city.id = orgs.city_id) 
            WHERE   '.$rubric_title.'  '.$title.'  '.$status.'  '.$city.' '.$street.'  '.$dom.' 
            ORDER BY category.title
            LIMIT 30';*/
            $sql = 'SELECT DISTINCT category.title 
            FROM category
            WHERE   '.$rubric_title.'
            ORDER BY category.title
            LIMIT 30';
            $command=$connection->createCommand($sql);
            $find = $command->queryAll();
            if(!empty($find)){
                foreach($find as $m){
                    $addr = $m['title'];
                   // if(!in_array($addr, $arr))
                    $arr[] = $addr;
                }
            }
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }
  
   
    public function actionAutoCompleteLogUser($term)
    {
         if(isset($_GET['term'])) {
            $arr = array();

            $criteria = new CDbCriteria();
            $criteria->compare('LOWER(username)', MHelper::String()->toLower($_GET['term']), true);
          //  $criteria->compare('superuser', 1);
            $criteria->limit = 50;
            $model = new User;

            foreach($model->findAll($criteria) as $m)
            {
                $arr[] = $m->username;
            }
        }
        echo CJSON::encode($arr);
        Yii::app()->end();
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return City the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Orgs::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param City $model the model to be validated
     */
    protected function performAjaxValidation($model, $form)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='city-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

 
}