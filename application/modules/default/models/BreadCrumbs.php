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

class BreadCrumbs 
{
    private $menu;
    public $breadCrumbs = array();
    
    public function __construct(){
        $this->menu = new Menu();
    }
    
    public function getCdMenu($controller, $modulo)
    {
        $select = $this->menu
                       ->select()
                       ->where('tx_pagina = ?', $controller);

		if($modulo === 'default'){
			$select->where("tx_modulo IS NULL");
		} else {
			$select->where('tx_modulo = ?', $modulo);
		}
        
        $res = $this->menu->fetchRow($select);
        if (!is_null($res)) {
            return $res->cd_menu;
        } else {
            return 1;
        }
    }
        
    public function mountBreadCrumbs($cd_menu)
    {
        $select         = $this->menu
                               ->select()
                               ->where("cd_menu = ?", $cd_menu, Zend_Db::INT_TYPE);
        $resAtual       = $this->menu->fetchRow($select);

        $cdMenuPai      = trim($resAtual->cd_menu_pai);
        $breadCrumb     = Base_Util::getTranslator($resAtual->tx_menu);

        //alterado para compatibilizar com os controllers de módulo
        $modulo = (trim($resAtual->tx_modulo) != '' && (trim($resAtual->tx_modulo) != 'default')) ? $resAtual->tx_modulo."/": '';
        $linkBreadCrumb = $modulo.$resAtual->tx_pagina;
        
//        $linkBreadCrumb = $resAtual->tx_pagina;
        $this->breadCrumbs[$linkBreadCrumb] = $breadCrumb;

        if (!empty($cdMenuPai)) {
            $this->mountBreadCrumbs($cdMenuPai);
        }
    }
}