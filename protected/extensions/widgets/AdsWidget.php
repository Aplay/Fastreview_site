<?php
Yii::import('application.modules.ads.models.Ads');
class AdsWidget extends CWidget
{
    public $block_id=0;

 
    public function run()
    {
        $ads = Ads::model()->find(array(
            'condition'=>'block_id=:block_id and status=1',
            'params'=>array(':block_id'=>$this->block_id),
        ));
       
        if($ads && !YII_DEBUG){
     	 echo '<div class="clearfix"></div>';
         echo '<div id="ads'.$ads->block_id.'">';   
         echo $ads->content;
         echo '</div>';
         echo '<div class="clearfix"></div>';
        } else if($ads && YII_DEBUG){ 

         echo '<div class="clearfix"></div>';
         echo '<div id="ads'.$ads->block_id.'" class="debug" style="width:200px;height:200px;background-color: #cdc;">';   
         echo '</div>';
         echo '<div class="clearfix"></div>';
        }
        /*if($ads && $this->block_id == 2){
           $content = str_replace(array("\n","\r"), '', $ads->content);
           $js = "$(document).ready(function(){
                var els = $('.org_item').length;
                var randomEls = Math.floor((Math.random() * els) + 1);
                console.log(randomEls)
                $('.org_item').eq(randomEls-1).after('<div id=\"ads2\" class=\"ads2\"></div>');
           });";
           Yii::app()->getClientScript()->registerScript('ads_' . $this->block_id, $js, CClientScript::POS_END);

        }*/
    }
}
?>