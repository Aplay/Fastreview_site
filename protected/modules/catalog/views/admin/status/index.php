<?php
/* @var $this ZalController */
/* @var $model Zal */

$this->breadcrumbs=array(
	'Статусы'
);


$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
	array('label'=>'Создать статус', 'url'=>array('create')),
)
));

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'status-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
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

		'title',
        array(
            'name' => 'text',
            'type' => 'html',
        ),
		array(
            'name' => 'type',
            'filter'=>false,
            'type' => 'raw',
            'value'=>function($data) {
                    $stats =  Status::getStatusNames();
                    if(isset($stats[$data->type]))
                        return $stats[$data->type];
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
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/status/update", array("id"=>$data->id))',
                    
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/status/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Вы действительно хотите удалить?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("status-grid");
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
