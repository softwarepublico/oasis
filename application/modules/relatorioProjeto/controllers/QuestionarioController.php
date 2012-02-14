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

class RelatorioProjeto_QuestionarioController extends Base_Controller_Action 
{
	private $objProjeto;
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		$this->objProjeto 		  = new Projeto($this->_request->getControllerName());
		$this->objUnidade 		  = new Unidade($this->_request->getControllerName());
		$this->objContrato 		  = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_QUESTIONARIO'));


		$cd_contrato		= null;
		$comStatus			= true;
		
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
	
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();
		
		$objUnidade        = new Unidade($this->_request->getControllerName());
		$objGrupoFator     = new GrupoFator($this->_request->getControllerName());
		$objGrupoFatorItem = new GrupoFatorItem($this->_request->getControllerName());
		
		$codProjeto  = $this->_request->getParam('cd_projeto');
		$codProposta = $this->_request->getParam('cd_proposta');
		$this->view->cd_proposta = $codProposta; 
		//Recupera os dados do projeto
		$arrProjeto = $this->objProjeto->getDadosProjeto($codProjeto);
		$arrProjeto = $arrProjeto[0];
		//Recupera a unidade do projeto
		$arrUnidade = $objUnidade->find($arrProjeto['cd_unidade'])->current();
		$arrProjeto['tx_sigla_unidade'] = $arrUnidade->tx_sigla_unidade;
		//Recupera os Dados do grupo Fator
		$arrGrupoFator = $objGrupoFator->getDadosGrupoFator();		
		//Recupera o item grupoFator
		$arrGrupoFatorItem = array();
		if(count($arrGrupoFator) > 0){
			foreach($arrGrupoFator as $key=>$value){
				$arrGrupoFatorItem[$value['cd_grupo_fator']] = $objGrupoFatorItem->getGrupoFatorItem($arrGrupoFator[$key]['cd_grupo_fator']);
			}
		}
		
		$this->view->arrProjeto        = $arrProjeto;
		$this->view->arrGrupoFator     = $arrGrupoFator;
		$this->view->arrGrupoFatorItem = $arrGrupoFatorItem;
	}

}