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

class BaselineItemControle extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_BASELINE_ITEM_CONTROLE;
	protected $_primary  = array('cd_projeto','dt_baseline','cd_item_controle_baseline','cd_item_controlado','dt_versao_item_controlado');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function init()
    {
        parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));
    }

	public function getDadosBaselineItemControleRequisito( $cd_projeto )
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('bic'=>$this->_name),
                      array('cd_requisito'=>'cd_item_controlado',
                            'cd_projeto',
                            'dt_versao_requisito'=>'dt_versao_item_controlado'),
                      $this->_schema);
        $select->join(array('req'=>KT_S_REQUISITO),
                      '(req.cd_requisito = bic.cd_item_controlado) AND (req.dt_versao_requisito = bic.dt_versao_item_controlado)',
                      array('tx_requisito',
                            'tx_descricao_requisito',
                            'ni_versao_requisito',
                            'st_tipo_requisito'      =>new Zend_Db_Expr("CASE req.st_tipo_requisito WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_NAO_FUNCIONAL')."'
                                                                                                             ELSE '".Base_Util::getTranslator('L_SQL_FUNCIONAL')."' END"),
                            'st_requisito'           =>new Zend_Db_Expr("CASE req.st_requisito WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_APROVADO')."'
                                                                                               WHEN 'C' THEN '".Base_Util::getTranslator('L_SQL_COMPLETADO')."'
                                                                                                        ELSE '".Base_Util::getTranslator('L_SQL_SUBMETIDO')."' END"),
                            'st_prioridade_requisito'=>new Zend_Db_Expr("CASE req.st_prioridade_requisito WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ALTA')."'
                                                                                                          WHEN 'B' THEN '".Base_Util::getTranslator('L_SQL_BAIXA')."'
                                                                                                                   ELSE '".Base_Util::getTranslator('L_SQL_MEDIA')."' END"),
                            'st_fechamento_requisito'=>new Zend_Db_Expr("CASE WHEN req.st_fechamento_requisito IS NULL THEN '".Base_Util::getTranslator('L_SQL_ABERTO')."'
                                                                                                                       ELSE '".Base_Util::getTranslator('L_SQL_FECHADO')."' END")),
                      $this->_schema);
        $select->join(array('b'=>KT_S_BASELINE),
                      '(b.cd_projeto = bic.cd_projeto) AND (b.dt_baseline = bic.dt_baseline)',
                      array(),
                      $this->_schema);
        $select->where('bic.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('bic.cd_item_controle_baseline = ?', ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REQUISITO)
               ->where('b.st_ativa IS NOT NULL');
        $select->order('req.tx_requisito');

        return $this->fetchAll($select)->toArray();
	}

	public function getDadosBaselineItemControleRegraDeNegocio( $cd_projeto )
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('bic'=>$this->_name),
                      array('cd_regra_negocio'        =>'cd_item_controlado',
                            'dt_regra_negocio'        =>'dt_versao_item_controlado',
                            'cd_projeto_regra_negocio'=>'cd_projeto'),
                      $this->_schema);
        $select->join(array('rn'=>KT_S_REGRA_NEGOCIO),
                      '(rn.cd_regra_negocio = bic.cd_item_controlado) AND (rn.dt_regra_negocio = bic.dt_versao_item_controlado)',
                      array('tx_regra_negocio',
                            'tx_descricao_regra_negocio',
                            'st_regra_negocio',
                            'ni_versao_regra_negocio',
                            'st_fechamento_regra_negocio',
                            'dt_fechamento_regra_negocio',
                            'dt_regra_negocio',
                            'st_regra_negocio_desc'           =>new Zend_Db_Expr("CASE rn.st_regra_negocio WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ATIVA')."'
                                                                                                                    ELSE '".Base_Util::getTranslator('L_SQL_INATIVA')."' END"),
                            'st_fechamento_regra_negocio_desc'=>new Zend_Db_Expr("CASE WHEN rn.st_fechamento_regra_negocio IS NULL THEN '".Base_Util::getTranslator('L_SQL_ABERTA')."'
                                                                                                                                   ELSE '".Base_Util::getTranslator('L_SQL_FECHADA')."' END")),
                      $this->_schema);
        $select->join(array('b'=>KT_S_BASELINE),
                      '(b.cd_projeto = bic.cd_projeto) AND (b.dt_baseline = bic.dt_baseline)',
                      array(),
                      $this->_schema);
        $select->where('bic.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('bic.cd_item_controle_baseline = ?', ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO, Zend_Db::INT_TYPE)
               ->where('b.st_ativa IS NOT NULL');
        $select->order('rn.tx_regra_negocio');

        return $this->fetchAll($select)->toArray();
	}
}