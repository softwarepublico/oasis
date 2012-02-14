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

class RelatorioProjeto_AlocacaoRecursoController extends Base_Controller_Action 
{
	private $objProjeto;
	private $cd_projeto;
	private $cd_proposta;
	private $siglaMetricaPadrao;
	private $objContrato;
	private $objContratoProjeto;
	private $objContratoDefinicaoMetrica;
	private $_parcelaOrcamento;

	public function init()
	{
		parent::init();
		$this->objProjeto  		           = new Projeto($this->_request->getControllerName());
		$this->objContrato 		           = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto          = new ContratoProjeto($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->_parcelaOrcamento           = new Base_Controller_Action_Helper_ParcelaOrcamento();
	}
	
	/**
	 * Método que mandará os paramêtros para a tela inicial 
	 *
	 */
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ALOCACAO_RECURSO'));
        
		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}

	/**
	 * Método que receberá os paramêtros da tela, irá recuperar as informações
	 * do banco de dados para o relatório. 
	 */
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

		$objRelAlocacao = new RelatorioProjetoAlocacao();
		$objProposta    = new Proposta($this->_request->getControllerName());
		
		$this->cd_projeto  = (int)$this->_request->getParam('cd_projeto');
		$this->cd_proposta = (int)$this->_request->getParam('cd_proposta');

		$arrSiglaMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($this->cd_projeto);
		$this->siglaMetricaPadrao = $arrSiglaMetricaPadrao['tx_sigla_unidade_metrica'];

		$arrAlocacao = $objRelAlocacao->relAlocacaoRecurso($this->cd_projeto,$this->cd_proposta);

		$arrProjeto  = $this->objProjeto->find($this->cd_projeto)->current()->toArray();
		$arrProposta = $objProposta->find($this->cd_proposta,$this->cd_projeto)->current()->toArray();
		
		$stProposta                       = " - ".Base_Util::getTranslator('L_VIEW_EVOLUTIVO');
		$porc_horas_projeto               = 0;
		$st_parcela_orcamento             = '';
		$ni_porcentagem_parc_orcamento = 0;
		if($this->cd_proposta == 1){
			$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($this->cd_projeto);
			$st_parcela_orcamento             = $this->_parcelaOrcamento->getStParcelaOrcamento();
			$ni_porcentagem_parc_orcamento = $this->_parcelaOrcamento->getNiPorcentagemParcelaOrcamento();
			if ($st_parcela_orcamento == 'S') {
				$porc_horas_projeto = ($arrProposta['ni_horas_proposta']*$ni_porcentagem_parc_orcamento)/100;
				$porc_horas_projeto = round($porc_horas_projeto,1);
			}
			$stProposta         = " - ".Base_Util::getTranslator('L_VIEW_PROPOSTA_INICIAL');
		}
		
