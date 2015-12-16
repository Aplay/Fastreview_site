<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array(
    Yii::app()->name,
);
$themeUrl = '/themes/bootstrap_311/';
?>
<body class="theme-frost page-signin">

<!-- Page background -->
    <div id="page-signin-bg">
        <!-- Background overlay -->
        <div class="overlay"></div>
        <!-- Replace this with your bg image -->
        <img src="<?php echo $themeUrl; ?>/img/signin-bg-1.jpg" alt="">
    </div>
    <!-- / Page background -->

    <!-- Container -->
    <div class="signin-container">
<!-- Left side -->
        <div class="signin-info">
            <a href="/" class="logo">
                <img src="<?php echo $themeUrl; ?>/img/logo-big.png" alt="" style="margin-top: -5px;">&nbsp;
                <?php echo Yii::app()->name; ?>
            </a> <!-- / .logo -->
            <div class="slogan">

            </div> <!-- / .slogan -->
       
        </div>
        <!-- / Left side -->
 <div class="signin-form">

            <!-- Form -->
            <?php
                $form = $this->beginWidget('CActiveForm', array(
                'id' => 'signin-form_id',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                ),
                ));
            ?>
                <div class="signin-text">
                    <span>Вход</span>
                </div> <!-- / .signin-text -->

                <div class="form-group w-icon">
                    <?php echo $form->textField($model, 'username', array('class' => 'form-control input-lg', 'placeholder'=>'Логин или email')); ?>
                    <span class="fa fa-user signin-form-icon"></span>
                </div> <!-- / Username -->
                <?php echo $form->error($model,'username'); ?>
                <div class="form-group w-icon">
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control input-lg', 'placeholder'=>'Пароль')); ?>
                    <span class="fa fa-lock signin-form-icon"></span>
                </div> <!-- / Password -->
                <?php echo $form->error($model,'password'); ?>
                <div class="form-group w-icon">
                <?php if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest){ ?>
				    <?php echo CHtml::activeLabelEx($model, 'verifyCode'); ?>
				    <?php 

				    $this->widget('CCaptcha',array(
				              'buttonLabel'=>'Новый код',
				              'id'=>'image_captcha' 
				              ));
				             ?>
				    </div>
				    <div class="form-group w-icon no-margin-t">
				    <?php echo $form->textField($model, 'verifyCode', array('class' => 'form-control input-lg', 'placeholder'=>'Введите код')); ?>
                   <span class="fa fa-key signin-form-icon"></span>
                   </div>
                   <div class="form-group w-icon no-margin-t">
				    <?php echo $form->error($model, 'verifyCode'); ?>
				    </div>
				<?php } ?>
				    
				    

				    <div class="form-actions">
                    <input type="submit" value="Вход" class="signin-btn bg-primary">
            
                </div> <!-- / .form-actions -->

            <?php $this->endWidget(); ?>
            <!-- / Form -->

           
        </div>
<!-- Pixel Admin's javascripts -->
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

 

    // Setup Sign In form validation
   /* init.push(function () {
        $('#signin-form_id').validate({ focusInvalid: true, 
           // errorPlacement: function () {} 
        });
        
        // Validate username
        $('#username_id').rules('add', {
            required: true,
            minlength: 3
        });

        // Validate password
        $('#password_id').rules('add', {
            required: true,
            minlength: 5
        });

		$('#code_id').rules('add', {
            required: true,
            minlength: 4
        });
    });*/


    window.PixelAdmin.start(init);
";
Yii::app()->clientScript->registerScript("pxTeam", $scriptAdd, CClientScript::POS_END);
?>
    </div>
    <!-- / Container -->

  
</body>


