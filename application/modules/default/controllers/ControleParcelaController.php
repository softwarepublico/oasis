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

class ControleParcelaController extends Base_Controller_Action
{

	private $zendDate;
	private $parcela;
	private $solicitacao;
	private $processamentoParcela;
	private $parecerTecnicoProposta;
	private $profissional;
	private $itemParecerTecnico;
	private $objContratoDefinicaoMetrica;
		
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CONTROLE_PARCELAS'));

		$this->zendDate                    = new Zend_Date();
		$this->parcela                     = new Parcela($this->_request->getControllerName());
		$this->solicitacao                 = new Solicitacao($this->_request->getControllerName());
		$this->processamentoParcela        = new ProcessamentoParcela($this->_request->getControllerName());
		$this->parecerTecnicoParcela       = new ParecerTecnicoParcela($this->_request->getControllerName());
		$this->profissional                = new Profissional($this->_request->getControllerName());
		$this->itemParecerTecnico          = new ItemParecerTecnico($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	public function pesquisaControleParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$parcela = new Parcela($this->_request->getControllerName());

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
		
		$arrControleParcelas = $parcela->getControleParcelas($mesAtual, $anoAtual, $cd_contrato);

        if ($mesAtual >= date("m") && $anoAtual >= date("Y")){
			$this->view->mes_corrente = true;
		}else{
			$this->view->mes_corrente = false;
		}

		foreach($arrControleParcelas as $key=>$value)
		{
			$siglaMetricaPadraoContrato = $this->objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($value["cd_projeto"]);
			$siglaMetricaPadrao         = (count($siglaMetricaPadraoContrato)>0) ? $siglaMetricaPadraoContrato["tx_sigla_unidade_metrica"] : Base_Util::getTranslator('L_VIEW_UNID_METRICA');

			$arrControleParcelas[$key]["ni_horas_parcela"] = "{$arrControleParcelas[$key]["ni_horas_parcela"]} {$siglaMetricaPadrao}";
		}

		$this->view->res = $arrControleParcelas;
	}

	public function parecerTecnicoParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto       = $this->_request->getParam('cd_projeto');
		$cd_proposta      = $this->_request->getParam('cd_proposta');
		$cd_parcela       = $this->_request->getParam('cd_parcela');
		$ni_parcela       = $this->_request->getParam('ni_parcela');
		$tx_sigla_projeto = $this->_request->getParam('tx_sigla_projeto');
		
