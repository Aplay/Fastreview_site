<?php

$user = User::model()->findByPk($model['uploaded_by']);
$org = Objects::model()->findByPk($model['org']);
$url = Yii::app()->createAbsoluteUrl('/fastreview/item', array( 'id'=>$org->id, 'dash'=>'-', 'itemurl'=>$org->url));
if(!empty($user)){
	$user_avatar = $user->getAvatar();
	$user_name = $user->fullname;
} else {
	$user_avatar = '/img/avatar.png';
	$user_name = 'Аноним';
}
?>
<div class="media oblects_view_main">
<div class="pull-left">
<div class="iAvatar">
<img class="lv-img-lg" src="<?php echo $user_avatar; ?>" alt="">
	</div>
	
</div>
<div class="media-body">
<div class="iAuthor">
		<span style="color:#333;font-size:17px;font-weight:300;display:block;line-height:1.3em;"><?php echo $user_name; ?></span>
		<span class="c-gray f-11" style="display:block;"><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $model['date']); ?></span>
	</div>
<span style="font-weight:bold;"><?php 
echo CHtml::link($org->title,$url,array('class'=>'nocolor')); 
?></span>

<div class="lightbox row lastimages">
<?php 

/*
$sql = "SELECT from orgs_images WHERE org={$org->id} and uploaded_by={$model['uploaded_by']}
and date_uploaded >= '".date('Y-m-d 00:00:00', strtotime("-1 day"))."'";
$command = Yii::app()->db->createCommand($sql);
$data = $command->queryALL();*/
if(!empty($model['uploaded_by'])){
$ad = " and uploaded_by={$model['uploaded_by']} ";
} else {
$ad = " and uploaded_by is null ";
}

$criteria = new CDbCriteria;
// $criteria->condition = "org={$org->id} {$ad} and date_uploaded >= '".date('Y-m-d 00:00:00', strtotime("-1 day"))."'";
$criteria->condition = "object={$org->id} {$ad} and date_uploaded >= '".date('Y-m-d 00:00:00', strtotime($model['date']))."' and date_uploaded <= '".date('Y-m-d 23:59:59', strtotime($model['date']))."'";
$criteria->limit = 10;
$dp = new CActiveDataProvider('ObjectsImages', array(
        'criteria' => $criteria,
        'sort'=>array(
            'defaultOrder' => 'date_uploaded DESC',
        ),
        'pagination' => false,
    ));
if(!empty($dp->data))
{
	if(isset($addClass) && !empty($addClass)){

	} else {
		$addClass = 'col-xs-6';
	}
	foreach ($dp->data as $d) 
	{
		// VarDumper::dump($d); die(); // Ctrl + X	Delete line
		$src = $d->getOrigFilePath().$d->filename;
	?>
	<div class="<?php echo $addClass; ?> lightbo" data-src="<?php echo $src; ?>">
	<div class="lightbox-item p-item">		      
	<?php echo CHtml::image($d->getUrl('200x200xC','adaptiveResizeQuadrant'),'',array('class'=>'img-responsive')); ?>
	</div>
	</div> 
	<?php 
	} 
} 
?>
</div>
</div>
</div>