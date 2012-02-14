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

class RelatorioDiverso_InventarioController extends Base_Controller_Action
{
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_INVENTARIO'));
		
        $objAreaAtuacaoTi  = new AreaAtuacaoTi($this->_request->getControllerName());
		$this->view->comboAreaAtuacaoTI = $objAreaAtuacaoTi->comboAreaAtuacaoTi(true);
	}
	
	public function inventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        $objRelatorio = new RelatorioInventario();
        $this->arrDadosRelInventariado = $objRelatorio->getDadosRelatorioInventario($this->_request->getPost("cd_inventario"));
        
        $this->geraRelatorio();
	}

	private function geraRelatorio()
	{
        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_INVENTARIO'),
                             Base_Util::getTranslator('L_VIEW_ITEMINVENTARIO'),
                             Base_Util::getTranslator('L_VIEW_INVENTARIO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        
		$objPdf      = new Base_Tcpdf_Pdf();
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_INVENTARIO'), $arrKeywords);

		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180);
        
		if(count($this->arrDadosRelInventariado) > 0){
            foreach($this->arrDadosRelInventariado as $nomeInventario=>$itensInventario){
                $objPdf->SetFillColor(0, 245, 255);
                $objPdf->SetTextColor(0);
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->MultiCell($w[0], 6, $nomeInventario,'LRT','L',1,1);
                $blank = '        ';
                $blank2 = '        ';
                foreach($itensInventario as $nomeItem=>$subitens){
                    $objPdf->SetFillColor(Base_Tcpdf_Pdf::R_FILL,Base_Tcpdf_Pdf::G_FILL,Base_Tcpdf_Pdf::B_FILL);
                    $objPdf->SetTextColor(0, 0, 255);
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->MultiCell($w[0], 6, $blank.$nomeItem,'LR','L',0,1);
                    $blank .= $blank;
//                    $objPdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)
                    foreach($subitens as $nomeSubitem=>$subSubitens){
                        $objPdf->SetFillColor(240,240,240);
                        $objPdf->SetTextColor(0);
                        $objPdf->SetFont('helvetica', 'B', 8);
                        $objPdf->MultiCell($w[0], 6,$blank2.$nomeSubitem,1,'L',1,1);
                        foreach($subSubitens as $nomeDescri=>$subDescri){
                            $objPdf->SetFont('helvetica', 'B', 8);
                            $objPdf->MultiCell($w[0], 6, $blank.'        '.$nomeDescri,'LR','L',0,1);
                            if(is_array( $subDescri )){
                                $objPdf->SetFont('helvetica', '', 8);
                                foreach ($subDescri as $key => $value) {
                                    $objPdf->MultiCell(50, 6, $blank.$key.' : ','L','R',0,0);
                                    $objPdf->MultiCell(130, 6, $blank.$value,'R', 'L',0,1);
                                }
                                $objPdf->Line(35, $objPdf->GetY()+3,180,$objPdf->GetY()+3);
                            }  else {
                                $objPdf->SetFont('helvetica', '', 8);
                                $objPdf->MultiCell(180, 6, $blank.'                Nenhum item encontrado ','LR','L',0,1);
                                
                                $objPdf->Line(35, $objPdf->GetY()+3,180,$objPdf->GetY()+3);
                            }
                            $objPdf->Cell(180, 6,'','LR',1);
                        }
                        $objPdf->Cell(180, 6,'','LR',1);
                    }
                    $objPdf->Cell(180, 6,'','LR',1);
                }
            }
			$objPdf->Ln(6);
			$objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
			//Close and output PDF document
			$objPdf->Output('relatorio_Inventario.pdf', 'I');
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);	
			$objPdf->Output('relatorio_Inventario.pdf', 'I');
		}
	}
	
}