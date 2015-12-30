<?php
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/morris/morris.min.js', CClientScript::POS_END);

?>
<div class="row">
<div class="col-sm-6">

<!-- 11. $EXAMPLE_ACCOUNT_OVERVIEW =================================================================

				Account overview example
-->
				<div class="stat-panel">
					<div class="stat-row">
						<!-- Success darker background -->
						<div class="stat-cell bg-success darker">
							<!-- Stat panel bg icon -->
							<i style="font-size:60px;line-height:80px;height:80px;" class="fa fa-lightbulb-o bg-icon"></i>
							<!-- Big text -->
							<span class="text-bg">Обзор</span><br>
							<!-- Small text -->
							<span class="text-sm">&nbsp;</span>
						</div>
					</div> <!-- /.stat-row -->
					<div class="stat-row">
						<!-- Success background, without bottom border, without padding, horizontally centered text -->
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<!-- Small padding, without horizontal padding -->
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<!-- Big text -->
								<span class="text-bg"><strong><?php echo $users; ?></strong></span><br>
								<!-- Extra small text -->
								<span class="text-xs"><?php echo Yii::t('site', 'user|users', $users); ?></span>
							</div>
							<!-- Small padding, without horizontal padding -->
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<!-- Big text -->
								<span class="text-bg"><strong><?php echo $objects; ?></strong></span><br>
								<!-- Extra small text -->
								<span class="text-xs"><?php echo Yii::t('site', 'object|objects', $objects); ?></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?php echo $polls; ?></strong></span><br>
								<span class="text-xs"><?php echo Yii::t('site', 'poll|polls', $polls); ?></span>
						</div>
							
						</div> <!-- /.stat-counters -->
					</div> <!-- /.stat-row -->
					<div class="stat-row">
					<div class="stat-counters bg-success no-border-b no-padding text-center">
						<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?php echo $photos; ?></strong></span><br>
								<span class="text-xs"><?php echo Yii::t('site', 'photo|photos', $photos); ?></span>
						</div>
						<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?php echo $videos; ?></strong></span><br>
								<span class="text-xs">видео</span>
						</div>
					</div>
					</div>
				</div> <!-- /.stat-panel -->
<!-- /11. $EXAMPLE_ACCOUNT_OVERVIEW -->

			</div>
<div class="col-sm-6">
<div class="row">
<?php
if($objectsNew) { ?>	
		<div class="col-sm-6">
			<div class="stat-panel">
					<!-- Success background. vertically centered text -->
					<a href="<?php echo Yii::app()->createUrl('catalog/admin/objects/new_objects'); ?>" class="stat-cell bg-danger valign-middle">
						<!-- Stat panel bg icon -->
						<i class="fa fa-briefcase bg-icon"></i>
						<!-- Extra large text -->
						<span class="text-xlg"><strong><?php echo $objectsNew; ?></strong></span><br>
						<!-- Big text -->
						<span class="text-bg"><?php echo Yii::t('site', 'objectsNew|objectsNew', $objectsNew); ?></span><br>
						<!-- Small text -->
						<span class="text-sm"><?php echo Yii::t('site', 'wait|waits', $objectsNew); ?></span>
					</a> <!-- /.stat-cell -->
				</div> <!-- /.stat-panel -->
		</div>
		<?php } ?>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">

<!-- 12. $EXAMPLE_UPLOAD_STATISTICS ================================================================

				Upload statistics example
-->
				<!-- Javascript -->
<?php 


?>



</div>
</div>
		