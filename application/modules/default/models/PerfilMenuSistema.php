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

class PerfilMenuSistema extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_PERFIL_MENU_SISTEMA;
	protected $_primary  = array('cd_perfil', 'cd_menu', 'st_perfil_menu');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	/**
     * 
     * @param int $cd_menu
     * @return Zend_Db_Table_RowSet
     */
	public function getPerfilMenuSistemaAssociadoAoMenu($cd_menu)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('pms'=>$this->_name),
                      array('tipo_perfil'=>new Zend_Db_Expr("CASE pms.st_perfil_menu WHEN 'P' THEN '".Base_Util::getTranslator('L_SQL_PROJETO')."'
                                                                                     WHEN 'D' THEN '".Base_Util::getTranslator('L_SQL_DEMANDA')."' END")),
                      $this->_schema);
        $select->join(array('per'=>KT_B_PERFIL),
                      '(pms.cd_perfil = per.cd_perfil)',
                      'tx_perfil',
                      $this->_schema);
        $select->where('cd_menu = ?', $cd_menu, Zend_Db::INT_TYPE);
        $select->order('per.tx_perfil');

		return $this->fetchAll($select);
	}
}
