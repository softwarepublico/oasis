<?php
/**
 * @package base
 * @author Adler Medrado
 * @email adler@neshertech.net
 * @since 24/02/2008
 * ---------------------------------------------
 * Alteração da estrutura do sistema para Módulo
 * @author Wunilberto Melo
 * @email wunilberto@gmail.com
 * @since 29/06/2009
 * ---------------------------------------------
 * Inclusão da integração do ezComponent com a 
 * Zend Framework
 * @author Wunilberto Melo
 * @email wunilberto@gmail.com
 * @since 29/06/2009
 */
session_start();

// Evita que os dados sejam recuperados do cache do browser.
header("Expires:Mon, 26 Jul 1997 05:00:00 GMT"); //colocamos uma data passada para que expire
header("Last-Modified:" . gmdate("D, dM Y H:i:s") . "GMT"); //Última modificação, justo agora.
header("Cache-Control: no-cache, must-revalidate"); //evita que se guarde em cache, HTTP 1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); //evita que se guarde em cache HTTP 1.0

ini_set('default_charset','UTF-8');
ini_set('display_errors', 1);
ini_set('max_execotion_time','0');
ini_set('memory_limit','1000M');

error_reporting(E_ALL | E_STRICT);
//date_default_timezone_set('America/Sao_Paulo');
date_default_timezone_set('Etc/GMT+3');

set_include_path('.' 
. PATH_SEPARATOR . './application/default/models'
. PATH_SEPARATOR . './application/default/models/business'
. PATH_SEPARATOR . './application/default/models/grafico'
. PATH_SEPARATOR . './application/default/views/form'
. PATH_SEPARATOR . './application/default/i18n'
. PATH_SEPARATOR . './application/default/config'
//Include Path do Modulo de RelatorioProjeto
. PATH_SEPARATOR . './application/relatorioProjeto/application/models'
//Include Path do Modulo de RelatorioDemanda
. PATH_SEPARATOR . './application/relatorioDemanda/application/models'
//Include Path do Modulo de RelatorioDiverso
. PATH_SEPARATOR . './application/relatorioDiverso/application/models'
. PATH_SEPARATOR . './lib'
. PATH_SEPARATOR . './lib/ezc'
. PATH_SEPARATOR . './lib/Base'
. PATH_SEPARATOR . get_include_path()
);

//Constantes do sistema
include_once 'const.php';
// Internacionalizacao
include_once 'i18n.php';
//Inclusão do firebug no sistema
include_once 'Zend/Db/Profiler/Firebug.php';
//Inclusão da Base para execulta o autoload proprio da Zend
include_once 'ezc/Base/src/base.php';
include_once 'Zend/Loader/Autoloader.php';

try {
	//Inclusão do novo AutoLoader da versão da zend 1.8.4
	// Inicializa o autoloader padrão para Zend_ e ZendX_ 
	$autoloader = Zend_Loader_Autoloader::getInstance();
	// Inicializa o autoloader padrão para Zend_ e ZendX_ 
	$autoloader->setFallbackAutoloader(true);
	// Registra o namespace Base_ no autoloader 

	$autoloader->registerNamespace('Base_');
	//Incluindo o método da ez para execultar o autoload das classes da ezComponents
	$autoloader->pushAutoloader(array('ezcBase', 'autoload'), 'ezc');

    // doctype config
    $doctypeHelper = new Zend_View_Helper_Doctype();
    $doctypeHelper->doctype('XHTML1_STRICT');
	/*
	 * Condição que verifica se o OASIS esta instalado e com todas as configurações
	 * do sistema corretas. Caso contrario o sistema ira carregar com as configurações
	 * padrões
	 */
	if(K_INSTALL != "N") {
		// carregando configurações de banco
		$banco = new Zend_Config_Ini('./application/default/config/banco.ini', 'prod');
		//Registrando os dados do banco de dados
		$registry = Zend_Registry::getInstance();
		$registry->set( 'banco', $banco );

		// determinando a conexão padrão
		$db = Zend_Db::factory ( $banco->db->adapter, $banco->db->config->toArray() );
		Zend_Db_Table::setDefaultAdapter ( $db );
		$registry->set( 'db', $db );

		// atachando o profiler ao db adapter (configuracao do firebug e firephp)
		$profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
		$profiler->setEnabled($banco->firebug->profiler->enabled);
		$db->setProfiler($profiler);

		// Carregando arquivo de internacionalização
		$translate = new Zend_Translate('array', $portugues, 'pt_BR');
		$registry->set('translate', $translate);

		// setup layout
		Zend_Layout::startMvc(array('layoutPath' => './application/default/views/layouts'));

		// setup controller
		$front = Zend_Controller_Front::getInstance();
		$front->throwExceptions(false);
		//Definindo os modulos do sistema
		$front->setControllerDirectory(Array(
			'default'   	  =>'./application/default/controllers/',
			'relatorioDemanda'=>'./application/relatorioDemanda/application/controllers/',
			'relatorioDiverso'=>'./application/relatorioDiverso/application/controllers/',
			'relatorioProjeto'=>'./application/relatorioProjeto/application/controllers/'
		));
		//$front->setParam('useDefaultControllerAlways', true);
		$front->dispatch();
	} else {
		// setup layout
		Zend_Layout::startMvc(array('layoutPath' => './application/default/views/layouts'));
		
		/**
		 * Setup controller
		 */
		$front = Zend_Controller_Front::getInstance();
		$front->setControllerDirectory('./application/install/application/controllers/');
		$front->throwExceptions(false); // should be turned on in development time
		$front->dispatch();
	}
} catch (Exception $e) {
	echo "<pre>";
	print_r($e);
	echo "</pre>";
	die("<br />LINE: ".__LINE__."<br />FILE: ".__FILE__);
}