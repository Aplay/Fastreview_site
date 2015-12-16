<?php

class vkApi extends CApplicationComponent
{
    public $clientId;
    public $clientSecret;
    public $redirectUri;
    public $redirectTo = '/';
    public $scope = 'email,user_birthday';

    protected $accessUid;
    protected $accessToken;
    protected $accessSecret;

    protected $authUrl = 'http://oauth.vk.com/authorize';

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

    public function getTokenServer()
    {
        $this->accessToken = null;
        $request = 'https://oauth.vk.com/access_token?client_id='.$this->clientId.'&client_secret='. $this->clientSecret . '&grant_type=client_credentials';
        $responce = @json_decode(file_get_contents($request), true);
        if (!empty($responce['access_token'])){
            $this->accessToken = $responce['access_token'];
            return $responce;
        }
    }

    public function getTokenClient($code = null, $redirectUri)
    {
        $this->accessToken = null;
        if (empty($code))
            return;

        $params = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $redirectUri
        );

        $request = 'https://oauth.vk.com/access_token?' . http_build_query($params);
        $responce = @json_decode(file_get_contents($request), true);

        if (!empty($responce['access_token'])){
            $this->accessToken = $responce['access_token'];
            return $responce;
        }
    }

    /**
     * @param string $method
     * @param mixed $parameters
     * @return mixed
     */
    public function callMethod($method, $parameters)
    {
        if (!$this->accessToken)
            return false;

        if (is_array($parameters))
            $parameters = http_build_query($parameters);

        $queryString = "/method/$method?$parameters&access_token={$this->accessToken}";

        $querySig = md5($queryString . $this->accessSecret);

        return json_decode(file_get_contents(
            "https://api.vk.com{$queryString}&sig=$querySig"
        ), true);
    }

    /**
     * @param string $message
     * @param bool $fromGroup
     * @param bool $signed
     * @return mixed
     */
    public function wallPostMsg($message, $owner_id)
    {
        return $this->callMethod('wall.post', array(
            'owner_id' => $owner_id,
            'message' => $message
        ));
    }

    /**
     * @param string $attachment
     * @param null|string $message
     * @param bool $fromGroup
     * @param bool $signed
     * @return mixed
     */
    public function wallPostAttachment($attachment, $message = null, $fromGroup = true, $signed = false)
    {
        return $this->callMethod('wall.post', array(
            'owner_id' => -1 * $this->groupId,
            'attachment' => strval($attachment),
            'message' => $message,
            'from_group' => $fromGroup ? 1 : 0,
            'signed' => $signed ? 1 : 0,
        ));
    }

    /**
     * @param string $file relative file path
     * @return mixed
     */
    public function createPhotoAttachment($file)
    {
        $result = $this->callMethod('photos.getWallUploadServer', array(
            'gid' => $this->groupId
        ));

        $ch = curl_init($result->response->upload_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'photo' => '@' . getcwd() . '/' . $file
        ));

        if (($upload = curl_exec($ch)) === false) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        $upload = json_decode($upload);
        $result = $this->callMethod('photos.saveWallPhoto', array(
            'server' => $upload->server,
            'photo' => $upload->photo,
            'hash' => $upload->hash,
            'gid' => $this->groupId,
        ));

        return $result->response[0]->id;
    }

    public function combineAttachments()
    {
        $result = '';
        if (func_num_args() == 0) return '';
        foreach (func_get_args() as $arg) {
            $result .= strval($arg) . ',';
        }
        return substr($result, 0, strlen($result) - 1);
    }
}