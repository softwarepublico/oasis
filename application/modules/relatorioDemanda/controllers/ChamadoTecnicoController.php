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

class RelatorioDemanda_ChamadoTecnicoController extends Base_Controller_Action
{
	private $arrChamadoTecnico;
	private $arrItemInventariado;
	private $objPdf;
	private $mesAno;
	private $objObjetoContrato;
	private $objSolicitacaoServiceDesk;

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CHAMADO_TECNICO'));
        
        $objObjetoContrato	   = new ObjetoContrato($this->_request->getControllerName());
		$this->view->arrObjeto = $objObjetoContrato->getObjetoContratoAtivo('D', true);
    }
    
    public function generateAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
       
        $objChamadoTecnico  		= new RelatorioDemandaChamadoTecnico();
		$objUtil			        = new Base_Controller_Action_Helper_Util();
        
        $arrParam['ni_solicitacao']		= $this->_request->getParam('ni_solicitacao');
        $arrParam['ni_ano_solicitacao']	= $this->_request->getParam('ni_ano_solicitacao');
        $arrParam['cd_objeto']      	= $this->_request->getParam('cd_objeto');

        
        $this->arrChamadoTecnico = $objChamadoTecnico->getDadosChamadoTecnico($arrParam);


        
        if (count($this->arrChamadoTecnico)>0) {
        
            if ($this->arrChamadoTecnico[0]['cd_item_inventariado']!= 999999) {

                $arrParam['cd_inventario']        = $this->arrChamadoTecnico[0]['cd_inventario'];
                $arrParam['cd_item_inventario']   = $this->arrChamadoTecnico[0]['cd_item_inventario'];
                $arrParam['cd_item_inventariado'] = $this->arrChamadoTecnico[0]['cd_item_inventariado'];

                $this->arrItemInventariado    = $objChamadoTecnico->getDadosItemInventariadoChamadoTecnico2($arrParam);
            }
        }

		$this->gerarRelatorio();
    }

	private function gerarRelatorio(){

		//criando o objeto
		$this->objPdf = new Base_Tcpdf_Pdf('P');

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_CHAMADO_TECNICO'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO'));

        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_CHAMADO_TECNICO'), $arrKeywords);

		$this->objPdf->SetDisplayMode("real");

		// set font
		$this->objPdf->SetFont('helvetica', 'B', 8);
		// add a page
		$this->objPdf->AddPage();

