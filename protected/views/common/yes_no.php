<?php 
$className = $url = '';
$ip = MHelper::Ip()->getIp();
if($model)
	$className = get_class($model);
$vote = null;
if($className == 'Wherefind'){
	$url = "/site/tovote";
	$vote = WherefindVote::model()->find(array('condition'=>'wherefind=:id and ip=:ip','params'=>array(':id'=>$model->id,':ip'=>$ip)));
} elseif($className == 'Article'){
	$url = "/site/tovotearticle";
	$vote = ArticleVote::model()->find(array('condition'=>'article=:id and ip=:ip','params'=>array(':id'=>$model->id,':ip'=>$ip)));
} 

	if($vote){ ?>

	<div class="vote  c-9" id="vote<?php echo $model->id; ?>">
    <span class="user_votes">
    <span  class="user_pro <?php if($vote->vote == 1) { echo 'user_mine';} ?>"><i class="md md-thumb-up"></i></span> 
    <?php 
   $diff = $model->yes - $model->no;
   if($diff > 0){
   	echo '<span class="user_n user_num_g">'.$diff.'</span>';
   } else if($diff < 0){
   	echo '<span class="user_n user_num_r">'.abs($diff).'</span>';
   } else {
   	echo '<span class="user_n"></span>';
   }
   ?>  
   <span class="user_contra <?php if($vote->vote != 1) { echo 'user_mine';} ?>" ><i class="md md-thumb-down"></i></span> 
   </span>
   </div>
	
	<?php } else {
       
	?>
  <div class="vote  c-9 active" id="vote<?php echo $model->id; ?>">
   <span class="user_votes">
   <span class="user_pro" onclick="toVote(<?php echo $model->id; ?>, 1);" ><i class="md md-thumb-up"></i></span> 
   
   <?php 
   $diff = $model->yes - $model->no;
   if($diff > 0){
   	echo '<span class="user_n user_num_g">'.$diff.'</span>';
   } else if($diff < 0){
   	echo '<span class="user_n user_num_r">'.abs($diff).'</span>';
   } else {
   	echo '<span class="user_n"></span>';
   }
   ?> 

   <span class="user_contra" onclick="toVote(<?php echo $model->id; ?>, 0);"><i class="md md-thumb-down"></i></span> 
   </span>
   </div>
	<?php
	}
	if($meta && $diff > 0){
		echo '<meta itemprop="upvoteCount" content="'.$diff.'" />';
	}
	?>

<?php 
$script = "
$(document).ready(function(){


toVote = function(id, vote){
    if(!$('#vote'+id).hasClass('active'))
        return;
    var hn = $('#csfr').attr('name');
    var hv = $('#csfr').attr('value');
    var datav = {'id':id,'vote':vote};
    datav[hn] = hv;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data:datav,
        url: '".$url."',

        success: function(data) {
            if(data.flag==true){
                $('#vote'+id).removeClass('active');
                
                if(vote == 1){
                    $('#vote'+id+' .user_pro').addClass('user_mine');
                } else {
                    $('#vote'+id+' .user_contra').addClass('user_mine');
                }
                var sum = data.yes - data.no;
                if(sum>0){
                	$('#vote'+id+' .user_n').removeClass('user_num_r').addClass('user_num_g').html(sum);
                } else if(sum<0){
                	$('#vote'+id+' .user_n').removeClass('user_num_g').addClass('user_num_r').html(Math.abs(sum));
                } else {
                	$('#vote'+id+' .user_n').removeClass('user_num_g, user_num_r').html('');
                }
            }
        }
    });
}
})
";
Yii::app()->clientScript->registerScript("scriptvote", $script, CClientScript::POS_END);
?>