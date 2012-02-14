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

class RelatorioProjeto_BaselineController extends Base_Controller_Action
{
	
	private $objProjeto;
	private $objBaseline;
	private $arrBaseline;
	private $cd_projeto;
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		
		$this->objProjeto		  = new Projeto($this->_request->getControllerName());
		$this->objBaseline		  = new Baseline($this->_request->getControllerName());
		$this->objContrato 		  = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_BASELINE'));

		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $options;
	}
	
	public function baselineAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$this->cd_projeto = (int) $this->_request->getParam('cd_projeto');
		
		$this->arrBaseline = $this->objBaseline->getDadosBaselineRelatorio($this->cd_projeto);
		
		$this->geraRelatorio();
	}

	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();
		$arrKeywords = array(K_CREATOR_SYSTEM,
                     Base_Util::getTranslator('L_TIT_REL_BASELINE'),
                     Base_Util::getTranslator('L_VIEW_BASELINE'),
                     Base_Util::getTranslator('L_VIEW_RELATORIO'));

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_BASELINE'), $arrKeywords);

		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180,15,165);
		
		if($this->arrBaseline->valid()){

			$objPdf->SetFillColor(240,240,240);
			$objPdf->SetTextColor(0);
			
            $header = array(
                            Base_Util::getTranslator('L_VIEW_SIGLA').":",
                            Base_Util::getTranslator('L_VIEW_PROJETO').":",
                            Base_Util::getTranslator('L_VIEW_BASELINE_ATIVA').":",
                            Base_Util::getTranslator('L_VIEW_BASELINE_REGISTRADA'),
                            Base_Util::getTranslator('L_VIEW_DATA'),
                            Base_Util::getTranslator('L_VIEW_STATUS'),
                            );
            
			if( $this->cd_projeto === 0 ){
				//impressao da baseline ativa de todos os projetos
				
				foreach( $this->arrBaseline as $rs ){


					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1], 6, $header[0],'LT',0,'R',1);
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell($w[2], 6, $rs->tx_sigla_projeto,'RT',1,'L',1);
					
					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1], 6, $header[1],'LB',0,'R',1);
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell($w[2], 6, $rs->tx_projeto,'RB',1,'L',1);
					
					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[0], 2, "",'LR',1,'L',0);
					$objPdf->Cell(($w[1]+8), 6, $header[2],'LB',0,'L',0);
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[2]-8), 6, date('d/m/Y H:i:s', strtotime($rs->dt_baseline)),'RB',1,'L',0);
					$objPdf->SetFont('helvetica', '', 8);
					
					$objPdf->Cell($w[0], 6, "",'',1,'L',0);
				}
				
			}else{
				//impressao da baseline de um projeto especifico
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[1], 6, $header[0],'LT',0,'R',1);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[2], 6, $this->arrBaseline->getRow(0)->tx_sigla_projeto,'RT',1,'L',1);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[1], 6, $header[1],'LB',0,'R',1);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[2], 6, $this->arrBaseline->getRow(0)->tx_projeto,'RB',1,'L',1);

				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, $this->toUpper($header[3]),'LRB',1,'C',0);
				$objPdf->Cell(($w[0]/2), 6, $this->toUpper($header[4]),'LRB',0,'C',0);
				$objPdf->Cell(($w[0]/2), 6, $this->toUpper($header[5]),'LRB',1,'C',0);
				
				$objPdf->SetFont('helvetica', '', 8);
				
				foreach( $this->arrBaseline as $rs ){

					$objPdf->Cell(($w[0]/2), 6, date('d/m/Y H:i:s', strtotime($rs->dt_baseline)),'LRB',0,'C',0);
					$objPdf->Cell(($w[0]/2), 6, $rs->st_ativa,'LRB',1,'C',0);
				}
			}
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_plano_implatacao.pdf', 'I');
	}
				
}
