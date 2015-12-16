<?php $this->beginContent('//layouts/board'); 
$themeUrl = '/themes/bootstrap_311/';

$this->renderPartial('application.views.common._flashMessage');


?>

		<div class="profile-full-name">
			<span class="text-semibold"><?php echo $this->user->fullname; ?></span><?php echo Yii::t('site','\'s profile'); ?>
		</div>
	 	<div class="profile-row">
			<div class="left-col">
				<div class="profile-block">
					<div class="panel profile-photo">
						<img src="<?php echo $this->user->getAvatar(); ?>" alt="">
					</div>
		
				</div>
		<?php if(!empty($this->user->about)) { ?>		
				<div class="panel panel-transparent">
					<div class="panel-heading">
						<span class="panel-title"><?php echo Yii::t('site', 'About me'); ?></span>
					</div>
					<div class="panel-body">
						<?php echo $this->user->about; ?>
					</div>
				</div>
		<?php } ?>
				
		<?php
	$show_social = true;
	if(!$this->user->phone && !$this->user->soc_twitter && !$this->user->soc_facebook && !$this->user->soc_linkedin && !$this->user->soc_envelope && !$this->user->soc_skype) { 
		$show_social = false;
	}
	if($show_social) {
	?>
				<div class="panel panel-transparent">
					<div class="panel-heading">
						<span class="panel-title"><?php echo Yii::t('site', 'Social'); ?></span>
					</div>
					<div class="list-group">
					    <?php if($this->user->phone) { ?>
						<span class="list-group-item"><i class="profile-list-icon fa fa-phone" style="color: #000"></i><?php echo $this->user->phone; ?></span>
						<?php } ?>
						<?php if($this->user->soc_twitter) { 
	$url = CHtml::encode($this->user->soc_twitter);
	$parse =  parse_url($url);
	$scheme = isset($parse['scheme']) ? '' : 'http://';
	$url = $scheme.$url;
						?>
						<a href="<?php echo $url; ?>" class="list-group-item"><i class="profile-list-icon fa fa-twitter" style="color: #4ab6d5"></i><?php echo $this->user->soc_twitter; ?></a>
						<?php } ?>
						<?php if($this->user->soc_facebook) { 
	$url = CHtml::encode($this->user->soc_facebook);
	$parse =  parse_url($url);
	$scheme = isset($parse['scheme']) ? '' : 'http://';
	$url = $scheme.$url;					
							?>
						<a href="<?php echo $url; ?>" class="list-group-item"><i class="profile-list-icon fa fa-facebook-square" style="color: #1a7ab9"></i><?php echo $this->user->soc_facebook; ?></a>
						<?php } ?>
						<?php if($this->user->soc_linkedin) { 
    $url = CHtml::encode($this->user->soc_linkedin);
	$parse =  parse_url($url);
	$scheme = isset($parse['scheme']) ? '' : 'http://';
	$url = $scheme.$url;	
						?>
						<a href="<?php echo $url; ?>" class="list-group-item"><i class="profile-list-icon fa fa-linkedin" style="color: #489DC9"></i><?php echo $this->user->soc_linkedin; ?></a>
						<?php } ?>
						<?php if($this->user->soc_envelope) { ?>
						<a href="mailto:<?php echo $this->user->soc_envelope; ?>" class="list-group-item"><i class="profile-list-icon fa fa-envelope" style="color: #888"></i><?php echo $this->user->soc_envelope; ?></a>
						<?php } ?>
						<?php if($this->user->soc_skype) { ?>
						<span class="list-group-item"><i class="profile-list-icon fa fa-skype" style="color: #489DC9"></i><?php echo $this->user->soc_skype; ?></span>
						<?php } ?>
					</div>
				</div>
		<?php } ?>
			</div>
			<div class="right-col">

				<hr class="profile-content-hr no-grid-gutter-h">
				
				<?php echo $content; ?>

			</div>

<!-- Template -->
<div id="modal-blurred-bg" class="modal fade modal-blur" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="modal-title"><?php echo Yii::t('site', 'Send Message'); ?></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" action="">
					
						<div class="row">
							<div class="col-md-12">
						<textarea placeholder="Message" rows="5" class="form-control"></textarea>
						</div>
						</div><!-- row -->
					<br>
					<div class="text-right">
						<button class="btn btn-primary"><?php echo Yii::t('site', 'Send Message'); ?></button>
					</div>
				</form>
			</div>
		</div> <!-- / .modal-content -->
	</div> <!-- / .modal-dialog -->
</div> <!-- / .modal -->
<!-- / Template -->
<?php

$scriptAddProfile = "
	document.body.className = document.body.className + ' page-profile';
	$('#profile-tabs').tabdrop();

		$('#leave-comment-form').expandingInput({
			target: 'textarea',
			hidden_content: '> div',
			placeholder: 'Write message',
			onAfterExpand: function () {
				$('#leave-comment-form textarea').attr('rows', '3').autosize();
			}
		});

$('.follower:last .btn-group').addClass('dropup');

";
Yii::app()->clientScript->registerScript("pxTeamProfile", $scriptAddProfile, CClientScript::POS_END);
?>

<?php $this->endContent(); ?>