<?php 
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/view/profile_view.js', CClientScript::POS_END);
?>
<div class="profile-content">

					<ul id="profile-tabs" class="nav nav-tabs">
						
						<li class="active">
							<a href="#profile-tabs-contact" data-toggle="tab"><?php echo Yii::t('site', 'Contact'); ?></a>
						</li>

					</ul>

					<div class="tab-content tab-content-bordered panel-padding">
						
						<div class="tab-pane fade widget-followers in active" id="profile-tabs-contact">
						   <div class="panel-body border-b">
						   
							   
		   <form class="form-inline" role="form" action="" method="post" id="InviteByEmailForm">
		   		<input type="hidden" name="<?php echo $csrfTokenName; ?>" value="<?php echo $csrfToken; ?>" />
			   <label for="invitePeople" class="control-label"><?php echo Yii::t('site', 'Invite people'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<div class="form-group dark" style="display: inline-block; margin-bottom: 0;vertical-align: middle;">
					<div class="input-group" id="invitePeopleInProfile">
						<span class="input-group-addon">@</span>
						<input type="text" style="margin-bottom:0" id="invitePeople" name="inviteEmail" placeholder="<?php echo Yii::t('site', 'e-mail'); ?>" class="form-control short-input">
					</div>
				</div>
				<button class="btn btn-primary InviteByEmailInProfile" type="submit" id="InviteByEmail" data-required-text="<?php echo Yii::t('error', 'This field is required'); ?>" data-error-text="<?php echo Yii::t('error', 'Required valid e-mail'); ?>"><?php echo Yii::t('site', 'Invite'); ?></button>
		    </form>
							   
						   
			</div>
			<?php
	
	$this->widget('zii.widgets.CListView', array(
	    'dataProvider' => $dataProviderContactList,
	   // 'viewData'=>array('themeUrl'=>$themeUrl),
	    'itemView' => '_view_contact_list',
	    'ajaxUpdate' => true,
	    'afterAjaxUpdate'=>'function(){
        $(".add-tooltip").tooltip();        
    	}',
	    'template' => "{items}\n{pager}",
	    'pager'=>array(
	        'header' => '',
	        'firstPageLabel'=>'first',
	        'lastPageLabel'=>'last',
	        'nextPageLabel' => '»',
	        'prevPageLabel' => '«',
	        'selectedPageCssClass' => 'active',
	        'hiddenPageCssClass' => 'disabled',
	        'htmlOptions' => array('class' => 'pagination')
	      ),
	    //'sorterHeader' => '',
	    'emptyText' => '&nbsp;',

	));
		?>


		</div> <!-- / .tab-pane -->
	</div> <!-- / .tab-content -->
</div>
