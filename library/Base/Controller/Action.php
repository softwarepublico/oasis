<?php
class Base_Controller_Action extends Zend_Controller_Action
{
	private $objProfissional;
	
    public function init()
    {
    	parent::init();
		if(K_INSTALL != "N"){
			$this->objProfissional = new Profissional($this->_request->getControllerName());
		}

        if (file_exists(SYSTEM_PATH_ABSOLUTE ."/application/".$this->_request->getModuleName()."/application/views/scripts/ajuda/".$this->_request->getControllerName().".phtml")) {
            $this->view->controllerAtualAjuda = $this->_request->getControllerName().".phtml";
        } else {
            $this->view->controllerAtualAjuda = "indisponivel.phtml";
        }
        if (($this->_request->getControllerName() != "index") 
            && ($this->_request->getControllerName() != "auth") 
            && ($this->_request->getControllerName() != "alterar-senha")
            && ($this->_request->getControllerName() != "fale-conosco")
            && ($this->_request->getControllerName() != "util")
            && ($this->_request->getControllerName() != "ajuda")
            && ($this->_request->getControllerName() != "base-conhecimento")) {
            //Verifica se o usuário esta logado no sistema
     		$this->validaSessionController();
			
            //dados do usuário...
            $userSession = array(
                "nome" => $_SESSION['oasis_logged'][0]['tx_nome_conhecido'] ,
                "dataUltimoAcesso" => $_SESSION['oasis_logged'][0]['tx_data_ultimo_acesso'] ,
                "horaUltimoAcesso" => $_SESSION['oasis_logged'][0]['tx_hora_ultimo_acesso']
            );
            $this->view->nomeUsuarioLogado = $userSession['nome'];
            $this->view->dataUltimoAcesso = $userSession['dataUltimoAcesso'];
            $this->view->horaUltimoAcesso = $userSession['horaUltimoAcesso'];
            //Montagem do menu
            $this->view->menuContainer = $this->generateMenuContainer($userSession);
            //Titulo da pagina do sistema 
            $this->view->tituloJanela = ConsultaMenuUsuario::getTituloJanela($this->_request->getControllerName(), $this->_request->getModuleName());
            
            // Bread Crumbs...
            $this->view->breadCrumbs = $this->generateBreadCrumbs($this->_request->getModuleName(),$this->_request->getControllerName());
            if (!isset($_SESSION['permissoes'])) {
                $profissionalMenu = new ProfissionalMenu($this->_request->getControllerName());
                $arrPermissoes = $profissionalMenu->getPermissoesProfissional($_SESSION['oasis_logged'][0]['cd_profissional'],$_SESSION['oasis_logged'][0]['cd_objeto']);
                $_SESSION['permissoes'] = $arrPermissoes;
            }
			
            // Verifica permissao de acesso ao controller corrente
            if( $this->_request->getModuleName() === 'default' ){
            	$controller_permissao = $this->_request->getControllerName();
            }else{
            	$controller_permissao = $this->_request->getModuleName()."_".$this->_request->getControllerName();
            }
            
            if (ChecaPermissao::possuiPermissao(strtolower($controller_permissao)) === false) {
                if ($this->_request->getControllerName() !== 'sem-permissao') {
                    // mostra tela de acesso negado
                    $url = SYSTEM_PATH . '/sem-permissao/index/permissao/'.$this->_request->getControllerName();
                    $this->_redirect($url);
                }
            }
        } elseif(array_key_exists('oasis_logged',$_SESSION)){
        	// dados do usuário...
	        $userSession = array(
                "nome" => $_SESSION['oasis_logged'][0]['tx_nome_conhecido'] ,
                "dataUltimoAcesso" => $_SESSION['oasis_logged'][0]['tx_data_ultimo_acesso'] ,
                "horaUltimoAcesso" => $_SESSION['oasis_logged'][0]['tx_hora_ultimo_acesso']
            );
        	$this->view->nomeUsuarioLogado = $userSession['nome'];
            $this->view->dataUltimoAcesso  = $userSession['dataUltimoAcesso'];
            $this->view->horaUltimoAcesso  = $userSession['horaUltimoAcesso'];
            // menu...
            if($_SESSION['oasis_logged'][0]['cd_perfil'] != "" && ($this->_request->getControllerName() != "alterar-senha")){
            	$this->view->menuContainer = $this->generateMenuContainer($userSession);
            }
        }
    }
    
