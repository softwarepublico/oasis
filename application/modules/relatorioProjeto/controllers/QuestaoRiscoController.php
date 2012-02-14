<?php
class RelatorioProjeto_QuestaoRiscoController extends Base_Controller_Action
{
    private $_objObjeto;
    private $_objEtapa;
    private $_objAtividade;
    private $_objQuestaoRisco;
    
    public function init()
    {
        parent::init();
        $this->_objObjeto       = new ObjetoContrato();
        $this->_objEtapa        = new Etapa();
        $this->_objAtividade    = new Atividade();
        $this->_objQuestaoRisco = new RelatorioProjetoQuestaoRisco();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_QUESTAO_RISCO'));

        $cd_contrato = null;
        $comStatus	 = true;
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
        $this->view->arrObjeto = $this->_objObjeto->getObjetoContratoAtivo('P', true, false, true);
    }

 	public function montaComboEtapaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_objeto    = $this->_request->getParam('cd_objeto');
		
		$selected = "";
		$cd_etapa = 0;
		if($cd_objeto){
			$arrDados = $this->_objEtapa->getEtapa($cd_objeto,true);
			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_etapa == $key){
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

    public function montaComboAtividadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_etapa = $this->_request->getParam("cd_etapa");

		$arrAtividade = $this->_objAtividade->getAtividade($cd_etapa,true);
		$strOption = "";
		if(count($arrAtividade) > 0){
			foreach($arrAtividade as $key=>$value){
				$strOption .= "<option value=\"{$key}\" label=\"{$value}\">{$value}</option>";
			}
		}
		echo $strOption;
	}

    public function relatorioQuestaoRiscoAction()
    {
 		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $arrPost = $this->_request->getPost();

        $arrDados = $this->_objQuestaoRisco->questaoRisco($arrPost);

        $arrQuestaoRisco = array();
        $cd_objeto                = 0;
        $cd_etapa                 = 0;
        $cd_atividade             = 0;
        $cd_item_risco            = 0;
        $cd_questao_analise_risco = 0;

        foreach($arrDados as $value){

            if($value['cd_objeto'] != $cd_objeto){
                $arrQuestaoRisco[$value['cd_objeto']]['tx_objeto']          = $value['tx_objeto'];
                $arrQuestaoRisco[$value['cd_objeto']]['tx_numero_contrato'] = $value['tx_numero_contrato'];
            }
            if($value['cd_etapa'] != $cd_etapa){
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']]['tx_etapa'] = $value['tx_etapa'];
            }
            if($value['cd_atividade'] != $cd_atividade){
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']]['tx_atividade'] = $value['tx_atividade'];
            }
//            else {
//                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']]['tx_atividade'] = "";
//            }
            if($value['cd_item_risco'] != $cd_item_risco){
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']][$value['cd_item_risco']]['tx_item_risco'] = $value['tx_item_risco'];
            }
//            else {
//                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']][$value['cd_item_risco']]['tx_item_risco'] = "";
//            }
            if($value['cd_questao_analise_risco'] != $cd_questao_analise_risco){
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']][$value['cd_item_risco']][$value['cd_questao_analise_risco']]['tx_questao_analise_risco']      = $value['tx_questao_analise_risco'];
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']][$value['cd_item_risco']][$value['cd_questao_analise_risco']]['ni_peso_questao_analise_risco'] = $value['ni_peso_questao_analise_risco'];
            } else {
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']][$value['cd_item_risco']][$value['cd_questao_analise_risco']]['tx_questao_analise_risco']      = "";
                $arrQuestaoRisco[$value['cd_objeto']][$value['cd_etapa']][$value['cd_atividade']][$value['cd_item_risco']][$value['cd_questao_analise_risco']]['ni_peso_questao_analise_risco'] = "";
            }

            $cd_objeto                = $value['cd_objeto'];
            $cd_etapa                 = $value['cd_etapa'];
            $cd_atividade             = $value['cd_atividade'];
            $cd_item_risco            = $value['cd_item_risco'];
            $cd_questao_analise_risco = $value['cd_questao_analise_risco'];
        }

        $this->generate($arrQuestaoRisco);
    }

