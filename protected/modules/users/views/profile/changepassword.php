<?php $this->pageTitle = Yii::app()->name . ' - ' . UsersModule::t("Change Password");
$this->breadcrumbs = array(
    UsersModule::t("Profile") => array('/user/profile'),
    UsersModule::t("Change Password"),
);
$this->menu = array(
    ((UsersModule::isAdmin())
        ? array('label' => UsersModule::t('Manage Users'), 'url' => array('/user/admin'))
        : array()),
    array('label' => UsersModule::t('Profile'), 'url' => array('/user/profile')),
    array('label' => UsersModule::t('Edit'), 'url' => array('edit')),
    array('label' => UsersModule::t('Logout'), 'url' => array('/user/logout')),
);
?>

<h1><?php echo UsersModule::t("Change password"); ?></h1>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'changepassword-form',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>

    <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'oldPassword'); ?>
        <?php echo $form->passwordField($model, 'oldPassword'); ?>
        <?php echo $form->error($model, 'oldPassword'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
        <p class="hint">
            <?php echo UsersModule::t("Minimal password length 4 symbols."); ?>
        </p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <?php echo $form->passwordField($model, 'verifyPassword'); ?>
        <?php echo $form->error($model, 'verifyPassword'); ?>
    </div>


    <div class="row submit">
        <?php echo CHtml::submitButton(UsersModule::t("Save")); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->