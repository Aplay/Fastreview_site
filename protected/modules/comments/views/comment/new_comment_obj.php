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
$comments = Comment::getObjectComments($model);





?>
<?php
// Display comments
if(!empty($comments)) { ?>
<div class="clearfix"></div>
<div id="comment_module" class="m-t-30 m-b-30">
<div id="gridReviews" data-columns>
<?php
if($comments){
	$ip = MHelper::Ip()->getIp();
	foreach($comments as $row){
		if(!empty($row->user_id)){
			$user_avatar = $row->user->getAvatar();
			$user_name = $row->user->fullname;
		} else {
			$user_avatar = '/img/avatar.png';
			$user_name = $row->name;
		}

		?>
		<div class="masonry_item">
		<?php 

		$this->renderPartial('application.modules.comments.views.comment._itemreview',array('model'=>$row,'ip'=>$ip, 'org_id'=>$model->id,'showcommenttext'=>false)); ?>
		</div>

		<?php
		}
	
}
?>
</div>
</div>
<?php
}
?>

<!-- Modal -->
<div id="leave_comment">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'comment-create-form',
				'action'=>'#comment-create-form',
				'htmlOptions'=>array( 'role'=>'form'),
				'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'errorMessageCssClass'=>'in-bl-error',
                'clientOptions'=>array(
                    'validateOnSubmit'=>true, 
                    'validateOnChange' => false,
                    'afterValidate' => "js: function(form, data, hasError) {\n"
                                                ."      if(jQuery.isEmptyObject(data)) {\n"
                                                ."	      $('#leave_comment .modal-body.for_form,#leave_comment .modal-footer').hide();\n"
                                                ."	      $('#leave_comment .modal-body.success').show();\n"
                                                ."      } else {\n"

                                                ."      }\n"
                                                ."    return false;\n"
                                                ."}\n"
                ),
			)); 
			
			?>
            <div class="modal-body success" style="display:none; padding: 0 36px; text-align: center; margin:0 auto;">
            <div>Спасибо за Ваш отзыв! Ваш отзыв будет опубликован после проверки.</div>
            <div style="height:40px;"></div>
            </div>
			<div class="modal-body for_form p-0">
            	
				<div class="clearfix"></div>

				<?php if(Yii::app()->user->isGuest) { ?>

				<div class="form-group fg-line green m-t-10">
					<?php echo $form->labelEx($comment,'name',array('class'=>'')); ?>
					<?php echo $form->textField($comment,'name',array('class'=>'form-control','placeholder'=>'Введите имя')); ?>
					<?php echo $form->error($comment,'name'); ?>
				</div>

			<?php } else { ?>
			<div class="media m-b-20">
		    	<div class="pull-left">
		            <a href="<?php echo Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username)); ?>"><img alt="" src="<?php echo Yii::app()->user->getAvatar(); ?>" class="lv-img-md" /></a>
		        </div>
		        <div class="media-body m-t-15">
		        <p class="m-b-5 nocolor f-15 f-500"><a class="nocolor" href="<?php echo Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username)); ?>"><?php echo Yii::app()->user->fullname; ?></a></p>
		        </div>
		  	</div>

			<?php	} ?>
			<div class="form-group fg-line green m-b-25">
				<?php echo $form->labelEx($comment,'text'); ?>
					<?php echo $form->textArea($comment,'text', array('style'=>'word-wrap: break-word; min-height: 90px; width: 100%; resize: none; overflow:hidden;', 'class'=>'form-control','placeholder'=>'Введите отзыв или комментарий')); ?>
		            <?php echo $form->error($comment,'text'); ?>
				</div>
			<div class="form-group">
			<button type="submit" class="btn btn-sm btn-default-over">Оставить отзыв</button>
			</div>		
			</div> <!-- / .modal-body -->
			
			
             <?php $this->endWidget(); ?>

</div> <!-- /.modal -->


<?php

$cntReviews = $comments?count($comments):0;

$cscript = "
var cntReviews = ".$cntReviews.";
function showMoreComments(elm){
	var review = $(elm).data('review');
	var page = $(elm).data('page');
	var cc = $(elm).data('cc');
	$.ajax({  
        type: 'POST',  
        url: '/comments/showmore/'+review, 
        data: {'page':page+1,
               '". Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'
              }, 
        
        success: function(html){  

          $('#comments_listview_'+review+' .items').prepend(html);
          $(elm).data('page',page+1);
          plusScripts();
          if(((page+1)*10+3)>cc){
          	// console.log((page+1)*10+3)
          	// console.log(cc)
          	 $(elm).hide()
          }
        }  
    }); 
}
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

// if(!Yii::app()->user->isGuest){ 
	$scriptAdd = "

$(document).ready(function(){
   plusScripts();
   addComment = function(id,review){  
      if($('#comment_add_'+id).length > 0){
        $('#comment_add_'+id).remove();
        return false;
      }
      $('.comment_add').remove();

      var src = '".Yii::app()->user->getAvatar()."';

      var form = '<div class=\"row\"><div id=\"comment_add_'+ id +'\" class=\"comment col-xs-12 comment_add\">'+
      '<img class=\"comment-avatar\" alt=\"\" src=\"'+ src +'\">'+
      '<div class=\"comment-body\">'+
      '<div class=\"article-comments-add expanding-input expanding-input-sm expanded\">'+
        '<div class=\"fg-line\"><textarea id=\"Comment_content_'+ review +'_'+ id +'\" name=\"Comment[text]\" class=\"form-control\" placeholder=\"Комментировать\"></textarea>'+
        '<div style=\"display: none;\" id=\"Comment_text_em_'+ review +'_'+ id +'\" class=\"Comment_text_em_ in-bl-error\"></div>'+
        '</div><button onclick=\"sendComment('+ review +', '+ id +');\" class=\"m-t-15 btn btn-success btn-sm waves-effect waves-button waves-float\">Отправить</button>'+
        '</div>'+
      '</div>'+
    '</div></div>'+
  '<div class=\"clearfix\"></div></div>';
      $('#comment_' +id).append(form);
      $('textarea').autosize({append:false});
      $('#Comment_text_'+ review +'_'+ id).focus();

   }
   sendComment = function(review, id){  
                
                $('.Comment_text_em_').hide();   
                 
                $.ajax({  
                    type: 'POST',  
                    url: '/comments/comment/addreview/'+review,  
                    data: {'ajax':'comments-form', 'type':1, 'Comment[id_parent]':id, 'Comment[text]':$('#Comment_text_'+ review +'_'+ id).val(),
                           '". Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'
                          }, 
                    
                    success: function(html){  
                        if (html.indexOf('{')==0) {
                                var obj = jQuery.parseJSON(html);
                                $('#Comment_text_em_'+ review +'_'+ id).show().html(obj.Comment_text);
                         } else {
                              $('.comment_add').remove();
                              
                              $('#comments_listview_'+ id +' .items').append(html);
                              
                              $('#Comment_text_'+ review +'_'+ id).val('');
                              plusScripts();

                         }
                    }  
                });  
                return false; 
            };
          });";
Yii::app()->clientScript->registerScript("addcomment", $scriptAdd, CClientScript::POS_END);	
// } else {
//	$this->widget('ext.widgets.Login', array('return_url' => Yii::app()->request->requestUri));
// }
?>