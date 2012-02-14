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

class GerenciaMudanca extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_GERENCIA_MUDANCA;
	protected $_primary  = array('dt_gerencia_mudanca','cd_item_controle_baseline','cd_projeto','cd_item_controlado','dt_versao_item_controlado');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	/**
	 * Método para reucperar os pedidos de mudança das proposta
	 *
	 * @param int $mes
	 * @param int $ano
	 * @param int $itemControleBaseline
	 * @return array
	 */
	public function getMudancaProposta( $mes, $ano, $itemControleBaseline )
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('gm'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta'=>'cd_item_controlado',
                            'st_decisao_mudanca_desc'=>new Zend_Db_Expr("CASE gm.st_decisao_mudanca WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_ANALIZADO_ACEITO')."'
                                                                                                    WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_ANALIZADO_NAO_ACEITO')."'
                                                                                                    ELSE '".Base_Util::getTranslator('L_SQL_PENDENTE')."' END"),
                            'cd_item_controlado',
                            'dt_gerencia_mudanca',
                            'dt_versao_item_controlado',
                            'cd_item_controle_baseline',
                            'st_mudanca_metrica',
                            'ni_custo_provavel_mudanca',
                            'st_reuniao',
                            'cd_reuniao',
                            'gm.st_decisao_mudanca',
                            'gm.st_execucao_mudanca',
                            'tx_motivo_mudanca'),
                      $this->_schema);
		$select->join(array('p'=>KT_S_PROJETO),
                      '(gm.cd_projeto = p.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $diaFinalMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        
        $select->where("gm.dt_gerencia_mudanca between '{$ano}-{$mes}-01 00:00:00' and '{$ano}-{$mes}-{$diaFinalMes} 23:59:59'");
        $select->where('gm.cd_item_controle_baseline = ?', $itemControleBaseline, Zend_Db::INT_TYPE);
        $select->order(array('st_decisao_mudanca_desc','dt_gerencia_mudanca'));

        return $this->fetchAll($select)->toArray();
	}
	
	/**
	 * Método para recuperar os pedidos de mudança dos requisito
	 *
	 * @param int $mes
	 * @param int $ano
	 * @param int $itemControleBaseline
	 * @return array
	 */
	public function getMudancaRequisito( $mes, $ano, $itemControleBaseline )
	{
        $select = $this->_montaInicioSelectMudanca($mes, $ano, $itemControleBaseline);

        $select->join(array('req'=>$this->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('r'=>KT_S_REQUISITO),
                                               array('tx_requisito','cd_requisito','dt_versao_requisito','ni_versao_requisito'),
                                               $this->_schema)
                                        ->join(array('maxreq'=>$this->select()
                                                                    ->setIntegrityCheck(false)
                                                                    ->from(KT_S_REQUISITO,
                                                                           array('cd_requisito',
                                                                                 'dt_versao_requisito'=>new Zend_Db_Expr("MAX(dt_versao_requisito)")),
                                                                           $this->_schema)
                                                                    ->group('cd_requisito')),
                                               '(maxreq.cd_requisito = r.cd_requisito)',
                                               array())),
                      '(req.cd_requisito = gm.cd_item_controlado) AND (req.dt_versao_requisito = gm.dt_versao_item_controlado)',
                      array('ni_versao_requisito','tx_requisito'));

        return $this->fetchAll($select)->toArray();
	}

	/**
	 * Método para recuperar os pedidos de mudança das regras de Negocio
	 *
	 * @param int $mes
	 * @param int $ano
	 * @param int $itemControleBaseline
	 * @return array
	 */
	public function getMudancaRegraDeNegocio($mes, $ano, $itemControleBaseline )
	{
        $select = $this->_montaInicioSelectMudanca($mes, $ano, $itemControleBaseline);

        $select->join(array('reg'=>$this->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('rn'=>KT_S_REGRA_NEGOCIO),
                                               array('tx_regra_negocio','cd_regra_negocio','dt_regra_negocio','ni_versao_regra_negocio'),
                                               $this->_schema)
                                        ->join(array('maxreg'=>$this->select()
                                                                 ->setIntegrityCheck(false)
                                                                 ->from(KT_S_REGRA_NEGOCIO,
                                                                        array('cd_regra_negocio',
                                                                              'dt_regra_negocio'=>new Zend_Db_Expr("MAX(dt_regra_negocio)")),
                                                                        $this->_schema)
                                                                 ->group('cd_regra_negocio')),
                                               '(maxreg.cd_regra_negocio = rn.cd_regra_negocio)',
                                               array())),
                      '(reg.cd_regra_negocio = gm.cd_item_controlado) AND (reg.dt_regra_negocio = gm.dt_versao_item_controlado)',
                      array('tx_regra_negocio','ni_versao_regra_negocio'));

        return $this->fetchAll($select)->toArray();
	}
	
	/**
	 * Método para recuperar os pedidos de mudança dos casos de uso
	 *
	 * @param int $mes
	 * @param int $ano
	 * @param int $itemControleBaseline
	 * @return array
	 */
	public function getMudancaCasoDeUso($mes, $ano, $itemControleBaseline )
	{

        $select = $this->_montaInicioSelectMudanca($mes, $ano, $itemControleBaseline);

        $select->join(array('caso'=>$this->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('uc'=>KT_S_CASO_DE_USO),
                                               array('tx_caso_de_uso','cd_caso_de_uso','dt_versao_caso_de_uso','ni_versao_caso_de_uso'),
                                               $this->_schema)
                                        ->join(array('maxuc'=>$this->select()
                                                                 ->setIntegrityCheck(false)
                                                                 ->from(KT_S_CASO_DE_USO,
                                                                        array('cd_caso_de_uso',
                                                                              'dt_versao_caso_de_uso'=>new Zend_Db_Expr("MAX(dt_versao_caso_de_uso)")),
                                                                        $this->_schema)
                                                                 ->group('cd_caso_de_uso')),
                                               '(maxuc.cd_caso_de_uso = uc.cd_caso_de_uso)',
                                               array())),

                      '(caso.cd_caso_de_uso = gm.cd_item_controlado) AND (caso.dt_versao_caso_de_uso = gm.dt_versao_item_controlado)',
                      array('tx_caso_de_uso','ni_versao_caso_de_uso'));

        return $this->fetchAll($select)->toArray();
	}

	public function buscaPedidoMudancaEmAndamento($cd_projeto, $itemControleBaseline)
	{
		$select = $this->select()
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('cd_item_controle_baseline = ?', $itemControleBaseline, Zend_Db::INT_TYPE)
                       ->where('st_decisao_mudanca IS NULL');

		return $this->fetchAll($select)->toArray();
	}

    private function _montaInicioSelectMudanca($mes, $ano, $itemControleBaseline)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('gm'=>$this->_name),
                      array('cd_projeto',
                            'cd_item_controlado',
                            'dt_gerencia_mudanca',
                            'dt_versao_item_controlado',
                            'cd_item_controle_baseline',
                            'st_mudanca_metrica',
                            'ni_custo_provavel_mudanca',
                            'st_reuniao',
                            'cd_reuniao',
                            'tx_motivo_mudanca',
                            'st_decisao_mudanca',
                            'st_execucao_mudanca',
                            'st_decisao_mudanca_desc'=>new Zend_Db_Expr("CASE gm.st_decisao_mudanca WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_ANALIZADO_ACEITO')."'
                                                                                                    WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_ANALIZADO_NAO_ACEITO')."'
                                                                                                    ELSE '".Base_Util::getTranslator('L_SQL_PENDENTE')."' END")),
                      $this->_schema);
        $select->join(array('p'=>KT_S_PROJETO),
                      '(gm.cd_projeto = p.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $diaFinalMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        $select->where("gm.dt_gerencia_mudanca between '{$ano}-{$mes}-01' and '{$ano}-{$mes}-{$diaFinalMes}'");
        $select->where('gm.cd_item_controle_baseline = ?', $itemControleBaseline, Zend_Db::INT_TYPE);
        $select->order('st_decisao_mudanca_desc');

        return $select;
    }

}


