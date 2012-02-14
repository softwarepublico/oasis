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

class RelatorioProjeto_PrevisaoMensalProdutosParcelasController extends Base_Controller_Action
{
	private $objProfissionalObjetoContrato;
	private $objProjeto;
	private $objRelPrevisaoMensalProdutosParcelas;
	private $_objContrato;
	private $_objObjetoContrato;
	private $_objUnidade;
	private $arrProdutoParcela;
	private $arrDadosContrato;
	private $arrDadosObjeto;
	private $mes;
	private $ano;
	private $objPdf;
	private $util;
	
    public function init()
    {
        parent::init();
        $this->objProfissionalObjetoContrato        = new ProfissionalObjetoContrato($this->_request->getControllerName());
        $this->objProjeto                           = new Projeto($this->_request->getControllerName());
        $this->objRelPrevisaoMensalProdutosParcelas = new RelatorioProjetoPrevisaoMensalProdutosParcelas();
        $this->_objContrato                         = new Contrato($this->_request->getControllerName());
        $this->_objObjetoContrato                   = new ObjetoContrato($this->_request->getControllerName());
        $this->_objUnidade                          = new Unidade($this->_request->getControllerName());
        $this->_objUtil                             = new Base_Util();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PREVISAO_MENSAL_PRODUTO_PACELA'));

        $cd_contrato 			 = null;
        $comStatus				 = true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
		$this->view->arrUnidade  = $this->_objUnidade->getUnidade(true);
    }
    
    public function comboGerenteAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato  = $this->_request->getParam('cd_contrato');
        $arrDadosObjeto = $this->_objObjetoContrato->getDadosObjetoContrato($cd_contrato);
 
       	$arrProfissionalGerente = $this->objProfissionalObjetoContrato->getProfissionalGerenteObjetoContrato($arrDadosObjeto[0]['cd_objeto'], true);

		$strOption = "";
		foreach($arrProfissionalGerente as $key=>$value)
		{
			$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
		}

