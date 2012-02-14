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

class Demanda extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_DEMANDA;
	protected $_primary  = 'cd_demanda';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDemanda($cd_demanda, $cd_nivel_servico = null, $cd_profissional = null)
	{

        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dem'=>$this->_name),
                      '*',
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      array(),
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda = dpns.cd_demanda) AND (dp.cd_profissional = dpns.cd_profissional)',
                      array('tx_obs_nivel_servico',
                            'cd_nivel_servico',
                            'cd_profissional',
                            'dt_demanda_nivel_servico'),
//                            'dt_demanda_nivel_servico'=>new Zend_Db_Expr("TO_CHAR(dpns.dt_demanda_nivel_servico, 'DD/MM/YYYY HH24:MI:SS')")),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      'tx_nivel_servico',
                      $this->_schema);
        $select->joinLeft(array('obj'=>KT_S_OBJETO_CONTRATO),
                          '(dem.cd_objeto = obj.cd_objeto)',
                          'tx_objeto',
                          $this->_schema);
        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                          '(dem.cd_unidade = uni.cd_unidade)',
                          'tx_sigla_unidade',
                          $this->_schema);
        $select->where('dem.cd_demanda = ?', $cd_demanda, Zend_Db::INT_TYPE);

		if(!is_null($cd_nivel_servico))
            $select->where('dpns.cd_nivel_servico = ?', $cd_nivel_servico, Zend_Db::INT_TYPE);
		if(!is_null($cd_profissional))
            $select->where('dpns.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);

		return $this->fetchRow($select)->toArray();

//		$and = "";
//		if(!is_null($cd_nivel_servico)){
//			$and .= " and dpns.cd_nivel_servico = {$cd_nivel_servico} ";
//		}
//		if(!is_null($cd_profissional)){
//			$and .= " and dpns.cd_profissional = {$cd_profissional} ";
//		}
//		$sql = "select
//					dem.*,
//					dpns.tx_obs_nivel_servico,
//					dpns.cd_nivel_servico,
//					dpns.cd_profissional,
//					to_char(dpns.dt_demanda_nivel_servico, 'DD/MM/YYYY HH24:MI:SS') as dt_demanda_nivel_servico,
//					ns.tx_nivel_servico,
//					to_char(dt_demanda, 'DD/MM/YYYY HH24:MI:SS') as dt_hora_demanda,
//					tx_objeto,
//					tx_sigla_unidade
//				from
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				join
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL."               as dp
//				on
//					(dem.cd_demanda = dp.cd_demanda)
//				join
//					{$this->_schema}.".KT_A_DEMANDA_PROF_NIVEL_SERVICO." as dpns
//					on (dem.cd_demanda = dpns.cd_demanda and dp.cd_profissional = dpns.cd_profissional)
//				join
//					{$this->_schema}.".KT_B_NIVEL_SERVICO."                      as ns
//					on (dpns.cd_nivel_servico = ns.cd_nivel_servico)
//--				join (modificado temporariamente)
//				left join
//					{$this->_schema}.".KT_S_OBJETO_CONTRATO."                    as obj
//					 on (dem.cd_objeto = obj.cd_objeto)
//				left join
//					{$this->_schema}.".KT_B_UNIDADE."                            as uni
//					on (dem.cd_unidade = uni.cd_unidade)
//				where dem.cd_demanda = {$cd_demanda}{$and}";
	}
	
	public function getDemandaSolicitadaExecutorDemanda($mes, $ano, $cd_profissional)
	{
/*
//@TODO estamos com um pequeno probleminha, quando muda de mes e a demanda nÃ£o e finalizada e nÃ£o aparece mais
mesmo reencaminhando
* */
		$select = $this->select()->setIntegrityCheck(false);
        $select->from(array('dem'=>$this->_name),
                      array('cd_demanda',
                            'tx_solicitante_demanda',
                            'tx_demanda',
                            'cd_objeto',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'dt_demanda',
                            'tx_solicitacao'=>new Zend_Db_Expr("CASE WHEN dem.ni_solicitacao IS NOT NULL THEN dem.ni_solicitacao{$this->concat()}'/'{$this->concat()}dem.ni_ano_solicitacao ELSE NULL END")),
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      'dt_demanda_profissional',
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda = dpns.cd_demanda) AND (dp.cd_profissional = dpns.cd_profissional)',
                      array('dt_leitura_nivel_servico',
                            'dt_demanda_nivel_servico',
                            'cd_nivel_servico',
                            'cd_profissional',
                            'tx_justificativa_nivel_servico'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      'tx_nivel_servico',
                      $this->_schema);
        $select->joinLeft(array('sa'=>KT_B_STATUS_ATENDIMENTO),
                          'dem.cd_status_atendimento = sa.cd_status_atendimento',
                          array('tx_rgb_status_atendimento',
                                'tx_status_atendimento'),
                          $this->_schema);


        $select->where(new zend_db_expr("{$this->to_char('dt_demanda', 'MM')} = '{$mes}'"));
        $select->where(new zend_db_expr("{$this->to_char('dt_demanda', 'YYYY')} = '{$ano}'"));
        $select->where('dpns.st_fechamento_nivel_servico IS NULL');
        $select->where('dpns.dt_demanda_nivel_servico IS NOT NULL');
        $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
        $select->where('sa.st_status_atendimento = ?', 'P');
        $select->order('dt_demanda DESC');

        return $this->fetchAll($select)->toArray();
/*
		$sql = "select
					dem.cd_demanda,
					dem.tx_solicitante_demanda,
					dem.tx_demanda,
					dem.cd_objeto,
					dem.ni_solicitacao,
					dem.ni_ano_solicitacao,
					dem.dt_demanda,
					case
						when
							dem.ni_solicitacao is not null
						then
							dem.ni_solicitacao||'/'||dem.ni_ano_solicitacao
					else
						null
					end as tx_solicitacao,
					to_char(dem.dt_demanda, 'DD/MM/YYYY HH24:MI:SS') as dt_hora_demanda,
					dp.dt_demanda_profissional,
					dpns.dt_leitura_nivel_servico,
					dpns.dt_demanda_nivel_servico,
					dpns.cd_nivel_servico,
					dpns.cd_profissional,
					dpns.tx_justificativa_nivel_servico,
					ns.tx_nivel_servico
				from
					{$this->_schema}.".KT_S_DEMANDA." as dem
				join
					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
				on
					dem.cd_demanda = dp.cd_demanda and dp.cd_profissional = {$cd_profissional}
				join
					{$this->_schema}.".KT_A_DEMANDA_PROF_NIVEL_SERVICO." as dpns
				on
					dem.cd_demanda = dpns.cd_demanda and dp.cd_profissional = dpns.cd_profissional
				join
					{$this->_schema}.".KT_B_NIVEL_SERVICO." as ns
				on
					ns.cd_nivel_servico = dpns.cd_nivel_servico
				where 
					to_char(dt_demanda,'MM') = '{$mes}'
				and
					to_char(dt_demanda,'YYYY') = '{$ano}'
				and
					dpns.st_fechamento_nivel_servico is null
				and
					dpns.dt_demanda_nivel_servico is not null
				order by
					dt_demanda desc";
 */
	}
	
	public function getDemandaExecutadaExecutorDemanda($mes, $ano, $cd_profissional)
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('dem'=>$this->_name),
                      array('cd_demanda',
                            'tx_solicitante_demanda',
                            'tx_demanda',
                            'cd_objeto',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'dt_demanda',
                            'tx_solicitacao'=>new Zend_Db_Expr("CASE WHEN dem.ni_solicitacao IS NOT NULL THEN dem.ni_solicitacao{$this->concat()}'/'{$this->concat()}dem.ni_ano_solicitacao ELSE NULL END")),
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      array(),
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda = dpns.cd_demanda) AND (dp.cd_profissional = dpns.cd_profissional)',
                      array('dt_leitura_nivel_servico',
                              'cd_nivel_servico',
                              'tx_justificativa_nivel_servico',
                              'dt_fechamento_demanda'=>'dt_fechamento_nivel_servico'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      'tx_nivel_servico',
                      $this->_schema);
        $select->where(new zend_db_expr("{$this->to_char('dt_demanda','MM')} = '{$mes}'"));
        $select->where(new zend_db_expr("{$this->to_char('dt_demanda','YYYY')} = '{$ano}'"));
        $select->where('dpns.st_fechamento_nivel_servico IS NOT NULL');
        $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
        $select->order('dt_demanda DESC');

        return $this->fetchAll($select)->toArray();