    private function generate(array $arrDados)
    {
        $objPdf = new Base_Tcpdf_Pdf();

		$objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_QUESTAO_RISCO'), K_CREATOR_SYSTEM.', '.
                                                                                      Base_Util::getTranslator('L_TIT_REL_QUESTAO_RISCO').', '.
                                                                                      Base_Util::getTranslator('L_VIEW_QUESTAO').', '.
                                                                                      Base_Util::getTranslator('L_VIEW_RISCO'));
		$objPdf->SetDisplayMode("real");

		// add a page
		$objPdf->AddPage();

		$w = array(100,14);
        foreach($arrDados as $objeto=>$arrObjeto){
   			$objPdf->SetFont('helvetica','B',8);
			$objPdf->Cell($w[1], 6,Base_Util::getTranslator('L_VIEW_CONTRATO').":",'',0);
			$objPdf->SetFont('helvetica','',8);
			$objPdf->Cell($w[0], 6,$arrObjeto['tx_numero_contrato'],'',0);
			$objPdf->ln(5);
            
   			$objPdf->SetFont('helvetica','B',8);
			$objPdf->Cell($w[1], 6,Base_Util::getTranslator('L_VIEW_OBJETO').":",'',0);
			$objPdf->SetFont('helvetica','',8);
			$objPdf->Cell($w[0], 6,$arrObjeto['tx_objeto'],'',0);
            
			$objPdf->ln(5);

            unset($arrObjeto['tx_objeto']);
            unset($arrObjeto['tx_numero_contrato']);
            foreach($arrObjeto as $etapa=>$arrEtapa){
                $objPdf->SetFont('helvetica','B',8);
                $objPdf->Cell($w[1], 6,Base_Util::getTranslator('L_VIEW_ETAPA').":",'',0);
                $objPdf->SetFont('helvetica','',8);
                $objPdf->Cell($w[0], 6,$arrEtapa['tx_etapa'],'',0);
                $objPdf->ln(5);
                
                unset($arrEtapa['tx_etapa']);
                foreach($arrEtapa as $atividade=>$arrAtividade){
                    $objPdf->SetFont('helvetica','B',8);
                    $objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_ATIVIDADE').":",'',0);
                    $objPdf->SetFont('helvetica','',8);
                    $objPdf->Cell($w[0], 6,$arrAtividade['tx_atividade'],'',0);
                    $objPdf->ln(7);

                    $strTable  = "";
                    $strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
                    $strTable .= '<thead>';
                        $strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" style="text-align:center;">';
                            $strTable .= '<th style="font-weight:bold; width:180px;">'.Base_Util::getTranslator('L_VIEW_ITEM_RISCO').'</th>';
                            $strTable .= '<th style="font-weight:bold; width:284px;">'.Base_Util::getTranslator('L_VIEW_QUESTAO_RISCO').'</th>';
                            $strTable .= '<th style="font-weight:bold; width:50px;">'. Base_Util::getTranslator('L_VIEW_PESO').'</th>';
                        $strTable .= '</tr>';
                    $strTable .= '</thead>';
                    $strTable .= '<tbody>';

                    unset($arrAtividade['tx_atividade']);
                    foreach($arrAtividade as $itemRisco=>$arrItemRisco){
                        $strTable .= '<tr>';
                        $strTable .= '<td style="width:180px;">';
                        $strTable .= $arrItemRisco['tx_item_risco'];
                        $strTable .= '</td>';
                        unset($arrItemRisco['tx_item_risco']);
                        foreach($arrItemRisco as $questao=>$arrQuestao){
                                $strTable .= '<td style="width:284px;">'.$arrQuestao['tx_questao_analise_risco'].'</td>';
                                $strTable .= '<td style="width:50px;">' .$arrQuestao['ni_peso_questao_analise_risco'].'</td>';
                            $strTable .= '</tr>';
                            $strTable .= '<tr>';
                            $strTable .= '<td style="width:180px;">&nbsp;</td>';
                        }
                        $strTable = substr($strTable,0, -40);
                    }
                    $strTable .= '</tbody>';
                    $strTable .= '</table>';
                    $objPdf->writeHTML($strTable,true, 0, true, 0);
                }
            }
        }
        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_situacao.pdf', 'I');
    }
}