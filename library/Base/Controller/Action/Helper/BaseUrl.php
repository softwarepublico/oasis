<?php
class Base_Controller_Action_Helper_BaseUrl extends Zend_Controller_Action_Helper_Abstract
{
	public function baseUrl()
	{
		$fc = Zend_Controller_Front::getInstance();
		return $fc->getBaseUrl();
	}
}