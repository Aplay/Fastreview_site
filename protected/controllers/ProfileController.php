<?php
class ProfileController extends Controller
{
	public $layout = '//layouts/profile';
	public $breadcrumbs = array('Profile');
	public $user;

	public function filters()
    {
     return array(
       // 'ajaxOnly + rejectFriend',
        'accessControl',

        );
    }

	 public function accessRules()
	 {
	    return array(
	        array('deny',
	                'actions'=>array('addContact','rejectContact','acceptContact','removeContact','notification'), // не могут быть выполнены анонимным пользователем
	                'users'=>array('?'),
	                ),
	           /* array('allow',
	                'actions'=>array('delete'),
	                'roles'=>array('admin'),
	            ),
	            array('deny',
	                'actions'=>array('delete'),
	                'users'=>array('*'),
	                ),*/
	    );
	}

	 /**
	 * Check if user is authenticated
	 * @return bool
	 * @throws CHttpException
	 */
	public function beforeAction($action)
	{
		if(Yii::app()->user->isGuest)
			Yii::app()->user->loginRequired();
		return true;
	}

	public function actionIndex()
    {

    	$user = User::model()->findByPk(Yii::app()->user->id);
    	if(!$user)
    		throw new CHttpException(404, Yii::t('site','Page not found'));

      $this->redirect($this->createUrl('/users/user/view', array('url'=>$user->username)));
    	$this->user = $user;


      $dataProviderContactList = $this->user->getContactList();

    	$this->render('index',array('dataProviderContactList'=>$dataProviderContactList));
    }


    public function actionAddContact($id)
    {

        $user_id = (int)$id;
        if(!$user_id) 
        	$this->redirect(Yii::app()->request->urlReferrer);

        $friendship_status = Yii::app()->user->getModel()->isFriendOf($user_id);
        if($friendship_status !== false)  {

            if($friendship_status == UserFriend::CONTACT_REQUEST)
                $this->addFlashMessage(Yii::t('site','Request to contact already sent'), 'error');
            if($friendship_status == UserFriend::CONTACT_ACCEPTED)
                $this->addFlashMessage(Yii::t('site','You are already in contact'), 'error');
            if($friendship_status == UserFriend::CONTACT_REJECTED)
                $this->addFlashMessage(Yii::t('site','Request to contact rejected'), 'error');
            $this->redirect(Yii::app()->request->urlReferrer);
                    //return false;
        }

        $model = new UserFriend();
        $model->friend_id = $user_id;
        $model->user_id = Yii::app()->user->id;
        $model->is_accepted = UserFriend::CONTACT_REQUEST;

        

        if($model->save()){
          // удалить похожие записи сперва 
          Yii::app()->queue->deleteMessage(Yii::app()->user->id, "User.{$model->friend_id}",  Yii::app()->user->id, 7);
          Yii::app()->queue->send(null, "User.{$model->friend_id}", null, Yii::app()->user->id, 7); // вам предложили дружить

          $this->addFlashMessage(Yii::t('site','Request to contact successfully created'), 'success');
      } else {
      	$this->addFlashMessage($model->errors, 'error');
      }


          $this->redirect(Yii::app()->request->urlReferrer);

    }

    public function actionRejectContact($id)
	{

        $friend_id = (int)$id;
        $friendship = UserFriend::model()->find(
			'((user_id = :user_id and friend_id = :friend_id) or (friend_id = :user_id and user_id = :friend_id)) and is_accepted = '.UserFriend::CONTACT_REQUEST, array(
				':user_id' => Yii::app()->user->id,
				':friend_id' => $friend_id));
        if($friendship){
           
   		$friendship->delete();

         // отправляем сообщение пользователю
         //  Events::setEvents(Yii::app()->user->id,$friend_id,8); // пользователь отклонил дружбу
           
        }
        $this->redirect(Yii::app()->request->urlReferrer);

	}

