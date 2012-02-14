<?php
class Base_Form_Element_Data extends Zend_Form_Element_Xhtml
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'data';

    /**
     * Constructor
     * 
     * @param  string|array|Zend_Config $spec Element name or configuration
     * @param  string|array|Zend_Config $options Element value or configuration
     * @return void
     */ 
    public function __construct($spec, $options = array('class'=>'calendario-input float-l')){
        parent::__construct($spec, $options);
        $this->clearDecorators();
        $this->addDecorator('ViewHelper')
             ->addDecorator('Errors')
             ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-3 float-l'))
             ->addDecorator('Label', array('class'=>'float-l'));
    }     
}
