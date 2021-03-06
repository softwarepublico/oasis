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

class PedidoMudancaRegraDeNegocioController extends Base_Controller_Action
{
	private $objRegraNegocio;
	private $_objGerenciaMudanca;
	
	public function init()
	{
		parent::init();
		//Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));
		$this->_objGerenciaMudanca = new GerenciaMudanca($this->_request->getControllerName());
		$this->objRegraNegocio     = new RegraNegocio($this->_request->getControllerName());
	}

	public function indexAction(){$this->initView();}

	public function gridMudancaRegraNegocioAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$res			  = $this->objRegraNegocio->getRegraNegocioAtivaUltimaVersao($cd_projeto);
		$arrPedidoMudanca = $this->_objGerenciaMudanca->buscaPedidoMudancaEmAndamento($cd_projeto, ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO);

		if (count($arrPedidoMudanca)) {
			foreach ($arrPedidoMudanca as $pedido)
			{
				foreach ($res as $key => $value)
				{
					if ($value["cd_regra_negocio"] == $pedido["cd_item_controlado"]) {
						$res[$key]["possui_pedido"] = "S";
					} else {
						$res[$key]["possui_pedido"] = null;
					}
				}
			}
		}

		$this->view->res = $res;
	}
}
