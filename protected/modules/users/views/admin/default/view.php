<?php
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$themeUrl = '/themes/bootstrap_311/';

//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/autocomplete/jquery.autoGrowInput.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/autocomplete/jquery.tagedit.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/js/autocomplete/css/jquery.tagedit.css'); 
//Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/js/autocomplete/css/jquery.ui.autocomplete.css'); 


Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/view/profile_settings.js', CClientScript::POS_END);
?><div class="row">
<div class="col-md-12 queue">

</div>
</div>
<div class="row page-profile page-settings">
<div class="col-md-6">
	<div class="panel">
			<div class="panel-heading">
				<span class="panel-title"><?php echo Yii::t('site','Information'); ?></span>
			</div>
			<div class="panel-body">
				<div class="row">
				<div class="col-lg-3 col-md-12">
					<div class="profile-block">
						<div class="panel profile-photo">
							<img id="profileAvatar" alt="" src="<?php echo $user->getAvatar(); ?>" class="my-avatar img-responsive">
						</div>
						<div id="profilePreviewAvatar" style="display:none"></div>
						<p style="margin-bottom:5px">
						<a class="btn btn-primary" id="profileAvatarAdd"><?php echo Yii::t('site','Upload photo'); ?></a></p>
						<a class="" href="#" id="profileAvatarDelete"><?php echo Yii::t('site','Delete photo'); ?></a>
					</div>
				</div>
				<div class="col-lg-9 col-md-12">
					
					<div class="form-group no-margin-hr dark">
					<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)),
						'method' => 'post',
						'id' => 'form-fullname',
						'enableAjaxValidation' => false,
						'enableClientValidation'=>true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'validateOnChange' => true,
						),
						'htmlOptions' => array(
							'class' => 'form',

						)
					));
				?>
				<?php echo $form->labelEx($user, 'fullname', array('class'=>'control-label')); ?>
						
						
				<div class="input_wrapper">
				<?php echo $form->textField($user, 'fullname', array('class'=>'submitField form-control', 'placeholder'=>Yii::t('site', 'Name and surname'))); ?>
				
				<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
				array(
			    'type' => 'POST',
			    'data'=>'js:{"ajax":"form-fullname", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[fullname]":$("#User_fullname").val()}',
			    'beforeSend'=>'js:function(data){
			    		 if ($("#form-fullname").valid()) { return true } else { return false }
        		}',
			    'success'=>"function(html) {
				     if (html.indexOf('{')==0) {
				     	var obj = jQuery.parseJSON(html);
				     	$.growl.error({ message: obj.User_fullname});
				     }
				     else {
				     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
				     }
				}",
				'error'=>"function(jqXHR, textStatus, errorThrown) {
					     $.growl.error({ message: textStatus +'  '+errorThrown});
				}"

				),array(
				   'encode' => false,
				   'class'=>'btn btn-xs btn-primary input_btn dataSend',
				  // 'type' => 'submit',

				));
			
			   ?>
				</div>
			<?php $this->endWidget(); ?>
						
					</div>
					<div class="form-group no-margin-hr dark">
					<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)),
						'method' => 'post',
						'id' => 'form-about',
						'enableAjaxValidation' => false,
						'enableClientValidation'=>true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'validateOnChange' => true,
						),
						'htmlOptions' => array(
							'class' => 'form',

						)
					));
				?>
				<?php echo $form->label($user, 'about', array('class'=>'control-label')); ?>
					<div class="input_wrapper">
					<?php echo $form->textarea($user, 'about', array('class'=>'submitField form-control', 'rows'=>5)); ?>
					<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
					array(
				    'type' => 'POST',
				    'data'=>'js:{"ajax":"form-about", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[about]":$("#User_about").val()}',
				    'beforeSend'=>'js:function(data){
				    		if ($("#form-about").valid()) { return true } else { return false }
	        		}',
				    'success'=>"function(html) {
					     if (html.indexOf('{')==0) {
					     	var obj = jQuery.parseJSON(html);
					     	$.growl.error({ message: obj.User_about});
					     }
					     else {
					     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
					     }
					}",
					'error'=>"function(jqXHR, textStatus, errorThrown) {
						     $.growl.error({ message: textStatus +'  '+errorThrown});
					}"

					),array(
					   'encode' => false,
					   'class'=>'btn btn-xs btn-primary input_btn dataSend',
					  // 'type' => 'submit',

					));
				
				?>

					</div>
					<?php $this->endWidget(); ?>
					</div>
				</div>
				</div>

				<!--
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
						<label class="control-label"><?php // echo Yii::t('site','Skills'); ?></label>
						<input type="text" class="form-control" name="firstname">
					</div>
				</div>
				</div>
				-->
				<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)),
						'method' => 'post',
						'id' => 'form-social',
						'enableAjaxValidation' => false,
						'enableClientValidation'=>true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'validateOnChange' => true,
						),
						'htmlOptions' => array(
							'class' => 'form',

						)
					));
				?>
				<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="form-group no-margin-hr dark">

					<?php echo $form->label($user, 'phone', array('class'=>'control-label')); ?>	
					<div class="input_wrapper">
					<?php echo $form->textField($user, 'phone', array('class'=>'submitField form-control')); ?>
					<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
					array(
				    'type' => 'POST',
				    'data'=>'js:{"ajax":"form-social", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[phone]":$("#User_phone").val()}',
				    'beforeSend'=>'js:function(data){
				    		if ($("#form-social").valid()) { return true } else { return false }
	        		}',
				    'success'=>"function(html) {
					     if (html.indexOf('{')==0) {
					     	var obj = jQuery.parseJSON(html);
					     	$.growl.error({ message: obj.User_phone});
					     }
					     else {
					     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
					     }
					}",
					'error'=>"function(jqXHR, textStatus, errorThrown) {
						     $.growl.error({ message: textStatus +'  '+errorThrown});
					}"

					),array(
					   'encode' => false,
					   'class'=>'btn btn-xs btn-primary input_btn dataSend',
					));
				
				?>
					</div>

					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="form-group no-margin-hr dark">
						<?php echo $form->label($user, 'soc_facebook', array('class'=>'control-label')); ?>	
						<div class="input_wrapper">
						<?php echo $form->textField($user, 'soc_facebook', array('class'=>'submitField form-control')); ?>
						<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
						array(
					    'type' => 'POST',
					    'data'=>'js:{"ajax":"form-social", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[soc_facebook]":$("#User_soc_facebook").val()}',
					    'beforeSend'=>'js:function(data){
					    		if ($("#form-social").valid()) { return true } else { return false }
		        		}',
					    'success'=>"function(html) {
						     if (html.indexOf('{')==0) {
						     	var obj = jQuery.parseJSON(html);
						     	$.growl.error({ message: obj.User_soc_facebook});
						     }
						     else {
						     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
						     }
						}",
						'error'=>"function(jqXHR, textStatus, errorThrown) {
							     $.growl.error({ message: textStatus +'  '+errorThrown});
						}"

						),array(
						   'encode' => false,
						   'class'=>'btn btn-xs btn-primary input_btn dataSend',
						));
					
					?>
					</div>
				</div>
				</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_skype', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_skype', array('class'=>'submitField form-control')); ?>
							<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
							array(
						    'type' => 'POST',
						    'data'=>'js:{"ajax":"form-social", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[soc_skype]":$("#User_soc_skype").val()}',
						    'beforeSend'=>'js:function(data){
						    		if ($("#form-social").valid()) { return true } else { return false }
			        		}',
						    'success'=>"function(html) {
							     if (html.indexOf('{')==0) {
							     	var obj = jQuery.parseJSON(html);
							     	$.growl.error({ message: obj.User_soc_skype});
							     }
							     else {
							     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
							     }
							}",
							'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
							}"

							),array(
							   'encode' => false,
							   'class'=>'btn btn-xs btn-primary input_btn dataSend',
							));
						
						?>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_linkedin', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_linkedin', array('class'=>'submitField form-control')); ?>
							<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
							array(
						    'type' => 'POST',
						    'data'=>'js:{"ajax":"form-social", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[soc_linkedin]":$("#User_soc_linkedin").val()}',
						    'beforeSend'=>'js:function(data){
						    		 if ($("#form-social").valid()) { return true } else { return false }
			        		}',
						    'success'=>"function(html) {
							     if (html.indexOf('{')==0) {
							     	var obj = jQuery.parseJSON(html);
							     	$.growl.error({ message: obj.User_soc_linkedin});
							     }
							     else {
							     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
							     }
							}",
							'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
							}"

							),array(
							   'encode' => false,
							   'class'=>'btn btn-xs btn-primary input_btn dataSend',
							));
						
						?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_envelope', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_envelope', array('class'=>'submitField form-control')); ?>
							<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
							array(
						    'type' => 'POST',
						    'data'=>'js:{"ajax":"form-social", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[soc_envelope]":$("#User_soc_envelope").val()}',
						    'beforeSend'=>'js:function(data){
						    		if ($("#form-social").valid()) { return true } else { return false }
			        		}',
						    'success'=>"function(html) {
							     if (html.indexOf('{')==0) {
							     	var obj = jQuery.parseJSON(html);
							     	$.growl.error({ message: obj.User_soc_envelope});
							     }
							     else {
							     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
							     }
							}",
							'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
							}"

							),array(
							   'encode' => false,
							   'class'=>'btn btn-xs btn-primary input_btn dataSend',
							));
						
						?>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="form-group no-margin-hr dark">
							<?php echo $form->label($user, 'soc_twitter', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'soc_twitter', array('class'=>'submitField form-control')); ?>
							<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
							array(
						    'type' => 'POST',
						    'data'=>'js:{"ajax":"form-social", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[soc_twitter]":$("#User_soc_twitter").val()}',
						    'beforeSend'=>'js:function(data){
						    		 if ($("#form-social").valid()) { return true } else { return false }
			        		}',
						    'success'=>"function(html) {
							     if (html.indexOf('{')==0) {
							     	var obj = jQuery.parseJSON(html);
							     	$.growl.error({ message: obj.User_soc_twitter});
							     }
							     else {
							     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
							     }
							}",
							'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
							}"

							),array(
							   'encode' => false,
							   'class'=>'btn btn-xs btn-primary input_btn dataSend',
							));
						
						?>
							</div>
						</div>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div><!-- panel-body -->
		
	</div>
