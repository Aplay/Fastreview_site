<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
$this->renderPartial('application.views.common._flashMessage');

$addClass = '';
if($model->isNewRecord){ 
    
?>

    <div class="panel-body padding-sm">
        <span class="panel-title">Создать рубрику</span>
    </div>
<?php } else {
?>
    <div class="panel-body padding-sm">
        <span class="panel-title">Редактировать рубрику</span>
    </div>


    <?php
    $addClass = 'tab-content-padding';
    } ?>
    <div class="<?php if($model->isNewRecord) { echo 'panel-body'; } ?>">
    <?php
        $form = $this->beginWidget('CActiveForm', array(
                'id' => 'project-form',
                'enableAjaxValidation'=>false,
                'htmlOptions'=>array('class'=>"panel form-horizontal no-border no-margin-b $addClass",'enctype' => 'multipart/form-data')
                ));
                ?>
        <div class="form-group">
            <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Название')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'url', array('class'=>'col-md-1 col-sm-2 control-label')); ?>

            <div class="col-md-11 col-sm-10">
            <?php echo $form->textField($model,'url',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'На латинице')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
                <?php echo $form->labelEx($model, 'parent_id',  array('class'=>'col-md-1 col-sm-2 control-label')); ?>
                <div class="col-md-11 col-sm-10">
                <?php
                echo $form->dropDownList($model, 'parent_id', Category::TreeArrayActive(), array(
                        'encode'=>false,
                        'empty'=>Yii::t('site', 'None'),
                        'class'=>'form-control'
                ));

                ?>
                </div>
        </div>
    <div class="row">
    <?php $tree = [];
    $selectedAttributes = [];
    $model->refresh();
    foreach ($model->typeAttributes as $attribute) {
        $selectedAttributes[] = $attribute->id;
    }
    foreach ((array)EavOptionsGroup::model()->findAll() as $group) {
        $items = [];
        $groupHasNotSelectedAttribute = false;
        $groupItems = (array)$group->groupAttributes;
        foreach ($groupItems as $item) {
            $selected = in_array($item->id, $selectedAttributes);
            if (!$selected) {
                $groupHasNotSelectedAttribute = true;
            }
            $items[] = ['text' => CHtml::tag('div', ['class' => 'checkbox treecheck'], CHtml::label(CHtml::checkBox('attributes[]', $selected, ['value' => $item->id]) . $item->title, null))];
        }
        $tree[] = [
            'text' => CHtml::tag(
                'div',
                ['class' => 'checkbox treecheck'],
                CHtml::label(CHtml::checkBox('', count($groupItems) && !$groupHasNotSelectedAttribute, ['class' => 'group-checkbox']) . $group->name, null)
            ),
            'children' => $items
        ];
    } 
    foreach ((array)EavOptions::model()->findAllByAttributes(array('group_id' => null),array('order'=>'title')) as $attribute) {
        $tree[] = array(
            'text' => CHtml::tag(
                'div',
                array('class' => 'checkbox treecheck'),
                CHtml::label(CHtml::checkBox('attributes[]', in_array($attribute->id, $selectedAttributes), ['value' => $attribute->id]) . $attribute->title, null)
            )
        );
    }
    ?>
    <div class="col-md-1 col-sm-2"></div>
    <div class="col-md-11 col-sm-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo 'Атрибуты'; ?>
            </div>
            <div class="panel-body">
                <?php $this->widget('CTreeView', ['data' => $tree, 'collapsed' => true]); ?>
            </div>
        </div>
    </div>
</div>    
  

        <div style="margin-bottom: 0;" class="form-group">
            <div class="col-md-offset-1 col-md-11 col-sm-offset-2 col-sm-10">
                <button class="btn btn-primary" type="submit"><?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'; ?></button>
            </div>
        </div> <!-- / .form-group -->
     <?php $this->endWidget(); ?>   
    </div>


<?php

$scriptDd = "
$(function(){

$('#switcherInherit > input').switcher();

$('#Category_parent_id').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите рубрику'
    }).on('change',function(e){
            
            if(e.val){
                $('#inherit-block').show();
                $('#switcherInherit > input').switcher('enable');
            } else {
                $('#inherit-block').hide();
                $('#switcherInherit > input').switcher('disable');
            }
    });

});
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);

?>


    

    

    

    

 




