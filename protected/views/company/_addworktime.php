<?php

$themeUrl = Yii::app()->theme->baseUrl;
// Yii::app()->clientScript->registerCssFile($themeUrl . '/js/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css');
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/bootstrap-timepicker/js/bootstrap-timepicker_mod.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/moment/moment-with-locale-ru.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker_4.17.37_mod.js', CClientScript::POS_END);


?>
<div id="timework" style="display: block;">
<?php 
$darray = $ch_array = array();
// Значения по умолчанию
for($i=0;$i<=6;$i++){
    $darray[$i]['od'] = '9:00';
    $darray[$i]['cd'] = '18:00';
    $darray[$i]['bd'] = null;
    $darray[$i]['ed'] = null;
    if($i==0 || $i==6){
      //  $darray[$i]['tw_day'] = 'notwork';  // notwork - css класс для не активного дня (перечеркнут) 
      //  $darray[$i]['des'] = 'disabled';    // disabled - css класс для активного дня (с указанным временем работы)
        $darray[$i]['tw_day'] = '';                
        $darray[$i]['des'] = '';                  
    } else {
        $darray[$i]['tw_day'] = '';
        $darray[$i]['des'] = '';
    }
}
$worktime = $data->orgsWorktimes;

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

if(!empty($res_array)){
    foreach($res_array as $w){
        $darray[$w]['tw_day'] = 'notwork';
        $darray[$w]['des'] = 'disabled';
    }
}

$wdlist = array('ВС','ПН','ВТ','СР','ЧТ','ПТ','СБ');
?>
<style>
.wtimec{
	cursor:default;
	margin:5px 20px 5px 0;
}
</style>
<label>Время работы</label>
<ul style="list-style: outside none none;padding-left:0;margin-top:15px;">
<?php $tp = 0;
for ($i=1; $i < 7; $i++) { 
    $krug = $vyh = false;
if($darray[$i]['od'] == '00:00:00' && $darray[$i]['cd'] == '24:00'){
    $krug = true;
}
if($darray[$i]['tw_day'] == 'notwork'){ // выходной
    $vyh = true;
}
    ?>
    <li>
        <div class="pull-left">
        <div class="wtimec <?php echo $i==6?'weeknd':''; ?>"><?php echo $wdlist[$i]; ?></div>
        </div>
<div  class="pull-left  <?php echo $darray[$i]['tw_day']; ?>" style="margin-top:15px;"> 
<div class="tw_time pull-left" 
<?php if($krug == true || $vyh == true){echo ' style="display:none;" ';} ?>
>
<div class="input-group form-group pull-left m-b-0" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input type='text' value="<?php echo $darray[$i]['od']; ?>" name="open_door[<?php echo $i; ?>]" id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
<div class="pull-left" style="line-height:1.3em;margin:0 8px;">-</div>
<div class="input-group form-group pull-left m-b-0" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input  value="<?php echo $darray[$i]['cd']; ?>" name="close_door[<?php echo $i; ?>]" type='text'  id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="second-input form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
</div>

<div class="pull-left" 
<?php if($vyh == true){echo ' style="display:none;" ';} ?>
>
<label  class="checkbox checkbox-inline m-l-20 f-12">
        <input type="checkbox" class="alldaywork" name="alldaywork" value="1"

<?php if($krug == true){echo ' checked="checked" ';} ?>
        
        >
        <i class="input-helper"></i>    
        круглосуточно
    </label>
</div>
<div class="pull-left">
<label  class="checkbox checkbox-inline m-l-20  f-12">
        <input type="checkbox" class="alldaynowork" name="alldaynowork" value="1"
        <?php if($vyh == true){echo ' checked="checked" ';} ?>
        >
        <i class="input-helper"></i>    
        выходной
    </label>
</div>
<div class="pull-left obed"
<?php if($krug == true || $vyh == true){echo ' style="display:none;" ';} ?>
>

<?php 
$add = ' style="display:none;" ';
$add2 = 'display:block;';
$des = ' disabled ';
if(!empty($darray[$i]['bd']) || !empty($darray[$i]['ed'])){
	$add = '';
	$add2 = 'display:none;';
	$des = '';
}
?>
<div class="pull-left c-theme f-12 m-l-20 add_obed" style="<?php echo $add2; ?>vertical-align:top;line-height:1.5em;cursor:pointer;">Добавить обед</div>
<div class="pull-left obedy m-l-20" <?php echo $add; ?>>
<div class="input-group form-group pull-left m-b-0" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input <?php echo $des; ?>  type='text' value="<?php echo $darray[$i]['bd']; ?>" name="break_door[<?php echo $i; ?>]" id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
<div class="pull-left" style="line-height:1.3em;margin:0 8px;">-</div>
<div class="input-group form-group pull-left m-b-0" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input <?php echo $des; ?>   type='text' value="<?php echo $darray[$i]['ed']; ?>" name="endbreak_door[<?php echo $i; ?>]" id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="second-input form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
<div class="pull-left c-theme f-12 m-l-20 remove_obed" style="display:block;vertical-align:top;line-height:1.5em;cursor:pointer;">Убрать обед</div>
</div>
</div>
<div class="clearfix"></div>

</div>
<div class="clearfix"></div>
</li>
<?php } 
$krug = $vyh = false;
if($darray[0]['od'] == '00:00:00' && $darray[0]['cd'] == '24:00'){
    $krug = true;
}
if($darray[0]['tw_day'] == 'notwork'){ // выходной
    $vyh = true;
}
?>
<li>
        <div class="pull-left">
        <div class="wtimec weeknd"><?php echo $wdlist[0]; ?></div>
        </div>
