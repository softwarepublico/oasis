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

class RelatorioProjeto_AtaDeReuniaoController extends Base_Controller_Action 
{
	private $objRelAtaReuniao;
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		$this->objRelAtaReuniao	  = new RelatorioProjetoAtaDeReuniao();
		$this->objContrato 		  = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ATA_REUNIAO'));
        
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
	
	public function montaAtaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam("cd_projeto");
		
		$arrAtaReuniao = $this->objRelAtaReuniao->getDadosAtaReuniao($cd_projeto, null);
		
		$strDataAta = "";
		if(count($arrAtaReuniao) > 0){
			foreach($arrAtaReuniao as $key=>$value){
				$strDataAta .= "<a href=\"#\" onclick=\"window.open('".SYSTEM_PATH."/relatorioProjeto/ata-de-reuniao/generate/cd_projeto/{$value['cd_projeto']}/cd_reuniao/{$value['cd_reuniao']}'); \">{$value['dt_reuniao']}</a><br />";
			}
		} else {
			$strDataAta = Base_Util::getTranslator('L_MSG_ALERT_PROJETO_SEM_ATA_REUNIAO');
		}
		
		echo $strDataAta;
	}
	
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_reuniao = $this->_request->getParam('cd_reuniao');
		
		$arrAtaReuniao = $this->objRelAtaReuniao->getDadosAtaReuniao($cd_projeto, $cd_reuniao);
		$this->view->arrAtaReuniao = $arrAtaReuniao[0];
	}
	
	public function generate2Action()
	{
		$this->_helper->layout->disableLayout();
	}

}