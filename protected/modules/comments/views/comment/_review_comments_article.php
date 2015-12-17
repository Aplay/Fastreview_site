<div class="citycomments supercom">
<div class="data">

<div id="comments_listview_<?php echo $model->id; ?>" class="list-view">
<div class="items">
<?php

if($dataProviderComments && count($dataProviderComments->data)>0){
	for($i=count($dataProviderComments->data)-1;$i>=0;$i--){
		$this->renderPartial('application.modules.comments.views.comment._comment_super',array('model'=>$dataProviderComments->data[$i],'org_id'=>$org_id));
	}
} ?>
</div> 
</div>

</div>
<div class="clearfix"></div>
<?php 

if(!Yii::app()->user->isGuest){ 

?>
<div class="row">
<div class="comment col-xs-12">
	<div style="margin-top: 40px;">
    <img class="comment-avatar" alt="" src="<?php echo Yii::app()->user->model->getAvatar(); ?>">
    <div class="comment-body">
      <div class="fg-line">
        <textarea id="CommentArticle_text_<?php echo $org_id; ?>_<?php echo $model->id; ?>" name="CommentArticle[text]"  class="form-control"  placeholder="Комментировать"></textarea>
        <div style="display: none;" id="CommentArticle_text_em_<?php echo $org_id; ?>_<?php echo $model->id; ?>" class="CommentArticle_text_em_<?php echo $org_id; ?>_<?php echo $model->id; ?> CommentArticle_text_em_ in-bl-error"></div>
        </div>
        <div class="clearfix"></div>
        <button onclick="sendCommentArticle(<?php echo $org_id; ?>);" class="m-t-15 btn btn-success btn-sm waves-effect waves-button waves-float">Отправить</button>
      </div>  
    </div> <!-- / .comment-body -->
  </div> <!-- / .comment -->
</div>
<?php 


} else { ?>
<div class="pull-left" style="width:100%;margin:0px 20px 20px 0;"> 
<div class="с-3 f-16" style="margin-top:20px;margin-bottom: 0px;"><span class="theme-color" style="cursor:pointer;" onclick="$('.lc-block').removeClass('toggled');$('#l-login').addClass('toggled');$('#login_modal').modal();">Войдите</span>, чтобы оставить комментарий</div>        
</div>
<?php } ?>
</div><!-- // panel -->