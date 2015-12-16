<?php
Yii::import('zii.widgets.grid.CGridView');

class MyGridView extends CGridView
{
	public $headlineCaption;
	public $headlineControls;
	public function renderHeadline(){
		if(!empty($this->headlineControls)){
			echo '<div class="panel-heading"><span class="panel-title">'.$this->headlineCaption.'</span>';
			echo $this->headlineControls;
			echo '<div class="clearfix"></div></div>';
		} else {

			echo '<div class="table-header"><div class="table-caption">'.$this->headlineCaption.'</div></div>';
			
		}
	}
}
?>