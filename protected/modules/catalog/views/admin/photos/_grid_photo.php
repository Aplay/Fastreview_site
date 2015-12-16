<?php
$src = Yii::app()->createAbsoluteUrl('/file/file/company',array('id'=>$data->id));
echo CHtml::link(CHtml::image($src,'',array('style'=>'width:155px;height:auto;')), $src ,array('data-lightbox'=>'lb-'.$data->id));

OrgsImages::model()->updateByPk($data->id,array('verified'=>1));
?>
               