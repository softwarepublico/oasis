<?php
/** Zend_Form_Element_Multi */
require_once 'Zend/Form/Element/Multi.php';
class Base_Form_Element_Radio extends Base_Form_Element_Multi
{
    /**
     * Use formRadio view helper by default
     * @var string
     */
    public $helper = 'formRadio';
    
	/**
     * Constructor
     * 
     * @param  string|array|Zend_Config $spec Element name or configuration
     * @param  string|array|Zend_Config $options Element value or configuration
     * @return void
     */ 
    public function __construct($spec, $options = null){
        parent::__construct($spec, $options);
        $this->clearDecorators();
        $this->addDecorator('ViewHelper')
             ->addDecorator('Errors')
             ->addDecorator('Label');
    }
}