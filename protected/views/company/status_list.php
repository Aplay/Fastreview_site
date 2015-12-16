<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProviderStatus,
    'itemView'=>'application.views.company._status_list',
    'id'=>'status_listview',       // must have id corresponding to js above
    'template'=>"{items}\n{pager}",
    'emptyText'=>'',
    'pager'=>array(

              'header' => '',
              'maxButtonCount'=>5,
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination','style'=>'margin-top:20px;')
            ),
));
?>