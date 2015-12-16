<?php

/**
 * Display logs
 **/

$this->pageHeader = 'Статистика';
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js', CClientScript::POS_END);
// $this->widget('ext.sgridview.SGridView', array(
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'    => $dataProvider,
	'id'              => 'loggerListGrid',
	'afterAjaxUpdate' => "function(){plusScripts();registerFilterDatePickers();}",
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
		/*array(
			'class'=>'CCheckBoxColumn',
		),*/
	//	array('name'=>'id'),
		array(
			'name'=>'search_user',
			'value'=>function($data) {
                 return ($data->userid)?$data->userid->username:"";
            },
		),
		array(
			'name'=>'event',
			'type'=>'raw',
			'value'=>'$data->actionTitle',
			'filter'=>ActionLog::getEventNames()
		),
		array(
			'name'=>'model_name',
			'type'=>'raw',
			'value'=>'$data->getHumanModelName()',
			'filter'=>$model->getModelNameFilter()
		),
		array(
			'name'=>'model_id',
			'type'=>'raw',
			'value'=>function($data) {
				 if($data->model_name == 'Orgs')
				 {
				 	$ret = $data->org?CHtml::link($data->model_id, Yii::app()->createAbsoluteUrl('catalog/admin/company/update', array("id"=>$data->model_id)), array('target'=>'_blank')):$data->model_id;
				 }
                 else 
                 {
                 	$ret = $data->cat?CHtml::link($data->model_id, Yii::app()->createAbsoluteUrl('catalog/admin/default/update', array("id"=>$data->model_id)), array('target'=>'_blank')):$data->model_id;
                 }
                 return $ret;
            },
		),
		array(
			'name'=>'model_title',
			'type'=>'raw',
			'value'=>function($data) {
                 if($data->model_name == 'Orgs') {
                 	if(!$data->org)
                 	{
				 		$ret = $data->model_title;
                 	}
                 	else
                 	{
                 		$ret = CHtml::link($data->org->title, Yii::app()->createAbsoluteUrl('catalog/admin/company/update', array("id"=>$data->model_id)), array('target'=>'_blank'));
                 	
                 	}
                 } else {
                 	if(!$data->cat){
                 		$ret = $data->model_title;
                
                 	} else {
                 		$ret = CHtml::link($data->cat->title, Yii::app()->createAbsoluteUrl('catalog/admin/default/update', array("id"=>$data->model_id)), array('target'=>'_blank'));
                
                 	}
                 }
                 return $ret;
            },
		),
		array(
			'name'=>'datetime',
		),
		// Buttons
	/*	array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
		),*/
		array(
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
                                    $.fn.yiiGridView.update("loggerListGrid");
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
));
$script = "
function plusScripts() {
$('.add-tooltip').tooltip();
};

plusScripts();
";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

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
