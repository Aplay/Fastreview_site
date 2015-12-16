<?php $this->pageTitle = Yii::app()->name . ' - ' . UsersModule::t("Profile");
$this->breadcrumbs = array(
    UsersModule::t("Profile"),
);
$this->menu = array(
    ((UsersModule::isAdmin())
        ? array('label' => UsersModule::t('Manage Users'), 'url' => array('/user/admin'))
        : array()),
    array('label' => UsersModule::t('Edit'), 'url' => array('edit')),
    array('label' => UsersModule::t('Change password'), 'url' => array('changepassword')),
    array('label' => UsersModule::t('Logout'), 'url' => array('/user/logout')),
);
?><h1><?php echo UsersModule::t('Your profile'); ?></h1>

<?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
    </div>
<?php endif; ?>
<table class="dataGrid">
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></th>
        <td><?php echo CHtml::encode($model->username); ?></td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></th>
        <td><?php echo CHtml::encode($model->email); ?></td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?></th>
        <td><?php echo $model->create_at; ?></td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastvisit_at')); ?></th>
        <td><?php echo $model->lastvisit_at; ?></td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></th>
        <td><?php echo CHtml::encode(User::itemAlias("UserStatus", $model->status)); ?></td>
    </tr>
</table>
