<?php 
$themeUrl = '/themes/bootstrap_311/';

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_article_listview',
    'id'=>'article_listview',       // must have id corresponding to js above
   // 'itemsCssClass'=>'row',
    'ajaxUpdate' => true,
    'template'=>"{items}\n{pager}",
    'pager'=>array(

              'header' => '',
              'maxButtonCount'=>5,
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            ),

    
));
?>