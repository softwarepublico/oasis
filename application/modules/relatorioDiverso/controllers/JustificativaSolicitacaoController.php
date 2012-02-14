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

class RelatorioDiverso_JustificativaSolicitacaoController extends Base_Controller_Action
{

	private $_rowSetDados;
	private $_objPdf;
	private $_objRelJustificativaSolicitacao;
	private $_objObjetoContrato;

	public function init()
	{
		parent::init();
        $this->_objRelJustificativaSolicitacao = new RelatorioDiversoJustificativaSolicitacao();
        $this->_objObjetoContrato              = new ObjetoContrato();
        $this->_objPdf                         = new Base_Tcpdf_Pdf();
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_JUSTIFICATIVA_SOLICITCAO'));
        $this->view->arrObjeto = $this->_objObjetoContrato->getObjetoContratoAtivo(null, true, false, true, null, null, true);
	}

	public function justificativaSolicitacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto = (int)$this->_request->getPost('cd_objeto');
        $dt_inicio = Base_Util::converterDate($this->_request->getPost('dt_inicio'), 'DD/MM/YYYY', 'YYYY-MM-DD'). ' 00:00:00';
        $dt_fim    = Base_Util::converterDate($this->_request->getPost('dt_fim'), 'DD/MM/YYYY', 'YYYY-MM-DD'). ' 23:59:59';

        $arrParam['sol.cd_objeto = ?'] = $cd_objeto;
        $arrParam["dt_solicitacao BETWEEN '{$dt_inicio}' AND '{$dt_fim}' "] = '';
        $arrParam['dt_justificativa is not null'] = '';

        $this->_rowSetDados = $this->_objRelJustificativaSolicitacao->getDadosRelatorioJustificativaSolicitacao($arrParam);

		$this->_geraRelatorio();
	}

	private function _geraRelatorio()
	{
        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_JUSTIFICATIVA_SOLICITCAO'),
                             Base_Util::getTranslator('L_VIEW_JUSTIFICATIVA'),
                             Base_Util::getTranslator('L_VIEW_SOLICITACAO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $this->_objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_JUSTIFICATIVA_SOLICITCAO'), $arrKeywords);

		$this->_objPdf->SetDisplayMode("real");
		// set font
		$this->_objPdf->AddPage();
        
		$w        = array(180,40,140);
        $font     = 'helvetica';
        $fontSize = 8;

		if($this->_rowSetDados->valid()){

			$this->_objPdf->SetFont($font, 'B', $fontSize+2);
            $this->_objPdf->MultiCell($w[0], 6, $this->_rowSetDados->getRow(0)->tx_objeto.PHP_EOL,'', 'C', false, 1);
			$this->_objPdf->SetFont($font, 'B', $fontSize);
            $this->_objPdf->Ln(6);

            $reader = array(
                Base_Util::getTranslator('L_VIEW_SOLICITACAO_SERVICO'  ).": ",
                Base_Util::getTranslator('L_VIEW_DATA_SOLICITACAO'     ).": ",
                Base_Util::getTranslator('L_VIEW_SOLICITANTE'          ).": ",
                Base_Util::getTranslator('L_VIEW_DESCRICAO_SOLICITACAO').": ",
                Base_Util::getTranslator('L_VIEW_DATA_JUSTIFICATIVA'   ).": ",
                Base_Util::getTranslator('L_VIEW_JUSTIFICATIVA'        ).": ",
                Base_Util::getTranslator('L_VIEW_ACEITE'               ).": ",
                Base_Util::getTranslator('L_VIEW_OBSERVACAO_ACEITE'    ).": "
            );

            foreach($this->_rowSetDados as $row){

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[1], 6, $reader[0],'LT');
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->Cell($w[2], 6, $row->solicitacao,'TR',1);

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[1], 6, $reader[1],'L');
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->Cell($w[2], 6, date('d/m/Y H:i:s', strtotime($row->dt_solicitacao)),'R',1);

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[1], 6, $reader[2],'L');
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->Cell($w[2], 6, $row->tx_solicitante,'R',1);

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[0], 6, $reader[3],'LR',1);
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->MultiCell($w[0], 6, $row->tx_solicitacao.PHP_EOL,'LR', 'J', false, 1);

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[1], 6, $reader[4],'L');
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->Cell($w[2], 6, date('d/m/Y H:i:s', strtotime($row->dt_justificativa)),'R',1);

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[0], 6, $reader[5],'LR',1,'L');
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->MultiCell($w[0], 6, $row->tx_justificativa_solicitacao.PHP_EOL,'LR', 'J', false, 1);

                $this->_objPdf->SetFont($font, 'B', $fontSize);
                $this->_objPdf->Cell($w[1], 6, $reader[6],'L');
                $this->_objPdf->SetFont($font, '', $fontSize);
                $this->_objPdf->Cell($w[2], 6, $row->st_aceite_just_solicitacao,'R',1);

                if($row->tx_obs_aceite_just_solicitacao){
                    //imprime a observação
                    $this->_objPdf->SetFont($font, 'B', $fontSize);
                    $this->_objPdf->Cell($w[0], 6, $reader[7],'LR',1,'L');
                    $this->_objPdf->SetFont($font, '', $fontSize);
                    $this->_objPdf->MultiCell($w[0], 6, $row->tx_obs_aceite_just_solicitacao.PHP_EOL,'LBR', 'J', false, 1);
                }else{
                    //imprime linha em branco com as bordas
                    $this->_objPdf->MultiCell($w[0], 6, '','LBR', 'J', false, 1);
                }
                $this->_objPdf->Ln(6);
            }
			$this->_objPdf->Ln(6);
			$this->_objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
			//Close and output PDF document
		}else{
			$this->_objPdf->writeHTML($this->_objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
        $this->_objPdf->Output('relatorio_justificativa_solicitacao.pdf', 'I');
	}
}