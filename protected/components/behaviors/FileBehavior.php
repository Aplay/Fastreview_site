<?php
// Внимание! CActiveRecordBehavior есть Behavior, init() не работает, getFile - не работает, как в CApplicationComponent
class FileBehavior extends CActiveRecordBehavior
{
	public $attribute = 'filename';
	public $cap = '/img/cap.gif';
    public $size = '200x200';
    public $fileName = 'cap.gif';
    public $thumbPath;
    public $folderUploadPath;
    public $attributeId;

    private $imagePath;


    public function folderUploadPath(){
    	if(!$this->folderUploadPath)
    		$this->folderUploadPath = $this->ownerName;
    	return $this->folderUploadPath;
    }

    public function attributeId(){
    	if(!$this->attributeId){
    		$attributeId = $this->owner->id;
    	} else {
    		$attribute_Id = $this->attributeId;
    		$attributeId = $this->owner->$attribute_Id;
    	}
    	return $attributeId;
    }

	public function getOwnerName()
	{
		return strtolower(get_class($this->owner));
	}

    public function setCap($cap)
    {
        $this->cap = $cap;
    }
    
	public function getUrl($size = false, $resizeMethod = false, $random = false, $attribute = null)
	{
		if($attribute)
			$this->attribute = $attribute;

        if($size)
            $this->size = $size;

        if(!empty($this->owner->{$this->attribute})){
        	$this->fileName = $this->owner->{$this->attribute};
        	$this->imagePath = $this->folderPath.DIRECTORY_SEPARATOR.$this->fileName;
       

        } 
        else 
        {
        	$this->imagePath = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.$this->cap;
        }


		if(!$this->imagePath ||  !file_exists($this->imagePath)){
			return 'http://dummyimage.com/'.$this->size.'/ccc/fff';
		}

		if($size !== false)
		{
			$this->thumbPath = Yii::getPathOfAlias(Yii::app()->params['storeImages']['thumbPath']).'/'.$size;
			
			if (is_dir($this->thumbPath) == false){
				CFileHelper:: createDirectory($this->thumbPath, Yii::app()->params['storeImages']['dirMode'],true);
             }

			// Path to thumb
			$this->thumbPath = $this->thumbPath.'/'.$this->owner->id.'_'.$this->fileName;
 

			if(!file_exists($this->thumbPath) && file_exists($this->imagePath))
			{    

				// Resize if needed
				Yii::import('ext.phpthumb.PhpThumbFactory');
				$sizes  = explode('x', $size);
				$thumb  = PhpThumbFactory::create($this->imagePath);

				if($resizeMethod == false) {
					$resizeMethod = Yii::app()->params['storeImages']['sizes']['resizeThumbMethod'];
				}

                if(!empty($sizes[2])){
                   $thumb->$resizeMethod($sizes[0],$sizes[1],$sizes[2])->save($this->thumbPath);  
				} elseif(!empty($sizes[1])){
                   $thumb->$resizeMethod($sizes[0],$sizes[1])->save($this->thumbPath); 
                } else {
                    $thumb->$resizeMethod($size)->save($this->thumbPath);
                }
                            
			}

			return Yii::app()->params['storeImages']['thumbUrl'].$size.'/'.$this->owner->id.'_'.$this->fileName;

		}

		if ($random === true)
			return $this->imagePath.'?'.rand(1, 10000);
		return $this->imagePath;

	}

	public function deleteFile()
    {

        if(!empty($this->owner->{$this->attribute})){
        	$imagePath = $this->folderPath.DIRECTORY_SEPARATOR.$this->owner->{$this->attribute};
	        if(file_exists($imagePath)) {
	            unlink($imagePath); //delete file
	        }
        	$this->fileName = $this->owner->{$this->attribute};
        	MHelper::File()->cleanTempImg($this->fileName); // remove tmp files, etc.
        }
        $this->owner->saveAttributes(array($this->attribute=>''));
        return true;
    }