    /**
     * Método que gera o menu
     * dependendo do perfil do usuário...
     *
     * @param  ARRAY  $userSession com os dados do usuário
     * @return STRING $htmlMenu     
     */
    private function generateMenuContainer(array $userSession)
    {
    	$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
    	$cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];
    	
        $menusPai = ConsultaMenuUsuario::getMenusPai($cd_profissional, $cd_objeto);
        $htmlMenu = '<ul id="menuContainer">';
        foreach ($menusPai as $menu) {
			$tx_modulo = "";
			if(!empty($menu['tx_modulo'])){
				$tx_modulo = "/{$menu['tx_modulo']}";
			}
            $htmlMenu .= "<li><a href=\"" . $this->_helper->BaseUrl->baseUrl(). $tx_modulo ."/{$menu['tx_pagina']}\" onclick=\"window.location.href = '{$this->_helper->BaseUrl->baseUrl()}{$tx_modulo}/{$menu['tx_pagina']}';\">".$menu['tx_menu']."</a></li>";
        }
        $htmlMenu .= '</ul>';
        return $htmlMenu;
    }

    /**
     * Método que gera o Bread Crumbs (Naveçação - Links)
     * dependendo da tela...
     *
     * @param  STRING $controller
     * @return STRING $htmlMenu     
     */
    private function generateBreadCrumbs($modulo, $controller)
    {
        $breadCrumbs = new BreadCrumbs();
        $cd_menu = $breadCrumbs->getCdMenu($this->_request->getControllerName(), $this->_request->getModuleName());
        $breadCrumbs->mountBreadCrumbs($cd_menu);

//------adicionado para compatibilizar a montagem do
        //breadCrumbs quando o controller for de um módulo
        $paramUnset = $controller;
        if($modulo != 'default'){
            $paramUnset = $modulo.'/'.$controller;
        }
        unset($breadCrumbs->breadCrumbs[$paramUnset]);
//---------------------------------------------------------

//      unset($breadCrumbs->breadCrumbs[$controller]);
        $htmlBreadCrumbs = '<ul id="breadCrumbs">';
        $htmlBreadCrumbs .= '<li><span></span></li>';
        $abaOrigem = $this->_request->getParam("abaOrigem");
        $abaOrigem = ($abaOrigem)?"#".$abaOrigem:"";
        
        foreach ($breadCrumbs->breadCrumbs as $moduleControllerBread => $bread) {
            $htmlBreadCrumbs .= "<li><a href=\"" . $this->_helper->BaseUrl->baseUrl() . "/{$moduleControllerBread}{$abaOrigem}\">{$bread}</a></li>";
        }
        $htmlBreadCrumbs .= '</ul>';
        
        return $htmlBreadCrumbs;
    }
    
    public function ajudaModalAjaxAction()
    {
    	$this->_helper->layout->disableLayout();
		$paginaPhtml  = $this->_request->getParam('paginaPhtml');
    	$render =    "ajuda/{$paginaPhtml}.phtml";
		die($this->view->render($render));
    }
    
    private function validaSessionController()
    {
        if(array_key_exists('error',$_SESSION)){
            $this->_redirect(SYSTEM_PATH."/index/index/error/{$_SESSION['error']}");
            exit();
        }

        if(count($_SESSION) == 0){
            $this->_redirect(SYSTEM_PATH."/index/index/error/6");
        } else {
            if(array_key_exists('oasis_logged',$_SESSION)){
                /*verifica se é o 1º acesso do usuário*/
                $where = $this->objProfissional->select()->where("tx_email_institucional = ?", $_SESSION['oasis_logged'][0]['tx_email_institucional'])->where("st_nova_senha = 'S' ")->where("st_inativo is null");
                $res = $this->objProfissional->fetchAll($where)->toArray();

                if(count($res) > 0){
                    session_destroy();
                    $_SESSION = array();
                    $_SESSION['error'] = "7";

                    $this->_redirect(SYSTEM_PATH."/index/index/error/7");
                }
            } else {
                $this->_redirect(SYSTEM_PATH."/index/index/error/6");
            }
        }
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
    
    /**
     * Atalho para a action helper Upload
     *
     * @return Base_Controller_Action_Helper_Upload
     */
    public function helperUpload()
    {
        return $this->_helper->Upload;
    }
    /**
     * Atalho para a action helper I18n
     *
     * @return Base_Controller_Action_Helper_I18n
     */
    public function helperI18n()
    {
        return $this->_helper->I18n;
    }
    
}