<?php 
class Poll extends CWidget
{
	public $org_id;
	public $type = PollChoice::TYPE_PLUS;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		
		$choice = PollChoice::model()->open()->find(array('condition'=>'org_id='.$this->org_id.' and type='.$this->type,'order'=>'votes DESC, label'));
		
		$this->render('poll',array(
			'choice'=>$choice
		));
		
	}
    
}
