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

class RelatorioProjeto_CatalogoProjetoController extends Base_Controller_Action
{
	private $objRelCatalagoProjeto;
	private $objProjeto;
	private $arrImpressao;
	private $objPdf;

	public function init()
	{
		parent::init();
		$this->objRelCatalagoProjeto = new RelatorioCatalogoProjeto();
		$this->objProjeto			 = new Projeto();
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CATALOGO_PROJETO'));
		$this->view->comboProjeto	= $this->objProjeto->getProjeto(true, null, true);
	}

	public function catalogoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_projeto = ($cd_projeto == 0) ? null: $cd_projeto;
		
		$arrCatalogoProjeto = $this->objRelCatalagoProjeto->relCatalogoProjeto($cd_projeto);

		$arrTipoConhecimento = array();
		$strConhecimento	 = '';
		
		$objUtil = new Base_Controller_Action_Helper_Util();
		
		foreach($arrCatalogoProjeto as $key=>$value){

			$arrDt1 = array();
			$arrDt2 = array();

			//converte a data Inicial Ex.: 1/2009 para Jan/2009
			if( $value->inicio ){
				$arrDt1 = explode("/", $value->inicio);
				$dtInicio = $objUtil->getMesRes(trim($arrDt1[0]))."/".trim($arrDt1[1]);
			}else{
				$dtInicio = $value->inicio;
			}
			//converte a data Final Ex.: 1/2009 para Jan/2009
			if( $value->termino ){
				$arrDt2 = explode("/", $value->termino);
				$dtTermino = $objUtil->getMesRes(trim($arrDt2[0]))."/".trim($arrDt2[1]);
			}else{
				$dtTermino = $value->termino;
			}

			$arrConhecimento = $this->objRelCatalagoProjeto->getConhecimento($value->cd_projeto);

			if( $arrConhecimento->valid() ){
				$cd_tipo_conhecimento_aux = '';
				foreach ($arrConhecimento as $key1 => $conhecimento) {
					
					if( $cd_tipo_conhecimento_aux != $conhecimento->cd_tipo_conhecimento ){
						if($cd_tipo_conhecimento_aux != ''){
							$arrTipoConhecimento[]	  = $strConhecimento;
						}
						$strConhecimento = $conhecimento->tx_tipo_conhecimento."| ".$conhecimento->tx_conhecimento; //A utilização do pipe "|" é para quebrar quando for
					}else{
						$strConhecimento .= "/".$conhecimento->tx_conhecimento;
					}
					$cd_tipo_conhecimento_aux = $conhecimento->cd_tipo_conhecimento;
				}
				//imprime no array o ultimo conhecimento
				$arrTipoConhecimento[]	  = $strConhecimento;
			}

			$this->arrImpressao[$value->cd_projeto]['tx_sigla_projeto'			] = $value->tx_sigla_projeto;
			$this->arrImpressao[$value->cd_projeto]['tx_projeto'				] = $value->tx_projeto;
			$this->arrImpressao[$value->cd_projeto]['tx_contexto_geral_projeto'] = $value->tx_contexto_geral_projeto;
			$this->arrImpressao[$value->cd_projeto]['inicio'					] = $dtInicio;
			$this->arrImpressao[$value->cd_projeto]['termino'					] = $dtTermino;
			$this->arrImpressao[$value->cd_projeto]['tx_sigla_unidade'			] = $value->tx_sigla_unidade;
			$this->arrImpressao[$value->cd_projeto]['tx_publico_alcancado'		] = $value->tx_publico_alcancado;
			$this->arrImpressao[$value->cd_projeto]['tx_tipo_conhecimento'		] = $arrTipoConhecimento;

			//zera o array para o proximo projeto
			$arrTipoConhecimento = array();
		}

