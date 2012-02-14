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

class PlanejamentoController extends Base_Controller_Action 
{
	private $_objProjeto;
	private $_objEtapa;
	private $_objAtividade;
	private $_objPlanejamento;
    private $_objContratoProjeto;
    private $_objProposta;
    private $_objParcela;
    private $_objProduto;
    private $_objProfissionalProduto;
	
	public function init()
	{
		parent::init();
		$this->_objProjeto              = new Projeto($this->_request->getControllerName());
		$this->_objEtapa                = new Etapa($this->_request->getControllerName());
		$this->_objAtividade            = new Atividade($this->_request->getControllerName());
		$this->_objPlanejamento         = new Planejamento($this->_request->getControllerName());
        $this->_objContratoProjeto     = new ContratoProjeto($this->_request->getControllerName());
        $this->_objProposta            = new Proposta($this->_request->getControllerName());
        $this->_objParcela             = new Parcela($this->_request->getControllerName());
        $this->_objProduto             = new ProdutoParcela($this->_request->getControllerName());
        $this->_objProfissionalProduto = new ProfissionalProduto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PLANEJAR_EXECUCAO_ATIVIDADE'));
		$this->view->comboProjeto = $this->_objProjeto->getProjeto(true);
	}
	
	public function gridPlanejamentoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam("cd_projeto");
		$cd_modulo  = $this->_request->getParam("cd_modulo");
		$cd_etapa   = $this->_request->getParam("cd_etapa",0);
		$cd_etapa   = ($cd_etapa != 0)?$cd_etapa:null;
		$cd_objeto  = $_SESSION['oasis_logged'][0]['cd_objeto'];
		
