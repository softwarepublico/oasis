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

class MatrizRastreabilidadeController extends Base_Controller_Action
{
	private $objAnaliseMatrizRastreabilidade;
	private $objContratoProjeto;
	
	public function init()
	{
		parent::init();
		$this->objAnaliseMatrizRastreabilidade	= new AnaliseMatrizRastreabilidade($this->_request->getControllerName());
		$this->objContratoProjeto 				= new ContratoProjeto($this->_request->getControllerName());
	}

	public function indexAction(){ $this->initView(); }

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
	
    /*
     * Método para verificar se existe alguma analise em aberto
     */
    public function getAnaliseMatrizRastreabilidadeAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$comDados							= false;
		$arrDados							= array();
		$cd_projeto							= (int) $this->_request->getParam('cd_projeto', 0);
		$st_analise_matriz_rastreabilidade	= 		$this->_request->getParam('st_analise_matriz_rastreabilidade');

		$arrDadosAnalise					= $this->objAnaliseMatrizRastreabilidade->getVersaoAbertaAnaliseMatrizRastreabilidade( $cd_projeto, "{$st_analise_matriz_rastreabilidade}" );
		
		if($arrDadosAnalise){
			$comDados = true;
			$arrDados = $arrDadosAnalise[0];
		}
		echo Zend_Json::encode(array($comDados,$arrDados));
    }
}