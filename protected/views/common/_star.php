<?php 
$themeUrl = Yii::app()->theme->baseUrl;
?>
<?php

if ($data->rating_id && isset($data->rating) && is_numeric($data->rating->vote_average) ){
         $value = round($data->rating->vote_average,0);
         if($value > 5)
            $value = 5;
         $vote_count = $data->rating->vote_count;
         }else{
         $value = 0;
         $vote_count = 0;
 }

$star_text =  array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем');

$this->widget('ext.widgets.MyStarRating',array(

    'value'=>$value,
    'name'=>'star_rating_review_modal_'.$data->id,
    'cssFile'=>$themeUrl.'/css/star_rating2.css',
    'starWidth'=>25,
    'minRating'=>1,
    'maxRating'=>5,
    'titles'=>$star_text,
    'readOnly'=>true,
    'htmlOptions'=>array('class'=>'rl-star', 'id'=>'rl-star-'.$data->id),
                   
    
  ));

?>
<?php 
if($show_count && $vote_count){
  echo '<span class="afterstar">'.$vote_count.' '.Yii::t('site','review|reviews',$vote_count).'</span>';
}

?>