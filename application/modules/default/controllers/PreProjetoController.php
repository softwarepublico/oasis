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

class PreProjetoController extends Base_Controller_Action
{
	private $preProjeto;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PRE_PROJETO'));
		$this->preProjeto   = new PreProjeto($this->_request->getControllerName());
	}

	public function indexAction()
	{	
		$form = new PreProjetoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {
			
				$novo 				                    = $this->preProjeto->createRow();
				$novo->cd_pre_projeto	                = $this->preProjeto->getNextValueOfField('cd_pre_projeto');
				$novo->tx_pre_projeto                   = $formData['tx_pre_projeto'];
				$novo->tx_sigla_pre_projeto             = $formData['tx_sigla_pre_projeto'];
				$novo->tx_contexto_geral_pre_projeto    = $formData['tx_contexto_geral_pre_projeto'];
				$novo->tx_escopo_pre_projeto            = $formData['tx_escopo_pre_projeto']; 
				$novo->cd_unidade                       = $formData['cd_unidade'];
				$novo->tx_gestor_pre_projeto            = $formData['tx_gestor_pre_projeto']; 
				$novo->tx_obs_pre_projeto               = $formData['tx_obs_pre_projeto'];
				$novo->st_impacto_pre_projeto           = $formData['st_impacto_pre_projeto']; 
				$novo->st_prioridade_pre_projeto        = $formData['st_prioridade_pre_projeto']; 
				$novo->cd_gerente_pre_projeto           = $formData['cd_gerente_pre_projeto'];  
				$novo->tx_horas_estimadas               = $formData['tx_horas_estimadas'];  				
				$novo->tx_pub_alcancado_pre_proj = $formData['tx_pub_alcancado_pre_proj'];  
				$novo->cd_contrato                      = $_SESSION["oasis_logged"][0]["cd_contrato"];  
				
				if ($novo->save()) {
					$this->_redirect('./gerenciar-projetos-pre-projeto');
				} else {
					// mensagem de erro  L_ERRO_????????????????
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PRE_PROJETO'));
        
		$form = new PreProjetoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_pre_projeto = (int)$form->getValue('cd_pre_projeto');

				$obj = $this->preProjeto->fetchRow("cd_pre_projeto= $cd_pre_projeto");

				$obj->tx_pre_projeto                   = $formData['tx_pre_projeto'];
				$obj->tx_sigla_pre_projeto             = $formData['tx_sigla_pre_projeto'];
				$obj->tx_contexto_geral_pre_projeto    = $formData['tx_contexto_geral_pre_projeto'];
				$obj->tx_escopo_pre_projeto            = $formData['tx_escopo_pre_projeto']; 
				$obj->cd_unidade                       = $formData['cd_unidade'];
				$obj->tx_gestor_pre_projeto            = $formData['tx_gestor_pre_projeto']; 
				$obj->tx_obs_pre_projeto               = $formData['tx_obs_pre_projeto'];
				$obj->st_impacto_pre_projeto           = $formData['st_impacto_pre_projeto']; 
				$obj->st_prioridade_pre_projeto        = $formData['st_prioridade_pre_projeto']; 
				$obj->cd_gerente_pre_projeto           = $formData['cd_gerente_pre_projeto'];  
				$obj->tx_horas_estimadas               = $formData['tx_horas_estimadas'];  				
				$obj->tx_pub_alcancado_pre_proj = $formData['tx_pub_alcancado_pre_proj'];  
								
				$obj->save();

				$this->_redirect('./gerenciar-projetos-pre-projeto');

			} else {
				// redireciona
				$form->populate($formData);
			}
		}  else {
			$excluir = new Zend_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir');
			$excluir->setAttrib('class', 'vermelho buttonBar');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
		    $form->addElement($excluir);

			$cd_pre_projeto= (int)$this->_request->getParam('cd_pre_projeto', 0);
			if ($cd_pre_projeto> 0) {
				$preProjeto  = new PreProjeto($this->_request->getControllerName());
				$row   = $preProjeto->fetchRow("cd_pre_projeto=$cd_pre_projeto");
				$form->populate($row->toArray());
			}
		}
		$this->view->form = $form;
	}


	public function excluirAction()
	{
	
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_pre_projeto = (int)$this->_request->getParam('cd_pre_projeto', 0);
		if ($this->preProjeto->delete("cd_pre_projeto=$cd_pre_projeto")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function listarAction()
	{
		$res = $this->preProjeto->fetchAll();
		$this->view->res  = $res;
	}
}