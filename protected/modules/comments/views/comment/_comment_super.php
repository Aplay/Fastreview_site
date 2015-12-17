
<div class="comment" id="comment_<?php echo $model->id; ?>">
    <div class="iTable">
    <div class="iAvatar" style="height: 30px;">
    <img class="comment-avatar" alt="" src="<?php echo $model->user->getAvatar(); ?> ">
    </div>
    <div class="iAuthor" style="min-height: 30px;">
    <div class="comment-heading">
          <?php echo $model->user->fullname; ?>
          <span><?php echo MHelper::Date()->timeAgo($model->created, array('short'=>true)); ?></span>
    </div>
    </div>    
    </div>
    <div class="clearfix"></div> 
      <div class="comment-text">
 	<?php echo nl2br(CHtml::encode($model->text)); ?>
      </div>
  </div> <!-- / .comment -->
