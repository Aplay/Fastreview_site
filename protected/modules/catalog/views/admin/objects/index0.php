<?php

$this->renderPartial('application.views.common._flashMessage');
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js', CClientScript::POS_END);
/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#orgs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
",CClientScript::POS_END);
*/
?>
<form id="firmSearche" action="" method="get">
<div class="form-horizontal col-lg-8 col-md-8 col-sm-12 col-xs-12">
<div class="form-group">
<label for="Orgs_title" class="col-sm-3 control-label">Организация</label>
<div class="col-sm-9">
<input type="text" class="ui-autocomplete-input form-control" value=""  maxlength="255" name="Orgs[title]" id="Orgs_title" autocomplete="off" >
</div>
</div>

<div class="form-group">
<label for="Orgs_rubric_title" class="col-sm-3 control-label">Рубрика</label>
<div class="col-sm-9">
<input type="text" class="ui-autocomplete-input form-control" value="" name="Orgs[rubric_title]" id="Orgs_rubric_title" autocomplete="off" >
</div></div>


<!--
<div class="form-group">
<label class="col-sm-3 col-xs-12 control-label">Период времени</label>
<div class="form-inline col-sm-9 col-xs-12">
<label for="Orgs_per_from"> с </label>
<input type="text" class="form-control"  value="" name="Orgs[per_from]" id="Orgs_per_from" autocomplete="off" >
<label for="Orgs_per_to"> по </label>
<input type="text" class="form-control"  value="" name="Orgs[per_to]" id="Orgs_per_to" autocomplete="off" >
</div>
</div>
-->
<div class="form-group">
<label for="Orgs_log_user" class="col-sm-3 control-label">Пользователь</label>
<div class="col-sm-9">
<input type="text" class="ui-autocomplete-input form-control" value=""  name="Orgs[log_user]" id="Orgs_log_user" autocomplete="off" >
</div>
</div>
<div class="form-group">
<label for="Orgs_log_user" class="col-sm-3 control-label">Вебсайт</label>
<div class="col-sm-9">
<input type="text" class="ui-autocomplete-input form-control" value=""  name="Orgs[web_site]" id="Orgs_web_site" autocomplete="off" >
</div>
</div>
<div class="form-group">
<label for="Orgs_status_org" class="col-sm-3 control-label">Статус</label>
<div class="col-sm-9"><?php // echo CHtml::checkbox('Orgs[status_org]', false, array('onchange'=>'javascript:ckeckboxChanged();')); ?>
<?php  
$statuses[4] = 'Все';
$statuses +=  Orgs::getStatusNames();
echo CHtml::dropDownList('Orgs[status_org]', 4, $statuses,
                    array(
                        'id'=>"Orgs_status_org",
                        'class'=>'form-control',
                        'onchange'=>'javascript:statusesChanged();'
                        )
                    ); 
?>
</div>
</div>

<!--
</div>
<div class="form-vertical col-lg-4 col-md-4 col-sm-12 col-xs-12">
-->
<div class="form-group">
<label for="Orgs_city_search" class="col-sm-3 control-label">Город</label>
<div class="col-sm-9">
<input class="ui-autocomplete-input form-control" type="text" value=""  name="Orgs[city_search]" id="Orgs_city_search" autocomplete="off" >
</div>
</div>
<div class="form-group">
<label for="Orgs_street" class="col-sm-3 control-label">Улица</label>
<div class="col-sm-9">
<input class="ui-autocomplete-input form-control" type="text" value=""  name="Orgs[street]" id="Orgs_street" autocomplete="off" >
</div>
</div>
<div class="form-group">
<label for="Orgs_dom" class="col-sm-3 control-label">Дом</label>
<div class="col-sm-9">
<input class="ui-autocomplete-input form-control" type="text" value=""  name="Orgs[dom]" id="Orgs_dom" autocomplete="off" >
</div>
</div>

</div>

