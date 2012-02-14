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

class RelatorioDiverso_PenalizacaoController extends Base_Controller_Action
{
	private $objContrato;
	private $objRelatorioDiversoPenalizacao;
	private $arrImpressao;
	private $objPdf;

	public function init()
	{
		parent::init();
		$this->objContrato = new Contrato($this->_request->getControllerName());
		$this->objRelatorioDiversoPenalizacao = new RelatorioDiversoPenalizacao();
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PENALIZACAO'));
        
		$this->view->comboContrato = $this->objContrato->getContrato(true);
	}

	public function penalizacaoAction(){
		
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrParams = $this->_request->getParams();

		$arrComJustificativa = $this->objRelatorioDiversoPenalizacao->getPenalizacoes($arrParams['cd_contrato'], $arrParams['dt_inicio_periodo'], $arrParams['dt_fim_periodo'] );
		$arrSemJustificativa = $this->objRelatorioDiversoPenalizacao->getPenalizacoes($arrParams['cd_contrato'], $arrParams['dt_inicio_periodo'], $arrParams['dt_fim_periodo'], false );

		$arrDadosContrato	 = $this->objContrato->getDadosContrato($arrParams['cd_contrato']);

		$this->arrImpressao['periodo_pesquisa'				] = $arrParams['dt_inicio_periodo'] ." à ". $arrParams['dt_fim_periodo'];
		$this->arrImpressao['tx_numero_contrato'			] = $arrDadosContrato[0]['tx_numero_contrato'];
		$this->arrImpressao['tx_objeto'						] = $arrDadosContrato[0]['tx_objeto'];
		$this->arrImpressao['penalizacao_com_justificativa' ] = $arrComJustificativa;
		$this->arrImpressao['penalizacao_sem_justificativa' ] = $arrSemJustificativa;

		$this->geraRelatorio();
	}

	private function geraRelatorio(){
		
		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf('L');

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_PENALIZACAO'),
                             Base_Util::getTranslator('L_VIEW_PENALIZACAO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PENALIZACAO'), $arrKeywords);

		$this->objPdf->SetDisplayMode("real");
		// set font
		$this->objPdf->AddPage();

		
		$w = array(180,15,165);

		if( ($this->arrImpressao['penalizacao_com_justificativa']->valid()) || ($this->arrImpressao['penalizacao_sem_justificativa']->valid()) ){
			
			$this->objPdf->SetFont('helvetica', 'B', 8);
			$this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PERIODO').": ",'',0,'L',0);
			$this->objPdf->SetFont('helvetica', '', 8);
			$this->objPdf->Cell($w[2], 6, $this->arrImpressao['periodo_pesquisa'],'',1,'L',0);

			$this->objPdf->SetFont('helvetica', 'B', 8);
			$this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_CONTRATO').":",'',0,'L',0);
			$this->objPdf->SetFont('helvetica', '', 8);
			$this->objPdf->Cell($w[2], 6, $this->arrImpressao['tx_numero_contrato'],'',1,'L',0);

			$this->objPdf->SetFont('helvetica', 'B', 8);
			$this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_OBJETO').":",'',0,'L',0);
			$this->objPdf->SetFont('helvetica', '', 8);
			$this->objPdf->MultiCell($w[2], 6, $this->arrImpressao['tx_objeto'].PHP_EOL,'', "J", 0, 1);

			$this->objPdf->Ln(4);
			$this->objPdf->SetFont('helvetica', 'B', 8);
			$this->objPdf->Cell(50, 6, Base_Util::getTranslator('L_MSG_ALERT_PENALIDADE_JUSTIFICATIVA_ACEITA')."",'B',1,'L',0);
			$this->objPdf->SetFont('helvetica', '', 8);
			$this->montaDadosJustificativasAceitas($w, $this->arrImpressao['penalizacao_com_justificativa']);

			$this->objPdf->Ln(4);
			$this->objPdf->SetFont('helvetica', 'B', 8);
			$this->objPdf->Cell(65, 6, Base_Util::getTranslator('L_MSG_ALERT_SEM_PENALIDADE_JUSTIFICATIVA_NAO_ACEITA'),'B',1,'L',0);
			$this->objPdf->SetFont('helvetica', '', 8);
			$this->montaDadosSemJustificativasNaoAceitas($w, $this->arrImpressao['penalizacao_sem_justificativa']);

		}else{
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$this->objPdf->Output('relatorio_medicacao.pdf', 'I');
	}

