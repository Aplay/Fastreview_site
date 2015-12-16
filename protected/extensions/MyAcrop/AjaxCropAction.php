<?php
Yii::import('ext.image.Image');

class AjaxCropAction extends CAction
{
        
        
        public function run()
        {

                $rootPath=Yii::app()->getBasePath().'/..';
                $fullpath = $rootPath.'/uploads/tmp/'.Yii::app()->request->getPost('im');
                if(file_exists($fullpath)){
                    
                    $image = new Image($fullpath);
                    $image->crop(Yii::app()->request->getPost('w'),Yii::app()->request->getPost('h'),Yii::app()->request->getPost('y'),Yii::app()->request->getPost('x'));
                    $image->save();
                    echo Yii::app()->request->getPost('imbox');
                } 
                
        }
       
}
