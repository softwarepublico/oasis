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

class PerfilMenuController extends Base_Controller_Action 
{
	private $objeto;	
	
	public function init()
	{
		parent::init();
		$this->objeto = new ObjetoContrato($this->_request->getControllerName());
	}
	
	public function indexAction(){}
	
	public function treeviewAction()
	{
		$this->_helper->layout->disableLayout();
		
		
		$cd_perfil = $this->_request->getParam ( 'cd_perfil' );
		$cd_objeto = $this->_request->getParam ( 'cd_objeto' );
		
		// Obtem os menus cadastrados
		$menu             = new Menu ( );
		$selectMenu       = $menu->select()->order("tx_pagina");
		$this->view->data = $menu->fetchAll ($selectMenu);
		
		$this->view->possuiPermissoesParaOPerfil   = 'S';
		
		if ($cd_perfil !== null && $cd_objeto !== null) {
			
			$perfilMenu   = new PerfilMenu ( );
			$perfisMenu   = $perfilMenu->fetchAll ( "cd_perfil = {$cd_perfil} and cd_objeto = {$cd_objeto}" )->toArray();
			$aMenusPerfil = array ();
			
			if (count($perfisMenu) == 0)
			{
				$arrObjeto          = $this->objeto->find($cd_objeto)->current()->toArray();
				$st_objeto_contrato = $arrObjeto["st_objeto_contrato"];
				
				$perfilMenuSistema  = new PerfilMenuSistema ( );
				$perfisMenusSistema = $perfilMenuSistema->fetchAll("cd_perfil = {$cd_perfil} and st_perfil_menu = '{$st_objeto_contrato}'")->toArray();
				
				if (count($perfisMenusSistema) == 0)
				{
					$this->view->possuiPermissoesParaOPerfil = 'N';
				}
				else
				{
					foreach ($perfisMenusSistema as $p) {
					   $aMenusPerfil[] = $p['cd_menu'];
					   $perfilMenu->insert(array('cd_objeto' => $cd_objeto, 'cd_perfil' => $cd_perfil, 'cd_menu' => $p ['cd_menu']));
					}
				}
			}
			else
			{
				foreach ( $perfisMenu as $pm ) {
					$aMenusPerfil [] = $pm ['cd_menu'];
				}
			}
			
			$this->view->menuPerfis = $aMenusPerfil;
		}
	}
	
	public function salvarAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$op 	   = $this->_request->getParam('op');
		$cd_perfil = $this->_request->getParam('cd_perfil');
		$cd_menu   = $this->_request->getParam('cd_menu');
		$cd_objeto = $this->_request->getParam('cd_objeto');

		$perfilMenu = new PerfilMenu($this->_request->getControllerName());
		
		switch ($op) {
			case 'I':
				$perfilMenu->insert(array('cd_perfil' => $cd_perfil, 'cd_menu' => $cd_menu, 'cd_objeto' => $cd_objeto));
			break;
			case 'E':
				$perfilMenu->delete("cd_perfil = {$cd_perfil} and cd_menu = {$cd_menu} and cd_objeto = {$cd_objeto}");
			break;
		}
	}
}