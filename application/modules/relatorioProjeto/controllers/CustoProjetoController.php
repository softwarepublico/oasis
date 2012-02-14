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

class RelatorioProjeto_CustoProjetoController extends Base_Controller_Action
{
	private $objRelCustoProjeto;
	private $objContrato;
	private $objContratoProjeto;
	private $objContratoDefinicaoMetrica;
	private $objProjeto;
	private $siglaMetricaPadraoPorContrato;
	
    public function init()
    {
        parent::init();
        $this->objRelCustoProjeto          = new RelatorioProjetoCustoProjeto();
        $this->objContrato 		           = new Contrato($this->_request->getControllerName());
        $this->objContratoProjeto          = new ContratoProjeto($this->_request->getControllerName());
        $this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
        $this->objProjeto                  = new Projeto($this->_request->getControllerName());
    }

    /**
     * Action da tela de inicial
     */
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CUSTO_PROJETO'));

    	$cd_contrato = null;
		$comStatus   = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus   = false;
		}
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    	
    	$objUnidade 	 		  = new Unidade($this->_request->getControllerName());
    	$arrComboUnidade 		  = $objUnidade->getUnidade(true);
    	$this->view->comboUnidade = $arrComboUnidade;
    }
    
	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $options;
	}
    
    /**
     * Action de geração do relatório de Acompanhamento Proposta
     */
    public function relatorioProjetoAction()
    {
		$this->_helper->layout->disableLayout();

		$cd_contrato	 = $this->_request->getParam('cd_contrato');
		$cd_projeto      = $this->_request->getParam('cd_projeto');

		$arrDadosMetricaPadrao				 = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);
		$this->siglaMetricaPadraoPorContrato = $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
		$arrProjeto							 = $this->objRelCustoProjeto->getProjeto($cd_projeto);
		
		$arrContratosProjeto                 = $this->objContratoProjeto->getContratosExecucaoProjeto($cd_projeto);
		$arrCustoProjeto = array();
		foreach ($arrContratosProjeto as $contrato) {
			$arrCustoProjetoAux              = $this->objRelCustoProjeto->custoProjeto($contrato['cd_contrato'],$cd_projeto);
			$arrCustoProjeto                 = array_merge($arrCustoProjeto, $arrCustoProjetoAux);
		}
		
		$this->view->siglaMetricaPadraoPorContrato	= $this->siglaMetricaPadraoPorContrato;
		$this->view->arrCustoProjeto				= $arrCustoProjeto;
		$this->view->arrProjeto						= $arrProjeto;
    }
    
    public function relatorioTodosProjetosAction()
    {
    	$this->_helper->layout->disableLayout();
    	
		$cd_contrato = $this->_request->getParam('cd_contrato', 0);

		$arrDadosMetricaPadrao				 = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);
		$this->siglaMetricaPadraoPorContrato = $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];

		$this->view->siglaMetricaPadraoPorContrato	= $this->siglaMetricaPadraoPorContrato;

		//busca os projetos associados ao contrato
		$arrProjeto                 = $this->objContratoProjeto->listaProjetosContrato($cd_contrato);

		foreach ($arrProjeto as $cd_projeto => $tx_sigla_projeto) {
			$arrContratosProjeto    = $this->objContratoProjeto->getContratosExecucaoProjeto($cd_projeto);

			//Busca a Unidade do Projeto
			$arrDadosProjeto = $this->objProjeto->getDescricaoProjeto($cd_projeto);
			//Busca a Periodo de Execução do Projeto
			$arrPeriodo = $this->objProjeto->getPeriodoExecucaoProjeto($cd_projeto);

			//Calcula o custo do projeto por contrato que o executou
			$arrCustoProjeto        = array();
			foreach ($arrContratosProjeto as $contrato) {
				$arrCustoProjetoAux              = $this->objRelCustoProjeto->custoProjeto($contrato['cd_contrato'],$cd_projeto);
				$arrCustoProjeto                 = array_merge($arrCustoProjeto, $arrCustoProjetoAux);
			}
			//Calcula o custo total do projeto e junta ao array de todos os projetos
			$ni_total_horas_parcela = 0;
			$valor_total            = 0;
			foreach ($arrCustoProjeto as $custoProjeto) {
				$ni_total_horas_parcela = $ni_total_horas_parcela + $custoProjeto['ni_horas_parcela'];
				$valor_total            = $valor_total + $custoProjeto['valor_parcela'];
			}
			$arrCustoFinal[$cd_projeto] = array('tx_sigla_projeto'       => $tx_sigla_projeto,
												'cd_projeto'             => $cd_projeto,
												'ni_total_horas_parcela' => $ni_total_horas_parcela,
												'valor_total'            => $valor_total,
												'dt_inicio'              => $arrPeriodo[0]['dt_inicio'],
												'dt_fim'                 => $arrPeriodo[0]['dt_fim'],
												'tx_sigla_unidade'       => $arrDadosProjeto['tx_sigla_unidade']);
		}
    	$this->view->arrCustoProjeto				= $arrCustoFinal;
    }
    
    public function relatorioProjetoUnidadeAction()
    {
    	$this->_helper->layout->disableLayout();

		$cd_contrato     = $this->_request->getParam('cd_contrato', 0);
    	$cd_unidade      = $this->_request->getParam('cd_unidade');
		$arrCustoUnidade = $this->objRelCustoProjeto->custoUnidade($cd_unidade,$cd_contrato);
		
		$this->view->arrCustoUnidade = $arrCustoUnidade;		
    }
}