</div>
<div class="col-md-6">
	<div class="panel">
			<div class="panel-heading">
				<span class="panel-title"><?php echo Yii::t('site','Account'); ?></span>
			</div>
			<div class="panel-body">
			
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
					<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)),
						'method' => 'post',
						'id' => 'form-account-username',
						'enableAjaxValidation' => false,
						'enableClientValidation'=>true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'validateOnChange' => true,
						),
						'htmlOptions' => array(
							'class' => 'form',

						)
					));
				?>
						<?php echo $form->labelEx($user, 'username', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'username', array('class'=>'submitField form-control')); ?>
							<?php /*echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
							array(
						    'type' => 'POST',
						    'data'=>'js:{"ajax":"form-account-username", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[username]":$("#User_username").val()}',
						    'beforeSend'=>'js:function(data){
						    		 if ($("#form-account-username").valid()) { return true } else { return false }
			        		}',
						    'success'=>"function(html) {
							     if (html.indexOf('{')==0) {
							     	var obj = jQuery.parseJSON(html);
							        $.growl.error({ message: obj.User_username});
							     }
							     else {
							     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
							     }
							}",
							'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
							}"

							),array(
							   'encode' => false,
							   'class'=>'btn btn-xs btn-primary input_btn dataSend',
							));*/
						
						?>
						<button class="btn btn-xs btn-primary input_btn dataSend" ><?php echo Yii::t('site','save'); ?></button>
							</div>
							<input type="hidden" name="ajax" value="form-account-username" />
						<?php $this->endWidget(); ?>
					</div>
				</div>
				</div>
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
						<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)),
						'method' => 'post',
						'id' => 'form-account-email',
						'enableAjaxValidation' => false,
						'enableClientValidation'=>true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'validateOnChange' => true,
						),
						'htmlOptions' => array(
							'class' => 'form',

						)
					));
				?>
						<?php echo $form->labelEx($user, 'email', array('class'=>'control-label')); ?>	
							<div class="input_wrapper">
							<?php echo $form->textField($user, 'email', array('class'=>'submitField form-control')); ?>
							<?php 
							/*echo CHtml::ajaxSubmitButton(Yii::t('site','save'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
							array(
						    'type' => 'POST',
						    'data'=>'js:{"ajax":"form-account-email", "'.$csrfTokenName.'": "'.$csrfToken.'", "User[email]":$("#User_email").val()}',
						    'beforeSend'=>'js:function(data){
						    		 if ($("#form-account-email").valid()) { return true } else { return false }
			        		}',
						    'success'=>"function(html) {
							     if (html.indexOf('{')==0) {
							     	var obj = jQuery.parseJSON(html);
							        $.growl.error({ message: obj.User_email});
							     }
							     else {
							     	 $.growl.notice({ message: '".Yii::t('site','Saved successfully')."' });
							     }
							}",
							'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
							}"

							),array(
							   'encode' => false,
							   'class'=>'btn btn-xs btn-primary input_btn dataSend',
							));*/
						
						?>
						
						<button class="btn btn-xs btn-primary input_btn dataSend" ><?php echo Yii::t('site','save'); ?></button>

							</div>
							<input type="hidden" name="ajax" value="form-account-email" />
						<?php $this->endWidget(); ?>
					</div>
				</div>
				</div>
		
			</div>
			<div class="panel-footer">
			<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)),
						'method' => 'post',
						'id' => 'form-changepassword',
						'enableAjaxValidation' => false,
						'enableClientValidation'=>true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'validateOnChange' => true,
							/*'afterValidate'=>'js:function(form,data,hasError)
		                        {
		                        	
		                            if(!hasError)
		                            {
		                            	$.growl.notice({ message: "'.Yii::t('site','Password saved successfully').'" });

		                            }
		                        }'*/
						),
						'htmlOptions' => array(
							'class' => 'form',

						)
					));
				?>
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
					   <?php 
					   
					   echo $form->labelEx($changePassword, 'oldPassword', array('class'=>'control-label')); ?>
					   <?php echo $form->textField($changePassword, 'oldPassword', array('class'=>'form-control')); ?>
					</div>
				</div>
				</div>
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
						<?php echo $form->labelEx($changePassword, 'password', array('class'=>'control-label')); ?>
					   <?php echo $form->textField($changePassword, 'password', array('class'=>'form-control')); ?>
					</div>
				</div>
				</div>
				<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group no-margin-hr dark">
						<?php echo $form->labelEx($changePassword, 'verifyPassword', array('class'=>'control-label')); ?>
					    <?php echo $form->textField($changePassword, 'verifyPassword', array('class'=>'form-control')); ?>
					</div>
					<?php // echo CHtml::button(Yii::t('site', 'Change password'), array('type'=>'submit', 'class'=>'btn btn-primary pull-right')); ?>
				<?php echo CHtml::ajaxSubmitButton(Yii::t('site', 'Change password'), Yii::app()->createUrl('/users/admin/default/update', array('id'=>$user->id)), 
								array(
							    'type' => 'POST',
							    'data'=>'js:{"ajax":"form-changepassword", "'.$csrfTokenName.'": "'.$csrfToken.'", "UserChangePassword[oldPassword]":$("#UserChangePassword_oldPassword").val(), "UserChangePassword[password]":$("#UserChangePassword_password").val(), "UserChangePassword[verifyPassword]":$("#UserChangePassword_verifyPassword").val()}',
							    'beforeSend'=>'js:function(data){
							    	if ($("#form-changepassword").valid()) { return true } else { return false }
				        		}',
							    'success'=>"function(html) {
								     if (html.indexOf('{')==0) {
								     	var obj = jQuery.parseJSON(html);
								     	var mes = '';
								     	if(obj.UserChangePassword_oldPassword)
								     		mes = mes + obj.UserChangePassword_oldPassword + '<br>';
								     	if(obj.UserChangePassword_password)
								     		mes = mes + obj.UserChangePassword_password + '<br>';
								     	if(obj.UserChangePassword_verifyPassword)
								     		mes = mes + obj.UserChangePassword_verifyPassword + '<br>';
								        $.growl.error({ message: mes});
								     }
								     else {
								     	 $.growl.notice({ message: '".Yii::t('site','Password saved successfully')."' });
								     }
								}",
								'error'=>"function(jqXHR, textStatus, errorThrown) {
								     $.growl.error({ message: textStatus +'  '+errorThrown});
								}"
								),array(
								   'encode' => false,
								   'class'=>'btn btn-primary pull-right',
								   //'type' => 'submit',
								));
							?>
				</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
			<div class="panel-footer">
				<div class="form-group no-margin tab-content-padding">
				<?php // echo CHtml::link(Yii::t('site', 'Delete account'),Yii::app()->createUrl('/users/admin/default/delete',array('id'=>$user->id)), array('class'=>'pull-left', 'title'=>Yii::t('site', 'Delete account'), 'confirm' => Yii::t('site','Do you really want to delete account? Recovery will not be available.'))); ?>
				</div>
			</div>
	</div>