		$arrEtapa     = $this->_objEtapa->getDadosEtapa($cd_objeto,$cd_etapa);
		$arrAtividade = array();
		$arrDados     = array();
		foreach($arrEtapa as $key=>$value){
			$arrAtividade[$value['cd_etapa']] = $this->_objAtividade->getDadosAtividade($value['cd_etapa']);
			if(count($arrAtividade[$value['cd_etapa']]) > 0 ){
				foreach($arrAtividade[$value['cd_etapa']] as $chave=>$valor){
					$rowDados = $this->_objPlanejamento->getPlanejamento($cd_projeto, $cd_modulo, $value['cd_etapa'], $valor['cd_atividade']);
					if(count($rowDados) > 0 ){
						$arrAtividade[$value['cd_etapa']][$chave]['dt_inicio_atividade']     = Base_Util::converterDate($rowDados->dt_inicio_atividade, 'YYYY-MM-DD', 'DD/MM/YYYY');
						$arrAtividade[$value['cd_etapa']][$chave]['dt_fim_atividade']        = Base_Util::converterDate($rowDados->dt_fim_atividade, 'YYYY-MM-DD', 'DD/MM/YYYY');
						$arrAtividade[$value['cd_etapa']][$chave]['nf_porcentagem_execucao'] = $rowDados->nf_porcentagem_execucao;
						$arrAtividade[$value['cd_etapa']][$chave]['tx_obs_atividade']        = $rowDados->tx_obs_atividade;
					} else {
						$arrAtividade[$value['cd_etapa']][$chave]['dt_inicio_atividade']     = "";
						$arrAtividade[$value['cd_etapa']][$chave]['dt_fim_atividade']        = "";
						$arrAtividade[$value['cd_etapa']][$chave]['nf_porcentagem_execucao'] = "";
						$arrAtividade[$value['cd_etapa']][$chave]['tx_obs_atividade']        = "";
					}  
				}
			}
		}
		$this->view->arrEtapa     = $arrEtapa;
		$this->view->arrAtividade = $arrAtividade;
	}

	public function salvaDadosPlanejamentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
		
		$cd_projeto = $arrPost['cd_projeto_planejamento'];
		$cd_modulo  = $arrPost['cd_modulo_planejamento'];
		$cd_etapa   = $arrPost['cd_etapa_planejamento'];
		unset($arrPost['cd_projeto_planejamento']);
		unset($arrPost['cd_modulo_planejamento']);
		unset($arrPost['cd_etapa_planejamento']);

		$i        = 0;
		$arrDados = array();
		foreach($arrPost as $key=>$value){
			$arrCampo = explode('_',$key);
			
			$dtInicio       = "dtInicio_{$arrCampo[1]}_{$arrCampo[2]}";
			$dtTermino      = "dtTermino_{$arrCampo[1]}_{$arrCampo[2]}";
			$porcentagem    = "porcentagem_{$arrCampo[1]}_{$arrCampo[2]}";
			$obsAtividade   = "obsAtividade_{$arrCampo[1]}_{$arrCampo[2]}";
			
			if(empty($arrPost[$porcentagem]) && empty($arrPost[$dtInicio]) && empty($arrPost[$dtTermino]) && empty($arrPost[$obsAtividade])){
				continue;
			} else {
				if(!empty($arrPost[$dtInicio])){
					$arrDados[$i]['dt_inicio_atividade'] = $arrPost[$dtInicio];
					unset($arrPost[$dtInicio]);
				} else {
					$arrDados[$i]['dt_inicio_atividade'] = null;
				}
				if(!empty($arrPost[$dtTermino])){
					$arrDados[$i]['dt_fim_atividade'] = $arrPost[$dtTermino];
					unset($arrPost[$dtTermino]);
				} else {
					$arrDados[$i]['dt_fim_atividade'] = null;
				}
				if(!empty($arrPost[$porcentagem])){
					$arrDados[$i]['nf_porcentagem_execucao'] = $arrPost[$porcentagem];
					unset($arrPost[$porcentagem]);
				} else {
					$arrDados[$i]['nf_porcentagem_execucao'] = null;
				}
				if(!empty($arrPost[$obsAtividade])){
					$arrDados[$i]['tx_obs_atividade'] = $arrPost[$obsAtividade];
					unset($arrPost[$obsAtividade]);
				} else {
					$arrDados[$i]['tx_obs_atividade'] = null;
				}
				
				$arrDados[$i]['cd_etapa']                = $arrCampo[1];
				$arrDados[$i]['cd_atividade']            = $arrCampo[2];
				$arrDados[$i]['cd_projeto']              = $cd_projeto;
				$arrDados[$i]['cd_modulo']               = $cd_modulo;
			}
			$i++;
		}
		
		if( count($arrDados) > 0 ){
			$return = $this->_objPlanejamento->trataDadosGravacao($arrDados);
			$msg    = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			$msg = Base_Util::getTranslator('L_MSG_ALERT_VARIFICA_INFORMAÇOES');
		}
		echo $msg;
	}

	public function graficoGanttChartAction()
	{
		$this->_helper->layout->disableLayout();
	}

    public function popUpGraficoGanttAction()
    {
        $this->_helper->layout->disableLayout();

		$arrGraficoGantt = array();
		$cd_projeto      = $this->_request->getParam('cd_projeto');
		$gantt_filtro    = $this->_request->getParam('gantt_filtro');
		$cd_modulo       = $this->_request->getParam('cd_modulo',0);
		$cd_modulo       = ($cd_modulo == "todos")?null:$cd_modulo;
		$gantt_tipo_rel  = $this->_request->getParam('gantt_tipo_rel');

		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$rowSetDados  = $this->_objPlanejamento->getDadosGraficoGantt($cd_projeto,null,$cd_objeto,$gantt_tipo_rel);
		$cd_modulo = 0;
		foreach($rowSetDados as $key=>$value) {
			if($cd_modulo != $value->cd_modulo) {
				$rowSetDadosModulo = $this->_objPlanejamento->getDadosModuloGraficoGantt($cd_projeto,$value->cd_modulo,$cd_objeto);
				if(count($rowSetDadosModulo) > 0) {
					foreach($rowSetDadosModulo as $chave=>$valor) {
						$arrGraficoGantt[$value->cd_modulo]['dt_inicio'  ] = Base_Util::converterDate($valor->inicio, 'YYYY-MM-DD', 'DD/MM/YYYY');
						$arrGraficoGantt[$value->cd_modulo]['dt_fim'     ] = Base_Util::converterDate($valor->fim, 'YYYY-MM-DD', 'DD/MM/YYYY');
						$arrGraficoGantt[$value->cd_modulo]['porcentagem'] = $valor->porcentagem;
						$arrGraficoGantt[$value->cd_modulo]['tx_modulo'  ] = ucwords($this->toLower($valor->tx_modulo));
						if($gantt_filtro != "M"){
							$rowSetDadosEtapa = $this->_objPlanejamento->getDadosEtapaGraficoGantt($cd_projeto, $value->cd_modulo,$cd_objeto);
							if(count($rowSetDadosEtapa) > 0) {
								foreach($rowSetDadosEtapa as $chaveEtapa=>$valorEtapas) {
									$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['dt_inicio_etapa'  ] = Base_Util::converterDate($valorEtapas->inicio, 'YYYY-MM-DD', 'DD/MM/YYYY');
									$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['dt_fim_etapa'     ] = Base_Util::converterDate($valorEtapas->fim, 'YYYY-MM-DD', 'DD/MM/YYYY');
									$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['porcentagem_etapa'] = $valorEtapas->porcentagem;
									$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['tx_etapa'         ] = ucwords($this->toLower($valorEtapas->tx_etapa));
									if($gantt_filtro != "E"){
										$rowSetDadosAtividade = $this->_objPlanejamento->getDadosAtividadeGraficoGantt($cd_projeto, $value->cd_modulo, $valorEtapas->cd_etapa);
										if(count($rowSetDadosAtividade) > 0) {
											foreach($rowSetDadosAtividade as $chaveAtividade=>$valorAtividade){
												$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['dados_atividade'][$valorAtividade->cd_atividade]['dt_inicio_atividade'    ] = Base_Util::converterDate($valorAtividade->dt_inicio_atividade, 'YYYY-MM-DD', 'DD/MM/YYYY');
												$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['dados_atividade'][$valorAtividade->cd_atividade]['dt_fim_atividade'       ] = Base_Util::converterDate($valorAtividade->dt_fim_atividade, 'YYYY-MM-DD', 'DD/MM/YYYY');
												$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['dados_atividade'][$valorAtividade->cd_atividade]['nf_porcentagem_execucao'] = $valorAtividade->nf_porcentagem_execucao;
												$arrGraficoGantt[$value->cd_modulo]['dados_etapa'][$valorEtapas->cd_etapa]['dados_atividade'][$valorAtividade->cd_atividade]['tx_atividade'           ] = ucwords($this->toLower($valorAtividade->tx_atividade));
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$cd_modulo = $value->cd_modulo;
		}
		$arrProjeto = $this->_objProjeto->find($cd_projeto)->current()->toArray();
		$this->view->tx_sigla_projeto = $arrProjeto['tx_sigla_projeto'];
		$this->view->gantt_filtro = $gantt_filtro;
		$this->view->arrDados     = $arrGraficoGantt;
    }


    //Métodos utilizado na aba de Profissional X Produto
    /**
     * Action para montar um combo com os projetos que
     * estão associados ao contrato.
     *
     * @param <request> cd_contrato
     * @return <String> $options
     */
    public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true);

		$options = '';

		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

    /**
     * Action para montar um combo com as propostas do projeto
     * passado como parametro.
     *
     * @param <request> cd_projeto
     * @return <String> $options
     */
    public function pesquisaPropostaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_projeto = (int) $this->_request->getParam("cd_projeto", 0);
		$arrProposta = $this->_objProposta->getProposta($cd_projeto, true);

		$options = '';

		foreach( $arrProposta as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

    /**
     * Action para montar um combo com as propostas do projeto
     * passado como parametro.
     *
     * @param <request> cd_projeto
     * @param <request> cd_proposta
     * @return <String> $options
     */
    public function pesquisaParcelaPropostaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_projeto = (int) $this->_request->getParam("cd_projeto", 0);
		$cd_proposta = (int) $this->_request->getParam("cd_proposta", 0);

		$arrParcelas = $this->_objParcela->getComboParcelaProposta($cd_projeto, $cd_proposta, true);

		$options = '';

		foreach( $arrParcelas as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

    /**
     * Action para montar um combo com os profissionais alocados
     * ao projeto durante a execução das propostas
     * passado como parametro.
     *
     * @param <request> cd_contrato
     * @param <request> cd_projeto
     * @return <String> $options
     */
    /*public function pesquisaProfissionalProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        $objProfissionalProjeto = new ProfissionalProjeto();

		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$cd_projeto = (int) $this->_request->getParam("cd_projeto", 0);

		$arrProfissional = $objProfissionalProjeto->getProfissionalAlocadoProjetoDuranteAExecucao($cd_contrato,$cd_projeto, true);

		$options = '';

		foreach( $arrProfissional as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}*/

	public function pesquisaProfissionalProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrParams = $this->_request->getPost();

        $produtosSemProfissional = $this->_objProfissionalProduto->pesquisaProfissionalSemProduto($arrParams);
        $produtosComProfissional = $this->_objProfissionalProduto->pesquisaProfissionalComProduto($arrParams);

        $arr1 = "";

        foreach ($produtosSemProfissional as $sem) {
            $arr1 .= "<option title=\"{$sem->tx_profissional}\" value=\"{$sem->cd_profissional}\">{$sem->tx_profissional}</option>";
        }

        $arr2 = "";
        foreach ($produtosComProfissional as $com) {
            $arr2 .= "<option title=\"{$com->tx_profissional}\" value=\"{$com->cd_profissional}\">{$com->tx_profissional}</option>";
        }

        $retornaOsDois = array($arr1, $arr2);

        echo Zend_Json_Encoder::encode($retornaOsDois);
	}

	public function pesquisaProdutoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_projeto  = (int) $this->_request->getParam("cd_projeto", 0);
		$cd_proposta = (int) $this->_request->getParam("cd_proposta", 0);
		$cd_parcela  = (int) $this->_request->getParam("cd_parcela", 0);

		$arrProdutos = $this->_objProduto->getComboProdutoParcela($cd_projeto, $cd_proposta, $cd_parcela, true);

		$options = '';

		foreach( $arrProdutos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

	public function associaProfissionalProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_projeto      = $post['cd_projeto'];
		$cd_proposta     = $post['cd_proposta'];
		$cd_parcela      = $post['cd_parcela'];
		$cd_produto      = $post['cd_produto'];
		$profissionais   = Zend_Json_Decoder::decode($post['profissionais']);

		foreach ($profissionais as $profissional) {
            $this->_objProfissionalProduto->addProfissionalProduto($cd_projeto, $cd_proposta, $cd_parcela, $cd_produto, $profissional );
		}
	}

    public function desassociaProfissionalProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_projeto      = $post['cd_projeto'];
		$cd_proposta     = $post['cd_proposta'];
		$cd_parcela      = $post['cd_parcela'];
		$cd_produto      = $post['cd_produto'];
		$profissionais   = Zend_Json_Decoder::decode($post['profissionais']);

		foreach ($profissionais as $profissional) {
            $this->_objProfissionalProduto->removeProfissionalProduto($cd_projeto, $cd_proposta, $cd_parcela, $cd_produto, $profissional );
		}
	}
}