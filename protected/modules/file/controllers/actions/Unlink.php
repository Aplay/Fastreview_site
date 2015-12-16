<?php

class Unlink extends CAction {

    public function run() {

        $controller = $this->getController();
         
    	$fileName = $_POST['name'];

        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(Yii::app()->session->itemAt($controller->uploadsession)){
            $datas = Yii::app()->session->itemAt($controller->uploadsession);
            if(is_array($datas)){
                $mm = $datas;
                foreach($mm as $key => $value){
                    if($fileName == $key){
                        if(file_exists($folder.$value )) {
                            unlink($folder.$value);
                            unset($datas[$key]);
                        }
                    break;
                    }
                }
                Yii::app()->session->add($controller->uploadsession,$datas);
            }

        }
    }
}
?>