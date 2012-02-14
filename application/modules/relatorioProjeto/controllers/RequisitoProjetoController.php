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

class RelatorioProjeto_RequisitoProjetoController extends Base_Controller_Action
{
	
	private $objRequisito;
	private $objProjeto;
	private $objBaseline;
	private $arrRequisito;
	private $arrProjeto;
	private $arrImpressao = array();
	private $objContrato;
	
	public function init()
	{
		parent::init();
		
		$this->objRequisito	= new Requisito($this->_request->getControllerName());
		$this->objProjeto	= new Projeto($this->_request->getControllerName());
		$this->objBaseline	= new BaselineItemControle($this->_request->getControllerName());
		$this->objContrato 	= new Contrato($this->_request->getControllerName());
	}	
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_REQUISITO'));

		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function requisitoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post = $this->_request->getPost();
		
		$cd_projeto		= $post['cd_projeto'];
		$tipo_relatorio	= (array_key_exists('tipo_relatorio',$post)) ? $post['tipo_relatorio'] : "F";
		
		switch($tipo_relatorio){
			//requisitos abertos e fechados
			case 'T':
				$this->arrRequisito = $this->objRequisito->getRequisito( $cd_projeto );
				break;
			//requisitos fechados
			case 'F':
				$this->arrRequisito = $this->objRequisito->getRequisito( $cd_projeto, true );
				break;
			//requisitos contidos na baseline
			case 'B':
				$this->arrRequisito = $this->objBaseline->getDadosBaselineItemControleRequisito( $cd_projeto );
				break;
		}
		
		$this->arrProjeto = $this->objProjeto->getDadosProjeto( $cd_projeto );
		
		foreach($this->arrRequisito as $rs){
			if($rs['st_tipo_requisito'] == 'Funcional'){
				$this->arrImpressao['funcional'][] = $rs;
			}else{
				$this->arrImpressao['nao_funcional'][] = $rs;
			}
		}
		$this->_geraRelatorio();
	}

	private function _geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();
		
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_REQUISITO'), K_CREATOR_SYSTEM.', '.
                                                                                  Base_Util::getTranslator('L_TIT_REL_REQUISITO').', '.
                                                                                  Base_Util::getTranslator('L_VIEW_REQUISITO'));
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		$w = array(180, 90, 22, 158);
		
		if(count($this->arrImpressao) > 0){
			$objPdf->SetFillColor(240,240,240);
			$objPdf->SetTextColor(0);
			
			$objPdf->SetFont('helvetica', '', 10);
			$objPdf->MultiCell(180,6, "<b>".Base_Util::getTranslator('L_VIEW_PROJETO').":</b> ".trim($this->arrProjeto[0]['tx_sigla_projeto'])."\n",'','J',0,1,'','',true,0,true);
			$objPdf->Ln(5);
			$objPdf->SetFont('helvetica', '', 8);

			$strTable = "";
			$strTable .= '<table cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" border="1">';
			$strTable .= '<tr>';
			$strTable .= '  <td bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" width="510px" style="text-align:left;" colspan="4"><b>'. $this->toUpper(Base_Util::getTranslator('L_VIEW_FUNCIONAL')) .'</b></td>';
			$strTable .= '</tr>';
	
			$qtdFor		= 1;
			foreach( $this->arrImpressao as $requisitos){
				//o segundo loop do foreach 1º foreach diz respeito aos requisitos não funcionais
				if($qtdFor == 2){
					$strTable .= '<tr>';
					$strTable .= '  <td bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'" width="510px" style="text-align:left;" colspan="4"><b>'. $this->toUpper(Base_Util::getTranslator('L_VIEW_NAO_FUNCIONAL')) .'</b></td>';
					$strTable .= '</tr>';
				}
				foreach( $requisitos as $requisito ){
					$strTable .= '<tr>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_REQUISITO').':</b></td>';
					$strTable .= '  <td width="440px" colspan="3">'.htmlspecialchars($requisito['tx_requisito']).'</td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_VERSAO').':</b></td>';
					$strTable .= '  <td width="185px">'.$requisito['ni_versao_requisito'].'</td>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_DATA_VERSAO').':</b></td>';
					$strTable .= '  <td width="185px">'.date('d/m/Y H:m:s', strtotime($requisito['dt_versao_requisito'])).'</td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_STATUS').':</b></td>';
					$strTable .= '  <td width="185px">'.$requisito['st_requisito'].'</td>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_PRIORIDADE').':</b></td>';
					$strTable .= '  <td width="185px">'.$requisito['st_prioridade_requisito'].'</td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_SITUACAO').':</b></td>';
					$strTable .= '  <td width="440px" colspan="3">'.$requisito['st_fechamento_requisito'].'</td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td width="70px" style="text-align:right;"><b>'.Base_Util::getTranslator('L_VIEW_DESCRICAO').':</b></td>';
					$strTable .= '  <td width="440px" style="text-align:justify;" colspan="3">'.htmlspecialchars($requisito['tx_descricao_requisito']).'</td>';
					$strTable .= '</tr>';
					$strTable .= '<tr>';
					$strTable .= '  <td width="510px" style="text-align:right;" colspan="4"><b>&nbsp;</b></td>';
					$strTable .= '</tr>';
				}
				$qtdFor++;
			}
			$strTable .= '</table>';
			$objPdf->writeHTML($strTable,true,0, true, 0);
		}else{
			$objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
		$objPdf->Output('relatorio_projeto_requisito.pdf', 'I');
	}
}
