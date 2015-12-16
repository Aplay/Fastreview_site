<?php
$themeUrl = '/themes/bootstrap_311/';
$this->renderPartial('application.views.common._flashMessage');
Yii::app()->clientScript->registerScriptFile($themeUrl.'js/plugins/lightbox/js/lightbox.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl.'js/plugins/lightbox/css/lightbox.css');

?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'photos-grid',
	//'itemsCssClass'=>'table  table-hover',
    //'htmlOptions'=>array('class'=>'panel-info'),
	'dataProvider'=>$model_search,
	// 'filter'=>$model,
	'ajaxUpdate' => false,
    'afterAjaxUpdate' => "function(id,data){ plusScripts();   }",
   // 'loadingCssClass'=>'empty-loading',
    'template'=>"{summary}\n{items}\n{pager}",
    'summaryText'=>Yii::t('site','Displaying {start}-{end} of {count}'),
   // 'rowCssClassExpression'=>'$data->verified?(($row % 2) ? "odd" : "even"):"row-not-verified"',
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
            'header' => 'Кто добавил',
            'name' => 'uploaded_by',
            'filter'=>false,
            'type' => 'raw',
            'value'=>function($data) {
            	 if($data->uploadedBy){
            	 	return CHtml::link($data->uploadedBy->fullname,Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$data->uploadedBy->username)),array('target'=>'_blank'));
            	 } else {
            	 	return "Аноним";
            	 }
                 
            }
        ),
		array(
            'header' => 'Фирма',
            'name' => 'org',
            'filter'=>false,
            'type' => 'raw',
            'value'=>function($data) {
            	 if($data->organization){
            	 	return CHtml::link($data->organization->title,Yii::app()->createAbsoluteUrl('/catalog/catalog/item',array('city'=>$data->organization->city->url, 'id'=>$data->organization->id,  'itemurl'=>$data->organization->url)), array('target'=>'_blank')).'<br>'.Yii::app()->controller->renderPartial("application.modules.catalog.views.admin.company._grid_company_address", array("data"=>$data->organization), true);
            	 } else {
            	 	return "";
            	 }
            },
        ),
        array(
            'header' => 'Фото',
            'name' => 'filename',
            'type' => 'raw',
            'filter'=>false,
            'value' => 'Yii::app()->controller->renderPartial("_grid_photo", array("data"=>$data), true)',
            'htmlOptions'=>array('style'=>'width:155px;'),
           /* 'value'=>function($data) {
            	// $src = '';
				$src = Yii::app()->createAbsoluteUrl('/file/file/company',array('id'=>$data->id));
                // $src = '/uploads/orgs/'.$data->organization->id.'/'.$data->filename;
            	return CHtml::link(CHtml::image($src,'',array('style'=>'width:155px;height:auto;')), $src ,array('data-lightbox'=>'lb-'.$data->id));
            }*/
        ),
        array(
            'header' => 'Просм.',
            'name' => 'verified',
            'type' => 'raw',
            'filter'=>false,
            'cssClassExpression' => '$data["verified"] == 1 ? "green" : "red"',
           'value'=>''
        ),
		array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{delete}',
           // 'htmlOptions'=>array('class'=>'two-button-column'),
            'buttons' => array(
                
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createAbsoluteUrl("catalog/admin/company/deletefile", array("id"=>$data->id))',
                    'visible' => 'true',
                    'click'=>'function(){
                        if (confirm("Действительно удалить навсегда?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("photos-grid");
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
