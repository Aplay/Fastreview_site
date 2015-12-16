<?php $this->pageTitle = 'Восстановление пароля | '.Yii::app()->name; ?>

    <h1><?php echo UsersModule::t("Restore"); ?></h1>

<?php if (Yii::app()->user->hasFlash('recoveryMessage')){?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
    </div>
<?php } else { 

$this->redirect('/login');

	?>

    <div class="form">
        <?php /** @var BootActiveForm $form */
        $form2 = $this->beginWidget('CActiveForm', array(
            'id' => 'recovery-form',
        ));
        ?>

        <?php echo $form2->errorSummary($form); ?>

        <?php echo $form2->textField($form, 'login_or_email', array('hint' => UsersModule::t("Please enter your login or email addres."))); ?>

        <div class="control-group">
            <div class="controls">
                <?php echo CHtml::submitButton(UsersModule::t("Restore"), array('class' => 'btn')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php } ?>


