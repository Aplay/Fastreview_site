<?php

$this->renderPartial('application.views.common._flashMessage');

/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#city-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
",CClientScript::POS_END);*/
?>
<div>
<?php // echo CHtml::link('Поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none;">
<?php // $this->renderPartial('_search',array( 'model'=>$model,)); ?>
</div><!-- search-form -->
</div>
<div class="form-horizontal" id="citySearche">
<div class="form-group">
<label for="City_title" class="col-sm-2 control-label">Город</label>
<div class="col-sm-10">
<input type="text" value="" style="width:50%" maxlength="255" name="City[title]" id="City_title" autocomplete="off" class="ui-autocomplete-input">
</div></div>
<div class="form-group">
<label for="City_url" class="col-sm-2 control-label">Алиас</label>
<div class="col-sm-10">
<input type="text" value="" style="width:50%" name="City[url]" id="City_url" autocomplete="off" class="ui-autocomplete-input">
</div></div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'city-grid',
	// 'itemsCssClass'=>'table  table-hover',
   //  'htmlOptions'=>array('class'=>'table-info panel-info'),
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
	'ajaxUpdate' => true,
    'afterAjaxUpdate' => "function(id,data){ plusScripts(); updateScripts(); }",
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
            'header' => 'Название',
            'name' => 'title',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
        array(
            'header' => 'Алиас (url)',
            'name' => 'url',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
      /*  array(
            'header' => 'Позиция',
            'name' => 'pos',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),*/
		/* array(
			'header' => 'Опубл.',
		    'name'=>'orgs_count',
		    'filter'=>false
		),
		 array(
			'header' => 'Неопубл.',
		    'name'=>'orgs_count_notactive',
		    'filter'=>false
		), */
		array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}',
            'htmlOptions'=>array('class'=>'two-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("city/admin/default/update", array("id"=>$data->id))',
                    
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("city/admin/default/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Are you sure you want to delete this?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("city-grid");
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


$url1 = Yii::app()->createUrl('city/admin/default/autocompletetitle');
$url2 = Yii::app()->createUrl('city/admin/default/autocompleteurl');

$script = "
function plusScripts() {
$('.add-tooltip').tooltip();
}


function updateScripts() {
$('#City_title').autocomplete({
    'minLength':'2',
    'source':'".$url1."',
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'title')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
        $.fn.yiiGridView.update('city-grid', {
        data: $('#citySearche  input').serialize()         
    })
    },
    'change':function(event, ui) {    
        $.fn.yiiGridView.update('city-grid', {
        data: $('#firmSearche  input').serialize()         
    })
    }
}).keyup(function (e) {
        if(e.which === 13) {
            $('.ui-autocomplete.ui-menu').hide();
            $.fn.yiiGridView.update('city-grid', {
                data: $('#citySearche  input').serialize()         
            })
        }            
});
$('#City_url').autocomplete({
    'minLength':'2',
    'source':'".$url2."',
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'url')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
        $.fn.yiiGridView.update('city-grid', {
        data: $('#citySearche  input').serialize()         
    })
    },
    'change':function(event, ui) {    
        $.fn.yiiGridView.update('city-grid', {
        data: $('#firmSearche  input').serialize()         
    })
    }
}).keyup(function (e) {
        if(e.which === 13) {
            $('.ui-autocomplete.ui-menu').hide();
            $.fn.yiiGridView.update('city-grid', {
                data: $('#citySearche  input').serialize()         
            })
        }            
});
}
plusScripts();
updateScripts();
";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>
