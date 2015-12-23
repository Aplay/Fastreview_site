<?php 
class Poll extends CWidget
{
	public $org_id;
	public $type = 0;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		if(!$this->type){
			$choice = PollChoice::model()->open()->find(array('condition'=>'org_id='.$this->org_id, 'order'=>'created_date DESC'));
		
		} else {
			$choice = PollChoice::model()->open()->find(array('condition'=>'org_id='.$this->org_id.' and type='.$this->type, 'order'=>'created_date DESC'));
		
		}
		
		$this->render('poll',array(
			'choice'=>$choice
		));
		
	}
    
}
