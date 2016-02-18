<?php 
$sizeLimit = Yii::app()->params['storeImages']['maxFileSize']/1024/1024;
?>
<div class="panel panel-firm" style="margin:0 15px 15px 15px;">
        <div class="panel-heading accordion">
        <span class="panel-title">Фирма <span class="panel-title-num"></span></span>
        <div class="panel-heading-controls" style="width:auto;">
			<button type="button" class="btn btn-xs btn-danger btn-outline remfirm"><span class="fa fa-times"></span></button>
		</div>
        </div>
        <div class="panel-body">
        <div class="form-group">
            <label  class="col-lg-2 col-md-12 col-sm-12 control-label">Фирма</label>
            <div class="col-lg-10 col-md-12 col-sm-12">
            	<input type="text" value="" name="Article[article_ar][firmname][]" placeholder="Выводимое название в статье" maxlength="255" class="form-control">
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
            <label  class="col-lg-2 col-md-12 col-sm-12 control-label">Фирма, url <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-12 col-sm-12">
                <input type="text" value=""  name="Article[article_ar][firmurl][]" placeholder="http://moscow.zazadun.ru/1234 или 1234" maxlength="255" class="form-control">
            </div>
        </div> <!-- / .form-group -->
        <div class="form-group">
        <label  class="col-lg-2 col-md-12 col-sm-12 control-label">Фото фирмы</label>
        <div class="col-lg-10 col-md-12 col-sm-12"  style="padding-top:7px">
              <?php
				$this->widget('ext.dropzone.EDropzone', array(
					'id'=>'part'.uniqid(),
				    'model' => false,
				    'name'=>'logotip',
				    'attribute' => 'logotip',
				    'sizeLimit'=>$sizeLimit,
				    'deleteLogo'=>Yii::app()->createAbsoluteUrl('/catalog/admin/article/deletelogofile'),
				    'unlinkLinkLogo'=>Yii::app()->createAbsoluteUrl('/catalog/admin/article/unlinklogo'),
				    'url' => $this->createAbsoluteUrl('/catalog/admin/article/upload'),
				  //  'mimeTypes' => array('image/jpeg', 'image/png', 'image/jpg', 'image/gif'),
				  //  'onSuccess' => 'someJsFunction();',
				    'options' => array(),
				));
				?>      
        </div>
        </div>
        <div class="form-group">
            <label  class="col-lg-2 col-md-12 col-sm-12 control-label required">Описание <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-12 col-sm-12">
                <textarea rows="9" name="Article[article_ar][firmdescription][]" class="form-control auto-size"></textarea>
            </div>
        </div> <!-- / .form-group -->
        </div>
        </div> 