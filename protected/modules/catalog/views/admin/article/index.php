<?php

$this->renderPartial('application.views.common._flashMessage');
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js', CClientScript::POS_END);

?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'orgs-grid',
	//'itemsCssClass'=>'table  table-hover',
    //'htmlOptions'=>array('class'=>'panel-info'),
	'dataProvider'=>$model_search,
	// 'filter'=>$model,
	'ajaxUpdate' => true,
    'afterAjaxUpdate' => "function(id,data){ plusScripts();    }",
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
            'filter'=>false,
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
        array(
            'header' => 'Объект',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->renderPartial("_grid_company_object", array("data"=>$data), true)',
           'filter'=>false,
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
        array(
            'header' => 'Автор',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->renderPartial("_grid_company_author", array("data"=>$data), true)',
           'filter'=>false,
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
       array(
            'name' => 'views_count',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
       array(
            'name' => 'status_org',
            'filter'=>array(0=>"Закрыто", 1=>"Опубликовано"),
            'type' => 'raw',
            'value'=>function($data) {
                 if($data->status_org == Article::STATUS_ACTIVE){
                    return 'Опубликовано';
                 } else {
                    return "Закрыто";
                 }
                 
            }
        ),
		array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{view}{viewin}{delete}',
            'htmlOptions'=>array('class'=>'three-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/article/update", array("id"=>$data->id))',
                    
                ),
                'view'  => array(
                    'label' => 'Снять с публикации',
                    'icon'  => 'unlock',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/article/updatestatus", array("id"=>$data->id,"status"=>'.Article::STATUS_INACTIVE.'))',
                    'visible' => '($data->status_org=='.Article::STATUS_ACTIVE.')?true:false;',
                    'click'=>'function(){
                        if (confirm("Действительно снять с публикации?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("orgs-grid");
                                }
                                
                            }
                        });
                        }
                        return false;
                        
                    }',
                ),
                'viewin'  => array(
                    'label' => 'Опубликовать',
                    'icon'  => 'lock',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/article/updatestatus", array("id"=>$data->id,"status"=>'.Article::STATUS_ACTIVE.'))',
                    'visible' => '($data->status_org=='.Article::STATUS_INACTIVE.')?true:false;',
                    'click'=>'function(){
                        if (confirm("Действительно опубликовать?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("orgs-grid");
                                }
                                
                            }
                        });
                        }
                        return false;
                        
                    }',
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/article/delete", array("id"=>$data->id))',
                    'visible' => 'true',
                    'click'=>'function(){
                        if (confirm("Действительно удалить навсегда?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("orgs-grid");
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
// подправить в модели Orgs. Если выбрать Москву, потом любую организацию, то выведет что-то,
// хотя не должен. Суть в том, что в search Orgs заключено много compare и где-то OR, где-то AND
$script = "

function plusScripts() {
$('.add-tooltip').tooltip();
 
}

plusScripts();

";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>