<div class="form-horizontal col-lg-8 col-md-8 col-sm-12 col-xs-12">
<div class="form-group">
<div class="col-sm-3"></div>
<div class="col-sm-4 col-xs-12 text-left">
<button type="submit" class="btn" onclick="submitThis();return false;">Обновить фильтр</button>
</div>

<div class="col-sm-5 hidden-xs text-right">
<a href="javascript:void(0);" class="btn massedit"  target="_blank">Массовое редактирование</a>
</div>
<div class="hidden-sm hidden-md hidden-lg col-xs-12 text-left" >
<br>
<a href="javascript:void(0);" class="btn massedit" target="_blank">Массовое редактирование</a>
</div>

</div>
</div>
</form>
<div class="clearfix"></div>
<?php 
Yii::app()->clientScript->registerScript("pageDatepickers", "

    function registerFilterDatePickers(id, data){
        jQuery('#Orgs_per_from').datepicker({
            format: 'yyyy-mm-dd',
            language: 'ru',
            orientation: 'top auto',
            todayHighlight: true,
            toggleActive: true,
            autoclose: true,
        }).on('changeDate', function(e){
            $('.ui-autocomplete.ui-menu').hide();
            $.fn.yiiGridView.update('orgs-grid', {
                data: $('#firmSearche').serialize()         
            })
        });
        jQuery('#Orgs_per_to').datepicker({
            format: 'yyyy-mm-dd',
            language: 'ru',
            orientation: 'top auto',
            todayHighlight: true,
            toggleActive: true,
            autoclose: true,
        }).on('changeDate', function(e){
            $('.ui-autocomplete.ui-menu').hide();
            $.fn.yiiGridView.update('orgs-grid', {
                data: $('#firmSearche').serialize()         
            })
        });
    }
    registerFilterDatePickers();
", CClientScript::POS_END); 
// $this->renderPartial('_search',array('model'=>$model)); ?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'orgs-grid',
	//'itemsCssClass'=>'table  table-hover',
    //'htmlOptions'=>array('class'=>'panel-info'),
	'dataProvider'=>$model_search,
	// 'filter'=>$model,
	'ajaxUpdate' => true,
    'afterAjaxUpdate' => "function(id,data){ plusScripts();  updateScripts();  }",
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
           /* 'value'=>function($data) { 
                return CHtml::encode($data->title);
            },*/
            'type' => 'raw',
            'filter'=>false,
           /* 'filter'=>$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'model'=>$model,
                        'attribute'=>'title',
                        'source'=>Yii::app()->createUrl('catalog/admin/company/autocompletetitle'),

                        'options'=>array(
                             'minLength'=>'2',
                             'showAnim'=>'fold',
                             
                        ),
                        'htmlOptions'=>array('autocomplete'=>'off'),
            ),true),*/
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
       /* array(
            'header' => 'Алиас (url)',
            'name' => 'url',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),*/
        array(
         //   'class'=>'ext.widgets.grid.EDataColumnExt',
            'header' => 'Адрес',
           // 'name' => 'address_search',
            'type' => 'html',
            'value' => 'Yii::app()->controller->renderPartial("_grid_company_address", array("data"=>$data), true)',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
           'filter'=>false,
           /* 'filter'=>$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'model'=>$model,
                        'attribute'=>'address_search',
                        'source'=>Yii::app()->createUrl('catalog/admin/company/autocompleteaddress'),

                        'options'=>array(
                            'minLength'=>'2',
                            'showAnim'=>'fold',
                        ),
                        'htmlOptions'=>array('autocomplete'=>'off'),
            ),true),*/
            
        ),
        array(
            'header' => 'Рубрики',
           // 'name' => 'rubric_title',
            'type' => 'html',
            'value' => 'Yii::app()->controller->renderPartial("_grid_company_rubrics", array("data"=>$data), true)',
           'filter'=>false,
           /* 'filter'=>$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'model'=>$model,
                        'attribute'=>'rubric_title',
                        'source'=>Yii::app()->createUrl('catalog/admin/company/autocompleterubrics'),

                        'options'=>array(
                            'minLength'=>'2',
                            'showAnim'=>'fold',
                        ),
                        'htmlOptions'=>array('autocomplete'=>'off'),
            ),true),*/
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
        ),
        array(
            'header' => 'Редакт.',
           // 'name' => 'updated_date',
            'type' => 'html',
          /*  'value' => function($data) {
                $str = $data->updated_date;
                if($data->lasteditor){
                  $str .= '<br>'. $data->lasteditorid->username;
                }
                return $str;
            },*/
            'value' => 'Yii::app()->controller->renderPartial("_grid_log", array("data"=>$data), true)',
          
            'filter'=>false,
          /*  'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(  
                'model'=>$model,   
                'attribute'=>'updated_date',  
                'language'=>'ru',  
                'options'=>array(  

                        'showAnim'=>'fold',  
                        'dateFormat'=>'yy-mm-dd',  
                        'changeMonth' => 'true',  
                        'changeYear'=>'true',  
                        'showButtonPanel' => 'true', 
                        'beforeShow'=>'js: function(picker, inst) { 
                            $("#ui-datepicker-div").css("top","50px !important"); 
                        }'
                        
                ),  
                
        ),true),*/
            'htmlOptions'=>array('class'=>'',
                'style'=>'width:13%'),
        ),
		array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{view}{viewin}{delete}',
            'htmlOptions'=>array('class'=>'three-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/company/update", array("id"=>$data->id))',
                    
                ),
                'view'  => array(
                    'label' => 'Снять с публикации',
                    'icon'  => 'unlock',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/company/updatestatus", array("id"=>$data->id,"status"=>'.Orgs::STATUS_INACTIVE.'))',
                    'visible' => '($data->status_org=='.Orgs::STATUS_ACTIVE.')?true:false;',
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
                    'url'   => 'Yii::app()->createUrl("catalog/admin/company/updatestatus", array("id"=>$data->id,"status"=>'.Orgs::STATUS_ACTIVE.'))',
                    'visible' => '($data->status_org=='.Orgs::STATUS_INACTIVE.')?true:false;',
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
                    'url'   => 'Yii::app()->createUrl("catalog/admin/company/delete", array("id"=>$data->id))',
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

$url1 = Yii::app()->createUrl('catalog/admin/company/autocompletetitle');
$url2 = Yii::app()->createUrl('catalog/admin/company/autocompleteaddress');
$url3 = Yii::app()->createUrl('catalog/admin/company/autocompleterubrics');
$url4 = Yii::app()->createUrl('catalog/admin/company/autocompleteloguser');

$url5 = Yii::app()->createUrl('catalog/admin/company/autocompletecity');
$url6 = Yii::app()->createUrl('catalog/admin/company/autocompletestreet');
$url7 = Yii::app()->createUrl('catalog/admin/company/autocompletedom');


$urlMass = Yii::app()->createUrl('catalog/admin/company/massupdate'); 
// подправить в модели Orgs. Если выбрать Москву, потом любую организацию, то выведет что-то,
// хотя не должен. Суть в том, что в search Orgs заключено много compare и где-то OR, где-то AND
$script = "
hpv = $('#csfr').attr('value'),
hpt = $('#csfr').attr('name');

function plusScripts() {
$('.add-tooltip').tooltip();
 
}
var Orgs_status_org = $('#Orgs_status_org').val();
function updateScripts() {
// $('#Orgs_updated_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'}));

$('#Orgs_city_search').autocomplete({
    'minLength':'2',
     source: function(request, response) {
        $.ajax({
            url: '".$url5."',
            dataType: 'json',
            data: {
                term : request.term,
                title : $('#Orgs_title').val(),
                status_org: $('#Orgs_status_org').val(),
                street: $('#Orgs_street').val(),
                dom: $('#Orgs_dom').val(),
                rubric_title: $('#Orgs_rubric_title').val()
            },
            success: function(data) {
                response(data);
            }
        });
    },
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'city_search')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
       //  submitThis();
    },
    'change':function(event, ui) {    
       //  submitThis();
    }
}).keyup(function (e) {
        if(e.which === 13) {
          //   submitThis();
        }            
});
$('#Orgs_street').autocomplete({
    'minLength':'2',
    source: function(request, response) {
        $.ajax({
            url: '".$url6."',
            dataType: 'json',
            data: {
                term : request.term,
                title : $('#Orgs_title').val(),
                status_org: $('#Orgs_status_org').val(),
                city_search : $('#Orgs_city_search').val(),
                dom: $('#Orgs_dom').val(),
                rubric_title: $('#Orgs_rubric_title').val()
            },
            success: function(data) {
                response(data);
            }
        });
    },
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'street')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
        // submitThis();
    },
    'change':function(event, ui) {    
       //  submitThis();
    }
}).keyup(function (e) {
        if(e.which === 13) {
          //   submitThis();
        }            
});
$('#Orgs_dom').autocomplete({
    'minLength':'1',
    source: function(request, response) {
        $.ajax({
            url: '".$url7."',
            dataType: 'json',
            data: {
                term : request.term,
                title : $('#Orgs_title').val(),
                status_org: $('#Orgs_status_org').val(),
                city_search : $('#Orgs_city_search').val(),
                street: $('#Orgs_street').val(),
                rubric_title: $('#Orgs_rubric_title').val()
            },
            success: function(data) {
                response(data);
            }
        });
    },
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'dom')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
      //   submitThis();
    },
    'change':function(event, ui) {    
      //  submitThis();
    }
}).keyup(function (e) {
        if(e.which === 13) {
           //  submitThis();
        }            
});
$('#Orgs_rubric_title').autocomplete({
    'minLength':'2',
    source: function(request, response) {
        var datav =  {term : request.term,
                title : $('#Orgs_title').val(),
                status_org: $('#Orgs_status_org').val(),
                city_search : $('#Orgs_city_search').val(),
                street: $('#Orgs_street').val(),
                dom: $('#Orgs_dom').val()};
        datav[hpt] = hpv;
        $.ajax({
            type:'POST',
            url: '".$url3."',
            dataType: 'json',
            data: datav,
            success: function(data) {
                response(data);
            }
        });
    },
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'rubric_title')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
       //  submitThis();
    },
    'change':function(event, ui) {    
        // submitThis();
    }
}).keyup(function (e) {
        if(e.which === 13) {
           //  submitThis();
        }            
});


$('#Orgs_log_user').autocomplete({
    'minLength':'2',
    'source':'".$url4."',
    'showAnim':'fold',
    'focus':function(event, ui) {
                $('#".CHtml::activeId($model,'log_user')."').val(ui.item.value);
    },
    'select':function(event, ui) {    
       // submitThis();
    },
    'change':function(event, ui) {    
       // submitThis();
    }
}).keyup(function (e) {
        if(e.which === 13) {
          //  submitThis();
        }            
});
}
function ckeckboxChanged(){
    Orgs_status_org = $('#Orgs_status_org').is(':checked');
  //  submitThis();
}
function statusesChanged(){
    Orgs_status_org = $('#Orgs_status_org').val();
   // submitThis();
}

function submitThis(){
    $('.ui-autocomplete.ui-menu').hide();
    var ser = $('#firmSearche').serialize();
    addMassEdit(ser);
    $.fn.yiiGridView.update('orgs-grid', {
        data: ser     
    })
}
function addMassEdit(ser){
    var href = '".$urlMass."'+'?'+ser;
    $('a.massedit').attr('href',href);
}


plusScripts();
updateScripts();
";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>
