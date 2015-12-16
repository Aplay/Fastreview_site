<?php
/* @var $this ZalController */
/* @var $model Zal */

$this->breadcrumbs=array(
	'Промо коды'
);


$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
	array('label'=>'Создать промо код', 'url'=>array('create')),
)
));

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'promo-grid',
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

		'promo',
        'description',
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
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/promo/update", array("id"=>$data->id))',
                    
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/promo/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Вы действительно хотите удалить?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("promo-grid");
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