		$this->geraRelatorio($arrAlocacao,$arrProjeto,$arrProposta,$stProposta,$porc_horas_projeto,$st_parcela_orcamento,$ni_porcentagem_parc_orcamento);
	}

	/**
	 * Método que irá receber os parametros do banco de dados e montará o relatório em
	 * PDF
	 *
	 * @param array $arrAlocacao
	 * @param array $arrProjeto
	 * @param array $arrProposta
	 * @param text $stProposta
	 * @param text $porc_horas_projeto
	 */
	private function geraRelatorio($arrAlocacao,$arrProjeto,$arrProposta,$stProposta,$porc_horas_projeto,$st_parcela_orcamento,$ni_porcentagem_parc_orcamento)
	{

		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_ALOCACAO_RECURSO'),
                             Base_Util::getTranslator('L_VIEW_ALOCACAO'),
                             Base_Util::getTranslator('L_VIEW_RECURSO'),
                             Base_Util::getTranslator('L_VIEW_ALOCACAO_RECURSO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ALOCACAO_RECURSO'), $arrKeywords);

		$objPdf->SetDisplayMode("real");
		$objPdf->AddPage('P','A4');

		$w  = array(150,30);
		$w2 = ($this->cd_proposta != 1)?array(1,120):array(75,75);

		//Informações do Projeto
		$objPdf->SetFont('', 'B',8);
		$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_PROJETO').":",'',0);
		$objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_SIGLA').":",'',0);
		$objPdf->Ln(4);
		$objPdf->SetFont('', '',8);
		$objPdf->Cell($w[0],6,$arrProjeto['tx_projeto'],'',0);
		$objPdf->Cell($w[1],6,$arrProjeto['tx_sigla_projeto'],'',0);
		$objPdf->Ln(7);

		//Informações da Proposta e da quantidade de horas
		$objPdf->SetFont('', 'B',8);
		$objPdf->Cell($w[0] - $w2[0],6, Base_Util::getTranslator('L_VIEW_PROPOSTA').":",'',0,"L");
		$objPdf->Cell($w[0] - $w2[1],6, Base_Util::getTranslator('L_VIEW_SIGLA_UNID_METRICA_PROJETO', $this->siglaMetricaPadrao).":",'',0);


		//Condição que mosta a porcentagem inicial do projeto
		if($this->cd_proposta == 1 && $st_parcela_orcamento == 'S'){
			$objPdf->Cell($w[1],6, Base_Util::getTranslator('L_VIEW_PORCENTAGEM_PARCELA_ORCAMENTO_PROJETO', $ni_porcentagem_parc_orcamento).":",'',0);
		}

		$objPdf->Ln(4);
		$objPdf->SetFont('', '',8);
		$objPdf->Cell($w[0] - $w2[0],6,$this->cd_proposta.$stProposta,'',0);
		$objPdf->Cell($w[0] - $w2[1],6,number_format($arrProposta['ni_horas_proposta'],0,'','.'),'',0);
		//Condição que mosta a porcentagem inicial do projeto
		if($this->cd_proposta == 1 && $st_parcela_orcamento == 'S'){
			$objPdf->Cell($w[1],6,number_format($porc_horas_projeto,0,'','.'),'',0);
			$arrProposta['ni_horas_proposta'] += $porc_horas_projeto;
		}
		$objPdf->Ln(10);

		//Loop para mostrar as alocações do contrato
		$cd_projeto_previsto_aux = 0;
		$contrato_aux            = "";
		$saldoAlocacao           = 0;
		$totalAlocacao           = 0;
		foreach($arrAlocacao as $key=>$value){
			if($contrato_aux != $value['tx_numero_contrato']){
				$objPdf->SetFont('', 'B',8);
				if($contrato_aux != ""){
					$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_TOTAL_ALOCADO').":",'LBRT',0);
					$objPdf->Cell($w[1],6, number_format($totalAlocacao,0,'','.'),'LBRT',1);
					$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_SALDO_ALOCAR').":",'LBRT',0);
					$objPdf->Cell($w[1],6, number_format($saldoAlocacao,0,'','.'),'LBRT',1);
					$objPdf->Ln(10);
					//Inicializa as variaveis novamente
					$totalAlocacao = 0;
					$saldoAlocacao = 0;
				}

				//Mostra o número do Contrato.
				$objPdf->Cell(14,6, Base_Util::getTranslator('L_VIEW_CONTRATO').":",'',0);
				$objPdf->SetFont('', '',8);
				$objPdf->Cell(165,6,$value['tx_numero_contrato'],'',1);
				$objPdf->SetFont('', 'B',8);
				//Monta cabeçalho do projeto
				$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_ALOCACAO'),'LBRT',0);
				$objPdf->Cell($w[1],6,$this->siglaMetricaPadrao,'LBRT',1);
			}
			$objPdf->SetFont('', '',8);

			if($cd_projeto_previsto_aux != $value['cd_projeto_previsto']){
				$objPdf->Cell($w[0],6,$value['tx_projeto_previsto'],'LBRT',0);
				$objPdf->Cell($w[1],6,number_format($value['ni_horas'],0,'','.'),'LBRT',1);
			}
			$cd_projeto_previsto_aux = $value['cd_projeto_previsto'];
			$totalAlocacao          += $value['ni_horas'];

			$saldoAlocacao = $arrProposta['ni_horas_proposta'] - $totalAlocacao;
			$contrato_aux  = $value['tx_numero_contrato'];
		}

		$objPdf->SetFont('', 'B',8);
		$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_TOTAL_ALOCADO').":",'LBRT',0);
		$objPdf->Cell($w[1],6,number_format($totalAlocacao,0,'','.'),'LBRT',1);

		$objPdf->Cell($w[0],6, Base_Util::getTranslator('L_VIEW_SALDO_ALOCAR').":",'LBRT',0);
		$objPdf->Cell($w[1],6,number_format($saldoAlocacao,0,'','.'),'LBRT',1);
		
		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_alocacao_recurso.pdf', 'I');		
	}

}