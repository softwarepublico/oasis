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

class PerfilBoxInicioController extends Base_Controller_Action 
{
	private $objPerfilBoxInicio;
	
	public function init()
	{
		parent::init();
		$this->objPerfilBoxInicio = new PerfilBoxInicio($this->_request->getControllerName());
	}
	
	public function indexAction(){}
	
	public function salvarBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$tx_box_inicio         = $this->_request->getParam('tx_box_inicio');
		$tx_titulo_box_inicio  = $this->_request->getParam('tx_titulo_box_inicio');
		$st_tipo_box_inicio    = $this->_request->getParam('st_tipo_box_inicio');
		
		$return = $this->objBoxInicio->salvarBoxInicio($tx_box_inicio,$tx_titulo_box_inicio,$st_tipo_box_inicio);
		
		if($return){
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function alterarboxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_box_inicio         = $this->_request->getParam('cd_box_inicio');
		$tx_box_inicio         = $this->_request->getParam('tx_box_inicio');
		$tx_titulo_box_inicio  = $this->_request->getParam('tx_titulo_box_inicio');
		$st_tipo_box_inicio    = $this->_request->getParam('st_tipo_box_inicio');
		
		$return = $this->objBoxInicio->alterarBoxInicio($cd_box_inicio,$tx_box_inicio,$tx_titulo_box_inicio,$st_tipo_box_inicio);
		if($return){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}
	
	public function excluirBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$cd_box_inicio = $this->_request->getParam('cd_box_inicio');
		
		$return = $this->objBoxInicio->excluirBoxInicio($cd_box_inicio);
		if($return){
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function associaPerfilBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post		= $this->_request->getPost();
		
		$cd_perfil  = $post['cd_perfil'];
		$cd_objeto  = $post['cd_objeto'];
		$boxInicial = Zend_Json_Decoder::decode($post['boxInicial']);
		
		foreach ($boxInicial as $boxInicio) {
			$novo = $this->objPerfilBoxInicio->createRow();
			$novo->cd_perfil      	= $cd_perfil;
			$novo->cd_objeto      	= $cd_objeto;
			$novo->cd_box_inicio    = $boxInicio;
			$novo->save();
		}
	}
	
	public function desassociaPerfilBoxInicioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post		= $this->_request->getPost();

		$cd_perfil	= $post['cd_perfil'];
		$cd_objeto	= $post['cd_objeto'];
		$boxInicial = Zend_Json_Decoder::decode($post['boxInicial']);
		
		foreach ($boxInicial as $boxInicio) {
			$where = "cd_perfil={$cd_perfil} and cd_objeto={$cd_objeto} and cd_box_inicio={$boxInicio}";
			$this->objPerfilBoxInicio->delete($where);
		}
	}
	
	public function pesquisaBoxInicialAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_perfil = $this->_request->getParam('cd_perfil');
		$cd_objeto = $this->_request->getParam('cd_objeto');

		if($cd_perfil != -1){
			$boxInicio = new BoxInicio($this->_request->getControllerName());
			// Recordset de profissionais que nao se encontram no projeto selecionado
			$allBoxesInicio  = $boxInicio->getBoxesNaoAssociados($cd_perfil, $cd_objeto);
			// Recordset de profissionais que se encontram no projeto selecionado
			$boxesAssociados  = $boxInicio->getBoxesAssociados($cd_perfil, $cd_objeto);

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset.
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";
			foreach ($allBoxesInicio as $todos) {
				$arr1 .= "<option value=\"{$todos['cd_box_inicio']}\">{$todos['tx_titulo_box_inicio']}</option>";
			}

			$arr2 = "";
			foreach ($boxesAssociados as $associados) {
				$arr2 .= "<option value=\"{$associados['cd_box_inicio']}\">{$associados['tx_titulo_box_inicio']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		} else {
			$arr1 = "<option value=\"\"></option>";
			$arr2 = "<option value=\"\"></option>";
			$retornaOsDois = array($arr1, $arr2);
			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
}
