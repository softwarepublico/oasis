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

class RelatorioProjeto_DistribuicaoRecursoController extends Base_Controller_Action
{
	private $arrImpressao;
	
	private $objContrato;
	private $objRelatorioContrato;
	private $objContratoDefinicaoMetrica;
	private $siglaMetricaPadraoContrato;
	private $comMetricaPadrao;

	public function init()
	{
		parent::init();
		$this->objContrato					= new Contrato($this->_request->getControllerName());
		$this->objRelatorioContrato			= new RelatorioProjetoContrato();
		$this->objContratoDefinicaoMetrica	= new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DISTRIBUICAO_RECURSO'));

		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->comboContrato	= $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function distribuicaoRecursoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = (int) $this->_request->getParam('cd_contrato',0);

		$arrDadosMetricaPadrao			  = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);

		if( count($arrDadosMetricaPadrao) == 0 ){
			$this->comMetricaPadrao			  = false;
		}else{
			$this->comMetricaPadrao			  = true;
			$this->siglaMetricaPadraoContrato = $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
			$arrProjetosPrevistos			  = $this->objRelatorioContrato->getProjetoPrevistoDistribuicaoRecuso($cd_contrato);

			if( $arrProjetosPrevistos ){
				foreach( $arrProjetosPrevistos as $key=>$value ){

					$value['recursos_alocados'] = $this->objRelatorioContrato->getDistribuicaoRecusoPorProjeto( $value['cd_projeto_previsto'] );
					$this->arrImpressao[] 		= $value;
				}
			}
		}
		$this->geraRelatorio();
	}
	
	/**
	 * Método para gerar relatorio apartir da opção de Medição
	 */
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_DISTRIBUICAO_RECURSO'),
                                 K_CREATOR_SYSTEM.', '.
                                 Base_Util::getTranslator('L_TIT_REL_DISTRIBUICAO_RECURSO').', '.
                                 Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                 Base_Util::getTranslator('L_VIEW_RECURSO').', '.
                                 Base_Util::getTranslator('L_VIEW_DISTRIBUICAO'));
		$objPdf->SetDisplayMode("real");

		$objPdf->AddPage();
		// set font
		$objPdf->SetFont('helvetica', '', 8);

		$w = array(180, 90);

		if( $this->comMetricaPadrao ){
			if( count($this->arrImpressao) > 0){

				$objPdf->SetFillColor(240, 248, 255);
				$objPdf->SetTextColor(0);

				$strTable = "";
				foreach( $this->arrImpressao as $value ){

					//largura das colunas da tabela
					$w1 = 399;
					$w2 = 110;

					$strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
					$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'">';
					$strTable .= '  <td width="'.$w1.'px" style="text-align:left;" ><b>'. Base_Util::getTranslator('L_VIEW_PROJETO_PREVISTO') .'</b></td>';
					$strTable .= '  <td width="'.$w2.'px" style="text-align:center;" ><b>'. Base_Util::getTranslator('L_VIEW_UNID_METRICA_PREVISTA') .'</b></td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td width="'.$w1.'px" style="text-align:justify;">'.$value['tx_projeto_previsto'].'</td>';
					$strTable .= '  <td width="'.$w2.'px" style="text-align:center;" >'.$value['ni_horas_projeto_previsto'].' '.$this->siglaMetricaPadraoContrato.'</td>';
					$strTable .= '</tr>';
					$strTable .= '</table>';
					$strTable .= '<br>';

					$strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
					$strTable .= '<tr>';
					$strTable .= '  <td width="'.$w1.'px" style="text-align:left;" ><b>'. Base_Util::getTranslator('L_VIEW_DESTINACAO_ALOCACAO_RECURSO') .'</b></td>';
					$strTable .= '  <td width="'.$w2.'px" style="text-align:center;" ><b>'. Base_Util::getTranslator('L_VIEW_UNID_METRICA_ALOCADA') .'</b></td>';
					$strTable .= '</tr>';

					$totalRecurso = 0;
					if(count($value['recursos_alocados']) > 0 ){

						$fill = false;
						foreach( $value['recursos_alocados'] as $recurso){

							if(!$fill){
								$strTable .= '<tr>';
								$strTable .= '  <td width="'.$w1.'px" style="text-align:justify;">'.$recurso['tx_sigla_projeto'].'</td>';
								$strTable .= '  <td width="'.$w2.'px" style="text-align:center;" >'.$recurso['ni_horas'].' '.$this->siglaMetricaPadraoContrato.'</td>';
								$strTable .= '</tr>';
							}else{
								$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'">';
								$strTable .= '  <td width="'.$w1.'px" style="text-align:justify;">'.$recurso['tx_sigla_projeto'].'</td>';
								$strTable .= '  <td width="'.$w2.'px" style="text-align:center;" >'.$recurso['ni_horas'].' '.$this->siglaMetricaPadraoContrato.'</td>';
								$strTable .= '</tr>';
							}
							$totalRecurso += $recurso['ni_horas'];
							$fill = !$fill;
						}
						$strTable .= '<tr>';
						$strTable .= '  <td width="'.$w1.'px" style="text-align:left;"><b>'. $this->toUpper(Base_Util::getTranslator('L_VIEW_TOTAL_ALOCADO')) .':</b></td>';
						$strTable .= '  <td width="'.$w2.'px" style="text-align:center;" >'.$totalRecurso.' '.$this->siglaMetricaPadraoContrato.'</td>';
						$strTable .= '</tr>';
					}else{
						$strTable .= '<tr>';
						$strTable .= '  <td width="'.($w1+$w2).'px" style="text-align:center;" colspan="2">'. Base_Util::getTranslator('L_MSG_ALERT_SEM_REGISTRO_ALOCACAO_RECURSO') .'</td>';
						$strTable .= '</tr>';
					}
					$strTable .= '</table>';
					$strTable .= '<br>';
					$strTable .= '<br>';
				}
				$objPdf->writeHTML($strTable,true, 0, true, 0);
			}else{
				$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
			}
		}else{
			$objPdf->SetFont('helvetica', 'B', 10);
			$objPdf->Cell(180, 6, Base_Util::getTranslator('L_MSG_ALERT_CONTRATO_SEM_METRICA_PADRAO_NAO_GERA_RELATORIO'), false, true, "C");
			$objPdf->Ln(5);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_extrato_geral_execucao_contrato.pdf', 'I');
	}
}
 

