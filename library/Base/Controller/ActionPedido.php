<?php
class Base_Controller_ActionPedido extends Zend_Controller_Action
{
    protected $_module;

    public function init()
    {
        $this->_module = $this->_request->getModuleName();

        if ($this->_request->getControllerName() != "index") {
            $this->_validaSessionController();
        }
    }
    
    private function _validaSessionController()
    {
        if(!array_key_exists('oasis_pedido', $_SESSION)){
            $this->_redirect("/{$this->_module}/index");
        }
        $this->view->nomeUsuarioLogado = $_SESSION['oasis_pedido']['tx_nome_usuario'];
    }

    protected function toUpper($value)
    {
        $objFilter        = new Zend_Filter();
        $objStringToUpper = new Zend_Filter_StringToUpper();

        $objStringToUpper->setEncoding('UTF-8');
        $objFilter->addFilter($objStringToUpper);

        return $objFilter->filter($value);
    }

    protected function toLower($value)
    {
        $objFilter        = new Zend_Filter();
        $objStringToLower = new Zend_Filter_StringToLower();

        $objStringToLower->setEncoding('UTF-8');
        $objFilter->addFilter($objStringToLower);

        return $objFilter->filter($value);
    }
}