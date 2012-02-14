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

class ControlePropostaController extends Base_Controller_Action
{

	private $zendDate;
	private $proposta;
	private $processamentoProposta;
	private $parecerTecnicoProposta;
	private $profissional;
	private $itemParecerTecnico;
	private $objContratoDefinicaoMetrica;
		
	public function init()
	{
		parent::init();
		$this->view->headTitle(Base_Util::setTitle('L_TIT_CONTROLE_PROPOSTA'));
        
		$this->zendDate                    = new Zend_Date();
		$this->proposta                    = new Proposta($this->_request->getControllerName());
		$this->processamentoProposta       = new ProcessamentoProposta($this->_request->getControllerName());
		$this->parecerTecnicoProposta      = new ParecerTecnicoProposta($this->_request->getControllerName());
		$this->profissional                = new Profissional($this->_request->getControllerName());
		$this->itemParecerTecnico          = new ItemParecerTecnico($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	public function pesquisaPropostasAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

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

		$mesAtual    = substr("00".$mesAtual,-2);
		$cd_contrato = ($this->_request->getParam('cd_contrato') == 0)? null : $this->_request->getParam('cd_contrato');
		
		if ($mesAtual >= date("m") && $anoAtual >= date("Y")){
			$arrControlePropostas     = $this->proposta->getControlePropostas($mesAtual, $anoAtual, $cd_contrato);
			$this->view->mes_corrente = true;
		}else{
			$arrControlePropostas     = $this->proposta->getControlePropostasMesAnterior($mesAtual, $anoAtual, $cd_contrato);
			$this->view->mes_corrente = false;
		}

		foreach($arrControlePropostas as $key=>$value){
			$siglaMetricaPadraoContrato = $this->objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($value["cd_projeto"]);
			$siglaMetricaPadrao         = (count($siglaMetricaPadraoContrato)>0)? $siglaMetricaPadraoContrato["tx_sigla_unidade_metrica"] : Base_Util::getTranslator('L_VIEW_UNID_METRICA');
			$arrControlePropostas[$key]["ni_horas_proposta"] = "{$arrControlePropostas[$key]["ni_horas_proposta"]} {$siglaMetricaPadrao}";
		}

		$this->view->res          = $arrControlePropostas;

		echo $this->view->render('controle-proposta/pesquisa-propostas.phtml');
	}
	
	public function parecerTecnicoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto                = $this->_request->getParam('cd_projeto');
		$cd_proposta               = $this->_request->getParam('cd_proposta');
		$tx_sigla_projeto          = $this->_request->getParam('tx_sigla_projeto');
		$cd_processamento_proposta = $this->_request->getParam('cd_processamento_proposta');
		
		$rowProcessamentoProposta = $this->processamentoProposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_processamento_proposta = {$cd_processamento_proposta}")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoProposta["cd_prof_parecer_tecnico_propos"])->current()->toArray();
		$arrItemParecerTecnico    = $this->itemParecerTecnico->fetchAll('st_proposta is not null');
		$arrParecerTecnico        = $this->parecerTecnicoProposta->fetchAll("cd_processamento_proposta = {$cd_processamento_proposta}");

		$arrAvaliacao             = array("OK" => Base_Util::getTranslator('L_VIEW_OK'),
                                          "NO" => Base_Util::getTranslator('L_VIEW_NAO_OK'),
                                          "NA" => Base_Util::getTranslator('L_VIEW_NAO_APLICA'));
		
		$this->view->dt_parecer_tecnico_proposta            = (!is_null($rowProcessamentoProposta["dt_parecer_tecnico_proposta"]))? date('d/m/Y H:i:s', strtotime($rowProcessamentoProposta["dt_parecer_tecnico_proposta"]) ):"";
		$this->view->tx_obs_parecer_tecnico_prop            = $rowProcessamentoProposta["tx_obs_parecer_tecnico_prop"];
		$this->view->profissional_parecer_tecnico_proposta  = $rowProfissional["tx_profissional"];
		$this->view->arrItemParecerTecnico                  = $arrItemParecerTecnico;
		$this->view->arrParecerTecnico                      = $arrParecerTecnico;
		$this->view->arrAvaliacao                           = $arrAvaliacao;
		$this->view->tx_sigla_projeto                       = $tx_sigla_projeto;
		
		echo $this->view->render('controle-proposta/parecer-tecnico-proposta.phtml');
	}
	
