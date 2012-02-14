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

class RelatorioProjeto_CasoDeUsoController extends Base_Controller_Action
{
	private $objContrato;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		$this->objContrato        = new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}
	
    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_CASO_DE_USO'));
        
        $cd_contrato = null;
        $comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }
    
    /**
     * Método para recupear os projetos associados ao contrato
     */
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
        
        if ($_POST['tipo_caso_de_uso'] =='C') {
            $this->_forward('generate1');   
        }
        
        $this->_helper->layout->disableLayout(true);
        $objRelatorioCasoDeUso = new RelatorioProjetoCasoDeUso();

    	$cd_projeto  = $this->_request->getParam('cd_projeto');

        if(array_key_exists('cd_modulo',$this->_request->getParams())){
			$cd_modulo = $this->_request->getParam('cd_modulo');
	        $params['cd_modulo'] = $cd_modulo;
        }
		
        if(array_key_exists('cd_proposta',$this->_request->getParams())){
			$cd_proposta  = $this->_request->getParam('cd_proposta');
	        $params['cd_proposta'] = $cd_proposta;
        }

        $params['cd_projeto'] = $cd_projeto;
        $arrResult = $objRelatorioCasoDeUso->getCasoDeUso($params);

        if(count($arrResult) > 0){
			$k = 0;
	        foreach( $arrResult as $chave => $valor ){
		        $params['cd_modulo']      = $valor['cd_modulo'];
		        $params['cd_caso_de_uso'] = $valor['cd_caso_de_uso'];
		        $arrInteracao[$k]         = $objRelatorioCasoDeUso->getInteracao($params);
		        
		        $params['st_complemento']  = "E";
			    $arrComplementoExcecao[$k] = $objRelatorioCasoDeUso->getComplemento($params);
			    $params['st_complemento'] ="R";
			    $arrComplementoRegra[$k]  = $objRelatorioCasoDeUso->getComplemento($params);
			    $params['st_complemento']   = "F";
			    $arrComplementoFluxoAlt[$k] = $objRelatorioCasoDeUso->getComplemento($params);
				$k++;
	        }
        } else {
        	$arrResult              = array();
        	$arrInteracao           = array();
        	$arrComplementoExcecao  = array();
        	$arrComplementoRegra    = array();
        	$arrComplementoFluxoAlt = array();
        }

        $this->view->arrResult              = $arrResult;
        $this->view->arrInteracao           = $arrInteracao;
        $this->view->arrComplementoExcecao  = $arrComplementoExcecao;
        $this->view->arrComplementoRegra    = $arrComplementoRegra;
        $this->view->arrComplementoFluxoAlt = $arrComplementoFluxoAlt;
    }
    
    public function generate1Action()
    {
        $this->_helper->layout->disableLayout(true);
        $objRelatorioCasoDeUso = new RelatorioProjetoCasoDeUso();

    	$cd_projeto  = $this->_request->getParam('cd_projeto');

        if(array_key_exists('cd_modulo',$this->_request->getParams())){
			$cd_modulo = $this->_request->getParam('cd_modulo');
	        $params['cd_modulo'] = $cd_modulo;
        }
		
        if(array_key_exists('cd_proposta',$this->_request->getParams())){
			$cd_proposta  = $this->_request->getParam('cd_proposta');
	        $params['cd_proposta'] = $cd_proposta;
        }

        $params['cd_projeto'] = $cd_projeto;
        $arrResult = $objRelatorioCasoDeUso->getCasoDeUso($params);

        if(count($arrResult) > 0){
			$k = 0;
	        foreach( $arrResult as $chave => $valor ){
		        $params['cd_modulo']      = $valor['cd_modulo'];
		        $params['cd_caso_de_uso'] = $valor['cd_caso_de_uso'];
		        $arrInteracao[$k]         = $objRelatorioCasoDeUso->getInteracao($params);
		        
		        $params['st_complemento']  = "E";
			    $arrComplementoExcecao[$k] = $objRelatorioCasoDeUso->getComplemento($params);
			    $params['st_complemento'] ="R";
			    $arrComplementoRegra[$k]  = $objRelatorioCasoDeUso->getComplemento($params);
			    $params['st_complemento']   = "F";
			    $arrComplementoFluxoAlt[$k] = $objRelatorioCasoDeUso->getComplemento($params);
				$k++;
	        }
        } else {
        	$arrResult              = array();
        	$arrInteracao           = array();
        	$arrComplementoExcecao  = array();
        	$arrComplementoRegra    = array();
        	$arrComplementoFluxoAlt = array();
        }

        $this->view->arrResult              = $arrResult;
        $this->view->arrInteracao           = $arrInteracao;
        $this->view->arrComplementoExcecao  = $arrComplementoExcecao;
        $this->view->arrComplementoRegra    = $arrComplementoRegra;
        $this->view->arrComplementoFluxoAlt = $arrComplementoFluxoAlt;
    }
}