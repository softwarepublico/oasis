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

class Contrato extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_CONTRATO;
	protected $_primary  = 'cd_contrato';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getContrato($comSelecione = false, $contratoAdministrador = false, $comInativos = false)
    {
		$arrStContrato = array('A');
		if($comInativos === true){
            $arrStContrato[] = 'I';
        }

        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from($this,
                      array('cd_contrato','tx_numero_contrato'));
        $select->where('st_contrato in (?)', $arrStContrato);
		$select->order(array(new Zend_Db_Expr("{$this->substringOrderNumeroContrato()}")));

		if(!$contratoAdministrador){
            $select->where('cd_contrato <> ?', 0);
        }

		$res = $this->fetchAll($select);
		
		$arrContrato = array();
		if ($comSelecione) {
			$arrContrato[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($res as  $key=>$valor){
			$arrContrato[$valor->cd_contrato] = $valor->tx_numero_contrato;
		}
		return $arrContrato;
	}
	
	public function getContratoPorTipoDeObjeto($comSelecione = false, $tipoObjeto, $cd_contrato=null, $comDescStatus=false, $status=null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('con'=>$this->_name),
                      array('cd_contrato',
                            'tx_numero_contrato',
                            'status'=>new Zend_Db_Expr("CASE con.st_contrato WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ATIVO')."'
                                                                             WHEN 'I' THEN '".Base_Util::getTranslator('L_SQL_INATIVO')."' END")),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(con.cd_contrato = obj.cd_contrato)',
                      'tx_objeto',
                      $this->_schema);
        $select->where('st_objeto_contrato = ?', $tipoObjeto);
        $select->order(array(new Zend_Db_Expr("{$this->substringOrderNumeroContrato()}")));

		if( !is_null($cd_contrato) )
            $select->where('con.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
		if( !is_null($status) )
            $select->where('con.st_contrato = ?', $status);

		$res = $this->fetchAll($select);
		
		$arrContrato = array();
		if ($comSelecione === true) {
			$arrContrato[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($res as  $valor) {
			$descStatus = ($comDescStatus === true) ? " - (".$valor->status.")": '';
			$arrContrato[$valor->cd_contrato] = "{$valor->tx_numero_contrato} - {$valor->tx_objeto}{$descStatus}";
		}
		return $arrContrato;
	}
	
	public function getContratoAtivoObjeto($comSelecione = false, $tipoObjeto = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('con'=>$this->_name),
                      array('cd_contrato','tx_numero_contrato'),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(con.cd_contrato = obj.cd_contrato)',
                      'tx_objeto',
                      $this->_schema);
        $select->where('con.cd_contrato <> ?', 0)
               ->where('st_contrato = ?', 'A');
		$select->order(array(new Zend_Db_Expr("{$this->substringOrderNumeroContrato()}")));
		
		if (!is_null($tipoObjeto))
			$select->where('st_objeto_contrato = ?', $tipoObjeto);
		
		$res = $this->fetchAll($select);
		
		$arrContrato = array();
		if ($comSelecione === true) {
			$arrContrato[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($res as  $valor) {
			$arrContrato[$valor->cd_contrato] = $valor->tx_numero_contrato." - ".$valor->tx_objeto;
		}
		return $arrContrato;
	}	
	
	public function getDadosContratoAtivoObjetoTipoProjeto($cd_projeto)
    {
		$select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('con'=>$this->_name),
                      '*',
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(con.cd_contrato = obj.cd_contrato)',
                      array('cd_objeto','tx_objeto'),
                      $this->_schema);
        $select->join(array('cp'=>$this->select()
                                       ->setIntegrityCheck(false)
                                       ->from(KT_A_CONTRATO_PROJETO, 'cd_contrato', $this->_schema)
                                       ->where('cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE)),
                      '(cp.cd_contrato = con.cd_contrato)',
                      array());
        $select->where('st_contrato        = ?', 'A');
        $select->where('st_objeto_contrato = ?', 'P');

        return $this->fetchRow($select)->toArray();
/*
		$sql = "select 
					  con.cd_contrato,
					  con.cd_empresa,
					  con.tx_numero_contrato,
					  to_char(con.dt_inicio_contrato,'DD/MM/YYYY') as dt_inicio_contrato,
					  to_char(con.dt_fim_contrato,'DD/MM/YYYY') as dt_fim_contrato,
					  con.st_aditivo,
					  con.tx_cpf_gestor,
					  con.ni_horas_previstas,
					  con.tx_objeto,
					  con.tx_gestor_contrato,
					  con.tx_fone_gestor_contrato,
					  con.tx_numero_processo,
					  con.tx_obs_contrato,
					  con.tx_localizacao_arquivo,
					  con.tx_co_gestor,
					  con.tx_cpf_co_gestor,
					  con.tx_fone_co_gestor_contrato,
					  con.st_contrato,
					  con.ni_mes_inicial_contrato,
					  con.ni_ano_inicial_contrato,
					  con.ni_mes_final_contrato,
					  con.ni_ano_final_contrato,
					  con.ni_qtd_meses_contrato,
					  con.nf_valor_unitario_hora,
					  con.nf_valor_contrato,
					  obj.cd_objeto,
					  obj.tx_objeto
				from 
					{$this->_schema}.{$this->_name} as con
				join 
					{$this->_schema}.".KT_S_OBJETO_CONTRATO." as obj
				on
					con.cd_contrato = obj.cd_contrato
				join
				(
				select 
					cd_contrato
				from
					{$this->_schema}.".KT_A_CONTRATO_PROJETO."
				where
					cd_projeto = {$cd_projeto}
				) as cp
				on
					cp.cd_contrato = con.cd_contrato
				where 
					st_contrato = 'A'
				and
					st_objeto_contrato = 'P'
				order by
					substring(tx_numero_contrato,position('/' in tx_numero_contrato)+1,4) desc,
					substring(tx_numero_contrato,1,position('/' in tx_numero_contrato)-1) desc";

		return $this->getDefaultAdapter()->fetchRow($sql);
*/
	}

	public function getContratoEmpresa($cd_empresa = null, $st_contrato = null)
    {
/*
		$sql = " select c.cd_empresa,
						c.cd_contrato,
						c.tx_numero_contrato,
						to_char(c.dt_inicio_contrato,'DD/MM/YYYY') as dt_inicio_contrato,
						to_char(c.dt_fim_contrato,'DD/MM/YYYY') as dt_fim_contrato,
						e.tx_empresa
				from {$this->_schema}.{$this->_name}	     as c
				inner join {$this->_schema}.".KT_S_EMPRESA." as e ON (c.cd_empresa = e.cd_empresa)
				{$where}
				order by
					substring(tx_numero_contrato,position('/' in tx_numero_contrato)+1,4) desc
					--,substring(tx_numero_contrato,1,position('/' in tx_numero_contrato)-1) desc";
*/
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('c'=>$this->_name),
                      array('cd_empresa',
                            'cd_contrato',
                            'tx_numero_contrato',
                            'dt_inicio_contrato'=>new Zend_Db_Expr("{$this->to_char('dt_inicio_contrato', 'DD/MM/YYYY')}"),
                            'dt_fim_contrato'=>new Zend_Db_Expr("{$this->to_char('dt_fim_contrato', 'DD/MM/YYYY')}")),
                      $this->_schema);
        $select->join(array('e'=>KT_S_EMPRESA),
                      '(c.cd_empresa = e.cd_empresa)',
                      'tx_empresa',
                      $this->_schema);

		$select->order(array(new Zend_Db_Expr("{$this->substringOrderNumeroContrato()}")));
		
		if(!is_null($cd_empresa)){
            $select->where('c.cd_empresa = ?', $cd_empresa, Zend_Db::INT_TYPE);
		}
		if(!is_null($st_contrato)){
            $select->where('c.st_contrato = ?', $st_contrato);
		}

        return $this->fetchAll($select)->toArray();
	}
	
	public function getDadosContrato($cd_contrato)
    {
		return $this->fetchAll(array("cd_contrato = ?"=>$cd_contrato))->toArray();
	}
	
	public function getSomaHorasExecutadasContrato($mes_inicial, $mes_final, $ano_inicial, $ano_final, $cd_contrato)
    {
		$mes_ano_inicial = $ano_inicial.substr("00".$mes_inicial,-2);
		$mes_ano_final   = $ano_final.substr("00".$mes_final,-2);

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('t'=>$this->select()
                                      ->setIntegrityCheck(false)
                                      ->from(array('parc'=>KT_S_PARCELA),
                                             array('ni_horas_parcela'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')),
                                             $this->_schema)
                                      ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                             '(parc.cd_projeto = procpar.cd_projeto) AND (parc.cd_proposta = procpar.cd_proposta) AND (parc.cd_parcela = procpar.cd_parcela)',
                                             array(),
                                             $this->_schema)
                                      ->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                                             '(parc.cd_projeto = cp.cd_projeto)',
                                             array(),
                                             $this->_schema)
                                      ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                                             '(procpar.cd_objeto_execucao = oc.cd_objeto) AND (oc.cd_contrato = cp.cd_contrato)',
                                             array(),
                                             $this->_schema)
                                      ->where(new Zend_Db_Expr("(ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela BETWEEN {$mes_ano_inicial} AND {$mes_ano_final}"))
                                      ->where('procpar.st_ativo = ?', 'S')
                                      ->where('procpar.st_homologacao_parcela IS NOT NULL')
                                      ->where('cp.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                      array('ni_horas_parcela'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')));

        return $this->fetchRow($select)->toArray();
	}
	
	public function somaHorasAlocadasContrato($cd_contrato)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('t'=>$this->select()
                                      ->setIntegrityCheck(false)
                                      ->from(KT_A_CONTROLE,
                                             array('cd_projeto',
                                                   'cd_proposta',
                                                   'debito' =>new Zend_Db_Expr("SUM(CASE st_controle  WHEN 'D' THEN ni_horas ELSE 0 END)"),
                                                   'credito'=>new Zend_Db_Expr("SUM(CASE st_controle  WHEN 'C' THEN ni_horas ELSE 0 END)"),
                                                   'alocado'=>new Zend_Db_Expr("(SUM(CASE st_controle WHEN 'D' THEN ni_horas
                                                                                                      WHEN 'C' THEN ni_horas*(-1) ELSE 0 END))")),
                                             $this->_schema)
                                      ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)
                                      ->group(array('cd_projeto','cd_proposta'))),
                      array('total_alocado'=>new Zend_Db_Expr('SUM(alocado)')));

        return$this->fetchRow($select)->toArray();
	}
	
	public function somaHorasMesRestante($mes, $ano, $cd_contrato)
    {
		$select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('par'=>KT_S_PARCELA),
                      array('ni_horas_parcela'=>new Zend_Db_Expr('SUM(par.ni_horas_parcela)')),
                      $this->_schema);
        $select->joinLeft(array('parcelas_ativas'=>$this->select()
                                                        ->setIntegrityCheck(false)
                                                        ->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                               array('cd_projeto','cd_proposta','cd_parcela','st_homologacao_parcela'),
                                                               $this->_schema)
                                                        ->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                                                               '(procpar.cd_projeto = cp.cd_projeto)',
                                                               'cd_contrato',
                                                               $this->_schema)
                                                        ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                                                               '(procpar.cd_objeto_execucao = oc.cd_objeto) AND (oc.cd_contrato = cp.cd_contrato)',
                                                               array(),
                                                               $this->_schema)
                                                        ->where('procpar.st_ativo = ?','S')
                                                        ->where('cp.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                '(par.cd_projeto = parcelas_ativas.cd_projeto) AND (par.cd_proposta = parcelas_ativas.cd_proposta) AND (par.cd_parcela = parcelas_ativas.cd_parcela)',
                array());
        $select->where('parcelas_ativas.st_homologacao_parcela IS NULL')
               ->where('par.ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('par.ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('parcelas_ativas.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $this->fetchRow($select)->toArray();
	}
	
	public function somaHorasMesRestanteNaoAlocado($mes, $ano, $cd_contrato)
    {
        $select	= $this->select()->setIntegrityCheck(false);

        $select->from(array('par'=>KT_S_PARCELA),
                      array('ni_horas_parcela'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')),
                      $this->_schema);
        $select->join(array('t'=>$this->select()
                                      ->setIntegrityCheck(false)
                                      ->from(array('a'=>$this->select()
                                                             ->setIntegrityCheck(false)
                                                             ->distinct()
                                                             ->from(array('parc'=>KT_S_PARCELA),
                                                                    array('cd_projeto','cd_proposta'),
                                                                    $this->_schema)
                                                             ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                                    '(parc.cd_projeto = procpar.cd_projeto) AND (parc.cd_proposta = procpar.cd_proposta) AND (parc.cd_parcela = procpar.cd_parcela)',
                                                                    array(),
                                                                    $this->_schema)
                                                             ->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                                                                    '(parc.cd_projeto = cp.cd_projeto)',
                                                                    'cd_contrato',
                                                                    $this->_schema)
                                                             ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                                                                    '(procpar.cd_objeto_execucao = oc.cd_objeto) AND (oc.cd_contrato = cp.cd_contrato)',
                                                                    array(),
                                                                    $this->_schema)
                                                             ->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
                                                             ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
                                                             ->where('cp.cd_contrato          = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                                             array('cd_projeto','cd_proposta','cd_contrato'))
                                      ->joinLeft(array('b'=>$this->select()
                                                                 ->setIntegrityCheck(false)
                                                                 ->distinct()
                                                                 ->from(KT_A_CONTROLE,
                                                                        array('cd_projeto','cd_proposta','cd_contrato'),
                                                                        $this->_schema)
                                                                 ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                                                 '(a.cd_projeto = b.cd_projeto) AND (a.cd_proposta = b.cd_proposta) AND (a.cd_projeto = b.cd_projeto)',
                                                 array())
                                      ->where('b.cd_projeto  IS NULL')
                                      ->where('b.cd_proposta IS NULL')
                                      ->where('b.cd_contrato IS NULL')),
                      '(par.cd_projeto = t.cd_projeto) AND (par.cd_proposta = t.cd_proposta)',
                      array());
        $select->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('t.cd_contrato           = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $this->fetchRow($select)->toArray();
/*
		$sql = "
                SELECT
                     sum(ni_horas_parcela) as ni_horas_parcela
                FROM
                    {$this->_schema}.".KT_S_PARCELA." as par
                JOIN
                (
                        SELECT
                                a.cd_projeto,
                                a.cd_proposta,
                                a.cd_contrato
                        FROM
                        (	SELECT
                                        DISTINCT
                                        parc.cd_projeto,
                                        parc.cd_proposta,
                                        cp.cd_contrato
                                FROM {$this->_schema}.".KT_S_PARCELA." as parc
                                JOIN {$this->_schema}.".KT_S_PROCESSAMENTO_PARCELA." as procpar on parc.cd_projeto = procpar.cd_projeto and parc.cd_proposta = procpar.cd_proposta and parc.cd_parcela = procpar.cd_parcela
                                JOIN {$this->_schema}.".KT_A_CONTRATO_PROJETO." as cp on parc.cd_projeto = cp.cd_projeto
                                JOIN {$this->_schema}.".KT_S_OBJETO_CONTRATO." as oc on procpar.cd_objeto_execucao = oc.cd_objeto and oc.cd_contrato = cp.cd_contrato
                                WHERE
                                        ni_ano_previsao_parcela = {$ano} and
                                        ni_mes_previsao_parcela = {$mes} and
                                        cp.cd_contrato = {$cd_contrato}
                    ) as a
                        LEFT JOIN
                        (	SELECT
                                        DISTINCT
                                        cd_projeto,
                                        cd_proposta,
                                        cd_contrato
                                FROM {$this->_schema}.".KT_A_CONTROLE."
                                WHERE cd_contrato = {$cd_contrato}
                    ) as b
                        ON
                                a.cd_projeto  = b.cd_projeto AND
                                a.cd_proposta = b.cd_proposta AND
                                a.cd_projeto  = b.cd_projeto
                        WHERE
                                b.cd_projeto is null  AND
                                b.cd_proposta is null AND
                                b.cd_contrato is null
                ) as t
                on par.cd_projeto = t.cd_projeto and par.cd_proposta = t.cd_proposta
                WHERE
                        ni_ano_previsao_parcela = {$ano} and
                        ni_mes_previsao_parcela = {$mes} and
                        t.cd_contrato = {$cd_contrato} ";
*/
	}
	
	public function somaHorasMesRestanteAlocado($mes, $ano, $cd_contrato)
    {
		$select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('par'=>KT_S_PARCELA),
                      array('ni_horas_parcela'=>new Zend_Db_Expr("SUM(ni_horas_parcela)")),
                      $this->_schema);
        $select->join(array('t'=>$this->select()
                                      ->setIntegrityCheck(false)
                                      ->distinct()
                                      ->from(array('a'=>$this->select()
                                                             ->setIntegrityCheck(false)
                                                             ->distinct()
                                                             ->from(array('parc'=>KT_S_PARCELA),
                                                                    array('cd_projeto','cd_proposta'),
                                                                    $this->_schema)
                                                             ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                                    '(parc.cd_projeto = procpar.cd_projeto) AND (parc.cd_proposta = procpar.cd_proposta) AND (parc.cd_parcela = procpar.cd_parcela)',
                                                                    array(),
                                                                    $this->_schema)
                                                             ->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                                                                    '(parc.cd_projeto = cp.cd_projeto)',
                                                                    'cd_contrato',
                                                                    $this->_schema)
                                                             ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                                                                    '(procpar.cd_objeto_execucao = oc.cd_objeto) AND (oc.cd_contrato = cp.cd_contrato)',
                                                                    array(),
                                                                    $this->_schema)
                                                             ->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
                                                             ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
                                                             ->where('cp.cd_contrato          = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                                             array('cd_projeto','cd_proposta','cd_contrato'))

                                      ->join(array('b'=>$this->select()
                                                             ->setIntegrityCheck(false)
                                                             ->distinct()
                                                             ->from(KT_A_CONTROLE,
                                                                    array('cd_projeto','cd_proposta','cd_contrato'),
                                                                    $this->_schema)
                                                             ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                                             '(a.cd_projeto  = b.cd_projeto) AND (a.cd_proposta = b.cd_proposta) AND (a.cd_contrato = b.cd_contrato)',
                                             array())),
                      '(par.cd_projeto = t.cd_projeto) AND (par.cd_proposta = t.cd_proposta)',
                      array());
        $select->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('t.cd_contrato           = ?', $cd_contrato, Zend_Db::INT_TYPE);
        
        return $this->fetchRow($select)->toArray();
/*
        $sql = "
            SELECT
                sum(ni_horas_parcela) as ni_horas_parcela
            FROM
                {$this->_schema}.".KT_S_PARCELA." as par
            JOIN (
                    SELECT
                        a.cd_projeto,
                        a.cd_proposta,
                        a.cd_contrato
                    FROM
                    (
                    SELECT
                        DISTINCT
                        parc.cd_projeto,
                        parc.cd_proposta,
                        cp.cd_contrato
                    FROM {$this->_schema}.".KT_S_PARCELA." as parc
                    JOIN {$this->_schema}.".KT_S_PROCESSAMENTO_PARCELA." as procpar ON parc.cd_projeto = procpar.cd_projeto and parc.cd_proposta = procpar.cd_proposta and parc.cd_parcela = procpar.cd_parcela
                    JOIN {$this->_schema}.".KT_A_CONTRATO_PROJETO." as cp ON parc.cd_projeto = cp.cd_projeto
                    JOIN {$this->_schema}.".KT_S_OBJETO_CONTRATO." as oc ON procpar.cd_objeto_execucao = oc.cd_objeto and oc.cd_contrato = cp.cd_contrato
                    WHERE
                        ni_ano_previsao_parcela = {$ano} and
                        ni_mes_previsao_parcela = {$mes}   and
                        cp.cd_contrato          = {$cd_contrato}
                    ) as a
                    JOIN
                    (
                    SELECT
                        DISTINCT
                        cd_projeto,
                        cd_proposta,
                        cd_contrato
                    FROM
                        {$this->_schema}.".KT_A_CONTROLE."
                    WHERE
                        cd_contrato = 9
                    ) as b
                    ON
                            a.cd_projeto  = b.cd_projeto
                    AND
                            a.cd_proposta = b.cd_proposta
                    AND
                            a.cd_contrato = b.cd_contrato
                ) as t
            on
                par.cd_projeto = t.cd_projeto
            and
                par.cd_proposta = t.cd_proposta
            WHERE
                ni_ano_previsao_parcela = {$ano}
            and
                ni_mes_previsao_parcela = {$mes}
            and
                t.cd_contrato = {$cd_contrato} ";
*/
	}
	
	public function diferencaAlocacaoAlteracaoProposta($cd_contrato, $mes_inicial, $ano_inicial, $mes_final, $ano_final)
    {
		$mes_ano_inicial = $ano_inicial.substr("00".$mes_inicial,-2);
		$mes_ano_final   = $ano_final.substr("00".$mes_final,-2);

        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('t'=>$this->select()
                                      ->setIntegrityCheck(false)
                                      ->from(array('p'=>KT_S_PARCELA),
                                             array('cd_projeto',
                                                   'cd_proposta',
                                                   'ni_horas_parcela'=>new Zend_Db_Expr('SUM(ni_horas_parcela)'),
                                                   'diferenca'=>new Zend_Db_Expr('(sum(ni_horas_parcela)-alocado)')),
                                             $this->_schema)
                                      ->join(array('c'=>$this->select()
                                                             ->setIntegrityCheck(false)
                                                             ->from(KT_A_CONTROLE,
                                                                    array('cd_projeto',
                                                                          'cd_proposta',
                                                                          'alocado'=>new Zend_Db_Expr("SUM(CASE st_controle WHEN 'D' THEN ni_horas WHEN 'C' THEN ni_horas*(-1) END)")),
                                                                    $this->_schema)
                                                             ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)
                                                             ->group(array('cd_projeto','cd_proposta'))),
                                             '(p.cd_projeto = c.cd_projeto) AND (p.cd_proposta = c.cd_proposta)',
                                             'alocado')
                                      ->join(array('dc'=>$this->select()
                                                              ->setIntegrityCheck(false)
                                                              ->distinct()
                                                              ->from(KT_A_CONTROLE,
                                                                     array('cd_projeto','cd_proposta'),
                                                                     $this->_schema)
                                                              ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)
                                                  ),
                                             '(p.cd_projeto = dc.cd_projeto) AND (p.cd_proposta = dc.cd_proposta)',
                                             array())
                                      ->where(new Zend_Db_Expr("ni_ano_previsao_parcela*100+ni_mes_previsao_parcela BETWEEN {$mes_ano_inicial} AND {$mes_ano_final}"))
                                      ->group(array('p.cd_projeto','p.cd_proposta','alocado'))


                           ),
                      array('diferenca'=>new Zend_Db_Expr('SUM(diferenca)')));

        return $this->fetchRow($select)->toArray();
	}

	public function getAllDadosContrato($cd_contrato)
    {
		return $this->fetchAll($this->select()
                                    ->where("cd_contrato = ?", $cd_contrato, Zend_Db::INT_TYPE)
                              )->toArray();
	}

	public function getTodosContratos($comSelecione = false, $comDescStatus = false)
    {

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('con'=>$this->_name),
                      array('cd_contrato',
                            'tx_numero_contrato',
                            'status'=>new Zend_Db_Expr("CASE con.st_contrato WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ATIVO')."'
                                                                             WHEN 'I' THEN '".Base_Util::getTranslator('L_SQL_INATIVO')."' END")),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      'con.cd_contrato = obj.cd_contrato',
                      array('tx_objeto','cd_objeto'),
                      $this->_schema);
        $select->where('con.cd_contrato <> ?', 0);
		$select->order(array(new Zend_Db_Expr("{$this->substringOrderNumeroContrato()}")));
		
		$rowSet = $this->fetchAll($select);

		$arrContrato = array();
		if ($comSelecione) {
			$arrContrato[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($rowSet as $valor) {
			$descStatus = ($comDescStatus ===true) ? " - (".$valor->status.")": '';
			$arrContrato[$valor->cd_contrato."_".$valor->cd_objeto] = "{$valor->tx_numero_contrato} - {$valor->tx_objeto}{$descStatus}";
		}
		return $arrContrato;
	}
}