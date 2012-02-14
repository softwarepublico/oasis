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

class SubItemMetrica extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_B_SUB_ITEM_METRICA;
	protected $_primary  = array('cd_sub_item_metrica', 'cd_definicao_metrica', 'cd_item_metrica');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getDadosSubItemMetrica( $cd_definicao_metrica=null, $cd_item_metrica=null )
    {
        $select = $this->select();

        if( !is_null($cd_definicao_metrica) ){
            $select->where("cd_definicao_metrica = ?", $cd_definicao_metrica, Zend_Db::INT_TYPE);
        }

        if( !is_null($cd_item_metrica) ){
            $select->where("cd_item_metrica = ?", $cd_item_metrica, Zend_Db::INT_TYPE);
        }

        $select->order("tx_sub_item_metrica");

        return $this->fetchAll($select)->toArray();
    }

    public function getComboSubItemMetrica( $comSelecione=false, $cd_definicao_metrica, $cd_item_metrica )
    {
        $arrRetorno = array();

        if( $comSelecione === true ){
            $arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        $select = $this->select()
                       ->where("cd_definicao_metrica = ?", $cd_definicao_metrica, Zend_Db::INT_TYPE)
                       ->where("cd_item_metrica      = ?", $cd_item_metrica, Zend_Db::INT_TYPE)
                       ->order("tx_sub_item_metrica");

        $arrSubItens = $this->fetchAll($select);

        foreach( $arrSubItens as $subItem ){
            $arrRetorno[$subItem->cd_sub_item_metrica] = $subItem->tx_sub_item_metrica . " (".$subItem->tx_variavel_sub_item_metrica.")";
        }

        return $arrRetorno;
    }

    public function getSubItemMetrica($cd_definicao_metrica, $cd_item_metrica=null, $comStInterno=true)
    {
        $select = $this->select()
                       ->where("cd_definicao_metrica = ?", $cd_definicao_metrica, Zend_Db::INT_TYPE)
                       ->order("ni_ordem_sub_item_metrica");

        if( !is_null($cd_item_metrica) ){
            $select->where("cd_item_metrica = ?", $cd_item_metrica, Zend_Db::INT_TYPE);
        }

        if($comStInterno===false ){
            $select->where("st_interno IS NULL");
        }

        return $this->fetchAll($select)->toArray();
    }
}