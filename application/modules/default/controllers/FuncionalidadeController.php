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

class FuncionalidadeController extends Base_Controller_Action
{
	
	private $funcionalidade;
	private $funcionalidadeMenu;
	
	public function init()
	{
		parent::init();
		$this->funcionalidade     = new Funcionalidade($this->_request->getControllerName());
		$this->funcionalidadeMenu = new FuncionalidadeMenu($this->_request->getControllerName());
        
        
	}
	
	public function indexAction()
	{
       
        
    }

    public function comboFuncionalidadeAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        
        $arrFuncionalidade = $this->funcionalidade->getFuncionalidade(true, true,true);
        $options = '';

        foreach($arrFuncionalidade as $key=>$value){
            $options .= '<option label="'.$value.'" value="'.$key.'">'.$value.'</option>';
        }
        echo $options;
    }

	public function salvarFuncionalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();

		if (empty($arrDados["cd_funcionalidade"])) {
			$return = $this->funcionalidade->salvaFuncionalidade($arrDados);
			$msg    = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
		else{
			$return = $this->funcionalidade->alterarFuncionalidade($arrDados);
			$msg    = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}

		echo $msg;
	}

	public function excluirFuncionalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_funcionalidade = (int)$this->_request->getParam('cd_funcionalidade');
				
		$return = $this->funcionalidade->excluirFuncionalidade($cd_funcionalidade);
			
		$msg    = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
		
	}

	public function recuperaFuncionalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_funcionalidade = $this->_request->getParam('cd_funcionalidade');

		$res = $this->funcionalidade->recuperaFuncionalidade($cd_funcionalidade);

		echo Zend_Json::encode($res);
	}
	
	public function gridFuncionalidadeAction()
	{
		$this->_helper->layout->disableLayout();
		
		$this->view->res = $this->funcionalidade->listaFuncionalidade();
	}

    public function pesquisaMenuAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_funcionalidade    = $this->_request->getParam('cd_funcionalidade');

		// Recordset de menus que nao se encontram na funcionalidade selecionada
		$foraFuncionalidade   = $this->funcionalidadeMenu->getListaFuncionalidadeMenu($cd_funcionalidade, 0);

		// Recordset de menus que se encontram na funcionalidade selecionada
		$dentroFuncionalidade = $this->funcionalidadeMenu->getListaFuncionalidadeMenu($cd_funcionalidade, 1);

		/*
		 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset.
		 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
		 */
		$arr1 = "";

		foreach ($foraFuncionalidade as $fora) {
			$controle = (!is_null($fora['tx_modulo'])) ? "{$fora['tx_modulo']}/{$fora['tx_pagina']}" : "{$fora['tx_pagina']}";
			$arr1 .= "<option value=\"{$fora['cd_menu']}\">{$controle} (".Base_Util::getTranslator($fora['tx_menu']).")</option>";
		}

		$arr2 = "";
		foreach ($dentroFuncionalidade as $dentro) {
			$controle = (!is_null($dentro['tx_modulo'])) ? "{$dentro['tx_modulo']}/{$dentro['tx_pagina']}" : "{$dentro['tx_pagina']}";
			$arr2 .= "<option value=\"{$dentro['cd_menu']}\">{$controle} (".Base_Util::getTranslator($dentro['tx_menu']).")</option>";
		}

		$retornaOsDois = array($arr1, $arr2);

		echo Zend_Json_Encoder::encode($retornaOsDois);
	}

	public function associaMenuFuncionalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_funcionalidade = $post['cd_funcionalidade'];
		$menus             = Zend_Json_Decoder::decode($post['menus']);

		$arrDados = array();

		foreach ($menus as $menu) {
			$novo = $this->funcionalidadeMenu->createRow();
			$novo->cd_funcionalidade = $cd_funcionalidade;
			$novo->cd_menu           = $menu;
			$novo->save();
		}

	}

	public function desassociaMenuFuncionalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_funcionalidade = $post['cd_funcionalidade'];
		$menus             = Zend_Json_Decoder::decode($post['menus']);

		$arrDados = array();

		foreach ($menus as $menu) {
			$where = "cd_funcionalidade = {$cd_funcionalidade} and cd_menu = {$menu}";
			$this->funcionalidadeMenu->delete($where);
		}

	}
}