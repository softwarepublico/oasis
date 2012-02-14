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

class RelatorioProjeto_AlocacaoRecursoContratoController extends Base_Controller_Action
{
	private $objContrato;
	private $controle;
	private $objContratoDefinicaoMetrica;
	private $_parcelaOrcamento;
    private $arrProjeto;
    private $arrHorasProjetoPrevisto;
    private $unidadePadraoMetrica;
    private $contrato;
	
	public function init()
	{
		parent::init();
		
		$this->objContrato                 = new Contrato($this->_request->getControllerName());
		$this->controle                    = new Controle($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->_parcelaOrcamento           = new Base_Controller_Action_Helper_ParcelaOrcamento();
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ALOCACAO_RECURSO_CONTRATO'));

		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->comboContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function alocacaoRecursoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato             = $this->_request->getParam('cd_contrato');

        foreach($this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, false) as $key=>$value){
            $this->contrato = $value;
        }

		$projetoPrevisto         = new ProjetoPrevisto($this->_request->getControllerName());
		$this->arrProjeto        = $projetoPrevisto->getProjetoPrevisto($cd_contrato);
		$arrHorasProjetoPrevisto = array();

		foreach ($this->arrProjeto->toArray() as $projetoPrevisto){

			$ni_horas_projeto_previsto = $projetoPrevisto['ni_horas_projeto_previsto'];

			$credito = round((float)$this->controle->getHorasProjetoPrevisto($projetoPrevisto['cd_projeto_previsto'], 'C'),1);
			$debito  = round($this->controle->getHorasProjetoPrevisto($projetoPrevisto['cd_projeto_previsto'], 'D'),1);

			$total   = round($ni_horas_projeto_previsto + ($credito - $debito),1);

			$arrHorasProjetoPrevisto[$projetoPrevisto['cd_projeto_previsto']] = array('credito' => $credito, 'debito' => $debito, 'total' => $total);
		}

		$this->arrHorasProjetoPrevisto = $arrHorasProjetoPrevisto;

		$this->arrDadosMetricaPadrao = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);
		$this->unidadePadraoMetrica  = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : Base_Util::getTranslator('L_VIEW_UNID_METRICA');
	
		$this->geraRelatorio();
	}
	
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf('L');

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ALOCACAO_RECURSO_CONTRATO'), K_CREATOR_SYSTEM.', '.
                                                                                          Base_Util::getTranslator('L_TIT_REL_ALOCACAO_RECURSO_CONTRATO').', '.
                                                                                          Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                          Base_Util::getTranslator('L_VIEW_EXTRATO'));
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();

        $objPdf->SetFont('helvetica', 'B', 9);
        $objPdf->Cell(35, 6, Base_Util::getTranslator('L_VIEW_CONTRATO').': ');
        $objPdf->SetFont('helvetica', '', 9);
        $objPdf->Cell(145, 6, $this->contrato, 0, 1);
        $objPdf->Cell(268, 2, '', 'T', true);

        if(count($this->arrProjeto) > 0 ){
            $objPdf->SetFont('helvetica', '', 9);
            $objPdf->SetFillColor(240, 240, 240);

            $objPdf->Cell(160, 4, Base_Util::getTranslator('L_VIEW_GRID_LISTA'), 1, 0, 'C', 1);
            $objPdf->Cell(27, 4, Base_Util::getTranslator('L_VIEW_GRID_TOTAIS'), 1, 0, 'C', 1);
            $objPdf->Cell(27, 4, Base_Util::getTranslator('L_VIEW_GRID_CREDITOS'), 1, 0, 'C', 1);
            $objPdf->Cell(27, 4, Base_Util::getTranslator('L_VIEW_GRID_DEBITOS'), 1, 0, 'C', 1);
            $objPdf->Cell(27, 4, Base_Util::getTranslator('L_VIEW_GRID_SALDO'), 1, 1, 'C', 1);

            $objPdf->SetFillColor(240, 248, 255);
            $fill = false;
            
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            foreach ($this->arrProjeto->toArray() as $projeto){
                $objPdf->Cell(160, 4, $projeto['tx_projeto_previsto'], 1, 0, 'L', $fill);
                $objPdf->Cell(27, 4, $projeto['ni_horas_projeto_previsto'], 1, 0, 'C', $fill);
                $objPdf->Cell(27, 4, $this->arrHorasProjetoPrevisto[$projeto['cd_projeto_previsto']]['credito'], 1, 0, 'C', $fill);
                $objPdf->Cell(27, 4, $this->arrHorasProjetoPrevisto[$projeto['cd_projeto_previsto']]['debito'], 1, 0, 'C', $fill);
                $objPdf->Cell(27, 4, $this->arrHorasProjetoPrevisto[$projeto['cd_projeto_previsto']]['total'], 1, 1, 'C', $fill);
                $fill = !$fill;
                $total1 += $projeto['ni_horas_projeto_previsto'];
                $total2 += $this->arrHorasProjetoPrevisto[$projeto['cd_projeto_previsto']]['credito'];
                $total3 += $this->arrHorasProjetoPrevisto[$projeto['cd_projeto_previsto']]['debito'];
                $total4 += $this->arrHorasProjetoPrevisto[$projeto['cd_projeto_previsto']]['total'];
            }
            $objPdf->SetFillColor(240, 240, 240);
            $objPdf->Cell(160, 4, '', 1, 0, 'L', $fill);
            $objPdf->Cell(27, 4, $total1, 1, 0, 'C', $fill);
            $objPdf->Cell(27, 4, $total2, 1, 0, 'C', $fill);
            $objPdf->Cell(27, 4, $total3, 1, 0, 'C', $fill);
            $objPdf->Cell(27, 4, $total4, 1, 1, 'C', $fill);

            $objPdf->Ln(6);
            $objPdf->SetFont('helvetica', '', 8);
            $objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
        }else{
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }

		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_alocacao_recurso_contrato.pdf', 'I');
	}

}