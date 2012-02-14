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

class GrupoFatorController extends Base_Controller_Action
{
	private $objGrupoFator;
	private $objProjeto;
	
	public function init()
	{
		parent::init();
		$this->objProjeto    = new Projeto($this->_request->getControllerName());
		$this->objGrupoFator = new GrupoFator($this->_request->getControllerName());
	}

	public function indexAction()
	{	
		$this->view->formGrupoFator = new GrupoFatorForm();
		$arrProjeto = $this->objProjeto->getProjeto(true);
		$this->view->arrProjeto = $arrProjeto;
	}

	public function salvarGrupoFatorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$return = $this->objGrupoFator->salvarGrupoFator($arrDados);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo  $msg;		
	}
	
	public function alterarGrupoFatorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$return = $this->objGrupoFator->alterarGrupoFator($arrDados);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo  $msg;
	}
	
	public function excluirGrupoFatorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_grupo_fator = (int)$this->_request->getParam('cd_grupo_fator', 0);
		
		if ($this->objGrupoFator->delete("cd_grupo_fator = {$cd_grupo_fator}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	public function recuperaGrupoFatorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_grupo_fator = $this->_request->getParam('cd_grupo_fator',0);
		
		$arrDados = $this->objGrupoFator->getDadosGrupoFator($cd_grupo_fator);
		$arrDados = $arrDados[0];
				
		echo Zend_Json_Encoder::encode($arrDados);
	}
	
	public function gridGrupoFatorAction()
	{
		$this->_helper->layout->disableLayout();
		
		$res = $this->objGrupoFator->getDadosGrupoFator();
		$this->view->res = $res;
	}
}