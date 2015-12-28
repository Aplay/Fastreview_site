<?php  
$type = $v = $url = '';

if (strpos($data->site, 'youtube') > 0) {
	    $query_str = parse_url($data->site, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        if(!empty($query_params) && isset($query_params['v'])){
			$v = $query_params['v'];
		}
        $type = 'type="video/youtube"';
        $url = 'http://www.youtube.com/embed/'.$v.'?controls=1&enablejsapi=0';
    } elseif (strpos($data->site, 'vimeo') > 0) {
        $type = 'type="video/vimeo"';
        $v = substr(parse_url($data->site, PHP_URL_PATH), 1);
        $url = 'http://player.vimeo.com/video/'.$v.'?byline=0&amp;portrait=0';
    } 

?>

<div class="article-bg-container video">
<div class="embed-responsive embed-responsive-4by3">
<iframe class="embed-responsive-item"  src="<?php echo $url; ?>">
	
</iframe>
 </div>   	

	<div class="caption hide" style="color:#5e5e5e;background-color:#fff;padding:12px 20px; height:60px;width:100%;overflow:hidden;">
	<table  style="width:100%;height:36px;">
		<tr><?php 
		$num =  substr($data->description, 0, strspn($data->description, "0123456789"));
		if($num){
			echo '<td  style="height:36px;overflow:hidden;font-size:35px;font-weight:300;vertical-align:middle;line-height:35px;">'.$num.'</td>';
			echo '<td  style="height:36px;overflow:hidden;padding-left:10px;vertical-align:middle;line-height:1.3em;"><div class="article_caption_title" style="margin-top:-1px;">' . ltrim($data->description, $num).'</div></td>';
			echo '';
	    } else 
	    	echo '<td  style="height:36px;overflow:hidden;vertical-align:middle;"><div class="article_caption_title">'.$data->description.'</div></td>'; ?>
	</tr></table></div>
	</div>





