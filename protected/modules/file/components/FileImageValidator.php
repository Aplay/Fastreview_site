<?php

Yii::import('application.modules.file.components.FileImageConfig');

/**
 * Validate uploaded image.
 * Create unique image name.
 */
class FileImageValidator
{

	/**
	 * @param CUploadedFile $image
	 * @return bool
	 */
	public static function isAllowedSize(CUploadedFile $image)
	{
		//return ($image->getSize() <= Yii::app()->params['storeImages']['maxFileSize']);
                 return ($image->getSize() <= StoreImagesConfig::get('maxFileSize'));
	}

	/**
	 * @param CUploadedFile $image
	 * @return bool
	 */
	public static function isAllowedExt(CUploadedFile $image)
	{
		//return in_array(strtolower($image->getExtensionName()), Yii::app()->params['storeImages']['extensions']);
                return in_array(strtolower($image->getExtensionName()),  StoreImagesConfig::get('extensions'));
	}

	/**
	 * @param CUploadedFile $image
	 * @return bool
	 */
	public static function isAllowedType(CUploadedFile $image)
	{
                $type = CFileHelper::getMimeType($image->getTempName());
		if(!$type)
			$type = CFileHelper::getMimeTypeByExtension($image->getName());
		//return in_array($type, Yii::app()->params['storeImages']['types']);
                return in_array($type,  StoreImagesConfig::get('types'));
	}

	/**
	 * @param CUploadedFile $image
	 * @return bool
	 */
	public static function hasErrors(CUploadedFile $image)
	{
		return !(!$image->getError() && self::isAllowedExt($image) === true && self::isAllowedSize($image) === true && self::isAllowedType($image) === true);
	}

	/**
	 * @return string Path to save product image
	 */
	public static function getSavePath()
	{
		//return Yii::getPathOfAlias(Yii::app()->params['storeImages']['path']);
                return Yii::getPathOfAlias(StoreImagesConfig::get('path'));
	}

	/**
	 * @param Products $model
	 * @param CUploadedFile $image
	 * @return string
	 */
	public static function createName(Products $model, CUploadedFile $image)
	{
		$path = self::getSavePath();
                $name = self::generateRandomName($model, $image);
	//	$name = strtolower($model->id.'.'.$image->getExtensionName());

		if (!file_exists($path.'/'.$name))
			return $name;
		else
			//return strtolower($model->id.'_'.md5(microtime()).'.'.$image->getExtensionName());
                    self::createName($model, $image);
	}
        
        /**
	 * Generates random name bases on product and image models
	 *
	 * @param Products $model
	 * @param CUploadedFile $image
	 * @return string
	 */
	public static function generateRandomName(Products $model, CUploadedFile $image)
	{
		return strtolower($model->id.'_'.crc32(microtime()).'.'.$image->getExtensionName());
	}

}