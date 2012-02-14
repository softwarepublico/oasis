<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÂO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class Install_IndexController extends Base_Controller_Action
{
	private $objUtil;
	private $objConexao;

	public function init()
	{
		parent::init();
		$this->objUtil = new Base_Controller_Action_Helper_Util();
		$this->objConexao = new Base_Controller_Action_Helper_Conexao();
	}
    
	public function indexAction()
	{
		$_SESSION = array();
		//Inicializando as variaveis de sessão
		$_SESSION['oasis_install'] = array();
		$_SESSION['oasis_install']['language'   ] = array();
		$_SESSION['oasis_install']['banco_dados'] = array();
		$_SESSION['oasis_install']['constantes' ] = array();
		$_SESSION['oasis_install']['licenca'    ] = array();
		//Inicialização da variavel de linguagem
		$_SESSION['oasis_install']['language']['tx_language'] = "";
		//Inicialização da variavel de licença do sistema
		$_SESSION['oasis_install']['licenca']['tx_aceite_licenca'] = "";
		//Inicialização das variaveis do banco de dados
		$_SESSION['oasis_install']['banco_dados']['tx_adapter']    = "";
		$_SESSION['oasis_install']['banco_dados']['tx_host']       = "";
		$_SESSION['oasis_install']['banco_dados']['tx_dbname']     = "";
		$_SESSION['oasis_install']['banco_dados']['tx_schema']     = "oasis";
		$_SESSION['oasis_install']['banco_dados']['tx_port']       = "";
		$_SESSION['oasis_install']['banco_dados']['tx_username']   = "";
		$_SESSION['oasis_install']['banco_dados']['tx_password']   = "";
		$_SESSION['oasis_install']['banco_dados']['dados_exemplo'] = "N";
		//Inicialização das variaveis das constantes do sistema
		$_SESSION['oasis_install']['constantes']['nome_orgao']               = "";
		$_SESSION['oasis_install']['constantes']['nome_ti_orgao']            = "";
		$_SESSION['oasis_install']['constantes']['dominio_email']            = "";
		$_SESSION['oasis_install']['constantes']['prefixo_telefone_orgao']   = "";
		$_SESSION['oasis_install']['constantes']['telefone_principal_orgao'] = "";
		$_SESSION['oasis_install']['constantes']['caminho_virtual']          = "";
		$_SESSION['oasis_install']['constantes']['caminho_fisico']           = "";
		$_SESSION['oasis_install']['constantes']['rodape']                   = K_ADDRES_ORGAO;
		$_SESSION['oasis_install']['constantes']['email']                    = "";
		$_SESSION['oasis_install']['constantes']['email_principal']          = "";
		$_SESSION['oasis_install']['constantes']['servidor_email']           = "";
		$_SESSION['oasis_install']['constantes']['porta_email']              = "";
        
        
		$_SESSION['oasis_install']['constantes']['ldap_autenticate']         = K_LDAP_AUTENTICATE;
        
        $ldapConfig = Zend_Registry::get('config')->ldap->server;
        
		$_SESSION['oasis_install']['ldap']['host']                           = $ldapConfig->host;
		$_SESSION['oasis_install']['ldap']['port']                           = $ldapConfig->port;
		$_SESSION['oasis_install']['ldap']['useSsl']                         = $ldapConfig->useSsl;
		$_SESSION['oasis_install']['ldap']['accountDomainName']              = $ldapConfig->accountDomainName;
		$_SESSION['oasis_install']['ldap']['accountDomainNameShort']         = $ldapConfig->accountDomainNameShort;
		$_SESSION['oasis_install']['ldap']['accountCanonicalForm']           = $ldapConfig->accountCanonicalForm;
		$_SESSION['oasis_install']['ldap']['baseDn']                         = $ldapConfig->baseDn;
		$_SESSION['oasis_install']['ldap']['bindRequiresDn']                 = $ldapConfig->bindRequiresDn;
		$_SESSION['oasis_install']['ldap']['useStartTls']                    = $ldapConfig->useStartTls;
        
	}

	public function passoUmIdiomaAction()
	{
		$this->_helper->layout->disableLayout();

		$arrLanguage['pt_br'] = "Português (Brasil)";
//		$arrLanguage['en_us'] = "Inglês";
//		$arrLanguage['es_es'] = "Espanhol";
		$this->view->arrLanguage  = $arrLanguage;
	}

	public function passoDoisApresentacaoAction()
	{
		$this->_helper->layout->disableLayout();
		$_SESSION['oasis_install']['language']['tx_language'] = $this->_request->getParam('tx_language');
	}
    
	public function passoTresLicencaAction()
	{
		$this->_helper->layout->disableLayout();
	}

	public function passoQuatroVerificaPermissaoAction()
	{
		$this->_helper->layout->disableLayout();
		$systemName      = SYSTEM_NAME;
		$directorySystem = SYSTEM_PATH_ABSOLUTE;

		//Montando as imagens de permissão
		$imgFalse = '<img class="float-l" style="margin: 0px 5px 0px -5px;" src="'.$this->_helper->BaseUrl->baseUrl().'/public/img/u46.png"/>';
		$imgTrue  = '<img class="float-l" style="margin:5px 10px 0px 0px;" src="'.$this->_helper->BaseUrl->baseUrl().'/public/img/u42.png"/>';

		$b = DIRECTORY_SEPARATOR;
		$arrPastas = array("public{$b}documentacao{$b}",
						   "public{$b}documentacao{$b}geral{$b}",
						   "public{$b}documentacao{$b}item-teste{$b}",
						   "public{$b}documentacao{$b}item-teste{$b}caso-de-uso{$b}",
						   "public{$b}documentacao{$b}item-teste{$b}regra-negocio{$b}",
						   "public{$b}documentacao{$b}item-teste{$b}requisito{$b}",
						   "public{$b}documentacao{$b}log{$b}",
						   "public{$b}documentacao{$b}log{$b}logEvento{$b}",
						   "public{$b}documentacao{$b}log{$b}logError{$b}",
						   "public{$b}documentacao{$b}profissional{$b}",
						   "public{$b}documentacao{$b}projeto{$b}",
						   "public{$b}img{$b}svg{$b}",
						   "application{$b}configuration{$b}install{$b}"
					     );
		$html  = "";
		$error = 0;
		foreach($arrPastas as $pastas){
			$arquivoPermissao = $directorySystem.$b.$pastas;
			if(isset($_SERVER['WINDIR'])){
				$arquivoPermissao = str_replace("/","\\",$arquivoPermissao);
			}

			$html .= '<p class="float-l clear-l span-20" style="margin-left: 30px; margin-top: -15px;"><label class="float-l">';
			@chmod($arquivoPermissao, 0777);
			if (!is_writable($arquivoPermissao)) {
				$html .= $imgFalse;
				$html .= Base_Util::getTranslator('L_MSG_ALERT_SEM_PERMISSAO_GRAVACAO_DIRETORIO').": <span class='bold'>..{$b}{$systemName}{$b}{$pastas}</span>";
				$error = 1;
			}else{
				$html .= $imgTrue;
				$html .= Base_Util::getTranslator('L_MSG_GRAVACAO_PERMITIDA_DIRETORIO').": <span class='bold'>..{$b}{$systemName}{$b}{$pastas}</span>";
			}
			$html .= '</label></p>';
		}

		if($error == 1){
			$html .= '<p class="float-l clear-l span-17 bold" style="margin-left: 85px; margin-top:10px; text-align:justify; color: rgb(254, 3, 3);">
                        '.Base_Util::getTranslator('L_MSG_PARA_FUNCIONAMENTO_NECESSARIO_PERIMISSAO_LEITURA_ESCRITA').'
					</p>';
		} else {
			$html .= '<p class="float-l clear-l span-17 bold" style="margin-left: 85px; margin-top:10px;">
						'.Base_Util::getTranslator('L_MSG_PERMISSOES_CORRETAS').'
					</p>';
		}

		$this->view->html  = $html;
		$this->view->error = $error;
	}

	public function passoCincoConfiguracaoBancoDeDadosAction()
	{
		$this->_helper->layout->disableLayout();

		$arrBancoDeDados[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrBancoDeDados[Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES] = 'PDO - Postgres';
		$arrBancoDeDados[Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL]    = 'PDO - MySQL';
//		$arrBancoDeDados[Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL]     = 'PDO - SQLServer';
//		$arrBancoDeDados[Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE]       = 'Oracle';
//		$arrBancoDeDados[Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL]        = 'SQLServer';

		$this->view->arrBancoDeDados = $arrBancoDeDados;
	}

	public function passoSeisConfiguracaoConstantesAction()
	{
		$this->_helper->layout->disableLayout();
        
		$this->view->caminhoVirtual = SYSTEM_PATH;
		$this->view->caminhoFisico  = SYSTEM_PATH_ABSOLUTE;
		$this->view->arrTipoLdap  = array(
            'open_ldap'       =>'openLdap',
            'active_directory'=>'Active Directory'
        );
	}

	public function passoSeteCriaConstantesAction()
	{
		$this->_helper->layout->disableLayout();

        $arrConst['K_LANGUAGE']             = $_SESSION['oasis_install']['language'   ]['tx_language'];
		$arrConst['K_NOME_ORGAO']           = $_SESSION['oasis_install']['constantes' ]['nome_orgao'];
		$arrConst['K_DDD_PREFIXO_TELEFONE'] = $_SESSION['oasis_install']['constantes' ]['prefixo_telefone_orgao'];
		$arrConst['K_TELEFONE_ORGAO']       = $_SESSION['oasis_install']['constantes' ]['telefone_principal_orgao'];
		$arrConst['K_HEADER_COORDENACAO']   = $_SESSION['oasis_install']['constantes' ]['nome_ti_orgao'];
		$arrConst['K_ENVIAR_EMAIL']         = $_SESSION['oasis_install']['constantes' ]['email'];
		$arrConst['K_DOMINIO_EMAIL_PADRAO'] = $_SESSION['oasis_install']['constantes' ]['dominio_email'];
		$arrConst['K_EMAIL_OASIS']          = $_SESSION['oasis_install']['constantes' ]['email_principal'];
		$arrConst['K_SERVIDOR_EMAIL']       = $_SESSION['oasis_install']['constantes' ]['servidor_email'];
		$arrConst['K_PORTA_EMAIL']          = $_SESSION['oasis_install']['constantes' ]['porta_email'];
		$arrConst['K_ADDRES_ORGAO']         = $_SESSION['oasis_install']['constantes' ]['rodape'];
        $arrConst['K_LDAP_AUTENTICATE']     = $_SESSION['oasis_install']['constantes' ]['ldap_autenticate'];
		$arrConst['K_USER']                 = $_SESSION['oasis_install']['banco_dados']['tx_username'];
        $arrConst['K_SCHEMA']               = $_SESSION['oasis_install']['banco_dados']['tx_schema'];

		$b = DIRECTORY_SEPARATOR;
		$arquivoConst    = SYSTEM_PATH_ABSOLUTE."{$b}application{$b}configuration{$b}const.php";
		$arquivoBancoIni = SYSTEM_PATH_ABSOLUTE."{$b}application{$b}configuration{$b}config.ini";
		if(isset($_SERVER['WINDIR'])){
			$arquivoConst    = str_replace("/","\\",$arquivoConst);
			$arquivoBancoIni = str_replace("/","\\",$arquivoBancoIni);
		}

		$this->view->arrConstantes        = $arrConst;
		$this->view->arquivoConstante     = $arquivoConst;
		$this->view->arquivoBancoIni      = $arquivoBancoIni;
	}

	public function passoOitoCriaTabelasAction()
	{
		$this->_helper->layout->disableLayout();
		/**
		 * Recupera o adapter escolhido na configuração do banco de dados,
		 * retira os '_' e monta o caminho para chegar ate o sql.
		 * pdo_pgsql = pdo/pgsql/
		 */
		$arrPasta   = explode('_', $this->toLower($_SESSION['oasis_install']['banco_dados']['tx_adapter']));
		$quantPasta = count($arrPasta);

		//Nome dos arquivos de SQL
		$arqCreateTable = "oasis_install_create_table.sql";

		if($_SESSION['oasis_install']['banco_dados']['dados_exemplo'] == "S"){
			$arqDataInsert  = "oasis_install_data_insert_example.sql";
		} else {
			$arqDataInsert  = "oasis_install_data_insert.sql";
		}
		$arqGrant       = "oasis_install_grant.sql";

		$b = DIRECTORY_SEPARATOR;
		$caminhoArquivoSql = SYSTEM_PATH_ABSOLUTE."{$b}application{$b}configuration{$b}install{$b}banco{$b}";

		foreach($arrPasta as $value) {
			$caminhoArquivoSql .= $value.$b;
		}
		if(isset($_SERVER['WINDIR'])){
			$caminhoArquivoSql = str_replace("/","\\",$caminhoArquivoSql);
		}

		//Recupera conexão com o banco de dados
		$this->objConexao->validaConexao($_SESSION['oasis_install']['banco_dados']);

		$this->view->objConnection     = $this->objConexao->getConnection();
		$this->view->caminhoArquivoSql = $caminhoArquivoSql;
		$this->view->arqCreateTable    = $arqCreateTable;
		$this->view->arqDataInsert     = $arqDataInsert;
		$this->view->arqGrant          = $arqGrant;
		$this->initView();
	}

	public function passoNoveDefinicaoDadosSistemaAction()
	{
		$this->_helper->layout->disableLayout();
	}

	public function passoDezFinalizaInstalacaoAction()
	{
		$this->_helper->layout->disableLayout();
		$arrConstantes['K_INSTALL'] = "S";
		
		$b = DIRECTORY_SEPARATOR;
		$arquivoConst = SYSTEM_PATH_ABSOLUTE."{$b}application{$b}configuration{$b}const.php";

		$this->view->arqConst     = $arquivoConst;
		$this->view->arrConstante = $arrConstantes;
		$this->initView();
	}

    public function validaConexaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();

        /**     limpa possíveis \\     */
        $arrPost['tx_host'] = stripcslashes($arrPost['tx_host']);

		$arrParam['tx_adapter']  = $arrPost['tx_adapter'];
		$arrParam['tx_host']     = $arrPost['tx_host'];
		$arrParam['tx_port']     = $arrPost['tx_port'];
		$arrParam['tx_dbname']   = $arrPost['tx_dbname'];
		$arrParam['tx_schema']   = $arrPost['tx_schema'];
		$arrParam['tx_username'] = $arrPost['tx_username'];
		$arrParam['tx_password'] = $arrPost['tx_password'];

		$arrRetorno = $this->objConexao->validaConexao($arrParam);

		$_SESSION['oasis_install']['banco_dados']['tx_adapter']  = $arrParam['tx_adapter'];
		$_SESSION['oasis_install']['banco_dados']['tx_host']     = $arrParam['tx_host'];
		$_SESSION['oasis_install']['banco_dados']['tx_port']     = $arrParam['tx_port'];
		$_SESSION['oasis_install']['banco_dados']['tx_dbname']   = $arrParam['tx_dbname'];
		$_SESSION['oasis_install']['banco_dados']['tx_schema']   = $arrParam['tx_schema'];
		$_SESSION['oasis_install']['banco_dados']['tx_username'] = $arrParam['tx_username'];
		$_SESSION['oasis_install']['banco_dados']['tx_password'] = $arrParam['tx_password'];

        if(array_key_exists('checkbox_dados_exemplo',$arrPost)){
			$_SESSION['oasis_install']['banco_dados']['dados_exemplo'] = $arrPost['checkbox_dados_exemplo'];
		}
		echo Zend_Json_Encoder::encode($arrRetorno);
	}
    
    public function testaConexaoLdapAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$arrPost   = $this->_request->getPost();
		$arrReturn = array("msg"=>"Conexão Validada com Sucesso","type"=>1);
        
        $user  = $arrPost['usuario'];
        $senha = $arrPost['senha'];
        
        unset($arrPost['ldap_tipo']);
        unset($arrPost['ldap_autenticate']);
        unset($arrPost['usuario']);
        unset($arrPost['senha']);
        
        $options = array();
        foreach ($arrPost as $key => $value) {
            $arrKey = explode('_', $key);
            $options[$arrKey[1]] = $value;
        }
        
        $auth = Zend_Auth::getInstance();
        
        $adapter    = new Zend_Auth_Adapter_Ldap(array($options),$user,$senha);
        $result     = $auth->authenticate($adapter);
        
        
        if(!$result->isValid()){
            $msg = $result->getMessages();
            
            $arrReturn["msg" ] = $msg[0];
            $arrReturn["type"] = 3;
        }
        
        echo Zend_Json::encode( $arrReturn );
    }
    
    public function atualizaDadosAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$arrPost   = $this->_request->getPost();
		$arrReturn = array("msg"=>"","erro"=>false,"erroType"=>"","tab"=>"","campo"=>"");
        
        $_SESSION['oasis_install']['constantes']['ldap_autenticate'] = $arrPost['ldap_autenticate'];
        
        if( $_SESSION['oasis_install']['constantes']['ldap_autenticate'] == 'S' ){
            /**  limpa possiveis \\ transformando em \ */
            $arrPost['ldap_host'] = stripcslashes($arrPost['ldap_host']);
        
            $_SESSION['oasis_install']['ldap']['host']                   = $arrPost['ldap_host'];
            $_SESSION['oasis_install']['ldap']['port']                   = $arrPost['ldap_port'];
            $_SESSION['oasis_install']['ldap']['useSsl']                 = $arrPost['ldap_useSsl'];
            $_SESSION['oasis_install']['ldap']['accountDomainName']      = strip_tags( $arrPost['ldap_accountDomainName'] );
            $_SESSION['oasis_install']['ldap']['accountDomainNameShort'] = strip_tags( $arrPost['ldap_accountDomainNameShort'] );
            $_SESSION['oasis_install']['ldap']['baseDn']                 = strip_tags( $arrPost['ldap_baseDn']);
            $_SESSION['oasis_install']['ldap']['bindRequiresDn']         = $arrPost['ldap_bindRequiresDn'];
            $_SESSION['oasis_install']['ldap']['useStartTls']            = $arrPost['ldap_useStartTls'];
        }
        
        /**  limpa possiveis \\ transformando em \ */
        $arrPost['tx_host'] = stripcslashes($arrPost['tx_host']);
        $arrPost['rodape' ] = stripcslashes($arrPost['rodape']);

		$arrParam['tx_adapter']  = $arrPost['tx_adapter'];
		$arrParam['tx_host']     = $arrPost['tx_host'];
		$arrParam['tx_port']     = $arrPost['tx_port'];
		$arrParam['tx_dbname']   = $arrPost['tx_dbname'];
		$arrParam['tx_schema']   = $arrPost['tx_schema'];
		$arrParam['tx_username'] = $arrPost['tx_username'];
		$arrParam['tx_password'] = $arrPost['tx_password'];

		$arrRetorno = $this->objConexao->validaConexao($arrParam);
		if(($arrPost['tx_language'] == "") || ($arrPost['tx_language'] == "0" )){
			$arrReturn['msg']      = Base_Util::getTranslator('L_MSG_ALERT_IDIOMA_SISTEMA_OBRIGATORIO');
			$arrReturn['erro']     = true;
			$arrReturn['erroType'] = "2";
			$arrReturn['tab']      = 1;
			$arrReturn['campo']    = "tx_language";
		} else if(array_key_exists('tx_aceite_licenca', $arrPost)){
			if($arrPost['tx_aceite_licenca'] != "S"){
				$arrReturn['msg']      = Base_Util::getTranslator('L_MSG_ALERT_ACEITAR_TERMO_LICENCA_OBRIGATORIO');
				$arrReturn['erro']     = true;
				$arrReturn['erroType'] = "2";
				$arrReturn['tab']      = 3;
				$arrReturn['campo']    = "tx_aceite_licenca";
			} else if($arrRetorno['error']){
				$arrReturn['msg']      = $arrRetorno['msg'];
				$arrReturn['erro']     = $arrRetorno['error'];
				$arrReturn['erroType'] = $arrRetorno['type'];
				$arrReturn['tab']      = 5;
				$arrReturn['campo']    = "";
			} else {
				$_SESSION['oasis_install']['language'   ]['tx_language']             = $arrPost['tx_language'];
				$_SESSION['oasis_install']['licenca'    ]['tx_aceite_licenca']       = $arrPost['tx_aceite_licenca'];
				$_SESSION['oasis_install']['banco_dados']['tx_adapter']              = $arrPost['tx_adapter'];
				$_SESSION['oasis_install']['banco_dados']['tx_host']                 = $arrPost['tx_host'];
				$_SESSION['oasis_install']['banco_dados']['tx_port']                 = $arrPost['tx_port'];
				$_SESSION['oasis_install']['banco_dados']['tx_dbname']               = $arrPost['tx_dbname'];
				$_SESSION['oasis_install']['banco_dados']['tx_schema']               = $arrPost['tx_schema'];
				$_SESSION['oasis_install']['banco_dados']['tx_username']             = $arrPost['tx_username'];
				$_SESSION['oasis_install']['banco_dados']['tx_password']             = $arrPost['tx_password'];
				if(array_key_exists('checkbox_dados_exemplo',$arrPost)){
					$_SESSION['oasis_install']['banco_dados']['dados_exemplo'] = $arrPost['checkbox_dados_exemplo'];
				} else {
					$_SESSION['oasis_install']['banco_dados']['dados_exemplo'] = "N";
				}
				$_SESSION['oasis_install']['constantes']['nome_orgao']               = $arrPost['nome_orgao'];
				$_SESSION['oasis_install']['constantes']['nome_ti_orgao']            = $arrPost['nome_ti_orgao'];

				$arrEmail = explode('@',$arrPost['dominio_email']);
				$qtdEmail = count($arrEmail);
				if($qtdEmail == 1){
					$email = "@{$arrEmail[0]}";
				} else {
					$email = "@{$arrEmail[$qtdEmail-1]}";
				}
				$_SESSION['oasis_install']['constantes']['dominio_email']            = $email;
				$_SESSION['oasis_install']['constantes']['prefixo_telefone_orgao']   = $arrPost['prefixo_telefone_orgao'];
				$_SESSION['oasis_install']['constantes']['telefone_principal_orgao'] = $arrPost['telefone_principal_orgao'];
				$_SESSION['oasis_install']['constantes']['caminho_virtual']          = $arrPost['caminho_virtual'];
				$_SESSION['oasis_install']['constantes']['caminho_fisico']           = $arrPost['caminho_fisico'];
				$_SESSION['oasis_install']['constantes']['rodape']                   = $arrPost['rodape'];

				if(array_key_exists('email', $arrPost)){
					if($arrPost['email'] == "S"){
						$_SESSION['oasis_install']['constantes']['email']                = $arrPost['email'];
						$_SESSION['oasis_install']['constantes']['email_principal']      = $arrPost['email_principal'];
						$_SESSION['oasis_install']['constantes']['servidor_email']       = $arrPost['servidor_email'];
						$_SESSION['oasis_install']['constantes']['porta_email']          = $arrPost['porta_email'];
					} else {
						$_SESSION['oasis_install']['constantes']['email']                = "N";
						$_SESSION['oasis_install']['constantes']['email_principal']      = "";
						$_SESSION['oasis_install']['constantes']['servidor_email']       = "";
						$_SESSION['oasis_install']['constantes']['porta_email']          = "";
					}
				} else {
					$_SESSION['oasis_install']['constantes']['email']                = "N";
					$_SESSION['oasis_install']['constantes']['email_principal']      = "";
					$_SESSION['oasis_install']['constantes']['servidor_email']       = "";
					$_SESSION['oasis_install']['constantes']['porta_email']          = "";
				}
			}
		} else if(!array_key_exists('tx_aceite_licenca', $arrPost)){
			$arrReturn['msg']      = Base_Util::getTranslator('L_MSG_ALERT_ACEITAR_TERMO_LICENCA_OBRIGATORIO');
			$arrReturn['erro']     = true;
			$arrReturn['erroType'] = "2";
			$arrReturn['tab']      = 3;
			$arrReturn['campo']    = "tx_aceite_licenca";
		}

		echo Zend_Json::encode($arrReturn);
	}
    
    public function salvaDadosSistemaAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$arrPost = $this->_request->getPost();

		//Recupera conexão com o banco de dados
		$this->objConexao->validaConexao($_SESSION['oasis_install']['banco_dados']);

		$senha  = md5($arrPost['tx_senha']);
        $tableName = KT_S_PROFISSIONAL;

        if($_SESSION['oasis_install']['banco_dados']['tx_schema'] != ''){
            $tableName = $_SESSION['oasis_install']['banco_dados']['tx_schema'].".".$tableName;
        }
		$update = "UPDATE {$tableName}
				   SET tx_senha='{$senha}', tx_email_institucional='administrador".K_DOMINIO_EMAIL_PADRAO."'
 				   WHERE cd_profissional = 0;";
                   
        $objConnection = $this->objConexao->getConnection();

        switch ($_SESSION['oasis_install']['banco_dados']['tx_adapter']){
            case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
            case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
            case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
            case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                $retorno = $objConnection->query($update);
                break;
            default:
                $retorno = $objConnection->exec($update);
                break;
        }

		$retorno = true;

		$msg = ($retorno) ? Base_Util::getTranslator('L_MSG_SUCESS_DEFINICAO_SENHA_ADMINISTRADOR') : Base_Util::getTranslator('L_MSG_ERRO_DEFINICAO_SENHA_ADMINISTRADOR');
		echo $msg;
	}

    
    
}