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

class ElaboracaoPropostaController extends Base_Controller_Action
{
	private $objProjeto;
	private $objProposta;
	private $objTipoProduto;
	private $objTipoDocumentacao;
	private $objRequisito;
	private $objDefinicaoMetrica;
	private $objItemMetrica;
	private $objSubItemMetrica;
	private $objCondicaoSubItemMetrica;
	private $objPropostaSubItemMetrica;
	private $objPropostaDefinicaoMetrica;
	private $objParcela;
	private $objProdutoParcela;
	private $objPapelProfissional;
	private $objProfissional;
	private $objProfissionalObjetoContrato;
	private $objContratoDefinicaoMetrica;
	private $objContrato;
	private $arrDadosMetricaPadrao;
	private $_objObjetoContrato;
	private $st_parcela_orcamento;
	private $ni_porcentagem_parc_orcamento;
	
	public function init()
	{
		parent::init();

		$this->objProjeto                  = new Projeto($this->_request->getControllerName());
		$this->objProposta                 = new Proposta($this->_request->getControllerName());
		$this->objTipoProduto              = new TipoProduto($this->_request->getControllerName());
		$this->objTipoDocumentacao         = new TipoDocumentacao($this->_request->getControllerName());
		$this->objRequisito		           = new Requisito($this->_request->getControllerName());
		$this->objDefinicaoMetrica         = new DefinicaoMetrica($this->_request->getControllerName());
		$this->objItemMetrica              = new ItemMetrica($this->_request->getControllerName());
		$this->objSubItemMetrica           = new SubItemMetrica($this->_request->getControllerName());
		$this->objCondicaoSubItemMetrica   = new CondicaoSubItemMetrica($this->_request->getControllerName());
		$this->objPropostaSubItemMetrica   = new PropostaSubItemMetrica($this->_request->getControllerName());
		$this->objPropostaDefinicaoMetrica = new PropostaDefinicaoMetrica($this->_request->getControllerName());
		$this->objParcela				   = new Parcela($this->_request->getControllerName());
		$this->objProdutoParcela		   = new ProdutoParcela($this->_request->getControllerName());
		$this->objPapelProfissional		   = new PapelProfissional($this->_request->getControllerName());
		$this->objProfissional             = new Profissional($this->_request->getControllerName());
		$this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->objContrato				   = new Contrato($this->_request->getControllerName());
		$this->_objObjetoContrato          = new ObjetoContrato($this->_request->getControllerName());

		//Verifica se o objeto do contrato que solicitou a proposta tem indicação de
		//parcela de orçamento para pagamento da elaboração da proposta e quanto é a porcentagem
		//dessa indicação
		//Vale para os acordeons Métrica e Criação de Parcelas
		$arrParcelaOrcamento                       = $this->_objObjetoContrato->verificaFlagParcelaOrcamento($_SESSION['oasis_logged'][0]['cd_objeto']);
		$this->st_parcela_orcamento                = $arrParcelaOrcamento['st_parcela_orcamento'];
		$this->ni_porcentagem_parc_orcamento       = $arrParcelaOrcamento['ni_porcentagem_parc_orcamento'];
		$this->view->st_parcela_orcamento          = $this->st_parcela_orcamento;
		$this->view->ni_porcentagem_parc_orcamento = $this->ni_porcentagem_parc_orcamento;
	}

	public function indexAction()
	{
		$this->view->headTitle(Base_Util::setTitle('L_TIT_ELABORACAO_PROPOSTA'));

		//Recebendo os paramêtros da tela
		$cd_projeto    = (int)$this->_request->getPost('cd_projeto', 1);
		$cd_proposta   = (int)$this->_request->getPost('cd_proposta', 1);
		$openAccordion =      $this->_request->getPost('accordion');

		//recupera os dados do contrato ativo ao qual pertence o projeto em questao
		$arrDadosContrato				= $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
		$this->arrDadosMetricaPadrao	= $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($arrDadosContrato['cd_contrato']);

		//Dados enviados para a tela
		$this->view->cd_projeto  = $cd_projeto;
		$this->view->cd_proposta = $cd_proposta;

		$this->view->openAccordion = $openAccordion;

		//Recupera o código do projeto para verificar o nome.
		$arrProjeto = $this->objProjeto->getProjeto(false,$cd_projeto);
		$this->view->nomeProjeto = $arrProjeto[$cd_projeto];

		//Consulta proposta
		$selectProposta = $this->objProposta->select()
                                            ->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
                                            ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE);
		$resProposta = $this->objProposta->fetchRow($selectProposta);
		$this->view->resProposta = $resProposta;
		
