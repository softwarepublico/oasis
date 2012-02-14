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

class ExecucaoPropostaController extends Base_Controller_Action 
{
	private $cd_projeto;
	private $cd_proposta;
	private $objModulo;
	private $objTipoConhecimento;
	private $objTipoDocumentacao;
	private $objConhecimento;
	private $proposta;
	private $objConfigBanco;
	private $objProjeto;
	private $planoImplantacao;
	private $objUtil;
	private $objRequisito;
	private $objPapelProfissional;
	private $objPropostaDefinicaoMetrica;
	private $objDefinicaoMetrica;
	private $objItemMetrica;
	private $objSubItemMetrica;
	private $objCondicaoSubItemMetrica;
	private $objPropostaSubItemMetrica;
	private $objContratoDefinicaoMetrica;
	private $objContrato;
	private $arrDadosMetricaPadrao;
	
	public function init()
	{
		parent::init();
		$this->objModulo           = new Modulo($this->_request->getControllerName());
		$this->objTipoConhecimento = new TipoConhecimento($this->_request->getControllerName());
		$this->objConhecimento     = new Conhecimento($this->_request->getControllerName());
		$this->proposta            = new Proposta($this->_request->getControllerName());		
		$this->objConfigBanco      = new ConfigBancoDeDados($this->_request->getControllerName());
		$this->objProjeto          = new Projeto($this->_request->getControllerName());
		$this->objTipoDocumentacao = new TipoDocumentacao($this->_request->getControllerName());
		$this->planoImplantacao	   = new PlanoImplantacao($this->_request->getControllerName());
		$this->objUtil             = new Base_Controller_Action_Helper_Util();
		$this->objRequisito        = new Requisito($this->_request->getControllerName());
		$this->objPapelProfissional= new PapelProfissional($this->_request->getControllerName());
		$this->objPropostaDefinicaoMetrica = new PropostaDefinicaoMetrica($this->_request->getControllerName());
		$this->objDefinicaoMetrica         = new DefinicaoMetrica($this->_request->getControllerName());
		$this->objItemMetrica              = new ItemMetrica($this->_request->getControllerName());
		$this->objSubItemMetrica           = new SubItemMetrica($this->_request->getControllerName());
		$this->objCondicaoSubItemMetrica   = new CondicaoSubItemMetrica($this->_request->getControllerName());
		$this->objPropostaSubItemMetrica   = new PropostaSubItemMetrica($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->objContrato				   = new Contrato($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_EXECUCAO_PROPOSTA'));

		//Paramêtros recebidos por GET
		$this->cd_projeto  = (int)$this->_request->getPost('cd_projeto', 1);
		$this->cd_proposta = (int)$this->_request->getPost('cd_proposta', 1);
		$openAccordion     = $this->_request->getPost('accordion');
		//recupera os dados do contrato ativo ao qual pertence o projeto em questao

		$arrDadosContrato				  = $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($this->cd_projeto);
		$this->arrDadosMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($arrDadosContrato['cd_contrato']);

		$this->view->unidadePadraoMetrica = $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'];

		//Dados enviados para tela
		$this->view->cd_projeto  = $this->cd_projeto;
		$this->view->cd_proposta = $this->cd_proposta;
		$this->view->openAccordion = $openAccordion;
		
		$arrProjeto = $this->objProjeto->getDadosProjeto($this->cd_projeto);
		$this->view->tx_sigla_projeto        = $arrProjeto[0]['tx_sigla_projeto'];
		$this->view->tx_mes_inicio_previsto  = $this->objUtil->getMes($arrProjeto[0]['ni_mes_inicio_previsto']);
		$this->view->ni_ano_inicio_previsto  = $arrProjeto[0]['ni_ano_inicio_previsto'];
		$this->view->tx_mes_termino_previsto = $this->objUtil->getMes($arrProjeto[0]['ni_mes_termino_previsto']);
		$this->view->ni_ano_termino_previsto = $arrProjeto[0]['ni_ano_termino_previsto'];
		

		// consulta proposta
		$selectProposta = $this->proposta->select();
		$selectProposta->where("cd_projeto  = ?",$this->cd_projeto, Zend_Db::INT_TYPE)
				       ->where("cd_proposta = ?",$this->cd_proposta, Zend_Db::INT_TYPE);
		$resProposta = $this->proposta->fetchRow($selectProposta);
		$this->view->resProposta = $resProposta;		
		
		// Consulta Solicitacao
		$solicitacao 	= new Solicitacao($this->_request->getControllerName());
		$resSolicitacao = $solicitacao->getSolicitacao($resProposta->cd_objeto, $resProposta->ni_solicitacao, $resProposta->ni_ano_solicitacao);
		$this->view->solicitacao = $resSolicitacao;		
		
		/** Telas de accordion **/
		//condição que checa a permissão para acessar o accordion 'Caso de Uso'
		if(ChecaPermissao::possuiPermissao('caso-de-uso') === true){
			/** Recupera os atores do projeto **/
			$objAtor = new Ator($this->_request->getControllerName());
			$arrAtorCombo = $objAtor->getAtor(true,$this->cd_projeto);
			$this->view->comboAtor = $arrAtorCombo;
			
			$arrModulo = $this->objModulo->getModulo($this->cd_projeto, true);
			$this->view->comboModulo = $arrModulo;
		}
		
		if (ChecaPermissao::possuiPermissao('dicionario-de-dados') === true){
			/**
			 * Método para processar os dados da tela de conecimento utilizado
			 * do accordeon Dados Técnicos
			 */
			$this->accordionDicionarioDeDados();	
		}
		
		//condição que checa a permissão para acessar o accordion 'Dados Técnicos'
		if (ChecaPermissao::possuiPermissao('dados-tecnicos') === true){
			/**
			 * Método para processar os dados da tela de conecimento utilizado
			 * do accordeon Dados Técnicos
			 */
			$this->tabConhecimentoUtilizado();
		}
		
		//Accordion 'Objetivo da Proposta'
		$arrProjeto = $this->objProjeto->getProjeto(false,$this->cd_projeto);
		$this->view->nomeProjeto = $arrProjeto[$this->cd_projeto];
		$this->view->tx_objetivo_proposta = $resProposta->tx_objetivo_proposta;
		
		//Accordion 'Descrição de Projeto'
		$this->view->arrDescricaoProjeto = $this->objProjeto->getDescricaoProjeto($this->cd_projeto);

		//Accordion 'Produtos'
		$this->view->escondeExclusao = "S";
		
		//Accordion 'Arquivo Proposta'
		$this->view->tipoDocumentacaoCombo = $this->objTipoDocumentacao->getTipoDocumentacao("P",true);

		//Accordion 'Métrica'
		$this->accordionMetrica();
		
		//condição que checa a permissão para acessar o accordion 'regra_negocio'
        if (ChecaPermissao::possuiPermissao('regra-negocio') === true){
            //metodo passa todas as informações necessarias para a tela de Regra de Negócios
            $this->accordionRegraNegocio();
        }
        
		//condição que checa a permissão para acessar o accordion plano_implantacao
		if (ChecaPermissao::possuiPermissao('plano-implantacao') === true){
			//metodo passa todas as informações necessarias para a tela de Plano de Implantação
			$this->accordionPlanoImplantacao();
		}		
		//condição que checa a permissão para acessar o accordion associa_requisito_regra
		if (ChecaPermissao::possuiPermissao('associar-requisito-regra-negocio') === true){
			//metodo passa todas as informações necessarias para a tela de Requisitos e Regra de Negócio
			$this->accordionRequisitoRegraNegocio();
		}		
		//condição que checa a permissão para acessar o accordion associa_requisito_caso_de_uso
		if (ChecaPermissao::possuiPermissao('associar-requisito-caso-de-uso') === true){
			//metodo passa todas as informações necessarias para a tela de Requisitos e Caso de Uso
			$this->accordionRequisitoCasoDeUso();
		}		
		
		/**
		 * Alocação de profissionais
		 */	
		$this->view->arrPapelProfissional = $this->objPapelProfissional->getPapelProfissional($_SESSION['oasis_logged'][0]['cd_objeto'], true);
    }

    /**
     * Método que manda as informações da tela de Regra de Negócios
     */
    private function accordionMetrica()
    {

		$objSelectPropostaDefinicaoMetrica = $this->objPropostaDefinicaoMetrica->select()
																			   ->from($this->objPropostaDefinicaoMetrica, array('cd_definicao_metrica', 'ni_horas_proposta_metrica'))
																			   ->where("cd_projeto  = ?", $this->cd_projeto, Zend_Db::INT_TYPE)
																			   ->where("cd_proposta = ?", $this->cd_proposta, Zend_Db::INT_TYPE)
																			   ->order('cd_definicao_metrica');

		$objDadosPropostaDefinicaoMetrica = $this->objPropostaDefinicaoMetrica->fetchAll($objSelectPropostaDefinicaoMetrica);

		if( !$objDadosPropostaDefinicaoMetrica->valid() ){
			$this->view->semRegistroMetrica = true;
		}else{
			$this->view->semRegistroMetrica = false;

			$arrResult = array();

			foreach($objDadosPropostaDefinicaoMetrica as $regMetrica){
				$arrResult[]	= $this->getDadosMetrica($regMetrica->cd_definicao_metrica, $regMetrica->ni_horas_proposta_metrica);
			}
		}
        $this->view->arrMetricas = $arrResult;
    }

	private function getDadosMetrica( $cd_definicao_metrica, $ni_horas_proposta_metrica )
	{
		$arrDefinicaoMetrica = $this->objDefinicaoMetrica->find($cd_definicao_metrica)->current()->toArray();

		//recupera a formula da metrica
        $formulaMetrica = $arrDefinicaoMetrica['tx_formula_metrica'];
		
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

					$subItemItem['valor'] = $this->getValorCadastradoSubItem($this->cd_projeto, $this->cd_proposta, $cd_definicao_metrica, $item['cd_item_metrica'], $subItem['cd_sub_item_metrica']);

					//monta o array de retorno com os sub-itens não internos
					//$arrRetorno[$tx_variavel_item_metrica][] = $subItemItem;
					$arrRetorno[$arrDefinicaoMetrica['tx_nome_metrica']][] = $subItemItem;
                }
            }
        }
		// adiciona o total de horas calculado para a metrica
		$arrRetorno[$arrDefinicaoMetrica['tx_nome_metrica']]['ni_horas_proposta_metrica'] = $ni_horas_proposta_metrica;

