<?php

class Deleteobjectsfile extends CAction {

    public function run() {

        $controller = $this->getController();
       
    	$id = (int)Yii::app()->request->getQuery('id',null);
        // check that you have access to this note
        $file = ObjectsImages::model()->findByPk($id);
        if(isset($file->objectid)){
           // $pin = $controller->_loadModel($file->pinboard->id);
            // remove it from disk also in model issueFile
           // if($file->delete()) {

                if(isset(Yii::app()->session['deleteObjectsFiles'])){
                	$sessAr = unserialize(Yii::app()->session['deleteObjectsFiles']);
                	if(isset($sessAr['id']) && $sessAr['id'] == $file->objectid->id && isset($sessAr['files']) && is_array($sessAr['files']))
                	{
                		$files = $sessAr['files'];
                		if(!in_array($id, $files))
                		{
                			$files[] = $id;
                		}
                		$sessAr = serialize(array('id'=>$file->objectid->id,'files'=>$files));
                		Yii::app()->session['deleteObjectsFiles'] = $sessAr;
                	
                	}
                } 
                else
                {
                	$sessAr = serialize(array('id'=>$file->objectid->id,'files'=>array($id)));
                	Yii::app()->session['deleteObjectsFiles'] = $sessAr;
                }
                echo "[]";
                Yii::app()->end();
           // }
        }
        echo 'error';
        Yii::app()->end();
    }
}
?>