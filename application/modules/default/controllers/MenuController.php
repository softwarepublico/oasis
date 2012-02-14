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

class MenuController extends Base_Controller_Action
{
	private $menu;

	public function init()
	{
		parent::init();
		$this->menu = new Menu($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_MENU'));
		$selectMenu       = $this->menu->select()->order("tx_pagina");
		$this->view->data = $this->menu->fetchAll($selectMenu);
		
		$form = new MenuForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			
			if(!empty($formData['cd_menu'])) {
				$novo          = $this->menu->fetchRow("cd_menu= {$formData['cd_menu']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo          = $this->menu->createRow();
				$novo->cd_menu = $this->menu->getNextValueOfField('cd_menu');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->cd_menu_pai   = ($formData['cd_menu_pai'] != "0")? $formData['cd_menu_pai']:null;
			$novo->tx_menu       = (!empty($formData['tx_menu']))   ? $formData['tx_menu']    :null;
			$novo->tx_modulo     = (!empty($formData['tx_modulo'])) ? $formData['tx_modulo']  :null;
			$novo->tx_pagina     = (!empty($formData['tx_pagina'])) ? $formData['tx_pagina']  :null;
			
			if($novo->save()){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
	public function recuperaMenuAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_menu = $this->_request->getParam('cd_menu');
	
		$res = $this->menu->recuperaMenu($cd_menu);
		
		echo Zend_Json::encode($res);
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$objMenuProf   = new ProfissionalMenu($this->_request->getControllerName());
		$objMenuPerfil = new PerfilMenu($this->_request->getControllerName());
		$objPerfilMenuSistema = new PerfilMenuSistema($this->_request->getControllerName());
		
		$comAssociacao = false;
		
		$cd_menu = (int)$this->_request->getParam('cd_menu', 0);
		
		//resultset de todos os profissionais associados ao menu a ser excluido
		$rowSetMenuProf   = $objMenuProf->getProfissionalAssociadoAoMenu($cd_menu);
		//resultset de todos os perfis associados ao menu a ser excluido
		$rowSetMenuPerfil = $objMenuPerfil->getPerfilAssociadoAoMenu($cd_menu);
		//resultset de todos os perfis associados aos menu a ser excluido
		$rowSetPerfilMenu = $objPerfilMenuSistema->getPerfilMenuSistemaAssociadoAoMenu($cd_menu);

		if($rowSetMenuProf->valid()){
			$comAssociacao = true;
			$msg = $this->montaMensagemProfissional($rowSetMenuProf);
		}else if($rowSetMenuPerfil->valid()){
			$comAssociacao = true;
			$msg = $this->montaMensagemPerfil($rowSetMenuPerfil);
		}else if($rowSetPerfilMenu->valid()){
			$comAssociacao = true;
			$msg = $this->montaMensagemPerfilMenu($rowSetPerfilMenu);
		}else{
			if ($this->menu->delete("cd_menu={$cd_menu}")) {
				$msg = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
			} else {
				$msg = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			}
		}
		echo Zend_Json::encode(array($comAssociacao, $msg));
	}

	private function montaMensagemProfissional($rowSetMenuProf)
	{
		$msg 	 = '';
		$qtdProf = count($rowSetMenuProf);
		$count   = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdProf <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_PROFISSIONAL',$qtdProf);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_PROFISSIONAL_10',$qtdProf);
		}
		
		foreach($rowSetMenuProf as $prof){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; <b>{$prof->tx_profissional}</b> &rarr; <b>{$prof->tx_objeto}</b><br/>";
			$count++;
		}
		$msg .= "</div>";
		
		return $msg;
	}

	private function montaMensagemPerfil($rowSetMenuPerfil)
	{
		$msg 	   = '';
		$qtdPerfil = count($rowSetMenuPerfil);
		$count     = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdPerfil <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_PERFIL',$qtdPerfil);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_PERFIL_10',$qtdPerfil);
		}
		
		foreach($rowSetMenuPerfil as $perfil){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; <b>{$perfil->tx_perfil}</b> &rarr; <b>{$perfil->tx_objeto}</b><br/>";
			$count++;
		}
		$msg .= "</div>";
		
		return $msg;
	}
	
	private function montaMensagemPerfilMenu($rowSetPerfilMenu)
	{
		$msg 	 = '';
		$qtdPerfil = count($rowSetPerfilMenu);
		$count   = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdPerfil <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_PERFIL',$qtdPerfil);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_PERFIL_10',$qtdPerfil);
		}
		
		foreach($rowSetPerfilMenu as $perfil){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; <b>{$perfil->tx_perfil}</b> &rarr; Tipo: <b>{$perfil->tipo_perfil}</b><br/>";
			$count++;
		}
		$msg .= "</div>";
		
		return $msg;
	}
	
	public function treeviewMenuAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$data       = $this->menu->fetchAll(null,"tx_pagina");
		
		$treeview   = new Base_View_Helper_TreeviewMenu();
		
		$treeview->treeviewMenu("red", $data, false, null, 'treeview-red', true);	
	}
}