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

class RelatorioProjetoPrevisaoMensalProjeto extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    private $_objTable;

    public function init()
    {
        parent::init();
        $this->_objTable = new Parcela();
    }

	public function getPrevisaoMensais(array $arrParam)
	{
        //primeiro Select
        $select_1 = $this->_objTable->select()
                                    ->distinct()
                                    ->setIntegrityCheck(false);

        $select_1->from(array('parc'=>KT_S_PARCELA),
                        array('cd_projeto'),
                        $this->_schema);
        $select_1->join(array('proj'=>KT_S_PROJETO),
                        '(parc.cd_projeto = proj.cd_projeto)',
                        array('tx_sigla_projeto'),
                        $this->_schema);
        $select_1->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                        '(cp.cd_projeto = proj.cd_projeto)',
                        array(),
                        $this->_schema);
        $select_1->where("parc.ni_mes_previsao_parcela BETWEEN ? AND 12", $arrParam['mesInicial'], Zend_Db::INT_TYPE)
                 ->where("ni_ano_previsao_parcela = ?", $arrParam['anoInicial' ], Zend_Db::INT_TYPE)
                 ->where("cp.cd_contrato          = ?", $arrParam['cd_contrato'], Zend_Db::INT_TYPE);

        $select_1->group('parc.cd_projeto')
                 ->group('proj.tx_sigla_projeto')
                 ->group('parc.ni_mes_previsao_parcela');

        //Segundo Select
        $select_2 = $this->_objTable->select()
                                    ->distinct()
                                    ->setIntegrityCheck(false);

        $select_2->from(array('parc'=>KT_S_PARCELA),
                        array('cd_projeto'),
                        $this->_schema);
        $select_2->join(array('proj'=>KT_S_PROJETO),
                        '(parc.cd_projeto = proj.cd_projeto)',
                        array('tx_sigla_projeto'),
                        $this->_schema);
        $select_2->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                        '(cp.cd_projeto = proj.cd_projeto)',
                        array(),
                        $this->_schema);
        $select_2->where("parc.ni_mes_previsao_parcela BETWEEN 1 AND ?", $arrParam['mesFinal'], Zend_Db::INT_TYPE)
                 ->where("ni_ano_previsao_parcela = ?", $arrParam['anoFinal'   ], Zend_Db::INT_TYPE)
                 ->where("cp.cd_contrato          = ?", $arrParam['cd_contrato'], Zend_Db::INT_TYPE);

        $select_2->group('parc.cd_projeto')
                 ->group('proj.tx_sigla_projeto')
                 ->group('parc.ni_mes_previsao_parcela');

        $select = $this->_objTable->select()->union(array($select_1,$select_2));
        
        return $this->fetchAll($select)->toArray();

//		$sql = "
//                 SELECT distinct
//					parc.cd_projeto,
//					proj.tx_sigla_projeto
//				FROM
//					{$this->_schema}.".KT_S_PARCELA." parc
//				join
//					{$this->_schema}.".KT_S_PROJETO." as proj ON (parc.cd_projeto = proj.cd_projeto)
//                join
//                    {$this->_schema}.".KT_A_CONTRATO_PROJETO." as cp
//                ON
//                    (cp.cd_projeto = proj.cd_projeto)
//				WHERE
//					parc.ni_mes_previsao_parcela between :mesInicial and 12
//
//					and ni_ano_previsao_parcela = :anoInicial
//                    and cp.cd_contrato = :cdContrato
//				group by
//                    parc.cd_projeto,
//					proj.tx_sigla_projeto,
//					parc.ni_mes_previsao_parcela
//				union
//				SELECT distinct
//					parc.cd_projeto ,
//					proj.tx_sigla_projeto
//				FROM {$this->_schema}.".KT_S_PARCELA." parc
//				join
//                    {$this->_schema}.".KT_S_PROJETO." as proj
//                ON
//                    (parc.cd_projeto = proj.cd_projeto)
//                join
//                    {$this->_schema}.".KT_A_CONTRATO_PROJETO." as cp
//                ON
//                    (cp.cd_projeto = proj.cd_projeto)
//				WHERE
//					parc.ni_mes_previsao_parcela between 1 and :mesFinal
//					and parc.ni_ano_previsao_parcela = :anoFinal
//                    and cp.cd_contrato = :cdContrato
//				group by parc.cd_projeto,
//					proj.tx_sigla_projeto,
//					parc.ni_mes_previsao_parcela ";
//
//		$param[':mesInicial'] = $arrParam['mesInicial'];
//		$param[':anoInicial'] = $arrParam['anoInicial'];
//		$param[':mesFinal']   = $arrParam['mesFinal'];
//		$param[':anoFinal']   = $arrParam['anoFinal'];
//		$param[':cdContrato'] = $arrParam['cd_contrato'];
//
//		$arrPrevisaoMensais = $this->getDefaultAdapter()->fetchAll($sql,$param);
//
//		return $arrPrevisaoMensais;
	}

	public function getPrevisaoMensal(array $arrParam)
	{
        $select = $this->_objTable->select()
                                  ->distinct()
                                  ->setIntegrityCheck(false);

        $select->from(array('parc'=>KT_S_PARCELA),
                      array('cd_projeto'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(parc.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(cp.cd_projeto = proj.cd_projeto)',
                      array(),
                      $this->_schema);

        $select->where('cp.cd_contrato = ?', $arrParam['cd_contrato'], Zend_Db::INT_TYPE)
               ->where('parc.ni_ano_previsao_parcela = ?', $arrParam['anoInicial'], Zend_Db::INT_TYPE)
               ->where("parc.ni_mes_previsao_parcela  BETWEEN {$this->getDefaultAdapter()->quote($arrParam['mesInicial'], Zend_Db::INT_TYPE)} and {$this->getDefaultAdapter()->quote($arrParam['mesFinal'], Zend_Db::INT_TYPE)} ");

        $select->group('parc.cd_projeto')
               ->group('proj.tx_sigla_projeto')
               ->group('parc.ni_mes_previsao_parcela');

        $select->order('proj.tx_sigla_projeto');

        return $this->fetchAll($select)->toArray();
	}
	
	public function getHorasMensalProjeto($cd_projeto, $mes, $ano)
	{
        $select = $this->_objTable->select()
                                  ->distinct()
                                  ->setIntegrityCheck(false)
                                  ->from(array('parc'=>KT_S_PARCELA),
                                         array('ni_mes_previsao_parcela',
                                               'horas_total_projeto'=>new Zend_Db_Expr("sum(ni_horas_parcela)")),
                                         $this->_schema)
                                  ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                  ->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
                                  ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
                                  ->group('ni_mes_previsao_parcela');

        return $this->fetchAll($select)->toArray();
	}
}