</div>
</div>
<?php
$src = $themeUrl.'/img/avatars/male.png';
$availableNotifications = CHtml::listData(Notifications::model()->findAll(array('condition'=>'available_email=:available', 'params'=>array(':available'=>true), 'order'=>'id')), 'id','form_title');
$availableNotificationsArray = array();
if(!empty($availableNotifications)){
	foreach($availableNotifications as $key=>$value){
		$availableNotificationsArray[] = array('value'=>$key,'text'=>$value);
	}
}
$setArray = CJSON::encode($availableNotificationsArray);

$scriptDd = "
$(document).ready(function(){



var dropzone = new Dropzone('#profileAvatarAdd', {
	    // Prevents Dropzone from uploading dropped files immediately
		autoProcessQueue: true,
		url: '".Yii::app()->createUrl('/users/admin/default/addavatar', array('id'=>$user->id))."',
		maxFilesize: 5,
		paramName: 'qqfile',
		thumbnailWidth: 160,
        thumbnailHeight: 160,
		params: {
          '".$csrfTokenName."': '".$csrfToken."'
        },
        previewsContainer : '#profilePreviewAvatar',
}).on('addedfile', function(file) {
                
}).on('success', function(file) {
		var src = $('#profilePreviewAvatar .dz-preview:last .dz-details img').attr('src');
	//	$('.page-settings .profile-photo').css('width','auto');
		$('.my-avatar').attr('src', src);
	//	var url = src.replace(/^data:image\/[^;]/, 'data:application/octet-stream');
    //		location.href = url;
		
});
$('#profileAvatarDelete').on('click', function(e){
	e.preventDefault();
    $.ajax({
				type: 'POST',
				url: '".Yii::app()->createUrl('/users/admin/default/unlink', array('id'=>$user->id))."',
				data: { '".$csrfTokenName."': '".$csrfToken."'},
		}).done(function(data){
				
				$('.my-avatar').attr('src', '".$src."');
				dropzone.removeAllFiles();
		});
})
$('#bs-x-editable-notifications').editable({
		//limit: 3,
		type:'checklist',
		source: ".$setArray.",
		emptytext: '".Yii::t('site', 'None')."',
		display: function(value, sourceData) {
		    //display checklist as comma-separated values
		    var html = [],
		    checked = $.fn.editableutils.itemsByValue(value, sourceData);
		    if(checked.length) {
		    	$.each(checked, function(i, v) { 
		    		html.push('<span class=\"selNotifsEmail\">'+$.fn.editableutils.escape(v.text)+ '</span>'); 
		    	});
		    	$(this).html(html.join(', '));
	
		    } else {
		    	$(this).empty();
		    }
		 },
		
	    params: function(params) {
		    //originally params contain pk, name and value
		    params.".$csrfTokenName." = '".$csrfToken."';
		    return params;
		    },
		'url': '".Yii::app()->createUrl('/users/admin/default/updateEmailNotification', array('id'=>$user->id))."'
	});
});
";

Yii::app()->clientScript->registerScript("selScript", $scriptDd, CClientScript::POS_END);
?>