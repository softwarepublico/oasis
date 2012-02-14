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

class QuestaoAnaliseRisco extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_QUESTAO_ANALISE_RISCO;
	protected $_primary  = 'cd_questao_analise_risco';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getQuestaoAnaliseRisco($comSelecione = false)
	{
		$arrQuestaoAnaliseRisco = array();
		
		if ($comSelecione === true) {
			$arrQuestaoAnaliseRisco[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$select = $this->select()->order("tx_questao_analise_risco");
		$res    = $this->fetchAll($select);
		
		foreach ($res as $valor){
			$arrQuestaoAnaliseRisco[$valor->cd_questao_analise_risco] = $valor->tx_questao_analise_risco;
		}
	
		return $arrQuestaoAnaliseRisco;
	}
	
	public function getDadosQuestaoAnaliseRisco($cd_etapa = null, $cd_atividade = null, $cd_item_risco = null)
	{
		
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('ir'=>KT_B_ITEM_RISCO),
                      'tx_item_risco',
                      $this->_schema);
        $select->join(array('qar'=>$this->_name),
                      '(ir.cd_item_risco = qar.cd_item_risco)',
                      array('cd_questao_analise_risco',
                            'tx_questao_analise_risco',
                            'cd_etapa',
                            'cd_atividade',
                            'cd_item_risco'),
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                      '(ir.cd_etapa = eta.cd_etapa)',
                      'tx_etapa',
                      $this->_schema);
        $select->join(array('ati'=>KT_B_ATIVIDADE),
                      '(ir.cd_atividade = ati.cd_atividade)',
                      'tx_atividade',
                      $this->_schema);
        $select->order('qar.tx_questao_analise_risco');
        
		if(!is_null($cd_etapa)){
            $select->where('ir.cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_atividade)){
            $select->where('ir.cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_item_risco)){
            $select->where('ir.cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE);
		}
        return $this->fetchAll($select)->toArray();

/*
		$where = "where";
		if(!is_null($cd_etapa)){
			$where .= " ir.cd_etapa = {$cd_etapa} and";
		}
		if(!is_null($cd_atividade)){
			$where .= " ir.cd_atividade = {$cd_atividade} and";
		}
		if(!is_null($cd_item_risco)){
			$where .= " ir.cd_item_risco = {$cd_item_risco} and";
		}
		if($where != "where"){
			$where = substr($where,0,-3);
		} else {
			$where = "";
		}

		$sql = " select 
					ir.tx_item_risco,
					qar.cd_questao_analise_risco,
					qar.tx_questao_analise_risco,
					qar.cd_etapa,
					qar.cd_atividade,
					qar.cd_item_risco,
					eta.tx_etapa,
					ati.tx_atividade
				from {$this->_schema}.".KT_B_ITEM_RISCO."             as ir
				join {$this->_schema}.".KT_B_QUESTAO_ANALISE_RISCO."  as qar ON(ir.cd_item_risco = qar.cd_item_risco)
				join {$this->_schema}.".KT_B_ETAPA."                  as eta ON(ir.cd_etapa = eta.cd_etapa)
				join {$this->_schema}.".KT_B_ATIVIDADE."              as ati ON(ir.cd_atividade = ati.cd_atividade)
				{$where}
				order by 
						qar.tx_questao_analise_risco ";
		return $this->getDefaultAdapter()->fetchAll($sql);
 */
        
	}
}