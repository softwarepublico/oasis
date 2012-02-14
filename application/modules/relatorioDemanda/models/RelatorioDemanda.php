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

class RelatorioDemanda extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    private $_objTable;

    public function init()
    {
        parent::init();
        $this->_objTable = new Demanda();
        
        
    }
    /**
     * Método que executa a query do relatório
     *
     * @param Integer $cd_objeto
     * @param String $tx_demanda
     * @param Integer $cd_profissional
     * @param String $dt_inicio         formato YYYY-MM-DD
     * @param String $dt_fim            formato YYYY-MM-DD
     * @param String $tx_solicitante
     * @param Integer $cd_unidade
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getDemandaResumido($cd_objeto = null,$tx_demanda = null, $cd_profissional = null, $dt_inicio = null,$dt_fim = null,$tx_solicitante = null,$cd_unidade = null)
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('dem'=>KT_S_DEMANDA),
                      array('tx_solicitacao'      => new Zend_Db_Expr("dem.ni_solicitacao{$this->concat()}'/'{$this->concat()}dem.ni_ano_solicitacao"),
                            'dt_tempo_fechamento' => new Zend_Db_Expr("(dem.dt_demanda - dem.dt_conclusao_demanda)"),
                            'desc_conclusao'      => new Zend_Db_Expr("CASE WHEN dem.st_conclusao_demanda = 'S' THEN '".Base_Util::getTranslator('L_SQL_FECHADA')."'
                                                                            WHEN dem.st_conclusao_demanda IS NULL THEN '".Base_Util::getTranslator('L_SQL_ABERTA')."' END"),
                            'dt_demanda',
                            'tx_demanda',
                            'tx_solicitante_demanda'),
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      array(),
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda      = dpns.cd_demanda) AND
                       (dp.cd_profissional  = dpns.cd_profissional)',
                      array(),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(dp.cd_profissional = prof.cd_profissional)',
                      'tx_nome_conhecido',
                      $this->_schema);
        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                      '(dem.cd_unidade = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);
        $select->order('dem.cd_demanda');

    	if(!is_null($cd_objeto))
            $select->where('dem.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
    	if(!is_null($tx_demanda))
            $select->where(new Zend_Db_Expr("dem.tx_descricao like '%{$tx_demanda}%'"));
    	if(!is_null($cd_profissional))
            $select->where('prof.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
    	if(!is_null($dt_inicio) && !is_null($dt_fim))
            $select->where("dem.dt_demanda between '{$dt_inicio} 00:00:00' AND '{$dt_fim} 23:59:59'");
    	if(!is_null($tx_solicitante))
            $select->where('dem.tx_solicitante_demanda = ?', $tx_solicitante);
    	if(!is_null($cd_unidade))
            $select->where('uni.cd_unidade = ?', $cd_unidade);

        return $this->_objTable->fetchAll($select);

    }

    /**
     * $arrParam['cd_objeto_contrato']
     * $arrParam['cd_profissional']
     * $arrParam['cd_nivel_servico']
     * $arrParam['data_inicial']
     * $arrParam['data_final']
     * $arrParam['st_tipo']
     *
     * @param Array $arrParam
     * @return Array
     */
    public function comparativoNivelDeServico(array $arrParam)
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('dados'=>$this->_montaSubSelectComparativoNivelDeServico($arrParam)),
                      array('*'));
        $select->order('dt_demanda_order');

        if(array_key_exists('cd_profissional', $arrParam))
            $select->where('dados.cd_profissional = ?',$arrParam['cd_profissional'], Zend_Db::INT_TYPE);
        if(array_key_exists('cd_nivel_servico', $arrParam))
            $select->where('dados.cd_nivel_servico = ?',$arrParam['cd_nivel_servico'], Zend_Db::INT_TYPE);
        if(array_key_exists('st_tipo', $arrParam))
            $select->where('dados.atraso > ?','00:00:00');

        //TODO quando alterei para zend_select não encontrei esta condição no $arrParam
        // nunca vem no array e sempre é incluida no select
        if(!array_key_exists('gerencial', $arrParam))
            $select->where('dados.perfil_profissional != ?', 1, Zend_Db::INT_TYPE);

