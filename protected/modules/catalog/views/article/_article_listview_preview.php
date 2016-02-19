<?php
$url = '#'; $im = '/img/cap_850x565.gif';
$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
$categories = $data->categories_ar;



$file = $data->tmpLogotip;

if($file){

    if(Yii::app()->session->itemAt($this->uploadlogosession)){

        $folder='/uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

        $dataSession = Yii::app()->session->itemAt($this->uploadlogosession);

        foreach($dataSession as $key => $value){
            if($file == $key){
                
                $im = '/uploads/tmp/'.$value;

            }
        }
    }
} elseif($data->id){
$im = '/img/cap_850x565.gif';
if(!empty($data->logotip))
{
 // $src = $model->getOrigFilePath().$model->logotip; 
 $data->setCap($im);
 $im = $data->getUrl('850x565',false,false,'logotip');
 } 
 
} ?> 
<div class="col-sm-9 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div  class="itemListView clearfix eBlog kl-blog--light">
<div class="itemContainer post featured-post" style="margin-bottom:0;">
<div class="zn_full_image" style="margin-bottom:0;background:url('<?php echo $im; ?>') no-repeat center center; background-size: cover; width:100%;height: 400px;">
<!-- <img alt="" src="" class="zn_post_thumbnail"> -->
</div>
<div class="itemFeatContent">
    <div class="itemFeatContent-inner">
        <div class="itemHeader">
            <h3 class="itemTitle">
               
                <?php echo CHtml::encode($data->title); ?>
                
            </h3>
            <div class="post_details kl-font-alt">
            <span class="catItemDateCreated">
                <?php echo Yii::app()->dateFormatter->format('dd MMMM, yyyy',  date('Y-m-d H:i:s', strtotime($data->created_date))); ?>                                    </span>
                <span class="catItemAuthor"> Автор: <span class="c-white"><?php 
                
                $user_url = Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username));
                echo CHtml::link(Yii::app()->user->showname,$user_url,array('target'=>'_blank','class'=>'nocolor')); 
                ?></span>
               
                </span>
            </div>
            <!-- end post details -->
        </div>
       
 		
               
        
        <div class="hide itemComments">
            <?php 
                $txt_comment = 'Нет комментариев';
                echo CHtml::link($txt_comment, '#', array('class'=>'kl-font-alt'));
             ?>

        </div>
        <!-- item links -->
        <div class="clearfix"></div>
    </div>
    </div>
</div><!-- itemContainer -->
<div class="itemContainer" >
<div class="">
<?php 
echo $data->description;
 ?>
 </div>
 <div class="itemBottom  clearfix" style="border:none;">
        <div class="itemTagsBlock kl-font-alt">

          </div><!-- end tags blocks -->
    </div>
 </div><!-- itemContainer -->
</div>
</div>