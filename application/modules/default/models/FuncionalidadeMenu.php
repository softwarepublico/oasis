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

class FuncionalidadeMenu extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_FUNCIONALIDADE_MENU;
	protected $_primary  = array('cd_funcionalidade', 'cd_menu');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getListaFuncionalidadeMenu($cd_funcionalidade, $tipo)
	{
		$condicao = ($tipo === 0) ? "IS NULL" : "IS NOT NULL";

        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('m'=>KT_B_MENU),
                      array('cd_menu','tx_menu','tx_pagina','tx_modulo'),
                      $this->_schema);
        $select->joinLeft(array('fm'=>$this->select()
                                           ->setIntegrityCheck(false)
                                           ->from($this,'cd_menu')
                                           ->where('cd_funcionalidade = ?',$cd_funcionalidade,Zend_Db::INT_TYPE)),
                          '(m.cd_menu = fm.cd_menu)',
                          array());
        $select->where("fm.cd_menu {$condicao}");
        $select->order("tx_pagina");

        return $this->fetchAll($select)->toArray();
	}
}