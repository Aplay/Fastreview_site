<?php
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css');
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-timepicker/js/bootstrap-timepicker_mod.min.js', CClientScript::POS_END);

$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

$this->renderPartial('application.views.common._flashMessage');

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'company-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>"no-border no-margin-b")
)); ?>
<div class="row">
<div class="col-md-6">
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">Данные</span>
	</div>
	<div class="panel-body">
	<div class="form-group">
            <?php CHtml::$afterRequiredLabel = ' <span class="text-danger">*</span>'; ?>
            <?php  echo $form->labelEx($model, 'title', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Название')); ?>
            </div>
        </div> <!-- / .form-group -->
   
        
        <div class="form-group">
            <?php  echo $form->label($model, 'rubrictext', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textArea($model, 'rubrictext', 
                array(
                    'class'=>'form-control',
                    'rows'=>5,
                    'placeholder'=>'Вид деятельности'
                )); 
                ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'description', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textArea($model, 'description', 
                array(
                    'class'=>'form-control',
                    'rows'=>5,
                    'placeholder'=>'Описание'
                )); 
                ?>

            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
                <?php echo $form->label($model, 'status_org',  array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-9 col-md-12 col-sm-12">
                <?php
                
                    echo $form->dropDownList($model, 'status_org', Orgs::getStatusNames(), array(
                            'encode'=>false,
                           // 'empty'=>'Выбрать',
                            'class'=>'form-control',
                            'disabled'=>'disabled',
                           // 'options' => array(Orgs::STATUS_ACTIVE => array('selected' => 'selected'))    
                    ));
                
                ?>
                </div>
        </div>
   
    </div>
 </div>
 <div class="panel">
    <div class="panel-heading">
        <span class="panel-title">Данные пользователя</span>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-lg-3 col-md-12 col-sm-12 control-label">Имя</label>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php 
            echo $model->authorid->fullname;
           // echo CHtml::textField('yourname', $model->authorid->fullname, array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Имя', 'disabled'=>'disabled'));
            ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <label class="col-lg-3 col-md-12 col-sm-12 control-label">Телефон</label>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php 
            echo $model->authorid->phone;
            // echo CHtml::textField('yourphone', $model->authorid->phone, array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Телефон', 'disabled'=>'disabled')); 
            ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <label class="col-lg-3 col-md-12 col-sm-12 control-label">E-mail</label>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php 
            echo $model->authorid->email;
           // echo CHtml::textField('youremail',$model->authorid->email,array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'E-mail для связи', 'disabled'=>'disabled')); ?>
            </div>
        </div> <!-- / .form-group -->
         <div class="form-group">
                <?php echo $form->label($model, 'verified',  array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-9 col-md-12 col-sm-12">
                <?php
                echo $form->dropDownList($model, 'verified',  array(0=>'Не просмотрено',1=>'Просмотрено',2=>'Изменено'), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                      //  'multiple'=>'multiple',      
                ));
                ?>
                </div>
        </div>
       
    </div> 
</div>
 </div>
<div class="col-md-6">
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">Адрес</span>
	</div>
	<div class="panel-body">
        <div class="form-group">
            <?php  echo $form->labelEx($model, 'address', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'address',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Адрес')); ?>
            </div>
        </div> <!-- / .form-group -->
        <?php
        if($phones){
                    foreach($phones as $key=>$phon){
                        if($key == 0){
                            $buttn = '<button type="button" class="btn btn-success addPhone"><span class="btn-label icon fa fa-plus-square"></span></button>';
                        } else {
                            $buttn = '<button type="button" class="btn btn-danger remPhone"><span class="btn-label icon fa fa-minus-square"></span></button>';
                        }
                        ?>
                   <div class="form-group">
                    <?php  echo $form->label($model, 'phones', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                    <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="input-group">
                        <input type="text" value="<?php echo $phon->phone; ?>"  name="Orgs[phones][]" placeholder="Телефон" maxlength="255" class="form-control">
                        <span class="input-group-btn">
                            <?php echo $buttn; ?>
                        </span>
                    </div>
                    </div>
                    </div> <!-- / .form-group -->
                    <div class="form-group">
                        <?php  echo $form->label($model, 'phone_comments', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                        <div class="col-lg-9 col-md-12 col-sm-12">
                        <input type="text" value="<?php echo $phon->description; ?>"  name="Orgs[phone_comments][]" placeholder="Комментарий к телефону" maxlength="255" class="form-control">
                        </div>
                    </div> <!-- / .form-group -->

                    <?php
                    if($key == 0){ ?>
                    <div id="customFields">
                    <?php }

                    } ?>
                    </div>
                    <?php

        } else {

        ?>
        <div class="form-group">
            <?php  echo $form->label($model, 'phones', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="input-group">
                <input type="text" value=""  name="Orgs[phones][]" placeholder="Телефон" maxlength="255" class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success addPhone"><span class="btn-label icon fa fa-plus-square"></span></button>
                </span>
            </div>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'phone_comments', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <input type="text" value=""  name="Orgs[phone_comments][]" placeholder="Комментарий к телефону" maxlength="255" class="form-control">  
            </div>
        </div> <!-- / .form-group -->
         <div id="customFields"></div>
        <?php } ?>       
    </div>
</div>


<div class="panel">
    <div class="panel-heading">
        <span class="panel-title">Доп. данные</span>
    </div>
    <div class="panel-body">
        <?php /* ?>
        <div class="form-group">

            <?php  echo $form->label($model, 'email', array('class'=>'col-lg-2 col-md-1 col-sm-2 control-label')); ?>

            <div class="col-lg-10 col-md-11 col-sm-10">
            <?php echo $form->textField($model,'email',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Email')); ?>
            </div>
        </div>
        <?php */ ?>
        <?php
        if($http){
                    foreach($http as $key=>$ht){
                        if($key == 0){
                            $buttn = '<button type="button" class="btn btn-success addHttp"><span class="btn-label icon fa fa-plus-square"></span></button>';
                        } else {
                            $buttn = '<button type="button" class="btn btn-danger remHttp"><span class="btn-label icon fa fa-minus-square"></span></button>';
                        }
                        ?>
                   <div class="form-group">
                    <?php  echo $form->label($model, 'http', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                    <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="input-group">
                        <input type="text" value="<?php echo $ht->site; ?>"  name="Orgs[http][]" placeholder="Сайт" maxlength="255" class="form-control">
                        <span class="input-group-btn">
                            <?php echo $buttn; ?>
                        </span>
                    </div>
                    </div>
                    </div> <!-- / .form-group -->
                    <div class="form-group">
                        <?php  echo $form->label($model, 'http_comments', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                        <div class="col-lg-9 col-md-12 col-sm-12">
                        <input type="text" value="<?php echo $ht->description; ?>"  name="Orgs[http_comments][]" placeholder="Комментарий к сайту" maxlength="255" class="form-control">
                        </div>
                    </div> <!-- / .form-group -->

                    <?php
                    if($key == 0){ ?>
                    <div id="customFieldsHttp">
                    <?php }

                    } ?>
                    </div>
                    <?php

        } else {

        ?>
        <div class="form-group">
            <?php  echo $form->label($model, 'http', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="input-group">
                <input type="text" value=""  name="Orgs[http][]" placeholder="Сайт" maxlength="255" class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success addHttp"><span class="btn-label icon fa fa-plus-square"></span></button>
                </span>
            </div>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'http_comments', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <input type="text" value=""  name="Orgs[http_comments][]" placeholder="Комментарий к сайту" maxlength="255" class="form-control">  
            </div>
        </div> <!-- / .form-group -->
         <div id="customFieldsHttp"></div>
        <?php } ?>

        <div class="form-group">

            <?php  echo $form->label($model, 'vkontakte', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'vkontakte',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'ВКонтакте')); ?>
            </div>
        </div> <!-- / .form-group -->
         <div class="form-group">

            <?php  echo $form->label($model, 'facebook', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'facebook',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Facebook')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'twitter', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'twitter',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Twitter')); ?>
            </div>
        </div> <!-- / .form-group -->
       <div class="form-group">

            <?php  echo $form->label($model, 'instagram', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'instagram',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Instagram')); ?>
            </div>
        </div> <!-- / .form-group -->
         <div class="form-group">

            <?php  echo $form->label($model, 'youtube', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'youtube',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Youtube')); ?>
            </div>
        </div> <!-- / .form-group -->
    </div>
 </div>

</div>
</div>


<div class="row">
<div class="col-md-12">
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">Часы работы</span>
	</div>       
	<div class="panel-body">
<div id="timework" style="display: block;"> <p>( Дни работы  / часы работы / перерыв ) <span><a href="javascript:void(0)" id="autocompleteLink">Автозаполнение</a></span></p>

<?php 
$darray = $ch_array = array();
for($i=0;$i<=6;$i++){
    $darray[$i]['od'] = '9:00';
    $darray[$i]['cd'] = '18:00';
    $darray[$i]['bd'] = null;
    $darray[$i]['ed'] = null;
    if($i==0 || $i==6){
      //  $darray[$i]['tw_day'] = 'notwork';
      //  $darray[$i]['des'] = 'disabled';
        $darray[$i]['tw_day'] = '';
        $darray[$i]['des'] = '';
    } else {
        $darray[$i]['tw_day'] = '';
        $darray[$i]['des'] = '';
    }
}

if(!empty($worktime)){
    foreach($worktime as $wt){
        if(!in_array($wt->week, $ch_array))
            $ch_array[] = $wt->week;
        if($wt->iswork == true){
            if($wt->from_work == '23:59:59'){
                $darray[$wt->week]['od'] = '24:00';
            } else {
                $darray[$wt->week]['od'] = $wt->from_work;
            }
            if($wt->to_work == '23:59:59'){
                $darray[$wt->week]['cd'] = '24:00';
            } else {
                $darray[$wt->week]['cd'] = $wt->to_work;
            }
            
        } else if($wt->iswork == false){
            if($wt->from_work == '23:59:59'){
                $darray[$wt->week]['bd'] = '24:00';
            } else {
                $darray[$wt->week]['bd'] = $wt->from_work;
            }
            if($wt->to_work == '23:59:59'){
                $darray[$wt->week]['ed'] = '24:00';
            } else {
                $darray[$wt->week]['ed'] = $wt->to_work;
            }
            
        }
    }
}

$res_array = array_diff($days = array(0,1,2,3,4,5,6), $ch_array);
//VarDumper::dump($res_array); die(); // Ctrl + X    Delete line
if(!empty($res_array)){
    foreach($res_array as $w){
        $darray[$w]['tw_day'] = 'notwork';
        $darray[$w]['des'] = 'disabled';

    }
}

?>
<ul style="list-style: outside none none;padding-left:0">
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('0','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('0','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[1]['tw_day']; ?>">Понедельник</span>&nbsp;
</div>
<div id="firstTimeString"  class="pull-left tw_time <?php echo $darray[1]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[1]['od']; ?>" name="open_door[1]"  maxlength="5" id="tp1" <?php echo $darray[1]['des']; ?>> - <input id="tp2" class="close_door" type="text" size="5" value="<?php echo $darray[1]['cd']; ?>" maxlength="5"  name="close_door[1]" <?php echo $darray[1]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[1]['bd']; ?>" maxlength="5"  name="break_door[1]" id="tp3" <?php echo $darray[1]['des']; ?>> - <input id="tp4" type="text" size="5" value="<?php echo $darray[1]['ed']; ?>"  maxlength="5"  name="endbreak_door[1]" <?php echo $darray[1]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[1]['od'] == '00:00:00' && $darray[1]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('1','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('1','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[2]['tw_day']; ?>">Вторник</span>&nbsp;
</div>
<div  class="pull-left tw_time <?php echo $darray[2]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[2]['od']; ?>" name="open_door[2]"  maxlength="5" id="tp5" <?php echo $darray[2]['des']; ?>> - <input id="tp6" type="text" class="close_door" size="5" value="<?php echo $darray[2]['cd']; ?>" maxlength="5"  name="close_door[2]" <?php echo $darray[2]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[2]['bd']; ?>" maxlength="5"  name="break_door[2]" id="tp7" <?php echo $darray[2]['des']; ?>> - <input id="tp8" type="text" size="5" value="<?php echo $darray[2]['ed']; ?>"  maxlength="5"  name="endbreak_door[2]" <?php echo $darray[2]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[2]['od'] == '00:00:00' && $darray[2]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('2','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('2','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[3]['tw_day']; ?>">Среда</span>&nbsp;
</div>
<div  class="pull-left tw_time <?php echo $darray[3]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[3]['od']; ?>" name="open_door[3]"  maxlength="5" id="tp9" <?php echo $darray[3]['des']; ?>> - <input  id="tp10" type="text" class="close_door" size="5" value="<?php echo $darray[3]['cd']; ?>" maxlength="5"  name="close_door[3]" <?php echo $darray[3]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[3]['bd']; ?>" maxlength="5"  name="break_door[3]" id="tp11" <?php echo $darray[3]['des']; ?>> - <input id="tp12" type="text" size="5" value="<?php echo $darray[3]['ed']; ?>"  maxlength="5"  name="endbreak_door[3]" <?php echo $darray[3]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[3]['od'] == '00:00:00' && $darray[3]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('3','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('3','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[4]['tw_day']; ?>">Четверг</span>&nbsp;
</div>
<div  class="pull-left tw_time <?php echo $darray[4]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[4]['od']; ?>" name="open_door[4]"  maxlength="5" id="tp13" <?php echo $darray[4]['des']; ?>> - <input id="tp14" type="text" class="close_door" size="5" value="<?php echo $darray[4]['cd']; ?>" maxlength="5"  name="close_door[4]" <?php echo $darray[4]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[4]['bd']; ?>" maxlength="5"  name="break_door[4]" id="tp15" <?php echo $darray[4]['des']; ?>> - <input id="tp16" type="text" size="5" value="<?php echo $darray[4]['ed']; ?>"  maxlength="5"  name="endbreak_door[4]" <?php echo $darray[4]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[4]['od'] == '00:00:00' && $darray[4]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('4','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('4','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[5]['tw_day']; ?>">Пятница</span>&nbsp;
</div>
<div  class="pull-left tw_time <?php echo $darray[5]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[5]['od']; ?>" name="open_door[5]"  maxlength="5" id="tp17" <?php echo $darray[5]['des']; ?>> - <input id="tp18" type="text" class="close_door" size="5" value="<?php echo $darray[5]['cd']; ?>" maxlength="5"  name="close_door[5]" <?php echo $darray[5]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[5]['bd']; ?>" maxlength="5"  name="break_door[5]" id="tp19" <?php echo $darray[5]['des']; ?>> - <input id="tp20" type="text" size="5" value="<?php echo $darray[5]['ed']; ?>"  maxlength="5"  name="endbreak_door[5]" <?php echo $darray[5]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[5]['od'] == '00:00:00' && $darray[5]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('5','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('5','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[6]['tw_day']; ?>">Суббота</span>&nbsp;
</div>
<div  class="pull-left tw_time <?php echo $darray[6]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[6]['od']; ?>" name="open_door[6]"  maxlength="5"  id="tp21" <?php echo $darray[6]['des']; ?>> - <input id="tp22" type="text" class="close_door" size="5" value="<?php echo $darray[6]['cd']; ?>" maxlength="5"  name="close_door[6]" <?php echo $darray[6]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[6]['bd']; ?>" maxlength="5"  name="break_door[6]"  id="tp23" <?php echo $darray[6]['des']; ?>> - <input id="tp24" type="text" size="5" value="<?php echo $darray[6]['ed']; ?>"  maxlength="5"  name="endbreak_door[6]" <?php echo $darray[6]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[6]['od'] == '00:00:00' && $darray[6]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="pull-left">
<button type="button" class="btn btn-sm  btn-success" onclick="activ_day('6','+')" ><span class="btn-label icon fa fa-plus-square"></span></button>&nbsp; 
<button type="button" class="btn btn-sm  btn-danger" onclick="activ_day('6','-')" ><span class="btn-label icon fa fa-minus-square"></span></button>
&nbsp;&nbsp;<span class="tw_day <?php echo $darray[0]['tw_day']; ?> ">Воскресенье</span>&nbsp;
</div>
<div  class="pull-left tw_time <?php echo $darray[0]['tw_day']; ?>"> 
<input type="text" class="open_door" size="5" value="<?php echo $darray[0]['od']; ?>" name="open_door[0]"  maxlength="5" <?php echo $darray[0]['des']; ?> id="tp25"> - <input id="tp26" type="text" class="close_door" size="5" value="<?php echo $darray[0]['cd']; ?>" maxlength="5"  name="close_door[0]" <?php echo $darray[0]['des']; ?>>   
&nbsp;&nbsp; перерыв: 
<input type="text" size="5" value="<?php echo $darray[0]['bd']; ?>" maxlength="5"  name="break_door[0]" <?php echo $darray[0]['des']; ?> id="tp27"> - <input id="tp28" type="text" size="5" value="<?php echo $darray[0]['ed']; ?>"  maxlength="5"  name="endbreak_door[0]" <?php echo $darray[0]['des']; ?>>
<span  style="margin-left: 12px;">Круглосут.<input type="checkbox" name="alldaywork" value="1" class="alldaywork" <?php if($darray[0]['od'] == '00:00:00' && $darray[0]['cd'] == '24:00') {echo 'checked="checked"';} ?> /></span>
</div>
<div class="clearfix"></div>
</li>
</ul>
	</div>
	</div>
	</div>
</div>
</div>
<div class="row">
 <div class="col-md-12">
 <div class="panel">
    <div class="panel-heading">
        <span class="panel-title">Действия локатора</span>
    </div>
    <div class="panel-body">
<?php
$this->renderPartial('application.views.company.status_list', array('dataProviderStatus'=>$dataProviderStatus));

?>
        <button style="margin-top:20px;" data-toggle="modal" data-target="#locator_action" class="btn btn-primary" type="button">Добавить</button>
           
    </div>
</div>

  
 </div>
 </div>


<div class="row">
 <div class="col-md-12">
 <div class="panel no-border">

            <div class="pull-left">
                <button class="btn btn-primary" type="submit"><?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'; ?></button>
            </div>
       
   </div>
</div>
</div>


<?php 
$this->endWidget(); 
?>
  <!-- Modal -->
<div id="locator_action" class="modal fade" tabindex="-1" role="dialog" style="display:none;">

  <div class="modal-dialog">
    <div class="modal-content">
    <?php 
$js1 = <<< EOF_JS
function(){
    $('#status_preview').html('');
    return true;
}
EOF_JS;
$url_update = Yii::app()->createAbsoluteUrl('/company/getstatuses',array('id'=>$model->id));
$js2 = <<< EOF_JS
function(form, data, hasError) {
      
    if(!jQuery.isEmptyObject(data)) {
        if('flag' in data && data.flag == true){
            if(data.preview){
                $('#status_preview').show().html(data.message);
            } else {
                $('#locator_action').modal('hide');
                $.fn.yiiListView.update("status_listview",{
                    url: "{$url_update}",
                });
            }
        } 
    }
    return false;
}
EOF_JS;
    $status_url = Yii::app()->createAbsoluteUrl('/catalog/admin/company/statuslocator',array('id'=>$model->id));
    $modelActionLocator = new OrgsStatus;
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'form-statuslocator',
        'action'=>$status_url,
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
        <div class="modal-header" style="border-bottom:0">
      <div style="width:100%;padding:10px 20px; text-align: center; font-size:22px; position: relative;">
      Добавление статуса
      <div style="position:absolute; top:0; right:0;">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px">×</button></div>
      </div>
            </div>
            <div class="modal-body success" style="display:none; padding: 0 20px; text-align: center; margin:0 auto 40px auto;">
            <!--Ваше сообщение успешно отправлено.-->
            </div>
      <div class="modal-body for_form" style="padding: 0 20px">
              
        <div class="clearfix"></div>
        <div style="height:24px;width:100%"></div>
        <?php echo $form->hiddenField($modelActionLocator, 'org_id',  array('value'=>$model->id)); ?>
         <?php echo $form->hiddenField($modelActionLocator, 'user_id',  array('value'=>$model->author)); ?>
                  
        <div class="form-group">
                <?php echo $form->label($modelActionLocator, 'status_id',  array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-9 col-md-12 col-sm-12">
                <?php
                    echo $form->dropDownList($modelActionLocator, 'status_id', Status::getStatuses(), array(
                            'encode'=>false,
                           // 'empty'=>'Выбрать',
                            'class'=>'form-control',
                           // 'options' => array(Orgs::STATUS_ACTIVE => array('selected' => 'selected'))    
                    ));
                ?>
                <?php echo $form->error($modelActionLocator,'status_id'); ?>
                </div>
        </div>
        <div class="form-group">
            <?php  echo $form->label($modelActionLocator, 'url', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($modelActionLocator,'url',array('class'=>'form-control','maxlength'=>255)); ?>
            <?php echo $form->error($modelActionLocator,'url'); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($modelActionLocator, 'doptext', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textArea($modelActionLocator, 'doptext', 
                array(
                    'class'=>'form-control',
                    'rows'=>3,
                )); 
                ?>
                <?php echo $form->error($modelActionLocator,'doptext'); ?>
            </div>
        </div> <!-- / .form-group -->

      </div> <!-- / .modal-body -->
    <div class="modal-footer no-border-t m-t-20" style="border-top:0; text-align: center;">
    <div class="form-group">
    <div class="col-xs-12">
    <div id="status_preview" style="text-align:left;display:none;min-height: 100px; margin-bottom:20px;border: 3px dashed #ddd;
    border-radius: 3px;
    padding: 15px;">
              
     
                   
        </div>
        <button type="submit" name="preview" value="1" class="btn btn-default">Просмотр</button>
        <button type="submit" class="btn btn-primary">Создать</button>
    </div>
    </div>
      </div>
      <div style="width:100%;height:20px;"></div>
             <?php $this->endWidget(); ?>
    </div> <!-- / .modal-content -->
  </div> <!-- / .modal-dialog -->
  
</div> <!-- /.modal -->
<?php

$uploadLink = Yii::app()->createUrl('file/file/upload');
$unlinkLink = Yii::app()->createUrl('file/file/unlink');
$deleteLink = Yii::app()->createUrl('catalog/admin/company/deletefile');
$uploadLinkLogo = Yii::app()->createUrl('catalog/admin/company/uploadlogo');
$unlinkLinkLogo = Yii::app()->createUrl('catalog/admin/company/unlinklogo');
$deleteLogo = Yii::app()->createUrl('catalog/admin/company/deletelogofile');
$scriptDd = "
$(function(){
  

// select2 bug on ipad opens keyboard  
// Hide focusser and search when not needed so virtual keyboard is not shown
$('.select2-container').each(function () {
    $(this).find('.select2-focusser').hide();
    $(this).find('.select2-drop').not('.select2-with-searchbox').find('.select2-search').hide();
});

$('.tw_time input[type=text]').timepicker({
	showMeridian:false,
    twoDigitsHour: false,
    defaultTime: false
});
// stop submit clicking enter on timepicker
$('.tw_time input[type=text]').keypress(function(event) { return event.keyCode != 13; });

$('.tw_time input[type=text]').on('focus', function (event) {
    $('.tw_time input[type=text]').timepicker('hideWidget');
    $(this).timepicker('showWidget');
});
$('.tw_time input[type=text]').timepicker().on('changeTime.timepicker', function(e) {
   // console.log('The time is ' + e.time.value);
    var timevalue = e.time.value;
    var next_close_door_value, prev_open_door_value;
    if($(this).attr('class') == 'open_door'){
        next_close_door_value = $(this).next('input.close_door').val();
        if(timevalue == '0:00' && next_close_door_value == '24:00'){
            $(this).parent('.tw_time').children('span').children('.alldaywork').prop('checked',true);
        } else {
            $(this).parent('.tw_time').children('span').children('.alldaywork').prop('checked',false);
        }
    } else if ($(this).attr('class') == 'close_door') {
        prev_open_door_value = $(this).prev('input.open_door').val();
        if(timevalue == '24:00' && prev_open_door_value == '0:00'){
            $(this).parent('.tw_time').children('span').children('.alldaywork').prop('checked',true);
        } else {
            $(this).parent('.tw_time').children('span').children('.alldaywork').prop('checked',false);
        }
    }
  });
$('.addPhone').click(function(){
    $('#customFields').append('<div class=\"form-group added\">' +
            '<label class=\"col-lg-3 col-md-12 col-sm-12 control-label\">Телефон</label>' +
            '<div class=\"col-lg-9 col-md-12 col-sm-12\">' +
            '<div class=\"input-group\">' +
            '<input type=\"text\" value=\"\"  name=\"Orgs[phones][]\" placeholder=\"Телефон\" maxlength=\"255\" class=\"form-control\">' +
                '<span class=\"input-group-btn\">' +
                    '<button type=\"button\" class=\"btn btn-danger remPhone\"><span class=\"btn-label icon fa fa-minus-square\"></span></button>' +
                '</span>' +
            '</div>' +
            '</div>' +
        '</div>' +
        '<div class=\"form-group added\">' +
            '<label  class=\"col-lg-3 col-md-12 col-sm-12 control-label\">Комментарий</label>' +
            '<div class=\"col-lg-9 col-md-12 col-sm-12\">' +
            '<input type=\"text\" value=\"\"  name=\"Orgs[phone_comments][]\" placeholder=\"Комментарий к телефону\" maxlength=\"255\" class=\"form-control\">' +
            '</div>' +
        '</div>');
});
$('#customFields').on('click', '.remPhone', function(){
    $(this).parent().parent().parent().parent().next().remove();
    $(this).parent().parent().parent().parent().remove();
});
$('.addHttp').click(function(){
    $('#customFieldsHttp').append('<div class=\"form-group added\">' +
            '<label class=\"col-lg-3 col-md-12 col-sm-12 control-label\">Сайт</label>' +
            '<div class=\"col-lg-9 col-md-12 col-sm-12\">' +
            '<div class=\"input-group\">' +
            '<input type=\"text\" value=\"\"  name=\"Orgs[http][]\" placeholder=\"Сайт\" maxlength=\"255\" class=\"form-control\">' +
                '<span class=\"input-group-btn\">' +
                    '<button type=\"button\" class=\"btn btn-danger remHttp\"><span class=\"btn-label icon fa fa-minus-square\"></span></button>' +
                '</span>' +
            '</div>' +
            '</div>' +
        '</div>' +
        '<div class=\"form-group added\">' +
            '<label  class=\"col-lg-3 col-md-12 col-sm-12 control-label\">Комментарий</label>' +
            '<div class=\"col-lg-9 col-md-12 col-sm-12\">' +
            '<input type=\"text\" value=\"\"  name=\"Orgs[http_comments][]\" placeholder=\"Комментарий к сайту\" maxlength=\"255\" class=\"form-control\">' +
            '</div>' +
        '</div>');
});

$('#customFieldsHttp').on('click', '.remHttp', function(){
    $(this).parent().parent().parent().parent().next().remove();
    $(this).parent().parent().parent().parent().remove();
});

});
function set_time_break(num)
 {
 	$('[name=\"break_door[]\"]:eq('+num+')').val(' --:--');
	$('[name=\"endbreak_door[]\"]:eq('+num+')').val(' --:--');
 }
function activ_day(nc,cm){
  if(cm=='-'){ 
  	$('#timework li:eq('+nc+') span.tw_day').attr('class','tw_day notwork'); 
  	$('#timework li:eq('+nc+') .tw_time').attr('class','pull-left tw_time notwork');
  	$('#timework li:eq('+nc+') .tw_time input').attr('disabled', true);
  }
  if(cm=='+'){ 
  	$('#timework li:eq('+nc+') span.tw_day').attr('class','tw_day'); 
  	$('#timework li:eq('+nc+') .tw_time').attr('class','pull-left tw_time');
  	$('#timework li:eq('+nc+') .tw_time input').attr('disabled', false);
  }
}
$('#autocompleteLink').on('click',function(){
    var open_door = $('#tp1').val();
    var close_door = $('#tp2').val();
    var break_door = $('#tp3').val();
    var endbreak_door = $('#tp4').val();
    if($('#firstTimeString').hasClass('notwork')){
        $('.tw_time input').attr('disabled', true);
        $('#tp5,#tp9,#tp13,#tp17,#tp21,#tp25').val(open_door);
        $('#tp6,#tp10,#tp14,#tp18,#tp22,#tp26').val(close_door);
        $('#tp7,#tp11,#tp15,#tp19,#tp23,#tp27').val(break_door);
        $('#tp8,#tp12,#tp16,#tp20,#tp24,#tp28').val(endbreak_door);
        $('.tw_time, .tw_day').addClass('notwork');
        var checkvalue = $('#firstTimeString .alldaywork').is(':checked');
        if(checkvalue == 1){
            $('.tw_time .alldaywork').prop('checked', true);
        } else {
            $('.tw_time .alldaywork').prop('checked', false);
        }
    } else {
        $('.tw_time input').attr('disabled', false);
        $('#tp5,#tp9,#tp13,#tp17,#tp21,#tp25').val(open_door);
        $('#tp6,#tp10,#tp14,#tp18,#tp22,#tp26').val(close_door);
        $('#tp7,#tp11,#tp15,#tp19,#tp23,#tp27').val(break_door);
        $('#tp8,#tp12,#tp16,#tp20,#tp24,#tp28').val(endbreak_door);
        $('.tw_time, .tw_day').removeClass('notwork');
        var checkvalue = $('#firstTimeString .alldaywork').is(':checked');
        if(checkvalue == 1){
            $('.tw_time .alldaywork').prop('checked', true);
        } else {
            $('.tw_time .alldaywork').prop('checked', false);
        }
    }
    
    
    return false;
});
$('.alldaywork').on('click',function(){
    var checkvalue = $(this).is(':checked');
    if(checkvalue == 1){
        $(this).parent().parent('.tw_time').children('input[type=text]:eq(0)').val('0:00');
        $(this).parent().parent('.tw_time').children('input[type=text]:eq(1)').val('24:00');
    } else {
        $(this).parent().parent('.tw_time').children('input[type=text]:eq(0)').val('9:00');
        $(this).parent().parent('.tw_time').children('input[type=text]:eq(1)').val('18:00');
    }
});
";
Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>
