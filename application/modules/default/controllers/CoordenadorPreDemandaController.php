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

class CoordenadorPreDemandaController extends Base_Controller_Action
{
	private $objContrato;
	private $objPreDemanda;
	private $objUtil;

	public function init()
	{
		parent::init();
		$this->objContrato            = new ObjetoContrato($this->_request->getControllerName());
		$this->objPreDemanda          = new PreDemanda($this->_request->getControllerName());
		$this->objUtil                = new Base_Controller_Action_Helper_Util();
		$this->objDemandaProfissional = new DemandaProfissional($this->_request->getControllerName());
	}

	public  function indexAction()
	{
		$arrObjetoContrato = $this->objContrato->getObjetoContratoAtivo('D',true);
		$this->view->arrObjetoContrato = $arrObjetoContrato;

		$mesPreDemanda = date('n');
		$anoPreDemanda = date('Y');
		$this->view->mesAno = "{$this->objUtil->getMes($mesPreDemanda)}/{$anoPreDemanda}";
	}
	
	public function gridPreDemandaAction()
	{
		$this->_helper->layout->disableLayout();

		$nucleo = $this->_request->getParam('nucleo');
		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');

		$mes    = ($mes == 0)?date('n'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;
		$nucleo = ($nucleo == 0)?NULL:$nucleo;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		$mes    = substr("00".$mes, -2);

		$cd_objeto_emissor = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$arrPreDemanda = $this->objPreDemanda->getPreDemandaAndamento($mes,$ano,$cd_objeto_emissor,$nucleo);

		
		$this->view->res = $arrPreDemanda;
		$this->view->mesAno = $mesAno;
	}

	public function gridPreDemandaExecutadaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_objeto_receptor = $this->_request->getParam('cd_objeto_receptor');
		$mes                = $this->_request->getParam('mes');
		$ano                = $this->_request->getParam('ano');

		$mes                = ($mes == 0)?date('n'):$mes;
		$ano                = ($ano == 0)?date('Y'):$ano;
		$cd_objeto_receptor = ($cd_objeto_receptor == 0)?NULL:$cd_objeto_receptor;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		$mes    = substr("00".$mes, -2);

		$cd_objeto_emissor      = $_SESSION['oasis_logged'][0]['cd_objeto'];		
		$arrPreDemandaExecutada = $this->objPreDemanda->getPreDemandaExecutada($mes,$ano,$cd_objeto_emissor,$cd_objeto_receptor);

		$this->view->res    = $arrPreDemandaExecutada;
		$this->view->mesAno = $mesAno;
	}

	public function reabrirPreDemandaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_pre_demanda     = $this->_request->getParam('cd_pre_demanda');
		$cd_objeto          = $this->_request->getParam('cd_objeto');
		$ni_solicitacao     = $this->_request->getParam('ni_solicitacao');
		$ni_ano_solicitacao = $this->_request->getParam('ni_ano_solicitacao');

		$rowPreDemanda  = $this->objPreDemanda->getPreDemanda($cd_pre_demanda);

