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

class RelatorioProjeto_ChecklistParcelaController extends Base_Controller_Action
{
	private $arrChecklistParcela;
	private $objPdf;
	private $mesAno;
	
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CHECKLIST_PARCELA'));

        $objContrato 		= new Contrato($this->_request->getControllerName());
		$cd_contrato 		= null;
		$comStatus			= true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }

    public function generateAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout->disableLayout();

    	$objChecklistParcela		= new RelatorioProjetoChecklistParcela();
		$objUtil			        = new Base_Controller_Action_Helper_Util();
    	
    	$arrParam['mes']			= $this->_request->getParam('mes');
    	$arrParam['ano']			= $this->_request->getParam('ano');
    	$arrParam['cd_contrato']	= $this->_request->getParam('cd_contrato');

		$descMes			        = $objUtil->getMes( $arrParam['mes']);
		$this->mesAno               = $descMes."/".$arrParam['ano'];

		$this->arrChecklistParcela	= $objChecklistParcela->getChecklistParcela($arrParam);
		$this->gerarRelatorio();
    }

	private function gerarRelatorio(){

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf('L');

        $arrKeywords = array(K_CREATOR_SYSTEM,
                     Base_Util::getTranslator('L_TIT_REL_CHECKLIST_PARCELA'),
                     Base_Util::getTranslator('L_VIEW_RELATORIO'));

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_CHECKLIST_PARCELA'), $arrKeywords);

		$this->objPdf->SetDisplayMode("real");
		// set font
		$this->objPdf->SetFont('helvetica', 'B', 8);
		// add a page
		$this->objPdf->AddPage();

		$this->objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_MES_ANO').": ".$this->mesAno, '', 0, 'L');
		$this->objPdf->Ln(7);

		if( count($this->arrChecklistParcela) > 0 ){
			//Titulos da Coluna
            $header = array(
                            Base_Util::getTranslator('L_VIEW_PROJETO'),
                            Base_Util::getTranslator('L_VIEW_UNID_METRICA_PREVISTA'),
                            Base_Util::getTranslator('L_VIEW_AUTORIZACAO'),
                            Base_Util::getTranslator('L_VIEW_FINALIZACAO'),
                            Base_Util::getTranslator('L_VIEW_FISCALIZACAO'),
                            Base_Util::getTranslator('L_VIEW_ACEITE'),
                            Base_Util::getTranslator('L_VIEW_HOMOLOGACAO')
                );
			// Imprime os dados no relatório de checkList da Parcela
			$this->montaGridChecklist($header);

		} else {
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta('L'),true, 0, true, 0);
		}

		// reset pointer to the last page
		$this->objPdf->lastPage();

		//Close and output PDF document
		$this->objPdf->Output('relatorio_checklist_parcela.pdf', 'I');
	}

	private function montaGridChecklist($header){

		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica();

		// Colors, line width and bold font
		$this->objPdf->SetDrawColor(50);
		$this->objPdf->SetFillColor(240,240,240);
		$this->objPdf->SetTextColor(0);
		$this->objPdf->SetLineWidth(0.3);
		$this->objPdf->SetFont('helvetica', 'B', 8);

		// Tamanho do Cabeçalho
		$w = array(87,45,27,27,27,27,27);
		for($i = 0; $i < count($header); $i++)
			$this->objPdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		$this->objPdf->Ln();

		// Color and font restoration
		$this->objPdf->SetFillColor(240, 248, 255);
		$this->objPdf->SetTextColor(0);

		$fill = 1;

		$cd_projeto_aux = 0;
		foreach($this->arrChecklistParcela as $row){

			//Mostra o nome do projeto
			if($cd_projeto_aux != $row['cd_projeto']){
				$arrDadosMetricaPadrao = array();

				$arrDadosMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($row['cd_projeto']);

				$fill=!$fill;
				$this->objPdf->SetFont('helvetica', 'B', 8);
				$this->objPdf->Cell($w[0], 6, $row['tx_sigla_projeto'], 'LR', 0, 'L', $fill);
				$this->objPdf->Cell($w[1], 6, "", 'LR', 0, 'L', $fill);
				$this->objPdf->Cell($w[2], 6, "", 'LR', 0, 'R', $fill);
				$this->objPdf->Cell($w[3], 6, "", 'LR', 0, 'R', $fill);
				$this->objPdf->Cell($w[4], 6, "", 'LR', 0, 'R', $fill);
				$this->objPdf->Cell($w[5], 6, "", 'LR', 0, 'R', $fill);
				$this->objPdf->Cell($w[6], 6, "", 'LR', 0, 'R', $fill);
				$this->objPdf->Ln();
			}
			$this->objPdf->SetFont('helvetica', '', 8);
			$this->objPdf->Cell($w[0], 6, "    Parcela Nº ".$row['ni_parcela']." (Proposta Nº {$row['cd_proposta']})", 'LR', 0, 'L', $fill);
			$this->objPdf->Cell($w[1], 6, number_format($row['ni_horas_parcela'],1,',','.')." ".$arrDadosMetricaPadrao['tx_sigla_unidade_metrica'], 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[2], 6, (!is_null($row['st_autorizacao_parcela'    ])) ? "OK" : Base_Util::getTranslator('L_VIEW_PENDENTE'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[3], 6, (!is_null($row['st_fechamento_parcela'     ])) ? "OK" : Base_Util::getTranslator('L_VIEW_PENDENTE'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[4], 6, (!is_null($row['st_parecer_tecnico_parcela'])) ? "OK" : Base_Util::getTranslator('L_VIEW_PENDENTE'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[5], 6, (!is_null($row['st_aceite_parcela'         ])) ? "OK" : Base_Util::getTranslator('L_VIEW_PENDENTE'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[6], 6, (!is_null($row['st_homologacao_parcela'    ])) ? "OK" : Base_Util::getTranslator('L_VIEW_PENDENTE'), 'LR', 0, 'C', $fill);
			$this->objPdf->Ln();

			$cd_projeto_aux = $row['cd_projeto'];
		}
		$this->objPdf->SetFont('helvetica', 'I', 5);
		$this->objPdf->Cell(267, 6, "(*) Última parcela da proposta do projeto.", 'T', 0);
	}

}