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

class RelatorioProjeto_ProjetoDesenvolvidoContratoController extends Base_Controller_Action
{
    private $objRelProjeto;
    private $objContrato;
	private $tx_contrato;
	private $tx_impacto;

    public function init()
    {
        parent::init();
        $this->objRelProjeto = new RelatorioProjetoProjeto();
        $this->objContrato   = new Contrato($this->_request->getControllerName());
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PROJETO_DESENVOLVIDO_CONTRATO'));
    	
        $cd_contrato = null;
        $comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->comboContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
		
    }

    public function projetoDesenvolvidoContratoAction()
    {
   		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_contrato = $this->_request->getParam('cd_contrato');
        $rel_interno = $this->_request->getParam('rel_interno','0');
        $rel_externo = $this->_request->getParam('rel_externo','0');
        $rel_ambos   = $this->_request->getParam('rel_ambos','0');

        $arrImpacto       = array();
		$this->tx_impacto = "";
        if($rel_interno != '0'){
            $arrImpacto[] = 'I';
			$this->tx_impacto = 'Interno';
        }
        if($rel_externo != '0'){
            $arrImpacto[] = 'E';
			$this->tx_impacto = ($this->tx_impacto == "")?'Externo':$this->tx_impacto.', Externo';
        }
        if($rel_ambos != '0'){
            $arrImpacto[] = 'A';
			$this->tx_impacto = ($this->tx_impacto == "")?'Ambos':$this->tx_impacto.', Ambos';
        }
        $arrDados          = $this->objRelProjeto->projetoDesenvolvidoContrato($cd_contrato, $arrImpacto);
		$arrContrato       = $this->objContrato->getContratoPorTipoDeObjeto(false, 'P', $cd_contrato, false);
		$this->tx_contrato = $arrContrato[$cd_contrato];

        $this->gerarRelatorio($arrDados);
    }

    private function gerarRelatorio(array $arrDados)
    {
        $objPdf = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PROJETO_DESENVOLVIDO_CONTRATO'), K_CREATOR_SYSTEM.', '.
                                                                                                Base_Util::getTranslator('L_TIT_REL_HISTORICO_GERAL_PROJETO').', '.
                                                                                                Base_Util::getTranslator('L_VIEW_PROJETO').', '.
                                                                                                Base_Util::getTranslator('L_VIEW_CONTRATO').', '.
                                                                                                Base_Util::getTranslator('L_VIEW_PROJETO_DESENVOLVIDO_PERIODO_CONTRATO'));
        $objPdf->SetDisplayMode("real");

        // set font
        $objPdf->SetFont('helvetica', '', 10);

        // add a page
        $objPdf->AddPage();

		$objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_CONTRATO').": ".$this->tx_contrato, '', 0, 'L');
		$objPdf->Ln(4);
		$objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_IMPACTO').": ".$this->tx_impacto, '', 0, 'L');
		$objPdf->Ln(5);
		$objPdf->Cell(180,0,"",'B',1,'L');
		$objPdf->Ln(5);

        if(count($arrDados) > 0){

            $qtd = count($arrDados)-1;
            $linha = "S";
            foreach($arrDados as $key=>$value){
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(0,0, Base_Util::getTranslator('L_VIEW_SIGLA'),'',1,'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(0,0,$this->toUpper($value['tx_sigla_projeto']),'',1,'L');
                $objPdf->Ln(5);

                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(0,0, Base_Util::getTranslator('L_VIEW_NOME'),'',1,'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(0,0,$value['tx_projeto'],'',1,'L');
                $objPdf->Ln(5);

                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(0,0, Base_Util::getTranslator('L_VIEW_CONTEXTO_GERAL'),'',1,'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->MultiCell(0,0,trim(strip_tags($value['tx_contexto_geral_projeto'],'<b><i><span><li><ol><ul><br>'))."\n",'','J','',1,'','',true,'',true);
                $objPdf->Ln(5);

                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(0,0, Base_Util::getTranslator('L_VIEW_ESCOPO'),'',1,'L');
                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->MultiCell(0,0,trim(strip_tags($value['tx_escopo_projeto'],'<b><i><span><li><ol><ul><br>'))."\n",'','J','',1,'','',true,'',true);
                $objPdf->Ln(5);

                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90,0, Base_Util::getTranslator('L_VIEW_UNIDADE_GESTORA'),'',0,'L');
                $objPdf->Cell(0,0, Base_Util::getTranslator('L_VIEW_GESTOR_PROJETO'),'',1,'L');

                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90,0,$this->toUpper($value['tx_sigla_unidade']),'',0,'L');
                $objPdf->Cell(0,0,$this->toUpper($value['tx_gestor_projeto']),'',1,'L');
                $objPdf->Ln(5);

                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(90,0, Base_Util::getTranslator('L_VIEW_IMPACTO_PROJETO'),'',1,'L');

                $objPdf->SetFont('helvetica', '', 8);
                $objPdf->Cell(90,0,$this->toUpper($value['st_impacto_projeto']),'',1,'L');

                if($qtd == $key){
                    $linha = "N";
                }
                if($linha == "S"){
                    $objPdf->Ln(5);
                    $objPdf->Cell(180,0,"",'B',1,'L');
                    $objPdf->Ln(5);
                }
            }
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
        }else{
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        //Close and output PDF document
        $objPdf->Output('relatorio_documento_proposta.pdf', 'I');
    }


}