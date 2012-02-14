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

class RelatorioProjeto_DadosContratoController extends Base_Controller_Action
{
	private $arrDadosContrato;
	private $arrProjetosContrato;
	private $objContrato;
	private $objRelatorioContrato;
	
	public function init()
	{
		parent::init();
		$this->objContrato				= new Contrato($this->_request->getControllerName());
		$this->objRelatorioContrato		= new RelatorioProjetoContrato();
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DADOS_CONTRATO'));
        
        $cd_contrato = null;
        $comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->comboContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function dadosContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = (int) $this->_request->getParam('cd_contrato',0);
		
		$this->arrDadosContrato		= $this->objRelatorioContrato->getDadosContrato($cd_contrato);
		$this->arrProjetosContrato	= $this->objRelatorioContrato->getProjetosDadoContrato($cd_contrato);
		
		$this->geraRelatorio();
	}
	
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_DADOS_CONTRATO'),
                                 K_CREATOR_SYSTEM.', '.
                                 Base_Util::getTranslator('L_TIT_REL_DADOS_CONTRATO').', '.
                                 Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                 Base_Util::getTranslator('L_VIEW_DADOS_CONTRATO'));
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180, 90);
		
		//cabeçalho
		$objPdf->SetFont('helvetica', 'B', 8);
		$objPdf->Cell($w[0],2, Base_Util::getTranslator('L_VIEW_NUMERO_CONTRATO'),'',1);
		$objPdf->SetFont('helvetica', '', 8);
		$objPdf->Cell($w[0],6,$this->arrDadosContrato['tx_numero_contrato'],'',1);

		$objPdf->SetFont('helvetica', 'B', 8);
		$objPdf->Cell($w[0],2, Base_Util::getTranslator('L_VIEW_EMPRESA'),'',1);
		$objPdf->SetFont('helvetica', '', 8);
		$objPdf->Cell($w[0],6, strtoupper($this->arrDadosContrato['tx_empresa']),'',1);

		$objPdf->SetFont('helvetica', 'B', 8);
		$objPdf->Cell($w[0],2, Base_Util::getTranslator('L_VIEW_SITUACAO'),'',1);
		$objPdf->SetFont('helvetica', '', 8);
		$objPdf->Cell($w[0],6,$this->arrDadosContrato['situacao_contrato'],'',1);
		
		$objPdf->SetFont('helvetica', 'B', 8);
		$objPdf->Cell($w[0],2, Base_Util::getTranslator('L_VIEW_NUCLEO'),'',1);
		$objPdf->SetFont('helvetica', '', 8);
		$objPdf->Cell($w[0],6,strtoupper($this->arrDadosContrato['tx_objeto']),'',1);
				
		$objPdf->Cell($w[0],2,"",'B',1);
		$objPdf->Cell($w[0],2,"",'',1);

		$objPdf->SetFont('helvetica', 'B', 8);
		$objPdf->Cell($w[1],2, Base_Util::getTranslator('L_VIEW_VIGENCIA_INICIO' ),'',0);
		$objPdf->Cell($w[1],2, Base_Util::getTranslator('L_VIEW_VIGENCIA_TERMINO'),'',1);
		$objPdf->SetFont('helvetica', '', 8);
		$objPdf->Cell($w[1],6,$this->arrDadosContrato['dt_inicio_contrato'],'',0);
		$objPdf->Cell($w[1],6,$this->arrDadosContrato['dt_fim_contrato'],'',1);

		$objPdf->SetFont('helvetica', 'B', 8);
		$objPdf->Cell($w[0],2, Base_Util::getTranslator('L_VIEW_TOTAL_UNIDADE_METRICA_PREVISTA'),'',1);
		$objPdf->SetFont('helvetica', '', 8);
		$objPdf->Cell($w[0],6,$this->arrDadosContrato['ni_horas_previstas'],'',1);
		
		$objPdf->SetFillColor(240, 248, 255);
		$objPdf->SetTextColor(0);

		$strTable = "";
			$strTable .= '<table cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" border="1">';
			$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'">';
			$strTable .= '  <td width="349px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_LISTA_PROJETO').'</b></td>';
			$strTable .= '  <td width="80px" style="text-align:center;" ><b>'.Base_Util::getTranslator('L_VIEW_TIPO').'</b></td>';
			$strTable .= '  <td width="80px" style="text-align:center;" ><b>'.Base_Util::getTranslator('L_VIEW_UNID_METRICA_PREVISTA').'</b></td>';
			$strTable .= '</tr>';

		$fill		= false;
		$totalHoras = 0;
		foreach( $this->arrProjetosContrato as $rs ){

			if(!$fill){
				$strTable .= '<tr>';
				$strTable .= '  <td width="349px" style="text-align:justify;">'.$rs['tx_projeto_previsto'].'</td>';
				$strTable .= '  <td width="80px" style="text-align:center;">'.$rs['tipo'].'</td>';
				$strTable .= '  <td width="80px" style="text-align:center;">'.$rs['ni_horas_projeto_previsto'].'</td>';
				$strTable .= '</tr>';
			}else{
				$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'">';
				$strTable .= '  <td width="349px" style="text-align:justify;">'.$rs['tx_projeto_previsto'].'</td>';
				$strTable .= '  <td width="80px" style="text-align:center;">'.$rs['tipo'].'</td>';
				$strTable .= '  <td width="80px" style="text-align:center;">'.$rs['ni_horas_projeto_previsto'].'</td>';
				$strTable .= '</tr>';
			}
			$totalHoras += $rs['ni_horas_projeto_previsto'];
			$fill = !$fill;
		}
		//total
		$strTable .= '<tr>';
		$strTable .= '  <td width="429px" style="text-align:center;" colspan="2"><b>'.Base_Util::getTranslator('L_VIEW_TOTAL').'</b></td>';
		$strTable .= '  <td width="80px" style="text-align:center;"><b>'.$totalHoras.'</b></td>';
		$strTable .= '</tr>';
		$strTable .= '</table>';

		$objPdf->writeHTML($strTable,true, 0, true, 0);

		//Close and output PDF document
		$objPdf->Output('relatorio_extrato_geral_execucao_contrato.pdf', 'I');
	}
}
 
