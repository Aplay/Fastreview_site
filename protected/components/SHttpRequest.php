<?php

/**
 * SHttpRequest
 */
class SHttpRequest extends CHttpRequest {

	private $_pathInfo;
	public $noCsrfValidationRoutes;
    private $_csrfToken;
    public $csrfTokenName = "HiddenPropertyValue";


	/**
	 * @return string Parsed path info without lang prefix.
	 */
	public function getPathInfo()
	{
		$langCode = null;
		$pathInfo = parent::getPathInfo();
                 
		if($this->_pathInfo===null)
		{
			$pathInfo = parent::getPathInfo();
			$parts = explode('/', $pathInfo);
                        /*
			if (in_array($parts[0], Yii::app()->languageManager->getCodes()))
			{
				// Valid language code detected.
				// Remove it from url path to make route work and activate lang
				$langCode = $parts[0];

				// If language code are is equal default show 404 page
				if($langCode === Yii::app()->languageManager->default->code)
					throw new CHttpException(404, Yii::t('core', 'Страница не найдена.'));

				unset($parts[0]);
				$pathInfo = implode($parts, '/');
			}
                         */
			$this->_pathInfo = $pathInfo;
		}

		// Activate language by code
		//Yii::app()->languageManager->setActive($langCode);
                 
		return $pathInfo;
	}

	/**
	 * Add param to current url. Url is based on $data and $_GET arrays
	 * @param $data array of the data to add to the url.
	 * @param $selectMany
	 * @param $exclude exclude param from path
	 * @return string
	 */
	public function addUrlParam($route, $data, $selectMany=false, $exclude=false)
	{
		$city = '';
		if($_GET['city']){
        	$city = $_GET['city'];
        }
		foreach($data as $key=>$val)
		{
			if(isset($_GET[$key]) && $key !== 'url' && $selectMany === true)  // если запрос типа /catalog/goods/shop/1;3;4
			{
				$tempData = explode(';', $_GET[$key]);
				$data[$key] = implode(';', array_unique(array_merge((array)$data[$key], $tempData)));
			}
		}
		$set = array();
                if($_GET){
                foreach($_GET as $key=>$value){
             
	                	if(!empty($exclude)){
		                    if($key != $exclude){
		                        $set[$key] = $value;
		                    } 
	                    } else {
	                    	$set[$key] = $value;
	                    }
              	    
                }

                }
		return Yii::app()->createUrl($route, CMap::mergeArray($set, $data));
	}

	public function addUrlParamCat($data, $exclude=false)
	{
	        
			$city = '';
			if($_GET['city']){
	        	$city = $_GET['city'];
	        }
	        $route = $city.'/catalog/'.Yii::app()->request->getQuery('url');
 
                
            	if(isset($_GET['url'])){
                    if($_GET['url'] != 'goods' && $_GET['url'] != 'services' && $_GET['url'] != 'all'){
                        $tempData = explode(';', $_GET['url']);
                        $route = $city.'/catalog/'.implode(';', array_unique(array_merge($tempData, $data)));
                        
                    } else {
                        
                        $route = $city.'/catalog/'.$data[0];
                    }
                
                } else {
                	$route = $city.'/catalog/'.$data[0];
                }
		$set = array();
        
                if($_GET){
                foreach($_GET as $key=>$value){
                	
                    if($key != 'url' && $key != 'city'){
                        $set[$key] = $value;
                    } 
                }
                if(!empty($exclude)){
		            unset($set[$exclude]);
                }
               }
		return Yii::app()->createUrl($route, $set);
	}

        
	/**
	 * Delete param/value from current
	 * @param string $key to remove from query
	 * @param null $value If not value - delete whole key
	 * @return string new url
	 */
	public function removeUrlParam($route, $key, $value=null)
	{
		$get = $_GET;
		if(isset($get[$key]))
		{
			if($value === null)
				unset($get[$key]);
			else
			{
				$get[$key] = explode(';', $get[$key]);
				$pos = array_search($value, $get[$key]);
				// Delete value
				if(isset($get[$key][$pos]))
					unset($get[$key][$pos]);
				// Save changes
				if(!empty($get[$key]))
					$get[$key] = implode(';', $get[$key]);
				// Delete key if empty
				else
					unset($get[$key]);
			}
		}
		return Yii::app()->createUrl($route, $get);
	}
        
	public function removeUrlParamCat($rootcat, $data)
	{
		$route = '/catalog/'.Yii::app()->request->getQuery('url');
                $url = Yii::app()->request->getQuery('url');
                $get = $_GET;
		if(isset($get['url'])){
                    if($get['url'] != 'goods' && $get['url'] != 'services' && $get['url'] != 'all'){
                        $tempData = explode(';', $get['url']);
                        $pos = array_search($data,  $tempData);
                        if(isset($tempData[$pos]))
					unset($tempData[$pos]);
                        // Save changes
				if(!empty($tempData))
					$url = implode(';', $tempData);
                                // Delete key if empty
				else
					$url = $rootcat;
                        $route = '/catalog/'.$url;
                    } else {
                        $route = '/catalog/'.$data;
                    }
                
                }
                $set = array();
                if($_GET){
                foreach($_GET as $key=>$value){
                    if($key != 'url'){
                        $set[$key] = $value;
                    }
                }
                }
		return Yii::app()->createUrl($route, $set);
	}
	/**
	 * Normalize request.
	 * Disable CSRF for payment controller
	 */
	protected function normalizeRequest()
	{
		parent::normalizeRequest();
                
		if($this->enableCsrfValidation && $this->isCLI()===false)
		{
			$url=$this->getRequestUri();
			foreach($this->noCsrfValidationRoutes as $route)
			{
				if(substr($url,0,strlen($route))===$route)
					Yii::app()->detachEventHandler('onBeginRequest', array($this,'validateCsrfToken'));
			}
		}
                 
	}       

	/**
	 * Check if script launched from command line
	 * @return bool
	 */
	protected function isCLI()
	{
		if (substr(php_sapi_name(), 0, 3) === 'cli')
			return true;
		else
			return false;
	}
        
     /*  
        public function getCsrfToken()
        {
            if($this->_csrfToken===null)
            {
                $session = Yii::app()->session;
                $csrfToken=$session->itemAt($this->csrfTokenName);
                if($csrfToken===null)
                {
                    $csrfToken = sha1(uniqid(mt_rand(),true));
                    $session->add($this->csrfTokenName, $csrfToken);
                }
                $this->_csrfToken = $csrfToken;
            }

            return $this->_csrfToken;
        }
        
        
        public function validateCsrfToken($event)
        {
            if($this->getIsPostRequest()) {
                // only validate POST requests
                $session=Yii::app()->session;
   
                if($session->itemAt($this->csrfTokenName) && isset($_REQUEST[$this->csrfTokenName]))
                {
                    $tokenFromSession=$session->itemAt($this->csrfTokenName);
                    $tokenFromPostOrGet=$_REQUEST[$this->csrfTokenName];
                    $valid=$tokenFromSession===$tokenFromPostOrGet;
                }
                else
                    $valid=false;
                if(!$valid)
                    throw new CHttpException(400,Yii::t('yii','The CSRF token could not be verified.'));
                  
            } 
        }
        */
}