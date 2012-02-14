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

class RelatorioProjeto_AnaliseExecucaoProjetoController extends Base_Controller_Action
{
	private $objProposta;
	private $_objAnaliseExecucaoProjeto;
	private $arrImpressao = array();
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();

		$this->objProposta				 = new Proposta($this->_request->getControllerName());
		$this->_objAnaliseExecucaoProjeto = new RelatorioProjetoAnaliseExecucaoProjeto();
		$this->objContrato				 = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto		 = new ContratoProjeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ANALISE_EXECUCAO_PROJETO'));

		$comStatus   = true;
		$cd_contrato = null;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus = false;
		}
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function pesquisaProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objProposta->getProjetosExecucaoSemEncerramentoProposta( true, $cd_contrato );

		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $options;
	}

	public function analiseExecucaoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		$post['mes'] = ( $post['mes'] < 10 ) ? '0'.$post['mes'] : $post['mes'];
		
		$cd_projeto	= $post['cd_projeto'];
		$mes		= $post['mes'];
		$ano		= $post['ano'];

		$this->arrImpressao = $this->_objAnaliseExecucaoProjeto->getDadosAnaliseExecucao( $cd_projeto, $mes, $ano );

		$this->geraRelatorio();
	}
    
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_ANALISE_EXECUCAO_PROJETO'),
                             Base_Util::getTranslator('L_VIEW_ANALISE'),
                             Base_Util::getTranslator('L_VIEW_EXECUCAO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ANALISE_EXECUCAO_PROJETO'), $arrKeywords);

		$objPdf->SetDisplayMode("real");
		$objPdf->AddPage();
		
		$w = array(180,15,165,25);

		if(count($this->arrImpressao) > 0){

			$objPdf->SetFillColor(240,240,240);
			$objPdf->SetTextColor(0);

			$objPdf->SetFont('helvetica', 'B', 8);
			$objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PROJETO').":",'',0,'L',1);
			$objPdf->SetFont('helvetica', '', 8);
			$objPdf->Cell($w[2], 6, $this->arrImpressao[0]['tx_sigla_projeto'],'',1,'L',1);
			$objPdf->Cell($w[0], 6, "",'',1,'L',0);
			
			$qtdRegistro = count($this->arrImpressao)-1;
			$margemBotton = "B";
			$qtdFor = 0;
			foreach( $this->arrImpressao as $rs ){
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DATA_ANALISE'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[0], 2, $rs['dt_analise_execucao_projeto_f'],'',1,'L',0);
				$objPdf->Cell($w[0], 1, "",'',1,'L',0);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_RESULTADO_ANALISE'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->MultiCell($w[0],2, strip_tags($rs['tx_resultado_analise_execucao'], "<br><span>").PHP_EOL,'','J',0,1,'','',true,0,true);
				$objPdf->Cell($w[0], 1, "",'',1,'L',0);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DATA_DECISAO_ANALISE'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[0], 2, $rs['dt_decisao_analise_execucao_f'],'',1,'L',0);
				$objPdf->Cell($w[0], 1, "",'',1,'L',0);
				
				if($qtdRegistro == 0 || ($qtdRegistro == $qtdFor) ){
					$margemBotton = '';
				}
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_RESULTADO_DECISAO_ANALISE'),'',1,'L',0);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->MultiCell($w[0],2, strip_tags($rs['tx_decisao_analise_execucao'], "<br><span>").PHP_EOL, $margemBotton,'J',0,1,'','',true,0,true);
				$objPdf->Ln(4);

				$qtdFor++;
			}
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_analise_execucao_projeto.pdf', 'I');
	}
}
