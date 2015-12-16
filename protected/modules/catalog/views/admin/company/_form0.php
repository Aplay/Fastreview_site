<?php
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css');
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-timepicker/js/bootstrap-timepicker_mod.min.js', CClientScript::POS_END);

$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
$this->renderPartial('application.views.common._flashMessage');

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'company-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>"no-border no-margin-b", 'enctype' => 'multipart/form-data')
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
            <?php  echo $form->labelEx($model, 'synonim', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'synonim',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Синоним')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'url', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'url',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'На латинице')); ?>
            </div>
        </div> <!-- / .form-group -->
   
        <div class="form-group">
                <?php echo $form->label($model, 'rubric_title',  array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-9 col-md-12 col-sm-12">
                <?php
                echo CHtml::dropDownList('Orgs[categories_ar][]', $categories_ar, Category::TreeArrayActive(), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                        'multiple'=>'multiple',      
                ));
                ?>
                </div>
        </div>
         
       
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
                        'options' => array(Orgs::STATUS_ACTIVE => array('selected' => 'selected'))    
                ));
                ?>
                </div>
        </div>
   
 		<div class="form-group widget-comments">
        <?php echo $form->label($model, 'logotip', array('class' => 'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
        <div class="col-lg-9 col-md-12 col-sm-12"  style="padding-top:7px">
                    <div id="previewDz_logo">
                    </div>
                    <button class="btn btn-primary btn-outline" type="button" id="dropzone_opener_logo"><?php echo Yii::t('site', 'Select file from disk'); ?></button>
                    <div id="dropzone_logo" class="dropzone-box" style="display:none;min-height: 200px; margin-top: 10px;">
                        <div class="dz-default dz-message">
                            <i class="fa fa-cloud-upload"></i>
                            Переместите файл сюда<br><span class="dz-text-small">или нажмите, чтобы выбрать вручную</span>
                        </div>
                            <div class="fallback">
                                <input name="logotip" type="file" multiple="" />
                            </div>
                    </div>
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
                <?php echo $form->label($model, 'city_id',  array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-9 col-md-12 col-sm-12">
                <?php
                echo $form->dropDownList($model, 'city_id', City::getBigCities(), array(
                        'encode'=>false,
                        'empty'=>'Выбрать',
                        'class'=>'form-control'
                ));

                ?>
                </div>
        </div>
        <div class="form-group">

            <?php  echo $form->label($model, 'street', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'street',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Улица')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'dom', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'dom',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Дом')); ?>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">

            <?php  echo $form->label($model, 'address_comment', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'address_comment',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Комментарий к адресу')); ?>
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

        <div class="form-group">

            <?php  echo $form->label($model, 'fax', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>

            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'fax',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'Факс')); ?>
            </div>
        </div> <!-- / .form-group -->
       
    </div>
</div>

<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">Данные пользователя</span>
	</div>
	<div class="panel-body">
		<?php 
        if($model->verified == false || !empty($model->rubrictext)){ ?>
        <div class="form-group">
            <?php  echo $form->label($model, 'youremail', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <?php echo $form->textField($model,'youremail',array('class'=>'form-control','maxlength'=>255, 'placeholder'=>'E-mail для связи')); ?>
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
                <?php echo $form->label($model, 'verified',  array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                <div class="col-lg-9 col-md-12 col-sm-12">
                <?php
                $verified = $model->verified?1:0;
                echo CHtml::dropDownList('Orgs[verified]', $verified, array(0=>'Не проверено',1=>'Проверено'), array(
                        'encode'=>false,
                       // 'empty'=>'Выбрать',
                        'class'=>'form-control',
                      //  'multiple'=>'multiple',      
                ));
                ?>
                </div>
        </div>
        <?php } ?>
	</div> 
</div>

</div>
</div>
<div class="row">
 <div class="col-md-6">
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
         <?php
        if($video){
                    foreach($video as $key=>$ht){
                        if($key == 0){
                            $buttn = '<button type="button" class="btn btn-success addVideo"><span class="btn-label icon fa fa-plus-square"></span></button>';
                        } else {
                            $buttn = '<button type="button" class="btn btn-danger remVideo"><span class="btn-label icon fa fa-minus-square"></span></button>';
                        }
                        ?>
                   <div class="form-group">
                    <?php  echo $form->label($model, 'video', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                    <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="input-group">
                        <input type="text" value="<?php echo $ht->site; ?>"  name="Orgs[video][]" placeholder="Видео" maxlength="255" class="form-control">
                        <span class="input-group-btn">
                            <?php echo $buttn; ?>
                        </span>
                    </div>
                    </div>
                    </div> <!-- / .form-group -->
                    <div class="form-group">
                        <?php  echo $form->label($model, 'video_comments', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
                        <div class="col-lg-9 col-md-12 col-sm-12">
                        <input type="text" value="<?php echo $ht->description; ?>"  name="Orgs[video_comments][]" placeholder="Название видео" maxlength="255" class="form-control">
                        </div>
                    </div> <!-- / .form-group -->

                    <?php
                    if($key == 0){ ?>
                    <div id="customFieldsVideo">
                    <?php }

                    } ?>
                    </div>
                    <?php

        } else {

        ?>
        <div class="form-group">
            <?php  echo $form->label($model, 'video', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="input-group">
                <input type="text" value=""  name="Orgs[video][]" placeholder="Видео" maxlength="255" class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success addVideo"><span class="btn-label icon fa fa-plus-square"></span></button>
                </span>
            </div>
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <?php  echo $form->label($model, 'video_comments', array('class'=>'col-lg-3 col-md-12 col-sm-12 control-label')); ?>
            <div class="col-lg-9 col-md-12 col-sm-12">
            <input type="text" value=""  name="Orgs[video_comments][]" placeholder="Название видео" maxlength="255" class="form-control">  
            </div>
        </div> <!-- / .form-group -->
         <div id="customFieldsVideo"></div>
        <?php } ?>
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
		<span class="panel-title">Фотографии</span>
	</div>       
	<div class="panel-body">

        <div class="form-group widget-comments">
        <?php echo $form->label($model, 'tmpFiles', array('class' => 'col-lg-2 col-md-2 col-sm-3 control-label')); ?>
        <div class="col-lg-10 col-md-10 col-sm-9"  style="padding-top:7px">
                    <div id="previewDz">
        
                    </div>
                    <button class="btn btn-primary btn-outline" type="button" id="dropzone_opener"><?php echo Yii::t('site', 'Select file from disk'); ?></button>
                    <div id="dropzone" class="dropzone-box" style="display:none;min-height: 200px; margin-top: 10px;">
                        <div class="dz-default dz-message">
                            <i class="fa fa-cloud-upload"></i>
                            Переместите файл сюда<br><span class="dz-text-small">или нажмите, чтобы выбрать вручную</span>
                        </div>
                            <div class="fallback">
                                <input name="tmpFiles" type="file" multiple="" />
                            </div>
                    </div>
                </div>
        </div>
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

$uploadLink = Yii::app()->createUrl('file/file/upload');
$unlinkLink = Yii::app()->createUrl('file/file/unlink');
$deleteLink = Yii::app()->createUrl('catalog/admin/company/deletefile');
$uploadLinkLogo = Yii::app()->createUrl('catalog/admin/company/uploadlogo');
$unlinkLinkLogo = Yii::app()->createUrl('catalog/admin/company/unlinklogo');
$deleteLogo = Yii::app()->createUrl('catalog/admin/company/deletelogofile');
$scriptDd = "
$(function(){
  
$('#Orgs_city_id').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите город'
}); 
$('#Orgs_categories_ar').select2({
       // minimumResultsForSearch: -1,
        allowClear: true,
        placeholder: 'Выберите теги'
});


// select2 bug on ipad opens keyboard  
// Hide focusser and search when not needed so virtual keyboard is not shown
$('.select2-container').each(function () {
    $(this).find('.select2-focusser').hide();
    $(this).find('.select2-drop').not('.select2-with-searchbox').find('.select2-search').hide();
});
var dropzone = new Dropzone('#dropzone', {
        url: '".$uploadLink."',
        paramName: 'tmpFiles', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        parallelUploads: 1,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#previewDz',
        addRemoveLinks: true,
        dictRemoveFile:'',
       /* accept: function(file, done) {
            console.log(file);
            if (file.type != 'image/jpeg' || file.type != 'image/png') {
                done('Error! Files of this type are not accepted');
            }
            else { done(); }
        }, */
        acceptedFiles: 'image/*',
        init: function() {
              var thisDropzone = this;
                $.getJSON('".Yii::app()->createAbsoluteUrl('file/file/companyFiles', array('id'=>$model->id))."', function(data) { // get the json response
                    $.each(data, function(key,value){ //loop through it
                        var mockFile = { id: value.id, name: value.name, size: value.size }; // here we get the file name and size as response 
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/file/file/company/'+value.id);
                    });
                });
        },
        removedfile: function(file) {
            
            var name = file.name, removedlink;  
            if(file.id){
                removedlink = '".$deleteLink."/?id='+ file.id;
            }  else {
                removedlink = '".$unlinkLink."';
            }         
            $.ajax({
                type: 'POST',
                url: removedlink,
                data: {
                    'name':name,
                    '".$csrfTokenName."': '".$csrfToken."'

                },
                dataType: 'html'
            });
        $('input[value=\"'+ name +'\"]').remove();
        var _ref;
        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
        },
        dictResponseError: 'Can\'t upload file!',
        autoProcessQueue: true,
        thumbnailWidth: 138,
        thumbnailHeight: 120,

        previewTemplate: '<div class=\"dz-preview dz-file-preview\"><div class=\"dz-details\">' +
        '<div class=\"dz-thumbnail-wrapper\">' +
        '<div class=\"dz-thumbnail\">' +
        '<img data-dz-thumbnail><span class=\"dz-nopreview\">No preview</span>' +
        '<div class=\"dz-error-mark\"><i class=\"fa fa-times-circle-o\"></i></div>' +
        '<div class=\"dz-error-message\"><span data-dz-errormessage></span></div></div></div></div>' +
        '</div>',

        resize: function(file) {
            var info = { srcX: 0, srcY: 0, srcWidth: file.width, srcHeight: file.height },
                srcRatio = file.width / file.height;
            if (file.height > this.options.thumbnailHeight || file.width > this.options.thumbnailWidth) {
                info.trgHeight = this.options.thumbnailHeight;
                info.trgWidth = info.trgHeight * srcRatio;
                if (info.trgWidth > this.options.thumbnailWidth) {
                    info.trgWidth = this.options.thumbnailWidth;
                    info.trgHeight = info.trgWidth / srcRatio;
                }
            } else {
                info.trgHeight = file.height;
                info.trgWidth = file.width;
            }
            return info;
        }
    }).on('addedfile', function(file) {
                
    }).on('success', function(file, serverResponse){

                var id = $(this.element);
              //  console.log(id);
                id.find('.progress').remove();
                var response = $.parseJSON(serverResponse);
                if (response && response.success == true && response.fileName){
                    $('#company-form').append('<input type=\"hidden\" name=\"Orgs[tmpFiles][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
                   // saveTheNote(1000);
                }
                
    });

 var dropzoneLogo = new Dropzone('#dropzone_logo', {
        url: '".$uploadLinkLogo."',
        paramName: 'logotip', // The name that will be used to transfer the file
        maxFilesize: ".$sizeLimit.", // MB
        parallelUploads: 1,
        params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer:'#previewDz_logo',
        addRemoveLinks: true,
        dictRemoveFile:'',
       /* accept: function(file, done) {
            console.log(file);
            if (file.type != 'image/jpeg' || file.type != 'image/png') {
                done('Error! Files of this type are not accepted');
            }
            else { done(); }
        }, */
        acceptedFiles: 'image/*',
        init: function() {
              var thisDropzone = this;
                $.getJSON('".Yii::app()->createAbsoluteUrl('file/file/logotipFile', array('id'=>$model->id,'model'=>'Orgs','filename'=>'logotip','realname'=>'logotip_realname'))."', function(data) { // get the json response
                    $.each(data, function(key,value){ //loop through it
                        var mockFile = { id: value.id, name: value.name, size: value.size }; // here we get the file name and size as response 
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/file/file/logotip/'+value.id+'?model=Orgs&filename=logotip&realname=logotip_realname');
                    });
                });
        },
        removedfile: function(file) {
            
            var name = file.name, removedlink;  
            if(file.id){
                removedlink = '".$deleteLogo."/?id='+ file.id;
            }  else {
                removedlink = '".$unlinkLinkLogo."';
            }   
            $.ajax({
                type: 'POST',
                url: removedlink,
                data: {
                    'name':name,
                    '".$csrfTokenName."': '".$csrfToken."'

                },
                dataType: 'html'
            });
        $('input[value=\"'+ name +'\"]').remove();
        var _ref;
        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
        },
        dictResponseError: 'Can\'t upload file!',
        autoProcessQueue: true,
        thumbnailWidth: 138,
        thumbnailHeight: 120,

        previewTemplate: '<div class=\"dz-preview dz-file-preview\"><div class=\"dz-details\">' +
        '<div class=\"dz-thumbnail-wrapper\">' +
        '<div class=\"dz-thumbnail\">' +
        '<img data-dz-thumbnail><span class=\"dz-nopreview\">No preview</span>' +
        '<div class=\"dz-error-mark\"><i class=\"fa fa-times-circle-o\"></i></div>' +
        '<div class=\"dz-error-message\"><span data-dz-errormessage></span></div></div></div></div>' +
        '</div>',

        resize: function(file) {
            var info = { srcX: 0, srcY: 0, srcWidth: file.width, srcHeight: file.height },
                srcRatio = file.width / file.height;
            if (file.height > this.options.thumbnailHeight || file.width > this.options.thumbnailWidth) {
                info.trgHeight = this.options.thumbnailHeight;
                info.trgWidth = info.trgHeight * srcRatio;
                if (info.trgWidth > this.options.thumbnailWidth) {
                    info.trgWidth = this.options.thumbnailWidth;
                    info.trgHeight = info.trgWidth / srcRatio;
                }
            } else {
                info.trgHeight = file.height;
                info.trgWidth = file.width;
            }
            return info;
        }
    }).on('addedfile', function(file) {
                
    }).on('success', function(file, serverResponse){

                var id = $(this.element);

                id.find('.progress').remove();
                var response = $.parseJSON(serverResponse);
                if (response && response.success == true && response.fileName){
                    $('#company-form').append('<input type=\"hidden\" name=\"Orgs[tmpLogotip][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
                }
                
    });            
";

$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
//$url1 = str_replace("\\","/",$url1_thumb);
if(Yii::app()->session->itemAt($this->uploadsession)){
    $datas = Yii::app()->session->itemAt($this->uploadsession);
    if(is_array($datas)){
        $cnt = 0; 
        foreach($datas as $key => $value){
            $cnt++;
                $scriptDd .='
                var sessFile'.$cnt.' = {
                    "name": "'.$key.'",
                    "size": "'.filesize(Yii::getPathOfAlias('webroot').$folder.$value).'",
                    "ext": "'.pathinfo(Yii::getPathOfAlias('webroot').$folder.$value, PATHINFO_EXTENSION).'"
                };
                 var linkThumb = "'.$folderLink.$value.'";
                dropzone.options.addedfile.call(dropzone, sessFile'.$cnt.'); 
                dropzone.options.thumbnail.call(dropzone, sessFile'.$cnt.', linkThumb);

                 $("#company-form").append("<input type=\'hidden\' name=\'Orgs[tmpFiles][]\' value=\'" + sessFile'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

        ';
        }
    }
}
$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
//$url1 = str_replace("\\","/",$url1_thumb);
if(Yii::app()->session->itemAt($this->uploadlogosession)){
    $datas = Yii::app()->session->itemAt($this->uploadlogosession);
    if(is_array($datas)){
        $cnt = 0; 
        foreach($datas as $key => $value){
            if(file_exists(Yii::getPathOfAlias('webroot').$folder.$value)){
                $cnt++;
                    $scriptDd .='
                    var sessFileLogo'.$cnt.' = {
                        "name": "'.$key.'",
                        "size": "'.filesize(Yii::getPathOfAlias('webroot').$folder.$value).'",
                        "ext": "'.pathinfo(Yii::getPathOfAlias('webroot').$folder.$value, PATHINFO_EXTENSION).'"
                    };
                     var linkThumbLogo = "'.$folderLink.$value.'";
                    dropzoneLogo.options.addedfile.call(dropzoneLogo, sessFileLogo'.$cnt.'); 
                    dropzoneLogo.options.thumbnail.call(dropzoneLogo, sessFileLogo'.$cnt.', linkThumbLogo);

                     $("#company-form").append("<input type=\'hidden\' name=\'Orgs[tmpLogotip][]\' value=\'" + sessFileLogo'.$cnt.'.name + "\' class=\'dr-zone-inputs\' >");

            ';
            }
        }
    }
}
$scriptDd .="
$('#dropzone_opener').on('click', function(){
    if($('#dropzone').is(':visible')){
        $('#dropzone').hide();
        $('#dropzone input.dr-zone-inputs').attr('disabled', true);
    } else {
        $('#dropzone').show();
        $('#dropzone input.dr-zone-inputs').attr('disabled', false);
    }
});
$('#dropzone_opener_logo').on('click', function(){
    if($('#dropzone_logo').is(':visible')){
        $('#dropzone_logo').hide();
        $('#dropzone_logo input.dr-zone-inputs').attr('disabled', true);
    } else {
        $('#dropzone_logo').show();
        $('#dropzone_logo input.dr-zone-inputs').attr('disabled', false);
    }
});


";
if(!$model->isNewRecord){ 


$scriptDd .="



    $('.deletePinboardFile').bind('click',function(e){
    e.preventDefault();
    var filetag = $(this).parent('.dz-file-preview');
    var urldel = $(this).attr('href');
            $.ajax({
                type: 'get',
                url: urldel,
                success:function(data) {
                    if (data != '[]') {
                        $.growl.error({ message: 'Error'});
                    } else {
                        $(filetag).remove();
                      //  $.growl.notice({ message: 'File removed successfully' });
                         
                    }
                    
                }
            });

    })
$('.deleteLogoFile').bind('click',function(e){
    e.preventDefault();
    var filetag = $(this).parent('.dz-file-preview');
    var urldel = $(this).attr('href');
            $.ajax({
                type: 'get',
                url: urldel,
                success:function(data) {
                    if (data != '[]') {
                        $.growl.error({ message: 'Error'});
                    } else {
                        $(filetag).remove();
                      //  $.growl.notice({ message: 'File removed successfully' });
                         
                    }
                    
                }
            });

    })
";
}
$scriptDd .= "

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
$('.addVideo').click(function(){
    $('#customFieldsVideo').append('<div class=\"form-group added\">' +
            '<label class=\"col-lg-3 col-md-12 col-sm-12 control-label\">Видео</label>' +
            '<div class=\"col-lg-9 col-md-12 col-sm-12\">' +
            '<div class=\"input-group\">' +
            '<input type=\"text\" value=\"\"  name=\"Orgs[video][]\" placeholder=\"Видео\" maxlength=\"255\" class=\"form-control\">' +
                '<span class=\"input-group-btn\">' +
                    '<button type=\"button\" class=\"btn btn-danger remHttp\"><span class=\"btn-label icon fa fa-minus-square\"></span></button>' +
                '</span>' +
            '</div>' +
            '</div>' +
        '</div>' +
        '<div class=\"form-group added\">' +
            '<label  class=\"col-lg-3 col-md-12 col-sm-12 control-label\">Название видео</label>' +
            '<div class=\"col-lg-9 col-md-12 col-sm-12\">' +
            '<input type=\"text\" value=\"\"  name=\"Orgs[video_comments][]\" placeholder=\"Название видео\" maxlength=\"255\" class=\"form-control\">' +
            '</div>' +
        '</div>');
});
$('#customFieldsHttp').on('click', '.remHttp', function(){
    $(this).parent().parent().parent().parent().next().remove();
    $(this).parent().parent().parent().parent().remove();
});
$('#customFieldsVideo').on('click', '.remVideo', function(){
    $(this).parent().parent().parent().parent().next().remove();
    $(this).parent().parent().parent().parent().remove();
});
})
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