//        echo "<pre>";
//        print_r($select->__toString());
//        die( "<hr>ARQUIVO:<b>" . __FILE__ . "</b><br>LINHA:<b>" . __LINE__ . '</b>' );
        
        return $this->fetchAll($select)->toArray();

    }

    private function _montaSubSelectComparativoNivelDeServico($arrParam)
    {
        $select = $this->_objTable->select()
                           ->setIntegrityCheck(false)
                           ->from(array('t'=>$this->_montaSubSubSelectComparativoNivelDeServico($arrParam)),
                                  array('dt_demanda',
                                        'dt_demanda_order',
                                        'tx_demanda',
                                        'cd_demanda',
                                        'solicitacao',
                                        'tx_profissional',
                                        'cd_profissional',
                                        'tx_nome_conhecido',
                                        'tx_nivel_servico',
                                        'cd_nivel_servico',
                                        'previsto',
                                        'executado',
                                        'perfil_profissional',
                                        'atraso'=>new Zend_Db_Expr("CASE WHEN executado > previsto THEN executado-previsto ELSE '00:00:00'::time END")));

        return $select;
    }

    private function _montaSubSubSelectComparativoNivelDeServico($arrParam)
    {
        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);

        $select->from(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      array('cd_demanda',
                            'cd_nivel_servico',
                            'previsto'    => new Zend_Db_Expr("cast(ni_horas_prazo_execucao::text {$this->concat()} ' hours' as interval)"),
                            'executado'   => new Zend_Db_Expr("cast((dt_fechamento_nivel_servico-dt_leitura_nivel_servico) as time)"),
                            'solicitacao' => new Zend_Db_Expr("case when dem.ni_solicitacao is not null then dem.ni_solicitacao{$this->concat()}'/'{$this->concat()}dem.ni_ano_solicitacao else null end")),
                      $this->_schema);

        $select->join(array('dem'=>KT_S_DEMANDA),
                      '(dpns.cd_demanda = dem.cd_demanda)',
                      array('tx_demanda',
                            'dt_demanda',
                            'dt_demanda_order'=>'dt_demanda'),
                      $this->_schema);

        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      'tx_nivel_servico',
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(dpns.cd_profissional = prof.cd_profissional)',
                      array('perfil_profissional'=>'cd_perfil',
                            'cd_profissional',
                            'tx_profissional',
                            'tx_nome_conhecido'),
                      $this->_schema);

        $select->joinLeft(array('sol'=>KT_S_SOLICITACAO),
                          '(dem.ni_solicitacao     = sol.ni_solicitacao) AND
                           (dem.ni_ano_solicitacao = sol.ni_ano_solicitacao) AND
                           (dem.cd_objeto          = sol.cd_objeto)',
                          array(),
                          $this->_schema);

        $select->where("dt_demanda_nivel_servico between '{$arrParam['dt_demanda_inicio']}' AND '{$arrParam['dt_demanda_final']}'");
        $select->where('dem.cd_objeto = ?',$arrParam['cd_objeto_contrato']);
        $select->where('st_fechamento_nivel_servico is not null');

        return $select;
    }


    public function getDemanda(array $where)
    {
        $select = $this->_objTable->select()->distinct()->setIntegrityCheck(false);

        $select->from(array('dem'=>KT_S_DEMANDA),
                      array('cd_demanda',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'tx_demanda',
                            'dt_demanda'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      'dem.cd_unidade = uni.cd_unidade',
                      'tx_sigla_unidade',
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      'dem.cd_objeto = oc.cd_objeto',
                      'tx_objeto',
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      'dem.cd_demanda = dp.cd_demanda',
                      '',
                      $this->_schema);
        $select->where('dem.cd_objeto = ?', $where['cd_objeto'], Zend_Db::INT_TYPE);
        $select->where('dem.st_fechamento_demanda is null');
        $select->order('dt_demanda');

        if( isset($where['mes']) && isset($where['ano']) ){
            $select->where(new Zend_Db_Expr($this->to_char('dt_demanda', 'YYYY')." = ?"), $where['ano']);
            $select->where(new Zend_Db_Expr($this->to_char('dt_demanda', 'MM')." = ?"), $where['mes']);
        }
        if( isset($where['cd_profissional']) ){
            $select->where('dp.cd_profissional = ?', $where['cd_profissional'], Zend_Db::INT_TYPE);
        }
        return $this->fetchAll($select)->toArray();
    }

    public function getProfissionalDemanda($cdDemanda)
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('dem'=>KT_S_DEMANDA),
                      'cd_demanda',
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      'dem.cd_demanda = dp.cd_demanda',
                      array(
						  'dt_demanda_profissional',
						  'st_fechamento_demanda',
						  'dt_fechamento_demanda'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      'dp.cd_profissional = prof.cd_profissional',
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);
        $select->where('dem.cd_demanda = ?', $cdDemanda, Zend_Db::INT_TYPE);

        return $this->_objTable->fetchAll($select)->toArray();
    }

    public function getNivelServicoDemanda($cdDemanda, $cdProfissional)
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('dem'=>KT_S_DEMANDA),
                      'cd_demanda',
                      $this->_schema);
        $select->join(array('dnsp'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      'dem.cd_demanda = dnsp.cd_demanda',
                      array('cd_profissional',
					    'cd_nivel_servico',
					    'dt_demanda_nivel_servico',
						'st_fechamento_nivel_servico',
						'dt_fechamento_nivel_servico',
						'dt_leitura_nivel_servico',
						'tx_obs_nivel_servico'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      'dnsp.cd_nivel_servico = ns.cd_nivel_servico',
                      array('tx_nivel_servico', 'ni_horas_prazo_execucao'),
                      $this->_schema);
        $select->where('dem.cd_demanda = ?', $cdDemanda, Zend_Db::INT_TYPE);
        $select->where('dnsp.cd_profissional = ?', $cdProfissional, Zend_Db::INT_TYPE);

        return $this->_objTable->fetchAll($select)->toArray();
    }

    public function getMediaNivelDeServico(array $arrParam)
    {
        
        $subSelect = $this->getDefaultAdapter()->select();
        $subSelect->from(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                         array('cd_nivel_servico',
                             'executado'=> new Zend_Db_Expr("cast((dt_fechamento_nivel_servico-dt_leitura_nivel_servico) as time)")
                         ), 
                         $this->_schema);
        $subSelect->join(array('ns'=>KT_B_NIVEL_SERVICO),
                         '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                         array('tx_nivel_servico',
                             'cd_objeto',
                             'previsto'=> new Zend_Db_Expr("cast(ni_horas_prazo_execucao::text ||' hours' as interval)")
                         ),
                         $this->_schema);
        $subSelect->where('dpns.cd_nivel_servico <> 0');
        $subSelect->where('ns.cd_objeto = ?',$arrParam['cd_objeto_contrato'], Zend_Db::INT_TYPE);
        $subSelect->where("(dpns.dt_demanda_nivel_servico between '{$arrParam['dt_demanda_inicio']}' AND '{$arrParam['dt_demanda_final']}')");
        $subSelect->where('dpns.st_fechamento_nivel_servico is not null');
        
        $sql = $this->getDefaultAdapter()->select();
        $sql->from(array('t'=>$subSelect),
                      array(
                          'cd_nivel_servico',
                          'tx_nivel_servico',
                          'previsto'));
        $sql->join(array('qt'=> $this->getDefaultAdapter()
                                        ->select()
                                        ->from(array('a'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                                               array('cd_nivel_servico',
                                                     'qtd'=> new Zend_Db_Expr('count(a.cd_nivel_servico)')),
                                               $this->_schema)
                                        ->where('a.cd_nivel_servico <> 0')
                                        ->group('a.cd_nivel_servico')
                           ),
                      '(t.cd_nivel_servico <> 0 and t.cd_nivel_servico = qt.cd_nivel_servico)',
                      array('qtd'));
        
        $sql->join(array('mm'=> $this->getDefaultAdapter()
                                        ->select()
                                        ->from(array('b'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                                               array('cd_nivel_servico',
                                                     'minimo'=> new Zend_Db_Expr('min (cast((dt_fechamento_nivel_servico-dt_leitura_nivel_servico) as time))')
                                               ),
                                               $this->_schema)
                                        ->where('b.cd_nivel_servico <> 0')
                                        ->group('b.cd_nivel_servico')
                           ),
                      '(t.cd_nivel_servico <> 0 and t.cd_nivel_servico = mm.cd_nivel_servico)',
                      array('minimo'));
        $sql->join(array('ma'=> $this->getDefaultAdapter()
                                        ->select()
                                        ->from(array('c'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                                               array('cd_nivel_servico',
                                                     'maximo'=> new Zend_Db_Expr('max (cast((dt_fechamento_nivel_servico-dt_leitura_nivel_servico) as time))')
                                               ),
                                               $this->_schema)
                                        ->where('c.cd_nivel_servico <> 0')
                                        ->group('c.cd_nivel_servico')
                           ),
                      '(t.cd_nivel_servico <> 0 and t.cd_nivel_servico = ma.cd_nivel_servico)',
                      array('maximo'));
        
        $sql->join(array('md'=> $this->getDefaultAdapter()
                                        ->select()
                                        ->from(array('d'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                                               array('cd_nivel_servico',
                                                     'media'=> new Zend_Db_Expr('avg(cast((dt_fechamento_nivel_servico-dt_leitura_nivel_servico)  as time))')
                                               ),
                                               $this->_schema)
                                        ->where('d.cd_nivel_servico <> 0')
                                        ->group('d.cd_nivel_servico')
                           ),
                      '(t.cd_nivel_servico <> 0 and t.cd_nivel_servico = md.cd_nivel_servico)',
                      array('media'));
        $sql->group(
                    array(
                        't.cd_nivel_servico',
                        't.tx_nivel_servico',
                        't.previsto',
                        'qt.qtd',
                        'mm.minimo',
                        'ma.maximo',
                        'md.media'
                    )
            );
        $sql->order('t.tx_nivel_servico');
        
        
        return $this->getDefaultAdapter()->fetchAll($sql);
        
    }

	public function getHistoricoExecucaoDemanda($cd_demanda, $cd_profissional, $cd_nivel_servico)
	{
			$select = $this->_objTable->select()->setIntegrityCheck(false);

			$select->from(array('hed' => KT_S_HISTORICO_EXECUCAO_DEMANDA),
						  array('cd_profissional',
							'cd_nivel_servico',
							'dt_inicio',
							'dt_fim',
							'tx_historico'),
							$this->_schema);

			$select->join(array('prof' => KT_S_PROFISSIONAL),
							'hed.cd_profissional = prof.cd_profissional',
							'tx_profissional',
							$this->_schema);

			$select->join(array('ns'=>KT_B_NIVEL_SERVICO),
						  'hed.cd_nivel_servico = ns.cd_nivel_servico',
						  'tx_nivel_servico',
						  $this->_schema);
				  
			$select->where('hed.cd_demanda       = ?', $cd_demanda      , Zend_Db::INT_TYPE);
			$select->where('hed.cd_profissional  = ?', $cd_profissional , Zend_Db::INT_TYPE);
			$select->where('hed.cd_nivel_servico = ?', $cd_nivel_servico, Zend_Db::INT_TYPE);
			$select->order('hed.dt_inicio');
	
        return $this->_objTable->fetchAll($select)->toArray();
	}
}