		$this->gerarRelatorio();
	}

	private function gerarRelatorio()
	{
		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                     Base_Util::getTranslator('L_TIT_REL_CATALOGO_PROJETO'),
                     Base_Util::getTranslator('L_VIEW_PROJETO'),
                     Base_Util::getTranslator('L_VIEW_CATALOGO'),
                     Base_Util::getTranslator('L_VIEW_RELATORIO'));

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_CATALOGO_PROJETO'), $arrKeywords);

		$this->objPdf->SetDisplayMode("real");
		// add a page
        set_time_limit(1000);
		foreach( $this->arrImpressao as $key=>$value ){
			$this->objPdf->AddPage();

			$this->imprimeDados($value);
		}
		//Close and output PDF document
		$this->objPdf->Output('relatorio_catalogo_projeto.pdf', 'I');
	}

	private function imprimeDados( $dados )
	{
		$w = array(180, 55, 125);
		$h = array(5, 6);

        $header = array( Base_Util::getTranslator('L_VIEW_SISTEMA')." (".Base_Util::getTranslator('L_VIEW_SIGLA')." - ".Base_Util::getTranslator('L_VIEW_NOME')."):",
                         Base_Util::getTranslator('L_VIEW_UNIDADE')."(".Base_Util::getTranslator('L_VIEW_SETOR').") :",
                         Base_Util::getTranslator('L_VIEW_PREVISAO_INICIO').":",
                         Base_Util::getTranslator('L_VIEW_PREVISAO_TERMINO').":",
                         Base_Util::getTranslator('L_VIEW_PUBLICO_ALVO').":",
                         Base_Util::getTranslator('L_VIEW_DESCRICAO_SISTEMA')
                        );

		$this->objPdf->SetFont('', 'B',10);
		$this->objPdf->Cell($w[0], $h[1], $dados['tx_projeto'], '', 1, 'C');
		$this->objPdf->Cell($w[0], $h[1], $dados['tx_sigla_projeto'], '', 1, 'C');
		$this->objPdf->Ln($h[0]);
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell(180,0,"",'B',1,'L');

		$this->objPdf->ln();
		$this->objPdf->SetFont('', 'B',10);
		$this->objPdf->Cell($w[0], $h[1], "Indentificação", '', 1, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell(180,0,"",'B',1,'L');
		
		$this->objPdf->ln();
		$this->objPdf->SetFont('', 'B',8);
		$this->objPdf->Cell($w[1], $h[1], $this->toUpper($header[0]), '', 0, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell($w[2], $h[1], $dados['tx_sigla_projeto']." - ". $dados['tx_projeto'], '', 1, 'L');
		
		$this->objPdf->SetFont('', 'B',8);
		$this->objPdf->Cell($w[1], $h[1], $this->toUpper($header[1]), '', 0, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell($w[2], $h[1], $dados['tx_sigla_unidade'], '', 1, 'L');

		$this->objPdf->SetFont('', 'B',8);
		$this->objPdf->Cell($w[1], $h[1], $this->toUpper($header[2]), '', 0, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell($w[2], $h[1], $dados['inicio'], '', 1, 'L');

		$this->objPdf->SetFont('', 'B',8);
		$this->objPdf->Cell($w[1], $h[1], $this->toUpper($header[3]), '', 0, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell($w[2], $h[1], $dados['termino'], '', 1, 'L');

		$this->objPdf->SetFont('', 'B',8);
		$this->objPdf->Cell($w[1], $h[1], $this->toUpper($header[4]), '', 0, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell($w[2], $h[1], $dados['tx_publico_alcancado'], '', 1, 'L');

		$this->objPdf->Cell(180,0,"",'B',1,'L');

		$this->objPdf->Ln($h[0]);
		$this->objPdf->SetFont('', 'B',10);
		$this->objPdf->Cell($w[0], $h[0], $header[5], '', 1, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell(180,0,"",'B',1,'L');
		$this->objPdf->ln();
		$this->objPdf->MultiCell($w[0], $h[1], $dados['tx_contexto_geral_projeto'], '', 'J', 0, 1, '', '', '', '', true);
		$this->objPdf->Cell(180,0,"",'B',1,'L');

		$this->objPdf->ln();
		$this->objPdf->SetFont('', 'B',10);
		$this->objPdf->Cell($w[0], $h[1], "Conhecimento Aplicado", '', 1, 'L');
		$this->objPdf->SetFont('', '',8);
		$this->objPdf->Cell(180,0,"",'B',1,'L');

		$this->objPdf->ln();
		if( count($dados['tx_tipo_conhecimento']) > 0 ){
			foreach($dados['tx_tipo_conhecimento'] as $res ){
				$arrConhecimento = explode("|", $res);

				$this->objPdf->SetFont('', 'B',8);
				$this->objPdf->MultiCell($w[1], $h[1], trim($arrConhecimento[0]) . ":" . PHP_EOL, '', 'L', 0, 0);
				$this->objPdf->SetFont('', '',8);
				$this->objPdf->MultiCell($w[2], $h[1], trim($arrConhecimento[1]) . PHP_EOL, '', 'J', 0, 1);
			}
		}

		$this->objPdf->ln();
		$this->objPdf->Cell(180,0,"",'B',1,'L');

		$this->objPdf->ln(5);
		$this->objPdf->Cell(5,0,"",'B',1,'L');

	}
}