//		$this->objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_MES_ANO').": ".$this->mesAno, '', 0, 'L');
		$this->objPdf->Ln(2);

		if(count($this->arrChamadoTecnico) > 0){
			//Titulos da Coluna
			$header = array(
                            Base_Util::getTranslator('L_VIEW_DEMANDA').' ('.Base_Util::getTranslator('L_VIEW_SOLICITACAO').')',
                            Base_Util::getTranslator('L_VIEW_DATA_HORA'),
                            Base_Util::getTranslator('L_VIEW_ITEM_INVENTARIADO'),
                            Base_Util::getTranslator('L_VIEW_SOLICITANTE'),
                            Base_Util::getTranslator('L_VIEW_SALA'),
                            Base_Util::getTranslator('L_VIEW_TELEFONE'),
                            Base_Util::getTranslator('L_VIEW_SOLICITACAO'),
                            Base_Util::getTranslator('L_VIEW_TECNICO_ATENDIMENTO'),
                            Base_Util::getTranslator('L_VIEW_PROBLEMA_ENCONTRADO'),
                            Base_Util::getTranslator('L_VIEW_SOLUCAO_PROBLEMA'),
                            Base_Util::getTranslator('L_VIEW_DATA_INICIO'),
                            Base_Util::getTranslator('L_VIEW_DATA_FIM'),
                            Base_Util::getTranslator('L_VIEW_SITUACAO'),
                            Base_Util::getTranslator('L_VIEW_GRAU_SATISFACAO'),
                            Base_Util::getTranslator('L_VIEW_OTIMO'),
                            Base_Util::getTranslator('L_VIEW_BOM'),
                            Base_Util::getTranslator('L_VIEW_REGULAR'),
                            Base_Util::getTranslator('L_VIEW_RUIM'),
                            Base_Util::getTranslator('L_VIEW_OBSERVACAO_GERAL'),
                            Base_Util::getTranslator('L_VIEW_VISTO_TECNICO'),
                            Base_Util::getTranslator('L_VIEW_VISTO_SOLICITANTE')
                );
			// Imprime os dados no relatório de checkList da Parcela
			$this->montaChamadotecnico($header);
		} else {
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta('P'),true, 0, true, 0);
		}

		// reset pointer to the last page
		$this->objPdf->lastPage();
		//Close and output PDF document
		$this->objPdf->Output('relatorio_chamado_tecncio.pdf', 'I');
	}

	private function montaChamadotecnico($header){

		//$objChamadoTecnicoDetalhe = new ContratoDefinicaoMetrica();

		// Colors, line width and bold font
		$this->objPdf->SetDrawColor(50);
		$this->objPdf->SetFillColor(240,240,240);
		$this->objPdf->SetTextColor(0);
		$this->objPdf->SetLineWidth(0.1);
		$this->objPdf->SetFont('helvetica', 'B', 8);

        
		// Tamanho do Cabeçalho
        //          0  1  2  3  4   5  6  7  8  9
		$w = array(25,40,60,80,100,180,5,10,15,20);

    	$this->objPdf->Cell($w[2], 5, $header[0], 1, 0, 'C', 1);
    	$this->objPdf->Cell($w[2], 5, $header[1], 1, 0, 'C', 1);
    	$this->objPdf->Cell($w[2], 5, $header[2], 1, 0, 'C', 1);
        
        $this->objPdf->Ln();
		$this->objPdf->SetFont('helvetica', '', 8);
       
        $Chamado = $this->arrChamadoTecnico[0]['cd_demanda'].' ('.$this->arrChamadoTecnico[0]['ni_solicitacao'].'/'.$this->arrChamadoTecnico[0]['ni_ano_solicitacao'].')';
        $this->objPdf->Cell($w[2], 5, $Chamado, 1, 0, 'C', 0);
        $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['dt_solicitacao'], 1, 0, 'C', 0);
        
        if ($this->arrChamadoTecnico[0]['tx_item_inventariado']=='') {
            $this->objPdf->Cell($w[2], 5, Base_Util::getTranslator('L_VIEW_NAO_ESPECIFICADO'), 1, 0, 'C', 0);
        }else{
            $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['tx_item_inventariado'], 1, 0, 'C', 0);
        }

  //      $this->objPdf->Ln();
        $this->objPdf->Ln();
        
        $this->objPdf->SetFont('helvetica', 'B', 8);

        $this->objPdf->Cell($w[2], 5, $header[3], 1, 0, 'C', 1);
        $this->objPdf->Cell($w[2], 5, $header[4], 1, 0, 'C', 1);
    	$this->objPdf->Cell($w[2], 5, $header[5], 1, 0, 'C', 1);
        
        $this->objPdf->Ln();
    	
        $this->objPdf->SetFont('helvetica', '', 8);

        $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['tx_solicitante'], 1, 0, 'C', 0);
        $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['tx_sala_solicitante'], 1, 0, 'C', 0);
        $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['tx_telefone_solicitante'], 1, 0, 'C', 0);
        
    //    $this->objPdf->Ln();
        $this->objPdf->Ln();
        
        if (count($this->arrItemInventariado) > 0)  {
            $dados_item = '';
            foreach ($this->arrItemInventariado as $value) {
                $dados = array_keys($value);
                foreach ($dados as $key => $valor) {
                    $dados_item .= $valor.' = '.$value[$valor].'; ';
                }
            }
                $this->objPdf->SetFont('helvetica', 'B', 8);
                $this->objPdf->Cell($w[5], 5, Base_Util::getTranslator('L_VIEW_DADOS_ITEM_INVENTARIADO'), 1, 0, 'C', 1);
                $this->objPdf->Ln();
                $this->objPdf->SetFont('helvetica', '', 8);
                $this->objPdf->MultiCell($w[5], 5,$dados_item, 1, '', 0, 0);
        }else{
            if ($this->arrChamadoTecnico[0]['cd_item_inventariado']!=999999){
                
                $this->objPdf->SetFont('helvetica', 'B', 8);
                $this->objPdf->Cell($w[5], 5, Base_Util::getTranslator('L_VIEW_DADOS_ITEM_INVENTARIADO'), 1, 0, 'C', 1);
                $this->objPdf->Ln();
                $this->objPdf->SetFont('helvetica', '', 8);
                $this->objPdf->Cell($w[5], 5, Base_Util::getTranslator('L_VIEW_SEM_DADOS_ITEM_INVENTARIADO'), 1, 0, 'C', 0);
            }   
        }
            
        
     $this->objPdf->Ln();     
        
   		$this->objPdf->SetFont('helvetica', 'B', 8);

        $this->objPdf->Cell($w[5], 5, $header[7], 1, 0, 'C', 1);
        
        $this->objPdf->Ln();
        
        $this->objPdf->SetFont('helvetica', '', 8);

