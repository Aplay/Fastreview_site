<?php
/**
 * @var $this Controller
 * @var $form CActiveForm
 */

				        
// Load module
$module = Yii::app()->getModule('comments');
// Validate and save comment on post request
$comment = $module->processRequestSpec($model);
// Load model comments
$comments = CommentSpec::getObjectComments($model);

$themeUrl = Yii::app()->theme->baseUrl;



?>
<!-- Modal -->
<div id="leave_comment" class="modal fade" tabindex="-1" role="dialog" style="display:none;">

	<div class="modal-dialog">
		<div class="modal-content">
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
                    							."      $('#CommentSpec_reCaptcha_em_').hide();\n"
                                                ."      if(jQuery.isEmptyObject(data)) {\n"
                                                ."	      $('#leave_comment .modal-body.for_form,#leave_comment .modal-footer').hide();\n"
                                                ."	      $('#leave_comment .modal-body.success').show();\n"
                                                ."      } else {\n"
                                                ."		  if ('CommentSpec_reCaptcha' in data)\n" 
												."		     $('#CommentSpec_reCaptcha_em_').show().html(data['CommentSpec_reCaptcha']);\n"
                                                ."      }\n"
                                                ."    return false;\n"
                                                ."}\n"
                ),
			)); 
			
			?>
		    <div class="modal-header" style="border-bottom:0">
			<div style="width:100%; font-size:17px; position: relative;padding-left:10px; ">
			Оставить отзыв
			<div style="position:absolute; top:0; right:0;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:-3px">×</button></div>
			</div>
            </div>
            <div class="modal-body success" style="display:none; padding: 0 36px; text-align: center; margin:0 auto;">
            <div>Спасибо за Ваш отзыв! Ваш отзыв будет опубликован после проверки.</div>
            <div style="height:40px;"></div>
            </div>
            <?php if(Yii::app()->user->isGuest){ ?>
            <div class="modal-body for_form f-12 c-gray" style="padding: 10px 36px 5px 36px;background-color:#ebf6ec;">
            
            Если вы <a class="c-green" href="javascript:void(0);"  onclick="$('#leave_comment').modal('hide');$('.lc-block').removeClass('toggled');$('#l-login').addClass('toggled');setTimeout(function(){$('#login_modal').modal()},500);">авторизуетесь</a> через почту или социальные сети, то сможете:<br>
            <ul  style="padding:3px 0 0 12px;">
              <li>Оставлять отзыв от своего имени</li> 
              <li>Редактировать отзыв</li>
              <li>Общаться с организацией</li>  
              <li>Комментировать отзыв от имени автора</li> 
            </ul>
            
            </div>
            <?php } ?>
			<div class="modal-body for_form" style="padding: 0 36px">
            	<div class="org_rating m-t-20" style="position:relative;">
            	<span style="display:inline-block;margin-right:8px;vertical-align:middle;">Ваша оценка:</span>
            	
				<?php
				$value = 0;

				if ($model->rating_id && isset($model->rating) && !empty($model->rating->vote_average) && is_numeric($model->rating->vote_average) ){
				         
				          $value = round($model->rating->vote_average,0);
				          if($value > 5)
				          	$value = 5;
				         
				  }
				 $data_starred = array(0=>"", 1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем');
			/*	 echo $form->hiddenField($comment,'rating',array('value'=>$value)); */
				echo $form->hiddenField($comment,'rating');
				$this->widget('CStarRating',array(
				    'value'=>0,
				    'name'=>'star_rating_review_modal_'.$model->id,
				    'cssFile'=>$themeUrl.'/css/star_rating2.css',
				    'starWidth'=>25,
				    'minRating'=>1,
				    'maxRating'=>5,
				    'htmlOptions'=>array('class'=>'rl-star', 'id'=>'rl-star-live-'.$model->id,'style'=>'display:inline-block; vertical-align:middle;line-height:1.2em; margin-right:7px;'),
				    'titles'=>array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем'),
				    'focus'=>'function(){
				    	var id=$(this).attr("id");
				    	var rat = $("#CommentSpec_rating").val();
				    	$(".star-rating-live").attr("style", "");
				    	$("div#"+id).prevAll(".star-rating-live").css("color", $("div#"+id).css("color"));
				    	
				    	$(".star-rating > a").attr("title", "");
				    	$("#star_text_show").html($(this).attr("title"));

					}',
					'blur'=>'function(){
						var id=$(this).attr("id");
				    	
				    	
						var crat = ["","Ай-ай-ай, не советую","Так себе, могло быть и лучше","Вполне нормально","Да, мне нравится","Супер, советую всем"];
				    	var rat = $("#CommentSpec_rating").val();

				    	$(".star-rating-live").attr("style", "");
				    	if(rat){
				    		rat = parseInt(rat);
				    		$("#star_text_show").html(crat[rat]);
				    		var that = $(".modal-body div.star-rating").eq(rat-1);
				    		that.prevAll(".star-rating-live").css("color", that.css("color"));
				    	} else {
				    		$("div#"+id).prevAll(".star-rating-live").attr("style", "");
				    		$("#star_text_show").html(crat[1]);
				    	}

					}',
				    'callback'=>'
				        function(){
				        	var id=$(this).attr("id");
				    	    $("div#"+id).prevAll(".star-rating-live").css("color", $("div#"+id).css("color"));
				    	
				        		$("#star_text_show").html($(this).attr("title"))
				        		$("#CommentSpec_rating").attr("value", $(this).val());
				         }'
				  ));
			//	echo "<div id='mystar_voting_modal'></div>";
				?>
				<span class="c-gray f-12" style="display:inline-block;vertical-align:middle;" id="star_text_show" data-starred="<?php echo $data_starred[$value]; ?>"><?php echo $data_starred[$value]; ?></span>
				<?php echo $form->error($comment,'rating',array()); ?>
				</div>
				
				
				<div class="clearfix"></div>
				<div style="height:24px;width:100%" >
					
				</div>
				<?php if(Yii::app()->user->isGuest) { ?>

				<div class="form-group fg-line green">
					<?php echo $form->labelEx($comment,'name',array('class'=>'')); ?>
					<?php echo $form->textField($comment,'name',array('class'=>'form-control','placeholder'=>'Введите ваш текст...')); ?>
					<?php echo $form->error($comment,'name'); ?>
				</div>

				<div class="form-group fg-line green">
					<?php echo $form->labelEx($comment,'email',array('class'=>'')); ?>
					<?php echo $form->textField($comment,'email',array('class'=>'form-control','placeholder'=>'Введите ваш текст...')); ?>
					<?php echo $form->error($comment,'email'); ?>
				</div><?php  ?>
			<?php } ?>
				<div class="form-group fg-line green">
				<?php echo $form->labelEx($comment,'text'); ?>
					<?php echo $form->textArea($comment,'text', array('style'=>'word-wrap: break-word; min-height: 90px; width: 100%; resize: none; overflow:hidden;', 'class'=>'form-control','placeholder'=>'Введите ваш текст...')); ?>
		            <?php echo $form->error($comment,'text'); ?>
				</div>

		<?php if(Yii::app()->user->isGuest) { ?>
        <div class="form-group text-center">
          <?php // echo CHtml::activeLabelEx($comment, 'reCaptcha');

          $this->widget('application.components.ReCaptcha', array(
            'name'=>'reCaptcha',
          //  'size'=>'compact',
            'siteKey'=>Yii::app()->reCaptcha->siteKey,
            'htmlOptions'=>array('id'=>'recaptcha_comment'),
          )); 
          ?>
          <br/>
          <?php 
           echo $form->error($comment,'reCaptcha'); 
          ?>
        </div>
        <?php } else { ?>
        	<div style="width:200px;text-align:center;margin:0 auto;">
        	<div class="iTable" style="width:200px;margin:0 auto;">
            <div class="iAvatar">
        	<img class="img-circle" src="<?php echo Yii::app()->user->getAvatar(); ?>" />
        	</div>
			<div class="iAuthor">
				<span style="color:#333;font-size:17px;font-weight:300;"><?php echo Yii::app()->user->fullname; ?></span>
			</div>
			</div>
			</div>
        <?php	} ?>
              
                 
			</div> <!-- / .modal-body -->
			<div class="modal-footer no-border-t" style="border-top:0; text-align: center;margin-top:20px;">
				<button style="margin-bottom:60px;" type="submit" class="btn btn-sm btn-success"><i class="md md-speaker-notes"></i> Оставить отзыв</button>
				
			</div>
             <?php $this->endWidget(); ?>
		</div> <!-- / .modal-content -->
	</div> <!-- / .modal-dialog -->
	
