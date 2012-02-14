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

class Controle extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_CONTROLE;
	protected $_primary  = array('cd_controle','cd_projeto_previsto','cd_projeto','cd_proposta','cd_contrato');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getHorasAlocadas($cd_projeto, $cd_proposta, $tipo, $st_modulo_proposta = false, $cd_contrato = null)
	{
        $select = $this->select()
                       ->from($this, array('ni_horas'=>new Zend_Db_Expr('SUM(ni_horas)')))
                       ->where('cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
                       ->where('st_controle = ?', $tipo);
		if ($st_modulo_proposta === false){
			$select->where('st_modulo_proposta IS NULL');
        }else{
			$select->where('st_modulo_proposta IS NOT NULL');
        }
		if (!is_null($cd_contrato)){
			$select->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        }
        $rowHoras = $this->fetchRow($select);
		
		return (!is_null($rowHoras->ni_horas)) ? $rowHoras->ni_horas : 0;
	}
	
	public function getHorasProjetoPrevisto($cd_projeto_previsto, $tipo)
	{
        $select = $this->select()
                       ->from($this, array('ni_horas'=>new Zend_Db_Expr('SUM(ni_horas)')))
                       ->where('cd_projeto_previsto  = ?', $cd_projeto_previsto, Zend_Db::INT_TYPE)
                       ->where('st_controle = ?', $tipo);

        $rowHoras = $this->fetchRow($select);
        
		return (!is_null($rowHoras->ni_horas)) ? round($rowHoras->ni_horas,1) : 0;
	}
	
	public function registraControle($st_modulo_proposta, $st_controle, $cd_contrato, $cd_projeto_previsto, $cd_projeto, $ni_horas, $cd_proposta)
	{
		$erros  = false;
		$addRow = array();
		$addRow['cd_controle']         = $this->getNextValueOfField('cd_controle');
		$addRow['cd_projeto_previsto'] = $cd_projeto_previsto;
		$addRow['cd_projeto']          = $cd_projeto;
		$addRow['cd_proposta']         = $cd_proposta;
		$addRow['cd_contrato']         = $cd_contrato;
		$addRow['ni_horas']            = $ni_horas;
		$addRow['st_controle']         = $st_controle;
		$addRow['st_modulo_proposta']  = $st_modulo_proposta;
		$addRow['dt_lancamento']       = date("Y-m-d H:i:s");
		$addRow['cd_profissional']     = $_SESSION['oasis_logged'][0]['cd_profissional'];
		
	    if(!$this->insert($addRow)){
	    	$erros = true;
	    }
	    return $erros;
	}
	
	public function getPropostasAlocarRecursoContratoAnterior($cd_contrato)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('prop'=>KT_S_PROPOSTA),
                      array('cd_projeto','cd_proposta','ni_horas_proposta'),
                      $this->_schema);
        $select->join(array('c'=>$this->_name),
                      '(prop.cd_proposta = c.cd_proposta) AND (prop.cd_projeto = c.cd_projeto)',
                      array('alocado'  => new Zend_Db_Expr("SUM(CASE c.st_controle WHEN 'D' THEN c.ni_horas WHEN 'C' THEN c.ni_horas*(-1) ELSE 0 END)"),
                            'diferenca'=> new Zend_Db_Expr("prop.ni_horas_proposta - (SUM(CASE c.st_controle WHEN 'D' THEN c.ni_horas WHEN 'C' THEN c.ni_horas*(-1) ELSE 0 END))")),
                      $this->_schema);
        $select->join(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      '(procprop.cd_projeto = prop.cd_projeto) AND (procprop.cd_proposta = prop.cd_proposta)',
                      array(),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(prop.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(cp.cd_projeto = procprop.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->where('cp.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)
               ->where('c.st_modulo_proposta IS NULL')
               ->where('procprop.st_homologacao_proposta IS NOT NULL')
               ->where('prop.st_encerramento_proposta IS NULL')
               ->where('procprop.st_ativo = ?', 'S')
               ->where('c.cd_contrato <> ?', $cd_contrato, Zend_Db::INT_TYPE)
               ->where('prop.cd_projeto NOT IN ?', $this->select()
                                                        ->distinct()
                                                        ->from($this, 'cd_projeto')
                                                        ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE));
        $select->group(array('prop.cd_projeto','proj.tx_sigla_projeto','prop.cd_proposta','ni_horas_proposta'));
        $select->having(new Zend_Db_Expr("ni_horas_proposta <> (SUM(CASE st_controle WHEN 'D' THEN ni_horas WHEN 'C' THEN ni_horas*(-1) ELSE 0 END))"));
        $select->order('tx_sigla_projeto');

        return $this->fetchAll($select)->toArray();
/*
		$sql = "SELECT 
					prop.cd_projeto,
					prop.cd_proposta,
					prop.ni_horas_proposta,
					proj.tx_sigla_projeto,
					sum(case c.st_controle when 'D' then c.ni_horas when 'C' then c.ni_horas*(-1) else 0 end) as alocado,
					prop.ni_horas_proposta - (sum(case c.st_controle when 'D' then c.ni_horas when 'C' then c.ni_horas*(-1) else 0 end)) as diferenca
				FROM
					{$this->_schema}.".KT_S_PROPOSTA." as prop
				join
					{$this->_schema}.{$this->_name} as c
				on
					prop.cd_proposta = c.cd_proposta
				and
					prop.cd_projeto = c.cd_projeto
				join
					{$this->_schema}.".KT_S_PROCESSAMENTO_PROPOSTA." as procprop
				on
					procprop.cd_projeto = prop.cd_projeto
				and
					procprop.cd_proposta = prop.cd_proposta
				join
					{$this->_schema}.".KT_S_PROJETO." as proj
				on
					prop.cd_projeto = proj.cd_projeto
				join
					{$this->_schema}.".KT_A_CONTRATO_PROJETO." as cp
				on
					cp.cd_contrato = {$cd_contrato}
				and
					cp.cd_projeto = procprop.cd_projeto
				where
					c.st_modulo_proposta is null
				and
					procprop.st_homologacao_proposta is not null
				and
					prop.st_encerramento_proposta is null
				and
					procprop.st_ativo = 'S'
				and
					c.cd_contrato <> {$cd_contrato}
				and
					prop.cd_projeto not in (
					select
						distinct cd_projeto
					from
						{$this->_schema}.{$this->_name}
					where
						cd_contrato = {$cd_contrato})
				group by
					prop.cd_projeto,
					proj.tx_sigla_projeto,
					prop.cd_proposta,
					ni_horas_proposta
				having
					ni_horas_proposta <> (sum(case st_controle when 'D' then ni_horas when 'C' then ni_horas*(-1) else 0 end))
				order by
					tx_sigla_projeto";
		return $this->getDefaultAdapter()->fetchAll($sql);
*/
	}
	
	public function getPropostasDesalocarRecurso($cd_contrato, $mes_inicial, $ano_inicial, $mes_final, $ano_final)
	{
		$mes_ano_inicial = ($ano_inicial*100)+$mes_inicial;
		$mes_ano_final   = ($ano_final*100)+$mes_final;

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('alocacao'=>$this->select()
                                             ->from($this,
                                                    array('cd_projeto',
                                                          'cd_proposta',
                                                          'alocado'=>new Zend_Db_Expr("SUM(CASE st_controle WHEN 'D' THEN ni_horas WHEN 'C' THEN ni_horas*(-1) END)")))
                                             ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)
                                             ->group(array('cd_projeto','cd_proposta'))),
                      array('cd_projeto',
                            'cd_proposta',
                            'alocado',
                            'diferenca'=>new Zend_Db_Expr('executado-alocado')));
        $select->joinRight(array('execucao'=>$this->select()
                                                  ->setIntegrityCheck(false)
                                                  ->from(array('t'=>$this->select()
                                                                         ->setIntegrityCheck(false)
                                                                         ->from(array('par'=>KT_S_PARCELA),
                                                                                array('cd_projeto','cd_proposta','ni_horas_parcela'),
                                                                                $this->_schema)
                                                                         ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                                                '(procpar.cd_projeto = par.cd_projeto) AND (procpar.cd_proposta = par.cd_proposta) AND (procpar.cd_parcela = par.cd_parcela)',
                                                                                array(),
                                                                                $this->_schema)
                                                                         ->where(new Zend_Db_Expr("(ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela BETWEEN {$mes_ano_inicial} AND {$mes_ano_final}"))
                                                                         ->where('procpar.st_ativo = ?', 'S')
                                                                         ->where('st_homologacao_parcela IS NOT NULL')),
                                                         array('cd_projeto',
                                                               'cd_proposta',
                                                               'executado'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')))
                                                  ->group(array('cd_projeto','cd_proposta'))),
                           '(alocacao.cd_projeto = execucao.cd_projeto) AND (alocacao.cd_proposta = execucao.cd_proposta)',
                           'executado');
        $select->join(array('proj'=>KT_S_PROJETO),
                      'alocacao.cd_projeto = proj.cd_projeto',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->where(new Zend_Db_Expr('(executado-alocado) <> 0'));
        $select->order(array('proj.tx_sigla_projeto','alocacao.cd_proposta'));

        return $this->fetchAll($select)->toArray();

/*
		$sql = "select 
					alocacao.cd_projeto,
					alocacao.cd_proposta,
					alocado,
					executado,
					executado-alocado as diferenca,
					proj.tx_sigla_projeto
				from
				(
					select
						cd_projeto,
						cd_proposta,
						sum(case st_controle when 'D' then ni_horas when 'C' then ni_horas*(-1) end) as alocado
					from
						{$this->_schema}.{$this->_name}
					where
						cd_contrato = {$cd_contrato}
					group by
					cd_projeto, cd_proposta
				)  as alocacao
				right join
				(
					select
						cd_projeto,
						cd_proposta,
						sum(ni_horas_parcela) as executado
					from
					(
						SELECT
							par.cd_projeto,
							par.cd_proposta,
							par.ni_horas_parcela
						FROM
							{$this->_schema}.".KT_S_PARCELA." as par
						join
							{$this->_schema}.".KT_S_PROCESSAMENTO_PARCELA." as procpar
						on
							procpar.cd_projeto = par.cd_projeto
						and
							procpar.cd_proposta = par.cd_proposta
						and
							procpar.cd_parcela = par.cd_parcela
						WHERE
							(ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela between {$mes_ano_inicial} and {$mes_ano_final}
						and
							procpar.st_ativo = 'S'
						and
							st_homologacao_parcela is not null
					) as t
					group by
						cd_projeto,
						cd_proposta
				) as execucao
				on
					alocacao.cd_projeto = execucao.cd_projeto
				and
					alocacao.cd_proposta = execucao.cd_proposta
				join
					{$this->_schema}.".KT_S_PROJETO." as proj
				on
					alocacao.cd_projeto = proj.cd_projeto
				where 
					(executado-alocado) <> 0
				order by
					proj.tx_sigla_projeto, 
					alocacao.cd_proposta";
*/
	}

	public function getProjetoPrevistoQueCedeuRecurso($cd_projeto, $cd_proposta, $cd_contrato)
	{
        $select = $this->select()
                ->distinct()
                ->from($this, 'cd_projeto_previsto')
                ->where('cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                ->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
                ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

		return $this->fetchAll($select)->toArray();
	}

	public function contaDiasUteis($data, $tipo)
	{
		$dia  = (int)substr(trim($data),0,2);
		$mes  = (int)substr(trim($data),3,2);
		$ano  = (int)substr(trim($data),6,4);
		$cont = 0;

		if ($ano % 4 == 0) {
			$mes_array = array(31,29,31,30,31,30,31,31,30,31,30,31);
		} else {
			$mes_array = array(31,28,31,30,31,30,31,31,30,31,30,31);
		}

		if ($tipo == "I") {
			for ($i=$dia;$i<=$mes_array[$mes-1];$i++) {
				 $DiaSemana = date("w", mktime(0,0,0,$mes,$i,$ano))+1;
				 if ($DiaSemana != 1 && $DiaSemana != 7) {
				 	$cont = $cont + 1;
				 }
			}
		}

		if ($tipo == "F") {
			for ($i=1;$i<=$dia;$i++){
				 $DiaSemana = date("w", mktime(0,0,0,$mes,$i,$ano))+1;
				 if ($DiaSemana != 1 && $DiaSemana != 7) {
				 	$cont = $cont + 1;
				 }
			}
		}
		return $cont;
	}

	public function calculaSomaHorasParcelaMes($ni_horas_parcela, $data, $tipo)
	{
		$soma_mes = 0;

		if (!is_null($ni_horas_parcela)){
			$fator = $this->contaDiasUteis($data, $tipo);
			if ($fator == 0){
				$soma_mes = 0;
			} else {
				$fator    = round((($fator * 100)/$fator)/100);
				$soma_mes = (float)$ni_horas_parcela * $fator;
			}
		}

		return $soma_mes;
	}

    public function getTotalPropostasNovasEvolutivas($cd_contrato, $tipo)
	{
        $subSelect = $this->select()
                          ->distinct()
                          ->from($this, array('cd_projeto','cd_proposta'))
                          ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        if($tipo === 'N'){
            $subSelect->where('cd_proposta = ?', 1); //propostas novas
        }else{
            $subSelect->where('cd_proposta > ?', 1); //propostas evolutivas
        }

        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('t'=>$subSelect),
                      array('qtde_propostas'=>new Zend_Db_Expr('count(*)')));

		return $this->fetchRow($select);
	}
}