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

class SubitemInventario extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_SUBITEM_INVENTARIO;
	protected $_primary  = array('cd_item_inventario', 'cd_subitem_inventario');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getDadosSubitemInventario( $cd_item_inventario )
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('si'=>$this->_name),
                      array('cd_subitem_inventario',
                            'tx_subitem_inventario',
                            'cd_item_inventario'),
                      $this->_schema);
        $select->join(array('ii'=>KT_B_ITEM_INVENTARIO),
                     '(si.cd_item_inventario = ii.cd_item_inventario)',
                     'tx_item_inventario',
                     $this->_schema);
        $select->where('si.cd_item_inventario = ?', $cd_item_inventario, Zend_Db::INT_TYPE);
        $select->order('tx_subitem_inventario');

		return $this->fetchAll($select)->toArray();
	}
    
     public function comboSubitemInventario($cd_item_inventario, $comSelecione=false)
    {
        $objSelect = $this->fetchAll("cd_item_inventario = {$cd_item_inventario}");

        $arrResult = array();

        if($comSelecione === true){
            $arrResult[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($objSelect as $value) {
            $arrResult[$value->cd_subitem_inventario] = $value->tx_subitem_inventario;
        }
        return $arrResult;
    }

}