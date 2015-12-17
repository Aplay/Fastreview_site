
<div class="comment" id="comment_<?php echo $data->id; ?>">
    <img class="comment-avatar" alt="" src="<?php echo $data->user->getAvatar(); ?> ">
    <div class="comment-body">
      <div class="comment-text">
        <div class="comment-heading">
          <?php echo $data->user->fullname; ?>
          <span><?php 
          echo MHelper::Date()->timeAgo($data->created, array('short'=>true)); ?></span>
        </div>
        
      </div>
      
    </div> <!-- / .comment-body -->
 	<div class="clearfix"></div> 
 	<?php echo nl2br(CHtml::encode($data->text)); ?>
</div> <!-- / .comment -->
