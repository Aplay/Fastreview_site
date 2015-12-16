<?php 
Yii::app()->clientScript->registerPackage('slider_rev');


$im =  $this->city->getUrl('1190x412xC', 'adaptiveResizeQuadrant');
$text = '
<h3 class="c-white" style="font-family: \'Roboto\', Arial, serif;font-size: 50px;
    font-weight: bold;
    text-shadow: 1px 1px #666;margin-bottom:0;">Справочник '.$this->city->rodpad.'</h3>
<h4 class="c-white" style="font-family: \'Roboto\', Arial, serif;font-size: 25px;
    font-weight: 300;
    line-height: 1.2em;
    text-shadow: 1px 1px #666;margin-top:5px;">Фирмы, заведения, работа и отдых</h4>';

$url = Yii::app()->createAbsoluteUrl('/catalog/catalog/search', array('city'=>$this->city->url)); 
if(!$this->city->filename){
                $src = '/img/russia2.jpg';
              } else {
               // $src = '/uploads/city/'.$this->city->id.'/'.$this->city->filename;
              	$src = $im;
              }
              ?>

<div class="page-slider" id="main-slider">

      <div id="fullwidthbanner-container" class="fullwidthbanner-container  revolution-slider hidden-xs">
        <div  id="fullwidthbanner" class="fullwidthabnner dompsel" >
          <ul id="revolutionul">
            <!-- THE NEW SLIDE -->
            <li data-transition="zoomin" data-slotamount="7" data-masterspeed="100">
            <!--<li  data-transition="zoomin" data-slotamount="7" data-masterspeed="1000">-->
              <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
              <img src="<?php echo $src; ?>" alt="img1" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">

             <div class="caption lft slide_title_white slide_item_left proxima"
                data-x="center"
                data-y="120"
                data-speed="400"
                data-start="1500"
                data-easing="easeOutExpo">
                <?php 
                echo $text; ?>
                <div> 
<form style="font-family: 'Roboto', Arial, serif;" id="form_search" class="hidden-xs" action="<?php echo $url; ?>"  role="search" method="post">
         <input type="hidden" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>" />
         <input type="hidden" name="pereparam" value="1" />
         <input type="text" name="q" required class="form-control" placeholder="Что вы ищете?"> 
         <button type="submit" class="btn btn-success">ПОИСК</button>
</form> 
</div>
              </div>
   
            </li>     
          </ul>
            </div>
        </div>
    </div>
<?php

$scriptAddBr = '
$(document).ready(function(){
  $.when(
  jQuery("#fullwidthbanner").revolution({ 
                      delay:100,
                      startwidth:1920,
                      startheight:412,
                      hideThumbs:10,
                    })
  ).then(function(){
    $(".tp-bgimg.defaultimg").addClass("zoombo");
  });


})';
Yii::app()->clientScript->registerScript("fwban", $scriptAddBr, CClientScript::POS_END);
?>