<?php

class Base_Form_Element_MultiCheckbox extends Zend_Form_Element_MultiCheckbox
{
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
