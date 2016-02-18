<?php

class CommentsModule extends BaseModule
{
	/**
	 * @var string
	 */
	public $moduleName = 'comments';

	/**
	 * Init module
	 */
	public function init()
	{
		$this->setImport(array(
			'comments.models.Comment',
		));
	}

	/**
	 * @param $model
	 * @return Comment
	 */
	public function processRequest($model)
	{
        Yii::import('application.modules.users.models.User');
        Yii::import('application.modules.catalog.models.Orgs');
		$comment = new Comment;
		if(Yii::app()->request->getPost('Comment'))
		{
			$comment->attributes = Yii::app()->request->getPost('Comment');
			$ratingAjax = null;
			if(isset($_POST['Comment']['rating'])){
				$ratingAjax = (int)$_POST['Comment']['rating'];
				if($ratingAjax == 0) $ratingAjax = null;
			}
			$comment->rating = $ratingAjax;

			if(!Yii::app()->user->isGuest)
			{
				$comment->name = Yii::app()->user->username;
				$comment->email = Yii::app()->user->email;
			}
			
			$comment->status = Comment::STATUS_WAITING;
			if($comment->validate())
			{
				// $pkAttr = $model->getObjectPkAttribute();
				// $comment->class_name = $model->getClassName();
				$comment->object_pk = $model->id;
				$comment->user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
				if(!$comment->save()){
					// VarDumper::dump($comment->errors); die(); // Ctrl + X  Delete line
				}

				$url = Yii::app()->getRequest()->getUrl();
			//	if($comment->rating) {
			//		$this->starRating($comment->object_pk, $comment->rating);
			//	}
				

				if($comment->status==Comment::STATUS_WAITING)
				{
					$url.='#';
					Yii::app()->user->setFlash('messages', 'Ваш комментарий успешно добавлен. ');
              
				} elseif($comment->status==Comment::STATUS_APPROVED){
					$url.='#comment_'.$comment->id;
				}
               
	                if(Yii::app()->request->isAjaxRequest){
	                	echo '[]';
	                	Yii::app()->end();
	                } else {
					// Refresh page
					Yii::app()->request->redirect($url, true);
					}
			} 
		}
		return $comment;
	}

	/**
	 * @param $model
	 * @return Comment
	 */
	public function processRequestSpec($model)
	{
        Yii::import('application.modules.users.models.User');
        Yii::import('application.modules.catalog.models.Spec');
		$comment = new CommentSpec;
		if(Yii::app()->request->getPost('CommentSpec'))
		{
			$comment->attributes = Yii::app()->request->getPost('CommentSpec');
			$ratingAjax = null;
			if(isset($_POST['CommentSpec']['rating'])){
				$ratingAjax = (int)$_POST['CommentSpec']['rating'];
				if($ratingAjax == 0) $ratingAjax = null;
			}
			$comment->rating = $ratingAjax;

			if(!Yii::app()->user->isGuest)
			{
				$comment->name = Yii::app()->user->username;
				$comment->email = Yii::app()->user->email;
			}
			
			$comment->status = CommentSpec::STATUS_WAITING;
			if($comment->validate())
			{
				// $pkAttr = $model->getObjectPkAttribute();
				// $comment->class_name = $model->getClassName();
				$comment->object_pk = $model->id;
				$comment->user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
				if(!$comment->save()){
					// VarDumper::dump($comment->errors); die(); // Ctrl + X  Delete line
				}

				$url = Yii::app()->getRequest()->getUrl();
			//	if($comment->rating) {
			//		$this->starRating($comment->object_pk, $comment->rating);
			//	}
				

				if($comment->status==CommentSpec::STATUS_WAITING)
				{
					$url.='#';
					Yii::app()->user->setFlash('messages', 'Ваш комментарий успешно добавлен. ');
              
				} elseif($comment->status==CommentSpec::STATUS_APPROVED){
					$url.='#comment_'.$comment->id;
				}
               
	                if(Yii::app()->request->isAjaxRequest){
	                	echo '[]';
	                	Yii::app()->end();
	                } else {
					// Refresh page
					Yii::app()->request->redirect($url, true);
					}
			} 
		}
		return $comment;
	}
	/**
	 * @param $model
	 * @return Comment
	 */
	public function processRequestArticle($model)
	{
        Yii::import('application.modules.users.models.User');
        Yii::import('application.modules.catalog.models.Article');
		$comment = new CommentArticle;
		if(Yii::app()->request->getPost('CommentArticle'))
		{
			$comment->attributes = Yii::app()->request->getPost('CommentArticle');

			if(!Yii::app()->user->isGuest)
			{
				$comment->name = Yii::app()->user->username;
				$comment->email = Yii::app()->user->email;
			}
			
			$comment->status = CommentArticle::STATUS_APPROVED;
			if($comment->validate())
			{
				// $pkAttr = $model->getObjectPkAttribute();
				// $comment->class_name = $model->getClassName();
				$comment->object_pk = $model->id;
				$comment->user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
				if(!$comment->save()){
					// VarDumper::dump($comment->errors); die(); // Ctrl + X  Delete line
				}

				$url = Yii::app()->getRequest()->getUrl();
			//	if($comment->rating) {
			//		$this->starRating($comment->object_pk, $comment->rating);
			//	}
				

				if($comment->status==CommentArticle::STATUS_WAITING)
				{
					$url.='#';
					Yii::app()->user->setFlash('messages', 'Ваш комментарий успешно добавлен. ');
              
				} elseif($comment->status==CommentArticle::STATUS_APPROVED){
					$url.='#comment_'.$comment->id;
				}
               
	                if(Yii::app()->request->isAjaxRequest){
	                	echo '[]';
	                	Yii::app()->end();
	                } else {
					// Refresh page
					Yii::app()->request->redirect($url, true);
					}
			} 
		}
		return $comment;
	}
	public function starRating($id, $ratingAjax) {

      $id = (int)$id;
      $org = Orgs::model()->findByPk($id);
      if($org){

          $rating = OrgsRating::model()->findByAttributes(array(
            'org'=>$id
          ));
          // если не было категории - делаем
          if(!$rating)
          {
            $rating = new OrgsRating;
            $rating->org = $id;
            $rating->vote_count = 1;
            $rating->vote_sum = $ratingAjax;
            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
            $rating->save(false);

            $org->rating_id = $rating->id;
            $org->save(false,array('rating_id'));                    
          } else {
            $rating->vote_count = $rating->vote_count + 1;
            $rating->vote_sum = $rating->vote_sum + $ratingAjax;
            $rating->vote_average = round($rating->vote_sum / $rating->vote_count,2);
            if(!$rating->save()){
                VarDumper::dump($rating->errors); die(); // Ctrl + X  Delete line
            }
          }
          
    }
    return true;
    }
        
 /*       public $urlRules = array(
	'/admin/comments'=>'/comments/admin/comments',
	'/admin/comments/<action>'=>'/comments/admin/comments/<action>',

        );*/
}