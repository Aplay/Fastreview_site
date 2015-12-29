<?php

$this->renderPartial('application.views.common._flashMessage');
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js', CClientScript::POS_END);

 ?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'objects-grid',
	//'itemsCssClass'=>'table  table-hover',
    //'htmlOptions'=>array('class'=>'panel-info'),
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'ajaxUpdate' => true,
  //  'afterAjaxUpdate' => "function(id,data){ plusScripts();  updateScripts();  }",
   // 'loadingCssClass'=>'empty-loading',
    'template'=>"{summary}\n{items}\n{pager}",
    'summaryText'=>Yii::t('site','Displaying {start}-{end} of {count}'),
    'pager'=>array(
        'header' => '',
        'firstPageLabel'=>'«',
        'lastPageLabel'=>'»',
        'nextPageLabel' => '›',
        'prevPageLabel' => '‹',
        'selectedPageCssClass' => 'active',
        'hiddenPageCssClass' => 'disabled',
        'htmlOptions' => array('class' => 'pagination')
      ),
	'columns'=>array(
		array(
            'header' => 'Название',
            'name' => 'title',

            'type' => 'raw',
  
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
        array(
            'header' => 'Адрес',
            'name' => 'address',
            'type' => 'raw',
           'filter'=>false,
        ),
        array(
            'header' => 'Рубрика',
            'type' => 'html',
            'value' => '$data->category->title',
           'filter'=>false,
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
      /*  array(
            'name' => 'youremail',
            'type' => 'raw',
            'filter'=>false,
        ),*/
        array(
            'name' => 'status',
            'filter'=>false,
            'type' => 'raw',
            'value'=>function($data) {
                 if($data->status){
                    return 'Опубликовано';
                 } else {
                    return "Не опубликовано";
                 }
                 
            }
        ),
		array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{delete}',
            'htmlOptions'=>array('class'=>'two-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/objects/update", array("id"=>$data->id))',
                    
                ),
                
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/objects/delete", array("id"=>$data->id))',
                    'visible' => 'true',
                    'click'=>'function(){
                        if (confirm("Действительно удалить навсегда?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("objects-grid");
                                }
                                
                            }
                        });
                        }
                        return false;
                        
                    }',
                ),
            )
        ),
	),
)); ?>
<?php

$script = "

";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>
