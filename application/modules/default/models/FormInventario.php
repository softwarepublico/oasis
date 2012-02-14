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

class FormInventario extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_FORM_INVENTARIO;
	protected $_primary  = array(
        'cd_inventario',
        'cd_item_inventario',
        'cd_item_inventariado',
        'cd_subitem_inventario'
    );
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     *
     * @param integer $cd_inventario
     * @param integer $cd_item_inventario
     * @param integer $cd_item_inventariado
     * @param integer $cd_subitem_inventario
     * @return Zend_Db_Table_Row
     */
	public function getFormInventario($cd_inventario, $cd_item_inventario,$cd_item_inventariado, $cd_subitem_inventario)
	{
        $select = $this->select()
                       ->where('cd_inventario         = ?', $cd_inventario, Zend_Db::INT_TYPE)
                       ->where('cd_item_inventario    = ?', $cd_item_inventario, Zend_Db::INT_TYPE)
                       ->where('cd_item_inventariado  = ?', $cd_item_inventariado, Zend_Db::INT_TYPE)
                       ->where('cd_subitem_inventario = ?', $cd_subitem_inventario, Zend_Db::INT_TYPE);

        return $this->fetchRow($select);
	}
	
	public function trataDadosGravacao(array $arrDados)
	{
        $return = true;
        foreach($arrDados as $item){
            $row = $this->fetchNew();
            $data = array(
                'cd_inventario'               =>$item['cd_inventario'],
                'cd_item_inventario'          =>$item['cd_item_inventario'],
                'cd_subitem_inventario'       =>$item['cd_subitem_inventario'],
                'cd_item_inventariado'        =>$item['cd_item_inventariado'],
                'cd_subitem_inv_descri'       =>$item['cd_subitem_inv_descri'],
                'tx_valor_subitem_inventario' =>$item['tx_valor_subitem_inventario']
            );

            $row->setFromArray($data);
            if( !$row->save() ){
                $return = false;
                break;
            }
        }
        return $return;
	}

	private function alteraFormInventario(array $arrDados, $where)
	{
        $arrUpdate['tx_valor_subitem_inventario'] = $arrDados['tx_valor_subitem_inventario'];

        if($this->update($arrUpdate, $where)){
            return true;
        } else {
            return false;
        }
	}

}