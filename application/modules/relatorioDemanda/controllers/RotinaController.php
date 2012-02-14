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
class RelatorioDemanda_RotinaController extends Base_Controller_Action
{
    private $objObjetoContrato;
    private $objProfissionalObjetoContrato;
    private $objProfissional;
	private $objRelRotina;
    private $arrExecucaoRotina;
    private $arrParamRelatorio;

    public function init()
    {
        parent::init();
        $this->objObjetoContrato             = new ObjetoContrato();
        $this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato();
        $this->objProfissional               = new Profissional();
        $this->objRelRotina                  = new RelatorioRotina();
    }

    public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ROTINAS'));

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

    public function rotinaAction()
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

        $arrWhere['dt_inicio'] = Base_Util::converterDate($post['dt_inicio'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        $arrWhere['dt_fim']    = Base_Util::converterDate($post['dt_fim'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        
        $this->arrParamRelatorio['periodo'] = $post['dt_inicio'].' a '.$post['dt_fim'];

        $this->arrExecucaoRotina = $this->objRelRotina->getExecucaoRotina($arrWhere)->toArray();

        foreach ($this->arrExecucaoRotina as $key=>$rotina){
            $arrParams['cd_objeto']          = $rotina['cd_objeto'];
            $arrParams['dt_execucao_rotina'] = $rotina['dt_execucao_rotina'];
            $arrParams['cd_profissional']    = $rotina['cd_profissional'];
            $arrParams['cd_rotina']          = $rotina['cd_rotina'];

            $this->arrExecucaoRotina[$key]['historico'] = $this->objRelRotina->getHistoricoExecucaoRotina($arrParams)->toArray();
        }

        $this->geraRelatorio();
    }

	private function geraRelatorio()
    {
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_ROTINAS'),
                             Base_Util::getTranslator('L_VIEW_ROTINA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_ROTINAS'), $arrKeywords);

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

		if(count($this->arrExecucaoRotina) > 0){

            foreach($this->arrExecucaoRotina as $key=>$rotina){

                $objPdf->SetFont('helvetica', 'B', 9);
                $objPdf->Cell(40, 6, Base_Util::getTranslator('L_VIEW_DATA'), $border, false, 'C',true);
                $objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_HORA'), $border, false, 'C',true);
                $objPdf->Cell(80, 6, Base_Util::getTranslator('L_VIEW_ROTINA'), $border, true, 'C',true);
                $objPdf->SetFont('helvetica', '', 9);
                $objPdf->Cell(40, 6, date('d/m/Y',strtotime($rotina['dt_execucao_rotina'])), $border, false, 'C');
                $objPdf->Cell(60, 6, $rotina['tx_hora_execucao_rotina'], $border, false, 'C');
                $objPdf->Cell(80, 6, $rotina['tx_rotina'], $border, true, 'C');
                $objPdf->SetFont('helvetica', '', 9);
                $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL'), $border, true, 'C',true);
                $objPdf->Cell(180, 6, $rotina['tx_profissional'], $border, true, 'L');

                $objPdf->SetFont('helvetica', 'B', 9);
                $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_HISTORICO'), $border, true, 'C',true);
                $objPdf->SetFont('helvetica', '', 9);
                if (count($rotina['historico'])>0) {
                    foreach ($rotina['historico'] as $key=>$historico){
                        $objPdf->MultiCell(180, 6, '- ('.date('d/m/Y',strtotime($historico['dt_historico_rotina'])).') '.$historico['tx_historico_execucao_rotina'].PHP_EOL, $border, 'J', false, 1);
                    }
                }else{
                        $objPdf->MultiCell(180, 6, Base_Util::getTranslator('L_MSG_ALERT_SEM_HISTORICO_REGISTRADO_PROFISSIONAL'), $border, 'L', false, 1);
                }
                $objPdf->Cell(180, 2, '', 'T', true);
                $objPdf->ln(5);
            }
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('rel_rotina.pdf', 'I');
	}
}