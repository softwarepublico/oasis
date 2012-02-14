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

class RelatorioProjeto_ExtratoMensalDemandaController extends Base_Controller_Action
{

	private $mesAno;
	private $arrExtratoMensal;
	private $arrResumoExtratoMensal;
	private $objContrato;
	private $objExtratoMensal;
	private $objProfissional;
	private $objPdf;
	private $tx_contrato;

	public function init()
	{
		parent::init();
		$this->objContrato		= new Contrato($this->_request->getControllerName());
		$this->objExtratoMensal	= new ExtratoMensal($this->_request->getControllerName());
		$this->objProfissional	= new Profissional($this->_request->getControllerName());
	}

    /**
     * Action da tela de inicial
     */
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_EXTRATO_MENSAL_DEMANDA'));
        $this->initView();

		$cd_contrato = null;
		$comStatus	 = true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->comboContrato	= $this->objContrato->getContratoPorTipoDeObjeto(true, 'D', $cd_contrato, $comStatus);
    }
    
    /**
     * Action de geração do relatório de Previsão Mensal de Produtos de Parcelas
     */
	public function generateAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
	   
		$objExtratoMensal	= new RelatorioProjetoExtratoMensal();
		$objUtil			= new Base_Controller_Action_Helper_Util();
		$arrParans			= $this->_request->getParams();

        $descMes			= $objUtil->getMes($arrParans['mes']);

		$this->arrExtratoMensal = $objExtratoMensal->getExtratoMensalDemanda($arrParans);

        
        
		$this->mesAno           = $descMes."/".$arrParans['ano'];

		$arrContrato	    = $this->objContrato->getContratoPorTipoDeObjeto(false, 'D', $arrParans['cd_contrato'], false);
		$this->tx_contrato  = $arrContrato[$arrParans['cd_contrato']];

		$this->arrResumoExtratoMensal = $this->objExtratoMensal->getExtratoMensal($arrParans['cd_contrato'], $arrParans['mes'], $arrParans['ano']);

        
		$this->gerarRelatorio();
	}

	private function gerarRelatorio(){

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf();

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_EXTRATO_MENSAL_DEMANDA'), K_CREATOR_SYSTEM.', '.
                                                                                             Base_Util::getTranslator('L_TIT_REL_EXTRATO_MENSAL_DEMANDA').', '.
                                                                                             Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                             Base_Util::getTranslator('L_VIEW_EXTRATO_MENSAL_DEMANDA').', '.
                                                                                             Base_Util::getTranslator('L_VIEW_EXTRATO'));
		$this->objPdf->SetDisplayMode("real");
		// set font
		$this->objPdf->SetFont('helvetica', 'B', 10);
		// add a page
		$this->objPdf->AddPage();

		$this->objPdf->Cell(30, 4, Base_Util::getTranslator('L_VIEW_CONTRATO').":", '', 0, 'L');
		$this->objPdf->Cell(150, 4, $this->tx_contrato, '', 1, 'L');
		$this->objPdf->Cell(30, 4, Base_Util::getTranslator('L_VIEW_MES_ANO').":", '', 0, 'L');
		$this->objPdf->Cell(150, 4, $this->mesAno, '', 1, 'L');
		
		//Column titles
		$header = array(Base_Util::getTranslator('L_VIEW_PROJETO'), Base_Util::getTranslator('L_VIEW_UNIDADES_METRICA'));
		$this->objPdf->Ln(7);

		// print colored table
		$this->montarelatorioDemanda($header);

		//Close and output PDF document
		$this->objPdf->Output('relatorio_custo_projeto.pdf', 'I');
	}

	private function montarelatorioDemanda($header)
    {
		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica();
        
		if( count($this->arrExtratoMensal) > 0 && count($this->arrResumoExtratoMensal) > 0){
			// Colors, line width and bold font
			$this->objPdf->SetDrawColor(50);
			$this->objPdf->SetFillColor(240,240,240);
			$this->objPdf->SetTextColor(0);
			$this->objPdf->SetLineWidth(0.3);
			$this->objPdf->SetFont('', 'B',9);
			// Header
			$w = array(140,40);
			for($i = 0; $i < count($header); $i++)
			$this->objPdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
			$this->objPdf->Ln();
			// Color and font restoration
			$this->objPdf->SetFillColor(240, 248, 255);
			$this->objPdf->SetTextColor(0);
			$this->objPdf->SetFont('','',8);
			// Data
			$fill = 0;

			$siglaProjetoAux = "";
			$totalHora       = 0;
			$qtdParcela      = 0;

            
            
			foreach($this->arrExtratoMensal as $row){
				if($siglaProjetoAux != $row['tx_sigla_projeto']){

					$arrDadosUnidadeMetricaPadrao = array();
					$arrDadosUnidadeMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($row['cd_projeto']);

					$this->objPdf->Cell($w[0], 6, $row['tx_sigla_projeto'], 'LRBT', 0, 'L');
					$this->objPdf->Cell($w[1], 6, "", 'LRBT', 1, '');
				}
				$this->objPdf->Cell($w[0], 6, "     ".$row['dt_registro'], 'LRBT', 0, 'L');
				$this->objPdf->Cell($w[1], 6, $row['nf_qtd_unidade_metrica'].' '.$arrDadosUnidadeMetricaPadrao['tx_sigla_unidade_metrica'], 'LRBT', 1, 'C');

				$totalHora += $row['nf_qtd_unidade_metrica'];
				$siglaProjetoAux = $row['tx_sigla_projeto'];
				$qtdParcela++;
			}
			$this->objPdf->SetFont('','B',8);
			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_TOTAL'), 'LRTB', 0, 'C',0);
			$this->objPdf->Cell($w[1], 6, $totalHora."  ", 'LRTB', 1, 'C', 0);

			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_QTD_PARCELA').": {$qtdParcela}", '', 0, 'L',0);
			$this->objPdf->Cell($w[1], 6, "", '', 1, 'C', 0);

			$arrProfissional = $this->objProfissional->getDadosProfissional($this->arrResumoExtratoMensal[0]['id']);
			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL_EXECUTOU_FECHAMENTO').": ".$arrProfissional[0]['tx_profissional'], '', 0, 'L',0);
			$this->objPdf->Cell($w[1], 6, "", '', 1, 'C', 0);

			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DATA_FECHAMENTO').": ".Base_Util::converterDate($this->arrResumoExtratoMensal[0]['dt_fechamento_extrato'], 'YYYY-MM-DD', 'DD/MM/YYYY'), '', 0, 'L',0);
			$this->objPdf->Cell($w[1], 6, "", '', 1, 'C', 0);

		} else {
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta('P'),true, 0, true, 0);
		}

	}
}