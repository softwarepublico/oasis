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
 
class AuthController extends Base_Controller_Action
{
	private $url = "";
	private $objProfissional;
	private $objProfissionalObjetoContrato;
	private $objObjetoContrato;
	private $arrObjeto;
	private $arrProfissional;
	
	public function init()
	{
		parent::init();
		$this->objProfissional               = new Profissional($this->_request->getControllerName());
		$this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objObjetoContrato             = new ObjetoContrato($this->_request->getControllerName());
	}
	
    function indexAction ()
    {
    	$this->view->message = '';
        //verifica se é POST
        if ($this->_request->isPost()) {
            //Obtém dados do formulário
            $formData = $this->_request->getPost();

            if(empty($formData['usuario']) && empty($formData['senha'])){
                $this->_redirect(SYSTEM_PATH.'/index/index/error/7');
            } else {
                
                $auth = Zend_Auth::getInstance();
                //verifica a tabela para autenticação
                $tableName = KT_S_PROFISSIONAL;
                if(trim(K_SCHEMA) !== ""){
                   $tableName = K_SCHEMA . "." . $tableName;
                }
                
                //verifica se a opção de autenticação é por ldap
                if( 'S' === K_LDAP_AUTENTICATE && ( $formData['usuario'] != 'administrador' && $formData['usuario'] != 'administrador'.K_DOMINIO_EMAIL_PADRAO )){
                    $ldapConfig = Zend_Registry::get('config')->ldap;
                    $adapter    = new Zend_Auth_Adapter_Ldap($ldapConfig->toArray(),$formData['usuario'],$formData['senha']);
                    $result     = $auth->authenticate($adapter);
                    
                    if( !$result->isValid() ){
                        $arrError = $result->getMessages();
                        $this->_redirect(SYSTEM_PATH.'/index/index/error/9/ldap_error/'.  base64_encode( $arrError[0]) );
                    }else{
                        $accountObject = $adapter->getAccountObject();
                                                
                        $arrData = $this->_helper->util->getUserAuthLdap( $accountObject,'Profissional', 'tx_email_institucional', 'tx_senha' );

                        if( true === $arrData['error'] ){
                            $this->_redirect(SYSTEM_PATH.'/index/index/error/9/ldap_error/'.base64_encode($arrData['error_msg']) );
                        }
                        
                        $data = $arrData['row'];
                        if($data->tx_data_ultimo_acesso == "" || $data->tx_hora_ultimo_acesso == ""){
                            $data->tx_data_ultimo_acesso = date("d/m/Y");
                            $data->tx_hora_ultimo_acesso = date("H:i:s");
                        }
                    }
                }else{
                    /**
                     * Condição que verifica se a senha do usuário esta em branco
                     * para criptografar o nome do sistema e realizar uma verificação
                     * no banco de dados, pois se a senha for essa é o primeiro acesso
                     * do usuário
                     */
                    $formData['senha'] = (empty($formData['senha']))?SYSTEM_NAME:$formData['senha'];
                    
                    //valida os dados do login
                    $formData = Base_Controller_Action_Helper_Util::validateInformation($formData,'usuario','senha');
                    if(count($formData) == 0){
                        $this->_redirect(SYSTEM_PATH."/index/index/error/1");
                    }
                    //Inicialica a autenticação do usuário
                    $authAdapter = Base_Controller_Action_Helper_Util::getAuthAdapter($formData, $tableName, 'tx_email_institucional', 'tx_senha');
                    $result      = $auth->authenticate($authAdapter);

                    if(!$result->isValid()){
                        $this->_redirect(SYSTEM_PATH.'/index/index/error/1');
                    } else {
                        //Retira a senha para colocar na sessão
                        $data = $authAdapter->getResultRowobject(null,'tx_senha');
                        if($data->st_nova_senha == "S"){
                            $this->url = SYSTEM_PATH . "/alterar-senha";
                            if($data->tx_data_ultimo_acesso == "" or $data->tx_hora_ultimo_acesso == ""){
                                $data->tx_data_ultimo_acesso = date("d/m/Y");
                                $data->tx_hora_ultimo_acesso = date("H:i:s");
                            }
                        }
                    }
                }
            }
            $this->_preparaDados($data);
            $this->openSession();
        } else {
            $this->_forward('index', 'index', null, array('error'=>8));
        }
    }

    private function _preparaDados($objData)
    {
        foreach($objData as $key=>$value){
            $this->arrProfissional[0][$key] = $value;
        }
    }

    private function openSession()
    {
    	if (count($this->arrProfissional) > 0) {
    		if(empty($this->url)){
	        	$this->validaObjetoProfissional($this->arrProfissional[0]['cd_profissional']);
        	} else {
        		$this->iniciaSistemaAction();
        	}
        } else {
            $this->_forward('index', 'index', null, array('error'=>1));
        }
    }

	public function montaTelaObjetoProfissionalAction()
	{
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_perfil       = $this->_request->getParam('cd_perfil');
		$st_inativo      = $this->_request->getParam('st_inativo',null);
		
		if($st_inativo != "S"){
			$this->getObjetoProfissional($cd_profissional, null, $cd_perfil);
		} else {
			$this->getObjetoContratoInativo($cd_profissional, null, $cd_perfil);
		}
		
		$this->view->cd_profissional       = $cd_profissional;
		$this->view->cd_perfil             = $cd_perfil;
		$this->view->st_inativo            = $st_inativo;
		$this->view->arrObjetoProfissional = $this->arrObjeto;
	}
    
