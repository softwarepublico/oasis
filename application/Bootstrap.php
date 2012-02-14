<?php
class Bootstrap
{
    private $_configSection;
    private $_configLanguage;

    public function __construct($configSection)
    {
        $this->_configSection = $configSection;
        $this->_configureHeader();
        $this->_configureIni();

        $rootDir = dirname(dirname(__FILE__));
        define('ROOT_DIR',$rootDir);

        set_include_path(
              PATH_SEPARATOR . ROOT_DIR . '/application/modules/default/models'
            . PATH_SEPARATOR . ROOT_DIR . '/application/modules/default/models/business'
            . PATH_SEPARATOR . ROOT_DIR . '/application/modules/default/models/grafico'
            . PATH_SEPARATOR . ROOT_DIR . '/application/modules/default/views/form'
            . PATH_SEPARATOR . ROOT_DIR . '/application/configuration'
            . PATH_SEPARATOR . ROOT_DIR . '/library'
            . PATH_SEPARATOR . ROOT_DIR . '/library/ezc'
            . PATH_SEPARATOR . ROOT_DIR . '/library/Base'
            . PATH_SEPARATOR . get_include_path()
        );

        //Constantes do sistema
        include_once 'const.php';
        include_once 'const_table.php';
        
        //Inclusão do firebug no sistema
        include_once 'Zend/Db/Profiler/Firebug.php';
        //Inclusão da Base para execulta o autoload proprio da Zend
        include_once 'ezc/Base/src/base.php';
        include_once 'Zend/Loader/Autoloader.php';

        try {
            //Inclusão do novo AutoLoader da versão da zend 1.8.4
            //Inicializa o autoloader padrão para Zend_ e ZendX_
            $autoloader = Zend_Loader_Autoloader::getInstance();
            //Inicializa o autoloader padrão para Zend_ e ZendX_
            $autoloader->setFallbackAutoloader(true);
            //Registra o namespace Base_ no autoloader
            $autoloader->registerNamespace('Base_');
            //Incluindo o método da ez para execultar o autoload das classes da ezComponents
            $autoloader->pushAutoloader(array('ezcBase', 'autoload'), 'ezc');

            //Registra as informações do confi.ini
            $registry = Zend_Registry::getInstance();
            $config   = new Zend_Config_Ini(ROOT_DIR . '/application/configuration/config.ini');
            $registry->set( 'config', $config );
            //Configura a data do sistema
            date_default_timezone_set($config->configuration->date_default_timezone);
        } catch (Exception $e) {
            $this->_exception($e);
        }
    }
    
    public function runApp()
    {
        Zend_Session::start();

        $this->_configureLayout();
		$this->_i18n();

        /*
         * Configura o banco de dados do sistema
         */
        $this->_configureBanco();
        $front = Zend_Controller_Front::getInstance();
        $front->setBaseUrl('/'.SYSTEM_NAME);
        $front->throwExceptions(false); // should be turned on in development time
        $front->registerPlugin( new Zend_Controller_Plugin_ErrorHandler( array(
                    'module' => 'default',
                    'controller' => 'error',
                    'action' => 'error'
                ) ) );
        $front->registerPlugin(new Base_Controller_Plugin_ViewSetup());
        $front->registerPlugin(new Base_Controller_Plugin_ModelDirSetup());
        
        $directory = ROOT_DIR."/application/configuration/i18n";
        $languages = $this->_configLanguage->toArray();
        $front->registerPlugin(new Base_Controller_Plugin_LanguageSetup($directory, $languages));
        $front->dispatch();
    }

    private function _configureBanco()
    {
        try{
            $configSelection = $this->_configSection;
            //carregando configurações de banco
            $banco = Zend_Registry::get('config')->$configSelection;

            //Registrando os dados do banco de dados
            $registry = Zend_Registry::getInstance();
            $registry->set( 'banco', $banco );

            //Determinando a conexão padrão
            $db = Zend_Db::factory ( $banco->db->adapter, $banco->db->config->toArray() );
            Zend_Db_Table::setDefaultAdapter( $db );
            $registry->set( 'db', $db );

            //atachando o profiler ao db adapter (configuracao do firebug e firephp)
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled($banco->firebug->profiler->enabled);
            $db->setProfiler($profiler);

            //Setup controller
            //Definindo os modulos do sistema
            $front = Zend_Controller_Front::getInstance();
            $front->addModuleDirectory(ROOT_DIR . '/application/modules');
        }catch (Exception $e){
            $this->_exception($e);
        }
    }

    /**
     * Método desenvolvido para automatizar a internacionalização do
     * sistema
     */
    private function _i18n()
    {
        //carregando configurações das linguagens de internacionalização
        $this->_configLanguage = Zend_Registry::get('config')->languages;
        $arrLanguage = array_keys($this->_configLanguage->languages->toArray());

        //Usa o código de idioma do browser se estiver na lista permitida
        $zl = new Zend_Locale();
        $_SESSION['language'] = in_array($zl->getLanguage(), $arrLanguage) ?$zl->getLanguage():K_LANGUAGE;
    }

    private function _configureLayout()
    {
        // setup layout
        Zend_Layout::startMvc(array('layoutPath' => ROOT_DIR.'/application/modules/default/views/layouts'));
    }

    private function _configureHeader()
    {
        // Evita que os dados sejam recuperados do cache do browser.
        header("Expires:Mon, 26 Jul 1997 05:00:00 GMT"); //colocamos uma data passada para que expire
        header("Last-Modified:" . gmdate("D, dM Y H:i:s") . "GMT"); //Última modificação, justo agora.
        header("Cache-Control: no-cache, must-revalidate"); //evita que se guarde em cache, HTTP 1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache"); //evita que se guarde em cache HTTP 1.0
    }

    private function _configureIni()
    {
        ini_set('default_charset','UTF-8');
        ini_set('display_errors', 1);
        ini_set('max_execution_time','0');
        ini_set('memory_limit','1000M');
    }

    private function _exception($exception)
    {
        echo "<pre>";var_dump($exception);echo "</pre>";die("<br />LINE: ".__LINE__."<br />FILE: ".__FILE__);
    }
}