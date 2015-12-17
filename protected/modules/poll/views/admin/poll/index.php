<?php
$this->breadcrumbs=array(
  'Голосование'=>array('index'),
  'Список',
);

$this->menu=array(
  array('label'=>'List Polls', 'url'=>array('index')),
  array('label'=>'Create Poll', 'url'=>array('create')),
);
/*$this->widget('zii.widgets.CMenu', array(
    'items'=>$this->menu
));*/
// echo '<a href="/admin_cat/poll/poll/create">Создать голосование</a>';
/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $.fn.yiiGridView.update('poll-grid', {
    data: $(this).serialize()
  });
  return false;
});
");*/
?>


<?php // echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php /* $this->renderPartial('_search',array(
  'model'=>$model,
)); */ ?>
</div><!-- search-form -->
<?php
$this->renderPartial('application.views.common._flashMessage');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'poll-grid',
  'ajaxUpdate' => true,
  'afterAjaxUpdate' => "function(id,data){ plusScripts();  }",
    'loadingCssClass'=>'empty-loading',
    'template'=>"{summary}\n{items}\n{pager}",
    'summaryText'=>Yii::t('site','Displaying {start}-{end} of {count}'),
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
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
  	'label',
  	array(
			'header' => 'Фирма',
            'type'=>'raw',
			'value'=>'CHtml::link($data->org->title, Yii::app()->createAbsoluteUrl("/catalog/admin/company/update", array("id"=>$data->org_id)), array("target"=>"_blank"))',
			'filter'=>false
		),
  	array(
			'header' => 'Город',
            'type'=>'raw',
			'value'=>'$data->org->city->title',
			'filter'=>false
		),
    array(
      'name' => 'type',
      'value' => 'CHtml::encode($data->getTypeLabel($data->type))',
      'filter' => CHtml::activeDropDownList($model, 'type', $model->typeLabels()),
    ),
   /* array(
      'name' => 'status',
      'value' => 'CHtml::encode($data->getStatusLabel($data->status))',
      'filter' => CHtml::activeDropDownList($model, 'status', $model->statusLabels()),
    ),*/
    array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{view}{viewin}{delete}',
            'htmlOptions'=>array('class'=>'three-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("poll/admin/poll/update", array("id"=>$data->id))',
                    
                ),
                'view'  => array(
                    'label' => 'Снять с публикации',
                    'icon'  => 'unlock',
                    'url'   => 'Yii::app()->createUrl("poll/admin/poll/updatestatus", array("id"=>$data->id,"status"=>'.PollChoice::STATUS_CLOSED.'))',
                    'visible' => '($data->status=='.PollChoice::STATUS_OPEN.')?true:false;',
                    'click'=>'function(){
                        if (confirm("Действительно снять с публикации?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("poll-grid");
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
                    'url'   => 'Yii::app()->createUrl("poll/admin/poll/updatestatus", array("id"=>$data->id,"status"=>'.PollChoice::STATUS_OPEN.'))',
                    'visible' => '($data->status=='.PollChoice::STATUS_CLOSED.')?true:false;',
                    'click'=>'function(){
                        if (confirm("Действительно опубликовать?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("poll-grid");
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
                    'url'   => 'Yii::app()->createUrl("poll/admin/poll/delete", array("id"=>$data->id))',
                    'visible' => 'true',
                    'click'=>'function(){
                        if (confirm("Действительно удалить навсегда?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("poll-grid");
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