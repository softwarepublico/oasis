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

class RelatorioProjeto_HistoricoProjetoController extends Base_Controller_Action
{
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
        //Incluindo a classe de outro modulo
        Zend_Loader::loadClass('RelatorioDiversoHistorico',Base_Util::baseUrlModule('relatorioDiverso', 'models'));

		$this->objContrato 		  = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}
	
    /**
     * Action da tela de inicial
     */
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_HISTORICO_GERAL_PROJETO'));

    	$this->view->objDataDiff = new Base_View_Helper_Datediff();
        $cd_contrato 			 = null;
        $comStatus				 = true;
		
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
    
    /**
     * Action de geração do relatório de Histórico Geral do Projeto
     */
    public function generateAction()
    {
		$this->_helper->layout->disableLayout();

		$objRelHistorico = new RelatorioDiversoHistorico();
    	$objProjeto      = new Projeto($this->_request->getControllerName());	
    	$objProfissional = new Profissional($this->_request->getControllerName());
    	
		$cd_projeto      = $this->_request->getParam('cd_projeto',0);
		$cd_proposta     = $this->_request->getParam('cd_proposta',0);
		$cd_profissional = $this->_request->getParam('cd_profissional',0);
		$dt_inicio       = $this->_request->getParam('dt_inicio',0);
		$dt_fim          = $this->_request->getParam('dt_fim',0);
		
		$cd_projeto      = ($cd_projeto  != 0    )? $cd_projeto     :null;
		$cd_proposta     = ($cd_proposta != 0    )? $cd_proposta    :null;
		$cd_profissional = ($cd_profissional != 0)? $cd_profissional:null;
		$dt_inicio       = ($dt_inicio != 0      )? Base_Util::converterDate($dt_inicio, "DD/MM/YYYY", "YYYY-MM-DD") : null;
		$dt_fim          = ($dt_fim != 0         )? Base_Util::converterDate($dt_fim, "DD/MM/YYYY", "YYYY-MM-DD")    : null;

		$this->view->arrHistorico = $objRelHistorico->getHistorico($cd_projeto,$cd_proposta,$cd_profissional,$dt_inicio,$dt_fim);
		$arrProjeto               = $objProjeto->getProjeto(false,$cd_projeto);
		if (!is_null($cd_profissional)) {
			$arrProfissional             = $objProfissional->getDadosProfissional($cd_profissional);
			$this->view->tx_profissional = $arrProfissional[0]['tx_profissional'];
		}
		
		$this->view->tx_projeto      = $arrProjeto[$cd_projeto];
		$this->view->cd_profissional = $cd_profissional;
		$this->view->dt_inicio       = $dt_inicio;      
		$this->view->dt_fim          = $dt_fim;         
    }
    
    public function getProfissionalProjetoAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
    	$objProfissionalProjeto = new ProfissionalProjeto($this->_request->getControllerName());
		
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$arrProfissionalProjeto = $objProfissionalProjeto->getProfissionalProjeto($cd_projeto,true);
		
		if(count($arrProfissionalProjeto)>0){
			$strOption = "";
			foreach($arrProfissionalProjeto as $key=>$value){
				$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
			}
		} else {
			$strOption .= "<option label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOption;
    }
    
    public function getPropostaProjetoAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$objProposta = new Proposta($this->_request->getControllerName());

		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$arrProposta = $objProposta->getProposta($cd_projeto);
		if(count($arrProposta)>0){
			$strOption = "";
			foreach($arrProposta as $key=>$value){
				$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
			}
		} else {
			$strOption .= "<option label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOption;
    }
}