</div> <!-- /.modal -->

<?php
// Display comments
if(!empty($comments)) { ?>
<div class="clearfix"></div>
<div id="comment_module" style="margin-top:20px;margin-bottom:30px;">
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

		$this->renderPartial('application.modules.comments.views.comment._itemreview_spec',array('model'=>$row,'ip'=>$ip, 'org_id'=>$model->id,'showcommenttext'=>true)); ?>
		</div>

		<?php
		}
	
}
?>
</div>
</div>
<?php /* ?>

	foreach($comments as $row)
	{
            
	?>
		<div itemscope itemtype="http://schema.org/Review" class="comment_review" id="comment_<?php echo $row->id; ?>">
			<meta itemprop="url" content="<?php echo Yii::app()->createAbsoluteUrl(Yii::app()->request->getUrl()); ?>" />
			<span itemprop="author" itemscope itemtype="http://schema.org/Person">
			<span class="name" itemprop="name">
			<?php 
            if(!empty($row->user_id)){
               echo $row->user->username;
            } else {
                echo CHtml::encode($row->name);
                
            }
            ?></span></span> <span class="created">
            <meta itemprop="datePublished" content="<?php echo date('Y-m-d', strtotime($row->created)  + ($city_utc*60*60)); ?>" />
            <?php echo Yii::app()->dateFormatter->format('dd MMMM yyyy HH:mm',  date('Y-m-d H:i:s', strtotime($row->created)  + ($city_utc*60*60))); ?></span>
			
			<?php //echo CHtml::link('#', Yii::app()->request->getUrl().'#comment_'.$row->id)
			if($row->rating){
			 ?>
			
			<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"  class="review_rating">
			<meta itemprop="worstRating" content="1" />
			<meta itemprop="bestRating" content="5" />
			<meta itemprop="ratingValue" content="<?php echo $row->rating; ?>"/>
			<?php
			
			$this->widget('CStarRating',array(
			    'value'=>$row->rating,
			    'name'=>'star_rating_'.$row->id,
			    'cssFile'=>$themeUrl.'/css/star_rating2.css',
			    'titles'=>array(1=>'Ай-ай-ай, не советую',2=>'Так себе, могло быть и лучше',3=>'Вполне нормально',4=>'Да, мне нравится',5=>'Супер, советую всем'),
			    'starWidth'=>18,
			    'minRating'=>1,
				'maxRating'=>5,
			    'readOnly'=>true,
			  ));
			?>
			</div>
			<div class="clearfix"></div>
			<?php } ?>
			<div itemprop="reviewBody" class="message">
				<?php 
                echo MHelper::String()->wordwrap(nl2br(CHtml::encode($row->text)),39,"\n",true);
                ?>
			</div>
                       
		</div>
	<?php
	}
	?>
	<?php */ ?>
