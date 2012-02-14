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

class RelatorioDiverso_EtapaAtividadeAreaTiController extends Base_Controller_Action
{
	
	private $objAreaAtuacaoTi;
	private $objEtapa;
	private $objAtividade;
	private $arrEtapa   	= array();
	private $arrAtividade	= array();
	private $objPdf;
	private $cd_area_atuacao_ti;
	private $tx_area_atuacao_ti;
	
	public function init()
	{
		parent::init();
		
		$this->objAreaAtuacaoTi  = new AreaAtuacaoTi($this->_request->getControllerName());
		$this->objEtapa			 = new Etapa($this->_request->getControllerName());
		$this->objAtividade		 = new Atividade($this->_request->getControllerName());
		$this->objPdf			 = new Base_Tcpdf_Pdf();
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ETAPA_ATIVIDADE'));

		$this->view->comboAreaTi = $this->objAreaAtuacaoTi->comboAreaAtuacaoTi( true );
	}
	
	public function etapaAtividadeAreaTiAction()
	{
       
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$this->cd_area_atuacao_ti = $this->_request->getParam("cd_area_atuacao_ti");

        $this->tx_area_atuacao_ti = $this->objAreaAtuacaoTi->pegarAreaAtuacaoTi($this->cd_area_atuacao_ti);
        
               
		$this->arrEtapa     = $this->objEtapa->getEtapaAreaTI( $this->cd_area_atuacao_ti );
        if(count($this->arrEtapa) > 0){
            //montando o array para impressao
            foreach( $this->arrEtapa as $key=>$value ){
                $this->arrAtividade[$value] = $this->objAtividade->getAtividade( $key );
            }
		}
        
        $this->geraRelatorio();
	}

	private function geraRelatorio()
	{
        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_ETAPA_ATIVIDADE'),
                             Base_Util::getTranslator('L_VIEW_ATIVIDADE'),
                             Base_Util::getTranslator('L_VIEW_ETAPA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );
        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ETAPA_ATIVIDADE').' - '.$this->tx_area_atuacao_ti['tx_area_atuacao_ti'], $arrKeywords);

		$this->objPdf->SetDisplayMode("real");
		// set font
		$this->objPdf->AddPage();
		$w = array(180);
		
		if(count($this->arrAtividade) > 0){
	
			$this->objPdf->SetFillColor(240,240,240);
			$this->objPdf->SetTextColor(0);
	
			$this->objPdf->SetFont('helvetica', 'B', 8);
			$this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_ETAPAS'),'LRT',1,'L',1);
			$this->objPdf->Cell($w[0], 6, "               ".Base_Util::getTranslator('L_VIEW_ATIVIDADES'),'LR',1,'L',1);
			
			$qtdEtapas    = count($this->arrAtividade);
			$qtdFor       = 1;
			$borderBotton = "";
			$qtdAtividade = 0;
			foreach($this->arrAtividade as $key=>$value){
				$this->objPdf->SetFont('helvetica', 'B', 8);
				$this->objPdf->Cell($w[0], 6, $key,'LR',1);
				
				$this->objPdf->SetFillColor(240, 248, 255);
				$this->objPdf->SetTextColor(0);
				
				//quando for o ultimo registro pai
				//verifica quando filhos tem menos um
				if($qtdEtapas == $qtdFor ){
					$qtdAtividade = count($value)-1;
				}
	
				$fill = 1;
				$qtdSubFor = 0;
				foreach( $value as $rs ){
					$this->objPdf->SetFont('helvetica', '', 8);
					$this->objPdf->Cell($w[0], 6, "               {$rs}","RL{$borderBotton}",1,"L",$fill);
					$fill=!$fill;
					$qtdSubFor++;
					
					//caso entrou na contagem de filhos e essa quantidade for igual ao penultimo registro
					//irá setar a borta inforior para ser printada no ultimo registro.
					if(($qtdAtividade > 0)&&($qtdAtividade == $qtdSubFor)){
						$borderBotton = "B";
					}
				}
				$qtdFor++;
			}
			$this->objPdf->Ln(6);
			$this->objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
			//Close and output PDF document
			$this->objPdf->Output('relatorio_etapa_atividade.pdf', 'I');
		}else{
			
			$html = $this->objPdf->semRegistroParaConsulta();
			$this->objPdf->writeHTML($html,true, 0, true, 0);	
			$this->objPdf->Output('relatorio_etapa_atividade.pdf', 'I');
			
		}
	}
	
}