<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Minist?rio do Desenvolvimento, da Industria e do Com?rcio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gest?o de Demanda, Projetos e Servi?os de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma copia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class RelatorioProjeto_HistoricoPropostaController extends Base_Controller_Action
{
    private $objContrato;
    private $objProposta;
    private $objContratoProjeto;
    private $objHistoricoProposta;
    private $objHistoricoPropostaMetrica;
    private $objHistoricoPropostaSubItemMetrica;
    private $objHistoricoPropostaParcela;
    private $objHistoricoPropostaProduto;
    private $arrHistoricoProposta;
    private $arrProposta;
    private $arrMetrica;
    private $arrHistoricoPropostaSubItemMetrica;
    private $arrHistoricoPropostaParcela;
    private $arrHistoricoPropostaProduto;
    private $cd_projeto;
    private $cd_contrato;
    private $cd_proposta;
    private $dt_historico_proposta;

    public function init()
	{
		parent::init();
		$this->objContrato					        = new Contrato($this->_request->getControllerName());
		$this->objProposta					        = new Proposta($this->_request->getControllerName());
		$this->objContratoProjeto			        = new ContratoProjeto($this->_request->getControllerName());
		$this->objHistoricoProposta			        = new HistoricoProposta($this->_request->getControllerName());
		$this->objHistoricoPropostaMetrica			= new HistoricoPropostaMetrica($this->_request->getControllerName());
		$this->objHistoricoPropostaSubItemMetrica	= new HistoricoPropostaSubItemMetrica($this->_request->getControllerName());
		$this->objHistoricoPropostaParcela			= new HistoricoPropostaParcela($this->_request->getControllerName());
		$this->objHistoricoPropostaProduto			= new HistoricoPropostaProduto($this->_request->getControllerName());
	}

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_HISTORICO_PROPOSTA'));
		
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

    public function pesquisaDataHistoricoPropostaAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_projeto  = (int) $this->_request->getParam("cd_projeto", 0);
		$cd_proposta = (int) $this->_request->getParam("cd_proposta", 0);
        
		$arrDatas = $this->objHistoricoProposta->getDataHistoricoProposta($cd_projeto, $cd_proposta, true);

		$options = '';

		foreach( $arrDatas as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

    public function historicoPropostaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $this->cd_contrato           = $this->_request->getParam('cd_contrato',0);
        $this->cd_projeto            = $this->_request->getParam('cd_projeto',0);
        $this->cd_proposta           = $this->_request->getParam('cd_proposta',0);
        $this->dt_historico_proposta = $this->_request->getParam('dt_historico_proposta',0);

        $this->arrProposta                        = $this->objProposta->getDadosProjetoProposta($this->cd_projeto, $this->cd_proposta);
        $this->arrHistoricoProposta               = $this->objHistoricoProposta->getHistoricoProposta($this->cd_projeto, $this->cd_proposta, $this->dt_historico_proposta);
        $this->arrHistoricoPropostaParcela        = $this->objHistoricoPropostaParcela->getHistoricoPropostaParcela($this->cd_projeto, $this->cd_proposta, $this->dt_historico_proposta);

        foreach ($this->arrHistoricoPropostaParcela as $values){
            $arrAux[$values['cd_historico_proposta_parcela']] = $this->objHistoricoPropostaProduto->getHistoricoPropostaProduto($this->cd_projeto, $this->cd_proposta, $this->dt_historico_proposta, $values['cd_historico_proposta_parcela']);;
        }
        $this->arrHistoricoPropostaProduto        = $arrAux;
        
        switch ($this->arrHistoricoProposta[0]['tx_impacto_projeto']){
            case 'I':
                $impacto = 'Interno';
                break;
            case 'E':
                $impacto = 'Interno';
                break;
            case 'A':
                $impacto = 'Interno e Externo';
                break;
            default:
                $impacto = $this->arrHistoricoProposta[0]['tx_impacto_projeto'];
                break;
        }
        $this->arrHistoricoProposta[0]['tx_impacto_projeto'] = $impacto;
        $this->metrica();
            
        $this->_gerarRelatorio();
    }

    protected function _gerarRelatorio()
    {
        $objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_HISTORICO_PROPOSTA'),
                             Base_Util::getTranslator('L_VIEW_HISTORICO'),
                             Base_Util::getTranslator('L_VIEW_PROPOSTA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_HISTORICO_PROPOSTA'), $arrKeywords);

        $objPdf->SetDisplayMode("real");

        // set font
        $objPdf->SetFont('helvetica', 'B', 10);

        // add a page
        $objPdf->AddPage();
        if(count($this->arrHistoricoProposta) >0 ) {
            foreach($this->arrHistoricoProposta as $row) {
                $objPdf->ln(0);

                //DADOS DO PROJETO
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(60, 6, mb_strtoupper(Base_Util::getTranslator('L_VIEW_DADOS_PROJETO')), 'B', 1);
                $objPdf->ln(5);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_SIGLA'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(180, 6, $row['tx_sigla_projeto'], '', 1, 'L');
                $objPdf->ln(5);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_NOME'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(180, 6, $row['tx_projeto'], '', 1, 'L');
                $objPdf->ln(5);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_CONTEXTO_GERAL'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->MultiCell(0,0,trim($row['tx_contexdo_geral']).PHP_EOL,'','J',0,1,null,null,null,null,true);
                $objPdf->ln(5);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_ESCOPO'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->MultiCell(0,0,trim($row['tx_escopo_projeto']).PHP_EOL,'','J',0,1,null,null,null,null,true);
                $objPdf->ln(5);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_UNIDADE_GESTORA'), '', 0, 'L');
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_GESTOR_PROJETO'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['tx_sigla_unidade'], '', 0, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['tx_gestor_projeto'], '', 1, 'L');
                $objPdf->ln(5);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_IMPACTO_PROJETO'), '', 0, 'L');
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_GERENTE_DO_PROJETO'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['tx_impacto_projeto'], '', 0, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['tx_gerente_projeto'], '', 1, 'L');
                $objPdf->ln(5);

                //DADOS DA PROPOSTA
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(60, 6, mb_strtoupper(Base_Util::getTranslator('L_VIEW_DADOS_PROPOSTA')), 'B', 1);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_NR_PROPOSTA'), '', 0, 'L');
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_SOLICITACAO'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['cd_proposta'], '', 0, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, "{$this->arrProposta[0]['ni_solicitacao']}/{$this->arrProposta[0]['ni_ano_solicitacao']}", '', 1, 'L');
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_INICIO_PREVISTO'), '', 0, 'L');
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_TERMINO_PREVISTO'), '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['tx_inicio_previsto'], '', 0, 'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90, 6, $row['tx_termino_previsto'], '', 1, 'L');
                $objPdf->ln(5);

                //CUSTO
                $this->dadosMetricas($objPdf);
                
                //CRONOGRAMA DE EXECUÇÃO
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(60, 6, mb_strtoupper(Base_Util::getTranslator('L_VIEW_CRONOGRAMA_EXECUCAO')), 'B', 1);
                $objPdf->ln(5);
                foreach($this->arrHistoricoPropostaParcela as $rowParcela) {
                    $objPdf->ln(0);
                    $objPdf->SetFont('helvetica', 'B', 8);
                    if (!is_null( $rowParcela['ni_mes_previsao_parcela'] ) && !is_null( $rowParcela['ni_ano_previsao_parcela'] )) {
                        $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PARCELA').' '.$rowParcela['ni_parcela'].' ('.Base_Util::getMes($rowParcela['ni_mes_previsao_parcela']).'/'.$rowParcela['ni_ano_previsao_parcela'].')', '', 1, 'L');
                    }else{
                        $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PARCELA').' '.$rowParcela['ni_parcela'], '', 1, 'L');
                    }
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PRODUTOS'), '', 1, 'L');
                    foreach($this->arrHistoricoPropostaProduto[$rowParcela['cd_historico_proposta_parcela']] as $rowProduto) {
                        $objPdf->SetFont('helvetica', '', 8);
                        $objPdf->Cell(180, 6, '- '.$rowProduto['tx_produto'], '', 1, 'L');
                    }
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_UNID_METRICA_SERVICOS_REALIZACAO_PRODUTO_PARCELA').': '.$rowParcela['ni_horas_parcela'], 'B', 1);
                    $objPdf->ln(5);
                }
                $objPdf->ln(5);
            }
        } else {
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        $objPdf->Cell(1, 6, '', 'B', 1);
        $objPdf->Output('relatorio_historico_proposta.pdf', 'I');
    }
    
    public function dadosMetricas($objPdf)
    {
        $objPdf->SetFont('helvetica', 'B', 10);
        $objPdf->Cell(40,6, mb_strtoupper(Base_Util::getTranslator('L_VIEW_CUSTO_PROJETO'), 'utf-8'),'B',1,'L');
        $objPdf->Ln(5);
        $w = array(10,50,160,20);
        if(count($this->arrMetrica) > 0){
            $valorTotalHoras = 0;
            $arrValorMetrica = array();
            $unidadeMetricaPadrao = "";
            foreach($this->arrMetrica as $key=>$value){
                if(array_key_exists("observacao", $value)){
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell($w[0],6,'','');
                    $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_OBSERVACAO_METRICA').":",'');
                    $objPdf->ln(4);
                    $objPdf->SetFont('helvetica', '', 8);
                    $objPdf->Cell($w[0],6,'','');
                    $objPdf->MultiCell($w[2], 6, $value['observacao'].PHP_EOL, '', 'J', '', 1);
                    continue;
                }
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(12,6, Base_Util::getTranslator('L_VIEW_METRICA').":",'');
                $objPdf->SetFont('helvetica', '', 8);
                if($value['st_metrica_padrao'] == 'S'){
                    $objPdf->Cell(165,6,$value['tx_nome_metrica']." (".Base_Util::getTranslator('L_VIEW_FORMULA_PADRAO').")",'',1);
                    $unidadeMetricaPadrao = $value['tx_sigla_unidade_metrica'];
                } else {
                    $objPdf->Cell(165,6,$value['tx_nome_metrica'],'',1);
                }

                foreach($this->arrMetrica[$key]['item_metrica'] as $keyMetrica=>$metrica){
                    if(is_null($metrica['st_interno_item_metrica'])){
                        $objPdf->SetFont('helvetica', 'B', 8);
                        $objPdf->Cell($w[0],6,'','');
                        $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_ITEM_METRICA').":",'');
                        $objPdf->ln(4);
                        $objPdf->SetFont('helvetica', '', 8);
                        $objPdf->Cell($w[0],6,'','');
                        $objPdf->MultiCell($w[2], 6, $metrica['tx_item_metrica'].PHP_EOL, '', 'J', '', 1);

                        //Condição que verifica se a métrica possui justificativa
                        if($value['st_justificativa_metrica'] == 'S'){
                            $objPdf->SetFont('helvetica', 'B', 8);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_JUSTIFICATIVA_METRICA').":",'');
                            $objPdf->ln(4);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->SetFont('helvetica', '', 8);
                            $objPdf->MultiCell($w[2],6,$value['tx_justificativa_metrica'].PHP_EOL,'','J','',1,'','',true,'',true);
                        }

                        $formulaTotalItemMetrica = $metrica['tx_formula_item_metrica'];
                        foreach($this->arrMetrica[$key]['item_metrica'][$keyMetrica]['sub_item'] as $keySubItem=>$subItem){
                            //Condição que verifica se o sub item da métrica não é interno
                            if(is_null($subItem['st_interno'])) {
                                $objPdf->SetFont('helvetica', 'B', 8);
                                $objPdf->Cell($w[3],6,'','');
                                $objPdf->Cell($w[1],6,$subItem['tx_sub_item_metrica'],'');
                                $objPdf->ln(4);
                                $objPdf->Cell($w[3],6,'','');
                                $objPdf->SetFont('helvetica', '', 8);
                                $objPdf->MultiCell($w[2],6,$subItem['tx_variavel_sub_item_metrica'].' = '.$subItem['ni_valor_sub_item_metrica'].PHP_EOL,'','J','',1);
                            }
                            $formulaTotalItemMetrica = str_ireplace($subItem['tx_variavel_sub_item_metrica'], $subItem['ni_valor_sub_item_metrica'], $formulaTotalItemMetrica);
                        }

                        $objPdf->SetFont('helvetica', 'B', 8);
                        $objPdf->Cell($w[3],6,'','');
                        $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_FORMULA_ITEM_METRICA').":",'');
                        $objPdf->ln(4);
                        $objPdf->Cell($w[3],6,'','');
                        $objPdf->SetFont('helvetica', '', 8);
                        $objPdf->MultiCell($w[2],6,$metrica['tx_formula_item_metrica']."\n",'','J','',1);

                        $objPdf->SetFont('helvetica', 'B', 8);
                        $objPdf->Cell($w[3],6,'','');
                        $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_CALCULO_FORMULA_ITEM_METRICA'),'');
                        $objPdf->ln(4);
                        $objPdf->Cell($w[3],6,'','');
                        $objPdf->SetFont('helvetica', '', 8);
                        $formulaTotalItemMetrica = str_ireplace(',', '.', $formulaTotalItemMetrica);
                        eval("\$calculoDaMetrica = $formulaTotalItemMetrica;");
                        $calculoDaMetrica = round($calculoDaMetrica,1);
                        $objPdf->MultiCell($w[2],6, $formulaTotalItemMetrica." = <b>".$calculoDaMetrica."</b>".PHP_EOL,'','J','',1,'','',true,'',true);

                        if($value['st_metrica_padrao'] == 'S'){
                            $objPdf->SetFont('helvetica', 'B', 8);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_VALOR_TOTAL_ITEM_METRICA', $value['tx_sigla_metrica']).":",'');
                            $objPdf->ln(4);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->SetFont('helvetica', '', 8);
                            $objPdf->Cell($w[1],6,$calculoDaMetrica,'',1);
                        } else {
                            $objPdf->SetFont('helvetica', 'B', 8);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_FORMULA_CALCULO_VARIAVEL', $value['tx_sigla_metrica']).":",'');
                            $objPdf->ln(4);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->SetFont('helvetica', '', 8);
                            $objPdf->Cell($w[1],6,$value['tx_sigla_metrica']." * ".Base_Util::getTranslator('L_VIEW_FATOR_RELACAO_METRICA_PADRAO'),'',1);

                            $objPdf->SetFont('helvetica', 'B', 8);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_VALOR_TOTAL_ITEM_METRICA', $value['tx_sigla_metrica']).":",'');
                            $objPdf->ln(4);
                            $objPdf->Cell($w[3],6,'','');
                            $objPdf->SetFont('helvetica', '', 8);
                            $objPdf->Cell($w[1],6,"{$calculoDaMetrica} * {$value['nf_fator_relacao_metrica_pad']} = ".round(($calculoDaMetrica*$value['nf_fator_relacao_metrica_pad']),1),'',1);
                            $calculoDaMetrica = round($calculoDaMetrica*$value['nf_fator_relacao_metrica_pad'],1);
                        }

                        //Criando um índece com a variavel da formula geral para o calculo final
                        $arrValorMetrica[$metrica['tx_variavel_item_metrica']] = $calculoDaMetrica;
                    } else {
                        $formulaTotalItemMetrica = $metrica['tx_formula_item_metrica'];
                        foreach($this->arrMetrica[$key]['item_metrica'][$keyMetrica]['sub_item'] as $keySubItem=>$subItem){
                            $formulaTotalItemMetrica = str_ireplace($subItem['tx_variavel_sub_item_metrica'], $subItem['ni_valor_sub_item_metrica'], $formulaTotalItemMetrica);
                        }
                        eval("\$calculoDaMetrica = $formulaTotalItemMetrica;");
                        //Criando um índece com a variavel da formula geral para o calculo final
                        $arrValorMetrica[$metrica['tx_variavel_item_metrica']] = $calculoDaMetrica;
                    }
                }//fim

                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(28,6, Base_Util::getTranslator('L_VIEW_FORMULA_METRICA').":",'');
                $objPdf->ln(4);
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(150,6,$value['tx_formula_metrica'],'',1,'L');

                $formuloTotalMetrica = $value['tx_formula_metrica'];
                foreach($arrValorMetrica as $variavelMetrica=>$valorMetrica){
                    $formuloTotalMetrica = str_ireplace($variavelMetrica, $valorMetrica, $formuloTotalMetrica);
                }
                eval("\$calculoDaFormulaDaMetrica = $formuloTotalMetrica;");
                $calculoDaFormulaDaMetrica = round($calculoDaFormulaDaMetrica,1);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(43,6, Base_Util::getTranslator('L_VIEW_CALCULO_FORMULA_METRICA').":",'');
                $objPdf->ln(4);
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->MultiCell(135,6,$formuloTotalMetrica." = <b>".$calculoDaFormulaDaMetrica."</b>".PHP_EOL,'','J','',1,'','',true,'',true);

                $valorTotalHoras += $calculoDaFormulaDaMetrica;
            }
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_VALOR_TOTAL_METRICA_VARIAVEL', $unidadeMetricaPadrao).":",'');
            $objPdf->ln(4);
            $objPdf->SetFont('helvetica', '', 8);
            $objPdf->Cell($w[1],6,($valorTotalHoras == 0)?"0":$valorTotalHoras,'',1);
        } else {
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell($w[3],6,'','');
            $objPdf->Cell($w[1],6, Base_Util::getTranslator('L_MSG_ALERT_CONTRATO_PROJETO_VINCULADO_SEM_METRICA_PADRAO_DEFINIDA'),'');
        }
        $objPdf->Ln(5);
        $objPdf->Cell(180,0,"",'B',1,'L');
        $objPdf->Ln(5);
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

        $arrSiglaMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica( $this->cd_contrato );

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
                                                          ->where("cd_contrato          = ?", $this->cd_contrato, Zend_Db::INT_TYPE)
                                                          ->where("cd_definicao_metrica = ?", $key, Zend_Db::INT_TYPE)
                                                )->toArray();
                 if(count($arrContratoDefinicaoMetrica) > 0){
                    $arrContratoDefinicaoMetrica = $objContratoDefinicaoMetrica->find($this->cd_contrato,$key)->current();
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
		$this->arrAux = $this->objHistoricoPropostaMetrica->getHistoricoPropostaMetrica($this->cd_projeto, $this->cd_proposta, $this->dt_historico_proposta);
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
				$this->arrMetrica[$value['cd_definicao_metrica']]['tx_justificativa_metrica' ] = $value['tx_just_metrica_historico'];
				$this->arrMetrica[$value['cd_definicao_metrica']]['ni_horas_proposta_metrica'] = $value['ni_um_prop_metrica_historico'];
			} else {
				$this->arrMetrica[$value['cd_definicao_metrica']]['ni_horas_proposta_metrica'] = $value['ni_um_prop_metrica_historico'];
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
		$this->arrHistoricoPropostaSubItemMetrica = $this->objHistoricoPropostaSubItemMetrica->getHistoricoPropostaSubItemMetrica($this->cd_projeto, $this->cd_proposta, $this->dt_historico_proposta, $this->arrParam['cd_definicao_metrica'], $this->arrParam['cd_item_metrica'], $this->arrParam['cd_sub_item_metrica']);
        
		$this->arrMetrica[$this->arrParam['cd_definicao_metrica']]
						 ['item_metrica']
						 [$this->arrParam['cd_item_metrica']]
						 ['sub_item']
						 [$this->arrParam['cd_sub_item_metrica']]
						 ['ni_valor_sub_item_metrica'] =
						 (count($this->arrHistoricoPropostaSubItemMetrica)>0)?
						 (!empty($this->arrHistoricoPropostaSubItemMetrica[0]['ni_valor_sub_item_metrica']))?$this->arrHistoricoPropostaSubItemMetrica[0]['ni_valor_sub_item_metrica']:0:0;
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
}

