<?php
Yii::import('zii.widgets.CListView');
//Yii::import('ext.widgets.MyListView.MyListPager');
Yii::import('ext.widgets.MyListView.LinkListPager');
Yii::import('ext.widgets.MyListView.LinkListPagerAjax');

class MyListView extends CListView
{
        public $declentionwords = array();

         public function renderSummary()
	{
		if(($count=$this->dataProvider->getItemCount())<=0)
			return;

		echo '<div class="'.$this->summaryCssClass.'">';
		if($this->enablePagination)
		{
			if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('zii','Displaying {start}-{end} of {count} result(s).');
			$pagination=$this->dataProvider->getPagination();
			$total=$this->dataProvider->getTotalItemCount();
			$start=$pagination->currentPage*$pagination->pageSize+1;
			$end=$start+$count-1;
			if($end>$total)
			{
				$end=$total;
				$start=$end-$count+1;
			}
			echo strtr($summaryText,array(
				'{start}'=>$start,
				'{end}'=>$end,
				'{count}'=>$total,
				'{page}'=>$pagination->currentPage+1,
				'{pages}'=>$pagination->pageCount,
			));
                        echo MHelper::String()->plural($total, $this->declentionwords[0],$this->declentionwords[1], $this->declentionwords[2]);
		}
		else
		{
			if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('zii','Total {count} result(s).');
			echo strtr($summaryText,array(
				'{count}'=>$count,
				'{start}'=>1,
				'{end}'=>$count,
				'{page}'=>1,
				'{pages}'=>1,
			));
                        echo MHelper::String()->plural($count, $this->declentionwords[0],$this->declentionwords[1], $this->declentionwords[2]);
		}
                
		echo '</div>';
	}
        
        public function renderPager()
	{
		if(!$this->enablePagination)
			return;

		$pager=array();
		$class = 'LinkListPager';
		if(is_string($this->pager))
			$class=$this->pager;
		else if(is_array($this->pager))
		{
			$pager=$this->pager;
			if(isset($pager['class']))
			{
				$class=$pager['class'];
				unset($pager['class']);
			}
		}
		$pager['pages']=$this->dataProvider->getPagination();

		if($pager['pages']->getPageCount()>1)
		{
			echo '<div class="'.$this->pagerCssClass.'">';
			$this->widget($class,$pager);
			echo '</div>';
		}
		else
			$this->widget($class,$pager);
	}
}
?>
