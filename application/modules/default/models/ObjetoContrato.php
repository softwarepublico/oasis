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

class ObjetoContrato extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_OBJETO_CONTRATO;
	protected $_primary  = 'cd_objeto';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getObjetoContrato($cd_contrato = null,$comSelecione = true,$st_contrato = null)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('oc'=>$this->_name),
                      array('cd_objeto','tx_objeto'),
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      '(oc.cd_contrato = con.cd_contrato)',
                      'tx_numero_contrato',
                      $this->_schema);
		$select->order('oc.tx_objeto');

        if(!is_null($st_contrato)){
            $select->where('con.st_contrato = ?',$st_contrato);
		}
		if(!is_null($cd_contrato)){
            $select->where('con.cd_contrato = ?',$cd_contrato);
		}

	  	$rowSet = $this->fetchAll($select);

        $arrObjetoContrato = array();
	  	if($comSelecione){
	  		$arrObjetoContrato[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
	  	}
		foreach ($rowSet as  $row) {
			$arrObjetoContrato[$row->cd_objeto] = "{$row->tx_objeto} ({$row->tx_numero_contrato})";
		}
		return $arrObjetoContrato;
	}

	public function getObjeto($cd_objeto = null, $comSelecione = false)
	{
		$arr  = $this->getObjetoContrato(null, $comSelecione);
		
		$arr2 = array();
		if ($comSelecione === true) {
			$arr2[0] = $arr[0];
		} 
		
		$arr2[$cd_objeto] = $arr[$cd_objeto];
		
		return $arr2;
	}
	
	/**
     *
     * @param String|Array $tipo        OPCIONAL
     * @param Bool $comSelecione
     * @param Bool $comAdministrador
     * @param Bool $comDescNrContrato
     * @param Integer $cd_objeto        OPCIONAL
     * @param Integer $cd_contrato      OPCIONAL
     * @return Array
     */
    public function getObjetoContratoAtivo($tipo = null, $comSelecione = false, $comAdministrador = false, $comDescNrContrato = false, $cd_objeto = null, $cd_contrato = null, $objComJustificativa=false)
	{
        $arrObjetoContrato = array();
		if ($comSelecione === true){
			$arrObjetoContrato[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('obj'=>$this->_name),
                      array('cd_objeto','tx_objeto'),
                      $this->_schema);
        $select->join(array('cont'=>KT_S_CONTRATO),
                      '(obj.cd_contrato = cont.cd_contrato)',
                      'tx_numero_contrato',
                      $this->_schema);
        $select->where('st_contrato = ?', 'A');
        $select->order('obj.tx_objeto');

        if(!is_null($tipo))
            $select->where('obj.st_objeto_contrato IN (?)', $tipo);
		if ($comAdministrador === false)
            $select->where('obj.cd_objeto <> 0');
        if(!is_null($cd_contrato))
            $select->where('obj.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        if(!is_null($cd_objeto))
            $select->where('obj.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        if($objComJustificativa === true)
            $select->where('obj.st_necessita_justificativa = ?', 'S');

        $rowSet = $this->fetchAll($select);
        if ($rowSet->valid()) {
            foreach ($rowSet as  $row) {
                if($comDescNrContrato === true){
                    $arrObjetoContrato[$row->cd_objeto] = $row->tx_objeto." ({$row->tx_numero_contrato})";
                }else{
                    $arrObjetoContrato[$row->cd_objeto] = $row->tx_objeto;
                }
            }
        }
        return $arrObjetoContrato;
	}
	
	public function getDadosObjetoContrato($cd_contrato = null, $cd_objeto = null)
	{
		$select = $this->select();
		if(!is_null($cd_contrato)){
			$select->where("cd_contrato = ?", $cd_contrato, Zend_Db::INT_TYPE);
		} else if(!is_null($cd_objeto)){
			$select->where("cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE);
		} else {
			$select = null;
		}
		return $this->fetchAll($select)->toArray();
	}

    public function getAreaAtuacaoTiObjetoContrato($cd_contrato = null)
	{
        $select = $this->select();
		if(!is_null($cd_contrato)){
			$select->where("cd_contrato = ?", $cd_contrato, Zend_Db::INT_TYPE);
		} else {
			$select = null;
		}
        
        $arrobjetoContrato = $this->fetchRow($select)->toArray();
        
		return $arrobjetoContrato['cd_area_atuacao_ti'];
	}

    
	public function getTipoObjeto()
	{
		$arrTipoObjeto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');

        $select = $this->select()
					->distinct()
					->from($this->_name,
                           array("st_objeto_contrato",
                                 "tipo_objeto" => new Zend_Db_Expr("CASE st_objeto_contrato WHEN 'D' THEN '".Base_Util::getTranslator('L_SQL_DEMANDA')."'
                                                                                            WHEN 'P' THEN '".Base_Util::getTranslator('L_SQL_PROJETO')."'
                                                                                            WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_SERVICO')."' END")),
                                 $this->_schema)
					->where("st_objeto_contrato IS NOT NULL")
					->order("st_objeto_contrato");
		
		$arr = $this->fetchAll($select);

		foreach($arr as $objeto)
			$arrTipoObjeto[$objeto->st_objeto_contrato] = $objeto->tipo_objeto;

		return $arrTipoObjeto;
	}

	public function verificaFlagParcelaOrcamento($cd_objeto)
	{
		$arrObjetoContrato                                    = $this->find($cd_objeto)->current()->toArray();
		$arrParcelaOrcamento['cd_objeto']                     = $cd_objeto;
		$arrParcelaOrcamento['st_parcela_orcamento']          = $arrObjetoContrato['st_parcela_orcamento'];
		$arrParcelaOrcamento['ni_porcentagem_parc_orcamento'] = $arrObjetoContrato['ni_porcentagem_parc_orcamento'];
		return $arrParcelaOrcamento;
	}

	public function getDadosJustificativa($cd_objeto)
	{
		$arrObjeto                                         = $this->find($cd_objeto)->current()->toArray();
		$arrJustificativa["st_necessita_justificativa"]    = $arrObjeto["st_necessita_justificativa"];
		$arrJustificativa["ni_minutos_justificativa"]      = $arrObjeto["ni_minutos_justificativa"];
		$arrJustificativa["tx_hora_inicio_just_periodo_1"] = $arrObjeto["tx_hora_inicio_just_periodo_1"];
		$arrJustificativa["tx_hora_fim_just_periodo_1"]    = $arrObjeto["tx_hora_fim_just_periodo_1"];
		$arrJustificativa["tx_hora_inicio_just_periodo_2"] = $arrObjeto["tx_hora_inicio_just_periodo_2"];
		$arrJustificativa["tx_hora_fim_just_periodo_2"]    = $arrObjeto["tx_hora_fim_just_periodo_2"];
		return $arrJustificativa;
	}
}