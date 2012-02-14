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

class RelatorioProjeto_AnaliseMedicaoController extends Base_Controller_Action
{
	private $objRelatorioAnaliseMedicao;
	private $arrImpressao = array();
	private $tx_medicao;
	private $tx_box_inicio;
	
	public function init()
	{
		parent::init();
		$this->objRelatorioAnaliseMedicao = new RelatorioProjetoAnaliseMedicao();
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ANALISE_MEDICAO'));

		$objMedicao		= new Medicao($this->_request->getControllerName());
		$objBoxInicio	= new BoxInicio($this->_request->getControllerName());
		
		$this->view->comboMedicao	= $objMedicao->getMedicoes( true );
		$this->view->comboBoxInicio = $objBoxInicio->getBoxInicio( true );
	}
	
	public function analiseMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_medicao		= (int) $this->_request->getParam('cd_medicao',0);
		$tipo_relatorio = 		$this->_request->getParam('relatorio');
		
		if($tipo_relatorio == 'm'){// value do radio button
			$arr = $this->objRelatorioAnaliseMedicao->analiseMedicao( $cd_medicao );
		}
		
		if( count($arr) > 0){
			$this->tx_medicao = $arr[0]['tx_medicao'];
			
			$cd_box_inicio = '';
			foreach( $arr as $rs ){
				
				if($cd_box_inicio != $rs['cd_box_inicio']){
					$box = $rs['tx_titulo_box_inicio'];
				}
				
				$this->arrImpressao[$box][] = $rs;
				$cd_box_inicio = $rs['cd_box_inicio'];
			}
		}
		$this->geraRelatorioMedicao();
	}
	
	public function analiseMedicaoBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		
		$cd_box_inicio	= (int) $this->_request->getParam('cd_box_inicio',0);
		$tipo_relatorio = 		$this->_request->getParam('relatorio');

		if($tipo_relatorio == 'b'){ // value do radio button
			$arr = $this->objRelatorioAnaliseMedicao->analiseMedicao( null, $cd_box_inicio );
		}
		
		if( count($arr) > 0){
			$this->tx_box_inicio = $arr[0]['tx_titulo_box_inicio'];
			
			$cd_medicao = '';
			foreach( $arr as $rs ){
				if($cd_medicao != $rs['cd_medicao']){
					$medicao = $rs['tx_medicao'];
				}
				$this->arrImpressao[$medicao][] = $rs;
				$cd_medicao = $rs['cd_medicao'];
			}
		}
		$this->geraRelatorioBoxInicio();
	}

	/**
	 * Método para gerar relatorio apartir da opção de Medição
	 */
	private function geraRelatorioMedicao()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_ANALISE_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_ANALISE_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ANALISE_MEDICAO'), $arrKeywords);
		
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180, 8, 172);
		
		if(count($this->arrImpressao) > 0){
			
			$objPdf->SetFillColor(220,220,220);
			$objPdf->SetTextColor(0);
			
			$objPdf->SetFont('helvetica', '', 10);
			$objPdf->MultiCell($w[0],6, "<b>".Base_Util::getTranslator('L_VIEW_MEDICAO').":</b> {$this->tx_medicao}".PHP_EOL,'','J',1,1,'','',true,0,true);
			
			$objPdf->SetFillColor(240,240,240);
			$objPdf->Cell($w[0],2,"","",1);
			
			$qtdBox = count($this->arrImpressao)-1;
			$qtdFor = 0;
			foreach( $this->arrImpressao as $box=>$value ){
				
				$objPdf->SetFont('helvetica', '', 9);
				$objPdf->MultiCell($w[0],6, "&nbsp;&nbsp;&nbsp;<b>".Base_Util::getTranslator('L_VIEW_BOX').":</b> ".$box.PHP_EOL,'','J',1,1,'','',true,0,true);
				
				$qtdMedicao = count($value)-1;
				$qtdSubFor	= 0;
				foreach( $value as $dados ){

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_DADOS_MEDICAO').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['tx_dados_medicao'].PHP_EOL,'','J',0,1,'','',true,0,true);

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_ANALISE').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['dt_analise_medicao_f']." (".$dados['tx_profissional'].") ".  $dados['tx_resultado_analise_medicao'].PHP_EOL,'','J',0,1,'','',true,0,true);

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_DECISAO').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['dt_decisao_f']."   ".$dados['tx_decisao'].PHP_EOL,'','J',0,1,'','',true,0,true);

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_OBSERVACOES').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['tx_obs_decisao_executada'].PHP_EOL,'','J',0,1,'','',true,0,true);
					
					if( $qtdMedicao > 0 ){
						if( $qtdSubFor < $qtdMedicao ){
							$objPdf->Cell(($w[1]+2),6,"","");
							$objPdf->Cell(($w[2]-2),2,"","",1);
							$objPdf->Cell(($w[1]+2),6,"","");
							$objPdf->Cell(($w[2]-2),2,"","T",1);
						}
					}
					$qtdSubFor++;
				}
				if( $qtdBox > 0  ){
					if( $qtdFor < $qtdBox){
						$objPdf->Cell($w[0],2,"","B",1);
						$objPdf->Cell($w[0],2,"","",1);
					}
				}
				$qtdFor++;
			}
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_analise_medicao.pdf', 'I');
	}

	/**
	 * Método para gerar relatorio apartir da opção de Box
	 */
	private function geraRelatorioBoxInicio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_ANALISE_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_ANALISE_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_MEDICAO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ANALISE_MEDICAO'), $arrKeywords);

		
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180, 8, 172);
		
		if(count($this->arrImpressao) > 0){
			
			$objPdf->SetFillColor(220,220,220);
			$objPdf->SetTextColor(0);
			
			$objPdf->SetFont('helvetica', '', 10);
			$objPdf->MultiCell($w[0],6, "<b>".Base_Util::getTranslator('L_VIEW_BOX').":</b> ". $this->tx_box_inicio.PHP_EOL,'','J',1,1,'','',true,0,true);
			
			$objPdf->SetFillColor(240,240,240);
			$objPdf->Cell($w[0],2,"","",1);

			$qtdMedicao = count($this->arrImpressao)-1;
			$qtdFor		= 0;
			
			foreach( $this->arrImpressao as $medicao=>$value){
				
				$objPdf->SetFont('helvetica', '', 9);
				$objPdf->MultiCell($w[0],6, "&nbsp;&nbsp;&nbsp;<b>".Base_Util::getTranslator('L_VIEW_MEDICAO').":</b>". $medicao.PHP_EOL,'','J',1,1,'','',true,0,true);
				
				$qtdDados = count($value)-1;
				$qtdSubFor  = 0;
				foreach( $value as $dados ){

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_DADOS_MEDICAO').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['tx_dados_medicao'].PHP_EOL,'','J',0,1,'','',true,0,true);

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6,"Análise:",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['dt_analise_medicao_f']." (".$dados['tx_profissional'].") ". $dados['tx_resultado_analise_medicao'].PHP_EOL,'','J',0,1,'','',true,0,true);

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_DECISAO').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['dt_decisao_f']."   ".$dados['tx_decisao'].PHP_EOL,'','J',0,1,'','',true,0,true);

					$objPdf->SetFont('helvetica', 'B', 8);
					$objPdf->Cell($w[1],6,"",'');
					$objPdf->Cell($w[2],6, Base_Util::getTranslator('L_VIEW_OBSERVACOES').":",'',1);
					
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->Cell(($w[1]+4),6,"",'');
					$objPdf->MultiCell(($w[2]-4),6, $dados['tx_obs_decisao_executada'].PHP_EOL,'','J',0,1,'','',true,0,true);
					
					if( $qtdDados > 0 ){
						if( $qtdSubFor < $qtdDados ){
							$objPdf->Cell(($w[1]+2),6,"","");
							$objPdf->Cell(($w[2]-2),2,"","",1);
							$objPdf->Cell(($w[1]+2),6,"","");
							$objPdf->Cell(($w[2]-2),2,"","T",1);
						}
					}
					$qtdSubFor++;
				}
				if( $qtdMedicao > 0  ){
					if( $qtdFor < $qtdMedicao){
						$objPdf->Cell($w[0],2,"","B",1);
						$objPdf->Cell($w[0],2,"","",1);
					}
				}
				$qtdFor++;
			}
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_analise_medicao.pdf', 'I');
	}

}