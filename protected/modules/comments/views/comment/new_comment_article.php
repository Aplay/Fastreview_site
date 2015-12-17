<?php
/**
 * @var $this Controller
 * @var $form CActiveForm
 */

				        
// Load module
$module = Yii::app()->getModule('comments');
// Validate and save comment on post request
$comment = $module->processRequest($model);
// Load model comments
// $comments = CommentArticle::getObjectCommentsAll($model);

$themeUrl = Yii::app()->theme->baseUrl;



?>



<div class="clearfix"></div>
<div id="comment_module" class="card">
<div id="gridReviews" class="card-body card-padding padding-in-article">

<?php
$ip = MHelper::Ip()->getIp();

$this->renderPartial('application.modules.comments.views.comment._itemreview_article',array('model'=>$comment,'ip'=>$ip, 'org_id'=>$model->id,'showcommenttext'=>true)); ?>



</div>
</div>
<div class="clearfix"></div>

<?php



$cscript = "


function plusScripts(){
    $.when(
    $('.comment-text').linkify({
    linkAttributes: {
        rel: 'nofollow'
    }
    })).then(
    $('.comment-text .linkified').each(function() {
      var path = $(this).html();
    if ( path.length > 40 ) {
        var name = path.substring(0,30);
        var endname = path.length - 5;
        var aftername = path.substring(endname);
        $(this).html('<span class=\"linkiend\">' + name + '...'+ aftername + '</span>' );
        $(this).dotdotdot({
          after: 'span.linkiend',
          wrap: 'letter'
        });           
      }
    })
    )
    
}
$(document).ready(function(){
	$('.size-1of2:odd .innerReviewInColor').css('margin-left','16px');
   $(window).on('debouncedresize', function(){
		$('.innerReviewInColor').css('margin-left','0px');
		$('.size-1of2:odd .innerReviewInColor').css('margin-left','16px');
    });
	$('textarea').autosize({
		append:false
	});
})
";

Yii::app()->clientScript->registerScript("commentscript", $cscript, CClientScript::POS_END);

if(!Yii::app()->user->isGuest){ 
	$scriptAdd = "

$(document).ready(function(){
   plusScripts();
   
   sendCommentArticle = function(review){  
                
                $('.CommentArticle_text_em_').hide();   
                 
                $.ajax({  
                    type: 'POST',  
                    url: '/comments/comment/addreviewarticle/'+review,  
                    data: {'ajax':'comments-form', 'type':1,  'CommentArticle[text]':$('#CommentArticle_text_'+ review +'_').val(),
                           '". Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'
                          }, 
                    
                    success: function(html){  
                        if (html.indexOf('{')==0) {
                                var obj = jQuery.parseJSON(html);
                                $('#CommentArticle_text_em_'+ review +'_').show().html(obj.CommentArticle_text);
                         } else {
                              $('.comment_add').remove();
                              
                              $('#comments_listview_ .items').append(html);
                              
                              $('#CommentArticle_text_'+ review +'_').val('');
                              plusScripts();

                         }
                    }  
                });  
                return false; 
            };
          });";
Yii::app()->clientScript->registerScript("addcomment", $scriptAdd, CClientScript::POS_END);	
} else {
	$this->widget('ext.widgets.Login', array('return_url' => Yii::app()->request->requestUri));
}
?>