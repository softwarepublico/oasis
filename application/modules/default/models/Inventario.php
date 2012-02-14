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

class Inventario extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_INVENTARIO;
	protected $_primary  = 'cd_inventario';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getInventario( $cd_area_atuacao_ti = null, $cd_inventario = null, $isObjeto = false ){

		$select = $this->select()->setIntegrityCheck(false)
					   ->from(array('i'=>$this->_name),
							  '*',
							  $this->_schema)
						->join(array('aati'=>KT_B_AREA_ATUACAO_TI),
                               "i.cd_area_atuacao_ti = aati.cd_area_atuacao_ti",
                                'tx_area_atuacao_ti',
                                $this->_schema);
		$select->order("i.tx_inventario");

		if( !is_null($cd_area_atuacao_ti))
			$select->where("i.cd_area_atuacao_ti = ?", $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
		if( !is_null($cd_inventario))
			$select->where("i.cd_inventario = ?", $cd_inventario, Zend_Db::INT_TYPE);
		$retorno = $this->fetchAll($select);

		return ($isObjeto === false) ? $retorno->toArray() : $retorno;
	}

    public function comboNomeInventario($cd_area_atuacao_ti, $comSelecione=false)
    {
        $objSelect = $this->fetchAll("cd_area_atuacao_ti = {$cd_area_atuacao_ti}");

        $arrResult = array();

        if($comSelecione === true){
            $arrResult[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($objSelect as $value) {
            $arrResult[$value->cd_inventario] = $value->tx_inventario;
        }
        return $arrResult;
    }
    
        public function comboNomeInventarioPorContrato($cd_contrato, $comSelecione=false)
    {
        
       $select = $this->select()->setIntegrityCheck(false)
					   ->from(array('i'=>$this->_name),
							  '*',
							  $this->_schema)
						->join(array('aati'=>KT_B_AREA_ATUACAO_TI),
                               "i.cd_area_atuacao_ti = aati.cd_area_atuacao_ti",
                                'tx_area_atuacao_ti',
                                $this->_schema);
		$select->order("i.tx_inventario");

		if( !is_null($cd_area_atuacao_ti))
			$select->where("i.cd_area_atuacao_ti = ?", $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
		if( !is_null($cd_inventario))
			$select->where("i.cd_inventario = ?", $cd_inventario, Zend_Db::INT_TYPE);
		$retorno = $this->fetchAll($select);

		return ($isObjeto === false) ? $retorno->toArray() : $retorno;
    }

}