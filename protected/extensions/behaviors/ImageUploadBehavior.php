<?php
/**
 * Class ImageUploadBehavior
 *
 */
class ImageUploadBehavior extends CActiveRecordBehavior {
    public $imagePath = '';
    public $imageField = '';
    public $imageTitleField = '';
    private $_logo;
    private $_old_logo;
    private $_filename = '';
    private $_ext;

    public function beforeSave($event) {
        $this->_logo = CUploadedFile::getInstance($this->owner, $this->imageField);
        if ($this->_logo) {
            $this->_ext = pathinfo($this->_logo, PATHINFO_EXTENSION);
            $this->owner->{$this->imageField} = $this->getFileName() . '.' . $this->_ext;
        }
        else
            $this->owner->{$this->imageField} = $this->_old_logo;
    }

    public function afterSave($event) {
        $this->makeDefDir();

        if ($this->_logo) {
            $folder = $this->getFolderPath();
            $this->_logo->saveAs($folder . '/' . $this->getFileName() . '.' . $this->_ext);
        }
    }

    public function afterFind($event) {
        if (!$this->owner->isNewRecord)
            $this->_old_logo = $this->owner->{$this->imageField};
    }

    public function makeDefDir() {
        $folder = $this->getFolderPath();
        if (is_dir($folder) == false)
            mkdir($folder, 0755, true);
    }

    public function getFolderPath() {
        $folder = Yii::getPathOfAlias('webroot') . '/' . $this->imagePath . '/' . $this->owner->project_id;
        return $folder;
    }

    public function getFileName() {
        if (empty($this->_filename))
            $this->_filename = md5(rand(0, 9999));
        return $this->_filename;
    }

    public function beforeDelete($event) {
        $this->deleteFile();
    }

    public function deleteFile() {
        $file = $this->imagePath . '/' . $this->owner->{$this->imageField};
        if (file_exists($file) && !is_dir($file)) {
            unlink($file);
            $this->owner->{$this->imageField} = '';
        }
    }

    public function getImageUrl() {
        return $this->getBaseImagePath() . $this->owner->{$this->imageField};
    }

    private function getBaseImagePath() {
        $url = Yii::app()->baseUrl . '/' . $this->imagePath . '/' . $this->owner->project_id . '/';
        return $url;
    }

    public function getImageThumbUrl() {
        return $this->getBaseImagePath() . 'thumb_' . $this->owner->{$this->imageField};
    }

    public function createWM($image) {
        Yii::app()->ih
            ->load($image)
            ->watermark($_SERVER['DOCUMENT_ROOT'] . '/images/watermark.png', 10, 20, CImageHandler::CORNER_RIGHT_TOP)
            ->save($image);
    }
}