		$rowProcessamentoParcela  = $this->processamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoParcela["cd_prof_parecer_tecnico_parc"])->current()->toArray();
		$arrItemParecerTecnico    = $this->itemParecerTecnico->fetchAll('st_parcela IS NOT NULL');
		$arrParecerTecnico        = $this->parecerTecnicoParcela->fetchAll("cd_processamento_parcela = {$rowProcessamentoParcela["cd_processamento_parcela"]}");

		$arrAvaliacao             = array("OK" => Base_Util::getTranslator('L_VIEW_OK'),
                                          "NO" => Base_Util::getTranslator('L_VIEW_NAO_OK'),
                                          "NA" => Base_Util::getTranslator('L_VIEW_NAO_APLICA'));
		
		$this->view->dt_parecer_tecnico_parcela            = date('d/m/Y H:i:s', strtotime($rowProcessamentoParcela["dt_parecer_tecnico_parcela"]));
		$this->view->tx_obs_parecer_tecnico_parcela        = $rowProcessamentoParcela["tx_obs_parecer_tecnico_parcela"];
		$this->view->profissional_parecer_tecnico_parcela  = $rowProfissional["tx_profissional"];
		$this->view->arrItemParecerTecnico                 = $arrItemParecerTecnico;
		$this->view->arrParecerTecnico                     = $arrParecerTecnico;
		$this->view->arrAvaliacao                          = $arrAvaliacao;
		$this->view->tx_sigla_projeto                      = $tx_sigla_projeto;
		$this->view->ni_parcela                            = $ni_parcela;
	}
	
	public function aceiteParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto       = $this->_request->getParam('cd_projeto');
		$cd_proposta      = $this->_request->getParam('cd_proposta');
		$cd_parcela       = $this->_request->getParam('cd_parcela');
		$ni_parcela       = $this->_request->getParam('ni_parcela');
		$tx_sigla_projeto = $this->_request->getParam('tx_sigla_projeto');
		
		$rowProcessamentoParcela = $this->processamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'")->toArray();
		$rowProfissional         = $this->profissional->find($rowProcessamentoParcela["cd_profissional_aceite_parcela"])->current()->toArray();
		
		$this->view->dt_aceite_parcela            = date('d/m/Y H:i:s', strtotime($rowProcessamentoParcela["dt_aceite_parcela"]));
		$this->view->tx_obs_aceite_parcela        = $rowProcessamentoParcela["tx_obs_aceite_parcela"];
		$this->view->profissional_aceite_parcela  = $rowProfissional["tx_profissional"];
		$this->view->ni_parcela                   = $ni_parcela;
		$this->view->tx_sigla_projeto             = $tx_sigla_projeto;
	}
	
	public function homologacaoParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto       = $this->_request->getParam('cd_projeto');
		$cd_proposta      = $this->_request->getParam('cd_proposta');
		$cd_parcela       = $this->_request->getParam('cd_parcela');
		$ni_parcela       = $this->_request->getParam('ni_parcela');
		$tx_sigla_projeto = $this->_request->getParam('tx_sigla_projeto');
		
		$rowProcessamentoParcela = $this->processamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'")->toArray();
		$rowProfissional          = $this->profissional->find($rowProcessamentoParcela["cd_prof_homologacao_parcela"])->current()->toArray();
		
		$this->view->dt_homologacao_parcela            = date('d/m/Y H:i:s', strtotime($rowProcessamentoParcela["dt_homologacao_parcela"]));
		$this->view->tx_obs_homologacao_parcela        = $rowProcessamentoParcela["tx_obs_homologacao_parcela"];
		$this->view->profissional_homologacao_parcela  = $rowProfissional["tx_profissional"];
		$this->view->ni_parcela                        = $ni_parcela;
		$this->view->tx_sigla_projeto                  = $tx_sigla_projeto;
	}	
	
	public function autorizacaoParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto       = $this->_request->getParam('cd_projeto');
		$cd_proposta      = $this->_request->getParam('cd_proposta');
		$cd_parcela       = $this->_request->getParam('cd_parcela');
		$ni_parcela       = $this->_request->getParam('ni_parcela');
		$tx_sigla_projeto = $this->_request->getParam('tx_sigla_projeto');
		
		$rowProcessamentoParcela = $this->processamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'")->toArray();
		$rowProfissional         = $this->profissional->find($rowProcessamentoParcela["cd_prof_autorizacao_parcela"])->current()->toArray();
		$rowSolicitacao          = $this->solicitacao->find($rowProcessamentoParcela["ni_solicitacao_execucao"],$rowProcessamentoParcela["ni_ano_solicitacao_execucao"],$rowProcessamentoParcela["cd_objeto_execucao"])->current()->toArray();
		
		$this->view->dt_autorizacao_parcela            = date('d/m/Y H:i:s', strtotime($rowProcessamentoParcela["dt_autorizacao_parcela"]));
		$this->view->profissional_autorizacao_parcela  = $rowProfissional["tx_profissional"];
		$this->view->ni_parcela                        = $ni_parcela;
		$this->view->tx_sigla_projeto                  = $tx_sigla_projeto;
		$this->view->tx_obs_autorizacao_parcela        = $rowSolicitacao["tx_obs_solicitacao"];
	}	
	
	public function fechamentoParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto       = $this->_request->getParam('cd_projeto');
		$cd_proposta      = $this->_request->getParam('cd_proposta');
		$cd_parcela       = $this->_request->getParam('cd_parcela');
		$ni_parcela       = $this->_request->getParam('ni_parcela');
		$tx_sigla_projeto = $this->_request->getParam('tx_sigla_projeto');
		
		$rowProcessamentoParcela = $this->processamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'")->toArray();
		$rowProfissional         = $this->profissional->find($rowProcessamentoParcela["cd_prof_fechamento_parcela"])->current()->toArray();
		
		$this->view->dt_fechamento_parcela            = date('d/m/Y H:i:s', strtotime($rowProcessamentoParcela["dt_fechamento_parcela"]));
		$this->view->profissional_fechamento_parcela  = $rowProfissional["tx_profissional"];
		$this->view->ni_parcela                       = $ni_parcela;
		$this->view->tx_sigla_projeto                 = $tx_sigla_projeto;
	}	
}