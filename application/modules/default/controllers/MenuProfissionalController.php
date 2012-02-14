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

class MenuProfissionalController extends Base_Controller_Action
{
	private $objEmpresa;
	private $objProfissional;
	private $objTipoDocumentacao;
	private $objTipoConhecimento;
	private $objetoContrato;
	private $treinamento;
	private $objContrato;
	
	public function init()
	{
		parent::init();
		$this->objEmpresa          = new Empresa($this->_request->getControllerName());
		$this->objProfissional     = new Profissional($this->_request->getControllerName());
		$this->objTipoDocumentacao = new TipoDocumentacao($this->_request->getControllerName());
		$this->objTipoConhecimento = new TipoConhecimento($this->_request->getControllerName());
		$this->objetoContrato	   = new ObjetoContrato($this->_request->getControllerName());
		$this->treinamento		   = new Treinamento($this->_request->getControllerName());
		$this->objContrato         = new Contrato($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_MENU_PROFISSIONAL'));
		
		if((ChecaPermissao::possuiPermissao('painel-profissional') === true)){
			$this->accordionProfissionais();
		}
		
		if((ChecaPermissao::possuiPermissao('documentacao-profissional') === true)){
			$this->accordionDocumentacaoProfissional();
		}
		
		if((ChecaPermissao::possuiPermissao('conhecimento-profissional') === true)){
			$this->accordionConhecimentoProfissional();
		}
		
		if((ChecaPermissao::possuiPermissao('treinamento-profissional') === true)){
			$this->accordionTreinamentoProfissional();
		}
		
		if((ChecaPermissao::possuiPermissao('profissional-objeto-contrato') === true)){
			$this->accordionProfissionalObjeto();
		}
		
		if((ChecaPermissao::possuiPermissao('objeto-perfil-profissional') === true)){
			$this->accordionObjetoPerfilProfissional();
		}
	}
	
	private function accordionProfissionais()
	{
		if(ChecaPermissao::possuiPermissao('painel-profissional') === true){
			
			$arrSituacao['N'] = Base_Util::getTranslator('L_VIEW_COMBO_ATIVO');
			$arrSituacao['S'] = Base_Util::getTranslator('L_VIEW_COMBO_INATIVO');
	
			$this->view->comboSituacao = $arrSituacao;
			
			$arrEmpresa = $this->objEmpresa->getEmpresa(true);
			$this->view->comboEmpresa = $arrEmpresa; 
			
			$form = new ProfissionalForm();
            if( 'S' === K_LDAP_AUTENTICATE ){
                $form->removeElement('st_nova_senha');
                $form->getElement('tx_email_institucional')->setLabel( Base_Util::getTranslator( 'L_VIEW_EMAIL_INSTITUCIONAL' ) );
            }
			
			$this->view->formProfissional = $form;
		}
	}
	
	private function accordionDocumentacaoProfissional()
	{
		$this->view->profissionalCombo     = $this->objProfissional->getProfissional(true); 
		$this->view->tipoDocumentacaoCombo = $this->objTipoDocumentacao->getTipoDocumentacao("R", true);
	}
	
	private function accordionConhecimentoProfissional()
	{
	    $this->view->arrComboProfissional     = $this->objProfissional->getProfissional(true);
	    $this->view->arrComboTipoConhecimento = $this->objTipoConhecimento->getComboTipoConhecimento(true, true);
	    $this->view->arrComboConhecimento1    = array();
	    $this->view->arrComboConhecimento2    = array();
	}
	
	private function accordionTreinamentoProfissional()
	{
		$this->view->arrTipoObjetoContrato	= $this->objetoContrato->getObjetoContratoAtivo(null,true,false);
		$this->view->arrTreinamento			= $this->treinamento->getTreinamento(true);
	}
	
	private function accordionProfissionalObjeto()
	{
		//$this->view->contratoCombo = $this->objContrato->getContrato(true, true);
		$this->view->objetoContratoCombo = $this->objetoContrato->getObjetoContratoAtivo(null, true, true, true);
	}
	
	private function accordionObjetoPerfilProfissional()
	{
		$this->view->contratos = $this->objetoContrato->getObjetoContratoAtivo ( null, true, true );	
	}
}