		$this->view->tx_obs_aceite_pre_demanda = $rowPreDemanda[0]['tx_obs_aceite_pre_demanda'];
		$this->view->cd_pre_demanda            = $cd_pre_demanda;
		$this->view->cd_objeto                 = $cd_objeto;
		$this->view->ni_solicitacao            = $ni_solicitacao;
		$this->view->ni_ano_solicitacao        = $ni_ano_solicitacao;
		$this->view->cd_demanda                = $rowPreDemanda[0]['cd_demanda'];
	}

	public function reabrirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		$erros = $this->reabrePreDemanda($post);
		
		if ($erros === false){
			$erros = $this->abreSolicitacaoTipoDemanda($post);
		}
		
		if ($erros === false){
			$erros = $this->abreDemanda($post);
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_REABRIR_PRE_DEMANDA');
		} else {
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail            = new Base_Controller_Action_Helper_Mail();
				$arrPreDemanda       = $this->objPreDemanda->find($post['cd_pre_demanda'])->current()->toArray();
				$arrDados['_tx_obs'] = $_SESSION['tx_obs'];
				$_objMail->enviaEmail($arrPreDemanda["cd_objeto_receptor"], $this->_request->getControllerName(), $arrDados);
				unset ($_SESSION['tx_obs']);
			}
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_REABRIR_PRE_DEMANDA');
		}
	}

	public function reabrePreDemanda($post)
	{
		$erros = false;

		$upRow = array();
		$upRow['st_reabertura_pre_demanda']     = "S";
		$upRow['tx_obs_reabertura_pre_demanda'] = $post["tx_obs_reabertura_pre_demanda"];
		$upRow['dt_fim_pre_demanda']            = null;
		$upRow['st_fim_pre_demanda']            = null;
		
		$erros = $this->objPreDemanda->atualizaPreDemanda($post['cd_pre_demanda'], $upRow);

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['tx_obs'] = $post["tx_obs_reabertura_pre_demanda"];
		}

		return $erros;
	}

	public function abreSolicitacaoTipoDemanda($post)
	{
		$erros = false;
		
		$upRow                  = array();
		$upRow['st_fechamento'] = null;
		$upRow['dt_fechamento'] = null;		
		
		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros = $solicitacao->atualizaSolicitacao($post['cd_objeto'], $post['ni_solicitacao'], $post['ni_ano_solicitacao'], $upRow);
	
		return $erros;
	}

	public function abreDemanda($post)
	{
		$erros = false;
		
		$upRow                         = array();
		$upRow['st_conclusao_demanda'] = null;
		$upRow['dt_conclusao_demanda'] = null;		
		
		$demanda = new Demanda($this->_request->getControllerName());
		$erros   = $demanda->atualizaDemanda($post["cd_demanda"],$upRow);
	
		return $erros;
	}
	
	public function solicitacaoExecutadaDetalhesAction()
	{
		$objDemanda   = new Demanda($this->_request->getControllerName());
		$objHistorico = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		
		$cd_demanda   = $this->_request->getParam("cd_demanda");
		
		//Recupero os dados da demanda
		$arrDemanda                      = $objDemanda->getDemanda($cd_demanda);
		//Recupero os dados da pre-demanda
		$arrPreDemanda                   = $this->objPreDemanda->fetchRow("ni_solicitacao = {$arrDemanda["ni_solicitacao"]} and ni_ano_solicitacao = {$arrDemanda["ni_ano_solicitacao"]} and cd_objeto_receptor = {$arrDemanda["cd_objeto"]}")->toArray();
		$arrPreDemanda['tx_pre_demanda'] = str_ireplace('\"','"',$arrPreDemanda['tx_pre_demanda']);		
		//Recupero os dados do historico do profissional
		$arrHistorico                    = $objHistorico->historicoDemandaNivelServico($cd_demanda);
		
		$situacao  = Base_Util::getTranslator('L_VIEW_AGUARDANDO_ACEITE');
		$avaliacao = "";
		if (!is_null($arrPreDemanda["st_aceite_pre_demanda"]))
		{
			$situacao  = ($arrPreDemanda["st_aceite_pre_demanda"] == "S") ? Base_Util::getTranslator('L_VIEW_ATENDIDO_CORRETAMENTO') : Base_Util::getTranslator('L_VIEW_NAO_ATENDIDO_CORRETAMENTO');
			$avaliacao = $arrPreDemanda["tx_obs_aceite_pre_demanda"];
		}
		
		$this->view->abaorigem     = $this->_request->getParam("abaOrigem");
		$this->view->arrHistorico  = $arrHistorico;
		$this->view->arrDemanda    = $arrDemanda;
		$this->view->situacao      = $situacao;
		$this->view->avaliacao     = $avaliacao;
	}

	public function preDemandaDetalhesAction()
	{
		$objHistorico                       = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		$cd_pre_demanda                     = $this->_request->getParam('cd_pre_demanda');
		$abaOrigem                          = $this->_request->getParam('abaOrigem');
		$arrPreDemanda                      = $this->objPreDemanda->getPreDemanda($cd_pre_demanda);
		$arrPreDemanda[0]['tx_pre_demanda'] = str_ireplace('\"','"',$arrPreDemanda[0]['tx_pre_demanda']);
		$this->view->preDemanda             = $arrPreDemanda;
		$this->view->abaOrigem              = $abaOrigem;

		if (!is_null($arrPreDemanda[0]['ni_solicitacao']))
		{
			$demanda = new Demanda($this->_request->getControllerName());
			$rowDemanda = $demanda->fetchRow("cd_objeto = {$arrPreDemanda[0]['cd_objeto']} and ni_solicitacao = {$arrPreDemanda[0]['ni_solicitacao']} and ni_ano_solicitacao = {$arrPreDemanda[0]['ni_ano_solicitacao']}");

			if (!is_null($rowDemanda))
			{
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