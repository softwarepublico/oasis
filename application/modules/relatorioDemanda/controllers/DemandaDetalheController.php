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
class RelatorioDemanda_DemandaDetalheController extends Base_Controller_Action
{
    private $objRelDemanda;
    private $objDemanda;
    private $objObjetoContrato;
    private $objProfissional;
    private $objSolicitacao;
    private $arrDemanda;
    private $arrSolicitacao;
    private $arrParamRelatorio;
    private $arrDadosFinal;
    private $objStatusAtendimento;

    public function init()
    {
        parent::init();
        $this->objRelDemanda                 = new RelatorioDemanda();
        $this->objDemanda                    = new Demanda();
        $this->objObjetoContrato             = new ObjetoContrato();
        $this->objProfissional               = new Profissional();
        $this->objSolicitacao                = new Solicitacao();
        $this->objStatusAtendimento          = new StatusAtendimento();
    }

    public function indexAction()
	{
        $this->initView();
        $objObjetoContrato				= new ObjetoContrato($this->_request->getControllerName());
  		$this->view->arrObjetoContrato	= $objObjetoContrato->getObjetoContrato(null,true,'A');
	}

    public function demandaDetalheAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();


        $post = $this->_request->getParams();

        $detalhe = array();
        $detalhe = $this->objObjetoContrato->getObjetoContratoAtivo('D', false, false, true, $post['cd_objeto']);

        foreach($this->objObjetoContrato->getObjetoContratoAtivo('D', false, false, true, $post['cd_objeto']) as $key=>$value){
            $this->arrParamRelatorio['objeto'] = $value;
        }

        $arrWhere = array('cd_objeto'=>$post['cd_objeto']);

        if($post['ni_solicitacao'] != 0 && $post['ni_ano_solicitacao'] != 0){
            $arrWhere['ni_solicitacao']     = $post['ni_solicitacao'];
            $arrWhere['ni_ano_solicitacao'] = $post['ni_ano_solicitacao'];

            $this->arrParamRelatorio['solicitacao'] = "{$post['ni_solicitacao']}/{$post['ni_ano_solicitacao']}";

			$this->arrSolicitacao = $this->objSolicitacao->getSolicitacao($arrWhere['cd_objeto'], $arrWhere['ni_solicitacao'], $arrWhere['ni_ano_solicitacao']);
        }
        if($post['cd_demanda'] != 0){
            $arrWhere['cd_demanda']     = $post['cd_demanda'];

            $this->arrParamRelatorio['demanda'] = $post['cd_demanda'];

			$this->arrDemanda = $this->objDemanda->getDemanda($post['cd_demanda']);
			if(is_array($this->arrDemanda)){
					$this->arrDemanda['dados']['profissionais'] = $this->getProfissionalDemanda($post['cd_demanda']);
					$arrPrioridade = $this->objStatusAtendimento->getStatusAtendimento(array('cd_status_atendimento = ?' => $this->arrDemanda['cd_status_atendimento']))->toArray();
					$this->arrDemanda['prioridade']             = $arrPrioridade[0]['tx_status_atendimento'];
			}
        }

