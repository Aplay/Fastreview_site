<?php
$this->breadcrumbs=array(
  'Атрибуты'=>array('index'),
  'Список',
);

$this->menu=array(
 // array('label'=>'Создать группу для атрибутов', 'url'=>array('creategroup')),
  array('label'=>'Создать атрибут', 'url'=>array('create')),
);
$this->widget('zii.widgets.CMenu', array(
    'items'=>$this->menu,
));
?>


<?php
$this->renderPartial('application.views.common._flashMessage');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'attribute-grid',
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
  	'title',
  //  'group_id',

    array(
      'name' => 'type',
      'value' => 'CHtml::encode($data->getTypeTitle($data->type))',
      'filter'=>false
      //'filter' => CHtml::activeDropDownList($model, 'type', $model->getTypesList()),
    ),

    array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{delete}',
            'htmlOptions'=>array('class'=>'three-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/attribute/update", array("id"=>$data->id))',
                    
                ),
              
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("catalog/admin/attribute/delete", array("id"=>$data->id))',
                    'visible' => 'true',
                    'click'=>'function(){
                        if (confirm("Действительно удалить навсегда?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("attribute-grid");
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

function plusScripts() {
$('.add-tooltip').tooltip();
 
}

plusScripts();

";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>