		return $arrRetorno;
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
     * Método que manda as informações da tela de Regra de Negócios
     */
    private function accordionRegraNegocio()
    {
        $this->view->cd_projeto_regra_negocio = $this->cd_projeto;
    }

    /**
     * Método que manda as informações da tela de Plano de Implantação
     */
    private function accordionPlanoImplantacao()
    {
    	$planoImplantacao = $this->planoImplantacao->getDadosPlanoImplantacao($this->cd_projeto, $this->cd_proposta);

    	if(empty($planoImplantacao)){
    		$descricao = '';
    		$this->view->acao = 'insert';
    	}else{
    		$descricao = $planoImplantacao[0]['tx_descricao_plano_implantacao'];
    		$this->view->acao = 'update';
    	}
    	$this->view->descricaoPlanoImplantacao = $descricao; 
    }

    /**
     * Método que manda as informações da tela de Requisitos e Regra de Negócio
     */
    private function accordionRequisitoRegraNegocio()
    {
    	$this->view->arrRequisitos = $this->objRequisito->getComboRequisito( $this->cd_projeto, true ); 
    }
    
    /**
     * Método que manda as informações da tela de Requisitos e Caso de Uso
     */
    private function accordionRequisitoCasoDeUso()
    {
    	$this->view->arrRequisitosCasoDeUso = $this->objRequisito->getComboRequisito( $this->cd_projeto, true ); 
    }
    
    
	public function tabConhecimentoUtilizado()
	{
		$arrComboTipoConhecimento = $this->objTipoConhecimento->getComboTipoConhecimento(true);
		$this->view->arrComboTipoConhecimento = $arrComboTipoConhecimento;
	}
	
	
	protected function accordionDicionarioDeDados()
	{
		$this->tabConfiguracaoBancoDados();
	}
	
	
	private function tabConfiguracaoBancoDados()
	{
		$arrAdapterPdo = array(Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL   =>'PDO MySQL',
						       Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL   =>'PDO SQL Server',
						       Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE  =>'PDO Oracle',
						       Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES=>'PDO PostgreSQL');
		$arrAdapter    = array(Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL =>'MySQL',
							   Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE=>'Oracle',
							   Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL =>'Sqlsrv');
                           
		$arrComboGroup = array(Base_Util::getTranslator('L_VIEW_COMBO_CONEXAO_COM_PDO') => $arrAdapterPdo,
                               Base_Util::getTranslator('L_VIEW_COMBO_CONEXAO_SEM_PDO') => $arrAdapter);
		$this->view->comboBancoDados = $arrComboGroup;
        
		$objConfiBanco = $this->objConfigBanco->getConfigBancoDados($this->cd_projeto);
		if(count($objConfiBanco) > 0){
			$this->view->tx_adapter   = $objConfiBanco->tx_adapter;
			$this->view->tx_dbname    = $objConfiBanco->tx_dbname;
			$this->view->tx_username  = $objConfiBanco->tx_username;
			$this->view->tx_password  = $objConfiBanco->tx_password;
			$this->view->tx_host      = $objConfiBanco->tx_host;
			$this->view->tx_schema    = $objConfiBanco->tx_schema;
			$this->view->tx_port      = $objConfiBanco->tx_port;
		}
	}

