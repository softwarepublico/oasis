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

class PedidoMudancaCasoDeUsoController extends Base_Controller_Action
{

	private $objModulo;
	private $objCasoDeUso;
	private $_objGerenciaMudanca;
	
	public function init()
	{
		parent::init();
		//Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));
		$this->_objGerenciaMudanca = new GerenciaMudanca($this->_request->getControllerName());
		$this->objModulo	       = new Modulo($this->_request->getControllerName());
		$this->objCasoDeUso        = new CasoDeUso($this->_request->getControllerName());
		
	}

	public function indexAction(){$this->initView();}

	public function montaComboModuloAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$res = $this->objModulo->getModulo($cd_projeto);
		
		$option = "<option value='0'>".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		if($res){
			foreach($res as $key=>$value){
				$option .= "<option value='{$key}'>{$value}</option>";
			}
		}
		echo $option;
	}
	
	public function gridMudancaCasoDeUsoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_modulo	= $this->_request->getParam('cd_modulo');
		
		$res			  = $this->objCasoDeUso->pesquisaCasoDeUsoProjeto( $cd_projeto, $cd_modulo );
		$arrPedidoMudanca = $this->_objGerenciaMudanca->buscaPedidoMudancaEmAndamento($cd_projeto, ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_CASO_USO);

		if (count($arrPedidoMudanca)) {
			foreach ($arrPedidoMudanca as $pedido)
			{
				foreach ($res as $key => $value)
				{
					if ($value["cd_caso_de_uso"] == $pedido["cd_item_controlado"]) {
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
