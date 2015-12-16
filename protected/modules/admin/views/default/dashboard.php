<?php
$themeUrl = '/themes/bootstrap_311/';
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/morris/morris.min.js', CClientScript::POS_END);

?>
<div class="row">
<div class="col-sm-4">

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
								<span class="text-bg"><strong><?php echo $orgs; ?></strong></span><br>
								<!-- Extra small text -->
								<span class="text-xs"><?php echo Yii::t('site', 'org|orgs', $orgs); ?></span>
							</div>
							
						</div> <!-- /.stat-counters -->
					</div> <!-- /.stat-row -->
				</div> <!-- /.stat-panel -->
<!-- /11. $EXAMPLE_ACCOUNT_OVERVIEW -->

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
		