<?php $this->pageTitle = Yii::app()->name . ' - ' . UsersModule::t("Profile");
$this->breadcrumbs = array(
    UsersModule::t("Profile") => array('profile'),
    UsersModule::t("Edit"),
);
$this->menu = array(
    ((UsersModule::isAdmin())
        ? array('label' => UsersModule::t('Manage Users'), 'url' => array('/user/admin'))
        : array()),
    array('label' => UsersModule::t('Profile'), 'url' => array('/user/profile')),
    array('label' => UsersModule::t('Change password'), 'url' => array('changepassword')),
    array('label' => UsersModule::t('Logout'), 'url' => array('/user/logout')),
);
?><h1><?php echo UsersModule::t('Edit profile'); ?></h1>

<?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
    </div>
<?php endif; ?>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'profile-form',
        'enableAjaxValidation' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

    <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? UsersModule::t('Create') : UsersModule::t('Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
