<?php
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('id'=>$data->id,'itemurl'=>$data->url,'dash'=>'-'));

$im = '/img/cap_850x565.gif';

if(!empty($data->logotip))
{
 // $src = $data->getOrigFilePath().$data->logotip; 
 $data->setCap($im);
 $im = $data->getUrl('850x565',false,false,'logotip');
 } 
  ?>
<div class="itemContainer post-<?php echo $data->id; ?> featured-post" style="margin-bottom:0;">
<a style="position:absolute;z-index:2;top:0;height:80%;width:100%" href="<?php echo $url; ?>">
</a>
<div class="zn_full_image" style="margin-bottom:0;">
<img alt="" src="<?php echo $im; ?>" class="zn_post_thumbnail">
</div>
<div class="itemFeatContent">
    <div class="itemFeatContent-inner">
        <div class="itemHeader">
            <h3 class="itemTitle">
                <a href="<?php echo $url; ?>">
                <?php echo CHtml::encode($data->title); ?>
                </a>
            </h3>
            <div class="post_details kl-font-alt">
            <span class="catItemDateCreated">
                <?php echo Yii::app()->dateFormatter->format('dd MMMM, yyyy',  date('Y-m-d H:i:s', strtotime($data->created_date))); ?>                                    </span>
                <span class="catItemAuthor"> Автор: <span class="c-white"><?php 
                
                $user_url = Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>$data->authorid->username));
                echo CHtml::link($data->authorid->showname,$user_url); 
                ?></span>
               
                </span>
            </div>
            <!-- end post details -->
        </div>   
        
        <div class="itemComments">
            <?php 
            $cnt_comments = count($data->comments);
            if($cnt_comments){
                $com_url = $url.'#reviews';
                $txt_comment = $cnt_comments.' '.Yii::t('site', 'comments|commentss', $cnt_comments);
                echo CHtml::link($txt_comment, $com_url, array('class'=>'kl-font-alt'));
            } else {
                $txt_comment = 'Нет комментариев';
                echo CHtml::link($txt_comment, $url, array('class'=>'kl-font-alt'));

            } ?>

        </div>
        <!-- item links -->
        <div class="clearfix"></div>
    </div>
    </div>
    
</div>
<div class="itemContainer">
<div class="trunk_3">
<?php 
echo $data->description;
 ?>
 </div>
<div class="itemBottom  clearfix" style="border:none;">
        <div class="itemTagsBlock kl-font-alt">
                            <?php 
        if(!empty($data->categories)) { 
            foreach ($data->categories as $tag) { 
                    
             $turl = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$tag->url)); 
        
            ?>
            <a rel="tag" href="<?php echo $turl; ?>"><?php echo $tag->title; ?></a>
            <?php }

            } ?>
          </div><!-- end tags blocks -->
    </div>
</div>
 