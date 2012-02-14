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

class RelatorioProjeto_DocumentoPropostaController extends Base_Controller_Action
{
	private $objContrato;
	private $objContratoProjeto;
	private $objRelDocumentoProposta;
	private $objContratoDefinicaoMetrica;
	private $objObjetoContrato;
    
    
	private $arrParam   = array();
	private $arrAux     = array();
	private $arrMetrica = array();
	
	public function init()
	{
		parent::init();
		$this->objContrato					= new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto			= new ContratoProjeto($this->_request->getControllerName());
		$this->objRelDocumentoProposta		= new RelatorioProjetoDocumentoProposta();
		$this->objContratoDefinicaoMetrica	= new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->objObjetoContrato        	= new ObjetoContrato($this->_request->getControllerName());
	}
	
    /**
     * Action da tela de inicial
     */
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DOCUMENTO_PROPOSTA'));

		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', null, true);
    }

    public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}

    /**
     * Action de geração do relatório do Documento Proposta
     */
	public function documentoPropostaAction()
	{
    	$this->_helper->layout->disableLayout(true);

		$this->generateAction();
	}

    private function generateAction()
    {
        $cd_projeto     = (int)$this->_request->getParam('cd_projeto');
        $cd_proposta    = (int)$this->_request->getParam('cd_proposta');
        $tipo_relatorio = $this->_request->getParam('tipo_proposta');

        $this->view->tipo_relatorio		        = $tipo_relatorio;
        $this->arrParam['cd_projeto' ]	        = $cd_projeto;
        $this->arrParam['st_parcela_orcamento'] = ($this->_request->getParam('st_parcela_orcamento'))?$this->_request->getParam('st_parcela_orcamento'):null;
        $this->arrParam['cd_proposta']	        = $cd_proposta;
        $this->view->cd_proposta		        = $cd_proposta;

		$arrContratoFechamento = $this->objContratoDefinicaoMetrica->getContratoUltimoFechamentoProposta($cd_projeto, $cd_proposta);
		if (!is_null($arrContratoFechamento)) {
            $arrContratoFechamento->toArray();
			$arrSiglaMetricaPadrao = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica( $arrContratoFechamento['cd_contrato'] );
			$this->view->siglaMetricaPadrao	= $arrSiglaMetricaPadrao[0]['tx_sigla_metrica'];
		}else{
			$arrSiglaMetricaPadrao = $this->objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($cd_projeto);
			$this->view->siglaMetricaPadrao	= $arrSiglaMetricaPadrao['tx_sigla_unidade_metrica'];
		}

        if ($cd_proposta === 1){
            $parametrosRelatorio = Base_Util::getTranslator('L_VIEW_PROJETO_NOVO');
        }else{
            $parametrosRelatorio = Base_Util::getTranslator('L_VIEW_PROJETO_EVOLUTIVO');
        }   
		$this->view->parametrosRelatorio = $parametrosRelatorio;
        
        $arrDadosProjeto             = $this->objRelDocumentoProposta->getDadosProjeto($this->arrParam);
        $this->view->arrDadosProjeto = $arrDadosProjeto;
        
        $arrDadosProposta             = $this->objRelDocumentoProposta->getDadosProposta($this->arrParam);
        $this->view->arrDadosProposta = $arrDadosProposta;
        
        $this->metrica();

        $this->view->arrDadosMetrica        = $this->arrMetrica;

        $arrDadosModuloProposta             = $this->objRelDocumentoProposta->getDadosModuloProposta($this->arrParam);
        $this->view->arrDadosModuloProposta = $arrDadosModuloProposta;

        $objUtil           = new Base_Controller_Action_Helper_Util();
        $arrProdutoParcela = array();
        $arrDadosParcela   = $this->objRelDocumentoProposta->getDadosParcela($this->arrParam);
        foreach($arrDadosParcela as $key=>$parcela){
           $arrProdutoParcela[$parcela['cd_parcela']] = $this->objRelDocumentoProposta->getDadosProdutoParcela($parcela['cd_parcela']);
           $descMes                                   = $objUtil->getMesRes($parcela['ni_mes_previsao_parcela'])."/".$parcela['ni_ano_previsao_parcela'];
           $arrDadosParcela[$key]['dt_prevista']      = $descMes;
        }

        $this->view->arrDadosParcela   = $arrDadosParcela;
        $this->view->arrProdutoParcela = $arrProdutoParcela;

        $arrDadosProcessamentoPropostaAceite             = $this->objRelDocumentoProposta->getDadosProcessamentoPropostaAceite($this->arrParam);
        $this->view->arrDadosProcessamentoPropostaAceite = $arrDadosProcessamentoPropostaAceite;

        $arrUltimoProcessamentoProposta                  = $this->objRelDocumentoProposta->getUltimoFechamentoProposta($this->arrParam);
        $this->view->arrUltimoProcessamentoProposta      = $arrUltimoProcessamentoProposta;
    }

	/**
	 * Método que recupera as métricas do projeto e da proposta
	 * @param array $arrParam
	 */
	private function metrica()
	{
		$objContrato                 = new Contrato();
		$objDefinicaoMetrica         = new DefinicaoMetrica();
		$objPropostaDefinicaoMetrica = new PropostaDefinicaoMetrica();
        $objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica();

		$arrContratoFechamento = $this->objContratoDefinicaoMetrica->getContratoUltimoFechamentoProposta($this->arrParam['cd_projeto'], $this->arrParam['cd_proposta']);
		if (count($arrContratoFechamento)>0) {
			$cd_contrato = $arrContratoFechamento['cd_contrato'];
		}else{
	        $arrContrato = $objContrato->getDadosContratoAtivoObjetoTipoProjeto($this->arrParam['cd_projeto']);
		    $cd_contrato = $arrContrato['cd_contrato'];
		}

        $arrSiglaMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica( $cd_contrato );
		
        if(count($arrSiglaMetricaPadrao) == 0){
            $this->arrMetrica = array();
        } else {
            //Recupera as informações da métrica do projeto e da proposta
            $this->getDadosPropostaDefinicaoMetrica();

            $arrContratoDefinicaoMetrica = array();
            $tx_nome_metrica             = "";
            $ni_horas_proposta_metrica   = 0;
            foreach($this->arrMetrica as $key=>$value){
                $arrContratoDefinicaoMetrica = $objContratoDefinicaoMetrica->fetchAll(
                                                    $objContratoDefinicaoMetrica->select()
                                                          ->from($objContratoDefinicaoMetrica, array('st_metrica_padrao','nf_fator_relacao_metrica_pad'))
                                                          ->where("cd_contrato          = ?", $cd_contrato, Zend_Db::INT_TYPE)
                                                          ->where("cd_definicao_metrica = ?", $key, Zend_Db::INT_TYPE)
                                                )->toArray();
                 if(count($arrContratoDefinicaoMetrica) > 0){
                    $arrContratoDefinicaoMetrica = $objContratoDefinicaoMetrica->find($cd_contrato,$key)->current();
                    $this->arrMetrica[$key]['st_metrica_padrao']            = $arrContratoDefinicaoMetrica['st_metrica_padrao'];
                    $this->arrMetrica[$key]['nf_fator_relacao_metrica_pad'] = $arrContratoDefinicaoMetrica['nf_fator_relacao_metrica_pad'];
                 } else {
                    $arrDefinicao = $objDefinicaoMetrica->fetchAll(
                                                $objDefinicaoMetrica->select()
                                                ->from($objDefinicaoMetrica, "tx_nome_metrica")
                                                ->where(" cd_definicao_metrica = ?", $key, Zend_Db::INT_TYPE)
                                            )->toArray();
                    $arrValorMetrica = $objPropostaDefinicaoMetrica->fetchAll(
                                            $objPropostaDefinicaoMetrica->select()
                                            ->from($objPropostaDefinicaoMetrica,"ni_horas_proposta_metrica")
                                            ->where("cd_projeto  = ?", $this->arrParam['cd_projeto' ], Zend_Db::INT_TYPE)
                                            ->where("cd_proposta = ?", $this->arrParam['cd_proposta'], Zend_Db::INT_TYPE)
                                            ->where("cd_definicao_metrica = ?", $key, Zend_Db::INT_TYPE)
                                       )->toArray();

                    if(count($arrDefinicao) > 0){
                        $tx_nome_metrica = $arrDefinicao[0]['tx_nome_metrica'];
                    }
                    if(count($arrValorMetrica) > 0){
                        $ni_horas_proposta_metrica = $arrValorMetrica[0]['ni_horas_proposta_metrica'];
                    }
                    unset($this->arrMetrica[$key]);

                    $arrValueMsg = array('value1'=>$tx_nome_metrica,
                                         'value2'=>$ni_horas_proposta_metrica);
                    $this->arrMetrica[$key]['observacao'] = Base_Util::getTranslator('L_MSG_ALERT_METRICA_POSSUI_UNIDADES_VINCULADA_PROJETO_METRICA_FORA_CONTRATO', $arrValueMsg);
                }
            }
        }
	}

	/**
	 * Mmétodo que recupera a definição da métrica utilizado
	 * pelo projeto e pela proposta
	 *
	 * @param array $this->arrParam['cd_projeto']
	 * @param array $this->arrParam['cd_proposta']
	 * @return array $arrDados
	 */
	private function getDadosPropostaDefinicaoMetrica()
	{
		$objPropostaDefinicaoMetrica = new PropostaDefinicaoMetrica($this->_request->getControllerName());

		$this->arrAux = $objPropostaDefinicaoMetrica->fetchAll(
						$objPropostaDefinicaoMetrica->select()
						->from($objPropostaDefinicaoMetrica, array('cd_definicao_metrica', 'ni_horas_proposta_metrica', 'tx_justificativa_metrica'))
                        ->where("cd_projeto  = ?", $this->arrParam['cd_projeto' ], Zend_Db::INT_TYPE)
						->where("cd_proposta = ?", $this->arrParam['cd_proposta'], Zend_Db::INT_TYPE)
					)->toArray();
		$this->getDadosDefinicaoMetrica();
	}

	/*
	 * Método que recupera os dados da definição da métrica
	 * @param $this->arrAux;
	 * @return $this->arrMetrica;
	 */
	private function getDadosDefinicaoMetrica()
	{
		$objDefinicaoMetrica       = new DefinicaoMetrica($this->_request->getControllerName());
		foreach($this->arrAux as $key=>$value){
			$this->arrMetrica[$value['cd_definicao_metrica']] = $objDefinicaoMetrica->find($value['cd_definicao_metrica'])->current()->toArray();
			if(!empty($this->arrMetrica[$value['cd_definicao_metrica']]['st_justificativa_metrica'])){
				$this->arrMetrica[$value['cd_definicao_metrica']]['tx_justificativa_metrica' ] = $value['tx_justificativa_metrica'];
				$this->arrMetrica[$value['cd_definicao_metrica']]['ni_horas_proposta_metrica'] = $value['ni_horas_proposta_metrica'];
			} else {
				$this->arrMetrica[$value['cd_definicao_metrica']]['ni_horas_proposta_metrica'] = $value['ni_horas_proposta_metrica'];
			}
			$this->arrParam['cd_definicao_metrica'] = $value['cd_definicao_metrica'];
			$this->getDadosItemMetrica();
		}
	}

	/*
	 * Método que recupera os dados do item da métrica
	 * @param $this->arrParam['cd_definicao_metrica']
	 * @return $this->arrMetrica;
	 */
	private function getDadosItemMetrica()
	{
		$objItemMetrica = new ItemMetrica($this->_request->getControllerName());

		$arrItemMetrica = $objItemMetrica->fetchAll($objItemMetrica->select()
                                                                   ->where("cd_definicao_metrica = ?",$this->arrParam['cd_definicao_metrica'],Zend_Db::INT_TYPE)
                                                                   ->order('ni_ordem_item_metrica')
                                                   )->toArray();
		foreach($arrItemMetrica as $key=>$value){
			$this->arrMetrica[$this->arrParam['cd_definicao_metrica']]
							 ['item_metrica']
							 [$value['cd_item_metrica']] = $value;
			$this->arrParam['cd_item_metrica'] = $value['cd_item_metrica'];
			$this->getDadosSubItemMetrica();
		}
	}

	/*
	 * Método que recupera as informações do sub item da métrica
	 * @param $this->arrParam['cd_definicao_metrica'];
	 * @param $this->arrParam['cd_item_metrica'];
	 * @return $this->arrMetrica;
	 */
	private function getDadosSubItemMetrica()
	{
		$objSubItemMetrica = new SubItemMetrica($this->_request->getControllerName());
		$arrSubItemMetrica = $objSubItemMetrica->fetchAll(
										 $objSubItemMetrica->select()
														   ->where("cd_definicao_metrica = ?", $this->arrParam['cd_definicao_metrica'],Zend_Db::INT_TYPE)
														   ->where("cd_item_metrica      = ?", $this->arrParam['cd_item_metrica'     ],Zend_Db::INT_TYPE)
														   ->order("ni_ordem_sub_item_metrica")
                                                         )->toArray();

		foreach($arrSubItemMetrica as $key=>$value){
			$this->arrMetrica[$this->arrParam['cd_definicao_metrica']]
						     ['item_metrica']
							 [$this->arrParam['cd_item_metrica']]
							 ['sub_item']
							 [$value['cd_sub_item_metrica']] = $value;
			$this->arrParam['cd_sub_item_metrica'] = $value['cd_sub_item_metrica'];
			$this->getDadosPropostaSubItemMetrica();
		}
	}

	private function getDadosPropostaSubItemMetrica()
	{
		$objPropostaSubItemMetrica = new PropostaSubItemMetrica($this->_request->getControllerName());
		$arrPropostaSubItemMetrica = $objPropostaSubItemMetrica->fetchAll(
                                            $objPropostaSubItemMetrica->select()
                                            ->from($objPropostaSubItemMetrica,
                                                   'ni_valor_sub_item_metrica')
                                            ->where("cd_projeto           = ?", $this->arrParam['cd_projeto'          ], Zend_Db::INT_TYPE)
                                            ->where("cd_proposta          = ?", $this->arrParam['cd_proposta'         ], Zend_Db::INT_TYPE)
                                            ->where("cd_item_metrica      = ?", $this->arrParam['cd_item_metrica'     ], Zend_Db::INT_TYPE)
                                            ->where("cd_definicao_metrica = ?", $this->arrParam['cd_definicao_metrica'], Zend_Db::INT_TYPE)
                                            ->where("cd_sub_item_metrica  = ?", $this->arrParam['cd_sub_item_metrica' ], Zend_Db::INT_TYPE)
									 )->toArray();

		$this->arrMetrica[$this->arrParam['cd_definicao_metrica']]
						 ['item_metrica']
						 [$this->arrParam['cd_item_metrica']]
						 ['sub_item']
						 [$this->arrParam['cd_sub_item_metrica']]
						 ['ni_valor_sub_item_metrica'] = 
						 (count($arrPropostaSubItemMetrica)>0)?
						 (!empty($arrPropostaSubItemMetrica[0]['ni_valor_sub_item_metrica']))?$arrPropostaSubItemMetrica[0]['ni_valor_sub_item_metrica']:0:0;
		$this->getCondicaoMetrica();
	}

	private function getCondicaoMetrica()
	{
		$objCondicao = new CondicaoSubItemMetrica($this->_request->getControllerName());
		$arrSubItem  = array();

		$arrCondicaoSubItemMetrica = $objCondicao->fetchAll(
										$objCondicao->select()
													->where("cd_definicao_metrica = ?", $this->arrParam['cd_definicao_metrica'], Zend_Db::INT_TYPE)
													->where("cd_item_metrica      = ?", $this->arrParam['cd_item_metrica'     ], Zend_Db::INT_TYPE)
													->where("cd_sub_item_metrica  = ?", $this->arrParam['cd_sub_item_metrica' ], Zend_Db::INT_TYPE)
									)->toArray();
		if(count($arrCondicaoSubItemMetrica)>0) {
			$arrSubItem = $this->arrMetrica[$this->arrParam['cd_definicao_metrica']]
										   ['item_metrica']
										   [$this->arrParam['cd_item_metrica']]
										   ['sub_item'];
			$expressao = "";
			$possui    = "";

			foreach($arrCondicaoSubItemMetrica as $chave=>$valor){
				foreach($arrSubItem as $key=>$value){
					$possui = strpos($this->toLower($valor['tx_condicao_sub_item_metrica']),$this->toLower($value['tx_variavel_sub_item_metrica']));
					if($possui !== false){
						$expressao = str_replace(trim($value['tx_variavel_sub_item_metrica']), $value['ni_valor_sub_item_metrica'], $valor['tx_condicao_sub_item_metrica']);
						eval("\$condicao = {$expressao};");
						if($condicao){
							$this->arrMetrica[$this->arrParam['cd_definicao_metrica']]
										     ['item_metrica']
										     [$this->arrParam['cd_item_metrica']]
										     ['sub_item']
										     [$this->arrParam['cd_sub_item_metrica']]
											 ['ni_valor_sub_item_metrica']= $valor['ni_valor_condicao_satisfeita'];
						}
					}
				}
			}
		}
	}
    
    public function pesquisaStParcelaOrcamentoAction()
    {
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

        $cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
        $arr = $this->objObjetoContrato->getDadosObjetoContrato($cd_contrato);
        $st_parcela_orcamento = ($arr[0]['st_parcela_orcamento'] == 'S') ? $arr[0]['st_parcela_orcamento'] : 'N';
        echo Zend_Json_Encoder::encode($st_parcela_orcamento);
    }
    
}