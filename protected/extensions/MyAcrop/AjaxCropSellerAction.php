<?php
Yii::import('ext.image.Image');
Yii::import('application.modules.seller.models.Shops');

class AjaxCropSellerAction extends CAction
{
        public function run()
        {
            if (!Yii::app()->user->isGuest){
                $im = Yii::app()->request->getPost('im');
                $imbox = Yii::app()->request->getPost('imbox');
                $inbase = Shops::model()->findByAttributes(array($imbox => $im), 'real_admin='.Yii::app()->user->id);
                $shop = new Shops;
                if (!$inbase) {
                    throw new CHttpException(404, 'Ошибка.');
                    return true;
                }
                $rootPath=Yii::app()->getBasePath().'/..';
                $fullpath = $rootPath.'/uploads/shops/'.$inbase->id.'/photos/'.$im;
                if(file_exists($fullpath)){
                    $image = new Image($fullpath);
                    $image->crop(Yii::app()->request->getPost('w'),Yii::app()->request->getPost('h'),Yii::app()->request->getPost('y'),Yii::app()->request->getPost('x'));
                    $image->save();
                    MCleaner::cleanTempImg($im, 'shop');
                    $inbase->getUrl('220x114','resize',false,$im);
                    echo Yii::app()->request->getPost('imbox');
                }
            }
        }

}