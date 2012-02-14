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

class RelatorioProjetoContrato extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

	public function extratoPagamentoContrato($cd_contrato)
	{
        $objTable = new Contrato();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('con'=>KT_S_CONTRATO),
                      array('cd_contrato',
                            'tx_numero_contrato',
                            'ni_mes_inicial_contrato',
                            'ni_ano_inicial_contrato',
                            'ni_mes_final_contrato',
                            'ni_ano_final_contrato',
                            'ni_qtd_meses_contrato',
                            'nf_valor_unitario_hora',
                            'ni_horas_previstas'),
                      $this->_schema);

        $select->join(array('em'=>KT_S_EXTRATO_MENSAL),
                      '(con.cd_contrato = em.cd_contrato)',
                      array('ni_mes_extrato',
                            'ni_ano_extrato',
                            'ni_horas_extrato'),
                      $this->_schema);

        $select->where('con.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        $select->order('em.ni_ano_extrato')
               ->order('em.ni_mes_extrato');
        return $objTable->fetchAll($select)->toArray();
	}

	public function getDadosContrato($cd_contrato)
	{
        $objTable = new Contrato();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('cont'=>KT_S_CONTRATO),
                      array('tx_numero_contrato',
                            'situacao_contrato' =>new Zend_Db_Expr("CASE st_contrato WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ATIVO')."'
                                                                                     WHEN 'I' THEN '".Base_Util::getTranslator('L_SQL_INATIVO')."' END"),
                            'dt_inicio_contrato'=>new Zend_Db_Expr("{$this->to_char('dt_inicio_contrato', 'DD/MM/YYYY')}"),
                            'dt_fim_contrato'   =>new Zend_Db_Expr("{$this->to_char('dt_fim_contrato', 'DD/MM/YYYY')}"),
                            'ni_horas_previstas',
                            'nf_valor_unitario_hora'),
                      $this->_schema);

        $select->join(array('emp'=>KT_S_EMPRESA),
                      '(cont.cd_empresa = emp.cd_empresa)',
                      array('tx_empresa'),
                      $this->_schema);

        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(cont.cd_contrato = obj.cd_contrato)',
                      array('tx_objeto'),
                      $this->_schema);

        $select->where('cont.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $objTable->fetchRow($select)->toArray();
	}
	
	public function getProjetoPrevistoExecucaoContrato( $cd_contrato )
	{
        $objTable = new ExtratoMensalParcela();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('emp'=>KT_S_EXTRATO_MENSAL_PARCELA),
                      array('horas' => new Zend_Db_Expr("sum(ni_hora_parcela_extrato)")),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      '(emp.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto',
                            'projeto'=>new Zend_Db_Expr("tx_sigla_projeto {$this->concat()} ' - ' {$this->concat()} tx_projeto")),
                      $this->_schema);

        $select->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        $select->group('emp.cd_projeto')
               ->group('tx_sigla_projeto')
               ->group('tx_projeto');

        $select->order(new Zend_Db_Expr("tx_sigla_projeto {$this->concat()} ' - ' {$this->concat()} tx_projeto"));

        return $objTable->fetchAll($select)->toArray();
	}
	
	public function getParcelasExecucaoContrato( $cd_contrato )
	{
        $objTable = new ExtratoMensal();
        $select   = $objTable->select();

        $select->from(array(KT_S_EXTRATO_MENSAL),
                      array('total_horas'    => new Zend_Db_Expr("sum(ni_horas_extrato)"),
                            'total_parcelas' => new Zend_Db_Expr("sum(ni_qtd_parcela)")),
                      $this->_schema);

        $select->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $objTable->fetchRow($select)->toArray();
	}
	
	public function getProjetosDadoContrato( $cd_contrato )
	{
        $objTable = new ProjetoPrevisto();
        $select   = $objTable->select();

        $select->from(array(KT_S_PROJETO_PREVISTO),
                      array('tx_projeto_previsto',
                            'ni_horas_projeto_previsto',
                            'tipo' => new Zend_Db_Expr("CASE st_projeto_previsto WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_NOVO')."'
                                                                                 WHEN 'E' THEN '".Base_Util::getTranslator('L_SQL_EVOLUTIVO')."' END")),
                      $this->_schema);

        $select->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('tx_projeto_previsto');

        return $objTable->fetchAll($select)->toArray();
	}

	public function getProjetoPrevistoDistribuicaoRecuso($cd_contrato)
	{
        $objTable = new ProjetoPrevisto();
        $select   = $objTable->select();

        $select->from(KT_S_PROJETO_PREVISTO,
                      array('tx_projeto_previsto',
                            'ni_horas_projeto_previsto',
                            'cd_projeto_previsto'),
                      $this->_schema);

        $select->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('tx_projeto_previsto');

        return $objTable->fetchAll($select)->toArray();
	}
	
	public function getDistribuicaoRecusoPorProjeto( $cd_projeto_previsto )
	{
        $objTable = new Controle();

        $subSelect = $objTable->select();
        $subSelect->from(KT_A_CONTROLE,
                         array('cd_projeto',
                               'ni_horas'=>new Zend_Db_Expr("CASE WHEN st_controle = 'D' THEN ni_horas WHEN st_controle = 'C' THEN ni_horas*(-1) END")),
                         $this->_schema)
                  ->where('cd_projeto_previsto = ?', $cd_projeto_previsto, Zend_Db::INT_TYPE);

        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('t'=>$subSelect),
                      array('cd_projeto','ni_horas'=>new Zend_Db_Expr("sum(ni_horas)")));

        $select->join(array('p'=>KT_S_PROJETO),
                      '(t.cd_projeto = p.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);

        $select->group('t.cd_projeto')
               ->group('tx_sigla_projeto');

        $select->order('tx_sigla_projeto');

        return $objTable->fetchAll($select)->toArray();
	}
}