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

class RelatorioProjeto_DocumentoAceiteController extends Base_Controller_Action
{
	private $objContratoProjeto;

	public function init()
	{
		parent::init();
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ACEITE_PARCELA'));
        
        $objContrato 		= new Contrato($this->_request->getControllerName());
        $cd_contrato 		= null;
        $comStatus			= true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
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
    	$objDocumentoAceite = new RelatorioProjetoDocumentoAceite();
    	$objUtil            = new Base_Controller_Action_Helper_Util();

    	$arrParam['cd_projeto']  		  = $this->_request->getParam('cd_projeto');
    	$arrParam['cd_proposta'] 		  = $this->_request->getParam('cd_proposta');
    	$arrParam['cd_parcela']  		  = $this->_request->getParam('cd_parcela');
    	$tx_gestor_substituto             = $this->_request->getPost('tx_gestor_substituto',0);
    	$this->view->tx_gestor_substituto = $tx_gestor_substituto;
    	
    	//Projeto
    	$arrDadosProjeto             = $objDocumentoAceite->getDadosProjeto($arrParam['cd_projeto']);
    	$this->view->arrDadosProjeto = $arrDadosProjeto[0];
		//Parcela(s)
    	$arrDadosParcela               = $objDocumentoAceite->getDadosParcela($arrParam);
    	$arrDadosParcela[0]['data']    = $objUtil->getMesRes($arrDadosParcela[0]['ni_mes_previsao_parcela'])."/".$arrDadosParcela[0]['ni_ano_previsao_parcela'];
    	$this->view->arrDadosParcela   = $arrDadosParcela;
    	// Produtos da Parcela
    	$arrProdutoParcela = $objDocumentoAceite->getDadosProdutoParcela($arrParam);
    	$this->view->arrProdutoParcela = $arrProdutoParcela; 
    }
}