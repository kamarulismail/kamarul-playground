<?php


class YConsoleApplication extends CConsoleApplication
{
    protected function init()
    {
        parent::init();
    }
    
    /**
	 * Creates the command runner instance.
	 * @return CConsoleCommandRunner the command runner
	 */
	protected function createCommandRunner()
	{
		return new CConsoleCommandRunner;
	}
    
    public function __construct()
    {
        parent::__construct();
        
        #$this->getUrlManager()->setBaseUrl('yakimbi_eplatform.com');
        #$this->getUrlManager()->setUrlFormat('path');        
        #$this->getRequest()->setHostInfo('serverX');
        #var_dump($this->request);
    }

    /**
	 * Creates a relative URL based on the given controller and action information.
	 * @param string $route the URL route. This should be in the format of 'ControllerID/ActionID'.
	 * @param array $params additional GET parameters (name=>value). Both the name and value will be URL-encoded.
	 * @param string $ampersand the token separating name-value pairs in the URL.
	 * @return string the constructed URL
	 */
	public function createUrl($route,$params=array(),$ampersand='&')
	{
		return $this->getUrlManager()->createUrl($route,$params,$ampersand);
	}

    /**
	 * Creates an absolute URL based on the given controller and action information.
	 * @param string $route the URL route. This should be in the format of 'ControllerID/ActionID'.
	 * @param array $params additional GET parameters (name=>value). Both the name and value will be URL-encoded.
	 * @param string $schema schema to use (e.g. http, https). If empty, the schema used for the current request will be used.
	 * @param string $ampersand the token separating name-value pairs in the URL.
	 * @return string the constructed URL
	 */
	public function createAbsoluteUrl($route,$params=array(),$schema='',$ampersand='&')
	{
		$url=$this->createUrl($route,$params,$ampersand);
		if(strpos($url,'http')===0)
			return $url;
		else
			return $this->getRequest()->getHostInfo($schema).$url;
	}
    
    /**
	 * Returns the relative URL for the application.
	 * This is a shortcut method to {@link CHttpRequest::getBaseUrl()}.
	 * @param boolean $absolute whether to return an absolute URL. Defaults to false, meaning returning a relative one.
	 * This parameter has been available since 1.0.2.
	 * @return string the relative URL for the application
	 * @see CHttpRequest::getBaseUrl()
	 */
	public function getBaseUrl($absolute=false)
	{
		return $this->getRequest()->getBaseUrl($absolute);
	}
}

?>
