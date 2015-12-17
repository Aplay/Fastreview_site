<div class="comment" id="comment_<?php echo $model->id; ?>">
    <img class="comment-avatar" alt="" src="<?php echo $model->user->getAvatar(); ?> ">
    <div class="comment-body">
      <div class="comment-text">
        <div class="comment-heading">
          <?php echo $model->user->fullname; ?>
          <span><?php echo MHelper::Date()->timeAgo($model->created_date, array('short'=>true)); ?></span>
        </div>
        <?php echo nl2br(CHtml::encode($model->content)); ?>
      </div>
      <?php if(!$model->id_parent){ ?>
      <div class="comment-footer">
        <i class="md md-reply"></i> <a class="grayd" style="text-decoration:none;" href="javascript:void(0)" onclick="addComment(<?php echo $model->id; ?>);">Ответить</a>
      </div>
      <?php } ?>
    </div> <!-- / .comment-body -->
  </div> <!-- / .comment -->