<?php  
if($provider){
  if($type){
    $pager = false;
  } else {
    $pager = array(
              'maxButtonCount'=>5,
              'header' => '',
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            );
  }
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'ajaxUpdate'=>true,
           // 'ajaxUrl'=>$this->createUrl('site/review_objects'),
            'viewData'=>array('term'=>$term),
            'id'=>'objects_list',
            'template'=>"{items}\n{pager}",
          //  'summaryText'=>'<div class="summary_show">Показано</div><div><span class="summary_end">{end}</span> из {count}</div>',
            'itemView'=>$objects_view,
            'emptyText'=>'',
            'pager'=>$pager,
           ));
       }


?>