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

class RelatorioProjeto_RegraDeNegocioController extends Base_Controller_Action
{
	
	private $objRegraDeNegocio;
	private $objProjeto;
	private $objBaseline;
	private $arrRegraDeNegocio;
	private $arrProjeto;
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		
		$this->objRegraDeNegocio	= new RegraNegocio($this->_request->getControllerName());
		$this->objProjeto			= new Projeto($this->_request->getControllerName());
		$this->objBaseline			= new BaselineItemControle($this->_request->getControllerName());
		$this->objContrato 		    = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto   = new ContratoProjeto($this->_request->getControllerName());
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_REGRA_NEGOCIO'));
        
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
	
	public function regraDeNegocioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post = $this->_request->getPost();

		$cd_projeto		= $post['cd_projeto'];
		$tipo_relatorio	= (array_key_exists('tipo_relatorio',$post)) ? $post['tipo_relatorio'] : "F";


		switch($tipo_relatorio){

			//requisitos abertos e fechados
			case 'T':
				$this->arrRegraDeNegocio = $this->objRegraDeNegocio->getRegraNegocioWithLastVersion( $cd_projeto, false, false, "A" );
				break;
			//requisitos fechados
			case 'F':
					$this->arrRegraDeNegocio = $this->objRegraDeNegocio->getRegraNegocioWithLastVersion( $cd_projeto, false, 'S', "A" );
				break;
			//requisitos contidos na baseline
			case 'B':
				$this->arrRegraDeNegocio = $this->objBaseline->getDadosBaselineItemControleRegraDeNegocio( $cd_projeto );
				break;
		}
		
		$this->arrProjeto = $this->objProjeto->getDadosProjeto( $cd_projeto );
		
		$this->geraRelatorio();
	}

	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();


        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_REGRA_NEGOCIO'), K_CREATOR_SYSTEM.', '.
                                                                                      Base_Util::getTranslator('L_TIT_REL_REGRA_NEGOCIO').', '.
                                                                                      Base_Util::getTranslator('L_VIEW_REGRA_DE_NEGOCIO'));
		
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180, 90, 22, 158);
		
		if(count($this->arrRegraDeNegocio) > 0){
			
			$objPdf->SetFillColor(240,240,240);
			$objPdf->SetTextColor(0);
			
			$objPdf->SetFont('helvetica', '', 10);
			$objPdf->MultiCell(180,6, "<b>". Base_Util::getTranslator('L_VIEW_PROJETO') ."Projeto:</b> ".trim($this->arrProjeto[0]['tx_sigla_projeto'])."\n",'','J',0,1,'','',true,0,true);
			$objPdf->Ln(5);
			$objPdf->SetFont('helvetica', '', 8);
		
			$strTable  = "";
			foreach( $this->arrRegraDeNegocio as $requisito){
				
				$tx_descricao = strip_tags($requisito['tx_descricao_regra_negocio'], "<br><span><ol><li><ul>");
				$tx_descricao = str_replace("<br>"," ", $tx_descricao);
				
				$strTable .= '<table cellpadding="4" cellspacing="0" bordercolor="#CCCCCC" border="1">';
				$strTable .= '<tr>';
				$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_REGRA').':</b></td>';
				$strTable .= '  <td width="440px" colspan="3">'.$requisito['tx_regra_negocio'].'</td>';
				$strTable .= '</tr>';
				$strTable .= '<tr>';
				$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_VERSAO').':</b></td>';
				$strTable .= '  <td width="185px">'.$requisito['ni_versao_regra_negocio'].'</td>';
				$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_DATA_VERSAO').':</b></td>';
				$strTable .= '  <td width="185px">'.date('d/m/Y H:i:s', strtotime($requisito['dt_regra_negocio'])).'</td>';
				$strTable .= '</tr>';
				$strTable .= '<tr>';
				$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_SITUACAO').':</b></td>';
				$strTable .= '  <td width="440px" colspan="3">'.$requisito['st_fechamento_regra_negocio_desc'].'</td>';
				$strTable .= '</tr>';
				$strTable .= '<tr>';
				$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_DESCRICAO').':</b></td>';
				$strTable .= '  <td width="440px" style="text-align:justify;" colspan="3">'.$tx_descricao.'</td>';
				$strTable .= '</tr>';
				$strTable .= '</table>';
				$strTable .= '<br>';
			}
			$objPdf->writeHTML($strTable,true, 0, true, 0);
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		$objPdf->Output('relatorio_projeto_requisito.pdf', 'I');
	}
}
