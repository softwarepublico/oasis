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
class RelatorioDemanda_MediaNivelDeServicoController extends Base_Controller_Action
{
    private $objRelDemanda;
    private $objObjetoContrato;
    private $objNivelDeServico;
    private $arrDadosRel;
    
    public function init()
    {
        parent::init();
        $this->objRelDemanda                 = new RelatorioDemanda();
        $this->objObjetoContrato             = new ObjetoContrato();
        $this->objNivelDeServico             = new NivelServico();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_MEDIA_NIVEL_SERVICO'));

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

    public function mediaNivelDeServicoAction()
    {
   		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $arrPost = $this->_request->getPost();
        
		if($arrPost['dt_demanda_inicio'] == ""){
            $this->arrDadosRel['dt_demanda_inicio'] = date('d-m-Y');
			$arrPost['dt_demanda_inicio'] = date('Y-m-d')." 00:00:00";
		} else {
            $this->arrDadosRel['dt_demanda_inicio'] = $arrPost['dt_demanda_inicio'];
			$arrPost['dt_demanda_inicio'] = Base_Util::converterDate($arrPost['dt_demanda_inicio'], 'DD/MM/YYYY', 'YYYY-MM-DD')." 00:00:00";
		}
		if($arrPost['dt_demanda_final'] == ""){
            $this->arrDadosRel['dt_demanda_final'] = date('d-m-Y');
			$arrPost['dt_demanda_final'] = date('Y-m-d')." 23:59:59";
		} else {
            $this->arrDadosRel['dt_demanda_final'] = $arrPost['dt_demanda_final'];
			$arrPost['dt_demanda_final'] = Base_Util::converterDate($arrPost['dt_demanda_final'], 'DD/MM/YYYY', 'YYYY-MM-DD')." 23:59:59";
		}
        
        $objeto = $this->objObjetoContrato->getObjeto($arrPost['cd_objeto_contrato']);
        $this->arrDadosRel['tx_objeto_contrato'] = $objeto[$arrPost['cd_objeto_contrato']];
        
        
        
        $arrDados = $this->objRelDemanda->getMediaNivelDeServico($arrPost);
        
		$this->_generate($arrDados);
    }

	private function _generate(array $arrDados)
	{
       
		$objPdf = new Base_Tcpdf_Pdf('L');

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_MEDIA_NIVEL_DE_SERVICO'),
                             Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_MEDIA_NIVEL_DE_SERVICO'), $arrKeywords);

		// add a page
		$objPdf->AddPage();
        
        $strCabecalho = '';

        if(count($arrDados) > 0){
            
            $strCabecalho .= '<p>'. Base_Util::getTranslator('L_VIEW_OBJETO').': '.$this->arrDadosRel['tx_objeto_contrato'].'<br>';
			$strCabecalho .= Base_Util::getTranslator('L_VIEW_PERIODO').': '.$this->arrDadosRel['dt_demanda_inicio'].' - '.$this->arrDadosRel['dt_demanda_final'].'</p>';
            
			$strCabecalho .= '<table border=\"1\" cellspacing=\"0\" cellpadding=\"4\">';
			$strCabecalho .= '<thead>';
			$strCabecalho .= '  <tr>'; //font-size:25px;
			$strCabecalho .= '     <th style="width:30px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_NUMERO2').'</th>';
			$strCabecalho .= '     <th style="width:300px; text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO').'</th>';
			$strCabecalho .= '     <th style="width:80px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_QTD').'</th>';
			$strCabecalho .= '     <th style="width:80px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_PREVISTO').'</th>';
			$strCabecalho .= '     <th style="width:80px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_MENOR_REALIZADO').'</th>';
			$strCabecalho .= '     <th style="width:80px;  text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_MAIOR_REALIZADO').'</th>';
			$strCabecalho .= '     <th style="width:100px; text-align:center; font-weight:bold;">'.Base_Util::getTranslator('L_VIEW_MEDIA').'</th>';
			$strCabecalho .= '  </tr>';
			$strCabecalho .= '</thead>';
			$strCabecalho .= '<tbody>';

			$dt_demanda       = "";
            $min              = "";
            $max              = "";
            $media            = "";
            $qtd              = "";
			$strRelatorio     = $strCabecalho;
            $seq              = 1;
            
			foreach($arrDados as $key=>$value) {
                $strRelatorio .= '<tr>';
                //condição do nível de serviço
                $strRelatorio .= '<td style="width:30px;  text-align:left;">'.$seq.'</td>';
                $strRelatorio .= '<td style="width:300px; text-align:left;">'.$value['tx_nivel_servico'].'</td>';
                $strRelatorio .= '<td style="width:80px;  text-align:right;">'.$value['qtd'].'</td>';
                $strRelatorio .= '<td style="width:80px;  text-align:center;">'.$value['previsto'].'</td>';
                $strRelatorio .= '<td style="width:80px;  text-align:center;">'.$value['minimo'].'</td>';
                $strRelatorio .= '<td style="width:80px;  text-align:center;">'.$value['maximo'].'</td>';
                $strRelatorio .= '<td style="width:100px; text-align:left;">'.$value['media'].'</td>';

                $strRelatorio .= '</tr>';
                
                $seq += 1;
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
        $objPdf->Output('relatorio_media_nivel_de_servico.pdf', 'I');
	}

}