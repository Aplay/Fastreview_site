<?php
	//$this->breadcrumbs=array(
	//    Yii::t('site','Results for').': '. CHtml::encode($term),
	//);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/view/search.js', CClientScript::POS_END);
	?>

<div class="page-search">
<?php if ($results->totalItemCount || $resultsPr->totalItemCount) { ?>
<div class="search-text">
<?php
if ($results->totalItemCount){ ?>
	<strong><?php echo $results->totalItemCount; ?></strong> <?php echo Yii::t('site','result found for|results found for', $results->totalItemCount); ?>: <span class="text-primary"><?php echo CHtml::encode($term); ?></span> in tasks
<br>
<?php } 
if ($resultsPr->totalItemCount){ ?>
	<strong><?php echo $resultsPr->totalItemCount; ?></strong> <?php echo Yii::t('site','result found for|results found for', $resultsPr->totalItemCount); ?>: <span class="text-primary"><?php echo CHtml::encode($term); ?></span> in projects
<br>
<?php } ?>
</div>
<?php } else { ?>
<div class="alert alert-error"><?php echo Yii::t('site','No search results'); ?></div>
<?php } ?>
<div class="search-tabs">
	<ul class="nav nav-tabs" id="search-tab">
		<li class="active">
			<a data-toggle="tab" href="#search-tabs-all"><?php echo Yii::t('site','Tasks'); ?> <span class="label label-primary"><?php if ($results->totalItemCount) { echo $results->totalItemCount;} ?></span></a>
		</li>
		<li>
			<a data-toggle="tab" href="#search-tabs-project"><?php echo Yii::t('site','Projects'); ?> <span class="label label-primary"><?php if ($resultsPr->totalItemCount) { echo $resultsPr->totalItemCount;} ?></span></a>
		</li>
	</ul> <!-- / .nav -->
</div>
<div class="panel search-panel">

			<!-- Search form -->

			<form class="search-form bg-primary" action="/search/search" method="get">
				<div class="input-group input-group-lg">
					<span class="input-group-addon no-background"><i class="fa fa-search"></i></span>
					<input type="text" placeholder="<?php if(Yii::app()->getRequest()->getQuery('q') !== null) { echo Yii::app()->request->getQuery('q'); } else { echo 'Type your search here...'; } ?>" class="form-control" name="q">
					<span class="input-group-btn">
						<button type="submit" class="btn">Search</button>
					</span>
				</div> <!-- / .input-group -->
			</form>
			<!-- / Search form -->

			<!-- Search results -->
			
			<div class="panel-body tab-content">
				
				<div id="search-tabs-all" class="tab-pane fade in active">
				<?php  

				$this->widget('zii.widgets.CListView', array(
				    'dataProvider' => $results,
				    'viewData'=>array('query'=>$query),
				    'itemView' => '_task_list',
				    'itemsTagName'=>'ul',
				    'itemsCssClass'=>'search-classic',
				    //'template' => '{sorter}{items}{pager}',
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
				   /* 'sortableAttributes' => array(
				        'created_date' => 'Дате',
				    ),*/
				));
				
				
			?>
			</div>

			<div id="search-tabs-project" class="tab-pane fade">
				<?php 

				$this->widget('zii.widgets.CListView', array(
				    'dataProvider' => $resultsPr,
				    'viewData'=>array('query'=>$query),
				    'itemView' => '_project_list',
				    'itemsTagName'=>'ul',
				    'itemsCssClass'=>'search-classic',
				    //'template' => '{sorter}{items}{pager}',
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
			</div>


			
			</div>
			<!-- / Search results -->
		</div>
</div>
