<?php
class DefaultController extends SAdminController {

	public $layout = '//layouts/admin';
    public $pageTitle = 'Дашбоард';
    public $year;
    public $month = '01';
    public $mon = 0;
    public $period = 'no';
    public $day = '01';
    public $loguser;
    public $logobject;

	/* public function allowedActions()
	{
		return 'index'; // allow all registered users
	}*/
    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
            'rights'
        ));
    }

    public function actionIndex() {

    	$this->active_link = 'dashboard';
    	//count users
    	$users = User::model()->count('status != '.User::STATUS_DELETED);
        $orgs = Orgs::model()->active()->count(array('select'=>'id'));

        $data = array();
        $this->dataset();
        $d_begin = $this->year.'-'.$this->month.'-'.$this->day;
        
        if($this->mon>0 && $this->mon<13){ 
        for($i=1;$i<=cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year); ++$i)
            {
                $this->day = sprintf("%02s", $i); // add zero 
                $d_begin = $this->year.'-'.$this->month.'-'.$this->day;
                $d_end = date('Y-m-d', strtotime($d_begin . ' + 1 day'));

                $data[]  = array('period'=>$this->year.'-'.$this->month.'-'.$this->day, 'value'=>$this->loadUsersU($d_begin, $d_end));
                
            }

        } else {

        for($i=1;$i<=12; ++$i)
            {
                $this->month = sprintf("%02s", $i); // add zero 
                $d_begin = $this->year.'-'.$this->month.'-01';
                $d_end = date('Y-m-d', strtotime($d_begin . ' + 1 month'));

                $data[]  = array('period'=>$this->year.'-'.$this->month, 'value'=>$this->loadUsersU($d_begin, $d_end));

                
            }  


            
        } 

        $this->render('dashboard',array('users'=>$users,'orgs'=>$orgs,
            
            'year'       => $this->year,
            'month'      =>  $this->mon,
            'loguser' => $this->loguser,
            'logobject'=>$this->logobject,
            'data'=>$data,
            
        ));
    }

    public function getAvailableYears()
    {

        $result = array();
        /*
        $command = Yii::app()->db->createCommand("SELECT datetime FROM actionlog ORDER BY datetime")->queryAll();
        if($command){
            foreach($command as $row)
            {
                $year = date('Y',strtotime($row['datetime']));
                $result[$year] = $year;
            }
        }*/
        $result[2014] = 2014;
        $result[2015] = 2015;
        return $result;
    }

    public function getAvailableLogUsers()
    {
        $result = array();
        $criteria = new CDbCriteria;
       // $criteria->with = array('userid');
        $criteria->order = 'username';

      //  $logusers = ActionLog::model()->findAll($criteria);
        $logusers = User::model()->findAll($criteria);

        if($logusers){
            $result[] = 'Все';
            foreach($logusers as $row)
            {
               // if($row->userid){
                    $user = $row->username;
                    $result[$user] = $user;
               // }
            }
        }

        return $result;
    }

    public function loadUsersU($d_begin, $d_end)
    {

      //  return array('Добавление'=>rand(1,10),'Обновление'=>rand(1,10),'Удаление'=>rand(1,10));
        $ar = array();

        for($i=1;$i<=3;$i++){

            $criteria = new CDbCriteria;

            if(!empty($this->loguser)){
                $criteria->with = array(
                'userid'=>array('condition'=>'username=:username','params'=>array(':username'=>$this->loguser)),
                'together'=>true
                );
               // $criteria->together = true;
            } else {
                $criteria->with = array('userid');
            }
            if($d_begin){
                $criteria->addCondition("t.datetime >='" . date('Y-m-d 00:00:00', strtotime($d_begin)) . "'");
            }
            if($d_end){
                $criteria->addCondition("t.datetime <='" . date('Y-m-d 00:00:00', strtotime($d_end)) . "'");
            }
            if(!empty($this->logobject)){
                $criteria->addCondition("t.event=".$i." and t.model_name='".$this->logobject."'");
            } else {
                $criteria->addCondition("t.event=".$i);
            }
            
            $ev_names = ActionLog::getEventNames();
            $ar[$ev_names[$i]] = ActionLog::model()->count($criteria);
        }
      //  VarDumper::dump($ar); die(); // Ctrl + X    Delete line
        return $ar;
    }

    public function dataset(){
        $request    = Yii::app()->request;
        if(!$request->getParam('year')){
            $this->year = date('Y',time());
        } else {
            $this->year    = (int)$request->getParam('year', date('Y'));
        }
         if(!$request->getParam('loguser')){
            $this->loguser = 0;
        } else {
            $this->loguser = $request->getParam('loguser');
        }
        $this->logobject = $request->getParam('logobject','Orgs');
        

        if($request->getParam('year') && !$request->getParam('month')){

        } elseif ($request->getParam('year') && $request->getParam('month')) {
            $this->mon = (int)$request->getParam('month');
            if($this->mon == '0'){

            } else {
                
                $this->month = sprintf("%02s", $this->mon); // add zero 
            }

            
           // $month = (int)$request->getParam('month', date('m'));
        } else {
            $this->month = date('m',time());
            $this->mon = ltrim($this->month, '0'); // remove zero
        }
        if ($request->getParam('year') && $request->getParam('month') && $request->getParam('day')) {
            $this->day = (int)$request->getParam('day', date('d'));
            $this->period = 'day';
        } elseif($request->getParam('year') && $request->getParam('month') && !$request->getParam('day')){
            $this->period = 'month';
        } elseif($request->getParam('year') && !$request->getParam('month') && !$request->getParam('day')){
            $this->period = 'year';
        } 
    }
 
}