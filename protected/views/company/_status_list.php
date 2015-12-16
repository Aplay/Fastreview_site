<?php 
echo '<br>';
echo '<span style="color:gray">'.Yii::app()->dateFormatter->format('d MMMM yyyy HH:mm', $data->created_date).'</span><br>';
echo $data->getFullText();
echo '<br>';
?>