<section id="page_bg"  style="background-image: url('/img/page_bg.jpg');">
<div class="container">
<div class="row">
	<div class="col-xs-12 m-t-10">
		<a class="c-white" href="/"><div id="landing_logo">Локатор</div></a>
	</div>
    <div class="col-xs-12 text-center m-t-80">
        <h1 class="page_head_heading">О проекте</h1>
    </div>
</div>
</div>
</section>
<section id="main">
<section id="content" class="section-white">
<div class="container text-center" id="main_container">

<div class="row m-t-10 m-b-70">
<div class="col-xs-12 col-lg-8 col-lg-offset-2">
<h1 class="landing_heading">Локатор - система для размещения информации и отслеживание упоминания вашей компании в Интернете.
</h1>
<div class="page_text m-t-50">
Разработка проекта началась в 2014 году в продуктовой компании Анетика, как профессионального инструмента поискового робота для бизнеса. В 2015 году Локатор начал свою работу и стал помогать в работе малому бизнесу.
</div>
</div>
</div>
<?php $this->renderPartial('application.views.common.partners'); ?>
<div class="row m-t-60"></div>
<hr>
<div class="row m-t-70">
<div class="col-xs-12  text-center">
<h1 class="landing_heading">Принципы работы:</h1>
<div class="row m-t-20">
<div class="col-xs-12 col-sm-6 col-md-offset-2 col-md-4  text-center">
<img style="display:inline-block;max-width:80px;" class="img-responsive m-t-60" src="/img/about_1.png" />
<br>
<div style="display:inline-block;max-width:245px;" class="landing_text m-t-15">Открытость к внедрению новых 
идей и технологий</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4 text-center">
<img style="display:inline-block;max-width:80px;" class="img-responsive m-t-60" src="/img/about_2.png" />
<br>
<div style="display:inline-block;max-width:245px;" class="landing_text m-t-15">Причастность каждого участника 
команды к процессу развития</div>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-offset-2 col-md-4 text-center">
<img style="display:inline-block;max-width:80px;" class="img-responsive m-t-60" src="/img/about_3.png" />
<br>
<div style="display:inline-block;max-width:245px;" class="landing_text m-t-15">Профессионализм в решении 
поставленных задач</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4 text-center">
<img style="display:inline-block;max-width:80px;" class="img-responsive m-t-60" src="/img/about_4.png" />
<br>
<div style="display:inline-block;max-width:245px;" class="landing_text m-t-15">Ответсвенность перед 
пользователями и партнерами</div>
</div>
</div>
</div>
</div><!-- row -->
<div class="row m-t-30 hidden-xs" style="margin-bottom:110px"></div>
<div class="row m-t-70 hidden-sm hidden-md hidden-lg" style="margin-bottom:60px"></div> 
<hr>


<div class="row">
  <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 text-center" >
        <h1 class="landing_heading m-t-80">Контакты:</h1> 
        <p class="landing_text">Есть вопросы или возникли трудности? Свяжитесь с нами:</p>
        <h1><a class="big_link" href="mailto:info@inlocator.ru">info@inlocator.ru</a></h1>
  		<p class="landing_text m-t-50">Или отправьте нам сообщение:</p>
  </div>
</div>
  <div class="row m-t-25 text-left"  id="contactPlace">
  <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
  <?php
    $modelFeedback = new FormContact;
    $url1 = Yii::app()->createAbsoluteUrl('/site/feedback');
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'form-contact_about',
        'action'=>$url1,
        'htmlOptions'=>array( 'role'=>'form'),
        'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'afterValidate' => "js: function(form, data, hasError) {\n"
                                                ."    //if no error in validation, send form data with Ajax\n"
                                                 ."       if (! hasError) {\n"
                                                ."     $('#contactPlace .modal-body.for_form,#contactPlace .modal-footer').hide();\n"
                                                ."     $('#contactPlace .modal-body.success').html(data.message).show();\n"
                                                ."    }\n"
                                                ."    return false;\n"
                                                ."}\n"
                ),
      )); 
          ?>
   <div class="modal-body success" style="display:none; padding: 0 20px; text-align: center; margin:0 auto 40px auto;">
            <!--Ваше сообщение успешно отправлено.-->
            </div>
      <div class="modal-body for_form" style="padding: 0 20px">
              
        <div class="clearfix"></div>
        <div style="height:24px;width:100%"></div>

        <div class="form-group fg-line theme-locator">
          <?php echo $form->labelEx($modelFeedback,'name',array('class'=>'')); ?>
          <?php echo $form->textField($modelFeedback,'name',array('class'=>'form-control','placeholder'=>'Введите ваш текст...')); ?>
          <?php echo $form->error($modelFeedback,'name'); ?>
        </div>

        <div class="form-group fg-line theme-locator">
          <?php echo $form->labelEx($modelFeedback,'email',array('class'=>'')); ?>
          <?php echo $form->textField($modelFeedback,'email',array('class'=>'form-control', 'placeholder'=>'Введите ваш текст...')); ?>
          <?php echo $form->error($modelFeedback,'email'); ?>
        </div><?php  ?>

        <div class="form-group fg-line theme-locator">
        <?php echo $form->labelEx($modelFeedback,'content'); ?>
          <?php echo $form->textArea($modelFeedback,'content', array('style'=>'word-wrap: break-word; min-height: 100px; width: 100%; resize: none; overflow:hidden;', 'class'=>'form-control', 'placeholder'=>'Введите ваш текст...')); ?>
          <?php echo $form->error($modelFeedback,'content'); ?>
        </div>
              
                 
      </div> <!-- / .modal-body -->
      <div class="modal-footer no-border-t m-t-30" style="border-top:0; text-align: center;">
        <button type="submit" class="btn btn-primary theme-locator">Отправить</button>
        
      </div>
      <div style="width:100%;height:120px;"></div>
  <?php $this->endWidget(); ?>

  </div>
 </div>

</div>
</section>
</section>