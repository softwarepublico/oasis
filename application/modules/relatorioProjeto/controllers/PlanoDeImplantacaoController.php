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

class RelatorioProjeto_PlanoDeImplantacaoController extends Base_Controller_Action
{
	
	private $objProjeto;
	private $objProposta;
	private $objPlanoImplantacao;
	private $objAgendaPlanoImplantacao;
	private $arrProjeto;
	private $cd_projeto;
	private $cd_proposta;
	private $dadosPlanoImplantacao;
	private $arrAgendaPlanoImplantacao;
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		
		$this->objProjeto		   		 = new Projeto($this->_request->getControllerName());
		$this->objProposta		 	  	 = new Proposta($this->_request->getControllerName());
		$this->objPlanoImplantacao 		 = new PlanoImplantacao($this->_request->getControllerName());
		$this->objAgendaPlanoImplantacao = new AgendaPlanoImplantacao($this->_request->getControllerName());
		$this->objContrato 		  		 = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto 		 = new ContratoProjeto($this->_request->getControllerName());
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PLANO_IMPLANTACAO'));
        
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
	
	public function planoDeImplantacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$this->cd_projeto  = (int) $this->_request->getParam("cd_projeto");
		$this->cd_proposta = (int) $this->_request->getParam("cd_proposta");
		
		$this->arrProjeto = $this->objProjeto->getDadosProjeto( $this->cd_projeto );

		$this->dadosPlanoImplantacao	 = $this->objPlanoImplantacao->getDadosPlanoImplantacao( $this->cd_projeto, $this->cd_proposta );
		$this->arrAgendaPlanoImplantacao = $this->objAgendaPlanoImplantacao->getAgendaImplantacao( $this->cd_projeto, $this->cd_proposta );

		$this->geraRelatorio();
	}

	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

		$objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PLANO_IMPLANTACAO'), K_CREATOR_SYSTEM.', '.
                                                                                          Base_Util::getTranslator('L_TIT_REL_PLANO_IMPLANTACAO').', '.
                                                                                          Base_Util::getTranslator('L_VIEW_IMPLANTACAO').', '.
                                                                                          Base_Util::getTranslator('L_VIEW_PLANO'));
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180,15,165);
		
		if(count($this->dadosPlanoImplantacao) > 0){
			

				$objPdf->SetFillColor(Base_Tcpdf_Pdf::R_FILL, Base_Tcpdf_Pdf::G_FILL, Base_Tcpdf_Pdf::B_FILL);
				$objPdf->SetTextColor(0);
		
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PROJETO').":",'LT',0,'R',1);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[2], 6, $this->arrProjeto[0]['tx_sigla_projeto'],'RT',1,'L',1);
				$objPdf->SetFont('helvetica', 'B', 8);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PROPOSTA').":",'LB',0,'R',1);
				$objPdf->SetFont('helvetica', '', 8);
				$objPdf->Cell($w[2], 6, Base_Util::getTranslator('L_VIEW_PROPOSTA_NR')." ".$this->cd_proposta,'RB',1,'L',1);
				$objPdf->Cell($w[0], 4, "", 'LR', 1, 'L', 0);
				
				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->MultiCell($w[0], 6, "<span style=\"text-decoration: underline;\">".Base_Util::getTranslator('L_VIEW_DESCRICAO_PLANO_IMPLANTACAO')."</span>",'LR','J',0,1,'','',true,0,true);
				$objPdf->SetFont('helvetica', '', 8);

				$objPdf->MultiCell($w[0], 6,"      ".$this->dadosPlanoImplantacao[0]['tx_descricao_plano_implantacao']."\n", 'LRB', 'J', 0, 1);

				$objPdf->SetFont('helvetica', 'B', 8);
				$objPdf->Cell($w[0], 4, "", 'LR', 1, 'L', 0);
				$objPdf->MultiCell($w[0], 6, "<span style=\"text-decoration: underline;\">".Base_Util::getTranslator('L_VIEW_AGENDA_PLANO_IMPLANTACAO')."</span>",'LR','J',0,1,'','',true,0,true);
				$objPdf->SetFont('helvetica', '', 8);
				
			if(count($this->arrAgendaPlanoImplantacao) > 0){

				$qtdFor			= 1;
				$margemBotton	= "";
				$qtdAgenda		= count($this->arrAgendaPlanoImplantacao)-1;
				
				foreach($this->arrAgendaPlanoImplantacao as $agenda ){
					
					if($qtdFor > 1){
						$objPdf->Cell($w[0], 4, "", 'LR', 1, 'L', 0);
					}
					if( $qtdAgenda == 0 ){
						$margemBotton = "B";
					}
					$objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell($w[0], 4, "      - ".date('d/m/Y', strtotime($agenda['dt_agenda_plano_implantacao'])), 'LR', 1, 'L', 0);
					$objPdf->SetFont('helvetica', '', 8);
					$objPdf->MultiCell($w[0], 6, "         ".$agenda['tx_agenda_plano_implantacao']."\n", "LR{$margemBotton}", 'J', 0, 1);
					
					if( $qtdAgenda == $qtdFor ){
						$margemBotton = "B";
					}
					
					$qtdFor++;
				}

				
			}else{
                $objPdf->Cell($w[0], 4, "         ".Base_Util::getTranslator('L_VIEW_SEM_REGISTRO'), 'LRB', 1, 'L', 0);
			}
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}

        $objPdf->Ln(6);
        $objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
        
        //Close and output PDF document
		$objPdf->Output('relatorio_plano_implatacao.pdf', 'I');
	}
}