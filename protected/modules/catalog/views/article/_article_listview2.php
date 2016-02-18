<?php  

$url = Yii::app()->createAbsoluteUrl('/catalog/article/item', array('id'=>$data->id,'itemurl'=>$data->url,'dash'=>'-'));

if(!empty($data->logotip))
{
 // $src = $data->getOrigFilePath().$data->logotip; 
 $src = $data->getUrl('320x320',false,false,'logotip');
 } else {
 	$src = '/img/cap.gif';
 } ?>

 <a class="article-bg-container" href="<?php echo $url; ?>" >
	<div class="article-bg" style="background-image:url('<?php echo $src; ?>');"></div>
	<div class="caption" style="color:#5e5e5e;background-color:#fff;padding:12px 20px; height:60px;width:100%;overflow:hidden;">
	<table  style="width:100%;height:36px;">
		<tr><?php 
		$num =  substr($data->title, 0, strspn($data->title, "0123456789"));
		if($num){
			echo '<td  style="height:36px;overflow:hidden;font-size:35px;font-weight:300;vertical-align:middle;line-height:35px;">'.$num.'</td>';
			echo '<td  style="height:36px;overflow:hidden;padding-left:10px;vertical-align:middle;line-height:1.3em;"><div class="article_caption_title" style="margin-top:-1px;">' . ltrim($data->title, $num).'</div></td>';
			echo '';
	    } else 
	    	echo '<td  style="height:36px;overflow:hidden;vertical-align:middle;"><div class="article_caption_title">'.$data->title.'</div></td>'; ?>
	</tr></table></div>
</a>




