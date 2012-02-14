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

class ContratoItemInventario extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_CONTRATO_ITEM_INVENTARIO;
	protected $_primary  = array('cd_contrato', 'cd_item_inventario');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function pesquisaItemInventarioNoContrato( $cd_contrato )
    {
        $select = $this->_montaSelectPesquisaItemInventarioContrato($cd_contrato);
        $select->where('cp.cd_item_inventario IS NOT NULL');

        return $this->fetchAll($select)->toArray();
    }

    public function pesquisaItemInventarioForaDoContrato( $cd_contrato )
    {
        $select = $this->_montaSelectPesquisaItemInventarioContrato($cd_contrato);
        $select->where('cp.cd_item_inventario IS NULL');

        return $this->fetchAll($select)->toArray();
    }

    private function _montaSelectPesquisaItemInventarioContrato($cd_contrato)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pr'=>KT_B_ITEM_INVENTARIO),
                      array('cd_item_inventario',
                            'tx_item_inventario'),
                      $this->_schema);
        $select->joinLeft(array('cp'=>$this->select()
                                           ->from($this,'cd_item_inventario')
                                           ->where('cd_contrato = ?',$cd_contrato, Zend_Db::INT_TYPE)),
                          '(cp.cd_item_inventario = pr.cd_item_inventario)',
                          array());
        $select->order('tx_item_inventario');
        
        return $select;
    }
    
    public function listaItemInventarioContrato($cd_contrato, $comSelecione=false, $comTodos=false)
    {
    	$arrItemInventarioContrato = $this->pesquisaItemInventarioNoContrato($cd_contrato);
    	$arrItemInventario         = array();
    	
    	if ($comSelecione === true && $comTodos === true) {
			$arrItemInventario[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
			$arrItemInventario[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
		} else if($comSelecione === true){
			$arrItemInventario[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
    	
    	foreach ($arrItemInventarioContrato as $itemInventario){
    		$arrItemInventario[$itemInventario["cd_item_inventario"]] = $itemInventario["tx_item_inventario"];
    	}

		return $arrItemInventario;
    }

	public function getContratosExecucaoItemInventario($cd_item_inventario)
	{
		$select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('cp'=>$this->_name),
                      array('cp.cd_contrato'),
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      '(cp.cd_contrato = con.cd_contrato)',
                      'con.tx_numero_contrato',
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(oc.cd_contrato = con.cd_contrato)',
                      'oc.tx_objeto',
                      $this->_schema);
		$select->where('cp.cd_item_inventario = ?', $cd_item_inventario, Zend_Db::INT_TYPE);
        $select->order('con.dt_inicio_contrato');

        return $this->fetchAll($select)->toArray();
	}
}