		echo $strOption;
    }

    public function comboProjetoGerenteAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
    	
		$cd_profissional_gerente = $this->_request->getParam('cd_profissional_gerente');
		
		$arrProjetoGerente = $this->objProjeto->getProjetoGerente($cd_profissional_gerente);
		
		$strOption = "";
		foreach($arrProjetoGerente as $key=>$value)
		{
			$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
		}
		
		echo $strOption;
    }
    
    public function generateAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout->disableLayout();
		    	
    	$arrPost					= $this->_request->getPost();
		$this->arrProdutoParcela	= $this->objRelPrevisaoMensalProdutosParcelas->getProdutosParcela($arrPost);
		$this->arrDadosContrato     = $this->_objContrato->getDadosContrato($arrPost['cd_contrato']);
		$this->arrDadosObjeto       = $this->_objObjetoContrato->getDadosObjetoContrato($arrPost['cd_contrato']);
		$this->mes                  = $this->_objUtil->getMes($arrPost['mes']);
		$this->ano                  = $arrPost['ano'];
    
		$this->geraRelatorio();
	}


	private function geraRelatorio()
    {
		$objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf();

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PREVISAO_MENSAL_PRODUTO_PACELA'), K_CREATOR_SYSTEM.', '.
                                                                                                              Base_Util::getTranslator('L_TIT_REL_PREVISAO_MENSAL_PRODUTO_PACELA').', '.
                                                                                                              Base_Util::getTranslator('L_VIEW_PRODUTO').', '.
                                                                                                              Base_Util::getTranslator('L_VIEW_PREVISAO').', '.
                                                                                                              Base_Util::getTranslator('L_VIEW_PARCELA'));
		$this->objPdf->SetDisplayMode("real");
		// set font
		$this->objPdf->SetFont('helvetica', 'B', 8);

		// add a page
		$this->objPdf->AddPage();

		$this->objPdf->Cell(30, 4, Base_Util::getTranslator('L_VIEW_CONTRATO').":", '', 0, 'L');
		$this->objPdf->Cell(150, 4, $this->arrDadosContrato[0]['tx_numero_contrato']." (".$this->arrDadosObjeto[0]['tx_objeto'].")", '', 1, 'L');
		$this->objPdf->Cell(30, 4, Base_Util::getTranslator('L_VIEW_MES').":", '', 0, 'L');
		$this->objPdf->Cell(150, 4, $this->mes, '', 1, 'L');
		$this->objPdf->Cell(30, 4, Base_Util::getTranslator('L_VIEW_ANO').":", '', 0, 'L');
		$this->objPdf->Cell(150, 4, $this->ano, '', 1, 'L');
		$this->objPdf->Ln(6);

		if(count($this->arrProdutoParcela) > 0){
			$cd_profissional_aux = 0;
			$cd_projeto_aux      = 0;
			$cd_parcela_aux      = 0;
			$totalGeral          = 0;
			$totalProjetoParcela = 0;
			$possuiProduto       = "";

			foreach($this->arrProdutoParcela as $key=>$value){


				//Mostra o gerente do Projeto
				if($cd_profissional_aux != $value->cd_profissional){
					if(($possuiProduto != "") && ($possuiProduto == "N")){
						$this->objPdf->MultiCell(180, 6, "          ".Base_Util::getTranslator('L_MSG_ALERT_PARCELA_SEM_PRODUTO_CADASTRADO').PHP_EOL, 'LR', 'J', 0, 1);
						$possuiProduto = "";
					}
					if($totalProjetoParcela != 0){
						$this->objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_UNID_METRICA_PROJETO').":", 'LB', 0, 'L');
						$this->objPdf->Cell(130, 6, number_format($totalProjetoParcela,1,',','.')." ".$arrDadosMetricaPadrao['tx_sigla_unidade_metrica'], 'RB', 1, 'R');
						$this->objPdf->Ln(5);
					}
					$this->objPdf->Cell(180, 6, Base_Util::getTranslator('')."Gerente: ".$value->tx_profissional, 'B', 1, 'L');
					$totalGeral += $totalProjetoParcela;
					$totalProjetoParcela = 0;
				}
				//Mostra o projeto
				if($cd_projeto_aux != $value->cd_projeto){

					$arrDadosMetricaPadrao = array();
					$arrDadosMetricaPadrao = $objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($value->cd_projeto);

					if(($possuiProduto != "") && ($possuiProduto == "N")){
						$this->objPdf->MultiCell(180, 6, "          ".Base_Util::getTranslator('L_MSG_ALERT_PARCELA_SEM_PRODUTO_CADASTRADO').PHP_EOL, 'LR', 'J', 0, 1);
						$possuiProduto = "";
					}
					if($totalProjetoParcela != 0){
						$this->objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_UNID_METRICA_PROJETO').":", 'LB', 0, 'L');
						$this->objPdf->Cell(130, 6, number_format($totalProjetoParcela,1,',','.')." ".$arrDadosMetricaPadrao['tx_sigla_unidade_metrica'], 'RB', 1, 'R');
					}
					$this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_PROJETO').": ".$value->tx_sigla_projeto, 'LR', 1, 'L');
					$this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_UNIDADE').": ".$value->tx_sigla_unidade, 'LRB', 1, 'L');
					$totalGeral += round($totalProjetoParcela,1);
					$totalProjetoParcela = 0;
				}
				//Mostra a parcela
				if($cd_parcela_aux != $value->cd_parcela){
					$this->objPdf->Cell(50, 6, "      ".Base_Util::getTranslator('L_VIEW_PARCELA_NR')." ".$value->ni_parcela." (".Base_Util::getTranslator('L_VIEW_PROPOSTA_NR')." {$value->cd_proposta})", 'L', 0, 'L');
					$this->objPdf->Cell(130, 6, number_format($value->ni_horas_parcela,1,',','.')." ".$arrDadosMetricaPadrao['tx_sigla_unidade_metrica'], 'R', 1, 'R');
					//Soma a hora da Parcela
					$totalProjetoParcela += round($value['ni_horas_parcela'],1);
				}

				if($value->tx_produto_parcela != ""){
				   $possuiProduto = "S";
					// set font
					$this->objPdf->SetFont('helvetica', '', 8);
					//Imprime os produtos
					$this->objPdf->MultiCell(180, 6, "          ".trim($value->tx_produto_parcela).PHP_EOL, 'LR', 'J', 0, 1);
				} else {
					$possuiProduto = "N";
				}

				$cd_profissional_aux = $value->cd_profissional;
				$cd_projeto_aux      = $value->cd_projeto;
				$cd_parcela_aux      = $value->cd_parcela;
			}
			// set font
			$this->objPdf->SetFont('helvetica', 'B', 8);

			$this->objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_UNID_METRICA_PROJETO').":", 'LB', 0, 'L');
			$this->objPdf->Cell(130, 6, number_format($totalProjetoParcela,1,',','.')." ".$arrDadosMetricaPadrao['tx_sigla_unidade_metrica'], 'RB', 1, 'R');
			$totalGeral += round($totalProjetoParcela,1);


			$this->objPdf->ln(5);
			$this->objPdf->Cell(50, 6, Base_Util::getTranslator('L_VIEW_TOTAL_UNIDADE_METRICA_MES').":", 'LBT', 0, 'L');
			$this->objPdf->Cell(130, 6, number_format($totalGeral,1,',','.'), 'RBT', 1, 'R');
		} else {
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$this->objPdf->Output('relatorio_parecer_proposta.pdf', 'I');

	}
}