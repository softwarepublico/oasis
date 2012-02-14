<?php
class Base_Controller_Plugin_ModelDirSetup extends Zend_Controller_Plugin_Abstract
{
    protected $_baseIncludePath;

    public function __construct()
    {
        //armazena caminho de include corrente
        $this->_baseIncludePath = get_include_path();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $request->getModuleName();

        $fc = Zend_Controller_Front::getInstance();
        $controllerDir = $fc->getControllerDirectory($moduleName);

        //Define diret'orio de modelos
        $moduleDir = dirname($controllerDir);
        $modelsDir = $moduleDir.'/models';

        //Define caminho de include
        set_include_path($modelsDir . $this->_baseIncludePath);
    }
}