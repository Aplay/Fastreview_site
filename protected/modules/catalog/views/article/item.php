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
if(!empty($model->logotip)){
	$im = $model->getUrl('420x280');
	$image = Yii::app()->createAbsoluteUrl($model->getUrl('200x100xC','adaptiveResizeQuadrant'));
}     
?>
<div class="row m-t-20">
<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-2">
<div style="margin-bottom:20px;font-size:25px;font-weight:bold;padding-left:30px;"><?php 
echo CHtml::encode($model->title);
?></div>
	<div  class="itemListView clearfix eBlog kl-blog--light">
<?php
	$im = '/img/cap_850x565.gif';

if(!empty($model->logotip))
{
 // $src = $model->getOrigFilePath().$model->logotip; 
 $model->setCap($im);
 $im = $model->getUrl('850x565',false,false,'logotip');
 } 
  ?>
<div class="itemContainer post-<?php echo $model->id; ?> featured-post" style="margin-bottom:0;">
<div class="zn_full_image" style="margin-bottom:0;">
<img alt="" src="<?php echo $im; ?>"  class="zn_post_thumbnail">
</div>
<div class="itemFeatContent">
    <div class="itemFeatContent-inner">
        <div class="itemHeader">
            
            <div class="post_details kl-font-alt">
            <span class="catItemDateCreated">
            <br>
                <?php echo Yii::app()->dateFormatter->format('dd MMMM, yyyy',  date('Y-m-d H:i:s', strtotime($model->created_date))); ?>                                    </span>
                <span class="catItemAuthor"> Автор: <span class="c-white"><?php 
                  $user_url = Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$model->authorid->username));
                  echo CHtml::link($model->authorid->showname,$user_url,array('class'=>'nocolor')); 
                  ?></span>
               
                </span>
            </div>
            <!-- end post details -->
        </div>
               
        
        <div class="itemComments">
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
<div class="">
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
       <p class="must-log-in" style="margin-top:10px;margin-bottom:15px;">Чтобы оставить комментарий, необходимо <a class="kl-login-box" href="#login_panel">Войти</a></p>
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