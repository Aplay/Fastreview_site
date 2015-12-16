<?php

/**
 * Represent model as array needed to create CMenu.
 * Usage:
 * 	'SAsCMenuArrayBehavior'=>array(
 * 		'class'=>'ext.behaviors.SAsCMenuArrayBehavior',
 * 		'labelAttr'=>'name',
 * 		'urlExpression'=>'array("/store/category", "id"=>$model->id)',
 * TODO: Cache queries
 * 	)
 */
class SAsCMenuArrayBehavior extends CActiveRecordBehavior
{

	/**
	 * @var string Owner attribute to be placed in `label` key
	 */
	public $labelAttr;
    public $idAttr;
    public $sellerAttr;
	/**
	 * @var string Expression will be evaluated to create url.
	 * Example: 'urlExpression'=>'array("/store/category", "id"=>$model->id)',
	 */
	public $urlExpression;

	public function asCMenuArray()
	{
		return $this->walkArray($this->owner);
	}
	public function asCMenuArrayFirst()
	{
		return $this->walkArrayFirst($this->owner);
	}
	public function asCMenuSellerArray($sellerAttr)
	{
		$this->sellerAttr = $sellerAttr;
		$this->urlExpression = 'array("/seller/$this->sellerAttr/catalog/$model->url")';
		return $this->walkArray($this->owner);
	}
    public function asTreeArray()
	{
		return $this->treeArray($this->owner);
	}
	/**
	 * Recursively build menu array
	 * @param $model CActiveRecord model with NestedSet behavior
	 * @return array
	 */
	protected function walkArray($model)
	{
		$data = array(
			'label'=>$model->{$this->labelAttr},
			'url'=>$this->evaluateUrlExpression($this->urlExpression, array('model'=>$model)),
             'id'=>$model->{$this->idAttr}
		);

		// TODO: Cache result
		$children = $model->children()->findAll();
		if(!empty($children))
		{
			foreach($children as $c)
				$data['items'][] = $this->walkArray($c);
		}

		return $data;
	}
	protected function walkArrayFirst($model)
	{
		$data = array(
			'label'=>$model->{$this->labelAttr},
			'url'=>$this->evaluateUrlExpression($this->urlExpression, array('model'=>$model)),
             'id'=>$model->{$this->idAttr}
		);

		// TODO: Cache result
		$children = $model->children()->findAll();
		if(!empty($children))
		{
			foreach($children as $c)
				$data['items'][] = array(
										'label'=>$c->{$this->labelAttr},
										'url'=>$this->evaluateUrlExpression($this->urlExpression, array('model'=>$c)),
							             'id'=>$c->{$this->idAttr}
										);
		}

		return $data;
	}
	protected function treeArray($model)
	{
		$children = $model->descendants()->findAll();
		if(!empty($children))
		{
		$data = array();
		$level=2;
		if (count($children) > 0) {
			$set = array(); $boos = '';
		foreach($children as $n=>$category)
		{

			$data[$category->id] = ''; 
			
		   
	   		if($category->level!=$level){
	   			if(!array_key_exists($category->level, $set)){
	   			  $boos .= '-';
        	      $set[$category->level] = $boos;
        	    } else {
        	      $data[$category->id] .= $set[$category->level];
        	    }
	   		} 
		   

			if($category->level==$level) {
				if($category->level!=2)
				$data[$category->id] .= $set[$category->level];
			}
			else if($category->level>$level){
				if($category->level!=2)
				 $data[$category->id] .= $set[$category->level];
			}
			else
			{

				//echo CHtml::closeTag('li')."\n";

				for($i=$level-$category->level;$i;$i--)
				{

					//echo CHtml::closeTag('ul')."\n";
					//echo CHtml::closeTag('li')."\n";
				}
			}
            
			$data[$category->id] .= CHtml::encode($category->name);
			$level=$category->level;

		}
	}

		for($i=$level;$i;$i--)
		{
			//echo CHtml::closeTag('li')."\n";
			//echo CHtml::closeTag('ul')."\n";
		}
	   }

		return $data;
	}
	/**
	 * @param $expression
	 * @param array $data
	 * @return mixed
	 */
	public function evaluateUrlExpression($expression,$data=array())
	{
		extract($data);
		return eval('return '.$expression.';');
	}
}
