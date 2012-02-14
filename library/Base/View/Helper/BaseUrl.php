<?php
class Base_View_Helper_BaseUrl
{
	public function baseUrl($baseUrl = "getBaseUrl")
	{
		$baseUrl = $this->$baseUrl();
		return $baseUrl;
	}

	private function getBaseUrl()
	{
        $fc = Zend_Controller_Front::getInstance();
        return $fc->getBaseUrl();
  	}

    private function getBaseUrlScript()
    {
        $fc = Zend_Controller_Front::getInstance();
        $module = $fc->getBaseUrl()."/public/js/modules/".$fc->getRequest()->getModuleName();
        
        return $module;
    }
}