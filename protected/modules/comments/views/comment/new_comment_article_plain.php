<?php
/**
 * @var $this Controller
 * @var $form CActiveForm
 */

				        
// Load module
//$module = Yii::app()->getModule('comments');
// Validate and save comment on post request
//$comment = $module->processRequestArticle($model);
// Load model comments
$provider = CommentArticle::getObjectComments($model);

$themeUrl = Yii::app()->theme->baseUrl;

// Display comments
if($provider) {
 ?>
 <div class="clearfix"></div>
<div id="comment_module" class="woocommerce" style="margin-top:20px;margin-bottom:0px;">
<div id="reviews">
<div id="comments">
<?php
$ip = MHelper::Ip()->getIp();
			$this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$provider,
            'ajaxUpdate'=>true,
            'template'=>"{items}\n{pager}",
            'itemView'=>'application.modules.comments.views.comment._item',
            'itemsTagName'=>'ol',
            'itemsCssClass'=>'commentlist',
           // 'viewData'=>array(),
            'emptyText'=>'',
            'pager'=>array(
              'maxButtonCount'=>5,
              'header' => '',
              'firstPageLabel'=>'<<',
              'lastPageLabel'=>'>>',
              'nextPageLabel' => '>',
              'prevPageLabel' => '<',
              'selectedPageCssClass' => 'active',
              'hiddenPageCssClass' => 'disabled',
              'htmlOptions' => array('class' => 'pagination')
            ),
           ));
?>
</div>
</div>
</div>

<?php
}
?>