/*
		$sql = "select
					dem.cd_demanda,
					dem.tx_solicitante_demanda,
					dem.tx_demanda,
					dem.cd_objeto,
					dem.ni_solicitacao,
					dem.ni_ano_solicitacao,
					dem.dt_demanda,
					case
						when
							dem.ni_solicitacao is not null
						then
							dem.ni_solicitacao||'/'||dem.ni_ano_solicitacao
					else
						null
					end as tx_solicitacao,
					to_char(dem.dt_demanda, 'DD/MM/YYYY HH24:MI:SS') as dt_hora_demanda,
					dpns.dt_leitura_nivel_servico,
					dpns.cd_nivel_servico,
					dpns.tx_justificativa_nivel_servico,
					to_char(dpns.dt_fechamento_nivel_servico, 'DD/MM/YYYY HH24:MI:SS') as dt_fechamento_demanda,
					ns.tx_nivel_servico
				from
					{$this->_schema}.".KT_S_DEMANDA." as dem
				join
					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
				on
					dem.cd_demanda = dp.cd_demanda
				and
					cd_profissional = {$cd_profissional}
				join
					{$this->_schema}.".KT_A_DEMANDA_PROF_NIVEL_SERVICO." as dpns
				on
					dem.cd_demanda = dpns.cd_demanda
				and
					dp.cd_profissional = dpns.cd_profissional
				join
					{$this->_schema}.".KT_B_NIVEL_SERVICO." as ns
				on
					ns.cd_nivel_servico = dpns.cd_nivel_servico
				where
					to_char(dt_demanda,'MM') = '{$mes}'
				and
					to_char(dt_demanda,'YYYY') = '{$ano}'
				and
					dpns.st_fechamento_nivel_servico is not null
				order by
					dt_demanda desc";
*/
	}	
	
	public function atualizaDemanda($cd_demanda, $addRow)
	{
		$erros = false;
		
		$where      = array('cd_demanda = ?'=>$cd_demanda);
		$rowDemanda = $this->fetchRow($where);
		
		if (!is_null($rowDemanda)){
			if (!$this->update($addRow, $where)){
				$erros = true;
			}
		}
		return $erros;
	}	

	public function getDemandaAndamento($mes, $ano, $cd_objeto, $cd_profissional)
	{
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);
        $select->from(array('dem'=>$this->_name),
                      array('cd_demanda',
                            'dt_demanda',
                            'tx_solicitante_demanda',
                            'tx_demanda'),
                      $this->_schema);
        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                          'dem.cd_unidade = uni.cd_unidade',
                          array('tx_sigla_unidade'),
                          $this->_schema);
        $select->joinLeft(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                          'dem.cd_demanda = dp.cd_demanda',
                          array(),
                          $this->_schema);
        $select->join(array('sa'=>KT_B_STATUS_ATENDIMENTO),
                          'dem.cd_status_atendimento = sa.cd_status_atendimento',
                          array('tx_status_atendimento',
                                'tx_rgb_status_atendimento'),
                          $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_demanda', 'MM')} = '{$mes}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_demanda', 'YYYY')} = '{$ano}'"))
               ->where('dem.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('dem.ni_solicitacao IS NULL')
               ->where('dem.ni_ano_solicitacao IS NULL')
               ->where('dem.st_fechamento_demanda IS NULL')
               ->where('dem.st_conclusao_demanda IS NULL');
        $select->order('dt_demanda desc');

		if ( (!is_null($cd_profissional)) && (!empty($cd_profissional)) )
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();

//		$and = "";
//		if (!is_null($cd_profissional)){
//			$and = " and dp.cd_profissional = {$cd_profissional}";
//		}
//
//		$sql = "select
//					distinct
//					dem.cd_demanda,
//					to_char(dem.dt_demanda, 'DD/MM/YYYY HH24:MI:SS') as dt_demanda,
//					dem.tx_solicitante_demanda,
//					uni.tx_sigla_unidade,
//					dem.tx_demanda
//				from
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				left join
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				on
//					dem.cd_unidade = uni.cd_unidade
//				left join
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
//				on
//					dem.cd_demanda = dp.cd_demanda
//
//				where
//					to_char(dt_demanda,'MM') = '{$mes}'
//				and
//					to_char(dt_demanda,'YYYY') = '{$ano}'
//				and
//					dem.cd_objeto = {$cd_objeto}
//				and
//					ni_solicitacao is null
//				and
//					ni_ano_solicitacao is null
//				and
//					dem.st_fechamento_demanda is null
//				and
//					dem.st_conclusao_demanda is null
//				{$and}
//				order by
//					dt_demanda desc";
//
//		return $this->getDefaultAdapter()->fetchAll($sql);
	}

	//TODO Verifica esse SQL Depois
	public function getDemandaExecutada($mes, $ano, $cd_objeto, $cd_profissional)
	{
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);
        $select->from(array('dem'=>$this->_name),
                      array('cd_demanda',
                            'dt_demanda',
                            'tx_solicitante_demanda',
                            'tx_demanda'),
                      $this->_schema);
        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                          'dem.cd_unidade = uni.cd_unidade',
                          array('tx_sigla_unidade'),
                          $this->_schema);
        $select->joinLeft(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                          'dem.cd_demanda = dp.cd_demanda',
                          array(),
                          $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_demanda', 'MM')} = '{$mes}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_demanda', 'YYYY')} = '{$ano}'"))
               ->where('dem.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('dem.ni_solicitacao IS NULL')
               ->where('dem.ni_ano_solicitacao IS NULL')
               ->where('dem.st_fechamento_demanda IS NOT NULL')
               ->where('dem.st_conclusao_demanda IS NULL');

		if ( (!is_null($cd_profissional)) && (!empty($cd_profissional)) )
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);

        $select->order('dt_demanda desc');

        return $this->fetchAll($select)->toArray();
        
//		$and = "";
////		if (!is_null($cd_profissional)){
//		if (!is_null($cd_profissional) || (trim($cd_profissional) != '' )){
//			$and = " and dp.cd_profissional = {$cd_profissional}";
//		}
//
//		$sql = "select
//					distinct
//					dem.cd_demanda,
//					to_char(dem.dt_demanda, 'DD/MM/YYYY HH24:MI:SS') as dt_demanda,
//					dem.tx_solicitante_demanda,
//					uni.tx_sigla_unidade,
//					dem.tx_demanda
//				from
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				left join
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				on
//					dem.cd_unidade = uni.cd_unidade
//
//				left join
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
//				on
//					dem.cd_demanda = dp.cd_demanda
//				where
//                	to_char(dt_demanda,'MM') = '{$mes}'
//				and
//                	to_char(dt_demanda,'YYYY') = '{$ano}'
//				and
//                	dem.cd_objeto = {$cd_objeto}
//				and
//                    ni_solicitacao is null
//				and
//                    ni_ano_solicitacao is null
//				and
//                    dem.st_fechamento_demanda is not null
//				and
//                    dem.st_conclusao_demanda is null
//				{$and}
//				order by
//                    dt_demanda desc";
//		return $this->getDefaultAdapter()->fetchAll($sql);

	}
		
	public function getDemandaConcluida($mes, $ano, $cd_objeto, $cd_profissional)
	{
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('dem'=>$this->_name),
                      array('cd_demanda',
                            'dt_demanda',
                            'tx_solicitante_demanda',
                            'tx_demanda'),
                      $this->_schema);

        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                          'dem.cd_unidade = uni.cd_unidade',
                          array('tx_sigla_unidade'),
                          $this->_schema);

        $select->joinLeft(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                          'dem.cd_demanda = dp.cd_demanda',
                          array(),
                          $this->_schema);

        $select->where(new Zend_Db_Expr("{$this->to_char('dt_demanda', 'MM')} = '{$mes}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_demanda', 'YYYY')} = '{$ano}'"))
               ->where('dem.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('dem.ni_solicitacao IS NULL')
               ->where('dem.ni_ano_solicitacao IS NULL')
               ->where('dem.st_fechamento_demanda IS NOT NULL')
               ->where('dem.st_conclusao_demanda IS NOT NULL');

		if ( (!is_null($cd_profissional)) && (!empty($cd_profissional)) )
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);

        $select->order('dt_demanda desc');

        return $this->fetchAll($select)->toArray();

