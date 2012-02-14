<?php
class RelatorioDemanda_CustoContratoTipoDemandaController extends Base_Controller_Action
{
    private $_objContrato;

    public function init()
    {
        parent::init();
        $this->_objContrato = new Contrato();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CUSTO_CONTRATO_TIPO_DEMANDA'));
        $this->view->arrContrato = $this->_comboContrato();
    }

    private function _comboContrato()
    {
        $arrContratoDemanda = $this->_objContrato->getContratoAtivoObjeto(true, 'D');
        $arrContratoServico = $this->_objContrato->getContratoAtivoObjeto(false, 'S');

        foreach($arrContratoDemanda as $key => $value) {
            $arrContratosTipoDemandaServico[$key] = $value;
        }

        foreach($arrContratoServico as $key => $value) {
            $arrContratosTipoDemandaServico[$key] = $value;
        }

        return $arrContratosTipoDemandaServico;
    }

    public function custoContratoTipoDemandaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $objCustoContratoDemanda = new RelatorioCustoContratoDemanda();

        $arrParam = $this->_request->getPost();

        $objDadosContrato  = $objCustoContratoDemanda->getDadosContrato(array('cont.cd_contrato = ?'=>$arrParam['cd_contrato']));

        if((empty($arrParam['dt_inicial'])) && (empty($arrParam['dt_final']))) {
            $arrParam['dt_inicial'] = $objDadosContrato->getRow(0)->ni_ano_inicial_contrato.substr('00'.$objDadosContrato->getRow(0)->ni_mes_inicial_contrato,-2);
            $arrParam['dt_final']   = $objDadosContrato->getRow(0)->ni_ano_final_contrato.substr('00'.$objDadosContrato->getRow(0)->ni_mes_final_contrato,-2);
        } else {
        //Trantando a data inicial
            $arrDataInicial         = explode("/", $arrParam['dt_inicial']);
            $arrParam['dt_inicial'] = $arrDataInicial[2].$arrDataInicial[1];

            //Trantando a data final
            $arrDataFinal           = explode("/", $arrParam['dt_final']);
            $arrParam['dt_final']   = $arrDataFinal[2].$arrDataFinal[1];
        }
        $arrWhere['ccd.cd_contrato = ?'] = $arrParam['cd_contrato'];
        $arrWhere["(ni_ano_custo_contrato_demanda*100)+ni_mes_custo_contrato_demanda between {$arrParam['dt_inicial']} and {$arrParam['dt_final']}"] = '';
        $objCustoContrato = $objCustoContratoDemanda->getDadosContratoPeriodo($arrWhere);

        $this->_geraRelatorio($objDadosContrato, $objCustoContrato);
    }

    private function _geraRelatorio($objDadosContrato, $objCustoContrato)
    {
        $objPdf  = new Base_Tcpdf_Pdf();
        $objUtil = new Base_Controller_Action_Helper_Util();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_CUSTO_CONTRATO_TIPO_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_CUSTO_CONTRATO_TIPO_DEMANDA'), $arrKeywords);

        $objPdf->SetDisplayMode("real");
        // add a page
        $objPdf->AddPage();
        $w = array(100,30);
        if($objDadosContrato->valid()) {
            $objPdf->SetFont('helvetica','B',8);
            $objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_CONTRATO'),'',0);
            $objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_VALOR_CONTRATO'),'',0);
            $objPdf->Cell(100, 6,Base_Util::getTranslator('L_VIEW_PERIODO_CONTRATO'),'',0);
            $objPdf->ln(4);

            $objPdf->SetFont('helvetica','',8);
            $objPdf->Cell(50, 6,$objDadosContrato->getRow(0)->tx_numero_contrato,'',0);
            $objPdf->Cell(50, 6,number_format($objDadosContrato->getRow(0)->nf_valor_contrato,2,',','.'),'',0);
            $data_inicio = "{$objUtil->getMesRes($objDadosContrato->getRow(0)->ni_mes_inicial_contrato)}/{$objDadosContrato->getRow(0)->ni_ano_inicial_contrato}";
            $data_final  = "{$objUtil->getMesRes($objDadosContrato->getRow(0)->ni_mes_final_contrato)}/{$objDadosContrato->getRow(0)->ni_ano_final_contrato}";
            $objPdf->Cell(100, 6,"{$data_inicio} ". Base_Util::getTranslator('L_VIEW_A') ." {$data_final} ",'',0);
            $objPdf->ln(5);
            $objPdf->ln(5);
            if($objCustoContrato->valid()) {
                foreach($objCustoContrato as $key=>$value) {
                    $data = "{$objUtil->getMesRes($value->ni_mes_custo_contrato_demanda)}/{$value->ni_ano_custo_contrato_demanda}";
                    $objPdf->SetFont('helvetica','B',8);
                    $objPdf->Cell($w[1], 6,$data,'',0);
                    $objPdf->ln(5);

                    $strTable  = "";
                    $strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
                    $strTable .= '<thead>';
                    $strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" style="text-align:center;">';
                    $strTable .= '<th style="font-weight:bold; width:100px;">'.Base_Util::getTranslator('L_VIEW_MULTA').'</th>';
                    $strTable .= '<th style="font-weight:bold; width:100px;">'.Base_Util::getTranslator('L_VIEW_GLOSAS').'</th>';
                    $strTable .= '<th style="font-weight:bold; width:100px;">'.Base_Util::getTranslator('L_VIEW_VALOR_PAGO').'</th>';
                    $strTable .= '</tr>';
                    $strTable .= '</thead>';
                    $strTable .= '<tbody>';
                        $strTable .= '<tr>';
                            $strTable .= '<td style="width:100px;">'.number_format($value->nf_total_multa,2,',','.').'</td>';
                            $strTable .= '<td style="width:100px;">'.number_format($value->nf_total_glosa,2,',','.').'</td>';
                            $strTable .= '<td style="width:100px;">'.number_format($value->nf_total_pago,2,',','.').'</td>';
                        $strTable .= '</tr>';
                    $strTable .= '</tbody>';
                    $strTable .= '</table>';
                    $objPdf->writeHTML($strTable,true, 0, true, 0);
                }
            }
        } else {
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
        //Close and output PDF document
        $objPdf->Output('relatorio_custo_contrato_demanda.pdf', 'I');
    }
}