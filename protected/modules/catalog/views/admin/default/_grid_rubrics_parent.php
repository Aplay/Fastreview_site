<?php

$parent = $data->getParent();
if($parent){
	echo $parent->title;
} else {
	echo '';
}
?>