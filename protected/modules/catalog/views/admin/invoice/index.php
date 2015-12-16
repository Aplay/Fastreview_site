<?php
/* @var $this ZalController */
/* @var $model Zal */

$this->breadcrumbs=array(
	'Оплаты'
);




$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'plan-grid',
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

		'user_id',
        'org_id',
        'amount',
        array(
            'header' => 'Период',
            'type' => 'raw',
            'value'=>function($data) {
                 return $data->period.' '.Yii::t('site',Plan::getPlanSclon($data->period_type),$data->period);
            }
        ),
        'sum',
        array(
            'name' => 'promo_id',
            'filter'=>false,
            'type' => 'raw',
            'value'=>function($data) {
                if($data->promo_id)
                    return $data->promo->promo;
                return '';
            }
        ),
        'discount',
        'sum_discount',
        'created_date',
		array(
            'name' => 'status',
            'filter'=>false,
            'type' => 'raw',
            'value'=>function($data) {
                 if($data->status){
                    return 'Оплачено';
                 } else {
                    return "Не оплачено";
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
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/invoice/update", array("id"=>$data->id))',
                    
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/invoice/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Вы действительно хотите удалить?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("plan-grid");
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
