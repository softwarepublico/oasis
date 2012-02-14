<?php
/** Zend_Form_Element_Xhtml */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Text form element
 * 
 * @category   Base
 * @package    Base_Form
 * @subpackage Element
 */
class Base_Form_Element_AnoCombo extends Zend_Form_Element_Xhtml
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'anoCombo';
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