//        $this->objPdf->Cell($w[5], 5, $this->arrChamadoTecnico[0]['tx_profissionais'], 1, 0, 'C', 0);
        $this->objPdf->MultiCell($w[5], 5, $this->arrChamadoTecnico[0]['tx_profissionais'], 1, 'C', 0, 0);
     
        $this->objPdf->Ln();
        $this->objPdf->Ln();
        
        $this->objPdf->SetFont('helvetica', 'B', 8);

        
    	$this->objPdf->Cell($w[5], 5, $header[6], 1, 0, 'C', 1);

        $this->objPdf->Ln();
        
        $this->objPdf->SetFont('helvetica', '', 8);

        
        $this->objPdf->MultiCell($w[5], 30,$this->arrChamadoTecnico[0]['tx_solicitacao'], 1, '', 0, 0);
  
        $this->objPdf->Ln();
  
        $this->objPdf->SetFont('helvetica', 'B', 8);

                
        $this->objPdf->Cell($w[5], 5,  $header[8], 1, 0, 'C', 1);

        $this->objPdf->Ln();
        
        $this->objPdf->SetFont('helvetica', '', 8);
        
        
        $this->objPdf->MultiCell($w[5], 30, $this->arrChamadoTecnico[0]['tx_problema_encontrado'], 1, '', 0, 1);

        //$this->objPdf->Ln();

        $this->objPdf->SetFont('helvetica', 'B', 8);
        
        
        $this->objPdf->Cell(180, 5,  $header[9], 1, 0, 'C', 1);
                
        $this->objPdf->Ln();

        $this->objPdf->SetFont('helvetica', '', 8);

        
        $this->objPdf->MultiCell(180, 30, $this->arrChamadoTecnico[0]['tx_solucao_solicitacao'], 1, '', 0, 1);
                
        //$this->objPdf->Ln();

        $this->objPdf->SetFont('helvetica', 'B', 8);
        
      	$this->objPdf->Cell($w[2], 5, $header[10], 1, 0, 'C', 1);
      	$this->objPdf->Cell($w[2], 5, $header[11], 1, 0, 'C', 1);
      	$this->objPdf->Cell($w[2], 5, $header[12], 1, 0, 'C', 1);

        $this->objPdf->Ln();

        
        $this->objPdf->SetFont('helvetica', '', 8);

        
        $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['dt_demanda'], 1, 0, 'C', 0);
        $this->objPdf->Cell($w[2], 5, $this->arrChamadoTecnico[0]['dt_fechamento'], 1, 0, 'C', 0);
        
        if((!is_null($this->arrChamadoTecnico[0]['st_fechamento']))){
            if (!is_null($this->arrChamadoTecnico[0]['dt_grau_satisfacao'])) {
               $this->objPdf->Cell($w[2], 5,  Base_Util::getTranslator('L_VIEW_CONCLUIDA'), 1, 0, 'C', 0);
            }  else {
               $this->objPdf->Cell($w[2], 5,  Base_Util::getTranslator('L_VIEW_AGUARDANDO_FECHAR_SOLICITACAO'), 1, 0, 'C', 0);
            }
        }  else {
            if (!is_null($this->arrChamadoTecnico[0]['dt_leitura_solicitacao'])) {
               $this->objPdf->Cell($w[2], 5,  Base_Util::getTranslator('L_VIEW_AGUARDANDO_EXECUCAO'), 1, 0, 'C', 0);
            }  else {
               $this->objPdf->Cell($w[2], 5,  Base_Util::getTranslator('L_VIEW_AGUARDANDO_LEITURA'), 1, 0, 'C', 0);
                
            }
        }   
        
        $this->objPdf->Ln();
        $this->objPdf->Ln();
        
   		$this->objPdf->SetFont('helvetica', 'B', 8);
        
        $this->objPdf->Cell($w[1], 5, $header[13].':', 0, 0, 'L', 0);
        
   		$this->objPdf->SetFont('helvetica', '', 8);

        $this->objPdf->Cell($w[7], 5, "", 0, 0, 'C', 0);        
        if ($this->arrChamadoTecnico[0]['st_grau_satisfacao'] == 'O') {
            $this->objPdf->Cell($w[6], 5, "X", 1, 0, 'C', 0);        
        }else{
            $this->objPdf->Cell($w[6], 5, "", 1, 0, 'C', 0);        
        }
        $this->objPdf->Cell($w[6], 5, "", 0, 0, 'C', 0);        
        $this->objPdf->Cell($w[7], 5, $header[14], 0, 0, 'C', 0);        

        $this->objPdf->Cell($w[7], 5, "", 0, 0, 'C', 0);        
        if ($this->arrChamadoTecnico[0]['st_grau_satisfacao'] == 'B') {
            $this->objPdf->Cell($w[6], 5, "X", 1, 0, 'C', 0);        
        }else{
            $this->objPdf->Cell($w[6], 5, "", 1, 0, 'C', 0);        
        }
        $this->objPdf->Cell($w[6], 5, "", 0, 0, 'C', 0);        
        $this->objPdf->Cell($w[7], 5, $header[15], 0, 0, 'C', 0);        
        
        $this->objPdf->Cell($w[7], 5, "", 0, 0, 'C', 0);        
        if ($this->arrChamadoTecnico[0]['st_grau_satisfacao'] == 'R') {
            $this->objPdf->Cell($w[6], 5, "X", 1, 0, 'C', 0);        
        }else{
            $this->objPdf->Cell($w[6], 5, "", 1, 0, 'C', 0);        
        }
        $this->objPdf->Cell($w[6], 5, "", 0, 0, 'C', 0);        
        $this->objPdf->Cell($w[7], 5, $header[16], 0, 0, 'C', 0);        

        $this->objPdf->Cell($w[7], 5, "", 0, 0, 'C', 0);        
        if ($this->arrChamadoTecnico[0]['st_grau_satisfacao'] == 'U') {
            $this->objPdf->Cell($w[6], 5, "X", 1, 0, 'C', 0);        
        }else{
            $this->objPdf->Cell($w[6], 5, "", 1, 0, 'C', 0);        
        }
        $this->objPdf->Cell($w[6], 5, "", 0, 0, 'C', 0);        
        $this->objPdf->Cell($w[7], 5, $header[17], 0, 0, 'C', 0);        