		//Consulta Solicitacao
		$solicitacao 	= new Solicitacao($this->_request->getControllerName());
		$resSolicitacao = $solicitacao->getSolicitacao($resProposta->cd_objeto, $resProposta->ni_solicitacao, $resProposta->ni_ano_solicitacao);
		//Eviando informações dos dados
		$this->view->solicitacao = $resSolicitacao;
		
		/**
		 * Accordion Objetivo da Proposta
		 */
		if(ChecaPermissao::possuiPermissao('situacao-projeto') === true && $cd_proposta > 1){
			$this->view->tx_objetivo_proposta = $resProposta->tx_objetivo_proposta;
		}

		/*
		 * Accordeon Descrição do Projeto
		 */
		$this->view->formDescricaoProjeto  = new ProjetoForm();
		if ($cd_projeto > 0) {
			$rowDataProjeto   = $this->objProjeto->fetchRow(array("cd_projeto = ?"=>$cd_projeto));
			if (!is_null($rowDataProjeto)) {
				$rowDataProjeto->tx_contexto_geral_projeto = strip_tags($rowDataProjeto->tx_contexto_geral_projeto);
                $rowDataProjeto->tx_escopo_projeto         = strip_tags($rowDataProjeto->tx_escopo_projeto);
                $this->view->formDescricaoProjeto->populate($rowDataProjeto->toArray());
			}
		}

		/**
		 * Accordion Alocação de profissionais
		 */
		if(ChecaPermissao::possuiPermissao('associar-profissional-projeto') === true){
			$this->view->arrPapelProfissional = $this->objPapelProfissional->getPapelProfissional($_SESSION['oasis_logged'][0]['cd_objeto'], true);
		}

		/**
		 * Accordion Metrica Dinâmica
		 */
		$this->view->arrMetrica			  = $this->objDefinicaoMetrica->getComboDefinicaoMetrica( $arrDadosContrato['cd_contrato'], true );
		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : null ;


		/**
		 * Accordeon Gerênciar Módulos
		 */
		if(ChecaPermissao::possuiPermissao('modulo') === true){
			$this->view->arrTipoProduto = $this->objTipoProduto->getTipoProduto($_SESSION['oasis_logged'][0]['cd_contrato'], true);
		}
		/**
		 * Accordeon Criar Parcela
		 */
		//Captura a proxima parcela do sistema
		//Recupera todas as proposta do projeto
		if(ChecaPermissao::possuiPermissao('criacao-parcelas') === true){
			$this->valorHoraPropostaAction($cd_projeto,$cd_proposta);
			$objParcela = new Parcela($this->_request->getControllerName());
			$proximaParcela = $objParcela->getProximaParcela($cd_projeto);
			$this->view->proximaParcela = $proximaParcela;
		}
				
		/**
		 * Accordeon Arquivo Proposta
		 */
		if(ChecaPermissao::possuiPermissao('documentacao-projeto') === true){
			$this->view->tipoDocumentacaoCombo = $this->objTipoDocumentacao->getTipoDocumentacao("P",true);
		}