<div  class="pull-left  <?php echo $darray[0]['tw_day']; ?>" style="margin-top:15px;"> 
<div class="tw_time pull-left" 
<?php if($krug == true || $vyh == true){echo ' style="display:none;" ';} ?>
>
<div class="input-group form-group pull-left m-b-0" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input type='text' value="<?php echo $darray[0]['od']; ?>" name="open_door[0]" id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
<div class="pull-left" style="line-height:1.3em;margin:0 8px;">-</div>
<div class="input-group form-group pull-left m-b-0 m-r-20" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input  value="<?php echo $darray[0]['cd']; ?>" name="close_door[0]" type='text'  id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="form-control time-picker second-input" data-toggle="dropdown" placeholder="" >
    </div>
</div>
</div>
<div class="pull-left" 
<?php if($vyh == true){echo ' style="display:none;" ';} ?>
>
<label  class="checkbox checkbox-inline f-12">
        <input type="checkbox" class="alldaywork" name="alldaywork" value="1" 

<?php if($krug == true){echo ' checked="checked" ';} ?>

        >
        <i class="input-helper"></i>    
        круглосуточно
    </label>
</div>
<div class="pull-left">
<label  class="checkbox checkbox-inline m-l-20  f-12">
        <input type="checkbox" class="alldaynowork" name="alldaynowork" value="1"
        <?php if($vyh == true){echo ' checked="checked" ';} ?>
        >
        <i class="input-helper"></i>    
        выходной
    </label>
</div>
<div class="pull-left obed"
<?php if($krug == true || $vyh == true){echo ' style="display:none;" ';} ?>
>
<div class="pull-left c-theme f-12 m-l-20 add_obed" style="vertical-align:top;line-height:1.5em;cursor:pointer;">Добавить обед</div>
<?php 
$add = ' style="display:none;" ';
$add2 = 'display:block;';
$des = ' disabled ';
if(!empty($darray[0]['bd']) || !empty($darray[0]['ed'])){
	$add = '';
	$add2 = 'display:none;';
	$des = '';
}
?>
<div class="pull-left obedy m-l-20" <?php echo $add; ?>>
<div class="input-group form-group pull-left m-b-0" style="<?php echo $add2; ?>margin-top:2px;">
        <div class="dtp-container dropdown">
        <input <?php echo $des; ?>  type='text' value="<?php echo $darray[0]['bd']; ?>" name="break_door[0]" id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
