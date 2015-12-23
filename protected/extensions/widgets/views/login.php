<!-- Modal -->
<div id="login_modal" class="modal modal-styled fade" tabindex="-1" role="dialog" style="display:none;">
<div class="modal-dialog">
	  <div id="l-login" class="lc-block toggled">
	  <div class="modal-header bg-blue" style="padding:13px 20px 13px 40px;">
        	<i class="close zmdi zmdi-close-circle c-white" style="margin:7px 0;"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></i>
        	<h4 class="modal-title light-head-title c-white">Вход или регистрация</h4>
       </div>
	  <div class="row">
	  <div class="col-sm-6 col-xs-12 p-r-0">
	  
	  <div class="log-w modal-body h-w-g" style="border-right:1px solid #e0e0e0;min-height:430px;">
	  <div style="font-size:17px;margin-bottom:25px;">Быстрый вход</div>
<?php
                $form1 = $this->beginWidget('CActiveForm', array(
                'id' => 'form-login',
                'action'=>'/login',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
	            'validateOnSubmit'=>true, 
	            'validateOnChange' => false,  
	                                       

        			),
              //  'focus'=>array($model,'username'),
                ));
            ?>
            <div class="form-group fg-line green m-b-20">
	          <?php echo $form1->labelEx($modelLogin,'username',array('class'=>'')); ?>
	          <?php echo $form1->textField($modelLogin,'username',array('class'=>'form-control','placeholder'=>'Логин или E-mail')); ?>
	          <?php echo $form1->error($modelLogin,'username'); ?>
	        </div>

            <div class="form-group fg-line green p-t-5 " style="margin-bottom:25px;">
	          <?php echo $form1->labelEx($modelLogin,'password',array('class'=>'')); ?>
	          <?php echo $form1->passwordField($modelLogin,'password',array('class'=>'form-control','placeholder'=>'Пароль')); ?>
	          <?php echo $form1->error($modelLogin,'password'); ?>
	        </div>
	        <div class="text-center">
            <a href="#" style="text-decoration:underline;" onclick="$('.lc-block').toggleClass('toggled');return false;"  class="modal_navigate c-blue">Забыли пароль?</a>
            <div class="clearfix"></div>
            <button type="submit" class="btn btn-default-over" style="margin-top:26px;margin-bottom:33px;padding-left:33px;padding-right:33px;">Войти</button>
            </div>
            
            
            <?php $this->endWidget(); ?>
            <div class="go-social hide">
            <div class="card-body p-b-0 text-center">
	            <a class="gos facebook btn btn-icon" href="<?php echo Yii::app()->fbApi->getAuthUrl($return_url); ?>" >
	            <i class="zmdi zmdi-facebook"></i></a>
	            <a class="gos vkontakte btn btn-icon" href="<?php echo Yii::app()->vkApi->getAuthUrl($return_url); ?>" >
	            <i class="zmdi zmdi-vk"></i></a>
	            <a class="gos twitter btn btn-icon" href="<?php echo Yii::app()->twApi->getAuthUrl($return_url); ?>" >
                <i class="zmdi zmdi-twitter"></i>        
	            </a>
	            <!--
	            <a href="" class="col-xs-3">
	                <img alt="" class="img-responsive" src="/material/img/social/googleplus-128.png">
	            </a>-->
	        </div>
	        </div>
        </div>
  
         </div>
        <div class="col-sm-6 col-xs-12 p-l-0"> 
           
        <div class="log-w modal-body h-w-g" style="min-height:430px;">
        <div style="font-size:17px;margin-bottom:25px;">Регистрация</div>
         <?php
                $form3 = $this->beginWidget('CActiveForm', array(
                'id' => 'form-register',
                'action'=>'/login',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
	            'validateOnSubmit'=>true, 
	            'validateOnChange' => false,  

	            'afterValidate' => "js: function(form, data, hasError) {\n"
                                ."    //if no error in validation, send form data with Ajax\n"
								."		if (!hasError) {\n"
								."       location.reload();\n"
								."		}\n"
                                ."    return false;\n"
                                ."}\n"
        			),
                
                ));
            ?>  

            <div class="form-group fg-line green m-b-20">
	          <?php echo $form3->labelEx($modelRegister,'username',array('class'=>'')); ?>
	          <?php echo $form3->textField($modelRegister,'username',array('class'=>'form-control','placeholder'=>'Логин')); ?>
	          <?php echo $form3->error($modelRegister,'username'); ?>
	        </div>

            <div class="form-group fg-line green m-b-20 p-t-5">
	          <?php echo $form3->labelEx($modelRegister,'email',array('class'=>'')); ?>
	          <?php echo $form3->emailField($modelRegister,'email',array('class'=>'form-control','placeholder'=>'E-mail')); ?>
	          <?php echo $form3->error($modelRegister,'email'); ?>
	        </div>

            <div class="form-group fg-line green m-b-20 p-t-5">
	          <?php echo $form3->labelEx($modelRegister,'password',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($modelRegister,'password',array('class'=>'form-control','placeholder'=>'Пароль')); ?>
	          <?php echo $form3->error($modelRegister,'password'); ?>
	        </div>
	        <div class="text-center">
            <button type="submit" class="btn btn-default-over" style="margin-top:20px;padding-left:30px;padding-right:30px;" >Регистрация</button>
            </div>
            <?php $this->endWidget(); ?>
         
           </div>

         
            </div> 
            </div>
            <div class="c-gray f-12" style="padding:10px 40px ;width:100%;min-height:40px;text-align:center;border-top:1px solid #e0e0e0;">
            Вход или регистрация означает согласие с <a class="c-blue" href="/legal">Пользовательским соглашением</a>
            </div>
        </div><!-- #l-login -->
        
        <!-- Forgot Password -->
        <div id="l-forget-password" class="lc-block">
        <div class="modal-header bg-blue" style="padding:13px 20px 13px 70px;position:relative;">
        	<i id="login_modal_back" style="float:left;margin:0;" onclick="$('.lc-block').toggleClass('toggled');return false;" class="zmdi zmdi-long-arrow-left c-white modal_navigate" ></i>
        	<i class="close zmdi zmdi-close-circle c-white" style="margin:7px 0;"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></i>
        	<h4 class="modal-title light-head-title c-white text-center">Забыли пароль?</h4>
       </div>

        <div class="log-w modal-body" style="min-height:306px;">
        <?php /** @var BootActiveForm $form */
                  /*  $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'recovery-form',
                        'action'=>'/user/recovery'
                    )); */
                $form2 = $this->beginWidget('CActiveForm', array(
                    'id' => 'form-reminder',
                    'action'=>'/recovery',
                    'enableAjaxValidation'=>true,
                    'enableClientValidation'=>false,
                    'errorMessageCssClass'=>'in-bl-error',
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true, 
                        'validateOnChange' => false,
                        'afterValidate' => "js: function(form, data, hasError) {\n"
                                                ."    //if no error in validation, send form data with Ajax\n"
                                                ."    if (! hasError) {\n"
                                                ."      $('#recovery-form').hide();\n"
                                                ."      $('#message-recovery').show();\n"
                                                ."    }\n"
                                                ."    return false;\n"
                                                ."}\n"
                    ),
                   
                ));
                    ?>
            <p class="text-left" id="message-recovery" style="display:none">Информация о смене пароля поступит на ваш Email</p>
            <div class="text-center">
            <label for="UserRecoveryForm_login_or_email" style="margin-top:34px;margin-bottom:27px;">Электронная почта, указанная при регистрации:</label>
            <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
            <div class="form-group fg-line green m-b-20 text-center">
	         <?php echo $form2->textField($modelRecovery,'login_or_email',array('class'=>'form-control','placeholder'=>'sample@gmail.com')); ?>
	          <?php echo $form2->error($modelRecovery,'login_or_email'); ?>
	        </div>
	        </div>
	        </div>
            <button type="submit" class="btn btn-default-over p-l-20 p-r-20" style="margin-top:38px;">Отправить</button>
            </div>
            
            <?php $this->endWidget(); ?>
           </div>
            <div class="c-gray f-12" style="padding:10px 40px;width:100%;min-height:40px;text-align:center;border-top:1px solid #e0e0e0;">
            Вход или регистрация означает согласие с <a class="c-blue" href="/legal">Пользовательским соглашением</a>
            </div>
        </div>
</div>
</div>
