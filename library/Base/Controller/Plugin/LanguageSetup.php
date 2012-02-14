<?php
/**
 * Classe para realizar a internacionalização do sistema
 * @since 26/11/2009
 */

class Base_Controller_Plugin_LanguageSetup extends Zend_Controller_Plugin_Abstract
{
    protected $_languages;
    protected $_directory;

    public function __construct($directory, $languages)
    {
        $this->_directory = $directory;
        $this->_languages = $languages;
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        //Obtém o idioma selecionado
//        $lang = $this->getRequest()->getParam('lang');
        $lang = $_SESSION['language'];

        //Garante que o idioma selecionado é permitido
        if(!in_array($lang, array_keys($this->_languages['languages']))){
            $lang = 'pt';
        }

        //Define objeto locale
        $localeString = $this->_languages['languages'][$lang];
        $locale       = new Zend_Locale($localeString);

        //Carrega arquivo de tradução
        $file = $this->_directory.DIRECTORY_SEPARATOR.$localeString.'.php';
        $translationStrings = "";
        if(file_exists($file)){
            include $file;
        } else {
            include $this->_directory.DIRECTORY_SEPARATOR.'pt_BR.php';
        }

        //Verifica se o idioma possui internacionalização
        if(empty($translationStrings)){
            throw new Exception('Arquivo inexistente para internacionalização');
        }

        $this->_translatorScript($translationStrings['js']);
        //Cria objeto Zend_Translate
        $translate = new Zend_Translate('array', $translationStrings, $localeString);

        //Atribui ao registro
        Zend_Registry::set('lang', $lang);
        Zend_Registry::set('localeString', $localeString);
        Zend_Registry::set('locale', $locale);
        Zend_Registry::set('Zend_Translate', $translate);
    }

    private function _translatorScript(Array $translatorJs = array())
    {
         $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        //Inicializa ViewRenderer
        $viewRenderer->init();
        $view = $viewRenderer->view;

        $scriptInicial = " var i18n = ".Zend_Json::encode($translatorJs).";";
        $view->headScript()->appendScript($scriptInicial);
    }
}