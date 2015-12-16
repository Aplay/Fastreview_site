<?php
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl . '/js/plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/fancybox2/jquery.fancybox.css');
$this->renderPartial('application.views.common._flashMessage');
?>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'category-grid',
    'dataProvider'=>$dataProvider,
    'filter'=>$model,
   // 'itemsCssClass'=>'table  table-hover',
   // 'htmlOptions'=>array('class'=>'table-info panel-info'),
    //'headlineCaption'=>Yii::t('site','Projects'),
   // 'hideHeader'=>true,
    'ajaxUpdate' => true,
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
    'columns'=>array(

        array(
            'header' => 'Теги фирм',
            'name' => 'title',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'widget-support-tickets'),
            
        ),
        array(
            'header' => 'Родитель тега',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->renderPartial("_grid_rubrics_parent", array("data"=>$data), true)',
           
        ),
        array(
			'header' => 'Кол-во фирм',
		    'name'=>'orgs_count',
		  //  'value'=>'$data->orgs_count ? $data->orgs_count : ""',
		    'filter'=>false
		),
        array(
            'header' => 'Кол-во неопубл. фирм',
            'name'=>'orgs_count_not_published',
            'filter'=>false
        ),
        array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{delete}',
            'htmlOptions'=>array('class'=>'two-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/default/update", array("id"=>$data->id))',
                    
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("/catalog/admin/default/delete", array("id"=>$data->id))',
                    'options' => array(
                                'data-id' => '$data->id',
                     ),
                    'click'=>'function(){
                    	var id = $(this).data("id");
                        if (confirm("Вы действительно хотите удалить эту рубрику? Компании удалены не будут.")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                            	response = $.parseJSON(data);
                    			if (!response.success) {
                    				if(response.errortype == 1)
                                    	alert(response.message);
                                    if(response.errortype == 2)
                                    	MoveFirms(id, response.message)
                                } else {
                                    $.fn.yiiGridView.update("category-grid");
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
?>
<?php

$url = Yii::app()->createUrl('catalog/admin/default/returnMove_Firms');

$script = '
function plusScripts() {
$(".add-tooltip").tooltip();
}
function MoveFirms (id, text) {
			var hn = "'.Yii::app()->request->csrfTokenName.'";
			var hv = "'.Yii::app()->request->csrfToken.'";
			var datav = {"id":id,"text":text};
			datav[hn] = hv;
            $.ajax({
                type:"POST",
                url:"'.$url.'",
                data:datav,
                success:function (data) {
                    $.fancybox(data,
                        {    "transitionIn":"elastic",
                            "transitionOut":"elastic",
                            "speedIn":600,
                            "speedOut":200,
                            "overlayShow":false,
                            "hideOnContentClick":false,
                            "width": 750,
                            "autoSize" : false,
                            "afterClose":function () {
                            } //onclosed function
                        })//fancybox
        } //function
  });//ajax
}
plusScripts();
';
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>