		$this->montaDadosFinal($this->arrSolicitacao, $this->arrDemanda);
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
            $nivelServ['exec_nivel_servico'] = $this->getExecucaoNivelServicoDemanda($cdDemanda,$cdProfissional,$nivelServ['cd_nivel_servico']);
        }
        return $arrNivelServDem;
    }
	
    private function getExecucaoNivelServicoDemanda($cdDemanda, $cdProfissional, $cdNivelServico)
    {
        $arrExecNivelServDem = $this->objRelDemanda->getHistoricoExecucaoDemanda($cdDemanda, $cdProfissional, $cdNivelServico);
        foreach($arrExecNivelServDem as $key=>&$execNivelServ){
            unset($execNivelServ['cd_demanda']);
            unset($execNivelServ['cd_profissional']);
            unset($execNivelServ['cd_nivel_servico']);
			$execNivelServ['dt_inicio'] = date('d/m/Y H:i:s', strtotime($execNivelServ['dt_inicio']));
			$execNivelServ['dt_fim']    = date('d/m/Y H:i:s', strtotime($execNivelServ['dt_fim']));
        }
        return $arrExecNivelServDem;
    }

	private function montaDadosFinal($arrSolicitacao, $arrDemanda)
	{
		if (is_array($arrSolicitacao)) {
				$this->arrDadosFinal['demanda']                 = "{$arrSolicitacao['ni_solicitacao']}/{$arrSolicitacao['ni_ano_solicitacao']}";
				$this->arrDadosFinal['tx_demanda']              = $arrSolicitacao['tx_solicitacao'];
				$this->arrDadosFinal['dt_demanda']              = $arrSolicitacao['dt_solicitacao'];
				$this->arrDadosFinal['tx_solicitante']          = $arrSolicitacao['tx_solicitante'];
				$this->arrDadosFinal['tx_sigla_unidade']        = $arrSolicitacao['tx_sigla_unidade'];
		}else{
			if (is_array($arrDemanda)) {
				$this->arrDadosFinal['demanda']                 = $arrDemanda['cd_demanda'];
				$this->arrDadosFinal['tx_demanda']              = $arrDemanda['tx_demanda'];
				$this->arrDadosFinal['dt_demanda']              = $arrDemanda['dt_demanda'];
				$this->arrDadosFinal['tx_solicitante']          = $arrDemanda['tx_solicitante_demanda'];
				$this->arrDadosFinal['tx_sigla_unidade']        = $arrDemanda['tx_sigla_unidade'];
			}
		}
		if (is_array($arrDemanda)) {
			$this->arrDadosFinal['prioridade'] = $arrDemanda['prioridade'];
			$this->arrDadosFinal['dados']      = $arrDemanda['dados'];
		}
	}

	private function geraRelatorio()
    {
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_DEMANDA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_DEMANDA'), $arrKeywords);

        // add a page
		$objPdf->AddPage();
        $objPdf->SetFillColor(240, 240, 240); //#F0F0F0

        $objPdf->SetFont('helvetica', 'B', 9);
        //cabecalho
        $objPdf->Cell(35, 6, Base_Util::getTranslator('L_VIEW_CONTRATO').': ');
        $objPdf->SetFont('helvetica', '', 9);
        if (isset($this->arrParamRelatorio['objeto'])) {
            $objPdf->Cell(145, 6, $this->arrParamRelatorio['objeto'], 0, 1);
        }else{
            $objPdf->Cell(145, 6, ' ', 0, 1);
        }

        $objPdf->Cell(180, 2, '', 'T', true);

        $widthTotal = '510px';
        $border     = 0;

		if(is_array($this->arrDadosFinal)){

			$strSolicitacaoDemanda = $this->arrDadosFinal['demanda'];

			$objPdf->SetFont('helvetica', 'B', 9);
			$objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_SOL_SERVICO_DEMANDA'), $border, false, 'C',true);
			$objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_PRIORIDADE'), $border, false, 'C',true);
			$objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_DATA'), $border, true, 'C',true);
			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Cell(60, 6, $strSolicitacaoDemanda, $border, false, 'C');
			$objPdf->Cell(60, 6, $this->arrDadosFinal['prioridade'], $border, false, 'C');
			$objPdf->Cell(60, 6, $this->arrDadosFinal['dt_demanda'], $border, true, 'C');

			$objPdf->SetFont('helvetica', 'B', 9);
			$objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_DESCRICAO'), $border, true, 'C',true);
			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->MultiCell(180, 6, $this->arrDadosFinal['tx_demanda'].PHP_EOL, $border, 'J', false, 1, '', '', false, false, true);

			$objPdf->SetFont('helvetica', 'B', 9);
			$objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_SOLICITANTE'), $border, false, 'C',true);
			$objPdf->Cell(90, 6, Base_Util::getTranslator('L_VIEW_UNIDADE'), $border, true, 'C',true);
			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Cell(90, 6, $this->arrDadosFinal['tx_solicitante'], $border, false, 'C');
			$objPdf->Cell(90, 6, $this->arrDadosFinal['tx_sigla_unidade'], $border, true, 'C');

			$objPdf->SetFont('helvetica', 'B', 9);
			$objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_EXECUCAO_SERVICO'), $border, true, 'C',true);
			
			if (is_array($this->arrDadosFinal['dados']['profissionais'])) {
				foreach($this->arrDadosFinal['dados']['profissionais'] as $key=>$profissional){
					$objPdf->SetFont('helvetica', '', 9);
					$objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').": ".$profissional['tx_profissional'], $border, true, 'L');

					$strRelatorio  = '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="'.$border.'" style="width:'.$widthTotal.';">';
					$strRelatorio .= '<thead>';
					$strRelatorio .= '  <tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" >';
					$strRelatorio .= '     <th style="font-weight:bold; width:40%; text-align:center;" >'.Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO').'</th>';
					$strRelatorio .= '     <th style="font-weight:bold; width:60%; text-align:center;">'.Base_Util::getTranslator('L_VIEW_EXECUCAO_DEMANDA').'</th>';
					$strRelatorio .= '  </tr>';
					$strRelatorio .= '</thead>';
					$strRelatorio .= '<tbody>';

					foreach($profissional['nivel_servico'] as $key=>$niveisServico){
							if (!empty($niveisServico['exec_nivel_servico'])) {
								$flag = 1;
								foreach($niveisServico['exec_nivel_servico'] as $key=>$execNiveisServico){
									$strRelatorio        .= '<tr>';
									if ($flag > 1) {
										$strRelatorio    .= '    <td style="width:40%;"></td>';
									}else{
										$strRelatorio    .= '    <td style="width:40%;">'.$niveisServico['tx_nivel_servico'].'</td>';
									}
									$flag++;
									$strExecNiveisServico  = '';
									$strExecNiveisServico .= "- ({$execNiveisServico['dt_inicio']} a {$execNiveisServico['dt_fim']}) ".$execNiveisServico['tx_historico'];
									$strRelatorio         .= '   <td style="width:60%;">'.$strExecNiveisServico.'</td>';
									$strRelatorio         .= '</tr>';
								}
							}else{
								$strRelatorio .= '  <tr>';
								$strRelatorio .= '    <td style="width:40%;">'.$niveisServico['tx_nivel_servico'].'</td>';
								$strRelatorio .= '    <td style="width:60%; text-align:center;">*** '.Base_Util::getTranslator('L_MSG_ALERT_SEM_HISTORICO_REGISTRADO_PROFISSIONAL').' ***</td>';
								$strRelatorio .= '  </tr>';
							}
					}
					$strRelatorio .= '</tbody>';
					$strRelatorio .= '</table>';
					$objPdf->writeHTML($strRelatorio,true,0,true, 0);
				}
			}
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(PDF_MARGIN_LEFT,6,'__','',1,'L');
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('rel_demanda_detalhe.pdf', 'I');
	}
}