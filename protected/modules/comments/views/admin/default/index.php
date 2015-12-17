<?php

/**
 * Display comments list
 *
 * @var $model Comment
 **/

Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/admin/comments.index.js');
$this->renderPartial('application.views.common._flashMessage');



$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'id'           => 'comment-grid',
	'filter'       => $model,
	// 'itemsCssClass'=>'table  table-hover',
   // 'htmlOptions'=>array('class'=>'panel-info'),
	'ajaxUpdate' => false,
    'afterAjaxUpdate' => "function(id,data){ plusScripts() }",
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
	/*'customActions'=>array(
		array(
			'label'=>Yii::t('CommentsModule.core', 'Подтвержден'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setCommentsStatus(1, this);',
			)
		),
		array(
			'label'=>Yii::t('CommentsModule.core', 'Ждет одобрения'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setCommentsStatus(0, this);',
			)
		),
		array(
			'label'=>Yii::t('CommentsModule.core', 'Спам'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setCommentsStatus(2, this);',
			)
		),
	),*/
	'columns' => array(
	/*	array(
			'class'=>'CCheckBoxColumn',
		),
		array(
			'name'=>'id',
		),*/
		 /* array(
			'name'  => 'name',
			'type'  => 'raw',
			'value' => 'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
		),
		array(
			'name'=>'email',
		), */
		array(
			'header' => 'Отзыв',
			'name'=>'text',
			// 'value'=>'Comment::truncate($data, 100)'
		),
		array(
			'name'=>'status',
			'filter'=>Comment::getStatuses(),
			'value'=>'$data->statusTitle',
		),
		array(
			'header' => 'Оценка',
			'name'=>'rating',
			'filter'=>false
		),
		
		array(
			'header' => 'Объект',
            'type'=>'raw',
			'value'=>'CHtml::link($data->getOwner_title(), $data->getViewUrlAdmin(), array("target"=>"_blank"))',
			'filter'=>false
		),

		array(
			'header' => 'Дата',
			'name'=>'created',
			'filter'=>false
		),
		array(

			'name'=>'ip_address',

		),

		// Buttons
		array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{view}{viewin}{delete}',
            'htmlOptions'=>array('class'=>'three-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("/comments/admin/default/update", array("id"=>$data->id))',
                    
                ),
                'view'  => array(
                    'label' => 'Снять с публикации',
                    'icon'  => 'unlock',
                    'url'   => 'Yii::app()->createUrl("comments/admin/default/updatestatus", array("id"=>$data->id,"status"=>'.Comment::STATUS_WAITING.'))',
                    'visible' => '($data->status=='.Comment::STATUS_APPROVED.')?true:false;',
                    'click'=>'function(){
                        if (confirm("Действительно снять с публикации?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("comment-grid");
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
                    'url'   => 'Yii::app()->createUrl("comments/admin/default/updatestatus", array("id"=>$data->id,"status"=>'.Comment::STATUS_APPROVED.'))',
                    'visible' => '($data->status=='.Comment::STATUS_WAITING.')?true:false;',
                    'click'=>'function(){
                        if (confirm("Действительно опубликовать?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("comment-grid");
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
                    'url'   => 'Yii::app()->createUrl("/comments/admin/default/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Вы действительно хотите удалить этот отзыв?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("comment-grid");
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

}

plusScripts();
";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>