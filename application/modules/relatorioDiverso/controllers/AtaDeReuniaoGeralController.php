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

class RelatorioDiverso_AtaDeReuniaoGeralController extends Base_Controller_Action
{
	private $objRelAtaReuniaoGeral;
	private $objObjeto;
	
	public function init()
	{
		parent::init();
		$this->objRelAtaReuniaoGeral = new RelatorioDiversoAtaDeReuniaoGeral();
		$this->objObjeto   		  = new ObjetoContrato($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ATA_REUNIAO_GERAL'));
        
       // $cd_objeto = null;
		//$comStatus	 = true;
		
		//if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_objetos']) ){
		//	$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];
		//	$comStatus	 = false;
		//}
		$arrObjeto2 = $this->objObjeto->getObjetoContrato(null,true,null);
        $this->view->arrObjeto2 = $arrObjeto2;
		$this->view->arrObjeto = $this->objObjeto->getObjetoContrato(null,true,null);
	}
	
	public function pesquisaObjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
//		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrObjetos = $this->objObjeto->listaObjeto($cd_objeto, true);
		
		$options = '';
		
		foreach( $arrObjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}
	
	public function montaAtaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto= $this->_request->getParam("cd_objeto");
		
		$arrAtaReuniaoGeral = $this->objRelAtaReuniaoGeral->getDadosAtaReuniaoGeral($cd_objeto, null);
		
		$strDataAta = "";
		if(count($arrAtaReuniaoGeral) > 0){
			foreach($arrAtaReuniaoGeral as $key=>$value){
				$strDataAta .= "<a href=\"#\" onclick=\"window.open('".SYSTEM_PATH."/relatorioDiverso/ata-de-reuniao-geral/generate/cd_objeto/{$value['cd_objeto']}/cd_reuniao_geral/{$value['cd_reuniao_geral']}'); \">{$value['dt_reuniao']}</a><br />";
			}
		} else {
			$strDataAta = Base_Util::getTranslator('L_MSG_ALERT_OBJETO_SEM_ATA_REUNIAO_GERAL');
		}
		
		echo $strDataAta;
	}
	
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = $this->_request->getParam('cd_objeto');
		$cd_reuniao_geral = $this->_request->getParam('cd_reuniao_geral');
		
		$arrAtaReuniaoGeral = $this->objRelAtaReuniaoGeral->getDadosAtaReuniaoGeral($cd_objeto, $cd_reuniao_geral);
		$this->view->arrAtaReuniaoGeral = $arrAtaReuniaoGeral[0];
	}
	
	public function generate2Action()
	{
		$this->_helper->layout->disableLayout();
	}

}