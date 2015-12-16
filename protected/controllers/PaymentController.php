<?php


class PaymentController extends SController {

    public function actionIndex() {


        $this->active_link = 'payment';
        $this->pageTitle = Yii::app()->name.' - Платежи';
        $payments = Invoice::model()->findAll(array('condition'=>'user_id='.Yii::app()->user->id.' and status='.Invoice::STATUS_ACTIVE));
        $this->render('index',array('payments'=>$payments));
        

    }

    
    public function actionPay($id) {

    	$this->active_link = 'payment';
        $this->pageTitle = Yii::app()->name.' - Платежи';

       // $this->layout='//layouts/locatornew';
        if(Yii::app()->user->isGuest)
            throw new CHttpException(404, Yii::t('site','Page not found')); 
    	$model = Orgs::model()->findByPk($id, 'author='.Yii::app()->user->id.' and status_org='.Orgs::STATUS_NOT_ACTIVE);
        if(!$model)
            throw new CHttpException(404, Yii::t('site','Page not found')); 


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

          //  $model->verified = Orgs::STATUS_NOT_VERIFIED;

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
                    $model->save(false,array('paid_till'));
                }
            

            if($invoice){
                if($invoice->save()){
                $model->invoice_id = $invoice->id;
                $model->save(false,'invoice_id');

                $amount = $invoice->sum_discount;
                $orderId = $invoice->id;
                $description = 'оплата Локатора. Компания '.$model->title.' за '.$invoice->period.' '.Yii::t('site',Plan::getPlanSclon($invoice->period_type),$invoice->period);
                $clientEmail = $model->authorid->email;
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

                $message = 'Ваша организация успешно оплачена';
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


       // $model->yourname = Yii::app()->user->fullname;
        $model->youremail = Yii::app()->user->email;
        $model->yourphone = Yii::app()->user->phone;


        $this->render('pay',array(
            'modelAddCompany'=>$model,

            ));

    }

  
}
?>