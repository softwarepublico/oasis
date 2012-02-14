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

class Menu extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_MENU;
	protected $_primary  = 'cd_menu';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getMenu($comSelecione = false)
	{
		$arrMenu = array();
		
		if ($comSelecione === true) {
			$arrMenu[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$select = $this->select()
                       ->order("tx_menu");
		
		$res = $this->fetchAll($select);
		
		foreach ($res as  $valor) {
			$arrMenu[$valor->cd_menu] = Base_Util::getTranslator($valor->tx_menu)." ({$valor->tx_pagina})";
		}
        
		return $arrMenu;
	}

	public function getMenuProfissional( $cd_profissional )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('m'=>$this->_name),
                      '*',
                      $this->_schema);
        $select->join(array('pm'=>KT_A_PROFISSIONAL_MENU),
                      '(m.cd_menu = pm.cd_menu)',
                      array(),
                      $this->_schema);
        $select->where('pm.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
        $select->order('tx_menu');

		return $this->fetchAll($select)->toArray();
	}

	public function recuperaMenu($cd_menu){
		return $this->find($cd_menu)->current()->toArray();
	}

	public function buscaMenu($tx_pagina)
	{
		$select = $this->select()
					   ->from($this, 'cd_menu')
					   ->where("tx_pagina = ?", $tx_pagina);
		return $this->fetchRow($select)->cd_menu;
	}
}