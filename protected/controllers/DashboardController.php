<?php


class DashboardController extends SController {

    public function actionIndex() {


        $this->active_link = 'dashboard';
        $this->pageTitle = Yii::app()->name.' - Дашбоард';
        $orgs = Orgs::model()->findAll(array('condition'=>'author='.Yii::app()->user->id));
        
        $showtariffs = false;

        if($orgs){

            foreach ($orgs as $org) {
               if($org->status_org == Orgs::STATUS_NOT_ACTIVE && $org->paid_till <= date("Y-m-d H:i:s")){
                  $showtariffs = $org;
               }
            }
            $criteria = new CDbCriteria;
            $criteria->condition = 'user_id='.Yii::app()->user->id;
            $criteria->order = 'created_date DESC';
            $modelStatus = new OrgsStatus('search');

            if (!empty($_GET['OrgsStatus']))
                $modelStatus->attributes = $_GET['OrgsStatus'];

            $dataProviderStatus = $modelStatus->search(array(),$criteria);
            $dataProviderStatus->pagination->pageSize = 20;
            
            $this->render('index',array(
            'dataProviderStatus'=>$dataProviderStatus,
            'showtariffs'=>$showtariffs
            ));
        } else {
            $this->actionNewCompany();
        }

    }

    


    public function actionNewCompany() {
    	// $this->pageTitle .= ' - Аккаунт';
        $this->layout='//layouts/locatornew';

    	
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
            
            /*
            if(!empty($model->address)){
                $words = explode(',',$model->address,2);

                if(!empty($words)){
                    $city = trim($words[0]);
                    $trueCity = City::model()->find('title=:title or alternative_title=:alternative_title',array(':title'=>$city,':alternative_title'=>$city));
                    if(!$trueCity)
                        $trueCity = $this->addNewCity($city);
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
                
            } */
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
            
            
        /*  $cats = array();
            if(!empty($model->cats)){
                $bcats = explode(',',$model->cats);
                if(!empty($bcats)){
                    foreach ($bcats as $k=>$cat) {
                        if($k>=10)
                            break;
                        $trueCat = Category::model()->find(array('condition'=>'LOWER(title)=:title','params'=>array(':title'=>MHelper::String()->toLower($cat))));
                        if($trueCat){
                            if(!in_array($trueCat->id, $cats))
                                $cats[] = $trueCat->id;
                        }
                    }
                }
            }
            $model->categories_ar = $cats;*/
            $model->verified = Orgs::STATUS_NOT_VERIFIED;

            
            if($step && $step < 3){
                echo CJSON::encode(array('flag'=>true,'nextstep'=>$step+1));
                Yii::app()->end();
            }

            $plan_id = $model->plan;
            $plan = Plan::model()->findByPk($plan_id);
            if($plan){
                $model->plan = $plan_id;
                $model->status_org = Orgs::STATUS_NOT_ACTIVE;
            } else {
                echo CJSON::encode(array('flag'=>true,'nextstep'=>3));
                Yii::app()->end();
            }
            $promo = null;
            if($model->promo){
                $promo = Promo::model()->active()->find(array('condition'=>'LOWER(promo)=:promo','params'=>array(':promo'=>$model->promo))); 
            }
            
            if($model->save()){

                $user = Yii::app()->user->model;
                if($user->fullname != $model->yourname || $user->phone != $model->yourphone){
                    $user->fullname = $model->yourname;
                    $user->phone = $model->yourphone;
                    $user->save(false,array('fullname','phone'));
                }

                $invoice = null;
                 if($plan->amount){ // выставляем счет, если это не демка
                $invoice = new Invoice;
                $invoice->user_id = Yii::app()->user->id;
                $invoice->amount = $plan->amount;
                $invoice->invoice_id = $plan_id;
                $invoice->org_id = $model->id;
                $invoice->status = Invoice::STATUS_INACTIVE;
                $invoice->period = $plan->period;
                $invoice->period_type  = $plan->period_type;
                $invoice->sum = $plan->amount * $plan->period;
                $invoice->discount = 0;
                $invoice->sum_discount = $invoice->sum;
                if($promo){
                    $invoice->promo_id = $promo->id;
                    $invoice->discount = 10;
                    $invoice->sum_discount = $invoice->sum - ($invoice->sum * 10 / 100);
                    $invoice->sum_discount = round($invoice->sum_discount, 2);
                }
                } else {
                    if($plan->period_type == Plan::PERIOD_MONTH){
                        $model->paid_till = date("Y-m-d H:i:s", strtotime("+".$plan->period." month", time()));
                    } elseif($plan->period_type == Plan::PERIOD_WEEKS){
                        $model->paid_till = date("Y-m-d H:i:s", strtotime("+".$plan->period." week", time()));
                    } elseif($plan->period_type == Plan::PERIOD_DAYS){
                        $model->paid_till = date("Y-m-d H:i:s", strtotime("+".$plan->period." day", time()));
                    }
                  //  $model->save(false,array('paid_till'));
                }
                

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


            if($invoice){
                if($invoice->save()){
                $model->invoice_id = $invoice->id;
                $model->save(false,'invoice_id');

                $amount = $invoice->sum_discount;
                $orderId = $invoice->id;
                $description = 'оплата Локатора. Компания '.$model->title.' за '.$invoice->period.' '.Yii::t('site',Plan::getPlanSclon($invoice->period_type),$invoice->period);
                $clientEmail = $user->email;
                $url = Yii::app()->robokassa->pay($amount, $orderId, $description, $clientEmail); 
                
               // Yii::app()->end();

            } else {

                $message = $invoice->errors;
              //  $url =  Yii::app()->createAbsoluteUrl('/dashboard');
                echo CJSON::encode(array(
                                'flag' => false,
                                'message' => $message,
                            ));

                // VarDumper::dump($invoice->errors); 
                Yii::app()->end();

            }
        } else {
            $url = Yii::app()->createAbsoluteUrl('/dashboard');
        }

                $message = 'Ваша организация успешно добавлена';
              //  $url =  Yii::app()->createAbsoluteUrl('/dashboard');
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

      /*  if(!$this->city){
            $domain = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
            if($domain){
                $this->city = City::model()->withUrl($domain)->find();
            }
        }
        if($this->city)
            $model->address = $this->city->title; */
       // $model->yourname = Yii::app()->user->fullname;
        $model->youremail = Yii::app()->user->email;
        $model->yourphone = Yii::app()->user->phone;


        $this->render('new_company',array(
            'modelAddCompany'=>$model,

            ));

    }

  
}
?>