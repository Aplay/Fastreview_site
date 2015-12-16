
<div id="login_modal" class="" tabindex="-1" role="dialog" >
<div class="modal-dialog">
	  <div id="l-register" class="lc-block toggled">

	  <div class="row">
	  
        <div class="col-xs-12"> 
           
        <div class="log-w modal-body h-w-g" style="min-height:270px;padding:2px;">
        
     
        <div class="pull-left text-center" style="width:50%">
        <div style="font-size:11px;font-weight:bold;padding:15px;color:#2996cc;text-transform:uppercase;">Регистрация</div>
        </div>
        <div class="pull-right text-center" onclick="$('.lc-block').removeClass('toggled');$('#l-login').addClass('toggled');return false;" style="width:50%;background-color:#5db8e7;cursor:pointer;">
        <div style="font-size:11px;font-weight:bold;padding:15px;color:#fff;text-transform:uppercase;">Вход</div>
        </div>
        <div class="clearfix"></div>
        <div style="padding:15px">
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
	           
        			),
                
                ));
            ?>  

            <div class="form-group fg-line theme-locator m-b-20 p-t-5">
	          <?php echo $form3->labelEx($modelRegister,'email',array('class'=>'')); ?>
	          <?php echo $form3->emailField($modelRegister,'email',array('class'=>'form-control','placeholder'=>'E-mail')); ?>
	          <?php echo $form3->error($modelRegister,'email'); ?>
              <?php echo $form3->error($modelRegister,'username'); ?>
              <?php echo $form3->error($modelRegister,'fullname'); ?>
	        </div>

            <div class="form-group fg-line theme-locator m-b-20 p-t-5">
	          <?php echo $form3->labelEx($modelRegister,'password',array('class'=>'')); ?>
	          <?php echo $form3->passwordField($modelRegister,'password',array('class'=>'form-control','placeholder'=>'Пароль')); ?>
	          <?php echo $form3->error($modelRegister,'password'); ?>
	        </div>
	        <div class="text-center">
            <button type="submit" class="btn btn-primary theme-locator" style="background-color:#5db8e7;padding-left:30px;padding-right:30px;" >Регистрация</button>
            </div>
            <?php $this->endWidget(); ?>
         
           </div>
           </div>

         
            </div> 
            </div>
        </div><!-- #l-register -->
        
        <div id="l-login" class="lc-block">
            <div class="log-w modal-body" style="min-height:270px;padding:2px;">
            <div class="pull-left text-center" onclick="$('.lc-block').removeClass('toggled');$('#l-register').addClass('toggled');return false;" style="width:50%;background-color:#5db8e7;cursor:pointer;">
            <div style="font-size:11px;font-weight:bold;padding:15px;color:#fff;text-transform:uppercase;">Регистрация</div>
            </div>
            <div class="pull-right text-center"  style="width:50%;">
            <div style="font-size:11px;font-weight:bold;padding:15px;color:#2996cc;text-transform:uppercase;">Вход</div>
            </div>
            <div class="clearfix"></div>
           <div style="padding:15px">
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
            <div class="form-group fg-line theme-locator m-b-20">
              <?php echo $form1->labelEx($modelLogin,'username',array('class'=>'')); ?>
              <?php echo $form1->textField($modelLogin,'username',array('class'=>'form-control','placeholder'=>'E-mail')); ?>
              <?php echo $form1->error($modelLogin,'username'); ?>
            </div>

            <div class="form-group fg-line theme-locator p-t-5 " style="margin-bottom:25px;">
              <?php echo $form1->labelEx($modelLogin,'password',array('class'=>'')); ?>
              <?php echo $form1->passwordField($modelLogin,'password',array('class'=>'form-control','placeholder'=>'Пароль')); ?>
              <?php echo $form1->error($modelLogin,'password'); ?>
            </div>
            <div class="text-center">
            <a href="#"  onclick="$('.lc-block').toggleClass('toggled');return false;"  class="hide modal_navigate c-green">Забыли пароль?</a>
            <div class="clearfix"></div>
            <button type="submit" class="btn btn-primary theme-locator" style="background-color:#5db8e7;padding-left:33px;padding-right:33px;">Войти</button>
            </div>
            </div>
            </div>
            
            <?php $this->endWidget(); ?>
            </div>
        </div>
        <!-- Forgot Password -->
        <div id="l-forget-password" class="lc-block">
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
            <button type="submit" class="btn btn-primary p-l-20 p-r-20" style="margin-top:38px;">Отправить</button>
            </div>
            
            <?php $this->endWidget(); ?>
           </div>
  
        </div>
</div>
</div>
