<?php
$url = '#'; $im = '/img/cap_850x565.gif';
$folder = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
$folderLink= '/uploads/tmp/';
$categories = $data->categories_ar;

$user_avatar = Yii::app()->user->getAvatar();
$user_name = '<a class="nocolor" href="'.Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username)).'">'.Yii::app()->user->getShowname().'</a>';
$avatar = '<a href="'.Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username)).'"><img alt="" src="'.$user_avatar.'" class="lv-img-md" /></a>';


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
           <div class="media m-t-15 m-b-25">
            <div class="pull-left">
              <?php echo $avatar; ?>
            </div>
            <div class="media-body">
              <p class="nocolor f-15 f-500"><?php echo $user_name; ?> 
              <br>
              <span class="c-gray f-12" ><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $data->created_date); ?></span></p>
            </div>
            </div>
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