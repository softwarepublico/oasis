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

class RelatorioDemanda_DemandaController extends Base_Controller_Action
{
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DEMANDA'));

		$objUnidade						= new Unidade($this->_request->getControllerName());
		$objObjetoContrato				= new ObjetoContrato($this->_request->getControllerName());
		$objProfissionalObjetoContrato	= new ProfissionalObjetoContrato($this->_request->getControllerName());
		$cd_objeto						= $_SESSION['oasis_logged'][0]['cd_objeto'];
		
		$this->view->arrObjetoContrato	= $objObjetoContrato->getObjetoContrato(null,true,'A');
		$this->view->arrUnidade			= $objUnidade->getUnidade(true);
		$this->view->arrProfissional	= $objProfissionalObjetoContrato->getProfissionalGerenteTecnicoObjetoContrato($cd_objeto, true);
	}
	
	public function demandaAction()
	{
   		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        
		$objRelDemanda = new RelatorioDemanda();

		$cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$tx_demanda      = $this->_request->getParam('tx_descricao',0); 
		$cd_profissional = $this->_request->getParam('cd_profissional'); 
		$dt_inicio       = $this->_request->getParam('dt_inicio',0); 
		$dt_fim          = $this->_request->getParam('dt_fim',0); 
		$tx_solicitante  = $this->_request->getParam('tx_solicitante',0); 
		$cd_unidade      = $this->_request->getParam('cd_unidade',0); 
		
		$cd_objeto       = ($cd_objeto != 0)			? $cd_objeto		: null;
		$cd_profissional = ($cd_profissional != '-1')	? $cd_profissional	: null;
		$cd_unidade      = ($cd_unidade != 0)			? $cd_unidade		: null;
		$tx_demanda      = ($tx_demanda != 0)			? $tx_demanda		: null;
		$tx_solicitante  = ($tx_solicitante != 0)		? $tx_solicitante	: null;

        if($dt_inicio != 0){
            $dt_inicio = Base_Util::converterDate($dt_inicio, 'DD/MM/YYYY', 'YYYY-MM-DD');
        }else{
            $dt_inicio = null;
        }
        if($dt_fim != 0){
            $dt_fim = Base_Util::converterDate($dt_fim, 'DD/MM/YYYY', 'YYYY-MM-DD');
        }else{
            $dt_fim = null;
        }

		$arrDemanda = $objRelDemanda->getDemandaResumido($cd_objeto,$tx_demanda, $cd_profissional, $dt_inicio,$dt_fim,$tx_solicitante,$cd_unidade);
		$this->geraRelatorio($arrDemanda);
	}

	private function geraRelatorio($arrDemanda)
    {
		$objPdf = new Base_Tcpdf_Pdf('L',PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_DEMANDA'), $arrKeywords);
        
        // add a page
		$objPdf->AddPage();

		if(count($arrDemanda) > 0){
			$strRelatorio  = '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
			$strRelatorio .= '<thead>';
			$strRelatorio .= '  <tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" style="text-align:center;">';
			$strRelatorio .= '     <th style="font-weight:bold; width:75px;" >'.Base_Util::getTranslator('L_VIEW_SOLICITACAO'      ).'</th>';
			$strRelatorio .= '     <th style="font-weight:bold; width:70px;" >'.Base_Util::getTranslator('L_VIEW_INICIO'           ).'</th>';
			$strRelatorio .= '     <th style="font-weight:bold; width:70px;" >'.Base_Util::getTranslator('L_VIEW_DURACAO'          ).'</th>';
			$strRelatorio .= '     <th style="font-weight:bold; width:70px;" >'.Base_Util::getTranslator('L_VIEW_UNIDADE'          ).'</th>';
			$strRelatorio .= '     <th style="font-weight:bold; width:150px;">'.Base_Util::getTranslator('L_VIEW_SOLICITANTE'      ).'</th>';
			$strRelatorio .= '     <th style="font-weight:bold; width:223px;">'.Base_Util::getTranslator('L_VIEW_DESCRICAO_DEMANDA').'</th>';
			$strRelatorio .= '     <th style="font-weight:bold; width:100px;">'.Base_Util::getTranslator('L_VIEW_PROFISSIONAL'     ).'</th>';
			$strRelatorio .= '  </tr>';
			$strRelatorio .= '</thead>';
			$strRelatorio .= '<tbody>';

			foreach($arrDemanda as $key=>$value){
				$strRelatorio .= '<tr>';
					//Solicitação
					$tx_solicitacao = (!is_null($value->tx_solicitacao))?$value->tx_solicitacao:"---";
					$strRelatorio .= '<td style="width:75px; text-align: center;">'.$tx_solicitacao.'</td>';
					//Data de inicio da Demanda
					$dt_demanda = (!is_null($value->dt_demanda)) ? date('d/m/Y H:m:s',strtotime($value->dt_demanda)):"---";
					$strRelatorio .= '<td style="width:70px; text-align: center;">'.$dt_demanda.'</td>';
					//Duração da Demanda
					$strTempo = "";
					if(!is_null($value->dt_tempo_fechamento)){
						$strTempo = str_ireplace("-"," ",$value->dt_tempo_fechamento);
						$strTempo = str_ireplace("days", $this->toLower(Base_Util::getTranslator('L_VIEW_DIAS')), $strTempo);
						$strTempo = str_ireplace("month",$this->toLower(Base_Util::getTranslator('L_VIEW_MES' )), $strTempo);
						$strTempo = str_ireplace("year", $this->toLower(Base_Util::getTranslator('L_VIEW_ANO' )), $strTempo);
					} else {
						$strTempo = "---";
					}
					$strRelatorio .= '   <td style="width:70px; text-align: center;">'.$strTempo.'</td>';
					//Unidade do Solicitante
					$unidade = "";
					if(!is_null($value->tx_sigla_unidade)){
						$arrUnidade = explode("/",$value->tx_sigla_unidade);
						$tam = count($arrUnidade);
						$unidade = $arrUnidade[$tam-1];
					} else {
						$unidade = "---";
					}
					$strRelatorio .= '   <td style="width:70px; text-align: center;">'.$unidade.'</td>';
					//Solicitante da Demanda
					$tx_solicitante_demanda = (!is_null($value->tx_solicitante_demanda))?$value->tx_solicitante_demanda:"---";
					$strRelatorio .= '   <td style="width:150px; text-align:left;">'.$tx_solicitante_demanda.'</td>';
					//Descrição da Demanda
					if(!is_null($value->tx_demanda)){
						$tx_demanda = strip_tags($value->tx_demanda);
					} else {
						$tx_demanda = "---";
					}
					$strRelatorio .= '<td style="width:223px; text-align:justify;">'.$tx_demanda.'</td>';
					//Nome do Profissional que realizou a demanda
					$tx_nome_conhecido = (!is_null($value->tx_nome_conhecido))?$value->tx_nome_conhecido:"---";
					$strRelatorio .= '   <td style="width:100px; text-align:left;">'.$tx_nome_conhecido.'</td>';
				$strRelatorio .= '</tr>';
			}
			$strRelatorio .= '</tbody>';
			$strRelatorio .= '</table>';

			$objPdf->writeHTML($strRelatorio,true,0,true, 0);
            
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta("L"),true, 0, true, 0);
		}


		//Close and output PDF document
		$objPdf->Output('relatorio_demanda.pdf', 'I');
	}
}