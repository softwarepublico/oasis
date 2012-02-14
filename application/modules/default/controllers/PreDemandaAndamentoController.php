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

class PreDemandaAndamentoController extends Base_Controller_Action
{

	private $zendDate;
	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PRE_DEMANDA_ANDAMENTO'));
		$this->zendDate = new Zend_Date();
	}

	public function indexAction()
	{
		$objObjetoContrato = new ObjetoContrato($this->_request->getControllerName());
		$arrObjetoExecutor = $objObjetoContrato->getObjetoContratoAtivo(null, true);
		
		$this->view->objetoCombo  = $arrObjetoExecutor;
	}

	public function pesquisaPreDemandaAndamentoAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$preDemanda = new PreDemanda($this->_request->getControllerName());

		if (!is_null($this->_request->getParam('mes'))) {
			$mesAtual = $this->_request->getParam('mes');
		} else {
			$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		}

		if (!is_null($this->_request->getParam('ano'))) {
			$anoAtual = $this->_request->getParam('ano');

		} else {
			$anoAtual = date("Y");
		}

		//parâmetro opcional
		//pesquisa as solicitacoes por objeto de contrato
		if ($this->_request->getParam('cd_objeto_receptor') != 0) {
			$cd_objeto_receptor = $this->_request->getParam('cd_objeto_receptor');

		} else {
			$cd_objeto_receptor = null;
		}
		
		$mesAtual = substr("00".$mesAtual,-2);
		$cd_objeto_emissor = $_SESSION["oasis_logged"][0]["cd_objeto"];
		$this->view->res = $preDemanda->getPreDemandaAndamento($mesAtual, $anoAtual, $cd_objeto_emissor, $cd_objeto_receptor);
		
		echo $this->view->render('pre-demanda-andamento/pesquisa-pre-demanda-andamento.phtml');
	}
}