		/**
		 * Accordeon Arquivo Dependeincia Requisito
		 */
		if(ChecaPermissao::possuiPermissao('requisito-proposta') === true){
			$this->view->arrRequisitos = $this->objRequisito->getComboRequisito( $cd_projeto, true );
		}
	}
	
	/**
	 * Método desenvolvido para verificar a quantidade de horas total do projeto e
	 * calcular 2% do projeto
	 * Este método pode ser utilizado via ajax ou por chamada PHP
	 *
	 * @param unknown_type $cd_projeto_int Códido do projeto 
	 * @param unknown_type $cd_proposta_int Código da proposta
	 */
	public function valorHoraPropostaAction($cd_projeto_int = null, $cd_proposta_int = null)
	{
		if(is_null($cd_projeto_int) && is_null($cd_proposta_int)){
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			$cd_projeto  = (int)$this->_request->getParam('cd_projeto');
			$cd_proposta = (int)$this->_request->getParam('cd_proposta');
		} else {
			$cd_projeto  = $cd_projeto_int;
			$cd_proposta = $cd_proposta_int;
			
		}
		if ($cd_proposta == 1 && $this->st_parcela_orcamento == 'S'){
			$percentualHorasProposta = $this->objProposta->getPorcentagemHorasProposta($cd_projeto, $this->ni_porcentagem_parc_orcamento);
			$percentualHorasProposta = round($percentualHorasProposta,1);
		}
		else {
			$percentualHorasProposta = 0;
		}
		
		$quantidadeHorasTotal = $this->objProposta->getHorasProjetoProposta($cd_projeto,$cd_proposta);
		if(is_null($cd_projeto_int) && is_null($cd_proposta_int)){
			echo Zend_Json::encode(array($quantidadeHorasTotal,$percentualHorasProposta));
		} else {
			$this->view->porcentagemHorasProposta = $percentualHorasProposta;
			// Recupera horas totalw do projeto
			$this->view->quantidadeHorasTotal = $quantidadeHorasTotal;
		}
	}

	public function confirmaDocumentacaoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$erros = false;
		
		$cd_projeto  = (int)$this->_request->getParam('cd_projeto');
		$cd_proposta = (int)$this->_request->getParam('cd_proposta');
		
		$upRow = array();
		$upRow['st_documentacao'] = 1;
		
		$erros = $this->objProposta->atualizaProposta($cd_projeto, $cd_proposta, $upRow);
		
		if ($erros === false){
			echo Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_DOCUMENTACAO');
		}else{
			echo Base_Util::getTranslator('L_MSG_ERRO_CONFIRMAR_DOCUMENTACAO');
		}
	}

	public function confirmaElaboracaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');
		$campo       = $this->_request->getParam('campo');
		
		$upRow = array();
		$arrMsg = array();
		$upRow[$campo] = 1;

		$arrMsg['st_metrica'	 ] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_CALCULO_METRICA');
		$arrMsg['st_produto'	 ] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_PRODUTOS');
		$arrMsg['st_profissional'] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_PROFISSIONAIS');
		$arrMsg['st_modulo'		 ] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_MODULOS');
		$arrMsg['st_parcela'	 ] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_PARCELAS');
		$arrMsg['st_documentacao'] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_DOCUMENTACAO');
		$arrMsg['st_requisito'	 ] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_REQUISITOS');
		$arrMsg[99]                = Base_Util::getTranslator('L_MSG_ERRO_CONFIRMAR_DADOS');

		$erros = $this->objProposta->atualizaProposta($cd_projeto, $cd_proposta, $upRow);
		$msg = ($erros)?$arrMsg[99]:$arrMsg[$campo];
		
		echo $msg;
	}
	
	public function validaAccordionAction()
	{
	    $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');
		
		$arrDados = $this->objProposta->getDadosProjetoProposta($cd_projeto,$cd_proposta);
		echo Zend_Json::encode($arrDados);
	}

    /**
     * Método para recuperar o total de horas da proposta de um projeto
     */
    public function getTotalHorasProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_projeto  = $this->_request->getParam('cd_projeto');
        $cd_proposta = $this->_request->getParam('cd_proposta');

        $arrProposta = $this->objProposta->find($cd_proposta, $cd_projeto)->current()->toArray();

		$arrDadosContrato		= $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
		$arrDadosMetricaPadrao	= $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($arrDadosContrato['cd_contrato']);

        echo $arrProposta['ni_horas_proposta']." ".$arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
    }
    
    /*MÉTODOS UTILIZADO PARA A VALIDADAÇÃO DAS CONFIRMAÇÕES DOS ACCORDIONS*/
	/**
	 * Método para verificar se foi criado algum módulo para o projeto
	 * e se um desses módulos foi associado à parcela
	 */
    public function validaConfirmacaoGerenciaModuloAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>'');
		
		$modulo = new Modulo($this->_request->getControllerName());
		
		$post   = $this->_request->getPost();
		
		$cd_projeto  = $post['cd_projeto'];
		$cd_proposta = $post['cd_proposta'];
		
		// realiza a consulta
		$rowSetModulo = $modulo->fetchAll($modulo->select()->where("cd_projeto = ?",$cd_projeto, Zend_Db::INT_TYPE));
        
		if( $rowSetModulo->valid() ){
			$arrModuloVinculado = $modulo->pesquisarModuloVinculado($cd_projeto,$cd_proposta);
			if( count($arrModuloVinculado) == 0 ){
				$arrResult['error'    ] = true;
	            $arrResult['errorType'] = 2;
	            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_NENHUM_MODULO_VINCULADO_PROPOSTA');
			}
		}else{
			$arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_NENHUM_MODULO_CRIADO_PROJETO');
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }
    
	/**
	 * Método para verificar se foram atendidas as exigências para a confirmação
	 * do accordion de criação de parcelas
	 */
    public function validaConfirmacaoCriarParcelaAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post    	 = $this->_request->getPost();
        
		$arrResult   = array('error'=>'', 'errorType'=>'', 'msg'=>'');
		$cd_projeto  = $post['cd_projeto'];
		$cd_proposta = $post['cd_proposta'];
		
		$arrParcelaProposta = $this->objParcela->getParcelaProposta( $cd_projeto, $cd_proposta, true );
		
		if($cd_proposta == 1 && count($arrParcelaProposta) < 2 && $this->st_parcela_orcamento == 'S'){
				$arrResult['error'    ] = true;
	            $arrResult['errorType'] = 2;
	            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_PRIMEIRA_PROPOSTA_MINIMO_DUAS_PARCELAS');
		}else{
			if( count($arrParcelaProposta) == 0 ){
				$arrResult['error'    ] = true;
	            $arrResult['errorType'] = 2;
	            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_PROPOSTA_MINIMO_UMA_PARCELA');
			}else{
				//recupera o total das parcelas com st_modulo_proposta nulo
				$totalHorasParcelas = $this->objParcela->getSomaHorasParcelasDeExecucaoDaProposta($cd_projeto, $cd_proposta);
				//recupera os dados da proposta
				$arrProposta = $this->objProposta->getDadosProjetoProposta($cd_projeto, $cd_proposta);
				if( $arrProposta[0]['ni_horas_proposta'] != $totalHorasParcelas ){
					$arrResult['error'    ] = true;
		            $arrResult['errorType'] = 2;
		            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_SOMA_VALORES_PARCEALAS_DIFERENTE_TOTAL_PROPOSTA');
                }
			}
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }
    
	/**
	 * Método para verificar se foram atendidas as exigências para a confirmação
	 * do accordion de Acrescentar Produtos
	 */
    public function validaConfirmacaoAcrescimoProdutoAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post    	 = $this->_request->getPost();

		$arrResult   = array('error'=>'', 'errorType'=>'', 'msg'=>'');
		$cd_projeto  = $post['cd_projeto'];
		$cd_proposta = $post['cd_proposta'];
		
		$arrProdutos = $this->objProdutoParcela->getParcelaSemProdutos($cd_projeto, $cd_proposta);
    	if( count($arrProdutos) > 0 ){
			$arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_EXISTE_PARCELA_SEM_PRODUTO_CADASTRADO');
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }
    
	/**
	 * Método para verificar se foram atendidas as exigências para a confirmação
	 * do accordion de Acrescentar Produtos
	 */
    public function validaConfirmacaoRequisitoAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrResult   = array('error'=>'', 'errorType'=>'', 'msg'=>'');
