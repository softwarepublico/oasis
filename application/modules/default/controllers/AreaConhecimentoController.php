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

class AreaConhecimentoController extends Base_Controller_Action 
{
	protected $objAreaConhecimento;
	
	public function init()
	{
		parent::init();
		$this->objAreaConhecimento = new AreaConhecimento($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_AREA_CONHECIMENTO'));
	}
	
	public function salvarAreaConhecimentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$return = $this->objAreaConhecimento->salvarAreaConhecimento($arrPost);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function alterarAreaConhecimentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$return = $this->objAreaConhecimento->alterarAreaConhecimento($arrPost);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo $msg;
	}
	
	public function excluirAreaConhecimentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_area_conhecimento = $this->_request->getParam('cd_area_conhecimento');
		
		$return = $this->objAreaConhecimento->excluirAreaConhecimento($cd_area_conhecimento);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		
		echo $msg;
	}
	
	public function gridAreaConhecimentoAction()
	{
		$this->_helper->layout->disableLayout();

		$this->view->res = $this->objAreaConhecimento->getDadosAreaConhecimento();
	}
	
	public function recuperaAreaConhecimentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_area_conhecimento = $this->_request->getParam('cd_area_conhecimento');
		$arrDados = $this->objAreaConhecimento->getDadosAreaConhecimento($cd_area_conhecimento);
		
		echo Zend_Json::encode($arrDados);
	}
}