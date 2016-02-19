<?php  

$themeUrl = Yii::app()->theme->baseUrl;
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce-layout.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce.css');


//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/kl-woocommerce_single.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/dp-styles.css');
//Yii::app()->clientScript->registerCssFile($themeUrl . '/css/style.css');

$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('id'=>$model->id,'itemurl'=>$model->url,'dash'=>'-'));

//Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:site_name"));
Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag('article', null, null, array('property' => "og:type"));
Yii::app()->clientScript->registerMetaTag($url, null, null, array('property' => "og:url"));

$im = '';
$image = Yii::app()->createAbsoluteUrl('/mstile-310x310.png'); 
    
?>
<div class="row m-t-20">
<div class="col-sm-9 col-sm-offset-1 col-md-8 col-md-offset-2">

	<div  class="itemListView clearfix eBlog kl-blog--light">
<?php
$im = '/img/cap_850x565.gif';
$user_avatar = $model->authorid->getAvatar();
$user_name = '<a class="nocolor" href="'.Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$model->authorid->username)).'">'.$model->authorid->getShowname().'</a>';
$avatar = '<a href="'.Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$model->authorid->username)).'"><img alt="" src="'.$user_avatar.'" class="lv-img-md" /></a>';
if(!empty($model->logotip))
{
 // $src = $model->getOrigFilePath().$model->logotip; 
 $model->setCap($im);
 $im = $model->getUrl('850x565',false,false,'logotip');
 $image = Yii::app()->createAbsoluteUrl($model->getUrl('200x100xC','adaptiveResizeQuadrant'));
 } 
  ?>
<div class="itemContainer post-<?php echo $model->id; ?> featured-post" style="margin-bottom:0;">
<div class="zn_full_image" style="margin-bottom:0;background:url('<?php echo $im; ?>') no-repeat center center; background-size: cover; width:100%;height: 400px;">
<!-- <img alt="" src=""  class="zn_post_thumbnail">-->
</div>

<div class="itemFeatContent">
    <div class="itemFeatContent-inner">
        <div class="itemHeader">
            <h3 class="itemTitle">
                
                <?php echo CHtml::encode($model->title); ?>
           
            </h3>
            <div class="post_details kl-font-alt">
            <div class="media m-t-15 m-b-25">
            <div class="pull-left">
              <?php echo $avatar; ?>
            </div>
            <div class="media-body">
              <p class="nocolor f-15 f-500"><?php echo $user_name; ?> 
              <br>
              <span class="c-gray f-12" >&nbsp;<?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model->created_date); ?></span></p>
            </div>
            </div>
         
            </div>
            <!-- end post details -->
        </div>
       
        
        <div class="itemComments hide">
            <?php 
            $cnt_comments = count($model->comments);
            if($cnt_comments){
                $com_url = $url.'#reviews';
                $txt_comment = $cnt_comments.' '.Yii::t('site', 'comments|commentss', $cnt_comments);
                echo CHtml::link($txt_comment, $com_url, array('class'=>'nocolor'));
            } else {
                $txt_comment = 'Нет комментариев';
                echo CHtml::link($txt_comment, $url, array('class'=>'nocolor'));
            } ?>

        </div>
        <!-- item links -->
        <div class="clearfix"></div>
    </div>
    </div>
</div>
 <div class="itemContainer" >
<div class="fromvis">
<?php 
echo $model->description;
 ?>
 </div>
<div class="itemBottom  clearfix" style="border:none;">
        <div class="itemTagsBlock kl-font-alt">
        
          </div><!-- end tags blocks -->
    </div>
 
</div>



<?php 
// $this->renderPartial('application.views.common._share_2',array('thisUrl'=>$url,'image'=>$image));
?>
</div><!-- itemListView -->
<div >
  <?php
  $ip = MHelper::Ip()->getIp();
  $vote = Vote::model()->find(array('condition'=>'review=:id and ip=:ip','params'=>array(':id'=>$model->id,':ip'=>$ip)));
  if($vote){ ?>

  <div class="vote text-right c9" style="margin-top:10px;" id="vote<?php echo $model->id; ?>">
   <span class="c-gray f-11" style="margin-right:5px;">Полезен ли обзор?</span> <span class="user_votes c-9"><span  class="user_pro  <?php if($vote->vote == 1) { echo 'user_mine';} ?>"><i class="zmdi zmdi-thumb-up"></i></span> <span class="user_num"><?php echo $model->yes?$model->yes:' '; ?></span>  <span class="user_contra <?php if($vote->vote != 1) { echo 'user_mine';} ?>" ><i class="zmdi zmdi-thumb-down"></i></span> <span class="user_contra-num"><?php echo $model->no?$model->no:' '; ?></span></span></div>
  
  <?php } else {
       
  ?>
  <div class="vote text-right c9 active" style="margin-top:10px;" id="vote<?php echo $model->id; ?>">
  <span class="c-gray f-11" style="margin-right:5px;">Полезен ли обзор?</span> <span class="user_votes"><span onclick="toVoteArticle(<?php echo $model->id; ?>, 1);" class="user_pro"><i class="zmdi zmdi-thumb-up"></i></span> <span class="user_num"><?php echo $model->yes?$model->yes:' '; ?></span>  <span class="user_contra" onclick="toVoteArticle(<?php echo $model->id; ?>, 0);"><i class="zmdi zmdi-thumb-down"></i></span> <span class="user_contra-num"><?php echo $model->no?$model->no:' '; ?></span></span></div>
  <?php
  }
  ?>
  </div> 
