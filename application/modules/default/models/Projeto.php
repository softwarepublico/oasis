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

class Projeto extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PROJETO;
	protected $_primary  = 'cd_projeto';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getProjeto($comSelecione = false,$cd_projeto = null, $comTodos = false)
	{
		$select = $this->select();
		if (!is_null($cd_projeto)) {
			$select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
		}
		$select->order("tx_sigla_projeto");
		
		$rowSet = $this->fetchAll($select);

		$arrProjetos = array();
		if ($comSelecione === true && $comTodos === true) {
			$arrProjetos[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
			$arrProjetos[0]  = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
		} else if($comSelecione === true){
			$arrProjetos[0]  = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($rowSet as  $row) {
			$arrProjetos[$row->cd_projeto] = $row->tx_sigla_projeto;
		}
		return $arrProjetos;
	}
	
	public function getDadosProjeto($cd_projeto = null, $ord = false)
	{
        $select = null;
		if(!is_null($cd_projeto)){
			$select = $this->select()
                           ->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
		}
        
        if ($ord == true) {
             $select = $this->select()
                            ->order('tx_sigla_projeto');
        }
		return $this->fetchAll($select)->toArray();
	}

    /**
     *
     * @param type $cd_projeto
     * @return integer 
     */
    public function getStatusProjeto($cd_projeto)
	{
        $select = $this->select()->from($this,array('cd_status'));
        $select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
		
        return $this->getAdapter()->fetchOne($select);
	}
	
	public function atualizaStatusProjeto($cd_projeto, $addRow)
	{
		$erros = false;
		
		$where      = "cd_projeto = {$cd_projeto}";
		$rowProjeto = $this->fetchRow($where);
		
		if (!is_null($rowProjeto)){
			if (!$this->update($addRow, $where)){
				$erros = true;
			}
		}
		return $erros;
	}
	
	public function validaAccordion($cd_projeto, $cd_proposta)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('proj'=>$this->_name),
                      array('cd_projeto',
                            'st_dicionario_dados',
                            'st_informacoes_tecnicas'),
                      $this->_schema);
        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(proj.cd_projeto = prop.cd_projeto)',
                      'st_caso_de_uso',
                      $this->_schema);
		$select->where('proj.cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('prop.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}
	
	public function getDescricaoProjeto($cd_projeto)
	{
		$select = $this->select()->setIntegrityCheck(false);

        $select->from(array('proj'=>$this->_name),
                      array('cd_projeto','tx_projeto','tx_sigla_projeto','tx_escopo_projeto','tx_obs_projeto','tx_gestor_projeto','tx_contexto_geral_projeto',
                            'tx_publico_alcancado','ni_ano_inicio_previsto','ni_ano_termino_previsto',
                            'tx_co_gestor_projeto',
                            'cd_profissional_gerente',
                            'cd_unidade',
                            'cd_status',
                            'st_prioridade_projeto' => new Zend_Db_Expr("case st_prioridade_projeto
                                                                              when 'A' then '".Base_Util::getTranslator('L_SQL_ALTISSIMA')."'
                                                                              when 'L' then '".Base_Util::getTranslator('L_SQL_ALTA')."'
                                                                              when 'M' then '".Base_Util::getTranslator('L_SQL_MEDIA')."'
                                                                              when 'B' then '".Base_Util::getTranslator('L_SQL_BAIXA')."' end"),
                            'st_impacto_projeto' => new Zend_Db_Expr("case st_impacto_projeto
                                                                           when 'I' then '".Base_Util::getTranslator('L_SQL_INTERNO')."'
                                                                           when 'E' then '".Base_Util::getTranslator('L_SQL_EXTERNO')."'
                                                                           when 'A' then '".Base_Util::getTranslator('L_SQL_INTERNO_EXTERNO')."' end"),
                            'ni_mes_inicio_previsto' => new Zend_Db_Expr("case ni_mes_inicio_previsto
                                                                               when '1' then '".Base_Util::getTranslator('L_SQL_JANEIRO')."'
                                                                               when '2' then '".Base_Util::getTranslator('L_SQL_FEVEREIRO')."'
                                                                               when '3' then '".Base_Util::getTranslator('L_SQL_MARCO')."'
                                                                               when '4' then '".Base_Util::getTranslator('L_SQL_ABRIL')."'
                                                                               when '5' then '".Base_Util::getTranslator('L_SQL_MAIO')."'
                                                                               when '6' then '".Base_Util::getTranslator('L_SQL_JUNHO')."'
                                                                               when '7' then '".Base_Util::getTranslator('L_SQL_JULHO')."'
                                                                               when '8' then '".Base_Util::getTranslator('L_SQL_AGOSTO')."'
                                                                               when '9' then '".Base_Util::getTranslator('L_SQL_SETEMBRO')."'
                                                                               when '10' then '".Base_Util::getTranslator('L_SQL_OUTUBRO')."'
                                                                               when '11' then '".Base_Util::getTranslator('L_SQL_NOVEMBRO')."'
                                                                               when '12' then '".Base_Util::getTranslator('L_SQL_DEZEMBRO')."' end"),
                            'ni_mes_termino_previsto' => new Zend_Db_Expr("case ni_mes_termino_previsto
                                                                                when '1' then '".Base_Util::getTranslator('L_SQL_JANEIRO')."'
                                                                                when '2' then '".Base_Util::getTranslator('L_SQL_FEVEREIRO')."'
                                                                                when '3' then '".Base_Util::getTranslator('L_SQL_MARCO')."'
                                                                                when '4' then '".Base_Util::getTranslator('L_SQL_ABRIL')."'
                                                                                when '5' then '".Base_Util::getTranslator('L_SQL_MAIO')."'
                                                                                when '6' then '".Base_Util::getTranslator('L_SQL_JUNHO')."'
                                                                                when '7' then '".Base_Util::getTranslator('L_SQL_JULHO')."'
                                                                                when '8' then '".Base_Util::getTranslator('L_SQL_AGOSTO')."'
                                                                                when '9' then '".Base_Util::getTranslator('L_SQL_SETEMBRO')."'
                                                                                when '10' then '".Base_Util::getTranslator('L_SQL_OUTUBRO')."'
                                                                                when '11' then '".Base_Util::getTranslator('L_SQL_NOVEMBRO')."'
                                                                                when '12' then '".Base_Util::getTranslator('L_SQL_DEZEMBRO')."' end")),
                      $this->_schema);
        $select->join(KT_S_PROFISSIONAL,
                      '(cd_profissional_gerente = cd_profissional)',
                      'tx_profissional',
                      $this->_schema);
        $select->join(array('b'=>KT_B_UNIDADE),
                      '(proj.cd_unidade = b.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);
        $select->join(array('st'=>KT_B_STATUS),
                      '(proj.cd_status = st.cd_status)',
                      'tx_status',
                      $this->_schema);
        $select->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        $rowset = $this->fetchRow($select);
        if (is_object($rowset)) {
            return $rowset->toArray();
        }

/*
        $sql = "select
					tx_projeto,
					tx_sigla_projeto,
					tx_escopo_projeto,
					case st_prioridade_projeto when 'A' then 'Altíssima' when 'L' then 'Alta' when 'M' then 'Média' when 'B' then 'Baixa' end as st_prioridade_projeto,
					tx_obs_projeto,
					tx_gestor_projeto,
					case st_impacto_projeto when 'I' then 'Interno' when 'E' then 'Externo' when 'A' then 'Interno e Externo' end as st_impacto_projeto,
					tx_contexto_geral_projeto,
					tx_publico_alcancado,
					case ni_mes_inicio_previsto
						when '1' then 'Janeiro'
						when '2' then 'Fevereiro'
						when '3' then 'Março'
						when '4' then 'Abril'
						when '5' then 'Maio'
						when '6' then 'Junho'
						when '7' then 'Julho'
						when '8' then 'Agosto'
						when '9' then 'Setembro'
						when '10' then 'Outubro'
						when '11' then 'Novembro'
						when '12' then 'Dezembro'
					end as ni_mes_inicio_previsto,
					ni_ano_inicio_previsto,
					case ni_mes_termino_previsto
						when '1' then 'Janeiro'
						when '2' then 'Fevereiro'
						when '3' then 'Março'
						when '4' then 'Abril'
						when '5' then 'Maio'
						when '6' then 'Junho'
						when '7' then 'Julho'
						when '8' then 'Agosto'
						when '9' then 'Setembro'
						when '10' then 'Outubro'
						when '11' then 'Novembro'
						when '12' then 'Dezembro'
					end as ni_mes_termino_previsto,
					ni_ano_termino_previsto,
					tx_co_gestor_projeto,
					cd_projeto,
					cd_profissional_gerente,
					proj.cd_unidade,
					proj.cd_status,
					tx_profissional,
					tx_sigla_unidade,
					tx_status
				from
					{$this->_schema}.".KT_S_PROJETO." as proj
				join
					{$this->_schema}.".KT_S_PROFISSIONAL."
				on
					cd_profissional_gerente = cd_profissional
				join
					{$this->_schema}.".KT_B_UNIDADE." as b
				on
					proj.cd_unidade = b.cd_unidade
				join
					{$this->_schema}.".KT_B_STATUS." as st
				on
					proj.cd_status = st.cd_status
				where
					cd_projeto = {$cd_projeto}";
		return $this->getDefaultAdapter()->fetchRow($sql);
*/
	}
	
	/**
	 * Método irá recuperar os projetos do gerente para montar uma combo
	 * 
	 * @param int $cd_profissional_gerente
	 * @param bool $comSelecione
	 * @return array $arrProjetoGerente
	 */
	public function getProjetoGerente($cd_profissional_gerente, $comSelecione = true)
	{
		$selecione = $this->select()
						  ->where("cd_profissional_gerente = ?",$cd_profissional_gerente, Zend_Db::INT_TYPE)
						  ->order("tx_sigla_projeto");
						  
		
		$rowSet = $this->fetchAll($selecione);

        $arrProjetoGerente = array();
		if($comSelecione){
			$arrProjetoGerente[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($rowSet as $row) {
			$arrProjetoGerente[$row->cd_projeto] = $row->tx_sigla_projeto;
		}
		return $arrProjetoGerente;
	}

    /**
     * Método que recupera todos os projetos com as propostas em execução.
     * @return array
     */
    public function getProjetosPropostasExecucao($cd_contrato)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('proj'=>$this->_name),
                      array('cd_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(proj.cd_projeto = prop.cd_projeto)',
                      'cd_proposta',
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(proj.cd_projeto = cp.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->where('cp.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->where('prop.st_encerramento_proposta is null');
        $select->order(array('proj.tx_sigla_projeto',
                             'prop.cd_proposta'));
        return $this->fetchAll($select)->toArray();
    }

	public function getPeriodoExecucaoProjeto($cd_projeto)
	{
		$subSelect = $this->select()
                                 ->setIntegrityCheck(false)
                                 ->distinct()
                                 ->from(array('par'=>KT_S_PARCELA),
                                        array('periodo'      =>new Zend_Db_Expr("ni_ano_execucao_parcela{$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_execucao_parcela","{$this->length("'00' {$this->concat()} ni_mes_execucao_parcela")}-1","2")}")),
                                        $this->_schema)
                                 ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        $select    = $this->select()
                             ->distinct()
                             ->setIntegrityCheck(false)
                             ->from(array('dat'=>$subSelect),
                                    array('dt_inicio'               =>new Zend_Db_Expr("{$this->substring("min(periodo)","{$this->position("/", "min(periodo)")}+1","4")} {$this->concat()} '/' {$this->concat()} {$this->substring("min(periodo)","1","{$this->position("/","min(periodo)")}-1")}"),
                                          'dt_fim'                  =>new Zend_Db_Expr("{$this->substring("max(periodo)","{$this->position("/","max(periodo)")}+1","4")} {$this->concat()} '/' {$this->concat()} {$this->substring("max(periodo)","1","{$this->position("/","max(periodo)")}-1")}")));
		return $this->fetchAll($select)->toArray();
	}
    
    public function salvaProjeto(array $arrProjeto, &$cd_projeto)
	{
        
        $novo 				                = $this->createRow();
        $cd_projeto	                        = $this->getNextValueOfField('cd_projeto');
        $novo->cd_projeto	                = $cd_projeto;
        $novo->tx_projeto                   = $arrProjeto['tx_projeto'];
        $novo->tx_sigla_projeto             = $arrProjeto['tx_sigla_projeto'];
        $novo->tx_contexto_geral_projeto    = $arrProjeto['tx_contexto_geral_projeto'];
        $novo->tx_escopo_projeto            = $arrProjeto['tx_escopo_projeto']; 
        $novo->cd_unidade                   = $arrProjeto['cd_unidade'];
        $novo->tx_gestor_projeto            = $arrProjeto['tx_gestor_projeto']; 
        $novo->tx_obs_projeto               = $arrProjeto['tx_obs_projeto'];
        $novo->st_impacto_projeto           = $arrProjeto['st_impacto_projeto']; 
        $novo->st_prioridade_projeto        = $arrProjeto['st_prioridade_projeto']; 
        $novo->cd_profissional_gerente      = $arrProjeto['cd_gerente_projeto'];  
        $novo->tx_publico_alcancado         = $arrProjeto['tx_pub_alcancado_proj'];  

        if ($novo->save()) {
            return true;
		} else {
			return false;
		}
	}

	public function alterarProjeto(array $arrProjeto)
	{   
        
        $arrUpdate['tx_projeto']                   = $arrProjeto['tx_projeto'];
        $arrUpdate['tx_sigla_projeto']             = $arrProjeto['tx_sigla_projeto'];
        $arrUpdate['tx_contexto_geral_projeto']    = $arrProjeto['tx_contexto_geral_projeto'];
        $arrUpdate['tx_escopo_projeto']            = $arrProjeto['tx_escopo_projeto']; 
        $arrUpdate['cd_unidade']                   = $arrProjeto['cd_unidade'];
        $arrUpdate['tx_gestor_projeto']            = $arrProjeto['tx_gestor_projeto']; 
        $arrUpdate['tx_obs_projeto']               = $arrProjeto['tx_obs_projeto'];
        $arrUpdate['st_impacto_projeto']           = $arrProjeto['st_impacto_projeto']; 
        $arrUpdate['st_prioridade_projeto']        = $arrProjeto['st_prioridade_projeto']; 
        $arrUpdate['cd_profissional_gerente']      = $arrProjeto['cd_gerente_projeto'];  
        $arrUpdate['tx_publico_alcancado']         = $arrProjeto['tx_pub_alcancado_proj'];  

        if($this->update($arrUpdate, array('cd_projeto = ?'=>$arrProjeto['cd_projeto']))){
            return true;
		} else {
			return false;
		}
	}
    
    
}