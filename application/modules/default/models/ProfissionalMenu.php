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

class ProfissionalMenu extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_PROFISSIONAL_MENU;
	protected $_primary  = array('cd_menu','cd_profissional','cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Recuperas as permissões de um profissional em um determinado objeto
     *
     * @param int $cd_profissional
     * @param int $cd_objeto
     * @return array
     */
	public function getPermissoesProfissional($cd_profissional, $cd_objeto)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(KT_B_MENU,
                      array('tx_modulo','tx_pagina'),
                      $this->_schema);
        $select->where('cd_menu IN ?', $this->select()
                                            ->from($this, 'cd_menu')
                                            ->where('cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE)
                                            ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE));

        $rowSet = $this->fetchAll($select);

	    foreach ($rowSet as $row) {
	    	if(!is_null($row->tx_modulo)){
		        $arrPermissoes[] = strtolower($row->tx_modulo)."_".$row->tx_pagina;
	    	} else {
		        $arrPermissoes[] = $row->tx_pagina;
	    	}
	    }
	    return $arrPermissoes;
	}

    /**
     * Recupera os profissionais e o objeto associados a um determinado menu
     * 
     * @param int $cd_menu
     * @return Zend_Db_Table_RowSet
     */
	public function getProfissionalAssociadoAoMenu( $cd_menu )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct();
        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      'tx_profissional',
                      $this->_schema);
        $select->join(array('pm'=>$this->_name),
                      '(prof.cd_profissional = pm.cd_profissional)',
                      array(),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(pm.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->where('pm.cd_menu = ?', $cd_menu, Zend_Db::INT_TYPE);
        $select->order('prof.tx_profissional');

        return $this->fetchAll($select);
	}
	
}