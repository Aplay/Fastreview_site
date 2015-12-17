
<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

// http://yamal-web.ru/post/32?title=%D0%9F%D0%BE%D0%BB%D0%B5%D0%B7%D0%BD%D1%8B%D0%B5+%D1%84%D1%83%D0%BD%D0%BA%D1%86%D0%B8%D0%B8+%D0%B4%D0%BB%D1%8F+%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B+%D1%81+Yii+Rights+extension
// To determine if the logged user is superuser
// VarDumper::dump(Yii::app()->user->isSuperuser);

// Get all superusers in the application
// VarDumper::dump(Yii::app()->getModule("rights")->getAuthorizer()->getSuperusers());

//
/*
$roles=Rights::getAssignedRoles(Yii::app()->user->id);
foreach($roles as $role)
       echo $role->name."<br />";
VarDumper::dump($roles); die(); // Ctrl + X    Delete line
*/
$this->widget('ext.widgets.grid.MyGridView', array(
	'id'=>'dashboard-users',
    'dataProvider' => $dataProvider,
    'itemsCssClass'=>'table table-hover no-bg-th',
    'htmlOptions'=>array('class'=>'table-info panel panel-success no-border grid-view-green'),
    'headlineCaption'=>Yii::t('site','Users'),
    'headlineControls'=>'<div class="panel-heading-controls">
                            <form action="'.Yii::app()->createUrl($this->route).'"  method="get" id="searchUsersForm" style="width:100%">
                            <input type="hidden" name="'.Yii::app()->request->csrfTokenName.'" value="'.Yii::app()->request->csrfToken.'" />
                                <div class="input-group input-group-sm">
                                    <input type="text" name="s" placeholder="'.Yii::t('site','Search').'" class="form-control" style="width:100%">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn">
                                            <span class="fa fa-search"></span>
                                        </button>
                                    </span>
                                </div> <!-- / .input-group -->
                            </form>
                        </div>',
   // 'itemsCssClass'=>'table table-hover',
  // 'htmlOptions'=>array('class'=>'table-info panel panel-success no-border grid-view-green'),
    'ajaxUpdate' => true,
    'afterAjaxUpdate' => "function(id,data){ plusScripts() }",
    'loadingCssClass'=>'empty-loading',
    'template'=>"{headline}\n{items}\n{pager}",
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
   // 'enableHistory'=>true,
    //'filter' => $model,
    'columns' => array(
    	array(
            'name' => 'id',
            'header' => '#',
            'type' => 'raw',
            'filter' => false
        ),
    	array(
    		'name' => 'username',
            'header' => Yii::t('site','Username'),
            'type' => 'html',
            'filter' => false,
            'value' => 'Yii::app()->controller->renderPartial("_grid_users_title", array("data"=>$data), true)',
            'htmlOptions'=>array('class'=>'widget-followers'),

        ),
        array(
    		'name' => 'fullname',
            'header' => Yii::t('site','Full name'),
            'type' => 'html',
            'filter' => false,
            'value' => function ($data) {
            	return CHtml::link($data->fullname,Yii::app()->createUrl('/users/user/view',array('url'=>$data->username)));
            },

        ),
        array(
    		'name' => 'email',
            'header' => Yii::t('site','E-mail'),
            'type' => 'raw',
            'filter' => false,
            'headerHtmlOptions'=>array('class'=>'hidden-td-450'),
            'htmlOptions'=>array('class'=>'hidden-td-450'),


        ),
        array(
            'header' => Yii::t('site','Permission'),
            'type' => 'raw',
            'value' => 'Yii::app()->controller->renderPartial("_grid_status", array("data"=>$data), true)',
           // 'htmlOptions'=>array('class'=>'td-column-30'),

        ),
  
        array(
            'class' => 'ext.widgets.grid.MyButtonColumn',
            'template' => '{update}{delete}',
            'htmlOptions'=>array('class'=>'two-button-column'),
            'buttons' => array(
                'update'  => array(
                    'label' => Yii::t('site', 'Edit'),
                    'icon'  => 'pencil',
                    'url'   => 'Yii::app()->createUrl("/users/admin/default/update", array("id"=>$data->id))',
                    
                ),
                'delete' => array(
                    'label' => Yii::t('site', 'Delete'),
                    'icon'  => 'times',
                    'url'   => 'Yii::app()->createUrl("/users/admin/default/delete", array("id"=>$data->id))',
                    'click'=>'function(){
                        if (confirm("Are you sure you want to delete this?")) {
                        $.ajax({
                            url: $(this).attr("href"),
                            success:function(data) {
                                if(data!="[]"){
                                    alert(data);
                                } else {
                                    $.fn.yiiGridView.update("dashboard-users");
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



$script = "
function plusScripts() {
$('.add-tooltip').tooltip();
$('#searchUsersForm').submit(function(){
    $.fn.yiiGridView.update('dashboard-users', {
        data: $(this).serialize()
    });
    return false;
});

$('#dashboard-users a.status.editable').editable({
    source: [
        {value: 1, text: '".Yii::t('site', 'Admin')."'},
        {value: 0, text: '".Yii::t('site', 'User')."'}
    ],
    type    : 'select',
    display: function(value, sourceData) {
 
        var colors = {'': 'gray', 1: 'green', 0: '#4083a9'},
            elem = $.grep(sourceData, function(o){return o.value == value;});

        if(elem.length) {
           $(this).text(elem[0].text).css('color', colors[value]);

        } else {
            $(this).empty();
        }

    },
    params: function(params) {
    //originally params contain pk, name and value
    params.".$csrfTokenName." = '".$csrfToken."';
    return params;
    },
    'url': '/users/admin/default/updateUserStatusInProject',
    success: function(data, config) {
        console.log(data)
        if(data) { 
            if(data == 'me'){
                window.location.reload();
            }
            } 
    },

});
}

plusScripts();
";
Yii::app()->clientScript->registerScript("editableStart", $script, CClientScript::POS_END);

?>