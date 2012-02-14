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
class RelatorioDemanda_DemandaEmAbertoController extends Base_Controller_Action
{
    private $objRelDemanda;
    private $objObjetoContrato;
    private $objProfissionalObjetoContrato;
    private $objProfissional;
    private $arrDemanda;
    private $arrParamRelatorio;

    public function init()
    {
        parent::init();
        $this->objRelDemanda                 = new RelatorioDemanda();
        $this->objObjetoContrato             = new ObjetoContrato();
        $this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato();
        $this->objProfissional               = new Profissional();
    }

    public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DEMANDA_EM_ABERTO'));

        $this->view->arrObjetoContrato = $this->objObjetoContrato->getObjetoContratoAtivo('D', true, false, true);
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

    public function demandaEmAbertoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();
        foreach($this->objObjetoContrato->getObjetoContratoAtivo('D', false, false, true, $post['cd_objeto_contrato']) as $key=>$value){
            $this->arrParamRelatorio['objeto'] = $value;
        }

        $arrWhere = array('cd_objeto'=>$post['cd_objeto_contrato']);

        if($post['cd_profissional'] != '-1'){
            $arrWhere['cd_profissional'] = $post['cd_profissional'];

            $this->arrParamRelatorio['profissional'] = $this->objProfissional->fetchRow($this->objProfissional->select()->from($this->objProfissional,'tx_profissional')->where('cd_profissional = ?', $arrWhere['cd_profissional']))->tx_profissional;
        }
        if($post['mes'] != '0'){
            $arrWhere['mes'] = ($post['mes'] < 10) ? '0'.$post['mes']: $post['mes'];
        }
        if($post['ano'] != '0'){
            $arrWhere['ano'] = $post['ano'];
            $this->arrParamRelatorio['periodo'] = $arrWhere['mes'].'/'.$arrWhere['ano'];
        }

        $this->arrDemanda = $this->objRelDemanda->getDemanda($arrWhere);
        if(count($this->arrDemanda)>0){
            foreach($this->arrDemanda as $key=>&$demanda){
                $this->arrDemanda[$key]['profissionais'] = $this->getProfissionalDemanda($demanda['cd_demanda']);
            }
        }

