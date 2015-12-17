<?php
echo ($data->city)?$data->city->title:'';
if($data->street) { 
echo ', '.$data->street;
}
 if($data->dom) { 
 	echo ', '.$data->dom; 
 }
 ?>