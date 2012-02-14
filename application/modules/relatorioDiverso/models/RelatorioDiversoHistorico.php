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

class RelatorioDiversoHistorico extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
    public function getHistorico($cd_projeto, $cd_proposta=null, $cd_profissional=null, $dt_inicio=null,$dt_fim=null)
    {
        $objHistorico = new Historico();

        $select = $objHistorico->select()
                               ->setIntegrityCheck(false);

        $select->from(array('hist'=>KT_S_HISTORICO),
                      array('cd_proposta',
                            'cd_etapa',
                            'cd_atividade',
                            'tx_historico',
                            'cd_profissional',
                            'dt_inicio_order'=>'dt_inicio_historico',
                            'dt_inicio_historico',
                            'dt_fim_historico'),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      '(hist.cd_projeto = proj.cd_projeto)',
                      array(),
                      $this->_schema);

        $select->joinLeft(array('prof'=>KT_S_PROFISSIONAL),
                          '(hist.cd_profissional = prof.cd_profissional)',
                          array('tx_profissional'),
                          $this->_schema);

        $select->joinLeft(array('etap'=>KT_B_ETAPA),
                          '(hist.cd_etapa = etap.cd_etapa)',
                          array('tx_etapa'),
                          $this->_schema);

        $select->joinLeft(array('ativ'=>KT_B_ATIVIDADE),
                          '(hist.cd_etapa     = ativ.cd_etapa) and
                           (hist.cd_atividade = ativ.cd_atividade)',
                          array('tx_atividade'),
                          $this->_schema);

        $select->joinLeft(array('modu'=>KT_S_MODULO),
                          '(hist.cd_modulo  = modu.cd_modulo) and
                           (hist.cd_projeto = modu.cd_projeto)',
                          array('cd_modulo',
                                'tx_modulo'),
                          $this->_schema);

        $select->where('hist.cd_projeto = ? ', $cd_projeto, Zend_Db::INT_TYPE);

    	if(!is_null($cd_proposta)){
            $select->where('cd_proposta = ? ', $cd_proposta, Zend_Db::INT_TYPE);
    	}
    	if(!is_null($cd_profissional)){
            $select->where('prof.cd_profissional = ? ', $cd_profissional, Zend_Db::INT_TYPE);
    	}
    	if(!is_null($dt_inicio)){
            $select->where('dt_inicio_historico >= ? ', $dt_inicio);
    	}
    	if(!is_null($dt_fim)){
            $select->where('dt_fim_historico <= ? ', $dt_fim);
    	}
        $select->order('hist.cd_proposta')
               ->order('modu.cd_modulo')
               ->order('etap.tx_etapa')
               ->order('hist.cd_atividade')
               ->order('dt_inicio_order');

        return $objHistorico->fetchAll($select)->toArray();
    }

	public function getHistoricoDemanda($cd_objeto, $cd_profissional = null, $dt_inicio = null, $dt_fim = null)
	{
        $objHistorico = new HistoricoExecucaoDemanda();

        $select = $objHistorico->select()
                               ->setIntegrityCheck(false);

        $select->from(array('hist'=>KT_S_HISTORICO_EXECUCAO_DEMANDA),
                      array('dt_inicio',
                            'dt_fim',
                            'tx_historico'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(hist.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);
        $select->join(array('dem'=>KT_S_DEMANDA),
                      '(hist.cd_demanda = dem.cd_demanda)',
                      array('cd_demanda',
                            'dt_demanda',
                            'dt_conclusao_demanda',
                            'desc_conclusao_demanda'=> new Zend_Db_Expr("CASE WHEN dem.st_conclusao_demanda = 'S' THEN '".Base_Util::getTranslator('L_SQL_CONCLUIDA')."'
                                                                                                                  ELSE '".Base_Util::getTranslator('L_SQL_ANDAMENTO')."' END"),
                            'tx_demanda'),
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda       = dpns.cd_demanda) AND
                       (prof.cd_profissional = dpns.cd_profissional)',
                      array('cd_nivel_servico'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      array('tx_nivel_servico'),
                      $this->_schema);
        $select->order(array('dem.dt_demanda',
                             'dem.cd_demanda',
                             'prof.cd_profissional',
                             'hist.dt_inicio'));

            $select->where('dem.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
            $select->where('prof.st_inativo IS NULL');

		if(!is_null($cd_profissional)){
            $select->where('prof.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		}
		if(!is_null($dt_inicio)){
			$dt_fim = (!is_null($dt_fim))?$dt_fim:Base_Util::converterDate(date('d/m/Y'), "DD/MM/YYYY", "YYYY-MM-DD")." 23:59:59" ;

            $select->where("'{$dt_inicio}' BETWEEN hist.dt_inicio AND hist.dt_fim OR hist.dt_fim BETWEEN '{$dt_inicio}' AND '{$dt_fim}'");
		}
        
        return $this->fetchAll($select)->toArray();
	}
	
	public function getDemandaComparativoDiario($cd_profissional,$dia_final, $mes, $ano)
	{
        $objTable = new HistoricoExecucaoDemanda();

        $select = $objTable->select()->setIntegrityCheck(false);

        $subSelect = $objTable->select()
                              ->setIntegrityCheck(false)
                              ->from(array('hed'=>KT_S_HISTORICO_EXECUCAO_DEMANDA),
                                     array('quant'=>new Zend_Db_Expr('count(hed.cd_profissional)'),
                                            'dt_inicio_historico'=>'dt_inicio'),
                                     $this->_schema)
                              ->join(array('prof'=>KT_S_PROFISSIONAL),
                                     '(hed.cd_profissional = prof.cd_profissional)',
                                     'tx_profissional',
                                     $this->_schema)
                              ->where('hed.cd_profissional = ?',$cd_profissional,Zend_Db::INT_TYPE)
                              ->where("dt_inicio BETWEEN '{$ano}-{$mes}-01 00:00:00' AND '{$ano}-{$mes}-{$dia_final} 23:59:59'")
                              ->where("dt_fim    BETWEEN '{$ano}-{$mes}-01 00:00:00' AND '{$ano}-{$mes}-{$dia_final} 23:59:59'")
                              ->group(array('hed.dt_inicio',
                                            'prof.tx_profissional'))
                              ->order('dt_inicio');

        $select->from(array('dados'=>$subSelect),
                     array('dt_inicio_historico',
                           'quant',
                           'tx_profissional'));

        return $this->fetchAll($select)->toArray();
	}
	
	public function getProjetoComparativoDiario($cd_profissional,$dia_final, $mes, $ano)
	{
        $objTable = new Historico();

        $select = $objTable->select()->setIntegrityCheck(false);

        $subSelect = $objTable->select()
                              ->setIntegrityCheck(false)
                              ->from(array('hist'=>KT_S_HISTORICO),
                                     array('quant'=>new Zend_Db_Expr('count(hist.cd_profissional)'),
                                            'dt_inicio_historico'),
                                     $this->_schema)
                              ->join(array('prof'=>KT_S_PROFISSIONAL),
                                     '(hist.cd_profissional = prof.cd_profissional)',
                                     'tx_profissional',
                                     $this->_schema)
                              ->where('hist.cd_profissional = ?',$cd_profissional,Zend_Db::INT_TYPE)
                              ->where("dt_inicio_historico between '{$ano}-{$mes}-01' and '{$ano}-{$mes}-{$dia_final}'")
                              ->where("dt_fim_historico    between '{$ano}-{$mes}-01' and '{$ano}-{$mes}-{$dia_final}'")
                              ->group(array('hist.dt_inicio_historico',
                                            'prof.tx_profissional'))
                              ->order('dt_inicio_historico');

        $select->from(array('dados'=>$subSelect),
                     array('dt_inicio_historico',
                           'quant',
                           'tx_profissional'));

        return $this->fetchAll($select)->toArray();
	}
}