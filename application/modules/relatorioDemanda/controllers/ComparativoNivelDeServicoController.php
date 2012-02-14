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
class RelatorioDemanda_ComparativoNivelDeServicoController extends Base_Controller_Action
{
    private $objRelDemanda;
    private $objObjetoContrato;
    private $objProfissionalObjetoContrato;
    private $objNivelDeServico;

    public function init()
    {
        parent::init();
        $this->objRelDemanda                 = new RelatorioDemanda();
        $this->objObjetoContrato             = new ObjetoContrato();
        $this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato();
        $this->objNivelDeServico             = new NivelServico();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_COMPARATIVO_NIVEL_SERVICO'));

        /*
        if($_SESSION['oasis_logged'][0]['st_dados_todos_contratos'] == ""){
            $cd_objeto   = $_SESSION['oasis_logged'][0]['cd_objeto'];
            $cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
        } else {
            $cd_objeto   = null;
            $cd_contrato = null;
        }*/
		$cd_objeto   = null;
		$cd_contrato = null;

       $this->view->arrObjetoContrato = $this->objObjetoContrato->getObjetoContratoAtivo('D', true, false, true, $cd_objeto, $cd_contrato);
    }

    public function comparativoNivelDeServicoAction()
    {
   		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $arrPost = $this->_request->getPost();


		if($arrPost['cd_profissional'] == "-1"){
			unset($arrPost['cd_profissional']);
		}
		if($arrPost['cd_nivel_servico'] == "0"){
			unset($arrPost['cd_nivel_servico']);
		}
		
		if($arrPost['dt_demanda_inicio'] == ""){
			$arrPost['dt_demanda_inicio'] = date('Y-m-d')." 00:00:00";
		} else {
			$arrPost['dt_demanda_inicio'] = Base_Util::converterDate($arrPost['dt_demanda_inicio'], 'DD/MM/YYYY', 'YYYY-MM-DD')." 00:00:00";
		}
		if($arrPost['dt_demanda_final'] == ""){
			$arrPost['dt_demanda_final'] = date('Y-m-d')." 23:59:59";
		} else {
			$arrPost['dt_demanda_final'] = Base_Util::converterDate($arrPost['dt_demanda_final'], 'DD/MM/YYYY', 'YYYY-MM-DD')." 23:59:59";
		}
        
        $arrDados = $this->objRelDemanda->comparativoNivelDeServico($arrPost);

		$this->_generate($arrDados);
    }

	private function _generate(array $arrDados)
	{
		$objPdf = new Base_Tcpdf_Pdf('L');

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_COMPARATIVO_NIVEL_SERVICO'),
                             Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_COMPARATIVO_NIVEL_SERVICO'), $arrKeywords);

		// add a page
		$objPdf->AddPage();

		if(count($arrDados) > 0){

			$strCabecalho  = '<table border=\"1\" cellspacing=\"0\" cellpadding=\"4\">';
			$strCabecalho .= '<thead>';
			$strCabecalho .= '  <tr>'; //font-size:25px;
			$strCabecalho .= '     <th style="width:150px; text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO').'</th>';
			$strCabecalho .= '     <th style="width:150px; text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_SOL_SERVICO_DEMANDA').'</th>';
			$strCabecalho .= '     <th style="width:100px; text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_PROFISSIONAL').'</th>';
			$strCabecalho .= '     <th style="width:80px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_PREVISTO').'</th>';
			$strCabecalho .= '     <th style="width:80px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_EXECUTADO').'</th>';
			$strCabecalho .= '     <th style="width:200px; text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_DESCRICAO_DEMANDA').'</th>';
			$strCabecalho .= '  </tr>';
			$strCabecalho .= '</thead>';
			$strCabecalho .= '<tbody>';

			$dt_demanda       = "";
			$cd_nivel_servico = "";
			$cd_profissional  = "";
			$cd_demanda       = "";
			$strRelatorio     = "";
			$inclui           = "N";
			foreach($arrDados as $key=>$value) {
				if($value['dt_demanda'] != $dt_demanda) {
					$espacoHeader = "";
					if(!empty($dt_demanda)){
						$espacoHeader = "<br><br>";
					}

					$strData = $espacoHeader.'<div style="font-size:25px;"><b>'.Base_Util::getTranslator('L_VIEW_DATA_DEMANDA').':</b> '.date('d/m/Y',strtotime($value['dt_demanda'])).'</div><br>';
					if($inclui != "N") {
						$strRelatorio .= '</tbody>';
						$strRelatorio .= '</table>';
						$strRelatorio .= $strData;
						$strRelatorio .= $strCabecalho;
						$inclui   = "N";
					} else {
						$strRelatorio .= $strData;
						$strRelatorio .= $strCabecalho;
					}
				}
				$strRelatorio .= '<tr>';
				//condição do nível de serviço
				if($value['cd_nivel_servico'] != $cd_nivel_servico){
					$strRelatorio .= '<td style="width:150px; text-align:left;">'.$value['tx_nivel_servico'].'</td>';
				} else {
					$strRelatorio .= '<td style="width:150px; text-align:left;">&nbsp;</td>';
				}
				//condição da demanda
				if(($value['cd_demanda'] != $cd_demanda)&&(is_null($value['solicitacao']))){
					$strRelatorio .= '<td style="width:150px; text-align:center;">'.$value['cd_demanda'].'</td>';
				} else {
					$strRelatorio .= '<td style="width:150px; text-align:center;">'.$value['solicitacao'].'</td>';
				}
				//condição da demanda
				if($value['cd_profissional'] != $cd_profissional){
					$strRelatorio .= '<td style="width:100px; text-align:left;">'.$value['tx_nome_conhecido'].'</td>';
				} else {
					$strRelatorio .= '<td style="width:100px; text-align:left;">&nbsp;</td>';
				}
				$strRelatorio .= '<td style="width:80px; text-align:center;">'.$value['previsto'].'</td>';
				$strRelatorio .= '<td style="width:80px; text-align:center;">'.$value['executado'].'</td>';
				$strRelatorio .= '<td style="width:200px; text-align:left;" >'.strip_tags($value['tx_demanda']).'</td>';

				$strRelatorio .= '</tr>';

				$dt_demanda       = $value['dt_demanda'];
				$cd_demanda       = $value['cd_demanda'];
				$cd_nivel_servico = $value['cd_nivel_servico'];
				$cd_profissional  = $value['cd_profissional'];
				$inclui = "S";
			}
			$strRelatorio .= '</tbody>';
			$strRelatorio .= '</table>';
			$objPdf->writeHTML($strRelatorio,true, 0, true, 0);
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta("L"),true, 0, true, 0);
		}
        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
        //Close and output PDF document
        $objPdf->Output('relatorio_comparativo_nivel_de_servico.pdf', 'I');
	}

    public function comboProfissionalAction()
    {
  		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto = $this->_request->getParam('cd_objeto_contrato');

        $arrProfissional = $this->objProfissionalObjetoContrato->getProfissionalObjetoContrato($cd_objeto, true);

        $strOption = '';
        foreach($arrProfissional as $key=>$value){
            $strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
        }
        echo $strOption;
    }

    public function comboNivelDeServicoAction()
    {
  		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto = $this->_request->getParam('cd_objeto_contrato');

        $arrNivelDeServico = $this->objNivelDeServico->getNivelServico($cd_objeto, true);

        $strOption = '';
        foreach($arrNivelDeServico as $key=>$value){
            $strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
        }

        echo $strOption;
    }
}