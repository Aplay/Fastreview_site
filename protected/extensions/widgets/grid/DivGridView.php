<?php
Yii::import('zii.widgets.grid.CGridView');
//Yii::import('ext.widgets.grid.DivDataColumn');

class DivGridView extends CGridView
{
	public $headlineCaption;

	/**
	 * Creates column objects and initializes them.
	 */
	/*protected function initColumns()
	{
		if($this->columns===array())
		{
			if($this->dataProvider instanceof CActiveDataProvider)
				$this->columns=$this->dataProvider->model->attributeNames();
			elseif($this->dataProvider instanceof IDataProvider)
			{
				// use the keys of the first row of data as the default columns
				$data=$this->dataProvider->getData();
				if(isset($data[0]) && is_array($data[0]))
					$this->columns=array_keys($data[0]);
			}
		}
		$id=$this->getId();
		foreach($this->columns as $i=>$column)
		{
			if(is_string($column)){
				$column=$this->createDataColumn($column);
				
			} else {
				if(!isset($column['class'])){
					$column['class']='DivDataColumn';
				}
				$column=Yii::createComponent($column, $this);
			}
			if(!$column->visible)
			{
				unset($this->columns[$i]);
				continue;
			}
			if($column->id===null)
				$column->id=$id.'_c'.$i;
			$this->columns[$i]=$column;
		}

		foreach($this->columns as $column)
			$column->init();
	}*/

	public function renderHeadline(){
		echo '<div class="table-header"><div class="table-caption">'.$this->headlineCaption.'</div></div>';
	}

	/**
	 * Renders the data items for the grid view.
	 */
	public function renderItems()
	{
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			echo "<div class=\"divtable {$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			ob_start();
			$this->renderTableBody();
			$body=ob_get_clean();
			$this->renderTableFooter();
			echo $body; // TFOOT must appear before TBODY according to the standard.
			echo "</div>";
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		if(!$this->hideHeader)
		{

			echo "<div class=\"divthead\">\n";

			if($this->filterPosition===self::FILTER_POS_HEADER)
				$this->renderFilter();

			echo "<div class=\"divtr\">\n";
			foreach($this->columns as $column){

				
			    echo CHtml::openTag('div',array('class'=>'divth '.$column->headerHtmlOptions['class']));
				$column->renderHeaderCell();
				echo "</div>\n";

			}
			echo "</div>\n";

			if($this->filterPosition===self::FILTER_POS_BODY)
				$this->renderFilter();

			echo "</div>\n";
		}
		elseif($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY))
		{
			echo "<div class=\"divthead\">\n";
			$this->renderFilter();
			echo "</div>\n";
		}
	}

	/**
	 * Renders the filter.
	 * @since 1.1.1
	 */
	public function renderFilter()
	{
		if($this->filter!==null)
		{
			echo "<div class=\"divtr divtrfilter {$this->filterCssClass}\">\n";
			foreach($this->columns as $column){
				echo CHtml::openTag('div',array('class'=>'divtd'));
				$column->renderFilterCell();
				echo "</div>\n";
			}
			echo "</div>\n";
		}
	}

	/**
	 * Renders the table footer.
	 */
	public function renderTableFooter()
	{
		$hasFilter=$this->filter!==null && $this->filterPosition===self::FILTER_POS_FOOTER;
		$hasFooter=$this->getHasFooter();
		if($hasFilter || $hasFooter)
		{
			echo "<div class=\"divtfoot\">\n";
			if($hasFooter)
			{
				echo "<div class=\"divtr\">\n";
				foreach($this->columns as $column){
					echo CHtml::openTag('div',array('class'=>'divtd'));
					$column->renderFooterCell();
					echo "</div>\n";
				}
				echo "</div>\n";
			}
			if($hasFilter)
				$this->renderFilter();
			echo "</div>\n";
		}
	}

	/**
	 * Renders the table body.
	 */
	public function renderTableBody()
	{
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo "<div class=\"divtbody\">\n";

		if($n>0)
		{
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else
		{
			echo '<div class="divtr"><div  colspan="'.count($this->columns).'" class="divtd empty">';
			$this->renderEmptyText();
			echo "</div></div>\n";
		}
		echo "</div>\n";
	}

	/**
	 * Renders a table body row.
	 * @param integer $row the row number (zero-based).
	 */
	public function renderTableRow($row)
	{
		$htmlOptions=array();
		$htmlOptions['class']='divtr';
		if($this->rowHtmlOptionsExpression!==null)
		{
			$data=$this->dataProvider->data[$row];
			$options=$this->evaluateExpression($this->rowHtmlOptionsExpression,array('row'=>$row,'data'=>$data));
			if(is_array($options))
				$htmlOptions = $options;
		}

		if($this->rowCssClassExpression!==null)
		{
			$data=$this->dataProvider->data[$row];
			$class=$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data));
		}
		elseif(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			$class=$this->rowCssClass[$row%$n];

		if(!empty($class))
		{
			if(isset($htmlOptions['class']))
				$htmlOptions['class'].=' '.$class;
			else
				$htmlOptions['class']=$class;
		}

		echo CHtml::openTag('div', $htmlOptions)."\n";
		foreach($this->columns as $column){
			echo CHtml::openTag('div',array('class'=>'divtd '.$column->headerHtmlOptions['class']));
			$column->renderDataCell($row);
			echo "</div>\n";
		}
		echo "</div>\n";
	}

	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$id=$this->getId();

		if($this->ajaxUpdate===false)
			$ajaxUpdate=false;
		else
			$ajaxUpdate=array_unique(preg_split('/\s*,\s*/',$this->ajaxUpdate.','.$id,-1,PREG_SPLIT_NO_EMPTY));
		$options=array(
			'ajaxUpdate'=>$ajaxUpdate,
			'ajaxVar'=>$this->ajaxVar,
			'pagerClass'=>$this->pagerCssClass,
			'loadingClass'=>$this->loadingCssClass,
			'filterClass'=>$this->filterCssClass,
			'tableClass'=>$this->itemsCssClass,
			'selectableRows'=>$this->selectableRows,
			'enableHistory'=>$this->enableHistory,
			'updateSelector'=>$this->updateSelector,
			'filterSelector'=>$this->filterSelector
		);
		if($this->ajaxUrl!==null)
			$options['url']=CHtml::normalizeUrl($this->ajaxUrl);
		if($this->ajaxType!==null)
			$options['ajaxType']=strtoupper($this->ajaxType);
		if($this->enablePagination)
			$options['pageVar']=$this->dataProvider->getPagination()->pageVar;
		foreach(array('beforeAjaxUpdate', 'afterAjaxUpdate', 'ajaxUpdateError', 'selectionChanged') as $event)
		{
			if($this->$event!==null)
			{
				if($this->$event instanceof CJavaScriptExpression)
					$options[$event]=$this->$event;
				else
					$options[$event]=new CJavaScriptExpression($this->$event);
			}
		}

		$options=CJavaScript::encode($options);
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('bbq');
		if($this->enableHistory)
			$cs->registerCoreScript('history');
		$assets = dirname(__FILE__).'/../assets/gridview';
        $baseUrl = Yii::app()->assetManager->publish($assets);
		$cs->registerScriptFile($baseUrl.'/jquery.yiigridview_m.js',CClientScript::POS_END);
		$cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').yiiGridView($options);");
	}
}
?>