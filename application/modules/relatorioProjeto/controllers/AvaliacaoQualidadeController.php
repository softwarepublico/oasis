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

class RelatorioProjeto_AvaliacaoQualidadeController extends Base_Controller_Action
{
	private $objContrato;
	private $objContratoProjeto;
	private $objRelAvaliacaoQualidade;

	private $cd_contrato;
	private $cd_projeto;

	private $objPdf;
	private $w = array(180,20,160,420,90);
	private $h = array(8,6);
	
	public function init(){

		parent::init();
		$this->objContrato              = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto       = new ContratoProjeto($this->_request->getControllerName());
		$this->objRelAvaliacaoQualidade = new RelatorioAvaliacaoQualidade();
	}

	public function indexAction(){

        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_AVALIACAO_QUALIDADE'));
		$cd_contrato		= null;
		$comStatus			= true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}

	public function pesquisaProjetoAction(){

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

	public function avaliacaoQualidadeAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$this->cd_contrato = (int) $this->_request->getParam("cd_contrato");
		$this->cd_projeto  = (int) $this->_request->getParam("cd_projeto");

		if($this->cd_projeto === 0){
			$this->preparaDadosAvaliacaoGlobalContrato();
		}else{
			$this->geraRelatorioAvaliacaoProjeto();
		}
	}

	private function preparaDadosAvaliacaoGlobalContrato(){

		$arrProjetoContrato = $this->objContratoProjeto->listaProjetosContrato($this->cd_contrato);
		$arrImpressao = array();

		foreach ($arrProjetoContrato as $key=>$value) {
			$resultSet = $this->objRelAvaliacaoQualidade->getPercentualAvaliacaoProjeto($key);

            //TODO: o if foi colocado pois alguns projetos não possuem proposta ai dava erro na montagem do arrImpressao
            if($resultSet->valid()){
                $arrImpressao[$key]['tx_sigla_projeto'				] = $resultSet->current()->tx_sigla_projeto;
                $arrImpressao[$key]['tx_projeto'					] = $resultSet->current()->tx_projeto;
                $arrImpressao[$key]['percentual_avaliacao_projeto'	] = $resultSet->current()->precentual_avaliacao_projeto;
            }
		}

		$this->geraRelatorioAvaliacaoGlobalContrato($arrImpressao);
	}

	private function geraRelatorioAvaliacaoProjeto(){

		$resultSet = $this->objRelAvaliacaoQualidade->getPropostaProjeto($this->cd_projeto);

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf();

		$this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_AVALIACAO_QUALIDADE'), K_CREATOR_SYSTEM.', '.Base_Util::getTranslator('L_TIT_REL_AVALIACAO_QUALIDADE').', '.
                                                                                                                        Base_Util::getTranslator('L_VIEW_QUALIDADE').', '.
                                                                                                                        Base_Util::getTranslator('L_VIEW_AVALIACAO'));
		$this->objPdf->SetDisplayMode("real");

		// add a page
		$this->objPdf->AddPage();

		if($resultSet->valid()){

			$this->objPdf->SetFont('', 'B',8);
			$this->objPdf->Cell($this->w[1], $this->h[1], Base_Util::getTranslator('L_VIEW_NOME').':', '', 0, 'L');
			$this->objPdf->SetFont('', '',8);
			$this->objPdf->Cell($this->w[2], $this->h[1], $resultSet->current()->tx_projeto, '', 1, 'L');

			$this->objPdf->SetFont('', 'B',8);
			$this->objPdf->Cell($this->w[1], $this->h[1], Base_Util::getTranslator('L_VIEW_SIGLA').':', '', 0, 'L');
			$this->objPdf->SetFont('', '',8);
			$this->objPdf->Cell($this->w[2], $this->h[1], $resultSet->current()->tx_sigla_projeto, '', 1, 'L');

			$this->objPdf->Ln($this->h[0]);

			$html  = '<table border="1" cellpadding="2">';
			$html .= '	<thead>';
			$html .= '		<tr align="center" bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'">';
			$html .= '			<th style="width: '.$this->w[3].'px;"><b>'.Base_Util::getTranslator('L_VIEW_PROPOSTA').'</b></th>';
			$html .= '			<th style="width: '.$this->w[4].'px;"><b>'.Base_Util::getTranslator('L_VIEW_INDICE_AVALIACAO').'</b></th>';
			$html .= '		</tr>';
			$html .= '	</thead>';
			$html .= '	<tbody>';

			$totalAvaliacaoProposta = 0;
			$qtdProposta			= 0;
			$comObs					= false;
			$fill					= false;
			foreach($resultSet as $proposta){
				$totalAvaliacaoProposta += $proposta->nf_indice_avaliacao_proposta;

				$indice = ($proposta->nf_indice_avaliacao_proposta) ? $proposta->nf_indice_avaliacao_proposta." %" : "---";
				$obs	= '';
				if(!$proposta->nf_indice_avaliacao_proposta){
					$obs	= ' (*)';
					$comObs = true;
				}

				$bgcolor = ($qtdProposta%2==0) ? '' : 'bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'"';

				$html .= '<tr '.$bgcolor.'>';
				$html .= '	<td align="justify" style="width: '.$this->w[3].'px;">'.$proposta->proposta.$obs.'</td>';
				$html .= '	<td align="center"  style="width: '.$this->w[4].'px;">'.$indice.'</td>';
				$html .= '</tr>';

				$qtdProposta++;
				$fill = !$fill;
			}

			$html .= '	</tbody>';
			$html .= '</table>';

			$html .= '<table>';

			if($comObs){
				$html .= '<tr>';
				$html .= '	<td align="justify" colspan="2" style="font-size: 7; width: '.($this->w[3]+$this->w[4]).'px;">(*) '.Base_Util::getTranslator('L_MSG_ALERT_QUESTIONARIO_AVALIACAO_SEM_CALCULO_IMPACTO_AVALIACAO_GLOBAL').'</td>';
				$html .= '</tr>';
			}

			//calcula a média aritmética dos pontos obtidos em todas as avaliações de proposta
			$avaliacaoProjeto = $totalAvaliacaoProposta / $qtdProposta;

			$html .= '	<tr>';
			$html .= '		<td colspan="2"></td>';
			$html .= '	</tr>';
			$html .= '	<tr>';
			$html .= '		<td align="center" colspan="2" style="font-size: 12;"><b>'.Base_Util::getTranslator('L_VIEW_AVALIACAO_PROJETO').': </b>'.round($avaliacaoProjeto, 2).' %</td>';
			$html .= '	</tr>';
			$html .= '</table>';

			$this->objPdf->writeHTML($html,true, 0, true, 0);

		}else{
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$this->objPdf->Output('relatorio_avaliacao_qualidade_projeto.pdf', 'I');
	}

