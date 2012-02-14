<?php
class Base_Controller_Action_Helper_Texto extends Zend_Controller_Action_Helper_Abstract
{
	public function upper($value)
	{
		return strtoupper($value);
	}
	
	public function lower($value)
	{
		return strtolower($value);
	}
	
}