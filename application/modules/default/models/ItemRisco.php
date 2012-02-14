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

class ItemRisco extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_ITEM_RISCO;
	protected $_primary  = 'cd_item_risco';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getItemRisco($comSelecione = false, $cd_item_risco = null, $cd_etapa = null, $cd_atividade = null)
	{
		$select = $this->select();
		if(!is_null($cd_item_risco))
			$select->where("cd_item_risco = ?",$cd_item_risco, Zend_Db::INT_TYPE);
		if(!is_null($cd_etapa))
			$select->where("cd_etapa = ?", $cd_etapa, Zend_Db::INT_TYPE);
		if(!is_null($cd_atividade))
			$select->where("cd_atividade = ?", $cd_atividade, Zend_Db::INT_TYPE);
		
		$arrItemRisco = array();
		if ($comSelecione === true) {
			$arrItemRisco[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		$select->order("tx_item_risco");
		$rowSet    = $this->fetchAll($select);
		
		if($rowSet->valid()){
			foreach ($rowSet as $valor){
				$arrItemRisco[$valor->cd_item_risco] = $valor->tx_item_risco;
			}
		}
		return $arrItemRisco;
	}

	public function getDadosItemRisco($cd_etapa = null, $cd_atividade = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('ir'=>$this->_name),
                      array('cd_item_risco',
                            'cd_etapa',
                            'cd_atividade',
                            'tx_item_risco',
                            'tx_descricao_item_risco'),
                      $this->_schema);
        $select->join(array('e'=>KT_B_ETAPA),
                      '(ir.cd_etapa = e.cd_etapa)',
                      'tx_etapa',
                      $this->_schema);
        $select->join(array('a'=>KT_B_ATIVIDADE),
                      '(ir.cd_atividade = a.cd_atividade)',
                      'tx_atividade',
                      $this->_schema);
		$select->order('tx_item_risco');
        
        if(!is_null($cd_etapa)){
			$select->where('ir.cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_atividade)){
			$select->where('a.cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
		}
        return $this->fetchAll($select)->toArray();
	}

	public function getQtdItem($cd_etapa = null, $cd_atividade = null)
	{
        $select = $this->select()
                       ->from($this,array('total_item'=>new Zend_Db_Expr('COUNT(cd_item_risco)')));

        if(!is_null($cd_etapa))
			$select->where('cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
		if(!is_null($cd_atividade))
			$select->where('cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);

		$arrQtd = $this->fetchAll($select)->toArray();

		return $arrQtd[0]['total_item'];		
	}
}