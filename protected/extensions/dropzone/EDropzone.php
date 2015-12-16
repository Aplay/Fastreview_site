<?php
/**
 *
 * Yii extension to the drag n drop HTML5 file upload Dropzone.js
 * For more info, see @link http://www.dropzonejs.com/
 *
 * @link https://github.com/subdee/yii-dropzone
 *
 * @author Konstantinos Thermos
 *
 * @copyright
 * Copyright (c) 2013 Konstantinos Thermos
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 * NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */
class EDropzone extends CWidget {

    public $id;
    /**
     * @var string The name of the file field
     */
    public $name = false;
    /**
     * @var CModel The model for the file field
     */
    public $model = false;
    /**
     * @var string The attribute of the model
     */
    public $attribute = false;
    /**
     * @var array An array of options that are supported by Dropzone
     */
    public $options = array();
    /**
     * @var string The URL that handles the file upload
     */
    public $url = false;
    /**
     * @var array An array of supported MIME types
     */
    public $mimeTypes = array();
    /**
     * @var string The Javascript to be called in case of a succesful upload
     */
    public $onSuccess = false;

    public $sizeLimit = 5;

    public $deleteLogo;

    public $unlinkLinkLogo;
    /**
     * Create a div and the appropriate Javascript to make the div into the file upload area
     */
    public function run() {
        if (!$this->url || $this->url == '')
            $this->url = Yii::app()->createUrl('site/upload');
        if(!$this->id)
            $this->id = $this->getId();

        // echo CHtml::openTag('div', array('class' => 'dropzone', 'id' => $this->id));
        echo '<div id="previewDz_logo_'.$this->id.'">
		                    </div>
		                    <button class="btn btn-primary btn-outline" type="button" id="dropzone_opener_logo_'.$this->id.'">Загрузить файл с диска</button>
		                    <div id="'.$this->id.'" class="dropzone-box" style="display:none;min-height: 200px; margin-top: 10px;">
		                        <div class="dz-default dz-message">
		                            <i class="fa fa-cloud-upload"></i>
		                            Переместите файл сюда<br><span class="dz-text-small">или нажмите, чтобы выбрать вручную</span>
		                        </div>
		                            <div class="fallback">
		                                <input name="'.$this->name.'" type="file" multiple="" />
		                                
		                            </div>
		                            <input type="hidden" id="firmphotoh_'.$this->id.'" name="Article[article_ar][firmphotoh][]" value="">
		                    </div>';
        // echo CHtml::closeTag('div');

        if (!$this->name && ($this->model && $this->attribute) && $this->model instanceof CModel)
            $this->name = CHtml::activeName($this->model, $this->attribute);

        $this->mimeTypes = CJavaScript::encode($this->mimeTypes);
        $init = '';
        if($this->model){
        	$init = "var thisDropzone = this;
			                $.getJSON('".Yii::app()->createAbsoluteUrl('file/file/logotipFile', array('id'=>$this->model->id,'model'=>'Article','filename'=>'logotip','realname'=>'logotip_realname'))."', function(data) { // get the json response
			                    $.each(data, function(key,value){ //loop through it
			                        var mockFile = { id: value.id, name: value.name, size: value.size }; // here we get the file name and size as response 
			                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
			                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/file/file/logotip/'+value.id+'?model=Article&filename=logotip&realname=logotip_realname');
			                    });
			                });";
        } 
        $options = CMap::mergeArray(array(
                'url' => $this->url,
                'maxFilesize'=> $this->sizeLimit, // mb
                'parallelUploads' => 1,
                'paramName' => $this->name,
		        'params'=> "js:{
			          '".Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'
			        }",
                'previewsContainer'=>'#previewDz_logo_'.$this->id,
                'addRemoveLinks' => true,
                 'dictRemoveFile'=>'Удалить',
                'dictResponseError'=> "Can't upload file!",
                 'acceptedFiles'=> '.jpeg,.jpg,.png,.gif',
                'autoProcessQueue'=> true,
                'init'=>"js:function(){
                	this.on('success',function(file, serverResponse){
                		var id = $(this.element);

		                id.find('.progress').remove();
		                var response = $.parseJSON(serverResponse);
		                if (response && response.success == true && response.fileName){
		                	$('#firmphotoh_".$this->id."').attr('value', response.fileName);
		                	 $('#company-form').append('<input type=\"hidden\" name=\"Article[article_ar][firmphoto][]\" value=\"' + response.fileName + '\" class=\"dr-zone-inputs\">');
		                }
                	});
                
                       ".$init."
			              
			        }",
			    'removedfile'=> "js:function(file) {
            
            var name = file.name, removedlink;  
            if(file.id){
                removedlink = '".$this->deleteLogo."/?id='+ file.id;
            }  else {
                removedlink = '".$this->unlinkLinkLogo."';
            }   
            $.ajax({
                type: 'POST',
                url: removedlink,
                data: {
                    'name':name,
                    '".Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'

                },
                dataType: 'html'
            });
        $('input[value=\"'+ name +'\"]').remove();
        var _ref;
        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
        }", 
        'thumbnailWidth'=> 138,
        'thumbnailHeight'=> 120,   
        'previewTemplate'=> '<div class="dz-preview dz-file-preview"><div class="dz-details">
        <div class="dz-thumbnail-wrapper">
        <div class="dz-thumbnail">
        <img data-dz-thumbnail><span class="dz-nopreview">No preview</span>
        <div class="dz-error-mark"><i class="fa fa-times-circle-o"></i></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div></div></div></div>
        </div>',
        'resize'=> "js:function(file) {
            var info = { srcX: 0, srcY: 0, srcWidth: file.width, srcHeight: file.height },
                srcRatio = file.width / file.height;
            if (file.height > this.options.thumbnailHeight || file.width > this.options.thumbnailWidth) {
                info.trgHeight = this.options.thumbnailHeight;
                info.trgWidth = info.trgHeight * srcRatio;
                if (info.trgWidth > this.options.thumbnailWidth) {
                    info.trgWidth = this.options.thumbnailWidth;
                    info.trgHeight = info.trgWidth / srcRatio;
                }
            } else {
                info.trgHeight = file.height;
                info.trgWidth = file.width;
            }
            return info;
        }"
              //  'accept' => "js:function(file, done){if(jQuery.inArray(file.type,{$this->mimeTypes}) == -1 ){done('File type not allowed.');}else{done();}}",
              //  'init' => "js:function(){this.on('success',function(file){{$this->onSuccess}});}"
                ), $this->options);

        $options = CJavaScript::encode($options);

      //  $script = "Dropzone.options.".$this->id." = {$options}";
        $script = "$('#".$this->id."').dropzone(".$options.")";
        $script .= "
$('body').on('click', '#dropzone_opener_logo_".$this->id."', function(){
    if($('#".$this->id."').is(':visible')){
        $('#".$this->id."').hide();
        $('#".$this->id." input.dr-zone-inputs').attr('disabled', true);
    } else {
        $('#".$this->id."').show();
        $('#".$this->id." input.dr-zone-inputs').attr('disabled', false);
    }
});
";
     //   $this->registerAssets();
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id, $script, CClientScript::POS_END);

    }

    private function registerAssets() {
        $basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
       // Yii::app()->getClientScript()->registerCoreScript('jquery');
       // Yii::app()->getClientScript()->registerScriptFile("{$baseUrl}/js/dropzone.js", CClientScript::POS_END);
       // Yii::app()->getClientScript()->registerCssFile("{$baseUrl}/css/dropzone.css");
    }

}
?>
