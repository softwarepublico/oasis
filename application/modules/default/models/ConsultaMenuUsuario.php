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

Class ConsultaMenuUsuario
{
    static public function getMenusPai($cd_profissional, $cd_objeto)
    {
        $objTable = new Menu();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);
        $select->from(array('menu'=>KT_B_MENU),
                      '*',
                      K_SCHEMA);
        $select->join(array('prof'=>KT_A_PROFISSIONAL_MENU),
                      '(menu.cd_menu = prof.cd_menu)',
                      array(),
                      K_SCHEMA);
        $select->where('menu.cd_menu_pai IS NULL')
               ->where('prof.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('prof.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
        $select->order('menu.tx_menu DESC');
        
        $rowset = $objTable->fetchAll($select);
        
        //traduz o menu
        foreach($rowset as $row)
            $row->tx_menu = Base_Util::getTranslator($row->tx_menu);

        return Base_Util::sortMultiArray($rowset,'tx_menu');
    }
    
    static public function getMenusFilhos($controller, $cd_profissional, $cd_objeto)
    {
        $objTable = new Menu();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);
        $select->from(array('menu'=>KT_B_MENU),
                      array(),
                      K_SCHEMA);
        $select->join(array('menu2'=>KT_B_MENU),
                      '(menu.cd_menu = menu2.cd_menu_pai)',
                      '*',
                      K_SCHEMA);
        $select->join(array('prof'=>KT_A_PROFISSIONAL_MENU),
                      '(menu2.cd_menu = prof.cd_menu)',
                      array(),
                      K_SCHEMA);
        $select->where('menu.tx_pagina = ?', $controller)
               ->where('prof.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('prof.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
        $select->order('menu.tx_menu');
        
        $rowset = $objTable->fetchAll($select);
        
        //traduz o menu
        foreach($rowset as $row)
            $row->tx_menu = Base_Util::getTranslator($row->tx_menu);
        
        return Base_Util::sortMultiArray($rowset,'tx_menu');
    }
    
    static public function getTituloJanela ($controller, $tx_modulo)
    {
        $objTableMenu = new Menu();

        $select = $objTableMenu->select()
                               ->setIntegrityCheck(false);
        $select->from($objTableMenu,'tx_menu');
        $select->where('tx_pagina = ?', $controller);

    	if($tx_modulo === 'default'){
            $select->where('tx_modulo IS NULL');
    	}else{
            $select->where('tx_modulo = ?', $tx_modulo);
    	}

        $row = $objTableMenu->fetchRow($select);

        $tx_menu = "";
        if(!is_null($row)){
        	$tx_menu = $row->tx_menu;
        }
        return Base_Util::getTranslator($tx_menu);
    }
}