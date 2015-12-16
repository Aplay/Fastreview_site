<?php

class Deletefile extends CAction {

    public function run() {

        $controller = $this->getController();
       
    	$id = (int)$id;
        // check that you have access to this note
        $file = OrgsImages::model()->findByPk($id);
        if(isset($file->organization)){
           // $pin = $controller->_loadModel($file->pinboard->id);
            // remove it from disk also in model issueFile
           // if($file->delete()) {

                if(isset(Yii::app()->session['deleteFiles'])){
                	$sessAr = unserialize(Yii::app()->session['deleteFiles']);
                	if(isset($sessAr['id']) && $sessAr['id'] == $file->organization->id && isset($sessAr['files']) && is_array($sessAr['files']))
                	{
                		$files = $sessAr['files'];
                		if(!in_array($id, $files))
                		{
                			$files[] = $id;
                		}
                		$sessAr = serialize(array('id'=>$file->organization->id,'files'=>$files));
                		Yii::app()->session['deleteFiles'] = $sessAr;
                	
                	}
                } 
                else
                {
                	$sessAr = serialize(array('id'=>$file->organization->id,'files'=>array($id)));
                	Yii::app()->session['deleteFiles'] = $sessAr;
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