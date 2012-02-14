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

class EventoController extends Base_Controller_Action
{
	private $objEvento;
	
	public function init()
	{
		parent::init();
		$this->objEvento = new Evento($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->view->formEvento = new EventoForm();
	}

	public function salvarEventoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$return = $this->objEvento->salvarEvento($arrDados);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo  $msg;		
	}
	
	public function alterarEventoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$return = $this->objEvento->alterarEvento($arrDados);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo  $msg;
	}
	
	public function excluirEventoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_evento = (int)$this->_request->getParam('cd_evento', 0);
		
		if ($this->objEvento->delete(array('cd_evento = ?'=>$cd_evento))) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	public function recuperaEventoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_evento = $this->_request->getParam('cd_evento',0);
		
		$arrDados = $this->objEvento->getDadosEvento($cd_evento);
		$arrDados = $arrDados[0];
				
		echo Zend_Json_Encoder::encode($arrDados);
	}
	
	public function gridEventoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$res = $this->objEvento->getDadosEvento();
		$this->view->res = $res;
	}
}