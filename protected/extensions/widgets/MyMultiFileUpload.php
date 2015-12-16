<?php


Yii::import('system.web.widgets.CMultiFileUpload');

/**
 *## Bootstrap button column widget.
 *
 * Used to set buttons to use Glyphicons instead of the defaults images.
 *
 * @package booster.widgets.grids.columns
 */
class MyMultiFileUpload extends CMultiFileUpload {
	/**
	 * Registers the needed CSS and JavaScript.
	 */
	public function registerClientScript()
	{
		$id=$this->htmlOptions['id'];

		$options=$this->getClientOptions();
		$options=$options===array()? '' : CJavaScript::encode($options);

		$cs=Yii::app()->getClientScript();
		$assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        $cs->registerScriptFile($baseUrl . '/jquery.multifile_m.js', CClientScript::POS_END);

		$cs->registerScript('Yii.CMultiFileUpload#'.$id,"jQuery(\"#{$id}\").MultiFile({$options});");
	}

}