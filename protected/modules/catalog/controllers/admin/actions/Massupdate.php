<?php

class Massupdate extends CAction {

    public function run() {

        

    	$model = new Orgs('search');
        $model->unsetAttributes();  // clear any default values

        $model_search = null;
        $criteria=new CDbCriteria;
        $criteria->select = 't.id';
        

        if(isset($_GET['Orgs'])){
            

            $model->attributes=$_GET['Orgs'];

            if(isset($model->status_org)){

                    if($model->status_org == 1) {
                        $model->status_org = Orgs::STATUS_ACTIVE;
                        $model_search_ids = $model->pureactive()->search(array(),$criteria,false);
                      //  $model_search = $model->active()->search();
                    } else if($model->status_org == 0) {
                        $model->status_org = Orgs::STATUS_NOT_ACTIVE;
                        $model_search_ids = $model->notactive()->search(array(),$criteria,false);
                       // $model_search = $model->notactive()->search();
                    }  else {
                        $model->status_org = null;
                        $model_search_ids = $model->search(array(),$criteria,false);
                       // $model_search = $model->search();
                    }
                } 
        } else {
            $model->status_org = null;
            $model_search_ids = $model->search(array(),$criteria,false);
      
        }
        $keys = $post = array();
        $masstotalall = 0;
        $error = false;

        if(!empty($_POST)){
            $post = $_POST;
            Yii::app()->session['masskeysPost'] = serialize($post);
        }
        if(!isset(Yii::app()->session['masskeys']) && !empty($model_search_ids->data)){
            foreach ($model_search_ids->data as $val) {
                $keys[] = $val->id;
            }
            $ser_id = serialize($keys);
            Yii::app()->session['masskeys'] = $ser_id;
            // hash
            Yii::app()->session['masskeysHash'] = Yii::app()->request->requestUri;
            Yii::app()->session['masstotal'] = 0;
            $masstotalall = count($keys);
            Yii::app()->session['masstotalall'] = $masstotalall;

        } else if(isset(Yii::app()->session['masskeys'])) {
             
            if(isset(Yii::app()->session['masskeysHash']) && Yii::app()->session['masskeysHash'] == Yii::app()->request->requestUri){
                $keys = unserialize(Yii::app()->session['masskeys']);
                $post = unserialize(Yii::app()->session['masskeysPost']);
                $masstotalall = Yii::app()->session['masstotalall'];
            } else {

                unset(Yii::app()->session['masskeysHash']);
                unset(Yii::app()->session['masskeys']);
                unset(Yii::app()->session['masskeysPost']);
                unset(Yii::app()->session['masstotal']);
                unset(Yii::app()->session['masstotalall']);
                $this->getController()->refresh();
                Yii::app()->end();
            }
            
        }

        if(!empty($keys)){

            $criteria=new CDbCriteria;
            $criteria->condition='id IN ('.implode(',', $keys).')';
            $criteria->order = 'id';
            $model_search = new CActiveDataProvider('Orgs',array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize'=>5,
                )
            ));

        }
        
        // Записать id-шники в сессию, т.к. при обновлении страницы выборка может измениться после обновления данных.
        
        $cnt = 0;

        if(isset($post['Orgs'])){
           // VarDumper::dump($post); die(); // Ctrl + X    Delete line
            if($post['massaction'] == 1){ // Дополнить
                $action  =  1;

            } else if($post['massaction'] == 2) { // Заменить
                $action  =  2;
            }
            $mass = $massatr = array();
            if(isset($post['Orgs']['mass']))
                $mass = $post['Orgs']['mass'];
            if(!empty($mass)){
                foreach ($mass as $key => $value) {
                    if($value==1){
                        $massatr[] = $key;
                    }
                }
            }

            if(!empty($massatr) && ($action == 1 || $action == 2)){

                if(!empty($model_search->data)){
                    foreach ($model_search->data as $key => $model) {
                        foreach ($massatr as $attr) {
                            if($attr == 'title'  || $attr == 'synonim' || $attr == 'url'  || $attr == 'description' || $attr == 'street' || $attr == 'dom' || $attr == 'address_comment' || $attr == 'fax' || $attr == 'vkontakte' || $attr == 'facebook' || $attr == 'twitter' || $attr == 'instagram' || $attr == 'youtube'){
                                if($action == 1){
                                    $model->$attr = $model->$attr.$post['Orgs'][$attr];
                                } else if($action == 2) {
                                    $model->$attr = $post['Orgs'][$attr];
                                }
                            } else if($attr == 'status_org'){
                                $model->status_org = $post['Orgs']['status_org'];
                            }  else if($attr == 'categories_ar' && $action == 2){ // заменить категории
                               
                                if(!$post['Orgs']['categories_ar'] || !is_array($post['Orgs']['categories_ar'])){
                                    $cat_ar = array();
                                } else {
                                    $cat_ar = $post['Orgs']['categories_ar'];
                                }
                                $model->setCategories($cat_ar);
                            } else if($attr == 'categories_ar' && $action == 1){ // добавить категории
                               
                                if(!$post['Orgs']['categories_ar'] || !is_array($post['Orgs']['categories_ar'])){
                                    $cat_ar = array();
                                } else {
                                    $cat_ar = $post['Orgs']['categories_ar'];
                                }
                                $model->setCategories($cat_ar,true);
                            }  else if($attr == 'city_id'){ // город
                                 $model->$attr = $post['Orgs'][$attr];
                            } else if($attr == 'tmpLogotip'){ // заменить картинки
                                if(isset($post['Orgs']['tmpLogotip']) && !empty($post['Orgs']['tmpLogotip'])){
                                    $model->tmpLogotip = $post['Orgs']['tmpLogotip'];
                                    $model->addDropboxLogoFiles($this->getController()->uploadlogosession, false);
                                } 
                                
                            } else if($attr == 'worktime'){
                                $open_door = $close_door = $break_door = $endbreak_door = array();
                                if(isset($post['open_door']) && !empty($post['open_door'])){ $open_door = $post['open_door']; }
                                if(isset($post['close_door']) &&  !empty($post['close_door'])){ $close_door = $post['close_door']; }
                                if(isset($post['break_door']) && !empty($post['break_door'])){ $break_door = $post['break_door']; }
                                if(isset($post['endbreak_door']) &&  !empty($post['endbreak_door'])){ $endbreak_door = $post['endbreak_door']; }
                                OrgsWorktime::setWorktime($model->id, $open_door, $close_door, $break_door, $endbreak_door);
                            } else if($attr == 'phones' && $action == 2){ // заменить телефоны
                                $model->setPhones($post['Orgs']['phones'], $post['Orgs']['phone_comments']);
                            } else if($attr == 'phones' && $action == 1){ // добавить телефоны
                                $model->setPhones($post['Orgs']['phones'], $post['Orgs']['phone_comments'],true);
                            } else if($attr == 'http' && $action == 2){ // заменить сайты
                                $model->setHttp($post['Orgs']['http'], $post['Orgs']['http_comments']);
                            } else if($attr == 'http' && $action == 1){ // добавить сайты
                                $model->setHttp($post['Orgs']['http'], $post['Orgs']['http_comments'],true);
                            } else if($attr == 'video' && $action == 2){ // заменить видео
                                $model->setHttp($post['Orgs']['video'], $post['Orgs']['video_comments'], false, OrgsHttp::TYPE_VIDEO);
                            } else if($attr == 'video' && $action == 1){ // добавить видео
                                $model->setHttp($post['Orgs']['video'], $post['Orgs']['video_comments'],true, OrgsHttp::TYPE_VIDEO);
                            }
                            
                        }
                        

                        if(!$model->save()){
                            $this->getController()->addFlashMessage($model->errors,'error');
                            $error = true;
                            break;
                        } else {
                            if(!empty($keys)){
                                if(($key = array_search($model->id, $keys)) !== false) {
                                    unset($keys[$key]);
                                }
                            }
                            $cnt++;
                        }

                    }
                    
                    
                    

                    if($error){
                        $this->clearTmpImage($post);
                        Yii::app()->session['masstotal'] = Yii::app()->session['masstotal'] + $cnt;
                        $this->getController()->addFlashMessage('Операция завершена, обработано '.Yii::app()->session['masstotal'].' организаций.','error');
                        unset(Yii::app()->session['masskeys']);
                        unset(Yii::app()->session['masskeysHash']);
                        unset(Yii::app()->session['masskeysPost']);
                        unset(Yii::app()->session['masstotal']);
                        unset(Yii::app()->session['masstotalall']);

                        $this->getController()->refresh();
                        Yii::app()->end();
                    }
                    if(!empty($keys)){
                        $sleeper = 3;

                        $ser_id = serialize($keys);
                        Yii::app()->session['masskeys'] = $ser_id;
                        Yii::app()->session['masstotal'] = Yii::app()->session['masstotal'] + $cnt;
                        $this->getController()->addFlashMessage('Операция еще не завершена, обработано '.Yii::app()->session['masstotal'].' организаций.','success');
                        
                        echo '<META HTTP-EQUIV=Refresh CONTENT="'.$sleeper.'; URL='.Yii::app()->createUrl(Yii::app()->request->requestUri).'">';
                       // exit;
                      //  $this->getController()->redirect('/admin_cat/catalog/company/massupdate?Orgs[title]=&Orgs[rubric_title]=&Orgs[per_from]=&Orgs[per_to]=&Orgs[log_user]=&Orgs[status_org]=3&Orgs[city_search]=Москва&Orgs[street]=&Orgs[dom]=');
                      //  $this->getController()->refresh();

                        
                    } else {
                        $this->clearTmpImage($post);
                        Yii::app()->session['masstotal'] = Yii::app()->session['masstotal'] + $cnt;
                        $this->getController()->addFlashMessage('Операция успешно завершена, обработано '.Yii::app()->session['masstotal'].' организаций.','success');
                        unset(Yii::app()->session['masskeys']);
                        unset(Yii::app()->session['masskeysHash']);
                        unset(Yii::app()->session['masskeysPost']);
                        unset(Yii::app()->session['masstotal']);
                        unset(Yii::app()->session['masstotalall']);
                        $this->getController()->refresh();
                        Yii::app()->end();
                        
                    }
                }
            }
            
        } else {
            
        }
        
    	$this->getController()->render('massupdate', array(
            'dataProvider'=>$model_search, 'masstotalall'=>$masstotalall
        ));
    }

    public function clearTmpImage($post){
        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(isset($post['Orgs']['tmpLogotip']) && !empty($post['Orgs']['tmpLogotip'])){
            $files = $post['Orgs']['tmpLogotip'];

            if(Yii::app()->session->itemAt($this->getController()->uploadlogosession)){
                $dataSession = Yii::app()->session->itemAt($this->getController()->uploadlogosession);
                foreach($files as $fileUploadName){
                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {
                                    unlink($folder.$value);
                                }
                            }
                        }
                    }
                }
            }
        }
        Yii::app()->session->remove($this->getController()->uploadlogosession);
        return;
    }
}
?>