	public function confirmaExecucaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');
		$condicao    = $this->_request->getParam('condicao');
		$campo       = $this->_request->getParam('campo');
		
		$upRow = array();
		$upRow[$campo] = 1;
		
		$arrMsg = array();
		$arrMsg['st_caso_de_uso']          = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_CASO_DE_USO');
		$arrMsg['st_dicionario_dados']     = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_DICIONARIO_DE_DADOS');
		$arrMsg['st_informacoes_tecnicas'] = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_DADOS_TECNICOS');
		$arrMsg[99]                        = Base_Util::getTranslator('L_MSG_ERRO_CONFIRMAR_DADOS Erro ao confirmar dados');
		
		if($condicao == "proposta"){
			$erros = $this->proposta->atualizaProposta($cd_projeto, $cd_proposta, $upRow);
			$msg = ($erros)?$arrMsg[99]:$arrMsg[$campo];
		} else {
			$erros = $this->objProjeto->atualizaStatusProjeto($cd_projeto, $upRow);
			$msg = ($erros)?$arrMsg[99]:$arrMsg[$campo];
		}
		
		echo $msg;
	}
	
	public function validaAccordionAction()
	{
	    $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');

		$arrDados = $this->objProjeto->validaAccordion($cd_projeto, $cd_proposta);
		echo Zend_Json::encode($arrDados);
	}
}