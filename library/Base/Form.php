<?php
class Base_Form extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$translate = Zend_Registry::get('Zend_Translate');
		$this->setTranslator($translate);
	}
}