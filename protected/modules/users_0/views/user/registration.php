<?php $this->pageTitle = Yii::app()->name. ' - '. Yii::t('site', 'Sign up'); 
$this->breadcrumbs = array(
    Yii::app()->name,
);
$themeUrl = Yii::app()->theme->baseUrl;
?>
<body class="theme-frost page-signup">
<!-- Page background -->
    <div id="page-signup-bg">
        <!-- Background overlay -->
        <div class="overlay"></div>
        <!-- Replace this with your bg image -->
        <img src="<?php echo $themeUrl; ?>/img/signin-bg-1.jpg" alt="">
    </div>
    <!-- / Page background -->

    <!-- Container -->
    <div class="signup-container">
        <!-- Header -->
        <div class="signup-header">
            <a href="/" class="logo">
                <img src="<?php echo $themeUrl; ?>/img/logo-big.png" alt="" style="margin-top: -5px;">&nbsp;
                <?php echo Yii::app()->name; ?>
            </a> <!-- / .logo -->
            <div class="slogan">
                
            </div> <!-- / .slogan -->
        </div>
        <!-- / Header -->

        <!-- Form -->
        <div class="signup-form">
            <?php
               /* $form = $this->beginWidget('CActiveForm', array(
                'id' => 'signup-form_id',
                )); */
                $form = $this->beginWidget('CActiveForm', array(
                'id' => 'signup-form_id',
                'action'=>'/registration',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                ),
                ));
            ?>    
                <div class="signup-text">
                    <span>Создать аккаунт</span>
                </div>

                <div class="form-group w-icon">
                   <?php echo $form->textField($model, 'fullname', array('class' => 'form-control input-lg','placeholder'=>'Имя и Фамилия')); ?>
                    <span class="fa fa-info signup-form-icon"></span>
                </div>
                <?php echo $form->error($model,'fullname'); ?>
                <div class="form-group w-icon">
                   <?php echo $form->emailField($model, 'email', array('class' => 'form-control input-lg', 'placeholder'=>'E-mail')); ?>
                    <span class="fa fa-envelope signup-form-icon"></span>
                </div>
                <?php echo $form->error($model,'email'); ?>
                <div class="form-group w-icon">
                   <?php echo $form->textField($model, 'username', array('class' => 'form-control input-lg','placeholder'=>'Логин')); ?>
                    <span class="fa fa-user signup-form-icon"></span>
                </div>
                <?php echo $form->error($model,'username'); ?>
                <div class="form-group w-icon">
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control input-lg','placeholder'=>'Пароль')); ?>
                    <span class="fa fa-lock signup-form-icon"></span>
                </div>
                <?php echo $form->error($model,'password'); ?>
                <div class="form-group" style="margin-top: 20px;">
                    <label class="checkbox-inline">
                        <?php echo $form->checkBox($model,'signup_confirm',  array('class'=>'px')); ?>
                        <span class="lbl">Я согласен с <a href="#" target="_blank">условиями использования</a></span>
                    </label>
                </div>
                <?php echo $form->error($model,'signup_confirm'); ?>
                <div class="form-actions" style="margin-top:20px">
                    <input type="submit" value="Зарегистрироваться" class="signup-btn bg-primary">
                </div>
            <?php $this->endWidget(); ?>
            <!-- / Form -->

            <!-- / "Sign In with" block -->
        </div>
        <!-- Right side -->
    </div>

        <div class="have-account">
        Уже существует аккаунт? <a href="<?php echo $this->createUrl('/user/login'); ?>">Вход</a>
    </div>

<?php
$scriptAdd = "
 // Resize BG
    var init = [];
    


    init.push(function () {
        var ph  = $('#page-signin-bg'),
            imga = ph.find('> img');

        $(window).on('resize', function () {
            imga.attr('style', '');
            if (imga.height() < ph.height()) {
                imga.css({
                    height: '100%',
                    width: 'auto'
                });
            }
        });

    
    });

    window.PixelAdmin.start(init);
";
Yii::app()->clientScript->registerScript("pxTeam", $scriptAdd, CClientScript::POS_END);
?>
<?php
$this->renderPartial('//layouts/__counter');
?>
</body>