        $this->geraRelatorio();
    }

    private function getProfissionalDemanda($cdDemanda)
    {
        $arrProfDem = $this->objRelDemanda->getProfissionalDemanda($cdDemanda);
        foreach($arrProfDem as $key=>&$prof){
            unset($prof['cd_demanda']);
            $prof['nivel_servico'] = $this->getNivelServicoDemanda($cdDemanda,$prof['cd_profissional']);
        }
        return $arrProfDem;
    }

    private function getNivelServicoDemanda($cdDemanda, $cdProfissional)
    {
        $arrNivelServDem = $this->objRelDemanda->getNivelServicoDemanda($cdDemanda, $cdProfissional);
        foreach($arrNivelServDem as $key=>&$nivelServ){
            unset($nivelServ['cd_demanda']);
            unset($nivelServ['cd_profissional']);
        }
        return $arrNivelServDem;
    }

	private function geraRelatorio()
    {
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_DEMANDA_EM_ABERTO'),
                             Base_Util::getTranslator('L_VIEW_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_DEMANDA_EM_ABERTO'), $arrKeywords);

        // add a page
		$objPdf->AddPage();
        $objPdf->SetFillColor(240, 240, 240); //#F0F0F0

        $objPdf->SetFont('helvetica', 'B', 9);
        //cabecalho
        $objPdf->Cell(35, 6, Base_Util::getTranslator('L_VIEW_CONTRATO').': ');
        $objPdf->SetFont('helvetica', '', 9);
        $objPdf->Cell(145, 6, $this->arrParamRelatorio['objeto'], 0, 1);
        if(isset($this->arrParamRelatorio['profissional'])){
            $objPdf->SetFont('helvetica', 'B', 9);
            $objPdf->Cell(35, 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').': ');
            $objPdf->SetFont('helvetica', '', 9);
            $objPdf->Cell(145, 6, $this->arrParamRelatorio['profissional'], 0, 1);
        }
        if(isset($this->arrParamRelatorio['periodo'])){
            $objPdf->SetFont('helvetica', 'B', 9);
            $objPdf->Cell(35, 6, Base_Util::getTranslator('L_VIEW_PERIODO').': ');
            $objPdf->SetFont('helvetica', '', 9);
            $objPdf->Cell(145, 6, $this->arrParamRelatorio['periodo'], 0, 1);
        }

        $objPdf->Cell(180, 2, '', 'T', true);

        $widthTotal = '510px';
        $border     = 0;

		if(count($this->arrDemanda) > 0){

            foreach($this->arrDemanda as $key=>$demanda){

                $strSolicitacaoDemanda = ((!empty($demanda['ni_solicitacao'])) && (!empty($demanda['ni_solicitacao']))) ? $demanda['ni_solicitacao'].'/'.$demanda['ni_ano_solicitacao'] : $demanda['cd_demanda'];

                $objPdf->SetFont('helvetica', 'B', 9);
                $objPdf->Cell(40, 6, Base_Util::getTranslator('L_VIEW_DATA'), $border, false, 'C',true);
                $objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_SOL_SERVICO_DEMANDA'), $border, false, 'C',true);
                $objPdf->Cell(80, 6, Base_Util::getTranslator('L_VIEW_UNIDADE'), $border, true, 'C',true);
                $objPdf->SetFont('helvetica', '', 9);
                $objPdf->Cell(40, 6, date('d/m/Y H:i:s',strtotime($demanda['dt_demanda'])), $border, false, 'C');
                $objPdf->Cell(60, 6, $strSolicitacaoDemanda, $border, false, 'C');
                $objPdf->Cell(80, 6, $demanda['tx_sigla_unidade'], $border, true, 'C');

                $objPdf->SetFont('helvetica', 'B', 9);
                $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_DEMANDA'), $border, true, 'C',true);
                $objPdf->SetFont('helvetica', '', 9);
                $objPdf->MultiCell(180, 6, $demanda['tx_demanda'].PHP_EOL, $border, 'J', false, 1, null, null, true, 0,true);

                $strRelatorio = '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="'.$border.'" style="width:'.$widthTotal.';">';
                $strRelatorio .= '<thead>';
                $strRelatorio .= '  <tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" >';
                $strRelatorio .= '     <th style="font-weight:bold; width:40%;" >'.Base_Util::getTranslator('L_VIEW_PROFISSIONAL_DESIGNADO').'</th>';
                $strRelatorio .= '     <th style="font-weight:bold; width:60%;">'.Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO').'</th>';
                $strRelatorio .= '  </tr>';
                $strRelatorio .= '</thead>';
                $strRelatorio .= '<tbody>';
                foreach($demanda['profissionais'] as $key=>$profissional){
					$flag = 1;
					foreach($profissional['nivel_servico'] as $key=>$niveisServico){
						if ($flag == 1) {
							$strRelatorio     .= '  <tr>';
							$strRelatorio     .= '    <td style="width:40%;">'.$profissional['tx_profissional'].'</td>';
							$strNiveisServiço  = '';
                            if ($niveisServico['ni_horas_prazo_execucao']>1) { 
                                $txt_hora = ' horas';
                            }else{
                                $txt_hora = ' hora';
                            }
                                
							$strNiveisServiço .= '- '.$niveisServico['tx_nivel_servico'].' ('.$niveisServico['ni_horas_prazo_execucao'].$txt_hora.')' ;
							$strRelatorio .= '<td style="width:60%;">'.$strNiveisServiço.'</td>';
							$strRelatorio .= '  </tr>';
						}else{
							$strRelatorio     .= '  <tr>';
							$strRelatorio     .= '    <td style="width:40%;"></td>';
							$strNiveisServiço  = '';
							$strNiveisServiço .= '- '.$niveisServico['tx_nivel_servico'];
							$strRelatorio .= '<td style="width:60%;">'.$strNiveisServiço.'</td>';
							$strRelatorio .= '  </tr>';

						}
						$flag++;
					}
                }
                $strRelatorio .= '</tbody>';
                $strRelatorio .= '</table>';
                $objPdf->writeHTML($strRelatorio,true,0,true, 0);
                $objPdf->Cell(180, 2, '', 'T', true);
            }
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('rel_demanda_em_aberto.pdf', 'I');
	}
}