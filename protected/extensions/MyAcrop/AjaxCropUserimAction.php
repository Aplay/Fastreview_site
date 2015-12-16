<?php
Yii::import('ext.image.Image');
Yii::import('application.modules.users.models.UserImages');

class AjaxCropUserimAction extends CAction
{
        
        
        public function run()
        {
            
            if (!Yii::app()->user->isGuest){
                $im = Yii::app()->request->getPost('im');
                $inbase = UserImages::model()->findByAttributes(array('name'=>$im), 'user_id='.Yii::app()->user->id);
                if (!$inbase) {
                    throw new CHttpException(404, 'Ошибка.');
                    return true;
                } 
                $rootPath=Yii::app()->getBasePath().'/..';
                $fullpath = $rootPath.'/uploads/userimages/'.$im;
                if(file_exists($fullpath)){
                    
                    $image = new Image($fullpath);
                    $image->crop(Yii::app()->request->getPost('w'),Yii::app()->request->getPost('h'),Yii::app()->request->getPost('y'),Yii::app()->request->getPost('x'));
                    $image->save();
                    MCleaner::cleanTempImg($im);
                    $inbase->getUrl('100x75');
                    echo Yii::app()->request->getPost('imbox');
                } 
            }
        }
       
}