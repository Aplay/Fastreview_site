
<div class="block-header">
<h2 id="steps_title">Добавление компании</h2>    
</div>
<div class="card">
<ul class="nav nav-tabs hide" role="tablist">
    <li role="presentation"  class="active"><a id="t1" data-steptext="Добавление компании" href="#tab-1" aria-controls="home" role="tab" data-toggle="tab"></a></li>
    <li role="presentation"><a id="t2" href="#tab-2" data-steptext="Добавление контактных данных" aria-controls="profile" role="tab" data-toggle="tab"></a></li>
    <li role="presentation" ><a id="t3" href="#tab-3" data-steptext="Выбор тарифа" aria-controls="profile" role="tab" data-toggle="tab"></a></li>
    
    </ul>
 <?php 
$js1 = <<< EOF_JS
function(){
  $('#form-addcompany-submit').prop('disabled', true);
  return true;
}
EOF_JS;
$js2 = <<< EOF_JS
function(form, data, hasError) {
      
      if(!jQuery.isEmptyObject(data)) {
       
        if('flag' in data && data.flag == false){
        $.each(data.message,function(i,v){
            if(i=='city_id'){
               $('#FormAddCompany_address_em_').html('Не определился город').show();
            }
            if(i=='categories_ar'){
                $('#FormAddCompany_cats_em_').html('Не определился вид деятельности').show();
            }
        })
       }
        if('flag' in data && data.flag == true){
          if(data.nextstep){
              $('#t'+data.nextstep).tab('show');
              $('#steps_title').text($('#t'+data.nextstep).data('steptext'));
              window.scrollTo(0, 0);
              if(data.nextstep == 2)
                yaCounter33639359.reachGoal('addorg_next');
              if(data.nextstep == 3)
                yaCounter33639359.reachGoal('addcontact_next');

          } else if(data.url){
            yaCounter33639359.reachGoal('tarif_to_pay');
            window.location = data.url
          }
    } else {
      $('.form-addcompany-submit').prop('disabled', false);
    }
      }
    
    return false;
}
EOF_JS;
   // $url1 = Yii::app()->createAbsoluteUrl('/site/new');
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form-addcompany',
      //  'action'=>$url1,
        'htmlOptions'=>array( 'role'=>'form'),
        'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'beforeValidate'=>"js:{$js1}",
                    'afterValidate' => "js:{$js2}"
                ),
      )); 
      
      ?>
<div class="card-body" style="padding:23px 0px;"> 
<div class="tab-content">
<div id="tab-1" class="tab-pane animated fadeIn  in active" role="tabpanel">

<div id="form-addcompany-success" style="display:none;"></div>

    <div style="padding:0 41px">
    <div class="jumbotron">На этом шаге вам необходимо добавить данные вашей компании. 