//		$post    	 = $this->_request->getPost();
//
//		$cd_projeto  = $post['cd_projeto'];
//
//		$arrRequisito = $this->objRequisito->getRequisito($cd_projeto);
//
//    	if( count($arrRequisito) == 0 ){
//			$arrResult['error'    ] = true;
//            $arrResult['errorType'] = 2;
//            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ALERT_PROJETO_SEM_REQUISITO_CADASTRADO');
//		}

		echo Zend_Json_Encoder::encode($arrResult);
    }

    /* MÉTODOS UTILIZADOS NO CÁLCULO DA MÉTRICA DINÂMICA */
    /**
     * Método utilizado para recuperar as informações necessaria para montagem da tela de
     * insersão de dados para o cálculo da métrica
     */
    public function camposMetricaAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_definicao_metrica = (int)$this->_request->getParam('cd_definicao_metrica',0);
        $cd_projeto           = (int)$this->_request->getParam('cd_projeto',0);
        $cd_proposta          = (int)$this->_request->getParam('cd_proposta',0);

        //recupera as informações da Definição da Métrica
        $arrDefinicaoMetrica = $this->objDefinicaoMetrica->find($cd_definicao_metrica)->current()->toArray();
        //recupera a formula da metrica
        $formulaMetrica = $arrDefinicaoMetrica['tx_formula_metrica'];

        //recupera as informação da justificativa da métrica
        $arrPropostaDefinicaoMetrica = $this->objPropostaDefinicaoMetrica->find($cd_projeto, $cd_proposta, $cd_definicao_metrica)->current();

        $tx_justificativa_metrica = '';
        if(count($arrPropostaDefinicaoMetrica) > 0){
            $tx_justificativa_metrica = $arrPropostaDefinicaoMetrica->tx_justificativa_metrica;
        }

        //recupera os itens da formula da metrica
        $arrItensMetrica = $this->objItemMetrica->getDadosItemMetrica(null, $cd_definicao_metrica);
        
        $arrRetorno = array();
        //percore todos os item cadastratos para a metrica
        foreach( $arrItensMetrica as $item ){
            //procura se a variavel do item existe na formula da métrica
            if( strstr($formulaMetrica, $item['tx_variavel_item_metrica']) ){
                //recupera os subitens não internos do item encontrado na formula
                $arrSubItens = $this->objSubItemMetrica->getSubItemMetrica($cd_definicao_metrica, $item['cd_item_metrica'], false);

                //percore todos os sub-itens verificando os que não são do tipo interno
                foreach( $arrSubItens as $subItem ){
                    $subItemItem['tx_sub_item_metrica'         ] = $subItem['tx_sub_item_metrica'];
                    //concatena a variavel, cd_sub_item e cd_item "VAR|2|4"
                    $subItemItem['tx_variavel_sub_item_metrica'] = $subItem['tx_variavel_sub_item_metrica']."|".$subItem['cd_sub_item_metrica']."|".$item['cd_item_metrica'];

                    $valorSubItem = $this->getValorCadastradoSubItem($cd_projeto, $cd_proposta, $cd_definicao_metrica, $item['cd_item_metrica'], $subItem['cd_sub_item_metrica']);

                    //verifica se existe valor cadastrado caso contrário adiciona valor em branco
                    if($valorSubItem > 0){
                        //separa o numero em duas partes (inteira e fracionaria)
                        $arrValor = explode('.', $valorSubItem);

                        //caso a parte fracionaria é igual a zero adiciona apenas a parte inteira uma visualização mais amigável na tela
                        //caso a parte fracionaria seja diferente de zero adiciona o valor que veio da consulta ao banco
                        if($arrValor[1] == 0){
                            $subItemItem['valor'] = $arrValor[0];
                        }else{
                            $subItemItem['valor'] = $valorSubItem;
                        }
                    }else{
                        $subItemItem['valor'] = '';
                    }

                    //monta o array de retorno com os sub-itens não internos
                    $arrRetorno[$item['tx_variavel_item_metrica']][] = $subItemItem;
                }
            }
        }

		$arrDadosDefinicaoMetrica = $this->objDefinicaoMetrica->find($cd_definicao_metrica)->current();

		$arrDadosContrato		= $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
		$arrDadosMetricaPadrao	= $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($arrDadosContrato['cd_contrato']);


        $this->view->res						= $arrRetorno;
        $this->view->st_justificativa			= $arrDefinicaoMetrica['st_justificativa_metrica'];
        $this->view->tx_justificativa			= $tx_justificativa_metrica;
        $this->view->unidadePadraoMetrica		= $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
        $this->view->tx_sigla_unidade_metrica	= $arrDadosDefinicaoMetrica->tx_sigla_unidade_metrica;
    }

    private function getValorCadastradoSubItem($cd_projeto, $cd_proposta, $cd_definicao_metrica, $cd_item_metrica, $cd_sub_item_metrica)
    {
        $res = $this->objPropostaSubItemMetrica->find($cd_projeto, $cd_proposta, $cd_item_metrica, $cd_definicao_metrica, $cd_sub_item_metrica)
                                               ->current();
        if( count($res) > 0){
            $valorSubItem = $res->ni_valor_sub_item_metrica;
        }else{
            $valorSubItem = "";
        }
        return $valorSubItem;
    }

    /**
     * Método utilizado para efetuar o cálculo da metrica
     */
    public function calcularValorMetricaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $arrRetorno = array('error'=>'', 'errorType'=>'', 'horas'=>'', 'msg'=>'');
        
        $post				  = $this->_request->getPost();

        $cd_definicao_metrica = $post['cd_definicao_metrica'];

		if( array_key_exists("cd_projeto", $post) ){

			$cd_projeto = $post['cd_projeto'];
			unset($post['cd_projeto']);

			$arrDadosContrato = $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);

			//recupera os dados da metrica associada ao contrato para saber se é a metrica padrao
			//e utilizar seu fator.
			$arrDadosContratoDefinicaoMetrica = $this->objContratoDefinicaoMetrica->find($arrDadosContrato['cd_contrato'], $cd_definicao_metrica)->current();
		}else{
			$arrDadosContratoDefinicaoMetrica = $this->objContratoDefinicaoMetrica->find($_SESSION['oasis_logged'][0]['cd_contrato'], $cd_definicao_metrica)->current();
		}
		
        unset($post['cd_definicao_metrica'		 ]);
        unset($post['valor_total_calculo_metrica']);

        //recupera as informações da Definição da Métrica
        $arrDefinicaoMetrica = $this->objDefinicaoMetrica->find($cd_definicao_metrica)->current()->toArray();
        //recupera a formula da metrica
        $formulaMetrica = $arrDefinicaoMetrica['tx_formula_metrica'];

        $arrItensMetrica = $this->objItemMetrica->getDadosItemMetrica(null, $cd_definicao_metrica);

        $arrFormulaItem = array();

        //percore todos os item cadastratos para a metrica
        foreach( $arrItensMetrica as $item ){

            $formulaItem = str_replace(" ", "", $item['tx_formula_item_metrica']);

            //procura se a variavel do item existe na formula da métrica
            if( strstr($formulaMetrica, $item['tx_variavel_item_metrica']) ){
                //recupera os subitens do item encontrado na formula
                $arrSubItens = $this->objSubItemMetrica->getDadosSubItemMetrica($cd_definicao_metrica, $item['cd_item_metrica']);

                //percore todos os sub-itens verificando os que não são do tipo interno
                foreach( $arrSubItens as $subItem ){

                    $arrCondicao = $this->getCondicaoSubItem($cd_definicao_metrica, $item['cd_item_metrica'], $subItem['cd_sub_item_metrica']);

                    //monta a formula de cada item da métrica
                    if($arrCondicao){
                        $valorCondicao = $this->verificaCondicaoPost($post, $arrCondicao);
                        $formulaItem = str_replace($subItem['tx_variavel_sub_item_metrica'], $valorCondicao, $formulaItem);
                    }else{
                        $valorVariavel = $this->getValorVariavelPost($post, $subItem['tx_variavel_sub_item_metrica']);
                        $formulaItem = str_replace($subItem['tx_variavel_sub_item_metrica'], $valorVariavel, $formulaItem);
                    }
                }
                //monta o array com as fórmulas dos item [item][formula]
                $arrFormulaItem[$item['tx_variavel_item_metrica']][] = $formulaItem;
            }
        }

        //monta a fórmula da Definição da Métrica
        foreach($arrFormulaItem as $item=>$formula){
            $formula = "(".$formula[0].")";
            $formulaMetrica = str_replace($item, $formula, $formulaMetrica);
            $formulaMetrica = str_replace(' ', '', $formulaMetrica);
        }

        //troca a virgula por ponto caso exista para não dar erro na execução da formula
        $formulaMetrica = str_replace(',', '.', $formulaMetrica);

        //executa a formula
        eval("@\$result = {$formulaMetrica};"); //foi utilizado o "@" para esconder um provável erro caso alguma variável da métrica não esteja
                                              //cadastrado no banco. Caso contrário o eval identifica uma string na fórmula e tenta interpreta-la
                                              //como constante matando o processo.
        $fim = round($result,1);

        if($fim == 0){
            $arrRetorno['error'			 ] = true;
            $arrRetorno['errorType'		 ] = 2;
            $arrRetorno['msg'			 ] = Base_Util::getTranslator('L_MSG_ALERT_CONFERIR_VARIAVEIS_METRICA_VALOR_INFORMADO');
			$arrRetorno['unidade_metrica'] = $fim;
        }else{

			if( $arrDadosContratoDefinicaoMetrica->st_metrica_padrao === "S" ){
				$arrRetorno['unidade_metrica'	] = $fim;
				$arrRetorno['tx_calculo_metrica'] = "";
			}else{
				$arrRetorno['unidade_metrica'	] = $fim * $arrDadosContratoDefinicaoMetrica->nf_fator_relacao_metrica_pad;
				$arrRetorno['tx_calculo_metrica'] = $arrDefinicaoMetrica['tx_sigla_metrica'].": {$fim} * {$arrDadosContratoDefinicaoMetrica->nf_fator_relacao_metrica_pad}";
			}
		}
        echo Zend_Json::encode($arrRetorno);
    }

    private function getCondicaoSubItem($cd_definicao_metrica, $cd_item_metrica, $cd_sub_item_metrica)
    {
        return $this->objCondicaoSubItemMetrica->getDadosCondicaoSubItemMetrica($cd_definicao_metrica, $cd_item_metrica, $cd_sub_item_metrica);
    }

    /**
     * Método para varrer as condições dos sub-itens e retornar o valor cadastrato se a
     * condição for verdadeira.
     * 
     * @param Array $arrPost
     * @param Array $arrCondicao
     * @return integer
     */
    private function verificaCondicaoPost(array $arrPost, array $arrCondicao)
    {
        $valorCondicao = 0;
        foreach( $arrCondicao as $cond ){
            foreach($arrPost as $key=>$value){

                $arrKey = explode("|", $key);
				
                //caso não foi informado nenhum valor na tela, não faz nada 
                // e retorna 0 (zero)
                if( $value != '' ){
                	
	                if( strstr( $cond['tx_condicao_sub_item_metrica'], trim($arrKey[0]) ) ){
	                    $expressao = str_replace(trim($arrKey[0]), $value, $cond['tx_condicao_sub_item_metrica']);
	
	                    eval("\$condicao = {$expressao};");
	                    if($condicao){
	                        $valorCondicao = $cond['ni_valor_condicao_satisfeita'];
	                    }
	                }
                }
            }
        }
        return $valorCondicao;
    }

    /**
     * Método para recuperar o valor da variavel vinda da tela
     *
     * @param Array $arrPost
     * @param String $variavel
     * @return Integer
     */
    private function getValorVariavelPost($arrPost, $variavel)
    {
        $valor = 0;
        foreach($arrPost as $var=>$value){
            $arrVar = explode("|", $var);
            if(trim($arrVar[0]) == $variavel){
                $valor = ($value == "") ? 0 : $value;
            }
        }
        return $valor;
    }

    public function salvarCalculoMetricaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post                 = $this->_request->getPost();

        $cd_definicao_metrica = $post['cd_definicao_metrica'];
        $cd_projeto           = $post['cd_projeto'];
        $cd_proposta          = $post['cd_proposta'];
        $totalHorasMetrica    = $post['valor_total_calculo_metrica'];

        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
        $db = Zend_Registry::get('db');

        if(array_key_exists('tx_justificativa_metrica', $post)){
            $tx_justificativa_metrica = $post['tx_justificativa_metrica'];
        }else{
            $tx_justificativa_metrica = null;
        }

        try{
            $db->beginTransaction();

			$erro = false;
            $erro = (!$erro) ? $this->salvarRegistrosCalculoMetrica( $post ) : true;
            $erro = (!$erro) ? $this->registraTotalHorasPorMetrica( $cd_projeto, $cd_proposta, $cd_definicao_metrica, $totalHorasMetrica, $tx_justificativa_metrica ) : true;

            if(!$erro){
                $totalHorasProposta = $this->objPropostaDefinicaoMetrica->getTotalHorasProposta($cd_projeto, $cd_proposta);
            }
            
            $erro = (!$erro) ? $this->registraTotalHorasProposta($cd_projeto, $cd_proposta, $totalHorasProposta): true;
			
        	($erro) ? $db->rollBack() : $db->commit();

        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    /**
     * Método para salvar os valores dos sub-itens informados na tela de cálculo da métrica
     *
     * @param Array $arrPost
     * @return boolean $erro
     */
    private function salvarRegistrosCalculoMetrica( $arrPost )
    {
        $erro = false;

        $cd_definicao_metrica = $arrPost['cd_definicao_metrica'];
        $cd_projeto           = $arrPost['cd_projeto'];
        $cd_proposta          = $arrPost['cd_proposta'];

        //retira do post a informações que não serão salvas
        unset($arrPost['valor_total_calculo_metrica']);
        unset($arrPost['cd_definicao_metrica']);
        unset($arrPost['cd_projeto']);
        unset($arrPost['cd_proposta']);

        if(array_key_exists('tx_justificativa_metrica', $arrPost)){
            unset($arrPost['tx_justificativa_metrica']);
        }
		
        foreach( $arrPost as $subItem=>$valor ){

            $arrDados            = explode("|", $subItem);
            $cd_sub_item_metrica = trim($arrDados[1]);
            $cd_item_metrica     = trim($arrDados[2]);

            $reg = $this->objPropostaSubItemMetrica->find($cd_projeto, $cd_proposta, $cd_item_metrica, $cd_definicao_metrica, $cd_sub_item_metrica)->current();

            if( count($reg) > 0 ){

                $arrUpdate['ni_valor_sub_item_metrica'] = ($valor == '') ? 0 : $valor;

                $where  = "cd_projeto           = {$cd_projeto}           and ";
                $where .= "cd_proposta          = {$cd_proposta}          and ";
                $where .= "cd_definicao_metrica = {$cd_definicao_metrica} and ";
                $where .= "cd_item_metrica      = {$cd_item_metrica}      and ";
                $where .= "cd_sub_item_metrica  = {$cd_sub_item_metrica}      ";

                if( !$this->objPropostaSubItemMetrica->update($arrUpdate, $where) ){
                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_VALORES_SUB_ITEM_METRICA'));
                    $error = true;
                }
            }else{

                $novo                            = $this->objPropostaSubItemMetrica->createRow();
                $novo->cd_projeto                = $cd_projeto;
                $novo->cd_proposta               = $cd_proposta;
                $novo->cd_definicao_metrica      = $cd_definicao_metrica;
                $novo->cd_item_metrica           = $cd_item_metrica;
                $novo->cd_sub_item_metrica       = $cd_sub_item_metrica;
                $novo->ni_valor_sub_item_metrica = ($valor == '')?0:$valor;

                if( !$novo->save() ){
                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_VALORES_SUB_ITEM_METRICA'));
                    $error = true;
                }
            }
        }
        return $erro;
    }

    /**
     * Método para salvar o saldo de horas parcial (da métrica informada na tela) da proposta
     *
     * @param Integer $cd_projeto
     * @param Integer $cd_proposta
     * @param Integer $cd_definicao_metrica
     * @param Integer $totalHorasMetrica
     * @return Boolean $erro
     */
    private function registraTotalHorasPorMetrica( $cd_projeto, $cd_proposta, $cd_definicao_metrica, $totalHorasMetrica, $tx_justificativa_metrica )
    {
        $erro = false;

        $reg = $this->objPropostaDefinicaoMetrica->find($cd_projeto, $cd_proposta, $cd_definicao_metrica)->current();
		
        if( count($reg) > 0 ){
            $arrUpdate['ni_horas_proposta_metrica'] = $totalHorasMetrica;
            $arrUpdate['tx_justificativa_metrica' ] = $tx_justificativa_metrica;
            
            $where  = "cd_projeto           = {$cd_projeto}      and ";
            $where .= "cd_proposta          = {$cd_proposta}     and ";
            $where .= "cd_definicao_metrica = {$cd_definicao_metrica}";

            if( !$this->objPropostaDefinicaoMetrica->update($arrUpdate, $where)){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_SALDO_PARCIAL_CALCULO_METRICA_PROPOSTA'));
                $erro = true;
            }
        }else{

            $novo                            = $this->objPropostaDefinicaoMetrica->createRow();
            $novo->cd_projeto                = $cd_projeto;
            $novo->cd_proposta               = $cd_proposta;
            $novo->cd_definicao_metrica      = $cd_definicao_metrica;
            $novo->ni_horas_proposta_metrica = $totalHorasMetrica;
            $novo->tx_justificativa_metrica  = $tx_justificativa_metrica;

            if(!$novo->save()){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_SALDO_PARCIAL_CALCULO_METRICA_PROPOSTA'));
                $erro = true;
            }
        }
        return $erro;
    }

    /**
     * Método que grava o somatorio das horas das métricadas calculadas para o projeto
     * 
     * @param <int> $cd_projeto
     * @param <int> $cd_proposta
     * @param <int> $totalHoras
     * @return <boolean> $erro
     */
    private function registraTotalHorasProposta( $cd_projeto, $cd_proposta, $totalHoras )
    {
        $erro = false;

        $arrUpdate['ni_horas_proposta'] = $totalHoras;
        $where = "cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta}";

        if( !$this->objProposta->update($arrUpdate, $where) ){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_VALORES_PROPOSTA'));
            $erro = true;
        }
		
    	if ($erro == false) {
			
			/*se a proposta é a proposta 1, a inicial do projeto, a quantidade de horas da parcela 1
			será o equivalente a porcentagem indicada pela constante.*/
			if ($cd_proposta == 1 && $this->st_parcela_orcamento == 'S')
			{
				$ni_horas_parcela_proposta = ($totalHoras * $this->ni_porcentagem_parc_orcamento)/100;
				$ni_horas_parcela_proposta = round($ni_horas_parcela_proposta,1);
				
				$parcela = new Parcela($this->_request->getControllerName());
		        if($parcela->defineParcelaProposta($cd_projeto, $ni_horas_parcela_proposta) == 0){
		            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_HORAS_PROPOSTA'));
		            $erro = true;
		        }
			}
		}  
        return $erro;
    }

	public function verificaExistenciaMetricaPadraoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post       = $this->_request->getPost();
		$cd_projeto = ( array_key_exists("cd_projeto", $post)) ? $post['cd_projeto'] : null;
		$arrReturn  = array('error'=>false, 'errorType'=>'', 'msg'=>'');

		if($cd_projeto != 0){
			//recupera os dados do contrato ativo ao qual pertence o projeto em questao
			$arrDadosContrato = $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
			$rowSetMetrica    = $this->objContratoDefinicaoMetrica->fetchAll($this->objContratoDefinicaoMetrica->select()
                                                                                                               ->where("cd_contrato = ?", $arrDadosContrato['cd_contrato'], Zend_Db::INT_TYPE));
			if( count($rowSetMetrica) == 0 ){
				$arrReturn['error'	  ] = true;
				$arrReturn['errorType'] = 2;
				$arrReturn['msg'	  ] = Base_Util::getTranslator('L_MSG_ALERT_CONTRATO_SEM_METRICA_PADRAO_CONTACTE_ADMINISTRADOR');
			}else{
				foreach( $rowSetMetrica as $metrica ){
					if( ($metrica->st_metrica_padrao !== 'S') && ($metrica->nf_fator_relacao_metrica_pad == '') ){
						$arrReturn['error'	  ] = true;
						$arrReturn['errorType'] = 2;
						$arrReturn['msg'	  ] = Base_Util::getTranslator('L_MSG_ALERT_METRICA_VINCULADA_CONTRATO_SEM_FATOR_METRICA_PADRAO_CONTACTE_ADMINISTRADOR');
						break;
					}
				}
			}
		}
		echo Zend_Json::encode($arrReturn);
	}
}