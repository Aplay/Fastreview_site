<?php
/* @var $model Product - передается при рендере из формы редактирования товара */
/* @var $type Type - передается при генерации формы через ajax */
?>
<?php if (!empty($groups)) { ?>
    <div class="row">
        <div class="col-sm-12">
            <?php foreach ($groups as $groupName => $items) { 


                ?>
                <fieldset>
                    <legend><?php echo CHtml::encode($groupName); ?></legend>
                    <?php foreach ($items as $attribute) { 
/*
if(isset($_POST['EavOptions'][$attribute->name]))
    $value = $_POST['EavOptions'][$attribute->name];
else{
    $value = $model->getEavAttribute($attribute->name);

    VarDumper::dump($value); 
}*/

                        ?>
                        <?php /* @var $attribute Attribute */ ?>
                        <?php $hasError = $model->hasErrors('eav.' . $attribute->name); ?>
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label for="EavOptions_<?php echo $attribute->name ?>"
                                       class="<?php echo $hasError ? "has-error" : ""; ?>">
                                    <?php echo $attribute->title; ?>
                                    <?php if ($attribute->unit) { ?>
                                        <span>, <?php echo $attribute->unit; ?></span>
                                    <?php } ?>
                                    <?php if ($attribute->required) { ?>
                                        <span class="required text-danger">*</span>
                                    <?php } ?>
                                    
                                </label>
                            </div>
                            <div
                                class="col-sm-<?php echo $attribute->isType(EavOptions::TYPE_TEXT) ? 9 : 2; ?> <?php echo $hasError ? "has-error" : ""; ?>">
                                <?php $htmlOptions = $attribute->isType(EavOptions::TYPE_CHECKBOX_LIST) ? [] : ['class' => 'form-control']; ?>
                                <?php echo EavOptionsRender::renderField($attribute, 
                                $model->attribute($attribute), 
                               // $value,
                                null, $htmlOptions); ?>
                            </div>
                        </div>
                    <?php } ?>
                </fieldset>
            <?php } ?>
        </div>
    </div>
<?php } ?>