Если у вас несколько филиалов или несколько компаний, то вы сможете их добавить в личном кабинете далее.</div>
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
          <label class="required" for="FormAddCompany_title">Название <span class="required">*</span></label>
          <?php echo $form->textField($modelAddCompany,'title',array('class'=>'form-control','placeholder'=>'Название')); ?>
          <?php echo $form->error($modelAddCompany,'title'); ?>
    </div>
    </div> 
    </div>
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">

      <label class="required" for="FormAddCompany_address">Адрес (Город, улица, дом, корпус, офис) <span class="required">*</span></label>
      <?php echo $form->textField($modelAddCompany,'address',array('class'=>'form-control', 'placeholder'=>'Город, улица, дом, корпус, офис')); ?>
      <?php echo $form->error($modelAddCompany,'address'); ?>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
      <?php echo $form->label($modelAddCompany,'tempphones',array()); ?>
      <?php echo $form->textField($modelAddCompany,'tempphones',array('class'=>'form-control', 'placeholder'=>'Вместе с кодом страны (+7) и кодом города')); ?>
      
      <?php echo $form->error($modelAddCompany,'tempphones'); ?>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
    <label class="required" for="FormAddCompany_rubrictext">Вид деятельности <span class="required">*</span></label>
      <?php echo $form->textArea($modelAddCompany,'rubrictext', array('style'=>'word-wrap: break-word; min-height: 90px; width: 100%; resize: none; overflow:hidden;','class'=>'form-control auto-size','placeholder'=>'Через запятую перечислите виды вашей деятельности и в какие рубрики хотите попасть')); ?>
      <?php echo $form->error($modelAddCompany,'rubrictext'); ?>
    </div>
    </div>
    </div>
    </div>
    <div class="c-gray f-12" style="position:relative;margin-top:60px;margin-bottom:36px;">
    <div style="position:absolute;top:9px;z-index:1;left:41px;right:0px;border-bottom:1px solid #e0e0e0;height:1px;"></div>
    <div style="position:absolute;top:0;z-index:3;left:41px;padding-right:15px;background-color:#fff;">Необязательная информация</div>
    <br>
    <span style="padding-left:41px;display:inline-block;margin-top:5px;line-height:1.3em;">
    Чем более детальнее вы укажите информацию о своей компании, тем<br>
    больше пользователи будут доверять вам
    </span>
    </div> 
    <div style="padding:0 41px">
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
      <?php echo $form->label($modelAddCompany,'site',array()); ?>
      <?php echo $form->textField($modelAddCompany,'site',array('class'=>'form-control', 'placeholder'=>'Сайт')); ?>
      <?php echo $form->error($modelAddCompany,'site'); ?>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
      <?php echo $form->label($modelAddCompany,'description'); ?>
      <?php echo $form->textArea($modelAddCompany,'description', array('style'=>'word-wrap: break-word; min-height: 90px; width: 100%; resize: none; overflow:hidden;','class'=>'form-control auto-size','placeholder'=>'Здесь вы можете описать ваши конкурентные преимущества. Любой текст, который поможет пользователю сделать выбор в вашу пользу')); ?>
      <?php echo $form->error($modelAddCompany,'description'); ?>
    </div>
    </div>
    </div>
    <div class="row"> 
    <div class="col-xs-12">
    	<div class="form-group">
    		<?php  $this->renderPartial('_addworktime'); ?>
    	</div>
    </div>
    </div>
    <div class="row m-t-10">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator m-b-15 with-soc">
      <label for="FormAddCompany_vkontakte" style="margin-bottom:5px;">Социальные сети</label>
      <?php echo $form->textField($modelAddCompany,'vkontakte',array('class'=>'form-control', 'placeholder'=>'Vkontakte')); ?>
      <i class="socicon socicon-vkontakte c-theme"></i>
      <?php echo $form->error($modelAddCompany,'vkontakte',array('style'=>'bottom:-18px;')); ?>
    </div>
    <div class="form-group fg-line theme-locator m-b-15 with-soc">
      <?php echo $form->textField($modelAddCompany,'twitter',array('class'=>'form-control', 'placeholder'=>'Twitter')); ?>
      <i class="socicon socicon-twitter c-theme"></i>
      <?php echo $form->error($modelAddCompany,'twitter',array('style'=>'bottom:-18px;')); ?>
    </div>
    <div class="form-group fg-line theme-locator m-b-15 with-soc" id="facebook-block" style="display:none;">
      <?php echo $form->textField($modelAddCompany,'facebook',array('class'=>'form-control', 'placeholder'=>'Facebook')); ?>
       <i class="socicon socicon-facebook c-theme"></i>
      <?php echo $form->error($modelAddCompany,'facebook',array('style'=>'bottom:-18px;')); ?>
    </div>
    <div class="form-group fg-line theme-locator m-b-15 with-soc" id="instagram-block" style="display:none;">
      <?php echo $form->textField($modelAddCompany,'instagram',array('class'=>'form-control', 'placeholder'=>'Instagram')); ?>
      <i class="socicon socicon-instagram c-theme"></i>
      <?php echo $form->error($modelAddCompany,'instagram',array('style'=>'bottom:-18px;')); ?>
    </div>
    <div class="form-group fg-line theme-locator m-b-15 with-soc" id="youtube-block" style="display:none;">
      <?php echo $form->textField($modelAddCompany,'youtube',array('class'=>'form-control', 'placeholder'=>'Youtube')); ?>
      <i class="socicon socicon-youtube c-theme"></i>
      <?php echo $form->error($modelAddCompany,'youtube',array('style'=>'bottom:-18px;')); ?>
    </div>

    <div class="form-group m-b-20">
    <div class="c-theme" style="display:inline-block;font-weight:bold;margin-right:10px;cursor:pointer;" onclick="$('#facebook-block').toggle();">+Facebook</div>
    <div class="c-theme" style="display:inline-block;font-weight:bold;margin-right:10px;cursor:pointer;" onclick="$('#instagram-block').toggle();">+Instagram</div>
    <div class="c-theme" style="display:inline-block;font-weight:bold;cursor:pointer;" onclick="$('#youtube-block').toggle();">+Youtube</div>
    </div>
    </div>
    </div>
    
        <div class="clearfix"></div>
        <button type="button" 
        onclick="$('#FormAddCompany_step').attr('value', 1);$('#form-addcompany').submit();"
          class="btn btn-sm btn-primary theme-locator form-addcompany-submit" style="margin-top:20px;">Далее</button>
         </div>
         
</div> 
<div id="tab-2" class="tab-pane animated fadeIn" role="tabpanel"> 
<div style="padding:0 41px">
<div class="jumbotron">Ваши контактные данные нам нужны в случае необходимости уточнить данные вашей компании. 
Данные не передаются третьим лицам.</div>
<div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
      <label class="required" for="FormAddCompany_yourname">Ваше имя <span class="required">*</span></label>
      <?php echo $form->textField($modelAddCompany,'yourname',array('class'=>'form-control', 'placeholder'=>'Введите ваше имя')); ?>
      <?php echo $form->error($modelAddCompany,'yourname'); ?>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
      <label class="required" for="FormAddCompany_yourphone">Ваш телефон <span class="required">*</span></label>
      <?php echo $form->textField($modelAddCompany,'yourphone',array('class'=>'form-control', 'placeholder'=>'Вместе с кодом страны (+7) и кодом города')); ?>
      <?php echo $form->error($modelAddCompany,'yourphone'); ?>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
    <div class="form-group fg-line theme-locator">
      <label for="FormAddCompany_youremail">Ваш e-mail, на который будут высылаться отчеты и уведомления <span class="required">*</span></label>
      <?php 
      
      echo $form->emailField($modelAddCompany,'youremail',array('class'=>'form-control', 'placeholder'=>'Введите e-mail','disabled'=>'disabled')); ?>
      <?php echo $form->error($modelAddCompany,'youremail'); ?>
    </div>
    </div>
