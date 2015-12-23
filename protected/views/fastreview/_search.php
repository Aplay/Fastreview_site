<?php  
           if($provider){
           $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'ajaxUpdate'=>false,
            'template'=>"{items}\n{pager}",
          //  'summaryText'=>'<div class="summary_show">Показано</div><div><span class="summary_end">{end}</span> из {count}</div>',
            'itemView'=>'_objects_view',
            'emptyText'=>'',
            'pager'=>array(
              'maxButtonCount'=>5,
              'header' => '',
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            ),
           ));
       }


?>