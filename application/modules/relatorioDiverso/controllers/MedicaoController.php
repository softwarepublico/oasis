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

class RelatorioDiverso_MedicaoController extends Base_Controller_Action
{
	private $objMedicao;
	
	public function init()
	{
		parent::init();
		$this->objMedicao = new Medicao($this->_request->getControllerName());
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_MEDICAO'));
        
		$this->view->comboMedicao = $this->objMedicao->getMedicoes(true);
	}
	
	public function medicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_medicao = (int) $this->_request->getParam('cd_medicao', 0);
		
		$arrMedicao = $this->objMedicao->getAllMedicoes( $cd_medicao );
		
		$this->geraRelatorio( $arrMedicao );
	}

	private function geraRelatorio( $arrMedicao )
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_MEDICAO'), $arrKeywords);

		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180,15,165);
		
		if(count($arrMedicao) > 0){
				
			foreach( $arrMedicao as $rs ){
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_MEDICAO'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[0], 6, $rs['tx_medicao'],'',1,'L',0);
				$objPdf->Cell($w[0], 6, "",'',1,'L',0);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_NIVEL'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[0], 6, $rs['st_nivel_medicao'],'',1,'L',0);
				$objPdf->Cell($w[0], 6, "",'',1,'L',0);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_OBJETIVO'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->MultiCell($w[0], 6, $rs['tx_objetivo_medicao'].PHP_EOL,'','J',0,1);
				$objPdf->Cell($w[0], 6, "",'',1,'L',0);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROCEDIMENTO_COLETA'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->MultiCell($w[0], 6, $rs['tx_procedimento_coleta'].PHP_EOL,'','J',0,1);
				$objPdf->Cell($w[0], 6, "",'',1,'L',0);

				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROCEDIMENTO_ANALISE'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->MultiCell($w[0], 6, $rs['tx_procedimento_analise'].PHP_EOL,'','J',0,1);
				$objPdf->Cell($w[0], 6, "",'',1,'L',0);
			}
				
		}else{
			$html = $objPdf->semRegistroParaConsulta();
			$objPdf->writeHTML($html,true, 0, true, 0);	
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_medicacao.pdf', 'I');
	}
	
}