	public function actionAcceptContact($id)
	{
		
           
          
                $friend_id = (int)$id;
                $friendship = UserFriend::model()->find(
					'(user_id = :user_id and friend_id = :friend_id) or (friend_id = :user_id and user_id = :friend_id)', array(
						':user_id' => Yii::app()->user->id,
						':friend_id' => $friend_id));
                if($friendship){
                   $friendship->is_accepted = UserFriend::CONTACT_ACCEPTED;
                   
                   //отправляем сообщения всем их друзьям, о том, что они дружат
                   // находим всех друзей первого и отсылаем им уведомление
                                $friends1 = array();
                                $friends1 = User::getAllFriends(Yii::app()->user->id);
                                if($friends1){
                      
                                    foreach($friends1 as $friend){
                                        if($friend != $friend_id){
                                         //   Events::setEvents(Yii::app()->user->id,$friend,20,$friend_id,7); //  задружил 
                                        }
                                    }
                                }
                   // находим всех друзей второго и отсылаем им уведомление
                                $friends = array();
                                $friends = User::getAllFriends($friend_id);
                                if($friends){
                      
                                    foreach($friends as $friend){
                                        if(!in_array($friend, $friends1)){
                                           if($friend != $friend_id){
                                          //   Events::setEvents(Yii::app()->user->id,$friend,20,$friend_id,7); //  задружил 
                                           }
                                        }
                                      }
                                }
		   $friendship->save();
                   // отправляем сообщение пользователю
                 //  Events::setEvents(Yii::app()->user->id,$friend_id,1); // пользователь принял дружбу
                   
                   //отправляем сообщения всем их друзьям, о том, что они дружат
                   
                }
          $ref = Yii::app()->request->urlReferrer;
          if(empty($ref))
              $this->redirect(Yii::app()->createUrl('/users/user/view',array('url'=>Yii::app()->user->username)));

          $this->redirect(Yii::app()->request->urlReferrer);
		
	}

	public function actionRemoveContact($id)
    {

            $user_id = (int)$id;
            if(!$user_id) 
            	$this->redirect(Yii::app()->request->urlReferrer);

         $c = UserFriend::model()->find('(user_id=:user_id AND friend_id=:friend_id) OR (user_id=:friend_id AND friend_id=:user_id)', array('user_id' => Yii::app()->user->id, 'friend_id'=>$user_id));
         if ($c){
          $c->delete();
      //отправляем сообщения всем их друзьям, о том, что они не дружат
           // находим всех друзей первого и отсылаем им уведомление
          $friends1 = array();
          $friends1 = User::getAllFriends(Yii::app()->user->id);
          if($friends1){

            foreach($friends1 as $friend){
                           //    Events::setEvents(Yii::app()->user->id,$friend,21,$user_id,7); //  не дружит
                           }
                       }
           // находим всех друзей второго и отсылаем им уведомление
                       $friends = array();
                       $friends = User::getAllFriends($user_id);
                       if($friends){

                        foreach($friends as $friend){
                            if(!in_array($friend, $friends1)){
                                 //  Events::setEvents(Yii::app()->user->id,$friend,21,$user_id,7); //  не дружит
                               }
                           }
                       }
    		//  Events::setEvents(Yii::app()->user->id,$user_id,3); // удалил вас из друзей
      		$this->addFlashMessage(Yii::t('site','User successfully deleted from the list of your contacts'),'success');

      } else {
          $this->addFlashMessage(Yii::t('site','User is not in the list of your contacts'),'error');
      }

          $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionNotification()
    {
        $notif_count = (int)$_POST['notif_count'];

        $this->processPageRequest('page');
        
        $data_new = $data_old = array();

        $notifications = Yii::app()->queue->peekReadableAll(Yii::app()->user->id);

        if(!empty($notifications)){
            foreach($notifications as $data){
                  if($data->is_read == false){
                      $data_new[] = $data;
                  } else {
                      $data_old[] = $data;
                  }
            }
        }

        $data_all = array_merge($data_new,$data_old);

        $dataProvider = new CArrayDataProvider($data_all, array(
                'keyField' => 'id',
                'id'=>'notification_',
                'pagination'=>array(
                    'pageSize' => 5,
                    'pageVar'=>'page'
                ),

        ));
        
        $count_current_total = $notif_count;
        $count_this_page = $dataProvider->getItemCount();
        $count_rest = $count_current_total - $count_this_page;


        Yii::import('nfy.controllers.QueueController');
        
        $queueController = new QueueController('queue', Yii::app()->getModule('nfy'));
        
  
        $this->renderPartial('notification_list', array(
               // 'notif_count'=>$notif_count,
                'queueController'=>$queueController,
                'dataProvider'=>$dataProvider,
                'count_rest'=>$count_rest
        ));
        
        Yii::app()->end();
    }

    protected function processPageRequest($param='page')
    {
        if (Yii::app()->request->isAjaxRequest && isset($_POST[$param])) {
            $_GET[$param] = Yii::app()->request->getPost($param);
            return true;
        } elseif (Yii::app()->request->isAjaxRequest && isset($_GET[$param])) {
            $_GET[$param] = Yii::app()->request->getQuery($param);
            return true;
        }
        return false;
    }
    
}
?>