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

class ObjetoPerfilProfissionalController extends Base_Controller_Action {

	private $profissional;

	public function init()
	{
		parent::init();
		$this->profissional = new Profissional($this->_request->getControllerName());
	}
	
	public function indexAction() 
	{
		$objeto = new ObjetoContrato($this->_request->getControllerName());
		$this->view->contratos = $objeto->getObjetoContratoAtivo ( null, true, true );
	}
	
	public function treeviewAction() 
	{
		
		$this->_helper->layout->disableLayout ();
		
		$cd_objeto       = $this->_request->getParam ( 'cd_objeto' );
		$cd_profissional = $this->_request->getParam ( 'cd_profissional' );
		
		// Obtem os menus cadastrados
		$menu             = new Menu ();
		$selectMenu       = $menu->select()->order("tx_pagina");
		$this->view->data = $menu->fetchAll ($selectMenu);
		
		$this->view->possuiPermissoesParaOPerfil   = Base_Util::getTranslator('L_VIEW_SIM');
		
		if (($cd_objeto !== null) && ($cd_profissional !== null)) {
			
			$profissionalMenu  = new ProfissionalMenu($this->_request->getControllerName());
			$profissionaisMenu = $profissionalMenu->fetchAll ( "cd_profissional = {$cd_profissional} and cd_objeto = {$cd_objeto}" )->toArray ();
			$aMenusPerfil      = array ();
			
			if (count($profissionaisMenu) == 0)
			{
    			$perfilMenu = new PerfilMenu ( );
    			
    			$arrDadosProfissional = $this->profissional->find("cd_profissional");
    			
				//busca o perfil do profissional
				$selectPerfil = $this->profissional->select();
				$selectPerfil->from($this->profissional, array('cd_perfil'));
				$selectPerfil->where("cd_profissional = {$cd_profissional}");
				$arrPerfil    = $this->profissional->fetchRow($selectPerfil);
				$cd_perfil    = $arrPerfil->cd_perfil;
    			
    			$perfisMenus = $perfilMenu->fetchAll ( "cd_perfil = {$cd_perfil} and cd_objeto = {$cd_objeto}" )->toArray ();
    			
				if (count($perfisMenus) == 0) {
	    			$this->view->possuiPermissoesParaOPerfil = 'nao';
				}
				else
				{
	    			foreach ( $perfisMenus as $p ) {
	    				$aMenusPerfil [] = $p ['cd_menu'];
						$profissionalMenu->insert(array('cd_objeto' => $cd_objeto, 'cd_profissional' => $cd_profissional, 'cd_menu' => $p ['cd_menu']));	
	    			}
				}
    			
			}
			else
			{
				foreach ( $profissionaisMenu as $pr ) {
					$aMenusPerfil [] = $pr ['cd_menu'];
				}
				
			}
			
			$this->view->menuPerfis = $aMenusPerfil;
		}
	
	}
	
	public function salvarAction()
	{
	    $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$op 	         = $this->_request->getParam( 'op' );
		$cd_objeto       = $this->_request->getParam( 'cd_objeto' );
		$cd_profissional = $this->_request->getParam( 'cd_profissional' );
		$cd_menu         = $this->_request->getParam( 'cd_menu' );

		$profissionalMenu = new ProfissionalMenu($this->_request->getControllerName());
		
		switch ($op) {
			case 'I':		//TODO L_ falta comentario do que significa I e E
				$profissionalMenu->insert(array('cd_objeto' => $cd_objeto, 'cd_profissional' => $cd_profissional, 'cd_menu' => $cd_menu));
			    break;
			case 'E':
				$profissionalMenu->delete("cd_objeto = {$cd_objeto} and cd_profissional = {$cd_profissional} and cd_menu = {$cd_menu}");
			    break;
			default:
			    // somente para compor hierarquia da estrutura de controle
			    break;
		}
	}
}