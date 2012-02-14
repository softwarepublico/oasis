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

class RelatorioProjeto_SituacaoProjetoController extends Base_Controller_Action
{
	private $objRelProjeto;
	private $arrDadosProjeto = array();
	private $objUtil;
	
	public function init()
	{
		parent::init();
		$this->objRelProjeto = new RelatorioProjetoProjeto();
		$this->objUtil       = new Base_Controller_Action_Helper_Util();
	}
	
	public function indexAction()
	{
		$this->view->headTitle(Base_Util::setTitle('L_TIT_REL_SITUACAO_PROJETO'));
        
        $this->view->comboSituacao = array('0'=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'),
										   '1'=>Base_Util::getTranslator('L_VIEW_TODOS_PROJETOS'),
										   '2'=>Base_Util::getTranslator('L_VIEW_POR_OBJETO'));
	}
	
	public function situacaoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_situacao = $this->_request->getParam('cd_situacao',0);
		
		if($cd_situacao == 1){
			$this->arrDadosProjeto = $this->objRelProjeto->situacaoProjeto();
			foreach($this->arrDadosProjeto as $key=>$value){
				$this->arrDadosProjeto[$key]['desc_mes_inicio_previsto']  = $this->objUtil->getMesRes($value['ni_mes_inicio_previsto']); 
	            $this->arrDadosProjeto[$key]['desc_mes_termino_previsto'] = $this->objUtil->getMesRes($value['ni_mes_termino_previsto']);
	            $this->arrDadosProjeto[$key]['desc_mes_situacao_projeto'] = $this->objUtil->getMesRes($value['ni_mes_situacao_projeto']);
			}
		} else if($cd_situacao == 2){
			//colocar a chamada do relatório para o objeto	
		}
		$this->gerarRelatorio();
				
	}
	
	public function gerarRelatorio()
	{
		$objPdf = new Base_Tcpdf_Pdf();
		
		$objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_SITUACAO_PROJETO'), K_CREATOR_SYSTEM.", ".Base_Util::getTranslator('L_TIT_REL_SITUACAO_PROJETO').", ".
                                                                                                               Base_Util::getTranslator('L_VIEW_PROJETO').", ".
                                                                                                               Base_Util::getTranslator('L_VIEW_SITUACAO_PROJETO'));
		$objPdf->SetDisplayMode("real");
		
		// add a page
		$objPdf->AddPage();
			
		$w = array(100,80);
		
		foreach($this->arrDadosProjeto as $key=>$value){
			$objPdf->SetFont('helvetica','B',8);
			$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROJETO').":",'',0);
			$objPdf->ln(4);
			$objPdf->SetFont('helvetica','',8);
			$objPdf->Cell($w[0], 6,$value['tx_projeto'],'',0);
			$objPdf->ln(5);
			
			$objPdf->SetFont('helvetica','B',8);
			$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DATA_INICIO_PREVISTO').":",'',0);
			$objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_FINAL_PREVISTO' ).":",'',0);
			$objPdf->ln(4);
			$objPdf->SetFont('helvetica','',8);
			$objPdf->Cell($w[0], 6,"{$value['desc_mes_inicio_previsto']}/{$value['ni_ano_inicio_previsto']}",'',0);
			$objPdf->Cell($w[1], 6,"{$value['desc_mes_termino_previsto']}/{$value['ni_ano_termino_previsto']}",'',0);
			$objPdf->ln(5);
			
			$objPdf->SetFont('helvetica','B',8);
			$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SITUACAO_MES_ANO').":",'',0);
			$objPdf->ln(4);
			$objPdf->SetFont('helvetica','',8);
			$objPdf->Cell($w[1], 6,"{$value['desc_mes_situacao_projeto']}/{$value['ni_ano_situacao_projeto']}",'',0);
			$objPdf->ln(5);
			
			$objPdf->SetFont('helvetica','B',8);
			$objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SITUACAO').":",'',0);
			$objPdf->ln(4);
			$objPdf->SetFont('helvetica','',8);
			$objPdf->MultiCell($w[0]+$w[1], 6,trim(strip_tags("{$value['tx_situacao_projeto']}","<br><span><b><i><ul><li><ol>"))."\n",'','',0,1,'','',true,'',true);
			
			$objPdf->Cell($w[0]+$w[1],6,"",'B',0);
			$objPdf->ln(7);
		}

		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_situacao.pdf', 'I');
	}

}