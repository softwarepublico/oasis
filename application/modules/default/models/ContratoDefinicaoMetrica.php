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

class ContratoDefinicaoMetrica extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_CONTRATO_DEFINICAO_METRICA;
	protected $_primary  = array('cd_contrato', 'cd_definicao_metrica');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function pesquisaDefinicaoMetricaForaContrato($cd_contrato)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dm'=>KT_B_DEFINICAO_METRICA),
                      array('cd_definicao_metrica',
                            'tx_nome_metrica',
                            'tx_sigla_metrica'),
                      $this->_schema);
        $select->joinLeft(array('cdm'=>$this->select()
                                            ->from($this, 'cd_definicao_metrica')
                                            ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          'dm.cd_definicao_metrica = cdm.cd_definicao_metrica',
                          array());
        $select->where('cdm.cd_definicao_metrica IS NULL');
        $select->order('tx_nome_metrica');

        return $this->fetchAll($select)->toArray();
	}

	public function pesquisaDefinicaoMetricaNoContrato($cd_contrato)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dm'=>KT_B_DEFINICAO_METRICA),
                      array('cd_definicao_metrica',
                            'tx_nome_metrica',
                            'tx_sigla_metrica'),
                      $this->_schema);
        $select->joinLeft(array('cdm'=>$this->select()
                                            ->from($this, 
                                                   array('cd_definicao_metrica',
                                                         'cd_contrato',
                                                         'st_metrica_padrao',
                                                         'nf_fator_relacao_metrica_pad'))
                                            ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          'dm.cd_definicao_metrica = cdm.cd_definicao_metrica',
                          array('cd_contrato','st_metrica_padrao','nf_fator_relacao_metrica_pad'));
        $select->where('cdm.cd_definicao_metrica IS NOT NULL');
        $select->order('tx_nome_metrica');

        return $this->fetchAll($select)->toArray();
	}

	public function getSiglaUnidadePadraoMetrica( $cd_contrato )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('cdm'=>$this->_name),
                      array('cd_contrato','cd_definicao_metrica'),
                      $this->_schema);
        $select->join(array('dm'=>KT_B_DEFINICAO_METRICA),
                      '(cdm.cd_definicao_metrica = dm.cd_definicao_metrica)',
                      array('tx_sigla_metrica' => new Zend_Db_Expr('tx_sigla_unidade_metrica')),
                      $this->_schema);
        $select->where('st_metrica_padrao = ?', 'S')
               ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}

    public function getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($cd_projeto)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('cp'=>KT_A_CONTRATO_PROJETO),
                      array('cd_contrato'),
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      'cp.cd_contrato = con.cd_contrato',
                      array(),
                      $this->_schema);
        $select->join(array('cm'=>$this->_name),
                      'con.cd_contrato = cm.cd_contrato',
                      array(),
                      $this->_schema);
        $select->join(array('dm'=>KT_B_DEFINICAO_METRICA),
                      'cm.cd_definicao_metrica = dm.cd_definicao_metrica',
                      array('tx_sigla_unidade_metrica'),
                      $this->_schema);

        $select->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('con.st_contrato = ?','A')
               ->where('cm.st_metrica_padrao = ?','S');

        $row = $this->fetchRow($select);
        if (is_object($row)) {
            return $row->toArray();
        }
	}

	public function getContratoUltimoFechamentoProposta($cd_projeto, $cd_proposta)
	{
		$subSelect = $this->select()->setIntegrityCheck(false);
		$subSelect->from(array('p'=>KT_S_PROCESSAMENTO_PROPOSTA),
					array('p.dt_fechamento_proposta'),
					$this->_schema);
		$subSelect->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		$subSelect->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
		$subSelect->where('st_ativo = ?', 'S');

		$select = $this->select()->setIntegrityCheck(false);
		$select->from(array('con'=>KT_S_CONTRATO),
					array('con.cd_contrato'),
					$this->_schema);
		$select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
					'con.cd_contrato = cp.cd_contrato',
					null,
					$this->_schema);
		$select->where('cp.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		$select->where('? between dt_inicio_contrato and dt_fim_contrato', $subSelect);

		return $this->fetchRow($select);
	}
}