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

class RelatorioProjeto_ConteudoBaselineController extends Base_Controller_Action
{
	private $objProjeto;
	private $objBaseline;
	private $objRelatorioBaseline;
	private $arrImpressao;
	private $arrDadosProjeto;
	private $dt_baseline;
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		
		$this->objProjeto			= new Projeto($this->_request->getControllerName());
		$this->objBaseline			= new Baseline($this->_request->getControllerName());
		$this->objRelatorioBaseline	= new RelatorioProjetoBaseline();
		$this->objContrato			= new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto	= new ContratoProjeto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CONTEUDO_BASELINE'));
        
        $cd_contrato = null;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
		}
		
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato);
	}
	
	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $options;
	}
	
	public function getComboGroupBaselineAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');

		$baselineAtiva	= $this->objBaseline->getBaselineAtivaInativa( $cd_projeto, "A" );
		$baselineInativa = $this->objBaseline->getBaselineAtivaInativa( $cd_projeto, "I" );

		//Monta Group da Baseline Ativa
		$strOption = "<option selected=\"selected\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		
		$strOption .= "<optgroup label=\"".Base_Util::getTranslator('L_VIEW_ATIVA')."\">";
		if($baselineAtiva->valid()){
			foreach($baselineAtiva as $value){
                $dt_format = date('d/m/Y H:i:s', strtotime($value->dt_baseline));
				$strOption .= "<option label=\"{$dt_format}\" value=\"{$value->dt_baseline}\">{$dt_format}</option>";
			}
		} else {
			$strOption .= "<option disabled=\"disabled\" value=\"999999\">".Base_Util::getTranslator('L_VIEW_SEM_BASELINE_ATIVA')."</option>";
		}
		$strOption .= "</optgroup>";

		//Monta Group de Baseline Inativas
		$strOption .= "<optgroup label=\"".Base_Util::getTranslator('L_VIEW_INATIVA')."\">";
		if($baselineInativa->valid()){
			foreach($baselineInativa as $value){
                $dt_format = date('d/m/Y H:i:s', strtotime($value->dt_baseline));
				$strOption .= "<option label=\"{$dt_format}\" value=\"{$value->dt_baseline}\">{$dt_format}</option>";
			}
		} else {
			$strOption .= "<option disabled=\"disabled\" value=\"99999\">".Base_Util::getTranslator('L_VIEW_SEM_BASELINE_INATIVA')."</option>";
		}
		$strOption .= "</optgroup>";

		echo $strOption;
	}
	
	public function conteudoBaselineAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post = $this->_request->getPost();
		
		$cd_projeto  = $post['cd_projeto'];
		$this->dt_baseline = $post['dt_baseline'];
		
		if( array_key_exists('chk_requisito', $post) ){
			$this->arrImpressao['requisitos'] = $this->objRelatorioBaseline->getDadosRequisito( $cd_projeto, $this->dt_baseline );
		}
		if( array_key_exists('chk_regra_negocio', $post) ){
			$this->arrImpressao['regras_negocio'] = $this->objRelatorioBaseline->getDadosRegraDeNegocio( $cd_projeto, $this->dt_baseline );
		}
		if( array_key_exists('chk_caso_uso', $post) ){
			$this->arrImpressao['caso_uso'] = $this->objRelatorioBaseline->getDadosCasoDeUso( $cd_projeto, $this->dt_baseline );
		}
		if( array_key_exists('chk_proposta', $post) ){
			$this->arrImpressao['proposta'] = $this->objRelatorioBaseline->getDadosProposta( $cd_projeto, $this->dt_baseline );
		}
		$this->arrDadosProjeto = $this->objProjeto->getDadosProjeto( $cd_projeto );

		$this->geraRelatorio();
	}

	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_CONTEUDO_BASELINE'),
                                 K_CREATOR_SYSTEM.', '.
                                 Base_Util::getTranslator('L_TIT_REL_CONTEUDO_BASELINE').', '.
                                 Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                 Base_Util::getTranslator('L_VIEW_BASELINE'));
		$objPdf->SetDisplayMode("real");
		$objPdf->AddPage();
		
		if(count($this->arrImpressao) > 0){

			$objPdf->SetFillColor(240,240,240);
			$objPdf->SetTextColor(0);
			
			$objPdf->SetFont('helvetica', 'B', 8);
			$objPdf->Cell(15,6, Base_Util::getTranslator('L_VIEW_PROJETO').":");
			$objPdf->SetFont('helvetica', '', 8);
			$objPdf->Cell(165,6,$this->arrDadosProjeto[0]['tx_sigla_projeto'],'',1);
			$objPdf->Cell(180,2,'','',1);
			
			$strTable = "";
			foreach( $this->arrImpressao as $key=>$value ){
				
				switch($key){
					case 'requisitos'	 : $titulo = $this->toUpper( Base_Util::getTranslator('L_VIEW_REQUISITO')    ); break;
					case 'proposta'		 : $titulo = $this->toUpper( Base_Util::getTranslator('L_VIEW_PROPOSTA')     ); break;
					case 'regras_negocio': $titulo = $this->toUpper( Base_Util::getTranslator('L_VIEW_REGRA_DE_NEGOCIO')); break;
					case 'caso_uso'		 : $titulo = $this->toUpper( Base_Util::getTranslator('L_VIEW_CASO_DE_USO')  );	break;
				}
				
				//tres colunas
				if( $titulo != "PROPOSTA"){

					$strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
					$strTable .= '<tr>';
					$strTable .= '  <td bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" width="510px" style="text-align:left;" colspan="3" ><b>'.$titulo.'</b></td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td  width="310px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_NOME'  ).'</b></td>';
					$strTable .= '  <td  width="80px" style="text-align:center;" ><b>'.Base_Util::getTranslator('L_VIEW_VERSAO').'</b></td>';
					$strTable .= '  <td  width="120px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_DATA'  ).'</b></td>';
					$strTable .= '</tr>';
					
					if( count($value) > 0){
						$fill = false;
						foreach( $value as $conteudo){
							if( !$fill ){
								$strTable .= '<tr>';
								$strTable .= '  <td  width="310px" style="text-align:justify;">'.$conteudo['tx_conteudo'].'</td>';
								$strTable .= '  <td  width="80px" style="text-align:center;" >'.$conteudo['ni_versao_item_conteudo'].'</td>';
								$strTable .= '  <td  width="120px" style="text-align:center;" >'.date('d/m/Y H:i:s',strtotime($conteudo['dt_versao_item_conteudo'])).'</td>';
								$strTable .= '</tr>';
							}else{
								$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'">';
								$strTable .= '  <td width="310px" style="text-align:justify;">'.$conteudo['tx_conteudo'].'</td>';
								$strTable .= '  <td width="80px" style="text-align:center;" >'.$conteudo['ni_versao_item_conteudo'].'</td>';
								$strTable .= '  <td  width="120px" style="text-align:center;" >'.date('d/m/Y H:i:s',strtotime($conteudo['dt_versao_item_conteudo'])).'</td>';
								$strTable .= '</tr>';
							}
							$fill = !$fill;
						}
					}else{
						$strTable .= '<tr>';
						$strTable .= '  <td width="510px" style="text-align:center;" colspan="3" ><b>'.Base_Util::getTranslator('L_MSG_ALERT_SEM_REGISTRO_BASELINE').'</b></td>';
						$strTable .= '</tr>';
					}
					$strTable .= '</table>';
					$strTable .= '<br>';
					$strTable .= '<br>';

				// duas colunas quando for proposta
				}else{
					$strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
					$strTable .= '<tr>';
					$strTable .= '  <td bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" width="510px" style="text-align:left;" colspan="2" ><b>'.$titulo.'</b></td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td  width="390px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_NOME').'</b></td>';
					$strTable .= '  <td  width="120px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_DATA').'</b></td>';
					$strTable .= '</tr>';
					
					if( count($value) > 0){
						$fill = false;
						foreach( $value as $conteudo){
							if( !$fill ){
								$strTable .= '<tr>';
								$strTable .= '  <td width="390px" style="text-align:justify;">'.$conteudo['tx_conteudo'].'</td>';
								$strTable .= '  <td width="120px" style="text-align:center;" >'.$conteudo['dt_versao_item_conteudo'].'</td>';
								$strTable .= '</tr>';
							}else{
								$strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'">';
								$strTable .= '  <td width="390px" style="text-align:justify;">'.$conteudo['tx_conteudo'].'</td>';
								$strTable .= '  <td width="120px" style="text-align:center;" >'.$conteudo['dt_versao_item_conteudo'].'</td>';
								$strTable .= '</tr>';
							}
							$fill = !$fill;
						}
					}else{
						$strTable .= '<tr>';
						$strTable .= '  <td width="510px" style="text-align:center;" colspan="3" ><b>'.Base_Util::getTranslator('L_MSG_ALERT_SEM_REGISTRO_BASELINE').'</b></td>';
						$strTable .= '</tr>';
					}
					$strTable .= '</table>';
					$strTable .= '<br>';
					$strTable .= '<br>';
				}
			}
			$objPdf->writeHTML($strTable,true, 0, true, 0);
		} else {
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_conteudo_baseline.pdf', 'I');
	}
				
}

