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

class DefinicaoProcessoController extends Base_Controller_Action
{
	
	private $definicaoProcesso;
	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_DEFINICAO_PROCESSOS'));
		$this->definicaoProcesso  = new DefinicaoProcesso($this->_request->getControllerName());
	}
	
	public function indexAction()
	{}
	
	public function pesquisaFuncionalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$st_definicao_processo        = $this->_request->getParam('st_definicao_processo');
		$cd_perfil_definicao_processo = $this->_request->getParam('cd_perfil_definicao_processo');

		// Recordset de menus que nao se encontram na funcionalidade selecionada
		$foraDefinicaoProcesso   = $this->definicaoProcesso->getListaFuncionalidadeDefinicaoProcesso($st_definicao_processo, $cd_perfil_definicao_processo, 0);



		// Recordset de menus que se encontram na funcionalidade selecionada
		$dentroDefinicaoProcesso = $this->definicaoProcesso->getListaFuncionalidadeDefinicaoProcesso($st_definicao_processo, $cd_perfil_definicao_processo, 1);

		/*
		 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset.
		 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
		 */
		$arr1 = "";

		foreach ($foraDefinicaoProcesso as $fora) {
			$arr1 .= "<option value=\"{$fora['cd_funcionalidade']}\">{$fora['tx_funcionalidade']}</option>";
		}

		$arr2 = "";
		foreach ($dentroDefinicaoProcesso as $dentro) {
			$arr2 .= "<option value=\"{$dentro['cd_funcionalidade']}\">{$dentro['tx_funcionalidade']}</option>";
		}

		$retornaOsDois = array($arr1, $arr2);

		echo Zend_Json_Encoder::encode($retornaOsDois);
	}

	public function associaFuncionalidadeDefinicaoProcessoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post                  = $this->_request->getPost();
		$st_definicao_processo = $post['st_definicao_processo'];
		$cd_perfil             = $post['cd_perfil'];
		$funcionalidades       = Zend_Json_Decoder::decode($post['funcionalidades']);

		$arrDados = array();

		foreach ($funcionalidades as $funcionalidade) {
			$novo = $this->definicaoProcesso->createRow();
			$novo->st_definicao_processo = $st_definicao_processo;
			$novo->cd_perfil             = $cd_perfil;
			$novo->cd_funcionalidade     = $funcionalidade;
			$novo->save();
		}
	}

	public function desassociaFuncionalidadeDefinicaoProcessoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$st_definicao_processo = $post['st_definicao_processo'];
		$cd_perfil             = $post['cd_perfil'];

		$funcionalidades       = Zend_Json_Decoder::decode($post['funcionalidades']);

		$arrDados = array();

		foreach ($funcionalidades as $funcionalidade) {
			$where = "cd_funcionalidade = {$funcionalidade} and st_definicao_processo = '{$st_definicao_processo}' and cd_perfil = {$cd_perfil}";
			$this->definicaoProcesso->delete($where);
		}
	}
}