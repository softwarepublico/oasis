<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009, 2010 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
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

class RelatorioProjeto_ChecklistProjetoController extends Base_Controller_Action
{
	private $arrChecklistProjeto;
	private $objPdf;
	
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CHECKLIST_PROJETO'));

        $objContrato 		= new Contrato($this->_request->getControllerName());
		$cd_contrato 		= null;
		$comStatus			= true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }

    public function generateAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout->disableLayout();

    	$objChecklistProjeto		= new RelatorioProjetoChecklistProjeto();
		$objUtil			        = new Base_Controller_Action_Helper_Util();
    	
    	$arrParam['cd_contrato']	= $this->_request->getParam('cd_contrato');

		$this->arrChecklistProjeto	= $objChecklistProjeto->getChecklistProjeto($arrParam);
		$this->arrChecklistProjeto	= $this->getQuantTabelasProjeto($this->arrChecklistProjeto);

		$this->gerarRelatorio();
    }

	private function gerarRelatorio(){

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf('L');

        $arrKeywords = array(K_CREATOR_SYSTEM,
                     Base_Util::getTranslator('L_TIT_REL_CHECKLIST_PROJETO'),
                     Base_Util::getTranslator('L_VIEW_RELATORIO'));

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_CHECKLIST_PROJETO'), $arrKeywords);

		$this->objPdf->SetDisplayMode("real");
		// set font
		$this->objPdf->SetFont('helvetica', 'B', 8);
		// add a page
		$this->objPdf->AddPage();

//		$this->objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_MES_ANO').": ".$this->mesAno, '', 0, 'L');
		$this->objPdf->Ln(7);

		if( count($this->arrChecklistProjeto) > 0 ){
			//Titulos da Coluna
            $header = array(
                            Base_Util::getTranslator('L_VIEW_PROJETO'),
                            Base_Util::getTranslator('L_VIEW_CASO_DE_USO'),
                            Base_Util::getTranslator('L_VIEW_SITUACAO_PROJETO'),
                            Base_Util::getTranslator('L_VIEW_PROFISSIONAL_PROJETO'),
                            Base_Util::getTranslator('L_VIEW_PLANEJAMENTO'),
                            Base_Util::getTranslator('L_VIEW_MODELO_DADOS'),
                            Base_Util::getTranslator('L_VIEW_WIREFRAME'),
                            Base_Util::getTranslator('L_VIEW_CONHECIMENTO'),
                            Base_Util::getTranslator('L_VIEW_TABELA'),
                            Base_Util::getTranslator('L_VIEW_INFORMACAO_TECNICA')
                );
			// Imprime os dados no relatório de checkList da Projeto
			$this->montaGridChecklist($header);

		} else {
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta('L'),true, 0, true, 0);
		}

		// reset pointer to the last page
		$this->objPdf->lastPage();

		//Close and output PDF document
		$this->objPdf->Output('relatorio_checklist_Projeto.pdf', 'I');
	}

	private function montaGridChecklist($header){

		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica();

		// Colors, line width and bold font
		$this->objPdf->SetDrawColor(50);
		$this->objPdf->SetFillColor(240,240,240);
		$this->objPdf->SetTextColor(0);
		$this->objPdf->SetLineWidth(0.3);
		$this->objPdf->SetFont('helvetica', 'B', 8);

		// Tamanho do Cabeçalho
		$w = array(55,23,23,23,23,23,23,23,28,23);
		for($i = 0; $i < count($header); $i++)
			$this->objPdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1,0,1);
		$this->objPdf->Ln();

		// Color and font restoration
		$this->objPdf->SetFillColor(240, 248, 255);
		$this->objPdf->SetTextColor(0);

		$fill = 1;

		$cd_projeto_aux = 0;
		foreach($this->arrChecklistProjeto as $row){

            $fill=!$fill;

            $quant_table = (is_numeric($row['quant_table']))?number_format($row['quant_table'],0,'','.'):$row['quant_table'];

			$this->objPdf->SetFont('helvetica', '', 8);
			$this->objPdf->Cell($w[0], 6, $row['tx_sigla_projeto'], 'LR', 0, 'L', $fill);
			$this->objPdf->Cell($w[1], 6, number_format($row['caso_uso'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[2], 6, number_format($row['situacao_projeto'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[3], 6, number_format($row['profissional_projeto'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[4], 6, number_format($row['planejamento'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[5], 6, number_format($row['modelo'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[6], 6, number_format($row['wireframe'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[7], 6, number_format($row['conhecimento_projeto'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Cell($w[8], 6, $quant_table, 'LR', 0, 'C', $fill,'',3);
			$this->objPdf->Cell($w[9], 6, number_format($row['informacao_tecnica'],0,'','.'), 'LR', 0, 'C', $fill);
			$this->objPdf->Ln();

			$cd_projeto_aux = $row['cd_projeto'];
		}
                    
		$this->objPdf->SetFont('helvetica', 'I', 5);
		$this->objPdf->Cell(267, 6, "", 'T', 0);
	}

    public function getQuantTabelasProjeto($arrChecklistProjeto)
    {
        $_objConfigBanco    = new ConfigBancoDeDados($this->_request->getControllerName());
        
        $i = 0;
        foreach ($arrChecklistProjeto as $projeto){
            $objDataConection = $_objConfigBanco->getConfigBancoDados($projeto['cd_projeto']);
            $arrDataConection = array();
            if(!is_null($objDataConection)) {
                $arrDataConection = $objDataConection->toArray();
                $arrRetorno       = $this->_helper->conexao->validaConexao($arrDataConection);
                if ($arrRetorno['error'] == false) {
                    $this->_helper->conexao->getListQuantTable($objDataConection->tx_adapter,$objDataConection->tx_schema);
                    $_arrQuantTable   = $this->_helper->conexao->getArrQuantTable();
                    if (count($_arrQuantTable)>0) {
                        switch ($objDataConection->tx_adapter) {
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES:
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                                $arrChecklistProjeto[$i]['quant_table'] = $_arrQuantTable['quant_table'];
                                break;
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE:
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
                                $arrChecklistProjeto[$i]['quant_table'] = $_arrQuantTable['QUANT_TABLE'];
                                break;
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL:
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
                                $arrChecklistProjeto[$i]['quant_table'] = $_arrQuantTable['quant_table'];
                                break;
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL:
                            case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
                                $arrChecklistProjeto[$i]['quant_table'] = $_arrQuantTable['quant_table'];
                                break;
                        }
                    }else{
                        $arrChecklistProjeto[$i]['quant_table'] = 0;
                    }
                }else{
                    $arrChecklistProjeto[$i]['quant_table'] = Base_Util::getTranslator('L_VIEW_DADOS_BD_INVALIDOS');
                }
            }else{
                $arrChecklistProjeto[$i]['quant_table'] = Base_Util::getTranslator('L_VIEW_SEM_CONFIG_BD');
            }
            $i++;
        }
        return $arrChecklistProjeto;
    }
}