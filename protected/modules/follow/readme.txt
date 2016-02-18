Usage:

1. Connect behavior to AR model
    'follow' => array(
        'class'       => 'follow.components.FollowBehavior',
        'class_name'  => 'store.models.Products', // Alias to followable model
        'owner_title' => 'name', // Attribute name to present follow owner in admin panel
    )
2. Update view where to enable comments
    ...
    $this->renderPartial('follow.views.follow.create', array(
        'model'=>$model, // Followable model instance
    ));
    ...
3. Add captcha action
    ...
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
            ),
        );
    }
    ...