<div class="woocommerce">
<div class="product">
<div class="woocommerce-tabs wc-tabs-wrapper">
<div id="tab-reviews" class="panel entry-content wc-tab" style="display: block;">
        <div id="reviews">
  <div id="comments">
<?php  $this->renderPartial('application.modules.comments.views.comment.new_comment_article_plain', array(
    'model'=>$model,
    'themeUrl'=>$themeUrl,
  ));  ?>

</div>
<div id="review_form_wrapper">
      <div id="review_form">
        <div class="comment-respond" id="respond" style="padding:0 20px 5px 0px;">
        <?php 
       if(!Yii::app()->user->isGuest){ ?>
        <?php 
       $module = Yii::app()->getModule('comments');
        // Validate and save comment on post request
        $comment = $module->processRequestArticle($model);
        $form=$this->beginWidget('CActiveForm', array(
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
                                                ."        window.location.reload(); \n"
                                                ."      } else {\n"

                                                ."      }\n"
                                                ."    return false;\n"
                                                ."}\n"
                ),
      )); 
      
      ?>
      <div class="form-group fg-line green">
          <?php echo $form->textArea($comment,'text', array('style'=>'word-wrap: break-word; min-height: 70px; width: 100%; resize: none; overflow:hidden;', 'class'=>'form-control','placeholder'=>'Введите ваш текст...')); ?>
                <?php echo $form->error($comment,'text'); ?>
        </div>
        <div class="no-border-t text-left" style="border-top:0; margin-top:5px; margin-bottom:40px;">
        <button style="margin:0 20px 10px 0;" type="submit" class="btn-element btn btn-default-over pull-left"><span>КОММЕНТИРОВАТЬ</span></button>
      </div> 
      <?php $this->endWidget(); ?>
       <?php 
       } else { ?>
       <p class="must-log-in" style="margin-top:10px;margin-bottom:15px;">Чтобы оставить комментарий, необходимо <span style="cursor:pointer;" class="kl-login-box" data-toggle="modal" data-target="#login_modal">Войти</span></p>
       <?php } ?>
       </div><!-- #respond -->
       </div>
    </div>
<div class="clear"></div>
</div>
</div><!-- tab-reviews -->
</div><!-- woocommerce-tabs -->	
</div><!-- product -->
</div><!-- woocommerce -->	

	
<?php // $this->renderPartial('_article_aside_item',array('popular'=>$popular)); ?>
	



</div>

<?php
if(!empty($similar)){  ?>
<section style="padding-top:0px" class="zn_section eluid94fa47d7     zn_section--masked zn_section--relative section--no">
<div class="zn_section_size container">
<div class="row zn_columns_container zn_content " data-droplevel="1">
<div class="eluidf274ce36  col-md-12 col-sm-12    zn_sortable_content zn_content " data-droplevel="2">
<div class="latest_posts latest_posts--style4 eluid1ac6708b  latestposts4--light element-scheme--light kl-style-2 ">
<div class="row ">
<?php
$lasts_articles = array_slice($similar, 0, 3);
if(!empty($lasts_articles)){ 
$this->renderPartial('_section_last_art_el_short',array('lasts'=>$lasts_articles));
}
$lasts_articles_plus = array_slice($similar, 3, 5);
if(!empty($lasts_articles_plus)){ 
$this->renderPartial('_section_last_art_el_plus',array('lasts'=>$lasts_articles_plus));
}
?>
</div>
</div>
</div>
</div>
</div>

</section>
<?php } ?>

</div>


 <?php

$scriptAdd = "
$(document).ready(function(){
	
   
})";
Yii::app()->clientScript->registerScript("article", $scriptAdd, CClientScript::POS_END);
?>