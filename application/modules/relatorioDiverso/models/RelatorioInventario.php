<?php

/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 * 			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 * 			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 * 			  ou (na sua opnião) qualquer versão.
 * 			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 * 			  sem uma garantia implicita de ADEQUAÇÂO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 * 			  Veja a Licença Pública Geral GNU para maiores detalhes.
 * 			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * 			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 * 			  Fifth Floor, Boston, MA 02110-1301 USA.
 */
class RelatorioInventario extends Base_Db_Table_Abstract
{
    protected $_schema = K_SCHEMA;

    public function getDadosRelatorioInventario ( $cd_inventario )
    {
        $sql = $this->getDefaultAdapter()->select();

        $sql->from( array('i' => KT_S_INVENTARIO), array('cd_inventario', 'tx_inventario'), $this->_schema );

        $sql->joinleft( array('bii' => KT_B_ITEM_INVENTARIO), "i.cd_area_atuacao_ti = bii.cd_area_atuacao_ti", array("cd_item_inventario",
            "tx_item_inventario"), $this->_schema );
        $sql->joinleft( array('ii' => KT_A_ITEM_INVENTARIADO), "i.cd_inventario = ii.cd_inventario
                    and  bii.cd_item_inventario = ii.cd_item_inventario", array("cd_item_inventariado", "tx_item_inventariado"), $this->_schema );
        $sql->joinleft( array('sii' => KT_B_SUBITEM_INVENTARIO), "bii.cd_item_inventario = sii.cd_item_inventario", array("tx_subitem_inventario", "cd_subitem_inventario"), $this->_schema );
        $sql->where( "i.cd_inventario = ?", $cd_inventario, Zend_Db::INT_TYPE );
        $sql->where( "tx_item_inventariado is not null" );
        $sql->order( array("i.tx_inventario",
            "bii.tx_item_inventario",
            "ii.tx_item_inventariado",
            "sii.tx_subitem_inventario") );

        $arrReturn = array();
        foreach ($this->getDefaultAdapter()->fetchAll( $sql ) as $value) {
            $descri = self::getDescricoesSubitens( $value );
            $arrReturn[$value['tx_inventario']]
                      [$value['tx_item_inventario']]
                      [$value['tx_item_inventariado']]
                      [$value['tx_subitem_inventario']] = ($descri) ? $descri : NULL;
        }
        return $arrReturn;
    }

    private function getDescricoesSubitens ( array $params )
    {
        $sql = $this->getDefaultAdapter()->select();
        $sql->from( array('siid' => KT_B_SUBITEM_INV_DESCRI), 
                    array('cd_subitem_inv_descri','tx_subitem_inv_descri'), 
                    $this->_schema
                );
        $sql->joinLeft(
                array('fi'=>KT_A_FORM_INVENTARIO), 
                    'siid.cd_item_inventario    = fi.cd_item_inventario    and 
                     siid.cd_subitem_inventario = fi.cd_subitem_inventario and 
                     siid.cd_subitem_inv_descri = fi.cd_subitem_inv_descri', 
                    'tx_valor_subitem_inventario', 
                    $this->_schema
                );
        $sql->joinLeft(
                array('ii'=>KT_A_ITEM_INVENTARIADO), 
                    'fi.cd_item_inventariado = ii.cd_item_inventariado and 
                     fi.cd_inventario        = ii.cd_inventario        and 
                     fi.cd_item_inventario   = ii.cd_item_inventario', 
                    '', 
                    $this->_schema
                );
        $sql->where('fi.cd_inventario = ?',$params['cd_inventario'])
            ->where('fi.cd_item_inventariado = ?',$params['cd_item_inventariado'])
            ->where('siid.cd_item_inventario  = ?',$params['cd_item_inventario'])
            ->where('siid.cd_subitem_inventario = ?',$params['cd_subitem_inventario']);

        $aux = array();
        foreach ($this->getDefaultAdapter()->fetchAll( $sql ) as $key => $value) {
            $aux[$value['tx_subitem_inv_descri']] = $value['tx_valor_subitem_inventario'];
        }
        return $aux;
    }

//SELECT "i"."cd_inventario", "i"."tx_inventario", "bii"."cd_item_inventario", 
//     "bii"."tx_item_inventario", "ii"."cd_item_inventariado", 
//     "ii"."tx_item_inventariado", "sii"."tx_subitem_inventario", 
//     "sii"."cd_subitem_inventario","siid"."tx_subitem_inv_descri", "afi"."tx_valor_subitem_inventario"
//     
// FROM "oasis"."s_inventario" AS "i"
// LEFT JOIN "oasis"."b_item_inventario" AS "bii" 
//        ON i.cd_area_atuacao_ti = bii.cd_area_atuacao_ti
// LEFT JOIN "oasis"."a_item_inventariado" AS "ii" 
//        ON i.cd_inventario = ii.cd_inventario
//        and  bii.cd_item_inventario = ii.cd_item_inventario
// LEFT JOIN "oasis"."b_subitem_inventario" AS "sii" 
//        ON bii.cd_item_inventario = sii.cd_item_inventario
// LEFT JOIN "oasis"."b_subitem_inv_descri" AS "siid" 
//	ON  bii.cd_item_inventario = siid.cd_item_inventario
//        and sii.cd_subitem_inventario = siid.cd_subitem_inventario
// LEFT JOIN "oasis"."a_form_inventario" AS "afi" 
//        ON i.cd_inventario = afi.cd_inventario
//        and bii.cd_item_inventario = afi.cd_item_inventario
//        and ii.cd_item_inventariado = afi.cd_item_inventariado
//        and sii.cd_subitem_inventario = afi.cd_subitem_inventario
//        and siid.cd_subitem_inv_descri = afi.cd_subitem_inv_descri
//                     
// WHERE (i.cd_inventario = '1' and tx_item_inventariado is not null) 
// ORDER BY "i"."tx_inventario" ASC, "bii"."tx_item_inventario" ASC, 
//         "ii"."tx_item_inventariado" ASC, "sii"."tx_subitem_inventario" ASC

}