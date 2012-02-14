<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Minist?rio do Desenvolvimento, da Industria e do Com?rcio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gest?o de Demanda, Projetos e Servi?os de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma copia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class RelatorioDemanda_HistoricoDemandaController extends Base_Controller_Action
{
    public function indexAction() 
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_HISTORICO_DEMANDA'));
		
   		$objObjetoContrato				= new ObjetoContrato($this->_request->getControllerName());
        $objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
        $this->view->objDataDiff       = new Base_View_Helper_Datediff();
        $cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];

        $arrProfissional = $objProfissionalObjetoContrato->getProfissionalObjetoContrato($cd_objeto,true);
        $this->view->arrProfissional = $arrProfissional;
		$this->view->arrObjetoContrato	= $objObjetoContrato->getObjetoContrato(null,true,'A');

    }

    public function historicoDemandaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        Zend_Loader::loadClass('RelatorioDiversoHistorico', Base_Util::baseUrlModule('relatorioDiverso', 'models'));

        $objHistorico = new RelatorioDiversoHistorico();

        $cd_profissional = $this->_request->getParam('cd_profissional',0);
        $dt_inicio       = $this->_request->getParam('dt_inicio');
        $dt_fim          = $this->_request->getParam('dt_fim');
        $cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];
        
        
        $cd_profissional = ($cd_profissional != 0 && $cd_profissional != -1 )?$cd_profissional:null;
        $dt_inicio       = ($dt_inicio ) ? Base_Util::converterDate($dt_inicio, "DD/MM/YYYY", "YYYY-MM-DD")." 00:00:00" : null;
        $dt_fim          = ($dt_fim    ) ? Base_Util::converterDate($dt_fim, "DD/MM/YYYY", "YYYY-MM-DD")." 23:59:59"     : null;

        $arrHistoricoDemanda = $objHistorico->getHistoricoDemanda($cd_objeto, $cd_profissional, $dt_inicio, $dt_fim);

        $this->_gerarRelatorio($arrHistoricoDemanda);
    }

    protected function _gerarRelatorio(Array $arrHistoricoDemanda)
    {
        $objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_HISTORICO_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_HISTORICO'),
                             Base_Util::getTranslator('L_VIEW_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_HISTORICO_DEMANDA'), $arrKeywords);

        $objPdf->SetDisplayMode("real");

        // set font
        $objPdf->SetFont('helvetica', 'B', 10);

        // add a page
        $objPdf->AddPage();
        if(count($arrHistoricoDemanda) >0 ) {
            $cd_demanda_aux       = 0;
            $cd_nivel_servico_aux = 0;

            foreach($arrHistoricoDemanda as $row) {
                $objPdf->ln(0);
                if($cd_demanda_aux != $row['cd_demanda']) {
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell(40, 6, Base_Util::getTranslator('L_VIEW_NR_DEMANDA').": ".$row['cd_demanda'], '', 0, 'L');
                    $objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_DATA_INICIO').": ".date('d/m/Y H:i:s',strtotime($row['dt_demanda'])), '', 0, 'L');

                    if($row['dt_conclusao_demanda']) {
                        $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_DATA_FIM').": ".date('d/m/Y H:i:s',strtotime($row['dt_conclusao_demanda'])), '', 1, 'L');
                    } else {
                        $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_DATA_FIM').": ".$row['desc_conclusao_demanda'], '', 1, 'L');
                    }
                    $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_DEMANDA'), '', 1, 'L');
                    $objPdf->SetFont('helvetica', '', 8);
                    $objPdf->MultiCell(0,0,trim($row['tx_demanda']).PHP_EOL,'','J',0,1,null,null,null,null,true);
                }
                if($cd_nivel_servico_aux != $row['cd_nivel_servico']) {
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell(25, 6, Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO').":", '', 0, 'L');
                    $objPdf->SetFont('helvetica', '', 8);
                    $objPdf->Cell(155, 6, trim($row['tx_nivel_servico']), '', 1, 'L');
                }
                $objPdf->SetFont('helvetica', 'B', 8);
                $objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_HISTORICO').":", '', 1, 'L');
                $objPdf->SetFont('helvetica', '', 8);

                $texto = date('d/m/Y H:i:s',strtotime($row['dt_inicio']))." - ".date('d/m/Y H:i:s',strtotime($row['dt_fim']))." (".$row['tx_profissional'].") ".trim($row['tx_historico']).PHP_EOL;
                $objPdf->MultiCell(0,0,$texto,'','J',0,1,null,null,null,null,true);

                $cd_demanda_aux       = $row['cd_demanda'];
                $cd_nivel_servico_aux = $row['cd_nivel_servico'];
            
                $objPdf->ln(5);
            	$objPdf->Cell(180,0,"",'B',1,'L');
            	$objPdf->Ln(5);                
                
            }
        } else {
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        $objPdf->Output('relatorio_historico_demanda.pdf', 'I');
    }
}