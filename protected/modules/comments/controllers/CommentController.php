<?php

class CommentController extends Controller
{
	public $city;
	public $layout = '//layouts/emptyerror';

    public function filters()
    {
     return array(
        'ajaxOnly + addreview, addreviewarticle, addreviewspec',
        );
    }

  	public function actionAddReview($id)
    {

        $id = (int)$id;
        $review = Orgs::model()->active()->findByPk($id);
        if(!$review)
            Yii::app()->end();
        if(isset($_POST['Comment'])) {

            $model = new Comment;
            $model->scenario = 'subcomment';
            $model->object_pk = $review->id;

            if(!Yii::app()->user->isGuest)
            {
                $model->user_id = Yii::app()->user->id;

            } else {

                 Yii::app()->end();
            }
            $model->setAttribute('status', Comment::STATUS_APPROVED);
            $this->performAjaxValidation($model, 'comments-form');
            	$model->attributes=$_POST['Comment'];
            if(empty($model->id_parent))
                $model->id_parent = null;

            

            if($model->validate()){
                
                if($model->save()){
                	if(isset($_POST['type']) && $_POST['type']==1){
                		$this->renderPartial('_comment_super',array('model'=>$model,'supermodel'=>$review));
                	} else{
                		// $this->renderPartial('_comment',array('model'=>$model));
                	}
                    
                } 
                
            } 

        }
        Yii::app()->end();
    }

    public function actionAddReviewArticle($id)
    {

        $id = (int)$id;
        $review = Article::model()->active()->findByPk($id);
        if(!$review)
            Yii::app()->end();
        if(isset($_POST['CommentArticle'])) {

            $model = new CommentArticle;
            $model->scenario = 'subcomment';
            $model->object_pk = $review->id;

            if(!Yii::app()->user->isGuest)
            {
                $model->user_id = Yii::app()->user->id;

            } else {

                 Yii::app()->end();
            }
            $model->setAttribute('status', CommentArticle::STATUS_APPROVED);
            $this->performAjaxValidation($model, 'comments-form');
            	$model->attributes=$_POST['CommentArticle'];
            if(empty($model->id_parent))
                $model->id_parent = null;

            

            if($model->validate()){
                
                if($model->save()){
                	if(isset($_POST['type']) && $_POST['type']==1){
                		$this->renderPartial('_comment_super',array('model'=>$model,'supermodel'=>$review));
                	} else{
                		// $this->renderPartial('_comment',array('model'=>$model));
                	}
                    
                } 
                
            } 

        }
        Yii::app()->end();
    }

    public function actionAddReviewSpec($id)
    {

        $id = (int)$id;
        $review = Spec::model()->active()->findByPk($id);
        if(!$review)
            Yii::app()->end();
        if(isset($_POST['CommentSpec'])) {

            $model = new CommentSpec;
            $model->scenario = 'subcomment';
            $model->object_pk = $review->id;

            if(!Yii::app()->user->isGuest)
            {
                $model->user_id = Yii::app()->user->id;

            } else {

                 Yii::app()->end();
            }
            $model->setAttribute('status', CommentSpec::STATUS_APPROVED);
            $this->performAjaxValidation($model, 'comments-form');
                $model->attributes=$_POST['CommentSpec'];
            if(empty($model->id_parent))
                $model->id_parent = null;

            

            if($model->validate()){
                
                if($model->save()){
                    if(isset($_POST['type']) && $_POST['type']==1){
                        $this->renderPartial('_comment_super',array('model'=>$model,'supermodel'=>$review));
                    } else{
                        // $this->renderPartial('_comment',array('model'=>$model));
                    }
                    
                } 
                
            } 

        }
        Yii::app()->end();
    }
 
}
