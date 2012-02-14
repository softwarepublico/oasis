<?php
/**
 * Classe que configura os requisitos iniciais, do CSS e de codificação de saída antes de renderizar o layout.
 */
class Base_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract
{
    /**
     * var Base_View
     */
     protected $_view;

     public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
     {
        $this->_viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        //Inicializa ViewRenderer
        $this->_viewRenderer->init();

        $this->_view = $this->_viewRenderer->view;

        //Configura variáveis que podem ser necessárias na visão
        $this->_view->originalModule     = $request->getModuleName();
        $this->_view->originalController = $request->getControllerName();
        $this->_view->originalAction     = $request->getActionName();

        //Configura doctype para helpers da visão
        $this->_view->doctype('XHTML1_STRICT');

        //Adiciona caminho do novo helper aos helpers de visão do Base
        self::_configureHelpers();
        //Define o tipo de conteúdo
        self::_configureHeadMeta();
        //Defini o Titulo do Sistema
        self::_configureTitle();
        //Carrega os CSS's do sistema
        self::_configureCss();
        //Carrega favicon do sistema
        self::_configureFavicon();
        //Carrega os JS's do sistema
        self::_configureFileScriptDefault();
        self::_appendScript();
     }

     private function _configureHelpers()
     {
        $prefixView       = 'Base_View_Helper';
        $prefixController = 'Base_Controller_Action_Helper';
        $dirView          = dirname(__FILE__).'/../../View/Helper';
        $dirController    = dirname(__FILE__).'/../Action/Helper';
        $this->_view->addHelperPath($dirView, $prefixView);
        Zend_Controller_Action_HelperBroker::addPath($dirController, $prefixController);
     }
     
     private function _configureHeadMeta()
     {
        $this->_view->headMeta()->setName('Content-Type','text/html;charset=utf-8');
        $this->_view->headMeta()->setName('cache-control','no-cache');
     }
     
     private function _configureTitle()
     {
        $this->_view->headTitle( K_CREATOR_SYSTEM );
        $this->_view->headTitle( K_TITLE_SYSTEM );
        
        $this->_view->headTitle()->setSeparator(' - ');
     }
     
     /**
      * Método que carrega o CSS do sistema
      */
     private function _configureCss()
     {
        //Incui arquivo CSS padrão
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl().'/public/css/dialog.css','screen, projection');
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl().'/public/css/oasisImport.css','screen, projection');
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl().'/public/css/print.css','print');
        
        //CSS da TreeView
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl().'/public/js/lib/jquery/treeview/jquery.treeview.css','screen, projection');
     }
     
     private function _configureFavicon()
     {
        $this->_view->headLink()->headLink(array(
                     'rel'  => 'shortcut icon',
                     'href' => $this->_view->baseUrl().'/public/img/favicon.gif',
                     'type' => 'image/x-icon'
                 )
             );
     }

     private function _configureFileScriptDefault()
     {
         $this->_view->headScript()->appendFile($this->_view->baseUrl().'/public/js/lib/jquery/jquery-1.3.1.min.js','text/javascript',array('language'=>'javascript'));
         $this->_view->headScript()->appendFile($this->_view->baseUrl().'/public/js/lib/jquery/ui/jquery-ui-1.7.2.custom.min.js','text/javascript',array('language'=>'javascript'));
         $this->_view->headScript()->appendFile($this->_view->baseUrl().'/public/js/oasis.js','text/javascript',array('language'=>'javascript'));
         $this->_view->headScript()->appendFile($this->_view->baseUrl().'/public/js/oasis.msg.js','text/javascript',array('language'=>'javascript'));
     }

     private function _appendScript()
     {
         
         $scriptInicial  = " var welcome = false; ";
         if( (isset($_SESSION['oasis_logged']) && $this->_view->originalModule != 'pedido') || (isset($_SESSION['oasis_pedido']) && $this->_view->originalModule == 'pedido')){
            $scriptInicial  = " var welcome = true; ";
         }
         
         $scriptInicial .= " var systemName = '{$this->_view->baseUrl()}';";
         $scriptInicial .= " var systemNameModule = '{$this->_view->originalModule}';";
         
         $scriptInicial .= "
            $(document).ready(function(){
                $('.buttonBarLeft').css('display',''); ";
         
         if(key_exists('oasis_logged',$_SESSION)) {
            if(key_exists('cd_profissional',$_SESSION['oasis_logged'][0])) {
                if($_SESSION['oasis_logged'][0]['cd_profissional'] === "0") {
                    $scriptInicial .= " jQuery('#cabeca').height('112px'); ";
                }
            }
         } else {
             $scriptInicial .= "
                    $('#sair a').hide();
                    $('#sair').css('padding-right',5);
                    $('#sair').css('margin-left',-1); ";
         }

         $scriptInicial .= " }); ";
         
         $this->_view->headScript()->appendScript($scriptInicial);
     }
}