<?php

class MyAcrop extends CWidget
{
        public $id;
        public $htmlOptions = array();
        
        public function run()
        {	

		if (isset($this->htmlOptions['id']))
			$this->id = $this->htmlOptions['id'];
		else
			$this->id = "file_Uploader";
                
                echo '<div id="'.$this->id.'"><noscript><p>Please enable JavaScript to use file uploader.</p></noscript></div>';
		$assets = dirname(__FILE__).'/assets';
                $baseUrl = Yii::app()->assetManager->publish($assets);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fineuploader-3.6.4.min.js', CClientScript::POS_HEAD);

              /*  Yii::app()->clientScript->registerScriptFile($baseUrl . '/ajax.requester.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/button.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/deletefile.ajax.requester.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/dnd.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/handler.base.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/handler.form.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/handler.xhr.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/header.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/iframe.xss.response.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery-plugin.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/uploader.basic.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/uploader.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/util.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/window.receive.message.js', CClientScript::POS_HEAD);*/


                
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.Jcrop.min.js', CClientScript::POS_HEAD);
            
                Yii::app()->clientScript->registerCssFile($baseUrl.'/fileuploader.css');
               // Yii::app()->clientScript->registerCssFile($baseUrl.'/fineuploader-3.5.0.css');
                Yii::app()->clientScript->registerCssFile($baseUrl.'/jquery.Jcrop.css'); 
                
	}

}