    public function addDropboxFiles($uploadsession,$tmpFiles,$attr)
    {

        $files = $this->owner->$tmpFiles;

        if($files){

            if(Yii::app()->session->itemAt($uploadsession)){

                $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
               
                foreach($files as $fileUploadName){

                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {
                                    
                                   $filename =  MHelper::File()->getUniqueTargetPath($this->getFileFolder(),$value);
                                    
                                    if (copy($folder.$value, $this->getFileFolder() . $filename)) {
                                        unlink($folder.$value);
                                        $this->owner->saveAttributes(array($attr=>$filename));
                                    } else {
                                       // throw new CHttpException(404, Yii::t('Site', 'Cannot copy file to folder.'));
                                    }
                                    

                                }
                            break;
                            }
                        }
                    }

                    
                }
            }
            
        }
    }
    public function getFileFolder()
    {
        $dir = Yii::getPathOfAlias('webroot').'/uploads/'.$this->folderUploadPath().'/'.$this->attributeId().'/';
        if (is_dir($dir) == false)
            CFileHelper:: createDirectory($dir, Yii::app()->params['storeImages']['dirMode']);
        return $dir;
    }

    public function getOrigFilePath() {
        return Yii::app()->baseUrl.'/uploads/'.$this->folderUploadPath().'/'.$this->attributeId().'/';
    }

    public function getOrigFile() {
        if(!empty($this->owner->{$this->attribute})){
            $this->fileName = $this->owner->{$this->attribute};
        } else {
            $this->fileName = $this->cap;
        }
        return Yii::app()->baseUrl.'/uploads/'.$this->folderUploadPath().'/'.$this->attributeId().'/'.$this->fileName;
    }


    /**
     * Delete the files on delete.
     */
    public function beforeDelete($evt)
    {
        $this->deleteFile(); // remove and delete file, tmp files, etc.
       // $this->deleteModelDir();
    }

    /**
     * Delete the whole directory on delete.
     */
    protected function deleteModelDir()
    {
        CFileHelper::removeDirectory($this->folderPath);
    }

    protected function getFolderPath()
    {

        $dir = Yii::getPathOfAlias(Yii::app()->params['storeImages']['uploads']).DIRECTORY_SEPARATOR.$this->folderUploadPath().DIRECTORY_SEPARATOR.$this->attributeId();
        if (is_dir($dir) == false)
            CFileHelper:: createDirectory($dir, Yii::app()->params['storeImages']['dirMode'], true);
    	return $dir;
    }

    protected function getImagePath()
    {
    	return $this->imadePath;
    }

    protected function setImagePath($value)
    {
    	$this->imadePath =  $value;
    }

    protected function getFileName()
    {
    	return $this->fileName;
    }
    protected function setFileName($value)
    {
    	$this->fileName = $value;
    }

    protected function moveUploadedTmp($fromUrl)
    {
        $filename =  MHelper::File()->getUniqueTargetPath($this->folderPath, $fromUrl);
        $tmpFolder = Yii::getPathOfAlias(Yii::app()->params['storeImages']['tmp']);                            
        if (copy($tmpFolder. DIRECTORY_SEPARATOR . $fromUrl, $this->folderPath . DIRECTORY_SEPARATOR . $filename)) {
            unlink($tmpFolder. DIRECTORY_SEPARATOR . $fromUrl);
            $this->owner->saveAttributes(array($this->attribute => $filename));
            return true;
        }
        return false;
    }

    protected function getPhotoFromUrl($url, $size = null)
    {

        Yii::import('application.vendors.*');
       // Yii::import('ext.phpthumbnew.*');
        require_once('/phpthumb/phpthumb.class.php');
       // Yii::setPathOfAlias('phpthumb',Yii::getPathOfAlias('application.vendors.phpthumb.phpthumb.class.php'));
        $extention='jpg';

        $id_photo = time();
        $file_name = $id_photo.'.'.$extention;
        $upload_path = Yii::getPathOfAlias(Yii::app()->params['storeImages']['tmp']) . DIRECTORY_SEPARATOR . $file_name;
        
        
        $PT = new phpThumb();
        if($size){
            $sizes  = explode('x', $size);
            $w = $sizes[0];
            $h = $sizes[1];

        $PT->w = (int)$w;
        $PT->h = (int)$h;

        } 

        $PT->src = $url;
     
        $PT->bg = "#ffffff";
        $PT->q = 90;
        $PT->far = false;
        $PT->f = $extention;
        $PT->GenerateThumbnail();
        $success = $PT->RenderToFile($upload_path);

        if($success)
            return $id_photo.'.'.$extention;
        else
            return false;
    }
}
?>