<?php



$countComments = $model->countComments;


$themeUrl = Yii::app()->theme->baseUrl;

?>


	
	<div class="row">
	<div class="col-xs-12">

	<?php 

	if($showcommenttext == true){ 

		 $comments = new CommentArticle('search');
         $comments->unsetAttributes();

        if(!empty($model->user_id)){
			$user_name = $model->user->fullname;
		} else {
			$user_name = $model->name;
		}

         if(!empty($_GET['CommentArticle']))
            $comments->attributes = $_GET['CommentArticle'];
         $criteria = new CDbCriteria;
         $criteria->limit = 3;
         $criteria->with = array('user');
         $criteria->condition = 't.object_pk='.$org_id.'   and t.status='.CommentArticle::STATUS_APPROVED;
         $dataProviderComments = new CActiveDataProvider('CommentArticle', array(
                'criteria' => $criteria,
                'sort'=>array(
                    'defaultOrder' => 't.created DESC',
                ),
                'pagination' => false,
            ));
         
         $this->renderPartial('application.modules.comments.views.comment._review_comments_article',array('dataProviderComments'=>$dataProviderComments,'model'=>$model,'org_id'=>$org_id, 'countComments'=>$countComments));
		

		
	 }
	 	?>

	</div> 
	</div>
	

