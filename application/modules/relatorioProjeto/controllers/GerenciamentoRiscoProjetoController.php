<?php
class RelatorioProjeto_GerenciamentoRiscoProjetoController extends Base_Controller_Action {
    private $_objetoContrato;
    private $_tx_impacto;
    private $_tx_projeto;
    private $_cd_proposta;

    private $_objContrato;
    private $_objContratoProjeto;
    private $_objObjetoContrato;
    private $_objEtapa;
    private $_objAtividade;
    private $_objRelGerenciamentoRisco;
    private $_objAnaliseRisco;
    private $_objItemRisco;

    public function init() {
        parent::init();
        $this->_objContrato              = new Contrato();
        $this->_objContratoProjeto       = new ContratoProjeto();
        $this->_objObjetoContrato        = new ObjetoContrato();
        $this->_objEtapa                 = new Etapa();
        $this->_objAtividade             = new Atividade();
        $this->_objRelGerenciamentoRisco = new RelatorioGerenciamentoRiscoProjeto();
        $this->_objAnaliseRisco          = new AnaliseRisco();
        $this->_objItemRisco             = new ItemRisco();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_GERENCIAMENTO_RISCO'));

        if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ) {
            $cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
            $comStatus	 = false;
        }

        $this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }

    public function comboProjetoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
        $arrProjetos = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true);

        $options = '';
        foreach( $arrProjetos as $key=>$value) {
            $options .= "<option value=\"{$key}\">{$value}</option>";
        }
        echo $options;
    }

    public function comboEtapaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $cd_contrato = $this->_request->getParam('cd_contrato');
        $cd_objeto   = $this->_getObjeto($cd_contrato);

        $selected = "";
        $cd_etapa = 0;
        if($cd_objeto) {
            $arrDados = $this->_objEtapa->getEtapa($cd_objeto,true);
            if(count($arrDados) > 0) {
                $strOptions = "";
                foreach($arrDados as $key=>$conteudo) {
                    if($cd_etapa == $key) {
                        $strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
                    } else {
                        $strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
                    }
                }
            } else {
                $strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
            }
        } else {
            $strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
        }
        echo $strOptions;
    }

    public function comboAtividadeAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $cd_etapa = $this->_request->getParam("cd_etapa");

        $arrAtividade = $this->_objAtividade->getAtividade($cd_etapa,true);
        $strOption = "";
        if(count($arrAtividade) > 0) {
            foreach($arrAtividade as $key=>$value) {
                $strOption .= "<option value=\"{$key}\" label=\"{$value}\">{$value}</option>";
            }
        }
        echo $strOption;
    }

    public function comboPropostaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);

        $proposta = new Proposta();
        $res = $proposta->getProposta($cd_projeto);

        $strOptions = "";
        foreach ($res as $key => $value) {
            $strOptions .= "<option value=\"{$key}\">{$value}</option>";
        }

        echo $strOptions;
    }

    private function _getObjeto($cd_contrato)
    {
        $cd_objeto = 0;
        $arrObjeto = $this->_objObjetoContrato->getDadosObjetoContrato($cd_contrato);

        if(count($arrObjeto) > 0) {
            $cd_objeto = $arrObjeto[0]['cd_objeto'];
        }

        return $cd_objeto;
    }

    public function riscoProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $arrParam['ar.cd_projeto = ?']   = (int)$this->_request->getParam('cd_projeto');
        $arrParam['ar.cd_proposta = ?']  = (int)$this->_request->getParam('cd_proposta');
        $arrParam['ar.cd_etapa = ?']     = (int)($this->_request->getParam('cd_etapa') != 0)?$this->_request->getParam('cd_etapa'):null;
        $arrParam['ar.cd_atividade = ?'] = (int)($this->_request->getParam('cd_atividade') != 0)?$this->_request->getParam('cd_atividade'):null;

        $st_impacto_risco      = $this->_request->getParam('st_impacto_risco');
        $this->_objetoContrato = $this->_request->getParam('tx_contrato_objeto');
        $this->_tx_impacto     = $this->_request->getParam('tx_impacto');
        $this->_tx_projeto     = $this->_request->getParam('tx_projeto');
        $this->_cd_proposta    = $this->_request->getParam('cd_proposta');

        $objRiscoProjeto = $this->_objRelGerenciamentoRisco->getAnaliseRisco($arrParam);

        if($objRiscoProjeto->valid()) {
            $arrRiscoProjeto = $this->_trataDadosRisco($objRiscoProjeto,$st_impacto_risco);
            $this->_gerarRelatorio($arrRiscoProjeto);
        } else {
            $this->_gerarRelatorio();
        }
    }

    protected function _trataDadosRisco($objRiscoProjeto, $st_impacto_risco)
    {
        $cd_etapa = $cd_atividade = $cd_item_risco = 0;
        $arrDados = array();

        $arrDados['tx_sigla_projeto'] = $objRiscoProjeto->getRow(0)->tx_sigla_projeto;
        $arrDados['cd_proposta']      = $objRiscoProjeto->getRow(0)->cd_proposta;
        $cd_projeto                   = $objRiscoProjeto->getRow(0)->cd_projeto;
        $cd_proposta                  = $objRiscoProjeto->getRow(0)->cd_proposta;

        foreach($objRiscoProjeto as $key=>$value) {
            if($cd_etapa != $value->cd_etapa) {
                $arrRiscoEtapa = $this->_getCoresRisco($cd_projeto, $cd_proposta, $st_impacto_risco, $value->cd_etapa);
                $arrRiscoEtapa['tx_etapa'] = $value->tx_etapa;
                $arrDados[$value->cd_etapa] = $arrRiscoEtapa;
            }
            if($cd_atividade != $value->cd_atividade) {
                $arrRiscoAtividade = $this->_getCoresRisco($cd_projeto, $cd_proposta, $st_impacto_risco, $value->cd_etapa, $value->cd_atividade);
                $arrRiscoAtividade['tx_atividade'] = $value->tx_atividade;
                $arrDados[$value->cd_etapa][$value->cd_atividade] = $arrRiscoAtividade;
            }
            if($cd_item_risco != $value->cd_item_risco) {
                $arrDados[$value->cd_etapa][$value->cd_atividade][$value->cd_item_risco] = array('tx_item_risco'=>$value->tx_item_risco);

                $arrCorRisco = $this->_objAnaliseRisco->fetchAll(
                        $this->_objAnaliseRisco->select()
                                ->from($this->_objAnaliseRisco, $st_impacto_risco)
                                ->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
                                ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                                ->where("cd_etapa = ?",$value->cd_etapa, Zend_Db::INT_TYPE)
                                ->where("cd_atividade = ?",$value->cd_atividade, Zend_Db::INT_TYPE)
                                ->where("cd_item_risco = ?",$value->cd_item_risco, Zend_Db::INT_TYPE)
                )->toArray();
				if(count($arrCorRisco) > 0){
                    $arrDados[$value->cd_etapa][$value->cd_atividade][$value->cd_item_risco][trim($arrCorRisco[0][$st_impacto_risco])] = 100;
				} else {
                    $arrDados[$value->cd_etapa][$value->cd_atividade][$value->cd_item_risco]["riscoBranco"] = 100;
				}
            }

            $cd_etapa      = $value->cd_etapa;
            $cd_atividade  = $value->cd_atividade;
            $cd_item_risco = $value->cd_item_risco;
        }

        return $arrDados;
    }

    private function _getCoresRisco($cd_projeto, $cd_proposta, $st_impacto, $cd_etapa = null, $cd_atividade = null, $cd_item_risco = null)
    {
        $arrDadosRisco = $this->_objAnaliseRisco->getQtdImpacto($cd_projeto,$cd_proposta,$cd_etapa,$cd_atividade,null,$st_impacto);
        $totalItem     = $this->_objItemRisco->getQtdItem($cd_etapa, $cd_atividade);

        if($totalItem != 0) {
            $riscoBranco   = 0;
            $riscoVerde    = 0;
            $riscoAmarelo  = 0;
            $riscoVermelho = 0;
            $riscoCinza    = 0;

            if(count($arrDadosRisco) > 0) {
                foreach($arrDadosRisco as $chave=>$valor) {
                    $corRisco  = trim($valor[$st_impacto]);
                    $$corRisco = $valor["count"];
                }
            }

            $riscoBranco = ($totalItem - ($riscoVerde + $riscoVermelho + $riscoBranco + $riscoAmarelo + $riscoCinza)) + $riscoBranco;

            $tamanho                     = ($totalItem != 0)?(100/$totalItem):0;
            $arrRisco['riscoBranco']     = ($tamanho!=0)?round($riscoBranco*$tamanho):250;
            $arrRisco['riscoVerde']      = round($riscoVerde*$tamanho);
            $arrRisco['riscoAmarelo']    = round($riscoAmarelo*$tamanho);
            $arrRisco['riscoVermelho']   = round($riscoVermelho*$tamanho);
            $arrRisco['riscoCinza']      = round($riscoCinza*$tamanho);
//            $arrRisco['tx_riscoBranco']  = "";
//            if($tamanho != 0) {
//                $arrRisco['tamanho']         = $arrEtapas['riscoBranco']+$arrEtapas['riscoVerde']+$arrEtapas['riscoAmarelo']+$arrEtapas['riscoVermelho']+$arrEtapas['riscoCinza'];
//            } else {
//                $arrRisco['tamanho'] = 170;
//            }
        } else {
            $arrRisco['riscoBranco']     = 100;
            $arrRisco['riscoVerde']      = 0;
            $arrRisco['riscoAmarelo']    = 0;
            $arrRisco['riscoVermelho']   = 0;
            $arrRisco['riscoCinza']      = 0;
//            $arrRisco['tx_riscoBranco']  = "Não exite item de risco cadastrado";  
//            $arrRisco['tamanho']         = 170;
        }
        
        return $arrRisco;
    }

    private function _gerarRelatorio(Array $arrRiscoProjeto = array())
    {
        $objPdf  = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_GERENCIAMENTO_RISCO'), K_CREATOR_SYSTEM.", ".
                                                                                            Base_Util::getTranslator('L_VIEW_RISCO').", " .
                                                                                            Base_Util::getTranslator('L_VIEW_PROJETO'));

        $objPdf->SetDisplayMode("real");

        // add a page
        $objPdf->AddPage();
        $w = array(100,30);
        $objPdf->SetFont('helvetica','B',8);
        $objPdf->Cell(14, 6,Base_Util::getTranslator('L_VIEW_CONTRATO').':','',0,'R');
        $objPdf->SetFont('helvetica','',8);
        $objPdf->Cell(50, 6,$this->_objetoContrato,'',0,'L');
        $objPdf->ln(4);

        $objPdf->SetFont('helvetica','B',8);
        $objPdf->Cell(14, 6,Base_Util::getTranslator('L_VIEW_PROJETO').':','',0,'R');
        $objPdf->SetFont('helvetica','',8);
        $objPdf->Cell(70, 6,$this->_tx_projeto,'',0,'L');
        $objPdf->SetFont('helvetica','B',8);
        $objPdf->Cell(14, 6,Base_Util::getTranslator('L_VIEW_NR_PROPOSTA').':','',0,'R');
        $objPdf->SetFont('helvetica','',8);
        $objPdf->Cell(70, 6,$this->_cd_proposta,'',0,'L');
        $objPdf->ln(4);
        $objPdf->SetFont('helvetica','B',8);
        $objPdf->Cell(14, 6,Base_Util::getTranslator('L_VIEW_IMPACTO').':','',0,'R');
        $objPdf->SetFont('helvetica','',8);
        $objPdf->Cell(70, 6,$this->_tx_impacto,'',1,'L');
        $objPdf->ln(4);

        if(count($arrRiscoProjeto) > 0){
            $strHtml  = "";
            $strHtml .= '<div style="font-family:helvetica; font-size:25px; float:left; width:100px;">';
            $strHtml .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC">';
            $strHtml .= '<tbody>';

            unset($arrRiscoProjeto['tx_sigla_projeto']);
            unset($arrRiscoProjeto['cd_proposta']);
            foreach($arrRiscoProjeto as $cd_etapa=>$arrEtapa) {
                $tx_etapa = $arrEtapa['tx_etapa'];
                unset($arrEtapa['tx_etapa']);

                $strHtml .= '<tr>';
                $strHtml .= '    <td style="font-weight:bold; text-align:left; width:50px;">'.Base_Util::getTranslator('L_VIEW_ETAPA').':</td>';
                $strHtml .= '    <td style="text-align:left; width:250px;">'.$tx_etapa.'</td>';
                $strHtml .= '    <td style="text-align:left;">'.$this->_tableCorRisco($arrEtapa).'</td>';
                $strHtml .= '</tr>';

                foreach($arrEtapa as $cd_atividade=>$arrAtividade) {
                    $tx_atividade = $arrAtividade['tx_atividade'];
                    unset($arrAtividade['tx_atividade']);

                    $strHtml .= '<tr>';
                    $strHtml .= '    <td style="font-weight:bold; text-align:left; width:50px;">'.Base_Util::getTranslator('L_VIEW_ATIVIDADE').':</td>';
                    $strHtml .= '    <td style="text-align:left; width:250px;">'.$tx_atividade.'</td>';
                    $strHtml .= '    <td style="text-align:left;">'.$this->_tableCorRisco($arrAtividade).'</td>';
                    $strHtml .= '</tr>';

                    //Monata uma nova tabela para os itens de risco
                    $strHtml .= '<tr>';
                    $strHtml .= '    <table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC">';
                    $strHtml .= '    <tbody>';
                    $strHtml .= '    <tr><td colspan="2" style="font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_ITEM_RISCO').':</td></tr>';

                    $countItemRisco = 0;
                    $strColunaItem  = "";
                    $strColunaRisco = "";
                    foreach($arrAtividade as $cd_item_risco=>$arrItemRisco) {
                        $tx_item_risco = $arrItemRisco['tx_item_risco'];
                        unset($arrItemRisco['tx_item_risco']);
                        if($countItemRisco == 0) {
                            $strColunaItem  .= '<tr>';
                            $strColunaRisco .= '<tr>';
                        }
                        $strColunaItem  .= '<td><i>'.$tx_item_risco.'</i></td>';
                        $strColunaRisco .= '<td>'.$this->_tableCorRisco($arrItemRisco).'</td>';

                        $countItemRisco++;
                        if($countItemRisco == 2) {
                            $strColunaItem  .= '</tr>';
                            $strColunaRisco .= '</tr>';

                            $countItemRisco = 0;
                            $strHtml .= $strColunaItem . $strColunaRisco;

                            $strColunaItem  = "";
                            $strColunaRisco = "";
                        }
                    }
                    if($countItemRisco == 1) {
                        $strColunaItem  .= '</tr>';
                        $strColunaRisco .= '</tr>';

                        $strHtml .= $strColunaItem . $strColunaRisco;
                    }
                    $strHtml .= '<tr>';
                    $strHtml .= '    <td colspan="3"></td>';
                    $strHtml .= '</tr>';

                    $strHtml .= '    </tbody>';
                    $strHtml .= '    </table>';
                    $strHtml .= '</tr>';
                }
            }
            $strHtml .= '</div>';
            $objPdf->writeHTML($strHtml,true, 0, true, 0);
        } else {
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }

        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');

        //Close and output PDF document
        $objPdf->Output('relatorio_gerenciamento_risco.pdf', 'I');
    }

    private function _tableCorRisco(Array &$arrCorRisco) {

        $tableRisco = '<table cellpadding="0" cellspacing="0" border="1" width="180px;">
                <tbody>
                  <tr style="text-align:left;">
                    <td bgcolor="#FFFFFF" width="'.$arrCorRisco['riscoBranco'].'.001%"></td>
                    <td bgcolor="#00af4d" width="'.$arrCorRisco['riscoVerde'].'.001%"></td>
                    <td bgcolor="#fbe200" width="'.$arrCorRisco['riscoAmarelo'].'.001%"></td>
                    <td bgcolor="#e70033" width="'.$arrCorRisco['riscoVermelho'].'.001%"></td>
                    <td bgcolor="#d0cfcb" width="'.$arrCorRisco['riscoCinza'].'.001%"></td>
                  </tr>
                </tbody>
                </table>';

        if(array_key_exists('riscoBranco', $arrCorRisco)){
            unset($arrCorRisco['riscoBranco']);
        }
        if(array_key_exists('riscoVerde', $arrCorRisco)){
            unset($arrCorRisco['riscoVerde']);
        }
        if(array_key_exists('riscoAmarelo', $arrCorRisco)){
            unset($arrCorRisco['riscoAmarelo']);
        }
        if(array_key_exists('riscoVermelho', $arrCorRisco)){
            unset($arrCorRisco['riscoVermelho']);
        }
        if(array_key_exists('riscoCinza', $arrCorRisco)){
            unset($arrCorRisco['riscoCinza']);
        }

        return $tableRisco;
    }
}