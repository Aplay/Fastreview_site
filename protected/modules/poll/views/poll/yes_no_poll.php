<?php 

	$vote = PollVote::model()->find(array('condition'=>'choice_id=:id and ip_address=:ip','params'=>array(':id'=>$model->id,':ip'=>$ip)));

  if($vote){ ?>

	<div class="vote  c-9" id="vote<?php echo $model->id; ?>">
    <span class="user_votes">
    <span  class="user_pro <?php if($vote->vote == 1) { echo 'user_mine';} ?>"><i class="zmdi zmdi-thumb-up"></i></span> 
    <?php 
   $diff = $model->yes - $model->no;
    
   if($diff > 0){
   	 echo '<span class="user_n user_num_g">'.$diff.'</span>';
   //	echo '<span class="user_n user_num_g"></span>';
   } else if($diff < 0){
   	 echo '<span class="user_n user_num_r">'.abs($diff).'</span>';
   	// echo '<span class="user_n user_num_r"></span>';
   } else {
   	echo '<span class="user_n"></span>';
   }
   ?>  
   <span class="user_contra <?php if($vote->vote != 1) { echo 'user_mine';} ?>" ><i class="zmdi zmdi-thumb-down"></i></span> 
   </span>
   </div>
	
	<?php } else {
       
	?>

  <div class="vote  c-9 active" id="vote<?php echo $model->id; ?>">
   <span class="user_votes">
   <span class="user_pro" onclick="toVotePoll(<?php echo $model->id; ?>, 1);" ><i class="zmdi zmdi-thumb-up"></i></span> 
   
   <?php 
   $diff = $model->yes - $model->no;
   if($diff > 0){
   	 echo '<span class="user_n user_num_g">'.$diff.'</span>';
   	// echo '<span class="user_n user_num_g"></span>';
   } else if($diff < 0){
   	 echo '<span class="user_n user_num_r">'.abs($diff).'</span>';
   	// echo '<span class="user_n user_num_r"></span>';
   } else {
   	echo '<span class="user_n"></span>';
   }
   ?> 

   <span class="user_contra" onclick="toVotePoll(<?php echo $model->id; ?>, 0);"><i class="zmdi zmdi-thumb-down"></i></span> 
   </span>
   </div>
	<?php
	}

	?>

<?php 
$script = "
$(document).ready(function(){



})
";
Yii::app()->clientScript->registerScript("scriptvote", $script, CClientScript::POS_END);
?>