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

class RelatorioProjeto_ExtratoGeralExecucaoContratoCustoController extends Base_Controller_Action
{
    private $objContrato;
    private $objRelatorioContrato;
    private $arrDadosContrato;
    private $arrProjetosPrevistos;
    private $arrParcelas;
    private $objPdf;
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
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_EXTRATO_GERAL_EXECUCAO_CONTRATO_CUSTO'));

        $this->view->comboContrato	= $this->objContrato->getContratoPorTipoDeObjeto(true, 'P');
    }

    public function extratoGeralExecucaoContratoCustoAction()
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
			$valorHora                  = $this->arrDadosContrato['ni_horas_previstas']*$this->arrDadosContrato['nf_valor_unitario_hora'];
			$this->arrDadosContrato['valor_contrato'] = $valorHora;
			$this->arrProjetosPrevistos	= $this->objRelatorioContrato->getProjetoPrevistoExecucaoContrato($cd_contrato);
			$this->arrParcelas			= $this->objRelatorioContrato->getParcelasExecucaoContrato($cd_contrato);
			$this->arrParcelas['total_valor'] = $this->arrParcelas['total_horas']*$this->arrDadosContrato['nf_valor_unitario_hora'];
		}
		
        $this->gerarRelatorio();
    }

    private function gerarRelatorio()
    {
        //criando o objeto
        $this->objPdf = new Base_Tcpdf_Pdf();

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_EXTRATO_GERAL_EXECUCAO_CONTRATO_CUSTO'),
                                                                                             K_CREATOR_SYSTEM.', '.
                                                                                             Base_Util::getTranslator('L_TIT_REL_EXTRATO_GERAL_EXECUCAO_CONTRATO_CUSTO').', '.
                                                                                             Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                             Base_Util::getTranslator('L_VIEW_CUSTO').', '.
                                                                                             Base_Util::getTranslator('L_VIEW_EXTRATO'));
        $this->objPdf->SetDisplayMode("real");
        // add a page
        $this->objPdf->AddPage();

        $font     = 'helvetica';
        $fontSize = 8;

		if( $this->comMetricaPadrao ){

			$w = array(100,80);
			$this->objPdf->SetFont($font, 'B', $fontSize);
			$this->objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_DADOS_CONTRATO').":",'',0,'L');
			$this->objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_PERIODO_EXECUCAO').":",'',0);
			$this->objPdf->Ln(4);
			$this->objPdf->SetFont($font, '', $fontSize);
			$this->objPdf->Cell($w[0],6,$this->arrDadosContrato['tx_numero_contrato'],'',0,'L');
			$this->objPdf->Cell($w[1],6,"{$this->arrDadosContrato['dt_inicio_contrato']} a {$this->arrDadosContrato['dt_fim_contrato']}",'');
			$this->objPdf->Ln(5);

			$this->objPdf->SetFont($font, 'B', $fontSize);
			//$this->objPdf->Cell($w[0],6,"Horas Total:",'',0);
			$this->objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_TOTAL_UNIDADE_METRICA').": ",'',0);
			$this->objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_VALOR_TOTAL').":",'',0);
			$this->objPdf->Ln(4);
			$this->objPdf->SetFont($font, '', $fontSize);
			$this->objPdf->Cell($w[0],6,number_format($this->arrDadosContrato['ni_horas_previstas'],1,',','.')." ".$this->siglaMetricaPadraoContrato,'');
			$this->objPdf->Cell($w[1],6,number_format($this->arrDadosContrato['valor_contrato'],2,',','.'),'');

			$this->objPdf->ln(10);
			// print colored table
			$this->listaDadosContrato();
		}else{
			$this->objPdf->SetFont($font, 'B', $fontSize+2);
			$this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_MSG_ALERT_CONTRATO_SEM_METRICA_PADRAO_NAO_GERA_RELATORIO'), false, true, "C");
			$this->objPdf->Ln(5);
		}
        // ---------------------------------------------------------
        //Close and output PDF document
        $this->objPdf->Output('relatorio_extrato_geral_execucao_contrato_custo.pdf', 'I');
    }

    private function listaDadosContrato()
    {
        //Column titles
        $header = array('Projeto',"Total de {$this->siglaMetricaPadraoContrato}", 'Valor Total');
        // Colors, line width and bold font
        $this->objPdf->SetDrawColor(50);
        $this->objPdf->SetFillColor(240,240,240);
        $this->objPdf->SetTextColor(0);
        $this->objPdf->SetLineWidth(0.3);
        $this->objPdf->SetFont('', 'B',9);
        // Header
        $w = array(110, 30, 40);
        for($i = 0; $i < count($header); $i++)
        $this->objPdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        $this->objPdf->Ln();
        // Color and font restoration
        $this->objPdf->SetFillColor(240, 248, 255);
        $this->objPdf->SetTextColor(0);
        $this->objPdf->SetFont('helvetica', '', 8);
        // Data
        $fill = 0;

        foreach($this->arrProjetosPrevistos as $row){
            $this->objPdf->Cell($w[0], 6, $row['tx_sigla_projeto'], 'LR', 0, 'L', $fill);
            $this->objPdf->Cell($w[1], 6, number_format($row['horas'],1,',','.'), 'LR', 0, 'C', $fill);
            $valor_hora = $row['horas']*$this->arrDadosContrato['nf_valor_unitario_hora'];
            $this->objPdf->Cell($w[2], 6, number_format($valor_hora,2,',','.')."  ", 'LR', 0, 'C', $fill);
            $this->objPdf->Ln();
            $fill=!$fill;
        }
        $this->objPdf->SetFont('helvetica', 'B', 8);
        $this->objPdf->Cell($w[0], 6, "Total", 'LTB', 0, 'C',0);
        $this->objPdf->Cell($w[1], 6, number_format($this->arrParcelas['total_horas'],1,',','.')."  ", 'LTB', 0, 'C', 0);
        $this->objPdf->Cell($w[2], 6, number_format($this->arrParcelas['total_valor'],2,',','.')."  ", 'LRTB', 0, 'C', 0);
        $this->objPdf->Ln();
    }
}