</div>

<div class="clearfix"></div>

<button type="button" 
onclick="$('#FormAddCompany_step').attr('value', 2);$('#form-addcompany').submit();"
   class="btn btn-sm btn-primary theme-locator form-addcompany-submit" style="margin-top:20px;">Далее</button>

</div>
</div> 

<div id="tab-3" class="tab-pane animated fadeIn  " role="tabpanel"> 
<div style="padding:0 41px">
<div class="text-center m-t-25 p-t-15 block-header-alt">
    <h1>Теперь вам осталось выбрать тариф</h1>
    <p class="c-gray m-t-20">
    <!--Ваши данные успешно добавленны в базу Локатора. -->Сразу после выбора тарифа и оплаты, Локатор начнет размещение ваших данных на доступных площадках. Также, сразу начнется мониторинг отзывов о вашей компании.
</p>
</div>


 <?php 
$plans = Plan::model()->active()->findAll(array('order'=>'position, id'));
if($plans){
  $count = count($plans); 

if($count == 6 ){
    $colclass = 'col-sm-12';
} elseif($count == 4){
    $colclass = 'col-sm-12';
} elseif($count == 3){
    $colclass = 'col-sm-12';
} elseif($count == 2){
    $colclass = 'col-sm-8 col-sm-offset-2';
}  elseif($count == 1){
    $colclass = 'col-sm-4 col-sm-offset-4';
} else {
    $colclass = 'col-sm-12';
}

  ?>
<div class="row pricing-table m-t-10">
<div class="<?php echo $colclass; ?>">
<div class="jumbotron p-t-20 p-b-20">
<div class="form-group has-success has-feedback" id="locator_promo_kod">
      <label class="control-label" for="inputSuccess2"></label>
      <div class="fg-line theme-locator">
          <?php 
          echo CHtml::activeTextField($modelAddCompany,'promo',array(
              'class'=>'form-control',
              'id'=>'locator_promo_kod_input',
              'placeholder'=>'Укажите промо код',
          ));
          ?>
      </div>
      <span class="zmdi zmdi-check form-control-feedback" style="display:none;"></span>
  </div>
</div>
</div>
</div>
<div class="row pricing-table m-t-10">
  <?php
  foreach ($plans as $key=>$plan) {
    $this->renderPartial('plan',array('data'=>$plan, 'key'=>$key, 'count'=>$count));
  }
}
?>
                       
                
                        
  </div>
<input type="hidden" id="FormAddCompany_step" name="step" value="" />
<input type="hidden" id="FormAddCompany_plan" name="FormAddCompany[plan]" value="" />
<div class="clearfix"></div>


</div>
</div>

</div>
</div> 
<?php $this->endWidget(); ?>
</div><!-- card -->
<?php 
$scriptDd = "
$(function(){
// jQuery plugin to prevent double submission of forms

$('#locator_promo_kod_input').bind('input change paste keyup mouseup',function(e){
  $.ajax({
      type:'POST',
      dataType:'json',
      url: '".CController::createUrl('/site/getpromo')."',
      data: {'kod':$('#locator_promo_kod_input').val(),
      '".Yii::app()->request->csrfTokenName."':'".Yii::app()->request->csrfToken."'},
      success: function(data,status){
      $('#locator_promo_kod .fg-line').addClass('theme-locator');
      $('#locator_promo_kod .zmdi-check').hide();
      if(!jQuery.isEmptyObject(data)) {
          if('flag' in data && data.flag == true){
             $('#locator_promo_kod .fg-line').removeClass('theme-locator');
             if(data.plans){
                $.each(data.plans,function(i,v){
                    $('#plan_'+v.id).text(v.sum_discount);
                    $('#amount_'+v.id).text(v.amount);
                })
             }
             $('#locator_promo_kod .zmdi-check').show();
          } else {
             $('.plansum').each(function(i,v){
                  $(this).text($(this).data('default'));
             })
          }
        } else {
             $('.plansum').each(function(i,v){
                  $(this).text($(this).data('default'));
             })
          }
      }
  });
});
var planSubmissionFlag = false;
planSubmission = function(plan) {
  if(!planSubmissionFlag){
    $('#FormAddCompany_step').attr('value', 3);
    $('#FormAddCompany_plan').attr('value', plan);
    $('#form-addcompany').submit();
    planSubmissionFlag = true;
  }
};

});";
Yii::app()->clientScript->registerScript("new_company", $scriptDd, CClientScript::POS_END);

?>