<div class="pull-left" style="line-height:1.3em;margin:0 8px;">-</div>
<div class="input-group form-group pull-left m-b-0" style="margin-top:2px;">
        <div class="dtp-container dropdown">
        <input  <?php echo $des; ?> type='text' value="<?php echo $darray[0]['ed']; ?>" name="endbreak_door[0]" id="tp<?php echo ++$tp; ?>" style="width:30px;height:15px;" class="second-input form-control time-picker" data-toggle="dropdown" placeholder="" >
    </div>
</div>
<div class="pull-left c-theme f-12 m-l-20 remove_obed" style="display:block;vertical-align:top;line-height:1.5em;cursor:pointer;">Убрать обед</div>
</div>
</div>
<div class="clearfix"></div>

</div>
<div class="clearfix"></div>
</li>

</ul>
	</div>

<?php
$scriptW = "
$(function(){
		$('.add_obed').on('click',function(){
			$(this).hide();
			$(this).parent('.obed').children('.obedy').show();
			$(this).parent('.obed').children('.obedy').find('input').prop('disabled', false);
			$(this).parent('.obed').children('.obedy').find('input').eq(0).val('13:00');
			$(this).parent('.obed').children('.obedy').find('input').eq(1).val('14:00');
		});
		$('.remove_obed').on('click',function(){
			$(this).parent('.obedy').hide();
			$(this).parent('.obedy').parent('.obed').children('.add_obed').show();
			$(this).parent('.obedy').find('input').val('');
		});
		$('.alldaywork').on('click',function(){
		    var checkvalue = $(this).is(':checked');
		    if(checkvalue == 1){
		    	$(this).parent().parent().next().find('input[type=checkbox]').prop('checked', false);
		    	$(this).parent().parent().parent().find('.obed').hide();
		    	$(this).parent().parent().parent().find('.obed').find('input').val('');
		    	$(this).parent().parent().prev('.tw_time').hide();
		        $(this).parent().parent().prev('.tw_time').find('input').eq(0).val('00:00');
		        $(this).parent().parent().prev('.tw_time').find('input').eq(1).val('24:00');
		    } else {
		    	$(this).parent().parent().parent().find('.obed').show();
		    	$(this).parent().parent().prev('.tw_time').show();
		        $(this).parent().parent().prev('.tw_time').find('input').eq(0).val('09:00');
		        $(this).parent().parent().prev('.tw_time').find('input').eq(1).val('18:00');
		    }
		});
		$('.alldaynowork').on('click',function(){
		    var checkvalue = $(this).is(':checked');
		    if(checkvalue == 1){
		    	$(this).parent().parent().prev().find('input[type=checkbox]').prop('checked', false);
		    	$(this).parent().parent().prev().hide();
		    	$(this).parent().parent().parent().find('.obed').hide();
		    	$(this).parent().parent().parent().find('.obed').find('input').val('');
		    	$(this).parent().parent().prev().prev('.tw_time').hide();
		        $(this).parent().parent().prev().prev('.tw_time').find('input').eq(0).val('');
		        $(this).parent().parent().prev().prev('.tw_time').find('input').eq(1).val('');
		    } else {
		    	$(this).parent().parent().prev().show();
		    	$(this).parent().parent().parent().find('.obed').show();
		    	$(this).parent().parent().prev().prev('.tw_time').show();
		        $(this).parent().parent().prev().prev('.tw_time').find('input').eq(0).val('09:00');
		        $(this).parent().parent().prev().prev('.tw_time').find('input').eq(1).val('18:00');
		    }
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

";
Yii::app()->clientScript->registerScript("scriptW", $scriptW, CClientScript::POS_END);
?>