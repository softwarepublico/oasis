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

class RelatorioProjeto_ExtratoGeralExecucaoContratoController extends Base_Controller_Action
{
	
	private $arrDadosContrato;
	private $arrProjetosPrevistos;
	private $arrParcelas;
	private $objContrato;
	private $objRelatorioContrato;
	private $comMetricaPadrao;
	private $siglaMetricaPadraoContrato;

	public function init()
	{
		parent::init();
		$this->objContrato				= new Contrato($this->_request->getControllerName());
		$this->objRelatorioContrato		= new RelatorioProjetoContrato();
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_EXTRATO_GERAL_EXECUCAO_CONTRATO'));

		$cd_contrato = null;
		$comStatus   = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus = false;
		}
		$this->view->comboContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function extratoGeralExecucaoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = (int) $this->_request->getParam('cd_contrato',0);

		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());

		$arrDadosMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);

		if( count($arrDadosMetricaPadrao) == 0 ){
			$this->comMetricaPadrao			  = false;
		}else{
			$this->comMetricaPadrao			  = true;
			$this->siglaMetricaPadraoContrato = $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
			$this->arrDadosContrato		= $this->objRelatorioContrato->getDadosContrato($cd_contrato);
			$this->arrProjetosPrevistos	= $this->objRelatorioContrato->getProjetoPrevistoExecucaoContrato($cd_contrato);
			$this->arrParcelas			= $this->objRelatorioContrato->getParcelasExecucaoContrato($cd_contrato);
		}
		$this->geraRelatorio();
	}
	
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_EXTRATO_GERAL_EXECUCAO_CONTRATO'),
                                 K_CREATOR_SYSTEM.', '.
                                 Base_Util::getTranslator('L_TIT_REL_EXTRATO_GERAL_EXECUCAO_CONTRATO').', '.
                                 Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                 Base_Util::getTranslator('L_VIEW_EXTRATO'));
		$objPdf->SetDisplayMode("real");
		$objPdf->AddPage();

		if( $this->comMetricaPadrao ){

			$w = array(180, 8, 40, 136, 90, 45);

			//cabeçalho
			$objPdf->SetFont('helvetica', 'B', 8);
			$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_DADOS_CONTRATO'),'',1);

			$objPdf->Cell($w[1],6,"",'',0);
			$objPdf->SetFont('helvetica', 'B', 8);
			$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_PERIODO_EXECUCAO').":",'',0);
			$objPdf->SetFont('helvetica', '', 8);
			$objPdf->Cell($w[3],6,"{$this->arrDadosContrato['dt_inicio_contrato']} a {$this->arrDadosContrato['dt_fim_contrato']}",'',1);

			$objPdf->SetFont('helvetica', 'B', 8);
			$objPdf->Cell($w[1],6,"",'',0);
			$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_TOTAL_UNIDADE_METRICA').":",'',0);
			$objPdf->SetFont('helvetica', '', 8);
			$objPdf->Cell($w[3],6,"{$this->arrDadosContrato['ni_horas_previstas']} {$this->siglaMetricaPadraoContrato}",'',1);

			$objPdf->SetFillColor(240, 248, 255);
			$objPdf->SetTextColor(0);

			$strTable = "";
				$strTable .= '<table cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" border="1">';
				$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'">';
				$strTable .= '  <td width="403px" style="text-align:center;"><b>'. Base_Util::getTranslator('L_VIEW_PROJETO') .'</b></td>';
				$strTable .= '  <td width="106px" style="text-align:center;"><b>'.$this->siglaMetricaPadraoContrato.'</b></td>';
				$strTable .= '</tr>';

			$fill		= false;
			$totalHoras = 0;
			foreach( $this->arrProjetosPrevistos as $rs ){

				if(!$fill){
					$strTable .= '<tr>';
					$strTable .= '  <td width="403px" style="text-align:justify;">'.$rs['projeto'].'</td>';
					$strTable .= '  <td width="106px" style="text-align:center;">'.$rs['horas'].'</td>';
					$strTable .= '</tr>';
				}else{
					$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'">';
					$strTable .= '  <td width="403px" style="text-align:justify;">'.$rs['projeto'].'</td>';
					$strTable .= '  <td width="106px" style="text-align:center;">'.$rs['horas'].'</td>';
					$strTable .= '</tr>';
				}
				$totalHoras += $rs['horas'];
				$fill = !$fill;
			}
			//total
			$strTable .= '<tr>';
			$strTable .= '  <td width="403px" style="text-align:center;"><b>'. Base_Util::getTranslator('L_VIEW_TOTAL') .'</b></td>';
			$strTable .= '  <td width="106px" style="text-align:center;"><b>'.$totalHoras.'</b></td>';
			$strTable .= '</tr>';
			$strTable .= '</table>';

			$strTable .= '<br>';
			$strTable .= '<table cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" border="1">';
			$strTable .= '<tr>';
			$strTable .= '  <td width="403px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_QTD_PARCELA').'</b></td>';
			$strTable .= '  <td width="106px" style="text-align:center;"><b>'.$this->arrParcelas['total_parcelas'].'</b></td>';
			$strTable .= '</tr>';
			$strTable .= '</table>';

			$objPdf->writeHTML($strTable,true, 0, true, 0);
		}else{
			$objPdf->SetFont('helvetica', 'B', 10);
			$objPdf->Cell(180, 6,  Base_Util::getTranslator('L_MSG_ALERT_CONTRATO_SEM_METRICA_PADRAO_NAO_GERA_RELATORIO'), false, true, "C");
			$objPdf->Ln(5);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_extrato_geral_execucao_contrato.pdf', 'I');
	}


}
