<?php
Yii::import('zii.widgets.CListView');
//Yii::import('ext.widgets.MyListView.MyListPager');
Yii::import('ext.widgets.MyListView.LinkListPager');

class MyListViewColumns extends CListView
{
        public $declentionwords = array();
        public $itemsTagName = 'table';
        
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
		$class='LinkListPager';
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
        public function renderItems()
	{
		
               echo CHtml::openTag($this->itemsTagName,array('class'=>$this->itemsCssClass))."\n";
		$data_all=$this->dataProvider->getData();
                
                $data = array(); $arr_item = array();
                
                foreach($data_all as $data_item)  {
                    if(!in_array($data_item->title, $arr_item)){
                        $arr_item[] = $data_item->title;
                        $data[] = $data_item;
                    } 

                }
                
		if(($n=count($data))>0)
		{
			$owner=$this->getOwner();
			$viewFile=$owner->getViewFile($this->itemView);
			$j=0; $jj=0; $ttl = array();
                        foreach($data as $i=>$item)
			{
                          
                                $ttl[] = $item;
                                $jj++;  
                            
                        }
                       // if($jj > 3){
                        if($jj<=10){
                            $this->drawTableV($ttl,1,$jj);
                        }  else {
                            $this->drawTableV($ttl,3,$jj);
                        }
                            
                       /* } else {
                            foreach($data as $i=>$item)
                            {
                                    $data=$this->viewData;
                                    $data['index']=$i;
                                    $data['data']=$item;
                                    $data['widget']=$this;
                                    $owner->renderFile($viewFile,$data);
                                    if($j++ < $n-1)
                                            echo $this->separator;
                            }
                        } */
		}
		else
			$this->renderEmptyText();
		echo CHtml::closeTag($this->itemsTagName);
	}
        
            public function drawTableV($datain, $columns=3, $cnt=0, $tabs=0)
            {
                $tbl = null;

                if($tabs === false)
                {
                    $tr = $td = null;
                }
                else
                {
                    $tr = "\n".str_repeat("\t", $tabs);
                    $td = $tr."\t";
                }
                if($cnt){
                    $data = array_chunk($datain, ceil($cnt / $columns));
                 
                } else {
                    $data = array_chunk($datain, ceil(count($datain) / $columns));
                }
                
                for($i = 0, $c = count($data[0]); $i < $c; $i++)
                {
                    $tbl .= $tr.'<tr>';

                    for($si = 0; $si < $columns; $si++)
                    {
                        
                       if($cnt){ 
                           $owner=$this->getOwner();
			   $viewFile=$owner->getViewFile($this->itemView);
                           
                        $tbl .= $td.'<td style="width:33%">';
                        if(isset($data[$si][$i])){
                           
                               $tbl .=  Chtml::link($data[$si][$i]->title, '/useful/'.$data[$si][$i]->category->full_url.'/view/'.$data[$si][$i]->url);
                           
                           
                           
                        } else {
                           $tbl .= '&nbsp;';
                        }
                        $tbl .= '</td>';
                        
                       } else {
                           
                        $tbl .= $td.'<td>'.(isset($data[$si][$i]) ? $data[$si][$i] : '&nbsp;').'</td>';   
                       }
                    }

                    $tbl .= $tr.'</tr>';
                }

                if($tabs !== false)
                    $tbl .= "\n";
                echo $tbl;
                return true;
            }
}
?>
