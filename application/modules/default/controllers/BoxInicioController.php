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

class BoxInicioController extends Base_Controller_Action 
{
	private $objBoxInicio;
	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_BOXES_INICIO'));
		$this->objBoxInicio = new BoxInicio($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->initView();
	}
	
	public function salvarBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		
		$cd_box_inicio         = $this->_request->getParam('cd_box_inicio');
		$tx_box_inicio         = $this->_request->getParam('tx_box_inicio');
		$tx_titulo_box_inicio  = $this->_request->getParam('tx_titulo_box_inicio');
		$st_tipo_box_inicio    = $this->_request->getParam('st_tipo_box_inicio');
		$return = $this->objBoxInicio->salvarBoxInicio($tx_box_inicio,$tx_titulo_box_inicio,$st_tipo_box_inicio,$cd_box_inicio);
		
		if($return){
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
//	public function alterarboxInicioAction()
//	{
//		$this->_helper->viewRenderer->setNoRender(true);
//		$this->_helper->layout->disableLayout();
//		
//		$cd_box_inicio         = $this->_request->getParam('cd_box_inicio');
//		$tx_box_inicio         = $this->_request->getParam('tx_box_inicio');
//		$tx_titulo_box_inicio  = $this->_request->getParam('tx_titulo_box_inicio');
//		$st_tipo_box_inicio    = $this->_request->getParam('st_tipo_box_inicio');
//		
//		$return = $this->objBoxInicio->alterarBoxInicio($cd_box_inicio,$tx_box_inicio,$tx_titulo_box_inicio,$st_tipo_box_inicio);
//		if($return){
//			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
//		} else {
//			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
//		}
//	}

	public function excluirBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$cd_box_inicio = $this->_request->getPost('cd_box_inicio');
		$return = $this->objBoxInicio->delete("cd_box_inicio = {$cd_box_inicio}");
		if($return){
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridBoxInicioAction()
	{
		$this->_helper->layout->disableLayout();
		$res = $this->objBoxInicio->fetchAll(null,'tx_box_inicio')->toArray();

		$this->view->res = $res;
		$this->view->comboTipoBox = $this->objBoxInicio->getArrTipoBox();
	}
	
	public function redirecionaBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_box_inicio = $this->_request->getPost('cd_box_inicio');
		$where = "cd_box_inicio = {$cd_box_inicio}";
		$arrBoxInicio  = $this->objBoxInicio->fetchAll($where)->toArray();

		echo Zend_Json::encode($arrBoxInicio);
		
	}
	
}
