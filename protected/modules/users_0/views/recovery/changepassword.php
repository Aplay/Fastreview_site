<?php $this->pageTitle = Yii::app()->name . ' - ' . UsersModule::t("Change Password");
$this->breadcrumbs = array(
    UsersModule::t("Login") => array('/user/login'),
    UsersModule::t("Change Password"),
);
?>

<h1><?php echo UsersModule::t("Change Password"); ?></h1>


<div class="form">
    <?php echo CHtml::beginForm(); ?>

    <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    <?php echo CHtml::errorSummary($form); ?>

    <div class="row">
        <?php echo CHtml::activeLabelEx($form, 'password'); ?>
        <?php echo CHtml::activePasswordField($form, 'password'); ?>
        <p class="hint">
            <?php echo UsersModule::t("Minimal password length 5 symbols."); ?>
        </p>
    </div>

    <div class="row">
        <?php echo CHtml::activeLabelEx($form, 'verifyPassword'); ?>
        <?php echo CHtml::activePasswordField($form, 'verifyPassword'); ?>
    </div>


    <div class="row submit">
        <?php echo CHtml::submitButton(UsersModule::t("Save")); ?>
    </div>

    <?php echo CHtml::endForm(); ?>
</div><!-- form -->