//		$and = "";
////		if (!is_null($cd_profissional)){
//		if (!is_null($cd_profissional) || (trim($cd_profissional) != '' )){
//			$and = " and dp.cd_profissional = {$cd_profissional}";
//		}
//
//		$sql = "select
//					distinct
//					dem.cd_demanda,
//					to_char(dem.dt_demanda, 'DD/MM/YYYY HH24:MI:SS') as dt_demanda,
//					dem.tx_solicitante_demanda,
//					uni.tx_sigla_unidade,
//					dem.tx_demanda
//				from
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				left join
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				on
//					dem.cd_unidade = uni.cd_unidade
//
//				left join
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
//				on
//					dem.cd_demanda = dp.cd_demanda
//
//				where
//					to_char(dt_demanda,'MM') = '{$mes}' 				and 					to_char(dt_demanda,'YYYY') = '{$ano}' 				and 					dem.cd_objeto = {$cd_objeto}
//				and
//					ni_solicitacao is null 				and  					ni_ano_solicitacao is null 				and 					dem.st_fechamento_demanda is not null
//				and
//					dem.st_conclusao_demanda is not null
//				{$and}
//				order by
//					dt_demanda desc";
//
//		return $this->getDefaultAdapter()->fetchAll($sql);
	}

    /**
     *
     * @param array $arrDados
     * @return array|Exception
     */
    public function salvarDemanda(Array $arrDados=array())
    {
        $arrReturn = array();
        if(count($arrDados) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_demanda'])){
            $arrReturn['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
            $row = $this->fetchRow(array("cd_demanda = ?" => $arrDados['cd_demanda']));
        }else{
            $arrReturn['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');

            $row = $this->createRow();
            $row->cd_demanda  = $this->getNextValueOfField('cd_demanda');
            $arrReturn['cd_demanda'] = $row->cd_demanda;
        }
        $row->cd_objeto              = $arrDados['cd_objeto'];
        $row->ni_ano_solicitacao     = $arrDados['ni_ano_solicitacao'];
        $row->ni_solicitacao         = $arrDados['ni_solicitacao'];
        $row->dt_demanda             = (!empty ($arrDados['dt_demanda'])) ? $arrDados['dt_demanda'] : null;
        $row->tx_demanda             = $arrDados['tx_demanda'];
        $row->cd_status_atendimento  = $arrDados['cd_status_atendimento'];
        $row->tx_solicitante_demanda = $arrDados['tx_solicitante_demanda'];
        $row->cd_unidade             = $arrDados['cd_unidade'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $arrReturn;
    }




}