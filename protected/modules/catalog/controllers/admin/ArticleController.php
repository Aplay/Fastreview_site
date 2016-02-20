<?php
class ArticleController extends SAdminController {

	public $layout = '//layouts/admin';
    public $active_link = 'article';
    public $uploadsession = 'articlefiles';
    public $uploadlogosession = 'articlelogofiles';

    public $pageTitle = 'Статьи';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'rights',
            'ajaxOnly + delete, updatestatus,  upload, unlink, deletefile, deletelogofile, uploadlogo, unlinklogo, autocompletetitle,  autocompleteaddress, autocompleteloguser, autocompletecity, autocompletestreet, autocompletedom, getnewpart',
        );
    }

    public function actionIndex() {

        $this->pageTitle = 'Статьи';

        $model = new Article('search');
        $model->unsetAttributes();  // clear any default values


        $additionalCriteria = new CDbCriteria;
      //  $additionalCriteria->select = 't.id, t.title,t.status_org,t.updated_date,t.views_count';
        $additionalCriteria->condition = 't.part is null  and t.verified is true';

        if(isset($_GET['Article']))
            $model->attributes=$_GET['Article'];
 
        $model->status_org = null;
        $model_search = $model->search(array(),$additionalCriteria);


        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
        ));
    }

    public function actionNew_article() {

        $this->pageTitle = 'Новые cтатьи';
        $this->active_link = 'new_article';
        $model = new Article('search');
        $model->unsetAttributes();  // clear any default values


        $additionalCriteria = new CDbCriteria;
       // $additionalCriteria->select = 't.id, t.title,t.status_org,t.updated_date,t.views_count';
        $additionalCriteria->condition = 't.part is null and t.verified is false';

        if(isset($_GET['Article']))
            $model->attributes=$_GET['Article'];
 
        $model->status_org = null;
        $model_search = $model->search(array(),$additionalCriteria);


        $this->render('index',array(
            'model'=>$model, 'model_search'=>$model_search
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($new = false)
    {
        if ($new === true)
            $model = new Article;
        else
        {
            $id = (int)$_GET['id'];
            $model=$this->loadModel($id);
        }
        if($model->verified !== true){
            $this->active_link = 'new_article';
        }
        $firms = array(); $ar_url = array(); $error = false; $ids = array();

        if(!empty($_POST['Article']['article_ar'])){
        	$ars = $_POST['Article']['article_ar'];
        	if(isset($ars['firmname']) && !empty($ars['firmname'])){
        		foreach ($ars['firmname'] as $key => $value) {
        			$value = trim(strip_tags($value));
        			$firms[$key]['firmname'] = $value;
        		}
        	}

        	if(isset($ars['firmphotoh']) && !empty($ars['firmphotoh'])){
        		foreach ($ars['firmphotoh'] as $key => $value) {
        			$firms[$key]['firmphotoh'] = $value;
        		}
        	}
        	if(isset($ars['firmurl']) && !empty($ars['firmurl'])){
        		$ids = 0;
        		foreach ($ars['firmurl'] as $key => $value) {
        			$value = trim(strip_tags($value));
        			if(!empty($value)){
	        			$stri = stristr($value, Yii::app()->params['serverName']);
						if($stri === FALSE) {
							if (is_numeric($value)) { 
								$ids = (int) $value;
							} else {
								$this->addFlashMessage('Фирма '. ($key+1) .', url - неверная ссылка', 'error');
								$error = true;
								break;
							}
						} else {
							$id_url = explode('/',$stri);

							if(isset($id_url[1])){
								$ids = $id_url[1];
							}
						}
					}
					$ids_check = Objects::model()->findByPk($ids);
					if(!$ids_check){
						$this->addFlashMessage('Объект '. ($key+1) .', url - не найден', 'error');
						$error = true;
						break;
					}

        			$firms[$key]['firmurl'] = (int)$ids;

        		}
        	}
        	if(isset($ars['firmdescription']) && !empty($ars['firmdescription'])){
        		foreach ($ars['firmdescription'] as $key => $value) {
        			$value = trim(strip_tags($value));
        			$firms[$key]['firmdescription'] = $value;
        			if(!empty($value)){
        				
        			} else {
        				$this->addFlashMessage('Фирма '. ($key+1) .'  - нет описания', 'error');
						$error = true;
						break;
        			}

        		}
        	}
        } 

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model, 'orgs-form');
         
       /*  if(isset($_POST['Article']['articleorg_ar']) && 
         	!empty($_POST['Article']['articleorg_ar']) && 
            is_array($_POST['Article']['articleorg_ar'])) 
         { 
			$ar_url = $_POST['Article']['articleorg_ar'];

				for($i=0;$i<count($ar_url);$i++)
				{
					if(empty($ar_url[$i]))
						continue;
					$model->articleorg = $ar_url[$i];
					if($model->validate(array('articleorg')))
					{
						$ar_url[$i] = $model->articleorg;
					} else {
						$this->addFlashMessage($model->errors, 'error');
						$error = true;
						break;
					}
					
					$stri = stristr($ar_url[$i], Yii::app()->params['serverName']);
					if($stri === FALSE) {
						if (is_numeric($ar_url[$i])) { 
							$ids[] = (int) $ar_url[$i];
						} else {
							$this->addFlashMessage('Фирма, url - неверная ссылка', 'error');
							$error = true;
							break;
						}
					} else {
						$id_url = explode('/',$stri);

						if(isset($id_url[1])){
							$ids[] = $id_url[1];
						}
					}
				}
				
		 }
		 */
		 if(isset($_POST['Article'])){
		 	 
		 	// VarDumper::dump($_POST); die(); 
		}
		
        if(isset($_POST['Article']) && !$error)
        {

            $model->attributes=$_POST['Article'];
              
            if(!$model->categories_ar || !is_array($model->categories_ar) || (isset($model->categories_ar[0]) && empty($model->categories_ar[0]))){
                $model->categories_ar = array();
                $model->categorie = null;
            } else {
                $model->categorie = $model->categories_ar[0];
            }

            if($model->save()){
            
                
                
                $model->setCategories($model->categories_ar);
            //	$model->articleorg_ar = $ids;
           // 	$model->setArticleOrg($model->articleorg_ar);

                $model->addDropboxLogoFiles($this->uploadlogosession);
                Yii::app()->session->remove($this->uploadlogosession);

                $model->addDropboxFiles($this->uploadsession);
                Yii::app()->session->remove($this->uploadsession);

                $replace = $model->getOrigFilePath();
                $model->description = str_replace ( '/uploads/tmp/' , $replace , $model->description  );
                $model->save('false',array('description'));
                
                if(!empty($firms)){

              	    $dontDelete = array();
                	foreach ($firms as $key => $firm) {
                		 
                		 $found = Article::model()->findByAttributes(array(
							'part_org'=>$firm['firmurl'],
							'part'=>$model->id
						 ));
						 // если не было части - делаем
						 if(!$found){
	                		 $modelPart = new Article;
	                		 $modelPart->scenario = 'part';
	                		 $modelPart->title = $firm['firmname'];
	                		 $modelPart->description = $firm['firmdescription'];
	                		 $modelPart->part = $model->id;
	                		 $modelPart->part_order = $key;
	                		 $modelPart->part_org = $firm['firmurl'];
	                		 $modelPart->created_date = date('Y-m-d H:i:s');
	                		 if($modelPart->save()){
	                		 	//$modelPart->articleorg_ar = array($firm->firmurl);
	                		 	//$modelPart->setArticleOrg($modelPart->articleorg_ar);
	                		 	$modelPart->tmpLogotip = array($firm['firmphotoh']);
	                		 	$modelPart->addDropboxLogoFiles($this->uploadsession);
	                            
	                		 }
                		} else { // изменяем, если что-то поменялось.

		 					

							if($found->title != $firm['firmname'] or $found->description != $firm['firmdescription']
								or $found->part_order != $key){
								$found->title = $firm['firmname'];
								$found->description = $firm['firmdescription'];
								$found->part_order = $key;
								$found->save(false,array('title','description','part_order'));
							}
							$found->tmpLogotip = array($firm['firmphotoh']);
                			$found->addDropboxLogoFiles($this->uploadsession);
						}
                		$dontDelete[] = $firm['firmurl'];
                	}
                	// Удаляем все части, которых не было в массиве
					if(sizeof($dontDelete) > 0){
						$cr = new CDbCriteria;
						$cr->addNotInCondition('part_org', $dontDelete);

						Article::model()->deleteAllByAttributes(array(
							'part'=>$model->id,
						), $cr);
					} else { // удаляем все части, т.к. пустой массив
						// Delete all relations
						Article::model()->deleteAllByAttributes(array(
							'part'=>$model->id,
						));
					}
                }

                Yii::app()->session->remove($this->uploadsession);
           

                if(Yii::app()->request->isAjaxRequest){
                    
                } else {
                    $text = $new? "Статья {$model->title} добавлена" : "Статья {$model->title} отредактирована";
                    $this->addFlashMessage($text,'success');
                    $this->redirect(Yii::app()->createAbsoluteUrl('catalog/admin/article'));
                }

            } else {
                $this->addFlashMessage($model->errors,'error');
            }
        }

        $categories_ar = $articleorg_ar = array();
        $categories = $model->categories;
        $orgs = $model->orgs;

        if($categories){
            foreach($categories as $cats){
                $categories_ar[] = $cats->id;
            }
        }
       /* if($orgs){
            foreach($orgs as $org){
                $articleorg_ar[] = $org->id;
            }
        }*/
        $this->render('update',array(
            'model'=>$model,
            'categories_ar'=>$categories_ar,
          //  'articleorg_ar'=>$articleorg_ar
        ));
    }
    public function actionGetNewPart(){
    	$this->renderPartial('_getnewpart',array(),false,true);
    }

    public function actionUploadLogo(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadlogosession);
        $uploader->inputName = 'logotip';
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }

    public function actionUnlinkLogo(){

        $fileName = $_POST['name'];

        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(Yii::app()->session->itemAt($this->uploadlogosession)){
            $datas = Yii::app()->session->itemAt($this->uploadlogosession);
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
                Yii::app()->session->add($this->uploadlogosession,$datas);
            }

        }

    }
    public function actionUpload(){

        Yii::import("ext.MyAcrop.qqFileUploader");
        $folder='uploads/tmp';// folder for uploaded files
       // $allowedExtensions = array("jpg","jpeg","gif","png");
        $allowedExtensions = array();
        $sizeLimit = Yii::app()->params['storeImages']['maxFileSize'];
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $this->uploadsession);
        $uploader->inputName = 'logotip';
        $result = $uploader->handleUpload($folder);

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;
    }

    public function actionUnlink(){

        $fileName = $_POST['name'];

        $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
        if(Yii::app()->session->itemAt($this->uploadsession)){
            $datas = Yii::app()->session->itemAt($this->uploadsession);
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
                Yii::app()->session->add($this->uploadsession,$datas);
            }

        }

    }
    public function actionUpdatestatus($id, $status)
    {
        $id = (int)$id;
        $model = Article::model()->findByPk($id);

        if (!empty($model) && ($status == Article::STATUS_ACTIVE || $status == Article::STATUS_INACTIVE))
        {
           $model->status_org = $status;
           // $model->isNewRecord = false;
           // $model->saveAttributes(array('status_org'));
           if($model->save(false,'status_org')){
                
           }
        }
        echo '[]';
    }


    public function actionDelete($id)
    {
        $id = (int)$id;
        $model = Article::model()->findByPk($id);

        if ($model)
        {
            $model->delete();
           
        }
        echo '[]';
    }

    public function actionDeleteFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = ArticleImages::model()->findByPk($id);
        if(isset($file->organization)){
           // $pin = $this->_loadModel($file->pinboard->id);
            // remove it from disk also in model issueFile
            if($file->delete()) {
                
                echo "[]";
                Yii::app()->end();
            }
        }
        echo 'error';
        Yii::app()->end();
    }
    public function actionDeleteLogoFile($id)
    {
        $id = (int)$id;
        // check that you have access to this note
        $file = Article::model()->findByPk($id);
        if(!empty($file)){
           // Delete file
            $filename = $file->logotip;
            $imagePath = $file->getFileFolder() . $filename;
            if(file_exists($imagePath)) {
                unlink($imagePath); //delete file
            }
            $file->logotip = null;
            $file->logotip_realname = null;
            $file->save(false,array('logotip','logotip_realname'));
                echo "[]";
                Yii::app()->end();
        }

        
        echo 'error';
        Yii::app()->end();
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return City the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Article::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param City $model the model to be validated
     */
    protected function performAjaxValidation($model, $form)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']===$form)
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

 
}