	private function geraRelatorioAvaliacaoGlobalContrato($arrImpressao){

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf();

        		$this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_AVALIACAO_QUALIDADE_GLOBAL'), K_CREATOR_SYSTEM.', '.
                                                                                                                 Base_Util::getTranslator('L_TIT_REL_AVALIACAO_QUALIDADE_GLOBAL').', '.
                                                                                                                 Base_Util::getTranslator('L_VIEW_QUALIDADE').', '.
                                                                                                                 Base_Util::getTranslator('L_VIEW_AVALIACAO'));
		$this->objPdf->SetDisplayMode("real");
		// add a page
		$this->objPdf->AddPage();

		if(count($arrImpressao) > 0){
			$resultSet = $this->objRelAvaliacaoQualidade->getDadosContrato($this->cd_contrato);

			$this->objPdf->SetFont('', 'B',8);
			$this->objPdf->Cell($this->w[1], $this->h[1], Base_Util::getTranslator('L_VIEW_CONTRATO').':', '', 0, 'L');
			$this->objPdf->SetFont('', '',8);
			$this->objPdf->Cell($this->w[2], $this->h[1], $resultSet->current()->tx_numero_contrato.' - '.$resultSet->current()->tx_objeto, '', 1, 'L');

			$this->objPdf->Ln($this->h[0]);
			
			$html  = '<table border="1" cellpadding="2">';
			$html .= '	<thead>';
			$html .= '		<tr align="center" bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'">';
			$html .= '			<th style="width: '.$this->w[3].'px;"><b>'.Base_Util::getTranslator('L_VIEW_PROJETO').'</b></th>';
			$html .= '			<th style="width: '.$this->w[4].'px;"><b>'.Base_Util::getTranslator('L_VIEW_INDICE_AVALIACAO').'</b></th>';
			$html .= '		</tr>';
			$html .= '	</thead>';
			$html .= '	<tbody>';

			$totalAvaliacaoProjeto	= 0;
			$qtdProjeto				= 0;
			$comObs					= false;
			$fill					= false;
			foreach($arrImpressao as $value){

				$totalAvaliacaoProjeto += round($value['percentual_avaliacao_projeto'],2);

				$indice = ($value['percentual_avaliacao_projeto']) ? round($value['percentual_avaliacao_projeto'],2)." %" : "---";
				$obs	= '';
				if(!$value['percentual_avaliacao_projeto']){
					$obs	= ' (*)';
					$comObs = true;
				}

				$bgcolor = ($qtdProjeto%2==0) ? '' : 'bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'"';

				$html .= '<tr '.$bgcolor.'>';
				$html .= '	<td align="justify" style="width: '.$this->w[3].'px;">'.$value['tx_sigla_projeto'].' - '.$value['tx_projeto'].$obs.'</td>';
				$html .= '	<td align="center"  style="width: '.$this->w[4].'px;">'.$indice.'</td>';
				$html .= '</tr>';
				
				$qtdProjeto++;
				$fill = !$fill;
			}
			$html .= '	</tbody>';
			$html .= '</table>';

			$html .= '<table>';

			if($comObs){
				$html .= '<tr>';
				$html .= '	<td align="justify" colspan="2" style="font-size: 7; width: '.($this->w[3]+$this->w[4]).'px;">(*) '.Base_Util::getTranslator('L_MSG_ALERT_SEM_AVALIACAO_PROPOSTA_IMPACTO_AVALIACAO_GLOBAL').'</td>';
				$html .= '</tr>';
			}

			//calcula a média aritmética dos pontos obtidos em todos os projetos
			$avaliacaoContrato = $totalAvaliacaoProjeto/ $qtdProjeto;

			$html .= '	<tr>';
			$html .= '		<td colspan="2"></td>';
			$html .= '	</tr>';
			$html .= '	<tr>';
			$html .= '		<td align="center" colspan="2" style="font-size: 12;"><b>'.Base_Util::getTranslator('L_VIEW_AVALIACAO_CONTRATO').': </b>'.round($avaliacaoContrato, 2).' %</td>';
			$html .= '	</tr>';
			$html .= '</table>';

			$this->objPdf->writeHTML($html,true, 0, true, 0);
		}else{
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}

		//Close and output PDF document
		$this->objPdf->Output('relatorio_avaliacao_qualidade_contrato.pdf', 'I');
	}

}