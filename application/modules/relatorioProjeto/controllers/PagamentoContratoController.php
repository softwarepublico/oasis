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

class RelatorioProjeto_PagamentoContratoController extends Base_Controller_Action
{
	private $objContrato;
	private $objRelContrato;
	private $objUtil;
	private $arrContrato   = array();
	private $arrHeader     = array();
	private $arrHoras      = array();
	private $arrValorHoras = array();
	private $comMetricaPadrao;
	private $siglaMetricaPadraoContrato;
	
	public function init()
	{
		parent::init();
		
		$this->objContrato    = new Contrato($this->_request->getControllerName()); 
		$this->objRelContrato = new RelatorioProjetoContrato();
		$this->objUtil        = new Base_Controller_Action_Helper_Util();
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_EXTRATO_PAGAMENTO_CONTRATO'));

		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->comboContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function pagamentoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = $this->_request->getParam("cd_contrato");

		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());

		$arrDadosMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);

		
		if( count($arrDadosMetricaPadrao) == 0 ){
			$this->comMetricaPadrao			  = false;
		}else{
			$this->comMetricaPadrao			  = true;
			$this->siglaMetricaPadraoContrato = $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
			$this->arrContrato                = $this->objRelContrato->extratoPagamentoContrato($cd_contrato);
		}
		$this->geraRelatorio();
	}
	
	private function montaDadosRelatorio()
	{
		$this->arrHeader[0]     = Base_Util::getTranslator('L_VIEW_MESES');
		$this->arrHoras[0]      = $this->siglaMetricaPadraoContrato;
		//$this->arrHoras[0]      = "Horas";
		$this->arrValorHoras[0] = Base_Util::getTranslator('L_VIEW_TOTAL');
		
		//Quantidade de meses do contrato, está diminuindo 2 pq já tenho o mes inicio e o fim
		$quantMeses = $this->arrContrato[0]['ni_qtd_meses_contrato'] - 1;
		
		$mesInicial = $this->arrContrato[0]['ni_mes_inicial_contrato'];
		$anoInicial = $this->arrContrato[0]['ni_ano_inicial_contrato'];
		
		$horasTotal = $valorTotal = 0;
		
		for($i=0;$i <= $quantMeses;$i++){
			$this->arrHeader[$i+1] = "{$this->objUtil->getMesRes($mesInicial)}/{$anoInicial}";
			
			foreach($this->arrContrato as $key=>$value){
				if(($value['ni_mes_extrato'] == $mesInicial) && ($value['ni_ano_extrato'] == $anoInicial)){
					$this->arrHoras[$i+1] = $value['ni_horas_extrato'];
					$valorHoras = $value['ni_horas_extrato']*$value['nf_valor_unitario_hora'];
					$this->arrValorHoras[$i+1] = $valorHoras;
					$horasTotal += $value['ni_horas_extrato'];
					$valorTotal += $valorHoras;
					
					break;
				} else {
					$horasTotal += 0;
					$valorTotal += 0;
					$this->arrHoras[$i+1]      = "0";
					$this->arrValorHoras[$i+1] = "0"; 
				}
			}
			
			if($mesInicial == 12){
				$mesInicial = 1;
				$anoInicial++;
			} else {
				$mesInicial++;
			}
		}
		
		$this->arrHeader[$quantMeses+2]     = Base_Util::getTranslator('L_VIEW_TOTAL_GERAL');
		$this->arrHoras[$quantMeses+2]      = $horasTotal;
		$this->arrValorHoras[$quantMeses+2] = $valorTotal;
	}
		
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf('L');

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_EXTRATO_PAGAMENTO_CONTRATO'), K_CREATOR_SYSTEM.', '.
                                                                                          Base_Util::getTranslator('L_TIT_REL_EXTRATO_PAGAMENTO_CONTRATO').', '.
                                                                                          Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                          Base_Util::getTranslator('L_VIEW_EXTRATO'));
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();

        $font     = 'helvetica';
        $fontSize = 8;

		if( $this->comMetricaPadrao ){

			if(count($this->arrContrato) > 0 ){

				$this->montaDadosRelatorio();

				$w = array(90,90);

				$objPdf->SetFont($font, 'B', $fontSize);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_CONTRATO').":");
				$objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PERIODO_EXECUCAO').":");
				$objPdf->Ln(4);
				$objPdf->SetFont($font, '', $fontSize);
				$objPdf->Cell($w[0], 6, $this->arrContrato[0]['tx_numero_contrato']);
				$count = count($this->arrHeader);
				$objPdf->Cell($w[1], 6, "{$this->arrHeader[1]} a {$this->arrHeader[$count-2]}");
				$objPdf->Ln(5);

				$objPdf->SetFont($font, 'B', $fontSize);
				//$objPdf->Cell($w[0], 6, "Total de Horas:");
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_TOTAL_UNIDADE_METRICA').":");
				$objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_MEDIA_METRICA_MENSAL', $this->siglaMetricaPadraoContrato).":");
				$objPdf->Ln(4);
				$objPdf->SetFont($font, '', $fontSize);
				$objPdf->Cell($w[0], 6, number_format($this->arrContrato[0]['ni_horas_previstas'],1,',','.')." ".$this->siglaMetricaPadraoContrato);
				$mediaHorasMensal = $this->arrContrato[0]['ni_horas_previstas']/$this->arrContrato[0]['ni_qtd_meses_contrato'];
				$objPdf->Cell($w[1], 6, number_format($mediaHorasMensal,1,',','.'));
				$objPdf->Ln(10);

				foreach($this->arrHeader as $key=>$value){
						$objPdf->SetDrawColor(50);
						$objPdf->SetFillColor(240,240,240);
						$objPdf->SetTextColor(0);
						$objPdf->SetFont($font, 'B', $fontSize);
					if($key == 0){
						$objPdf->Cell(12,6,$value,'LBTR',0,"C",1);
					} else {
						$objPdf->Cell(20,6,$value,'LBTR',0,"C",1);
					}
				}
				$objPdf->Ln(6);

				foreach($this->arrHoras as $chave=>$valor){
					if($chave == 0){
						$objPdf->SetDrawColor(50);
						$objPdf->SetFillColor(240,240,240);
						$objPdf->SetTextColor(0);
						$objPdf->SetFont($font, 'B', $fontSize);
						$objPdf->Cell(12,6,$valor,'LBTR',0,"C",1);
					} else {
						$objPdf->SetFont($font, '', $fontSize);
						$objPdf->Cell(20,6,number_format($valor,1,',','.'),'LBTR',0,"C");
					}
				}
				$objPdf->Ln(6);
				foreach($this->arrValorHoras as $chave=>$valorHoras){
					if($chave == 0){
						$objPdf->SetDrawColor(50);
						$objPdf->SetFillColor(240,240,240);
						$objPdf->SetTextColor(0);
						$objPdf->SetFont($font, 'B', $fontSize);
						$objPdf->Cell(12,6,$valorHoras,'LBTR',0,"C",1);
					} else {
						$objPdf->SetFont($font, '', $fontSize);
						$objPdf->Cell(20,6, Base_Util::getTranslator('L_VIEW_CIFRAO_MOEDA')." ".number_format($valorHoras,2,',','.'),'LBTR',0,"C",0,'',1);
					}
				}
				$objPdf->Ln(6);
				$objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
			}else{
				$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
			}
		}else{
			$objPdf->SetFont($font, 'B', $fontSize+2);
			$objPdf->Cell(180, 6, Base_Util::getTranslator('L_MSG_ALERT_CONTRATO_SEM_METRICA_PADRAO_NAO_GERA_RELATORIO'), false, true, "C");
			$objPdf->Ln(5);
		}

		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_pagamento_contrato.pdf', 'I');
	}

}