    private function getProfissional($where)
    {
        return $this->objProfissional->fetchAll($where)->toArray();
    }
    
    private function getObjetoProfissional($cd_profissional, $cd_objeto = null, $cd_perfil = null)
	{
		//Recupera o objeto do contrato do profissional
		if(!is_null($cd_objeto)){
			$this->arrObjeto = $this->objProfissionalObjetoContrato->getDadosProfissionalObjetoContrato($cd_profissional,$cd_objeto);
		} else {
			$this->arrObjeto = $this->objProfissionalObjetoContrato->getDadosProfissionalObjetoContrato($cd_profissional,null);
		}
		
		if(count($this->arrObjeto) == 0){
			$cd_perfil = (is_null($cd_perfil))?$this->arrProfissional[0]['cd_perfil']:$cd_perfil;
			$this->getObjetoContratoInativo($cd_profissional,$cd_objeto,$cd_perfil);
		}
	}
	
	private function getObjetoContratoInativo($cd_profissional, $cd_objeto, $cd_perfil)
	{
		if(K_CODIGO_PERFIL_COORDENADOR == $cd_perfil || $cd_perfil == 0){
			$this->arrObjeto = $this->objProfissionalObjetoContrato->getDadosProfissionalObjetoContrato($cd_profissional,$cd_objeto,true);
		}
	}
	
	protected function validaObjetoProfissional($cd_profissional)
	{
		$this->getObjetoProfissional($cd_profissional);
		if(count($this->arrObjeto) > 1){
			//Condição se houver mais de um objeto para o profissional
			$this->_redirect("/auth/monta-tela-objeto-profissional/cd_profissional/{$cd_profissional}/cd_perfil/{$this->arrProfissional[0]['cd_perfil']}");
		} else {
			//Fluxo normal com um objeto padrão
			$this->iniciaSistemaAction();
		}
	}
	
    public function iniciaSistemaAction()
    {
    	if(count($this->arrProfissional) > 0){
    		$this->getObjetoProfissional($this->arrProfissional[0]['cd_profissional']);
    		$redirect = "S";
    	} else {
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
    		$cd_profissional = $this->_request->getParam('cd_profissional');
    		$cd_objeto       = $this->_request->getParam('cd_objeto');
    		$cd_perfil       = $this->_request->getParam('cd_perfil');

    		$this->getObjetoProfissional($cd_profissional, $cd_objeto, $cd_perfil);
    		
    		$where = $this->objProfissional->select()->where("cd_profissional = ?", $cd_profissional);
    		$this->arrProfissional = $this->getProfissional($where);
    		$redirect = "N";
    	}
    	if(count($this->arrObjeto) > 0){
	    	//Recupera o contrato do objeto de contrato do profissional
			$selectObjetoContrato = $this->objObjetoContrato->select()->where("cd_objeto = {$this->arrObjeto[0]['cd_objeto']}");
			$arrObjetoContrato = $this->objObjetoContrato->fetchAll($selectObjetoContrato)->toArray();

	        $this->arrProfissional[0]['cd_objeto']   = $this->arrObjeto[0]['cd_objeto'];
	        $this->arrProfissional[0]['cd_contrato'] = $this->arrObjeto[0]['cd_contrato'];

            $this->arrProfissional[0]['host' ] =  $_SERVER['HTTP_HOST'];
            
	        unset($this->arrProfissional[0]['tx_senha']);
    	} else {
    		$this->arrProfissional[0]['cd_objeto']   = "";
	        $this->arrProfissional[0]['cd_contrato'] = "";
    	}
		//metodo que valida a sessão do usuario
		$this->validaSession($this->arrProfissional);
        $_SESSION['oasis_logged'] = $this->arrProfissional;

        $dadosAtualizacao = array('tx_data_ultimo_acesso' => date("d/m/Y") , 'tx_hora_ultimo_acesso' => date("H:i:s"));
        $whereAtualizacao = "cd_profissional = {$this->arrProfissional[0]['cd_profissional']}";
        $this->objProfissional->update($dadosAtualizacao, $whereAtualizacao);

        if($redirect == "S" && $this->url == ""){
	        $this->_redirect(SYSTEM_PATH."/inicio");
        } else if($redirect == "S" && $this->url != ""){
	        $this->_redirect($this->url);
        } else {
        	echo SYSTEM_PATH."/inicio";
        }
    }
    
	protected function validaSession($arrSession)
	{
		$arrSession = $arrSession[0];
		$url = "";
		
		if($arrSession['cd_perfil'] == ""){
			$url = SYSTEM_PATH . "/index/index/error/2";
		} elseif ($arrSession['cd_contrato'] == ""){
			$url = SYSTEM_PATH . "/index/index/error/3";
		} elseif ($arrSession['cd_objeto'] == ""){
			$url = SYSTEM_PATH . "/index/index/error/4";
		} elseif ($arrSession['st_inativo'] == "S"){
			$url = SYSTEM_PATH . "/index/index/error/5";
		}
		
		if($url != ""){$this->_redirect($url);}
	}
	
	public function logoutAction ()
    {
        $lang = $_SESSION['language'];
        //Destroi a sessão
        session_destroy();

        //Limpa a variável da sessão
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        //Memoriza a linguagem selecionada pelo usuário
        $_SESSION['language'] = $lang;
        
        //Redireciona para a tela de login
        $this->_redirect('/');
    }
}