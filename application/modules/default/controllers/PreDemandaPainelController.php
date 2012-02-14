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

class PreDemandaPainelController extends Base_Controller_Action
{
	private $zendDate;
	private $objUtil;
	private $objDemandaProfissional;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PRE_DEMANDA'));
		$this->zendDate               = new Zend_Date();
		$this->objUtil                = new Base_Controller_Action_Helper_Util();
		$this->preDemanda             = new PreDemanda($this->_request->getControllerName());
		$this->objDemandaProfissional =  new DemandaProfissional($this->_request->getControllerName());
	}

	public  function indexAction()
	{
		//tela Pré-Demanda Andamento
		$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		$anoAtual = date("Y");
		$this->view->mesAno = "{$this->objUtil->getMes($mesAtual)}/{$anoAtual}";
		
		$objObjetoContrato = new ObjetoContrato($this->_request->getControllerName());
		$arrObjetoExecutor = $objObjetoContrato->getObjetoContratoAtivo('D', true);
		$this->view->objetoCombo  = $arrObjetoExecutor;		
	}

	public function gridPreDemandaExecutadaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');

		//parâmetro opcional
		if ($this->_request->getParam('cd_objeto_receptor') != 0) {
			$cd_objeto_receptor = $this->_request->getParam('cd_objeto_receptor');

		} else {
			$cd_objeto_receptor = null;
		}

		$mes    = ($mes == 0)?date('n'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		$mes    = substr("00".$mes,-2);
		$mes    = substr("00".$mes,-2);
		
		$cd_objeto_emissor      = $_SESSION["oasis_logged"][0]["cd_objeto"];		
		$arrPreDemandaExecutada = $this->preDemanda->getPreDemandaExecutada($mes, $ano, $cd_objeto_emissor, $cd_objeto_receptor);
		
		$this->view->res    = $arrPreDemandaExecutada;
		$this->view->mesAno = $mesAno;
	}
	
	public function aceitePreDemandaAction()
	{
		$this->preDemandaDetalhes();
	}
	
	public function aceitarPreDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$erros = false;

		$post = $this->_request->getPost();

		$upRow                              = array();
		$upRow['st_aceite_pre_demanda']     = $post['st_aceite_pre_demanda'];
		$upRow['tx_obs_aceite_pre_demanda'] = $post['tx_obs_aceite_pre_demanda'];
		$upRow['dt_aceite_pre_demanda']     = date('Y-m-d H:i:s');

		$erros = $this->preDemanda->atualizaPreDemanda($post['cd_pre_demanda'], $upRow);

		if ($erros === false)
		{
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			if (K_ENVIAR_EMAIL == "S") {
				$arrPreDemanda = $this->preDemanda->find($post['cd_pre_demanda'])->current()->toArray();
				$_objMail = new Base_Controller_Action_Helper_Mail();
				$_objMail->enviaEmail($arrPreDemanda["cd_objeto_emissor"], $this->_request->getControllerName(), $arrDados);
			}
		}
		else
		{
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function preDemandaDetalhesAction()
	{
		$this->preDemandaDetalhes();
	}
	
	private function preDemandaDetalhes()
	{
		$objHistorico                       = new HistoricoExecucaoDemanda($this->_request->getControllerName());		
		$cd_pre_demanda                     = $this->_request->getParam('cd_pre_demanda');
		$abaOrigem                          = $this->_request->getParam('abaOrigem');
		$arrPreDemanda                      = $this->preDemanda->getPreDemanda($cd_pre_demanda);
		$arrPreDemanda[0]['tx_pre_demanda'] = str_ireplace('\"','"',$arrPreDemanda[0]['tx_pre_demanda']);		
		$this->view->preDemanda             = $arrPreDemanda;
		$this->view->abaOrigem              = $abaOrigem;
		
		if (!is_null($arrPreDemanda[0]['ni_solicitacao'])){
			$demanda = new Demanda($this->_request->getControllerName());
			$rowDemanda = $demanda->fetchRow("cd_objeto = {$arrPreDemanda[0]['cd_objeto']} and ni_solicitacao = {$arrPreDemanda[0]['ni_solicitacao']} and ni_ano_solicitacao = {$arrPreDemanda[0]['ni_ano_solicitacao']}");
			
			if (!is_null($rowDemanda)){
				$rowDemanda = $rowDemanda->toArray();
				$cd_demanda = $rowDemanda['cd_demanda'];
				$rowDemanda = $demanda->getDemanda($cd_demanda);
				$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($cd_demanda, $arrPreDemanda[0]['cd_objeto'], "S",1);

				$arrHistorico = $objHistorico->historicoDemandaNivelServico($cd_demanda);
				
				$this->view->arrHistorico             = $arrHistorico;				
				$this->view->arrDemandaProfissional   = $arrDemandaProfissional;
			}
		}
	}
}