	private function montaDadosJustificativasAceitas($w, $objPenalidadeComJustificativa)
    {
		if($objPenalidadeComJustificativa->valid()){

			$html  = '<table border="1" cellpadding="2">';
			$html .= '	<thead>';
			$html .= '		<tr align="center" bgcolor="#BEBEBE">';
			$html .= '			<th style="width: 50px;" ><b>'.Base_Util::getTranslator('L_VIEW_DATA'            ).'</b></th>';
			$html .= '			<th style="width: 200px;"><b>'.Base_Util::getTranslator('L_VIEW_PENALIDADE'      ).'</b></th>';
			$html .= '			<th style="width: 150px;"><b>'.Base_Util::getTranslator('L_VIEW_OBSERVACAO'      ).'</b></th>';
			$html .= '			<th style="width: 190px;"><b>'.Base_Util::getTranslator('L_VIEW_JUSTIFICATIVA'   ).'</b></th>';
			$html .= '			<th style="width: 55px;" ><b>'.Base_Util::getTranslator('L_VIEW_QTD_OCORRENCIA'  ).'</b></th>';
			$html .= '			<th style="width: 55px;" ><b>'.Base_Util::getTranslator('L_VIEW_VALOR_PENALIDADE').'</b></th>';
			$html .= '			<th style="width: 55px;" ><b>'.Base_Util::getTranslator('L_VIEW_TOTAL_PENALIDADE').'</b></th>';
			$html .= '		</tr>';
			$html .= '	</thead>';
			$html .= '	<tbody>';

			$count = 0;
			foreach ($objPenalidadeComJustificativa as $penalidade) {

				$bgcolor = ($count%2==0) ? '' : 'bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'"';

				$html .= '		<tr '.$bgcolor.'>';
				$html .= '			<td align="center" style="width: 50px;"  >'.$penalidade->dt_penalizacao.'</td>';
				$html .= '			<td align="justify" style="width: 200px;">'.$penalidade->tx_penalidade.'</td>';
				$html .= '			<td align="justify" style="width: 150px;">'.$penalidade->tx_obs_penalizacao.'</td>';
				$html .= '			<td align="justify" style="width: 190px;">'.$penalidade->tx_justificativa_penalizacao.'</td>';
				$html .= '			<td align="center" style="width: 55px;"  >'.$penalidade->ni_qtd_ocorrencia.'</td>';
				$html .= '			<td align="center" style="width: 55px;"  >'.$penalidade->ni_valor_penalidade.'%</td>';
				$html .= '			<td align="center" style="width: 55px;"  >'.$penalidade->ni_valor_total_penalidade.'%</td>';
				$html .= '		</tr>';

				$count++;
			}
			$html .= '	</tbody>';
			$html .= '</table>';

			$this->objPdf->writeHTML($html,true, 0, true, 0);
		}else{
			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SEM_REGISTRO'),'',1,'L',0);
			$this->objPdf->Ln(4);
		}
	}

	private function montaDadosSemJustificativasNaoAceitas($w, $objPenalidadeSemJustificativa)
    {
		if($objPenalidadeSemJustificativa->valid()){

			$html  = '<table border="1" cellpadding="2">';
			$html .= '	<thead>';
			$html .= '		<tr align="center" bgcolor="#BEBEBE">';
			$html .= '			<th style="width: 50px;" ><b>'.Base_Util::getTranslator('L_VIEW_DATA'            ).'</b></th>';
			$html .= '			<th style="width: 165px;"><b>'.Base_Util::getTranslator('L_VIEW_PENALIDADE'      ).'</b></th>';
			$html .= '			<th style="width: 150px;"><b>'.Base_Util::getTranslator('L_VIEW_OBSERVACAO'      ).'</b></th>';
			$html .= '			<th style="width: 160px;"><b>'.Base_Util::getTranslator('L_VIEW_JUSTIFICATIVA'   ).'</b></th>';
			$html .= '			<th style="width: 65px;" ><b>'.Base_Util::getTranslator('L_VIEW_SITUACAO'        ).'</b></th>';
			$html .= '			<th style="width: 55px;" ><b>'.Base_Util::getTranslator('L_VIEW_QTD_OCORRENCIA'  ).'</b></th>';
			$html .= '			<th style="width: 55px;" ><b>'.Base_Util::getTranslator('L_VIEW_VALOR_PENALIDADE').'</b></th>';
			$html .= '			<th style="width: 55px;" ><b>'.Base_Util::getTranslator('L_VIEW_TOTAL_PENALIDADE').'</b></th>';
			$html .= '		</tr>';
			$html .= '	</thead>';
			$html .= '	<tbody>';

			$count = 0;
			foreach ($objPenalidadeSemJustificativa as $penalidade) {

				$bgcolor = ($count%2==0) ? '' : 'bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'"';

				$html .= '		<tr '.$bgcolor.'>';
				$html .= '			<td align="center"  style="width: 50px;" >'.$penalidade->dt_penalizacao.'</td>';
				$html .= '			<td align="justify" style="width: 165px;">'.$penalidade->tx_penalidade.'</td>';
				$html .= '			<td align="justify" style="width: 150px;">'.$penalidade->tx_obs_penalizacao.'</td>';
				$html .= '			<td align="justify" style="width: 160px;">'.$penalidade->tx_justificativa_penalizacao.'</td>';
				$html .= '			<td align="center"  style="width: 65px;" >'.$penalidade->st_aceite_justificativa.'</td>';
				$html .= '			<td align="center"  style="width: 55px;" >'.$penalidade->ni_qtd_ocorrencia.'</td>';
				$html .= '			<td align="center"  style="width: 55px;" >'.$penalidade->ni_valor_penalidade.'%</td>';
				$html .= '			<td align="center"  style="width: 55px;" >'.$penalidade->ni_valor_total_penalidade.'%</td>';
				$html .= '		</tr>';
				$count++;
			}
			$html .= '	</tbody>';
			$html .= '</table>';

			$this->objPdf->writeHTML($html,true, 0, true, 0);
		}else{
			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SEM_REGISTRO'),'',1,'L',0);
			$this->objPdf->Ln(4);
		}
	}

}
