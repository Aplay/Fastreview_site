<?php
$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('id'=>$data->id,'itemurl'=>$data->url,'dash'=>'-'));

if(!empty($data->logotip))
{
 // $src = $data->getOrigFilePath().$data->logotip; 
 $src = $data->getUrl('460x300',false,false,'logotip');
 } else {
 	$src = '/img/cap.gif';
 } ?>
 <div class="itemContainer post-<?php echo $data->id; ?>">
<div class="itemHeader">
    <h3 class="itemTitle">
        <a href="<?php echo $url; ?>">
       <?php echo CHtml::encode($data->title); ?></a>
    </h3>

    <div class="post_details kl-font-alt">
        <span class="catItemDateCreated">
          <?php echo Yii::app()->dateFormatter->format('dd MMMM, yyyy',  date('Y-m-d H:i:s', strtotime($data->created_date))); ?>
          </span>
        <span class="hide catItemAuthor">by <a rel="author" title="Posts by danut" href="http://kallyas.net/demo/author/danut/">danut</a>
        </span>
    </div>
    <!-- end post details -->
</div>
<!-- end itemHeader -->

                        <div class="itemBody">
                            <div class="itemIntroText">
                                <div class="zn_post_image">
                                <a class="pull-left" href="<?php echo $url; ?>">
<img height="300" width="460"  class="attachment-460x320 wp-post-image" src="<?php echo $src; ?>"></a>
</div>
<div class="trunk_3">
<?php 
echo $data->description;
 ?>
 </div>
</div>
                            <!-- end Item Intro Text -->
                            <div class="clear"></div>
                            <div class="itemBottom clearfix">

                            <div class="itemTagsBlock kl-font-alt">
                            <?php 
        if(!empty($data->categories)) { 
            foreach ($data->categories as $tag) { 
                    
             $turl = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$tag->url)); 
        
            ?>
            <a rel="tag" href="<?php echo $turl; ?>"><?php echo $tag->title; ?></a>                                       <div class="clear"></div>
            <?php }

            } ?>
                            </div><!-- end tags blocks -->
                              <div class="itemReadMore">
                                    <a href="<?php echo $url; ?>" class="btn btn-fullcolor text-uppercase">Далее</a>
                                </div>
                                <!-- end read more -->
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- end Item BODY -->

                        <ul class="hide itemLinks kl-font-alt clearfix">
                            <li class="itemCategory">
                                <span data-zn_icon="" data-zniconfam="glyphicons_halflingsregular"></span>
                                <span>Published in</span>
                                <a rel="category tag" href="http://kallyas.net/demo/category/mobile/">Mobile</a>                            </li>
                        </ul>
                        <div class="hide itemComments">
                            <a class="kl-font-alt" href="http://kallyas.net/demo/enthusiastically-administrate-ubiquitous/">No Comments</a>
                        </div>
                        <!-- item links -->
                        <div class="clear"></div>

                    </div>






