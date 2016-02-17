<div id="searchbuddy_container" class="galBorder">
                 <div class="galInner"> 
                <div class="galContent">
                <div class="filterpole_head">//
                    <?php
                         $anc = $model->excluderoot()->ancestors()->find();
                         if(!$anc){
                             echo $model->name;
                         } else {
                            echo $anc->name;
                         }
                     ?>
                   </div>
<?php

/**
 * @var $this SFilterRenderer
 */

/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 *	    'title'=>'Filter Title',
 *	    'selectMany'=>true, // Can user select many filter options
 *	    'filters'=>array(array(
 *	        'title'      => 'Title',
 *	        'count'      => 'Products count',
 *	        'queryKey'   => '$_GET param',
 *	        'queryParam' => 'many',
 *	    ))
 *  );
 */

// Render active filters
$active = $this->getActiveFilters();
if(!empty($active))
{
	echo CHtml::openTag('div', array('class'=>'rounded'));
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo Yii::t('StoreModule.core', 'Текущие фильтры');
		echo CHtml::closeTag('div');

		$this->widget('zii.widgets.CMenu', array(
			'htmlOptions'=>$this->activeFiltersHtmlOptions,
			'items'=>$active
		));

		echo CHtml::link(Yii::t('StoreModule.core','Сбросить фильтр'), $this->getOwner()->createUrl('view', array('url'=>$this->model->url)), array('class'=>'cancel_filter'));
	echo CHtml::closeTag('div');
}
?>
<?php /* ?>
<div class="rounded price_slider">
	<div class="filter_header">
		<?php echo Yii::t('StoreModule.core', 'Цена') ?>
	</div>
<?php
	$cm=Yii::app()->currency;
	echo $this->widget('zii.widgets.jui.CJuiSlider', array(
		'options'=>array(
			'range'=>true,
			'min'=>(int)floor($cm->convert($this->controller->getMinPrice())),
			'max'=>(int)ceil($cm->convert($this->controller->getMaxPrice())),
 
			'disabled'=>(int)$this->controller->getMinPrice()===(int)$this->controller->getMaxPrice(),
			'values'=>array($this->currentMinPrice, $this->currentMaxPrice),
			'slide'=>'js: function( event, ui ) {
				$("#min_price").val(ui.values[0]);
				$("#max_price").val(ui.values[1]);
			}',
		),
		'htmlOptions'=>array(
			'style'=>'margin:10px 20px 10px 5px',
		),
	), true);
?>
<?php echo CHtml::form() ?>
	от <?php echo CHtml::textField('min_price', (isset($_GET['min_price'])) ? (int)$this->getCurrentMinPrice():null ) ?>
	до <?php echo CHtml::textField('max_price', (isset($_GET['max_price'])) ? (int)$this->getCurrentMaxPrice():null ) ?>
	<?php echo Yii::app()->currency->active->symbol ?>
	<button  type="submit">OK</button>
<?php echo CHtml::endForm() ?>
</div>
 <?php */ ?>
 
<?php 
/*
   if($model->mainCategory)
	$mainCategory = ($model->isNewRecord) ? 0 : $model->mainCategory->id;
else
{
	if($model->type)
		$mainCategory = $model->type->main_category;
	else
		$mainCategory = 0;
}
echo CHtml::hiddenField('main_category', $mainCategory);

// Create jstree
$this->widget('ext.jstree.SJsTree', array(
	'id'=>'CategoriesTree',
	'data'=>StoreCategoryNode::fromArray(Categories::model()->findAllByPk(1)),
	'options'=>array(
		'core'=>array(
			// Open root
			'initially_open'=>'CategoriesTreeNode_1',
		),
		'plugins'=>array('themes','html_data','ui','crrm', 'search','checkbox', 'cookies'),
		'checkbox'=>array(
			'two_state'=>true,
		),
		'cookies'=>array(
			'save_selected'=>false,
		),
		'ui'=>array(
			'initially_select'=>'CategoriesTreeNode_'.$mainCategory,
		),
	),
));*/
?>
<div class="rounded">
    <div class="filter_header">:: Поиск по категориям</div>
   
    <?php
    $ancestors = $this->model->excludeRoot()->ancestors()->find();
    
    if($ancestors) {
        $setcat = $ancestors->id;
    } else {
        $setcat = $this->model->id; 
    }

        Yii::import('application.modules.store.models.Categories');
        $items = Categories::model()->findByPk($setcat)->asCMenuArray();
        if(isset($items['items']))
        {
                $this->widget('application.extensions.mbmenu.MbMenu',array(
                            'cssFile'=>'mbmenu_my.css',
                            'items'=>$items['items'])
                );
        }
		?>
    <div style="clear: both"></div>
</div>
<?php


if(!empty($manufacturers['filters']) || !empty($attributes))
	echo CHtml::openTag('div', array('class'=>'rounded'));

	// Render manufacturers
	if(!empty($manufacturers['filters']))
	{
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo CHtml::encode(Yii::t('StoreModule.core', ':: Бренд'));
		echo CHtml::closeTag('div');

		echo CHtml::openTag('ul', array('class'=>'filter_links'));
		foreach($manufacturers['filters'] as $filter)
		{
			$url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany']);
			$queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));

			echo CHtml::openTag('li');
			// Filter link was selected.
			if(in_array($filter['queryParam'], $queryData))
			{
				// Create link to clear current filter
				$url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
				echo CHtml::link($filter['title'], $url, array('style'=>'color:#EF4060;font-style:italic;'));
			}
			elseif($filter['count'] > 0)
				echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
			else
				echo $filter['title'].' (0)';

			echo CHtml::closeTag('li');
		}
		echo CHtml::closeTag('ul');
	}
      /*  if(!empty($accounts['filters']))
	{
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo CHtml::encode(Yii::t('StoreModule.core', 'Пользователь'));
		echo CHtml::closeTag('div');

		echo CHtml::openTag('ul', array('class'=>'filter_links'));
		foreach($accounts['filters'] as $filter)
		{
			$url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $accounts['selectMany']);
			$queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));

			echo CHtml::openTag('li');
			// Filter link was selected.
			if(in_array($filter['queryParam'], $queryData))
			{
				// Create link to clear current filter
				$url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
				echo CHtml::link($filter['title'], $url, array('style'=>'color:green'));
			}
			elseif($filter['count'] > 0)
				echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
			else
				echo $filter['title'].' (0)';

			echo CHtml::closeTag('li');
		}
		echo CHtml::closeTag('ul');
	}*/
	// Display attributes
	foreach($attributes as $attrData)
	{
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo ':: ';
                echo CHtml::encode($attrData['title']);
		echo CHtml::closeTag('div');
                
		echo CHtml::openTag('ul', array('class'=>'filter_links'));
		foreach($attrData['filters'] as $filter)
		{
			$url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
			$queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));

			echo CHtml::openTag('li');
			// Filter link was selected.
			if(in_array($filter['queryParam'], $queryData))
			{
				// Create link to clear current filter
				$url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
				echo CHtml::link($filter['title'], $url, array('style'=>'color:#EF4060;font-style:italic;'));
			}
			elseif($filter['count'] > 0)
				echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
			else
				echo $filter['title'].' (0)';

			echo CHtml::closeTag('li');
		}
		echo CHtml::closeTag('ul');
	}

if(!empty($manufacturers['filters']) || !empty($attributes))
	echo CHtml::closeTag('div');
?>
       </div>
 
        <div class="ab top"></div>
        <div class="ab left"></div>
        <div class="ab right"></div>
        <div class="ab bottom"></div>
        </div>
        </div>
            <div style="clear:both"></div>


