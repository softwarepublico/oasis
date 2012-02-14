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

class PreDemanda extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PRE_DEMANDA;
	protected $_primary  = 'cd_pre_demanda';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getPreDemanda($cd_pre_demanda)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pre'=>$this->_name),
                      array('pre.cd_pre_demanda',
                            'tx_pre_demanda',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'st_fim_pre_demanda',
                            'st_aceite_pre_demanda',
                            'tx_obs_aceite_pre_demanda',
                            'dt_pre_demanda',
                            'situacao'=>new Zend_Db_Expr("CASE pre.st_aceite_pre_demanda WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_ATENDIDA')."'
                                                                                         WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_NAO_ATENDIDA')."'
                                                                                         ELSE '".Base_Util::getTranslator('L_SQL_AGUARDADO_ACEITE')."' END"),
                            'situacao_geral'=>new Zend_Db_Expr("CASE WHEN pre.ni_solicitacao IS NULL THEN '".Base_Util::getTranslator('L_SQL_AGUARDANDO_ENCAMINHAMENTO')."'
                                                                    WHEN pre.ni_solicitacao IS NOT NULL 
                                                                    AND dem.cd_demanda IS NULL THEN '".Base_Util::getTranslator('L_SQL_ENCAMINHADA_EXECUCAO')."'
                                                                    WHEN dem.cd_demanda IS NOT NULL THEN '".Base_Util::getTranslator('L_SQL_EM_EXECUCAO')."'
                                                                END")),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(pre.cd_profissional_solicitante = prof.cd_profissional)',
                      array('cd_profissional', 'tx_profissional', 'tx_ramal_profissional'),
                      $this->_schema);
        $select->join(array('obje'=>KT_S_OBJETO_CONTRATO),
                      '(pre.cd_objeto_emissor = obje.cd_objeto)',
                      array('cd_objeto_emissor'=>'cd_objeto', 'tx_objeto_emissor'=>'tx_objeto'),
                      $this->_schema);
        $select->join(array('objr'=>KT_S_OBJETO_CONTRATO),
                      '(pre.cd_objeto_receptor = objr.cd_objeto)',
                      array('tx_objeto', 'cd_objeto'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(pre.cd_unidade = uni.cd_unidade)',
                      array('tx_sigla_unidade', 'cd_unidade'),
                      $this->_schema);
        $select->joinLeft(array('sol'=>KT_S_SOLICITACAO),
                          '(pre.cd_objeto_receptor = sol.cd_objeto) AND
                           (pre.ni_solicitacao     = sol.ni_solicitacao) AND
                           (pre.ni_ano_solicitacao = sol.ni_ano_solicitacao)',
                          array(),
                          $this->_schema);
        $select->joinLeft(array('dem'=>KT_S_DEMANDA),
                          '(pre.cd_objeto_receptor = dem.cd_objeto) AND
                           (pre.ni_solicitacao     = dem.ni_solicitacao) AND
                           (pre.ni_ano_solicitacao = dem.ni_ano_solicitacao)',
                          'cd_demanda',
                          $this->_schema);
        $select->where('pre.cd_pre_demanda = ?', $cd_pre_demanda, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}
	
	public function getPreDemandaAndamento($mes, $ano, $cd_objeto_emissor = null, $cd_objeto_receptor = null)
	{
		/*
		Lista de pre-demandas da tab PRE-DEMANDA >> Pre-demandas em Andamento

		Busca as pre-demandas que foram emitidas mas as solicitações de serviço
		que formalizaram as pre-demandas ainda não foram finalizadas 
		*/
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pre'=>$this->_name),
                      array('cd_pre_demanda',
                            'tx_pre_demanda',
                            'ni_solicitacao',
                            'dt_pre_demanda',
                            'ni_ano_solicitacao',
                            'situacao'=> new Zend_Db_Expr("CASE WHEN pre.ni_solicitacao IS NULL THEN '".Base_Util::getTranslator('L_SQL_AGUARDANDO_ENCAMINHAMENTO')."'
                                                                WHEN pre.ni_solicitacao IS NOT NULL AND
                                                                     dem.cd_demanda IS NULL THEN '".Base_Util::getTranslator('L_SQL_ENCAMINHADA_EXECUCAO')."'
                                                                WHEN dem.cd_demanda IS NOT NULL THEN '".Base_Util::getTranslator('L_SQL_EM_EXECUCAO')."'
                                                           END")),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(pre.cd_profissional_solicitante = prof.cd_profissional)',
                      array('tx_profissional','tx_nome_conhecido'),
                      $this->_schema);
        $select->join(array('obje'=>KT_S_OBJETO_CONTRATO),
                      '(pre.cd_objeto_receptor = obje.cd_objeto)',
                      array(),
                      $this->_schema);
        $select->join(array('objr'=>KT_S_OBJETO_CONTRATO),
                      '(pre.cd_objeto_receptor = objr.cd_objeto)',
                      array('cd_objeto', 'tx_objeto'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(pre.cd_unidade = uni.cd_unidade)',
                      array('cd_unidade','tx_sigla_unidade'),
                      $this->_schema);
        $select->joinLeft(array('sol'=>KT_S_SOLICITACAO),
                          '(pre.cd_objeto_receptor = sol.cd_objeto) AND
                           (pre.ni_solicitacao     = sol.ni_solicitacao) AND
                           (pre.ni_ano_solicitacao = sol.ni_ano_solicitacao)',
                          array(),
                          $this->_schema);
        $select->joinLeft(array('dem'=>KT_S_DEMANDA),
                          '(dem.ni_solicitacao     = pre.ni_solicitacao) AND
                           (dem.ni_ano_solicitacao = pre.ni_ano_solicitacao) AND
                           (dem.cd_objeto          = pre.cd_objeto_receptor)',
                          'cd_demanda',
                          $this->_schema);

        $select->where(new Zend_Db_Expr("{$this->to_char('dt_pre_demanda','MM')}   = '{$mes}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_pre_demanda','YYYY')} = '{$ano}'"))
               ->where("sol.st_fechamento IS NULL");
		$select->order("pre.dt_pre_demanda DESC");

		if (!is_null($cd_objeto_emissor)){
			if ($cd_objeto_emissor != "-1"){
                $select->where("pre.cd_objeto_emissor = ?", $cd_objeto_emissor, Zend_Db::INT_TYPE);
			}
		}
		if (!is_null($cd_objeto_receptor)){
			if ($cd_objeto_receptor != "-1"){
                $select->where("pre.cd_objeto_receptor = ?", $cd_objeto_receptor, Zend_Db::INT_TYPE);
			}
		}
        return $this->fetchAll($select)->toArray();

	}
	
	public function getPreDemandaExecutada($mes, $ano, $cd_objeto_emissor = null, $cd_objeto_receptor = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pre'=>$this->_name),
                      array('cd_pre_demanda',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'st_aceite_pre_demanda',
                            'st_reabertura_pre_demanda',
                            'tx_pre_demanda',
                            'dt_pre_demanda',
                            'situacao'=>new Zend_Db_Expr("CASE pre.st_aceite_pre_demanda WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_ATENDIDA')."'
                                                                                         WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_NAO_ATENDIDA')."'
                                                                                         ELSE '".Base_Util::getTranslator('L_SQL_AGUARDADO_ACEITE')."' END")),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(pre.cd_profissional_solicitante = prof.cd_profissional)',
                      'tx_profissional',
                      $this->_schema);
        $select->join(array('obje'=>KT_S_OBJETO_CONTRATO),
                      '(pre.cd_objeto_receptor = obje.cd_objeto)',
                      array(),
                      $this->_schema);
        $select->join(array('objr'=>KT_S_OBJETO_CONTRATO),
                      '(pre.cd_objeto_receptor = objr.cd_objeto)',
                      array('cd_objeto', 'tx_objeto'),
                      $this->_schema);
        $select->joinLeft(array('sol'=>KT_S_SOLICITACAO),
                          '(pre.cd_objeto_receptor = sol.cd_objeto) AND
                           (pre.ni_solicitacao     = sol.ni_solicitacao) AND
                           (pre.ni_ano_solicitacao = sol.ni_ano_solicitacao)',
                          'st_grau_satisfacao',
                          $this->_schema);
        $select->joinLeft(array('dem'=>KT_S_DEMANDA),
                          '(pre.ni_solicitacao     = dem.ni_solicitacao) AND
                           (pre.ni_ano_solicitacao = dem.ni_ano_solicitacao) AND
                           (pre.cd_objeto_receptor = dem.cd_objeto)',
                          'cd_demanda',
                          $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_pre_demanda','MM')}   = '{$mes}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_pre_demanda','YYYY')} = '{$ano}'"))
               ->where("sol.st_fechamento IS NOT NULL");
		$select->order("pre.dt_pre_demanda DESC");

		if (!is_null($cd_objeto_emissor)){
			if ($cd_objeto_emissor != "-1"){
                $select->where("pre.cd_objeto_emissor = ?", $cd_objeto_emissor, Zend_Db::INT_TYPE);
			}
		}
		if (!is_null($cd_objeto_receptor)){
			if ($cd_objeto_receptor != "-1"){
                $select->where("pre.cd_objeto_receptor = ?", $cd_objeto_receptor, Zend_Db::INT_TYPE);
			}
		}
        return $this->fetchAll($select)->toArray();
	}
	
	public function atualizaPreDemanda($cd_pre_demanda, $addRow)
	{
		$erros = false;
		
		$where         = "cd_pre_demanda = {$cd_pre_demanda}";
		$rowPreDemanda = $this->fetchRow($where);
		
		if (!is_null($rowPreDemanda)){
			if (!$this->update($addRow, $where)){
				$erros = true;
			}
		}
		return $erros;
	}			
}