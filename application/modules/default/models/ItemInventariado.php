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

class ItemInventariado extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_ITEM_INVENTARIADO;
	protected $_primary  = array(
        'cd_item_inventariado',
        'cd_inventario',
        'cd_item_inventario'
    );
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     *
     * @param integer $cd_item_inventariado
     * @param integer $cd_inventario
     * @param integer $cd_item_inventario
     * @return Zend_Db_Table_Row
     */
	public function getItemInventariado($cd_item_inventariado=null, $cd_inventario=null, $cd_item_inventario=null)
	{
        $select = $this->select()
               ->where('cd_item_inventariado  = ?', $cd_item_inventariado, Zend_Db::INT_TYPE);
        if (!is_null($cd_inventario))
            $select->where('cd_inventario         = ?', $cd_inventario, Zend_Db::INT_TYPE);
        if (!is_null($cd_item_inventario))
            $select->where('cd_item_inventario    = ?', $cd_item_inventario, Zend_Db::INT_TYPE);
        
        return $this->fetchRow($select);

	}
	public function getItensInventariado( $cd_inventario, $cd_item_inventario)
	{
        $select = $this->select()
                       ->where('cd_inventario         = ?', $cd_inventario, Zend_Db::INT_TYPE)
                       ->where('cd_item_inventario    = ?', $cd_item_inventario, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();

	}

    public function getItensInventariadoDados( $cd_inventario, $cd_item_inventario, $cd_item_inventariado,$cd_subitem_inventario)
	{

        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('sii'=>KT_B_SUBITEM_INV_DESCRI),
                      array('cd_subitem_inv_descri','tx_subitem_inv_descri'),
                      $this->_schema);
        $select->joinleft(array('fi'=>KT_A_FORM_INVENTARIO),
                      "(fi.cd_item_inventario = sii.cd_item_inventario
                        and fi.cd_subitem_inv_descri = sii.cd_subitem_inv_descri
                        and fi.cd_subitem_inventario = $cd_subitem_inventario
                        and fi.cd_inventario = $cd_inventario
                        and fi.cd_item_inventariado = $cd_item_inventariado )",
                      array('tx_valor_subitem_inventario'),
                      $this->_schema);
        $select->where('sii.cd_item_inventario = ?', $cd_item_inventario, Zend_Db::INT_TYPE)
               ->where('sii.cd_subitem_inventario = ?', $cd_subitem_inventario, Zend_Db::INT_TYPE);
        
        
//        $select = '   select sii.cd_subitem_inventario, sii.tx_subitem_inventario,
//            fi.tx_valor_subitem_inventario
//            from oasis.b_subitem_inventario sii
//            left join  oasis.a_form_inventario fi
//              on fi.cd_item_inventario = sii.cd_item_inventario
//            and fi.cd_subitem_inventario = sii.cd_subitem_inventario
//            and fi.cd_inventario = 1
//            and fi.cd_item_inventariado = 1
//            where
//            sii.cd_item_inventario = 2 ';


        return $this->fetchAll($select)->toArray();


	}


	public function trataDadosGravacao(array $arrDados)
	{

		$return = true;
		foreach($arrDados as $key=>$value){
			$where = "cd_item_inventariado  = {$value['cd_item_inventariado']} and
			          cd_inventario         = {$value['cd_inventario']} and
			          cd_item_inventario    = {$value['cd_item_inventario']}";
			$arrItemInventariado = $this->fetchAll($where)->toArray();
			if(count($arrItemInventariado) > 0 ){
				$return = $this->alteraItemInventariado($value, $where);
			} else {
				$return = $this->salvaItemInventariado($value);
			}

			if(!$return){
				break;
			}
		}
		return $return;
	}

	private function salvaItemInventariado(array $arrDados)
    {

		$novo                        = $this->createRow();
		$novo->cd_item_inventariado  = $arrDados['cd_item_inventariado'];
		$novo->cd_inventario         = $arrDados['cd_inventario'];
		$novo->cd_item_inventario    = $arrDados['cd_item_inventario'];
		$novo->tx_item_inventariado  = $arrDados['tx_item_inventariado'];
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}

	private function alteraItemInventariado(array $arrDados, $where)
	{
        $arrUpdate['tx_item_inventariado'] = $arrDados['tx_item_inventariado'];

        if($this->update($arrUpdate, $where)){
            return true;
        } else {
            return false;
        }
	}

    public function comboItemInventariado($cd_inventario, $cd_item_inventario, $comSelecione=false)
    {
        $objSelect = $this->fetchAll("cd_inventario = {$cd_inventario} and cd_item_inventario = {$cd_item_inventario}");

        $arrResult = array();

        if($comSelecione === true){
            $arrResult[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($objSelect as $value) {
            $arrResult[$value->cd_item_inventariado] = $value->tx_item_inventariado;
        }
        return $arrResult;
    }

        public function getItensInventariadoDemandaDados($cd_contrato, $selecione=true)
	{

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->distinct();
        $select->from(array('sii'=>KT_A_ITEM_INVENTARIADO),
                    array('cd_item_inventariado', 'tx_item_inventariado'),
                    $this->_schema);
        $select->where('"cd_item_inventario" in ?', $this->select()->setIntegrityCheck(false)
                    ->from(KT_A_CONTRATO_ITEM_INVENTARIO,'cd_item_inventario',$this->_schema)
                    ->where('"cd_contrato" = ?',$cd_contrato,Zend_Db::INT_TYPE));
       
        
     /* $select = "select distinct(tx_item_inventariado) from oasis.a_item_inventariado
             where cd_item_inventario in (
                             select cd_item_inventario 
                             from oasis.a_contrato_item_inventario 
                             where cd_contrato= $cd_contrato)";       */
        
        $arrItemInventarioDemanda =  $this->fetchAll($select)->toArray();
        $arrResult = array();

        if ($selecione) {
            $arrResult[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        foreach ($arrItemInventarioDemanda as $value) {
            $arrResult[$value['cd_item_inventariado']] = $value['tx_item_inventariado'];
        }
        
        return $arrResult;

	}

    
}
