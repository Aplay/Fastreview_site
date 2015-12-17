<?php

/**
 * Display logs
 **/

$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js', CClientScript::POS_END);
// $this->widget('ext.sgridview.SGridView', array(
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'    => $dataProviderHistory,
    'id'              => 'history-grid',
    'afterAjaxUpdate' => "function(){registerFilterDatePickers();}",
    'filter'          => $model,
    // 'enableHistory'   => true,
    'loadingCssClass'=>'empty-loading',
    'template'=>"{items}\n{pager}",
    'pager'=>array(
        'header' => '',
        'firstPageLabel'=>'first',
        'lastPageLabel'=>'last',
        'nextPageLabel' => '»',
        'prevPageLabel' => '«',
        'selectedPageCssClass' => 'active',
        'hiddenPageCssClass' => 'disabled',
        'htmlOptions' => array('class' => 'pagination')
      ),
    'columns'=>array(
        array(
            'name'=>'datetime',
        ),
        array(
            'name'=>'event',
            'type'=>'raw',
            'value'=>'$data->actionTitle',
            'filter'=>ActionLog::getEventNames()
        ),
        array(
            'name'=>'search_user',
            'value'=>function($data) {
                 return ($data->userid)?$data->userid->username:"";
            },
        ),
      /*  array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{delete}',
            'htmlOptions'=>array('class'=>'two-button-column'),
            'buttons' => array(
               
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("logger/admin/default/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Are you sure you want to delete this?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("history-grid");
                                }
                                
                            }
                        });
                        }
                        return false;
                        
                    }',
                ),
            )
        ), */
    ),
));
$script = "
function plusScripts() {
$('.add-tooltip').tooltip();
};

plusScripts();
";
// Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

 Yii::app()->clientScript->registerScript("pageDatepickers", "

    function registerFilterDatePickers(id, data){
        jQuery('input[name=\"ActionLog[datetime]\"]').datepicker({
            format: 'yyyy-mm-dd',
            language: 'ru',
            orientation: 'top auto',
            todayHighlight: true,
            toggleActive: true,
            autoclose: true,
        });
    }
    registerFilterDatePickers();
", CClientScript::POS_END); 