	public function aceitePropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto                = $this->_request->getParam('cd_projeto');
		$cd_proposta               = $this->_request->getParam('cd_proposta');
		$tx_sigla_projeto          = $this->_request->getParam('tx_sigla_projeto');
		$cd_processamento_proposta = $this->_request->getParam('cd_processamento_proposta');
		
		$rowProcessamentoProposta = $this->processamentoProposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_processamento_proposta = {$cd_processamento_proposta}")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoProposta["cd_prof_aceite_proposta"])->current()->toArray();
		
		$this->view->dt_aceite_proposta            = date('d/m/Y H:i:s', strtotime($rowProcessamentoProposta["dt_aceite_proposta"]));
		$this->view->tx_obs_aceite_proposta        = $rowProcessamentoProposta["tx_obs_aceite_proposta"];
		$this->view->profissional_aceite_proposta  = $rowProfissional["tx_profissional"];
		$this->view->tx_sigla_projeto              = $tx_sigla_projeto;
		
		echo $this->view->render('controle-proposta/aceite-proposta.phtml');
	}
	
	public function homologacaoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto                = $this->_request->getParam('cd_projeto');
		$cd_proposta               = $this->_request->getParam('cd_proposta');
		$tx_sigla_projeto          = $this->_request->getParam('tx_sigla_projeto');
		$cd_processamento_proposta = $this->_request->getParam('cd_processamento_proposta');
		
		$rowProcessamentoProposta = $this->processamentoProposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_processamento_proposta = {$cd_processamento_proposta}")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoProposta["cd_prof_homologacao_proposta"])->current()->toArray();
		
		$this->view->dt_homologacao_proposta            = date('d/m/Y H:i:s', strtotime($rowProcessamentoProposta["dt_homologacao_proposta"]));
		$this->view->tx_obs_homologacao_proposta        = $rowProcessamentoProposta["tx_obs_homologacao_proposta"];
		$this->view->profissional_homologacao_proposta  = $rowProfissional["tx_profissional"];
		$this->view->tx_sigla_projeto                   = $tx_sigla_projeto;
		
		echo $this->view->render('controle-proposta/homologacao-proposta.phtml');
	}

    public function alocacaoRecursoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto                = $this->_request->getParam('cd_projeto');
		$cd_proposta               = $this->_request->getParam('cd_proposta');
		$tx_sigla_projeto          = $this->_request->getParam('tx_sigla_projeto');
		$cd_processamento_proposta = $this->_request->getParam('cd_processamento_proposta');

		$rowProcessamentoProposta = $this->processamentoProposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_processamento_proposta = {$cd_processamento_proposta}")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoProposta["cd_prof_homologacao_proposta"])->current()->toArray();

		$this->view->dt_alocacao_proposta         = date('d/m/Y H:i:s', strtotime($rowProcessamentoProposta["dt_alocacao_proposta"]));
		$this->view->tx_motivo_alteracao_proposta = $rowProcessamentoProposta["tx_motivo_alteracao_proposta"];
		$this->view->prof_alocacao_proposta       = $rowProfissional["tx_profissional"];
		$this->view->tx_sigla_projeto             = $tx_sigla_projeto;

		echo $this->view->render('controle-proposta/alocacao-recurso-proposta.phtml');
	}
    
	public function fechamentoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto                = $this->_request->getParam('cd_projeto');
		$cd_proposta               = $this->_request->getParam('cd_proposta');
		$tx_sigla_projeto          = $this->_request->getParam('tx_sigla_projeto');
		$cd_processamento_proposta = $this->_request->getParam('cd_processamento_proposta');
		
		$rowProcessamentoProposta = $this->processamentoProposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_processamento_proposta = {$cd_processamento_proposta}")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoProposta["cd_prof_fechamento_proposta"])->current()->toArray();
		
		$this->view->dt_fechamento_proposta           = date('d/m/Y H:i:s', strtotime($rowProcessamentoProposta["dt_fechamento_proposta"]));
		$this->view->profissional_fechamento_proposta = $rowProfissional["tx_profissional"];
		$this->view->tx_sigla_projeto                 = $tx_sigla_projeto;
		
		echo $this->view->render('controle-proposta/fechamento-proposta.phtml');
	}	
}