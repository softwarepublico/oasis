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

class RelatorioProjeto_ParecerTecnicoPropostaController extends Base_Controller_Action 
{
    private $_objContrato;
    private $_objContratoProjeto;

	public function init()
	{
		parent::init();
		$this->_objContrato        = new Contrato($this->_request->getControllerName());
        $this->_objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PARECER_TECNICO_PROPOSTA'));
		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}

	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}
	
	public function generateAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        
		$objRelParecerProposta = new RelatorioProjetoParecerTecnico();
		
		$params['cd_projeto']  = $this->_request->getParam('cd_projeto');
		$params['cd_proposta'] = $this->_request->getParam('cd_proposta');
		
		$arrProjeto        = $objRelParecerProposta->getDadoDoProjetoProposta($params);
		$arrListaProdutos  = $objRelParecerProposta->getListaProdutosProposta($params);
		if (count($arrProjeto) > 0){
			$arrItensAvaliados = $objRelParecerProposta->getItensAvaliadosProposta($arrProjeto[0]['cd_processamento_proposta']);
		}
        //criando o objeto
        $objPdf = new Base_Tcpdf_Pdf();

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PARECER_TECNICO_PROPOSTA'), K_CREATOR_SYSTEM.', '.
                                                                                                 Base_Util::getTranslator('L_TIT_REL_PARECER_TECNICO_PROPOSTA').', '.
                                                                                                 Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                                 Base_Util::getTranslator('L_VIEW_PARECER_TECNICO_PROPOSTA'));
        $objPdf->SetDisplayMode("real");

        // set font
        $objPdf->SetFont('helvetica', 'B', 8);

        // add a page
        $objPdf->AddPage();
        if(count($arrProjeto) > 0){
            //Mostra o nome do Projeto e a Sigla
            $objPdf->Cell(PDF_MARGIN_LEFT, 4, Base_Util::getTranslator('L_VIEW_PROJETO').": ".$arrProjeto[0]['tx_projeto'], '', 0, 'L');
            $objPdf->Ln(4);
            $objPdf->Cell(PDF_MARGIN_LEFT, 4, Base_Util::getTranslator('L_VIEW_SIGLA').":     ".$arrProjeto[0]['tx_sigla_projeto'], '', 0, 'L');
            $objPdf->Ln(6);

            //Informa qual o número da proposta
            $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_DOCUMENTO').": ", 'LRBT', 1, 'L');
            $objPdf->SetFont('helvetica', '', 8);
            $objPdf->MultiCell(180, 6, Base_Util::getTranslator('L_MSG_ALERT_PARECER_TECNICO_AVALIACAO_PROPOSTA', $arrProjeto[0]['cd_proposta']).PHP_EOL, 'LRBT', 'J');
            $objPdf->Ln(2);

            //Lista os Produtos
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PRODUTOS').": ", 'LRBT', 1, 'L');
            $objPdf->SetFont('helvetica', '', 8);

            if(count($arrListaProdutos) > 0){
                foreach($arrListaProdutos as $chave=>$valor){
					$objPdf->MultiCell(180, 6, "   ".$valor['tx_produto_parcela'], 'LR', 'L', '', 1);
                }
                $objPdf->Cell(180, 6,"", 'T', 0,'L');
            } else {
                $objPdf->Cell(180, 6, "", 'LRBT', 0, 'L');
                $objPdf->Ln(6);
            }
            //Lista dos Itens Avaliados
            $objPdf->Ln(2);
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_ITEM_AVALIDADO').": ", 'LRBT', 1, 'L');
            $objPdf->SetFont('helvetica', '', 8);
            if(count($arrItensAvaliados) > 0){
                foreach($arrItensAvaliados as $chave=>$valor){
                    $objPdf->Cell(25, 6, "", 'LRTB', 0, 'L');
                    $objPdf->Cell(60, 6, $valor['tx_item_parecer_tecnico'], 'LRTB', 0, 'L');
                    
                    $ok = (trim($valor['st_avaliacao']) == "OK") ? "X" : "  ";
                    $n  = (trim($valor['st_avaliacao']) == "N" ) ? "X" : "  ";
                    $na = (trim($valor['st_avaliacao']) == "NA") ? "X" : "  ";

                    $objPdf->Cell(15, 6, "({$ok}) OK", 'LRTB', 0, 'L');
                    $objPdf->Cell(15, 6, "({$n}) ".Base_Util::getTranslator('L_VIEW_NAO'), 'LRTB', 0, 'L');
                    $objPdf->Cell(40, 6, "({$na}) ".Base_Util::getTranslator('L_VIEW_NAO_APLICA'), 'LRTB', 0, 'L');

                    $objPdf->Cell(25, 6, "", 'LRTB', 1, 'L');
                }
            } else {
                $objPdf->Cell(180, 6, "", 'LRBT', 0, 'L');
                $objPdf->Ln(6);
            }
            $objPdf->Ln(2);
            //Mostra a Observação
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell(180, 0, Base_Util::getTranslator('L_VIEW_OBSERVACOES').": ", 'LRBT', 1, 'L');
            $objPdf->SetFont('helvetica', '', 8);
            $objPdf->MultiCell(180, 6, $arrProjeto[0]['tx_obs_parecer_tecnico_prop'].PHP_EOL, 'LRBT', 'J');
            $objPdf->Ln(5);

            $dt_parecer_tecnico_proposta = date('d/m/Y H:i:s', strtotime($arrProjeto[0]['dt_parecer_tecnico_proposta']));
            $objPdf->SetFont('helvetica', '', 8);

            $arrValueMsg = array('value1'=>$arrProjeto[0]['tx_profissional'],
                                 'value2'=>$dt_parecer_tecnico_proposta);

            $objPdf->writeHTML(Base_Util::getTranslator('L_MSG_ALERT_PARECER_REALIZADO_POR_EM', $arrValueMsg));
        } else {
            $strHtml  = "";
            $strHtml .= "<table cellpadding=\"5\" cellspacing=\"0\" bordercolor='#CCCCCC' border=\"1\">";
            $strHtml .= "<tr>";
            $strHtml .= "  <td bgcolor=\"".Base_Tcpdf_Pdf::FILL_TITLE_TABLE."\" width=\"508px\" style='text-align:center;'><b>".Base_Util::getTranslator('L_MSG_ALERT_SEM_PARECER_TECNICO_PROJETO')."</b></td>";
            $strHtml .= "</tr>";
            $strHtml .= "</table>";
            $objPdf->writeHTML($strHtml,true, 0, true, 0);
        }
        //Close and output PDF document
        $objPdf->Output('relatorio_parecer_proposta.pdf', 'I');
	}

}