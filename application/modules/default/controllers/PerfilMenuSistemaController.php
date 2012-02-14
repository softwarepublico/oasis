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

class PerfilMenuSistemaController extends Base_Controller_Action 
{

	public function indexAction() 
	{
		// Obtem os perfis
		$perfil = new Perfil ( );
		$this->view->perfis = $perfil->getPerfil ( true, true );
	}
	
	public function treeviewAction()
	{
		$this->_helper->layout->disableLayout();
		
		
		$cd_perfil      = $this->_request->getParam ( 'cd_perfil' );
		$st_perfil_menu = $this->_request->getParam ( 'st_perfil_menu' );
		
		// Obtem os menus cadastrados
		$menu             = new Menu ( );
		$selectMenu       = $menu->select()->order("tx_pagina");
		$this->view->data = $menu->fetchAll ($selectMenu);
		
		if ($cd_perfil !== null && $st_perfil_menu !== null) {
			$perfilMenuSistema = new PerfilMenuSistema($this->_request->getControllerName());

			$perfisMenus = $perfilMenuSistema->fetchAll("cd_perfil = {$cd_perfil} and st_perfil_menu = '{$st_perfil_menu}'")->toArray();
			
			$aMenusPerfil = array();
			foreach ($perfisMenus as $p) {
			   $aMenusPerfil[] = $p['cd_menu'];
			}
			
			$this->view->menuPerfis = $aMenusPerfil;
		}
	}
	
	public function salvarAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$op 	        = $this->_request->getParam('op');
		$cd_perfil      = $this->_request->getParam('cd_perfil');
		$cd_menu        = $this->_request->getParam('cd_menu');
		$st_perfil_menu = $this->_request->getParam('st_perfil_menu');
		
		$perfilMenuSistema = new PerfilMenuSistema($this->_request->getControllerName());
		
		switch ($op) {
			case 'I':
				$perfilMenuSistema->insert(array('cd_perfil' => $cd_perfil, 'cd_menu' => $cd_menu, 'st_perfil_menu' => $st_perfil_menu));
			break;
			case 'E':
				$perfilMenuSistema->delete("cd_perfil = {$cd_perfil} and cd_menu = {$cd_menu} and st_perfil_menu = '{$st_perfil_menu}'");
			break;
		}
	}
}