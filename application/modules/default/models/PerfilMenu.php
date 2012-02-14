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

class PerfilMenu extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_PERFIL_MENU;
	protected $_primary  = array('cd_perfil', 'cd_menu', 'cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Recupera os perfis assoicados a um determinado menu
     * 
     * @param int $cd_menu
     * @return Zend_Db_Table_RowSet
     */
	public function getPerfilAssociadoAoMenu( $cd_menu )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('per'=>KT_B_PERFIL),
                      'tx_perfil',
                      $this->_schema);
        $select->join(array('pm'=>$this->_name),
                      '(per.cd_perfil = pm.cd_perfil)',
                      array(),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(pm.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->where('pm.cd_menu = ?', $cd_menu, Zend_Db::INT_TYPE);
        $select->order('per.tx_perfil');

        return $this->fetchAll($select);
	}

    /**
     * Recupera os menus assoicados a um determinado perfil
     *
     * @param int $cd_perfil
     * @return Zend_Db_Table_RowSet
     */
	public function getMenuAssociadoAoPerfil( $cd_perfil )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct();
        $select->from(array('pm'=>$this->_name),
                      array(),
                      $this->_schema);
        $select->join(array('m'=>KT_B_MENU),
                      '(pm.cd_menu = m.cd_menu)',
                      'tx_menu',
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(pm.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->where('pm.cd_perfil = ?', $cd_perfil, Zend_Db::INT_TYPE);
        $select->order('m.tx_menu');

        return $this->fetchAll($select);
	}
}