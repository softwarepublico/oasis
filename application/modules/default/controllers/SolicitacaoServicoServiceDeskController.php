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

class SolicitacaoServicoServiceDeskController extends Base_Controller_Action
{

	private $zendDate;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACOES_DE_SERVICO'));
		$this->zendDate = new Zend_Date();
	}

	public function indexAction()
	{
        
		$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		$anoAtual = date("Y");

		$this->view->mesComboValue = $mesAtual;
		$this->view->anoComboValue = $anoAtual;
        
        $st_solicitacao = '6';
		//busca os anos das solicitacoes atraves de um distinct na tabela
		$solicitacaoServiceDesk       = new SolicitacaoServiceDesk($this->_request->getControllerName());
		$solicitacaoServiceDeskSelect = $solicitacaoServiceDesk->select();
        $solicitacaoServiceDeskSelect->distinct();
		$solicitacaoServiceDeskSelect->from($solicitacaoServiceDesk, array('ni_ano_solicitacao'));

        $resAnoSolicitacaoServiceDesk = $solicitacaoServiceDesk->fetchAll($solicitacaoServiceDeskSelect);

		$arrAnos = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));

		foreach ($resAnoSolicitacaoServiceDesk as $ano) {
			$arrAnos[$ano->ni_ano_solicitacao] = $ano->ni_ano_solicitacao;
		}

		//montagem da combo de objeto de contrato
		$objeto    = new ObjetoContrato($this->_request->getControllerName());
		$arrObjeto = $objeto->getObjetoContratoAtivo(null, true, false, true);

		//montagem da combo de tipo de solicitacao
		$arrTipoSolicitacao    = array();

        $arrTipoSolicitacao[6] = Base_Util::getTranslator('L_VIEW_COMBO_SERVICE_DESK');

        
		$this->view->mesCombo             = $this->_helper->util->getMes();
		$this->view->anoCombo             = $arrAnos;
		$this->view->objetoCombo          = $arrObjeto;
		$this->view->tipoSolicitacaoCombo = $arrTipoSolicitacao;
		
		//*** TAB CONSULTA DE SOLICITAÇÕES DE SERVIÇO
		//Combo Unidade
		$unidade                      = new Unidade($this->_request->getControllerName());
		$arrUnidade                   = $unidade->getUnidade(true);
		$this->view->cd_unidade_combo = $arrUnidade;

		//Combo Profissional
		$cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$profissional    = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$arrObjeto       = $objeto->getDadosObjetoContrato(null, $cd_objeto);
		
		$arrProfissional = ($arrObjeto[0]["st_objeto_contrato"] == "D") ? 
							$profissional->getProfissionalGerenteTecnicoObjetoContrato($cd_objeto, true) :
		 					$profissional->getProfissionalGerenteObjetoContrato($cd_objeto, true);
		 					
		$this->view->cd_profissional_combo = $arrProfissional;					

	}

	public function pesquisaSolicitacaoServicoServiceDeskAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$solicitacaoServiceDesk = new SolicitacaoServiceDesk($this->_request->getControllerName());

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
		if ($this->_request->getParam('cd_objeto') != 0 && $this->_request->getParam('cd_objeto') != -1) {
			$cd_objeto = $this->_request->getParam('cd_objeto');

		} else {
			$cd_objeto = null;
		}

		//parametro opcional
		//pesquisa as solicitacoes por tipo de solicitacao
		if ($this->_request->getParam('st_solicitacao') != 0) {
			$st_solicitacao = $this->_request->getParam('st_solicitacao');

		} else {
			$st_solicitacao = null;
		}
			
		$mesAtual = substr("00".$mesAtual,-2);
		$arrSolicitacoes = $solicitacaoServiceDesk->getSolicitacaoServiceDeskCoordenacao($mesAtual, $anoAtual, $cd_objeto, $st_solicitacao);

        $arrAux = array();
		foreach($arrSolicitacoes as $key => $arrSolicitacao)
		{
			$arrDados                                   = $solicitacaoServiceDesk->verificaJustificativa($arrSolicitacao['cd_objeto'], $arrSolicitacao);
			$arrAux[$key]                               = $arrDados[2];
			$arrAux[$key]['st_necessita_justificativa'] = $arrDados[0];
			$arrAux[$key]['ni_minutos_justificativa']   = $arrDados[1];
		}
		$this->view->res = $arrAux;

		echo $this->view->render('solicitacao-servico-service-desk/pesquisa-solicitacao-servico-service-desk.phtml');
	}

	public function tabAnaliseSolicitacaoServiceDeskJustificativaAction()
	{
		$this->_helper->layout->disableLayout();

		$_objSolicitacaoServiceDesk  = new SolicitacaoServiceDesk($this->_request->getControllerName());
		$cd_objeto              = $this->_request->getParam('cd_objeto');
		$ni_solicitacao         = $this->_request->getParam('ni_solicitacao');
		$ni_ano_solicitacao     = $this->_request->getParam('ni_ano_solicitacao');
		$tab_origem             = $this->_request->getParam('tab_origem');
		$arrDados               = $_objSolicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		$this->view->arrDados   = $arrDados;
		$this->view->tab_origem = $tab_origem;
		$this->view->st_aceite  = (!is_null($arrDados['st_aceite_just_solicitacao']) && $arrDados['st_aceite_just_solicitacao']=="S")? "Sim" : "Não";
	}

	public function salvaAnaliseSolicitacaoServiceDeskJustificativaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
 		$arrPost = $this->_request->getPost();

		$_objSolicitacaoServiceDesk                  = new SolicitacaoServiceDesk($this->_request->getControllerName());
		$arrUpdate['st_aceite_just_solicitacao']     = $arrPost['st_aceite_just_solicitacao'];
		$arrUpdate['tx_obs_aceite_just_solicitacao'] = $arrPost['tx_obs_aceite_just_solicitacao'];
		
		$erros = $_objSolicitacaoServiceDesk->atualizaSolicitacaoServiceDesk($arrPost['cd_objeto'], $arrPost['ni_solicitacao'], $arrPost['ni_ano_solicitacao'], $arrUpdate);

		if ($erros === false) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		}else{
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
}