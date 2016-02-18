<?php  

$themeUrl = Yii::app()->theme->baseUrl;
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce-layout.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/woocommerce.css');


Yii::app()->clientScript->registerCssFile($themeUrl . '/css/kl-woocommerce_single.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/dp-styles.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/style.css');

$thisUrl = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('id'=>$model->id,'itemurl'=>$model->url,'dash'=>'-'));

//Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:site_name"));
Yii::app()->clientScript->registerMetaTag($model->title, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($this->pageDescription, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag('article', null, null, array('property' => "og:type"));
Yii::app()->clientScript->registerMetaTag($thisUrl, null, null, array('property' => "og:url"));

$im = '';
$image = Yii::app()->createAbsoluteUrl('/mstile-310x310.png'); 
if(!empty($model->logotip)){
	$im = $model->getUrl('420x280');
	$image = Yii::app()->createAbsoluteUrl($model->getUrl('200x100xC','adaptiveResizeQuadrant'));
}     
?>

<section id="content" class="site-content">
<div class="container">
<div class="row">
	<div class="right_sidebar col-md-9">
	<div id="th-content-post">
	<div id="post-<?php echo $model->id; ?>" class="post-<?php echo $model->id; ?> post type-post status-publish format-standard has-post-thumbnail hentry category-mobile category-technology tag-build tag-experiences tag-flexible">
	<h1 class="page-title"><?php echo CHtml::encode($model->title); ?></h1>
		<div class="itemView clearfix eBlog kl-blog--light">
			<div class="itemHeader">
            <div class="post_details kl-font-alt">
                <span class="itemAuthor">
                  Автор:  <span><?php echo $model->authorid->showname; ?></span>
                </span>
                <span class="infSep"> / </span>
                <span class="itemDateCreated"><span class="glyphicon glyphicon-calendar"></span> 
                <?php echo Yii::app()->dateFormatter->format('dd MMMM, yyyy',  date('Y-m-d H:i:s', strtotime($model->created_date))); ?></span>
                 <?php 
        if(!empty($model->categories)) { ?>
                <span class="infSep"> / </span>
                <span class="itemCommentsBlock"></span>
                <span class="itemCategory">
                    <span class="glyphicon glyphicon-folder-close"></span>
                    Тэги</span>
                    <?php 
                $tags = $tags2 = ''; 
                foreach ($model->categories as $tag) { 
                	
                	$turl = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$tag->url)); 
        
                	$tags .= '<a rel="category tag" href="'.$turl.'">'.$tag->title.'</a>, ';
                    $tags2 .= '<a rel="tag" href="'.$turl.'">'.$tag->title.'</a>, ';
               }

               if(!empty($tags)){
               	$tags = rtrim($tags, ', ');
               	$tags2 = rtrim($tags2, ', ');
               }
               echo $tags;
                ?>           

        	<?php } ?>
        	</div>
        	</div>
        	<div class="itemBody">
            <!-- Blog Image -->
            <?php if(!empty($im)){ 
$orig = $model->getOrigFilePath().$model->logotip;
            ?>
            <a data-lightbox="image" class="hoverBorder pull-left zn-bg-post-img zn-bg-post--default-view" href="<?php echo $orig; ?>" >
            
            <img alt="" src="<?php echo $im; ?>">
            
            </a>
            <?php } ?>
            <!-- Blog Content -->
          <?php echo nl2br(CHtml::encode($model->description)); ?>

        	</div>
        	<div class="clear"></div>
        	<div class="itemSocialSharing clearfix">
        <?php $this->renderPartial('application.views.common._share',array('thisUrl'=>$thisUrl,'image'=>$image)); ?>
            <div class="clear"></div>
            </div>
        <?php if(!empty($tags2)){ ?>
            <div class="itemTagsBlock kl-font-alt">
                <span>Тэги:</span>
                <?php echo $tags2; ?>               
                <div class="clear"></div>
            </div>
         <?php } ?>
            <div class="clear"></div>
            <div class="post-author">
            <div class="author-avatar">
                <img height="100" width="100" class="avatar avatar-100 photo" 
                
                src="<?php echo $model->authorid->getAvatar(); ?>" alt="">            </div>
            <div class="author-details">
                <h4><?php echo $model->authorid->showname; ?></h4>
                            </div>
            </div>
            <div class="clear"></div>
   <?php
$criteria = new CDbCriteria;
 $criteria->with = array(

 	'images',
 	'organization'=>array(
 		// 'condition'=>'organization.article != '.$model->id
 		)
 	);
 $criteria->condition = 't.id != '.$model->id .' and t.part is null';
 $criteria->limit = 3;
 $dataProvider = new CActiveDataProvider(Article::model()->active(), array(
        'criteria' => $criteria,
        'sort'=>array(
            'defaultOrder' => 't.created_date DESC',
        ),
        'pagination' => false,
    ));

if(isset($dataProvider) && !empty($dataProvider->totalItemCount)){ ?>
            <div class="related-articles">
            	<h3 class="rta-title">What you can read next</h3>
            	<div class="row">
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.catalog.views.article._article_listview2',
    'id'=>'article_listview',       // must have id corresponding to js above
    'itemsCssClass'=>'item-article-list',
    'template'=>"{items}",

    
)); ?>
            	</div>
            </div>
<?php } ?>
		</div><!-- itemView -->
	</div><!-- post -->
	<div class="comment-form-wrapper">
		<?php  $this->renderPartial('application.modules.comments.views.comment.new_comment_article', array(
		'model'=>$model,
		'themeUrl'=>$themeUrl,

	));  ?>
	</div>
	</div>
	</div>
	
<?php $this->renderPartial('_article_aside',array('popular'=>$popular)); ?>
	
</div>
</div>
</section>




 <?php

$scriptAdd = "
$(document).ready(function(){
	
   
})";
Yii::app()->clientScript->registerScript("article", $scriptAdd, CClientScript::POS_END);
?>