//$this->objPdf->Cell($w[4], 5, $this->arrChamadoTecnico[0]['dt_demanda'], 1, 0, 'C', 0);
        
        $this->objPdf->Ln();
        $this->objPdf->Ln();

       	$this->objPdf->SetFont('helvetica', 'B', 8);
        
        $this->objPdf->Cell($w[5], 5,  $header[18], 1, 0, 'C', 1);

        $this->objPdf->Ln();
        
		$this->objPdf->SetFont('helvetica', '', 8);
       
        $this->objPdf->MultiCell($w[5], 25, $this->arrChamadoTecnico[0]['tx_obs_grau_satisfacao'], 1,  '', 0, 1);

        $this->objPdf->Cell($w[1], 10,  "" , 0, 0, 'C', 0);
        
        $this->objPdf->Ln();       
        

   		$this->objPdf->SetFont('helvetica', 'B', 8);
      
        $this->objPdf->Cell($w[2], 5,  $header[19], 'T', 0, 'C', 1);
        $this->objPdf->Cell($w[1], 5,  "" , 0, 0, 'C', 0);
        $this->objPdf->Cell($w[2], 5,  $header[20], 'T', 0, 'C', 1);
//		$this->objPdf->Cell(267, 5, "", 'T', 0);
	}
    
    
//    public function getSolicitacaoAutocompleteAction()
//    {
//        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout->disableLayout();
//        
//        $ni_ano_solicitacao	= $this->_request->getPost('ni_ano_solicitacao');
//        $cd_objeto      	= $this->_request->getPost('cd_objeto');
//        
//        //$x = Zend_Db_Table::getDefaultAdapter();
//        
//        $objSolicitacaoServiceDesk = new SolicitacaoServiceDesk($this->_request->getControllerName());
//        
//        $teste  = $objSolicitacaoServiceDesk->getSolicitacaoParaAutoComplete($cd_objeto, $ni_ano_solicitacao);
//  
//       // $teste = $x->fetchAll("Select ni_solicitacao, ni_ano_solicitacao from oasis.s_solicitacao where ni_ano_solicitacao = $ni_ano_solicitacao and cd_objeto = $cd_objeto and dt_leitura_solicitacao is not null");
//        
//        echo Zend_Json::encode($teste);
//    }
    public function getSolicitacaoAutocompleteAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $ni_ano_solicitacao	= $this->_request->getPost('ni_ano_solicitacao');
        $cd_objeto      	= $this->_request->getPost('cd_objeto');
        
        //$x = Zend_Db_Table::getDefaultAdapter();
        
        $objSolicitacaoServiceDesk = new SolicitacaoServiceDesk($this->_request->getControllerName());
        
        $rowset  = $objSolicitacaoServiceDesk->getSolicitacaoParaAutoComplete($cd_objeto, $ni_ano_solicitacao);
  
       // $teste = $x->fetchAll("Select ni_solicitacao, ni_ano_solicitacao from oasis.s_solicitacao where ni_ano_solicitacao = $ni_ano_solicitacao and cd_objeto = $cd_objeto and dt_leitura_solicitacao is not null");
        
        echo Zend_Json::encode($rowset->toArray());
    }
}