<?php
}
?>
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
        url: '/comments/showmorespec/'+review, 
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

if(!Yii::app()->user->isGuest){ 
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
        '<div class=\"fg-line\"><textarea id=\"CommentSpec_content_'+ review +'_'+ id +'\" name=\"CommentSpec[text]\" class=\"form-control\" placeholder=\"Комментировать\"></textarea>'+
        '<div style=\"display: none;\" id=\"CommentSpec_text_em_'+ review +'_'+ id +'\" class=\"CommentSpec_text_em_ in-bl-error\"></div>'+
        '</div><button onclick=\"sendComment('+ review +', '+ id +');\" class=\"m-t-15 btn btn-success btn-sm waves-effect waves-button waves-float\">Отправить</button>'+
        '</div>'+
      '</div>'+
    '</div></div>'+
  '<div class=\"clearfix\"></div></div>';
      $('#comment_' +id).append(form);
      $('textarea').autosize({append:false});
      $('#CommentSpec_text_'+ review +'_'+ id).focus();

   }
   sendComment = function(review, id){  
                
                $('.CommentSpec_text_em_').hide();   
                 
                $.ajax({  
                    type: 'POST',  
                    url: '/comments/comment/addreviewspec/'+review,  
                    data: {'ajax':'comments-form', 'type':1, 'CommentSpec[id_parent]':id, 'CommentSpec[text]':$('#CommentSpec_text_'+ review +'_'+ id).val(),
                           '". Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'
                          }, 
                    
                    success: function(html){  
                        if (html.indexOf('{')==0) {
                                var obj = jQuery.parseJSON(html);
                                $('#CommentSpec_text_em_'+ review +'_'+ id).show().html(obj.CommentSpec_text);
                         } else {
                              $('.comment_add').remove();
                              
                              $('#comments_listview_'+ id +' .items').append(html);
                              
                              $('#CommentSpec_text_'+ review +'_'+ id).val('');
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