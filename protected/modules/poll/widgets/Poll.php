<?php 
class Poll extends CWidget
{
	public $org_id;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		
		$choice = PollChoice::model()->open()->find(array('condition'=>'org_id='.$this->org_id, 'order'=>'created_date DESC'));
		
		$this->render('poll',array(
			'choice'=>$choice
		));
		
	}
    
}
