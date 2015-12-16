<?php


class CompanyController extends SController {

    public function filters() {
        return array(
            'ajaxOnly + getstatuses',
        );
    }

    public function actionIndex() {

        $orgs = Orgs::model()->findAll(array('condition'=>'author='.Yii::app()->user->id));
        if($orgs){
            
        } else {
            $this->redirect('/');
        }

        $this->active_link = 'company';
        $this->pageTitle = Yii::app()->name.' - Компания';
        $criteria = new CDbCriteria;
        $criteria->condition ='author='.Yii::app()->user->id;
        $dataProvider = new CActiveDataProvider('Orgs', array(
            'criteria' => $criteria,
            'sort'       => array(
                'defaultOrder' => 't.created_date DESC',
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        if(!empty($dataProvider->data)){
            $this->render('index',array(
            'dataProvider'=>$dataProvider,
            ));
        } else {
            $this->actionNewCompany();
        }

    }

   public function actionNewCompany() {

        $this->active_link = 'company';
        $this->pageTitle = Yii::app()->name.' - Добавление компании';
        $model = new FormAddCompany;
        if(isset($_POST['ajax']) && $_POST['ajax']==='form-addcompany')
        {
            
            $step = Yii::app()->request->getPost('step',null);
            $model->attributes=$_POST['FormAddCompany'];
            $model->youremail = Yii::app()->user->email; 
            
            if($step && $step == 1){
                $model->scenario = 'step1';
            } else if($step && $step == 2){
                $model->scenario = 'step2';
            } else {
                $model->scenario = 'step3';
            }

            $errors = CActiveForm::validate($model);
                if ($errors !== '[]') {
                   echo $errors;
                   Yii::app()->end();
            } 
            
            $phones = $phones_comments = array();
            if(!empty($model->tempphones)){
                $bphones = explode(',',$model->tempphones);

                if(!empty($bphones)){
                    foreach ($bphones as $k=>$phone) {
                        if($k>=10)
                            break;
                        $phones[] = trim($phone);
                        $phones_comments[] = '';
                    }
                } else if(!empty($model->tempphones)){
                    $phones[] = trim($model->tempphones);
                    $phones_comments[] = '';
                }
            } 

            $sites = $sites_comments = array();
            if(!empty($model->site)){
                $bsites = explode(',',$model->site);
                if(!empty($bsites)){
                    foreach ($bsites as $k=>$site) {
                        if($k>=3)
                            break;
                        $sites[] = trim($site);
                        $sites_comments[] = '';
                    }
                } else if(!empty($model->site)){
                    $sites[] = trim($model->site);
                    $sites_comments[] = '';
                }
            } 
            
            $model->verified = Orgs::STATUS_NOT_VERIFIED;

            $plan_id = $model->plan;
            $plan = Plan::model()->findByPk($plan_id);
            if($plan){
                $model->plan = $plan_id;
                $model->status_org = Orgs::STATUS_ACTIVE;
            } else {
                $plan = Plan::model()->active()->find(array('condition'=>'amount=0'));
            }
          
                  
            
            if($model->save()){


              /*  $invoice = new Invoice;
                $invoice->user_id = Yii::app()->user->id;
                $invoice->amount = $plan->amount;
                $invoice->invoice_id = $plan_id;
                $invoice->org_id = $model->id;
                $invoice->status = Invoice::STATUS_ACTIVE;
                $invoice->period = $plan->period;
                $invoice->sum = $plan->amount * $plan->period;
                $invoice->discount = 0;
                $invoice->sum_discount = $invoice->sum;

                if($invoice->save()){
                    $model->invoice_id = $invoice->id;
                    $model->save(false,'invoice_id');
                } 
*/
            /*  if($plan){
                if($plan->period_type == Plan::PERIOD_MONTH){
                    $model->paid_till = date("Y-m-d H:i:s", strtotime("+".$plan->period." month", time()));
                } elseif($plan->period_type == Plan::PERIOD_WEEKS){
                    $model->paid_till = date("Y-m-d H:i:s", strtotime("+".$plan->period." week", time()));
                } elseif($plan->period_type == Plan::PERIOD_DAYS){
                    $model->paid_till = date("Y-m-d H:i:s", strtotime("+".$plan->period." day", time()));
                }
                //  $model->save(false,array('paid_till'));
            }*/

                $model->phones = $phones;
                $model->phone_comments = $phones_comments;
                $model->http = $sites;
                $model->http_comments = $sites_comments;
                
            $model->setPhones($model->phones, $model->phone_comments);
            $model->setHttp($model->http, $model->http_comments);
            $open_door = $close_door = $break_door = $endbreak_door = array();
            if(!empty($_POST['open_door'])){ $open_door = $_POST['open_door']; }
            if(!empty($_POST['close_door'])){ $close_door = $_POST['close_door']; }
            if(!empty($_POST['break_door'])){ $break_door = $_POST['break_door']; }
            if(!empty($_POST['endbreak_door'])){ $endbreak_door = $_POST['endbreak_door']; }

            OrgsWorktime::setWorktime($model->id, $open_door, $close_door, $break_door, $endbreak_door);

                $message = 'Ваша организация успешно добавлена';
                $url =  Yii::app()->createAbsoluteUrl('/dashboard');
                echo CJSON::encode(array(
                                'flag' => true,
                                'message' => $message,
                                'url'=>$url
                            ));
            } else {
                $message = $model->errors;
                echo CJSON::encode(array(
                                'flag' => false,
                                'message' => $message
                            ));
            }

            
            
            Yii::app()->end();
        }   

        $model->yourname = Yii::app()->user->fullname;
        $model->youremail = Yii::app()->user->email;
        $model->yourphone = Yii::app()->user->phone;


        $this->render('new_company',array(
            'modelAddCompany'=>$model,

            ));

    }

    public function actionUpdate($id){

        $this->active_link = 'company';

        $model = Orgs::model()->findByPk($id, 'author='.Yii::app()->user->id);
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found'));    
        
        $this->pageTitle = Yii::app()->name.' - Редактирование компании';
      
        

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-addcompany')
        {
            
            $step = Yii::app()->request->getPost('step',null);
            
            $model->youremail = Yii::app()->user->email; 
            if($step && $step == 1){
                $model->scenario = 'step1';
            } else if($step && $step == 2){
                $model->scenario = 'step2';
            } else {
                $model->scenario = 'step3';
            }
            $model->attributes=$_POST['Orgs'];
            $errors = CActiveForm::validate($model);
                if ($errors !== '[]') {
                   echo $errors;
                   Yii::app()->end();
            } 

            $phones = $phones_comments = array();
            if(!empty($model->tempphones)){

                $bphones = explode(',',$model->tempphones);

                if(!empty($bphones)){
                    foreach ($bphones as $k=>$phone) {
                        if($k>=10)
                            break;
                        $phones[] = trim($phone);
                        $phones_comments[] = '';
                    }
                } else if(!empty($model->tempphones)){
                    $phones[] = trim($model->tempphones);
                    $phones_comments[] = '';
                }
            } 

            $sites = $sites_comments =array();
            if(!empty($model->site)){
                $bsites = explode(',',$model->site);
                if(!empty($bsites)){
                    foreach ($bsites as $k=>$site) {
                        if($k>=3)
                            break;
                        $sites[] = trim($site);
                        $sites_comments[] = '';
                    }
                } else if(!empty($model->site)){
                    $sites[] = trim($model->site);
                    $sites_comments[] = '';
                }
            } 
            if($model->verified ==  Orgs::STATUS_VERIFIED)
                $model->verified = Orgs::STATUS_UPDATE_VERIFIED;
           
            
            if($model->save()){


            $model->phones = $phones;
            $model->phone_comments = $phones_comments;
            $model->http = $sites;
            $model->http_comments = $sites_comments;
                
            $model->setPhones($model->phones, $model->phone_comments);
            $model->setHttp($model->http, $model->http_comments);
            $open_door = $close_door = $break_door = $endbreak_door = array();
            if(!empty($_POST['open_door'])){ $open_door = $_POST['open_door']; }
            if(!empty($_POST['close_door'])){ $close_door = $_POST['close_door']; }
            if(!empty($_POST['break_door'])){ $break_door = $_POST['break_door']; }
            if(!empty($_POST['endbreak_door'])){ $endbreak_door = $_POST['endbreak_door']; }

            OrgsWorktime::setWorktime($model->id, $open_door, $close_door, $break_door, $endbreak_door);

                $message = 'Ваша организация успешно добавлена';
                $url =  Yii::app()->createAbsoluteUrl('/dashboard');
                echo CJSON::encode(array(
                                'flag' => true,
                                'message' => $message,
                                'url'=>$url
                            ));
            } else {
                $message = $model->errors;
                echo CJSON::encode(array(
                                'flag' => false,
                                'message' => $message
                            ));
            }

            
            
            Yii::app()->end();
        }   

        if(!empty($model->orgsPhones)){
            foreach ($model->orgsPhones as $phone) {
                $model->tempphones .= $phone->phone.', ';
            }
        }
        if($model->tempphones)
            $model->tempphones = rtrim($model->tempphones,', ');
       
        if(!empty($model->orgsHttp)){
            foreach ($model->orgsHttp as $site) {
                $model->site .= $site->site.', ';
            }
        }
        if($model->site)
            $model->site = rtrim($model->site,', ');
     /*   
        $model = new FormAddCompany;
        $model->id = $org->id;
        $model->title = $org->title;
        $model->address = $org->address;
        $model->rubrictext = $org->rubrictext;
        $model->description = $org->description;
        $model->vkontakte = $org->vkontakte;
        $model->twitter = $org->twitter;
        $model->facebook = $org->facebook;
        $model->instagram = $org->instagram;
        $model->youtube = $org->youtube;
        */

        $this->render('update',array(
            'modelAddCompany'=>$model,

            ));

    }
    
    public function actionGetStatuses($id){
        $criteria = new CDbCriteria;
        $criteria->condition = 'org_id=:org_id';
        $criteria->params = array(':org_id'=>$id);
        $criteria->order = 'created_date DESC';
        $modelStatus = new OrgsStatus('search');

        if (!empty($_GET['OrgsStatus']))
            $modelStatus->attributes = $_GET['OrgsStatus'];

        $dataProviderStatus = $modelStatus->search(array(),$criteria);
        $dataProviderStatus->pagination->pageSize = 20;
        $this->renderPartial('status_list',array('dataProviderStatus'=>$dataProviderStatus));
    }

  
}
?>