<?php

class PollController extends Controller
{
	public $city;
	public $layout = '//layouts/emptyerror';

  public function filters()
    {
     return array(
        'ajaxOnly + tovote, addpoll',
        );
    }
  	public function actionAddPoll (){

  		if (isset($_POST['PollChoice'])) {
  		  $model = new PollChoice;
		  $this->performAjaxValidation($model, 'poll-form');
	      $model->attributes = $_POST['PollChoice'];
	      if($model->validate()){
		       if ($model->save()) {
		       		$this->renderPartial('_poll_super',array('model'=>$model));
		       }
	   	   }
	   }
	   Yii::app()->end();
  	}
    public function actionTovote (){
        $id = (int)$_POST['id'];
        $voter = (int)$_POST['vote'];
        $ret = array();
        // check
        $ip = MHelper::Ip()->getIp();
        $vote = PollVote::model()->find(array('condition'=>'choice_id=:id and ip_address=:ip','params'=>array(':id'=>$id,':ip'=>$ip)));
        if(!$vote){
            $vote = new PollVote;
            $vote->vote = $voter;
            $vote->choice_id = $id;
            $vote->ip_address = $ip;
            if(!Yii::app()->user->isGuest){
              $vote->user_id = Yii::app()->user->id; 
            }
            if(!$vote->save()){
            	VarDumper::dump($vote->errors); die(); // Ctrl + X	Delete line
            }
            $weight = '';
            $sql = "SELECT COUNT(*) FROM poll_vote WHERE choice_id={$id} and vote={$voter}";
            $numClients = Yii::app()->db->createCommand($sql)->queryScalar();
            $review = PollChoice::model()->findByPk($id);
            if($voter == 1){
            	$review->yes = $numClients;
            	$diff = $review->yes - $review->no;
	            $sum = $review->yes + $review->no; 
				if($diff>0){
					$weight = round(($diff)*100/$sum);
				}
            	$review->weight =  $weight;
            	$review->votes =  $diff;
            	$review->save(false,array('yes','weight','votes'));

            } else {
            	$review->no = $numClients;
            	$diff = $review->yes - $review->no;
	            $sum = $review->yes + $review->no; 
				if($diff>0){
					$weight = round(($diff)*100/$sum);
				}
				$review->weight =  $weight;
				$review->votes =  $diff;
            	$review->save(false,array('no','weight','votes'));
            }

            
            $ret['flag'] = true;
            $ret['count'] = $numClients;
            $ret['yes'] = $review->yes;
            $ret['no'] = $review->no;
            $ret['type'] = $review->type;
            $ret['id'] = $review->id;

            $ret['weight'] = $weight;
            $ret['weight_text'] = (!empty($weight))?'считают '.$weight.'%':'';
            echo CJSON::encode($ret);
        }
    }
  
}
