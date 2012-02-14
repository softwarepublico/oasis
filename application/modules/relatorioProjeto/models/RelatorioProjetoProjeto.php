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

class RelatorioProjetoProjeto extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    private $_objTable;

    public function init()
    {
        parent::init();
        $this->_objTable = new Projeto();
    }

	public function situacaoProjeto()
	{
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('proj'=>KT_S_PROJETO),
                      array('tx_sigla_projeto',
                            'tx_projeto',
                            'ni_mes_inicio_previsto',
                            'ni_ano_inicio_previsto',
                            'ni_mes_termino_previsto',
                            'ni_ano_termino_previsto'),
                      $this->_schema);
        $select->join(array('sitproj'=>KT_S_SITUACAO_PROJETO),
                      '(proj.cd_projeto = sitproj.cd_projeto)',
                      array('ni_mes_situacao_projeto',
                            'ni_ano_situacao_projeto',
                            'tx_situacao_projeto'),
                      $this->_schema);

        return $this->fetchAll($select)->toArray();
	}

    public function projetoDesenvolvidoContrato($cd_contrato, $arrImpacto)
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('p'=>KT_S_PROJETO),
                      array('tx_sigla_projeto',
                            'tx_projeto',
                            'tx_contexto_geral_projeto',
                            'tx_escopo_projeto',
                            'tx_gestor_projeto',
                            'st_impacto_projeto'=>new Zend_Db_Expr("case st_impacto_projeto
                                                                            when 'I' then '".Base_Util::getTranslator('L_SQL_INTERNO')."'
                                                                            when 'E' then '".Base_Util::getTranslator('L_SQL_EXTERNO')."'
                                                                            when 'A' then '".Base_Util::getTranslator('L_SQL_INTERNO_EXTERNO')."' end")),
                      $this->_schema);

        $subSelect = $this->_objTable->select()
                                     ->distinct()
                                     ->setIntegrityCheck(false)
                                     ->from(array('proj'=>KT_S_PROJETO),
                                                  'cd_projeto',
                                                  $this->_schema)
                                     ->join(array('cont'=>KT_A_CONTROLE),
                                                  '(proj.cd_projeto = cont.cd_projeto)',
                                                  array(),
                                                  $this->_schema)
                                     ->where("proj.st_impacto_projeto in (?)", $arrImpacto)
                                     ->where('cont.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        $select->join(array('t'=>$subSelect),
                      '(p.cd_projeto = t.cd_projeto)',
                      array());
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(p.cd_unidade = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);
        $select->order('tx_sigla_projeto');

        return $this->fetchAll($select)->toArray();
    }

    public function getSituacaoAtualProjeto( $cd_projeto, $mes, $ano )
    {
        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);

        $select->from(array('sp'=>KT_S_SITUACAO_PROJETO),
                      array('ni_mes_situacao_projeto',
                            'ni_ano_situacao_projeto',
                            'tx_situacao_projeto'),
                      $this->_schema);
                  
        $select->join(array('p'=>KT_S_PROJETO),
                      'sp.cd_projeto = p.cd_projeto',
                      array('tx_sigla_projeto'),
                      $this->_schema);

        $select->where('sp.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where("ni_ano_situacao_projeto = ?", $ano, Zend_Db::INT_TYPE)
               ->where("ni_mes_situacao_projeto = ?", $mes, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
    }

    public function getSituacaoUltimaProjeto( $cd_projeto )
    {
        $objSitProj = new SituacaoProjeto();

        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);

        $subSelect = $objSitProj->select()
                                ->from(KT_S_SITUACAO_PROJETO,
                                       array('cd_projeto',
                                             'mes_ano'=>new Zend_Db_Expr("MAX((ni_ano_situacao_projeto * 100) + ni_mes_situacao_projeto)")),
                                       $this->_schema)
                                ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                ->group('cd_projeto');
                                
        $select->from(array('maxsit'=>$subSelect),
                      array('cd_projeto',
                            'mes_ano'));
        $select->join(array('sp'=>KT_S_SITUACAO_PROJETO),
                      '(maxsit.cd_projeto = sp.cd_projeto) and
                       (maxsit.mes_ano    = ((sp.ni_ano_situacao_projeto*100)+sp.ni_mes_situacao_projeto))',
                      array('ni_mes_situacao_projeto',
                            'ni_ano_situacao_projeto',
                            'tx_situacao_projeto'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(sp.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->where('sp.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
   }

    public function getDadosAnaliseExecucaoProjeto( $cd_projeto, $mes, $ano )
	{
		$mesChar = ($mes < 10) ? '0'.$mes : $mes;

        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);

        $select->from(array('aep'=>KT_S_ANALISE_EXECUCAO_PROJETO),
                      array('dt_analise_execucao_projeto',
                            'tx_resultado_analise_execucao'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(aep.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);

        $select->where('aep.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_analise_execucao_projeto', 'YYYY')} = ? "), $ano);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_analise_execucao_projeto', 'MM')} = ? "), $mesChar);

        return $this->fetchAll($select)->toArray();
	}
}