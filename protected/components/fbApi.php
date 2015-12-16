<?php

class fbApi extends CApplicationComponent
{
    public $clientId;
    public $clientSecret;
    public $redirectUri;
    public $redirectTo = '/';

    public $scope = 'public_profile,email,user_about_me,user_birthday,user_website';

    protected $authUrl = 'https://www.facebook.com/dialog/oauth';

    public function getAuthUrl($redirectTo = null)
    {
        if($redirectTo)
            $this->redirectTo = $redirectTo;
        Yii::app()->session['socredirect'] = $this->redirectTo;
        
        $params = array(
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => $this->scope
        );

        return $this->authUrl . '?' . urldecode(http_build_query($params));
    }

}

?>