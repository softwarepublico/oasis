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

class RelatorioProjeto_RegistroHistoricoController extends Base_Controller_Action 
{
    private $_arrHistorico;
    private $_arrPeriodo;
    private $_objContrato;

    public function init()
    {
        parent::init();
        $this->_objContrato = new Contrato($this->_request->getControllerName());
    }

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_REGISTRO_ATIVIDADE'));

        $cd_contrato 			 = null;
        $comStatus				 = true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
        $this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
		$objRegistroHistorico		 = new RelatorioProjetoRegistroHistorico();
		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		
		$arrPost = $this->_request->getPost();

		$arrParam['mes'  ] = $arrPost['mes'];
		$arrParam['ano'  ] = $arrPost['ano'];
		$this->_arrPeriodo = $arrParam;

        //adiciona o contrato ao array de parametros
		$arrParam['cd_contrato'] = $arrPost['cd_contrato'];
        //verifica quantos dias exitem no mes/ano
		$diasMes                 = cal_days_in_month(CAL_GREGORIAN, $arrParam['mes'], $arrParam['ano']);
        //recupera os projetos de acordo com os parametros informados
		$rowSetProjetos             = $objRegistroHistorico->getProjetoMensal($arrParam);

        //converte as datas para o padrão do banco para não usar to_date
		$arrParam['dtMesInicio'] = Base_Util::converterDate("01/".substr("00".$arrParam['mes'],-2)."/".$arrParam['ano'], 'DD/MM/YYYY', 'YYYY-MM-DD');
		$arrParam['dtMesFim'   ] = Base_Util::converterDate("{$diasMes}/".substr("00".$arrParam['mes'],-2)."/".$arrParam['ano'], 'DD/MM/YYYY', 'YYYY-MM-DD');

        //exclui do array de parametros os indices que não serão utilizado na proxima consulta
        unset($arrParam['ano']);
        unset($arrParam['mes']);
        unset($arrParam['cd_contrato']);

		$arrHistorico = array();
		if(count($rowSetProjetos) > 0){
			$cd_projeto_aux = 0;
			foreach($rowSetProjetos as $key=>$value){
				if($cd_projeto_aux != $value->cd_projeto){
					$arrHistorico[$value->cd_projeto]['cd_projeto'] = $value->cd_projeto;
					$arrHistorico[$value->cd_projeto]['tx_projeto'] = $value->tx_sigla_projeto;
				}
				
				$arrParam['cd_projeto' ] = $value->cd_projeto;
				$arrParam['cd_proposta'] = $value->cd_proposta;
				
				$arrSiglaMetricaPadrao	= $objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($value->cd_projeto);
				$arrQuantregistros		= $objRegistroHistorico->getQuantHistorico($arrParam);

				$arrHistorico[$value->cd_projeto][$value->cd_proposta]['registros'   ] = $arrQuantregistros['quant_registro'];
				$arrHistorico[$value->cd_projeto][$value->cd_proposta]['profissional'] = $arrQuantregistros['quant_profissional'];
				$arrHistorico[$value->cd_projeto][$value->cd_proposta]['horas'       ] = $value->ni_horas_parcela." ".$arrSiglaMetricaPadrao['tx_sigla_unidade_metrica'];
			}
		}
		$this->_arrHistorico = $arrHistorico;

        $this->_gerarRelatorio();
	}

    private function _gerarRelatorio()
    {
        $objPdf  = new Base_Tcpdf_Pdf();
        $objUtil = new Base_Controller_Action_Helper_Util();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_REGISTRO_ATIVIDADE'), K_CREATOR_SYSTEM.', '.
                                                                                           Base_Util::getTranslator('L_TIT_REL_REGISTRO_ATIVIDADE').', '.
                                                                                           Base_Util::getTranslator('L_VIEW_REGISTRO').', '.
                                                                                           Base_Util::getTranslator('L_VIEW_ATIVIDADE').', '.
                                                                                           Base_Util::getTranslator('L_VIEW_HISTORICO'));
        $objPdf->SetDisplayMode("real");

        // add a page
        $objPdf->AddPage();

		$objPdf->SetFont('helvetica', 'B', 9);
		$objPdf->Cell(14, 6, Base_Util::getTranslator('L_VIEW_PERIODO').":", '', 0, 'L');
		$objPdf->SetFont('helvetica', '', 9);
		$objPdf->Cell(166, 6, "{$objUtil->getMes($this->_arrPeriodo['mes'])}/{$this->_arrPeriodo['ano']}", '', 0, 'L');
		$objPdf->Ln(8);
		
        if(count($this->_arrHistorico) > 0){

            $header = array(Base_Util::getTranslator('L_VIEW_PROJETO'),
                            Base_Util::getTranslator('L_VIEW_METRICA'),
                            Base_Util::getTranslator('L_VIEW_REGISTRO_ATIVIDADE'),
                            Base_Util::getTranslator('L_VIEW_NR_PROFISSIONAL_REGISTRO_ATIVIDADE')
                          );

            // Colors, line width and bold font
            $objPdf->SetDrawColor(50);
            $objPdf->SetFillColor(240,240,240);
            $objPdf->SetTextColor(0);
            $objPdf->SetLineWidth(0.3);
            $objPdf->SetFont('', 'B',9);

            // Header
            $w = array(60,20,40,60);
            for($i = 0; $i < count($header); $i++){
                $objPdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            }
            $objPdf->Ln();

            // Color and font restoration
            $objPdf->SetFillColor(240, 248, 255);
            $objPdf->SetTextColor(0);
            $objPdf->SetFont('','',8);

            // Data
            $fill = 0;

            $cd_proposta = 0;
            foreach($this->_arrHistorico as $chave=>$valor){
                $objPdf->SetFont('','B',8);
                $objPdf->Cell($w[0], 6,$valor['tx_projeto'], 'LRTB', 0, 'L',0);
                $objPdf->Cell($w[1], 6, "", 'LRTB', 0, 'C', 0	);
                $objPdf->Cell($w[2], 6, "", 'LRTB', 0, 'C', 0);
                $objPdf->Cell($w[3], 6, "", 'LRTB', 0, 'C', 0);
                $objPdf->Ln();

                unset($valor['cd_projeto']);
                unset($valor['tx_projeto']);
                $objPdf->SetFont('','',8);
                foreach($valor as $key=>$value){
                    $objPdf->Cell($w[0], 6,"       ".Base_Util::getTranslator('L_VIEW_PROPOSTA_NR')." {$key}", 'LRTB', 0, 'L',0);
                    $objPdf->Cell($w[1], 6,($value['horas'] != 0)?$value['horas']:"0", 'LRTB', 0, 'C', 0);
                    $objPdf->Cell($w[2], 6,($value['registros'])?$value['registros']:"0", 'LRTB', 0, 'C', 0);
                    $objPdf->Cell($w[3], 6,($value['profissional'] != 0)?$value['profissional']:"0", 'LRTB', 0, 'C', 0);
                    $objPdf->Ln();
                }
                $fill=!$fill;
            }
        }else{
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        $objPdf->Output('relatorio_registro_historico.pdf', 'I');
    }

}